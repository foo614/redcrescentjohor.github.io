<template>
    <v-layout>
        <v-flex xs12 sm8>
            <div style="display: flex; font-family: Calibri, sans-serif; min-height: 80vh;">
                <div class="calendar-parent">
                    <calendar-view :events="events" :show-date="showDate" :time-format-options="{hour: 'numeric', minute:'2-digit'}"
                        :enable-drag-drop="false" 
                        :disable-past="disablePast" 
                        :disable-future="disableFuture"
                        :show-event-times="showEventTimes" 
                        :display-period-uom="displayPeriodUom" 
                        :display-period-count="displayPeriodCount"
                        :starting-day-of-week="startingDayOfWeek" 
                        :class="themeClasses" 
                        :on-period-change="periodChanged"
                        @drop-on-date="onDrop" 
                        @click-date="onClickDay" 
                        @click-event="onClickEvent"
                        style="display: flex; flex-direction: column; flex-grow: 1;">
                        <calendar-view-header 
                            slot="header" 
                            slot-scope="{ headerProps }" 
                            :header-props="headerProps"
                            @input="setShowDate" />
                    </calendar-view>
                </div>
            </div>
        </v-flex>

        <v-flex xs12 sm4 class="ml-3">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card>
                        <v-card-title primary-title>
                            <div class="headline">Options</div>
                            <v-spacer></v-spacer>
                            <v-btn icon @click="showOptions = !showOptions">
                                <v-icon>{{ showOptions ? 'keyboard_arrow_up' : 'keyboard_arrow_down' }}</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-slide-y-transition>
                        <v-card-text v-show="showOptions">
                            <v-container grid-list-xl>
                                <v-layout wrap>
                                    <v-flex xs12 sm6 d-flex>
                                        <v-select :items="['month','week','year']" label="Period UOM" v-model="displayPeriodUom"></v-select>
                                    </v-flex>              
                                    <v-flex xs12 sm6 d-flex>
                                        <v-select :items="[1,2,3]" label="Period Count" v-model="displayPeriodCount"></v-select>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        </v-slide-y-transition>
                    </v-card>
                </v-flex>
                <v-flex xs12 class="mt-2">
                    <v-card v-if="!eventDetail">
                        <v-card-title primary-title style="justify-content: center;">
                            <div style="text-align: center;">
                                <v-icon 
                                    size="60px"
                                    color="rgba(0,0,0,.26)">event</v-icon>
                                <div>Click any event to check details.</div>
                            </div>
                        </v-card-title>
                    </v-card>
                    <v-card v-if="eventDetail">
                        <v-toolbar
                            dark
                            :style="eventDetail.cover_img ? 'background-image:url(/img/'+eventDetail.cover_img+')' : ''"
                            class="event-cover--image"
                            extended
                        >
                            <v-toolbar-title slot="extension" class="white--text">{{eventDetail.title | capitalize}}</v-toolbar-title>
                            <v-btn
                                color="secondary"
                                small absolute bottom left fab icon
                                @click="fetchEventData(eventDetail, 'edit')">
                                <v-icon>edit</v-icon>
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-menu bottom left>
                                <v-btn icon slot="activator">
                                    <v-icon>more_vert</v-icon>
                                </v-btn>
                                <v-list light>
                                <v-list-tile @click="deleteEvent(eventDetail)">
                                    <v-list-tile-title>Delete</v-list-tile-title>
                                </v-list-tile>
                                </v-list>
                            </v-menu>
                        </v-toolbar>
                        <v-card-text>
                            <v-list dense>
                                <v-list-tile v-if="eventDetail.startDate">
                                    <v-list-tile-avatar>
                                    <v-icon>access_time</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ eventDetail.startDate | timeFormat }}</v-list-tile-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile v-if="eventDetail.endDate">
                                    <v-list-tile-avatar>
                                    <v-icon>access_time</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ eventDetail.endDate | timeFormat }}</v-list-tile-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile v-if="eventDetail.address">
                                    <v-list-tile-avatar>
                                    <v-icon>map</v-icon>
                                    </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title>{{ eventDetail.address }}</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>

                                </v-list>
                            </v-card-text>
                        </v-card>
                    </v-flex>

                    <v-flex xs12 class="mt-2">
                        <v-card v-show="upcomingEvents.length === 0 || !upcomingEvents">
                            <v-card-title primary-title style="justify-content: center;">
                                <div style="text-align: center;">
                                    <v-icon 
                                        size="60px"
                                        color="rgba(0,0,0,.26)">event</v-icon>
                                    <div>Create an event.</div>
                                </div>
                            </v-card-title>
                            <v-card-actions style="justify-content: center;">
                                <v-btn color="primary" @click="addDialog = true">Create event</v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-card v-show="upcomingEvents.length > 0">
                            <v-card-title primary-title>
                                <div class="headline">Upcoming events</div>
                            </v-card-title>
                            <v-card-text style="height:220px; overflow: auto;">
                                <v-list two-line subheader>
                                    <v-list-tile v-for="upcomingEvent in upcomingEvents" :key="upcomingEvent.title"
                                        no-action>
                                        <v-list-tile-content>
                                            <v-list-tile-title>{{ upcomingEvent.title }} ( {{upcomingEvent.address}} )</v-list-tile-title>
                                            <v-list-tile-sub-title>{{ upcomingEvent.startDate }}</v-list-tile-sub-title>
                                        </v-list-tile-content>

                                        <v-list-tile-action>
                                            <v-btn icon ripple>
                                                <v-icon color="grey lighten-1" @click="fetchEventData(upcomingEvent, 'read')">info</v-icon>
                                            </v-btn>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                </v-list>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
        </v-flex>
        <v-fab-transition>
            <v-btn color="primary" dark @click="addDialog = true" bottom fixed right fab>
                <v-icon>add</v-icon>
            </v-btn>
        </v-fab-transition>
        <v-dialog v-model="addDialog" max-width="600px" persistent>
            <v-form v-model="valid" ref="form" @submit.prevent="addEvent">
                <v-card>
                    <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
                    <v-card-title>
                       {{readEvent ? 'View ' : editEvent ? 'Edit ' : 'Add '}} Event
                    </v-card-title>
                    <v-card-text>
                        <!-- title -->
                        <v-layout>
                            <v-flex xs12 sm12>
                                <v-text-field v-model="item.title" label="Title" prepend-icon="event_note" :rules="[v => !!v || 'Title is required']"
                                    required :readonly="!readEvent ? false : true" ></v-text-field>
                            </v-flex>
                        </v-layout>
                        <!-- address -->
                        <v-layout>
                            <v-flex xs12 sm12>
                                <div class="v-input v-text-field theme--light">
                                    <div class="v-input__prepend-outer">
                                        <div class="v-input__icon v-input__icon--prepend">
                                            <i aria-hidden="true" class="v-icon material-icons theme--light">map</i>
                                        </div>
                                    </div>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <div class="v-text-field__slot">
                                                <label aria-hidden="true" class="v-label v-label--active theme--light"
                                                    style="left: 0px; right: auto; position: absolute;">Location</label>
                                                <input :ref="!readEvent ? 'autocomplete' : ''" placeholder="Search" class="search-location"
                                                    v-model="item.address" :disabled="!readEvent ? false : true"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </v-flex>
                        </v-layout>
                        <!-- start date picker -->
                        <v-layout>
                            <v-flex xs12 sm4>
                                <v-menu ref="date_menu1" 
                                    :close-on-content-click="false"
                                    v-model="date_menu1"
                                    :nudge-right="40" 
                                    :return-value.sync="item.start_date" 
                                    lazy transition="scale-transition"
                                    offset-y 
                                    full-width 
                                    min-width="290px">
                                    <v-text-field slot="activator" v-model="item.start_date" label="Start Date"
                                        prepend-icon="event" readonly></v-text-field>
                                    <v-date-picker 
                                        v-model="item.start_date" 
                                        no-title scrollable 
                                        @input="$refs.date_menu1.save(item.start_date)"
                                        :min="currentDate"
                                        :readonly="!readEvent ? false : true" >
                                        <v-spacer></v-spacer>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <!-- start time picker -->
                            <v-flex xs-12 sm4>
                                <v-menu ref="menu1" :close-on-content-click="false" v-model="time_menu1" :nudge-right="40"
                                    :return-value.sync="item.start_time" lazy transition="scale-transition" offset-y
                                    full-width max-width="290px" min-width="290px">
                                    <v-text-field slot="activator" v-model="item.start_time" label="Start time"
                                        prepend-icon="access_time" readonly></v-text-field>
                                    <v-time-picker v-if="time_menu1" v-model="item.start_time" @change="$refs.menu1.save(item.start_time)" :readonly="!readEvent ? false : true"></v-time-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex xs12 sm4>
                                <v-btn @click="optionalEnd = !optionalEnd" v-show="(!item.end_date || !item.end_time) && !readEvent">{{
                                    optionalEnd ? 'x ' : '+ ' }}End DateTime</v-btn>
                            </v-flex>
                        </v-layout>
                        <v-layout>
                            <!-- end date picker -->
                            <v-flex xs12 sm4 v-show="optionalEnd || item.end_date">
                                <v-menu ref="date_menu2" 
                                    :close-on-content-click="false" 
                                    v-model="date_menu2"
                                    :nudge-right="40"
                                    :return-value.sync="item.end_date" 
                                    lazy transition="scale-transition"
                                    offset-y full-width min-width="290px">
                                    <v-text-field slot="activator" v-model="item.end_date" label="End Date"
                                        prepend-icon="event" readonly></v-text-field>
                                    <v-date-picker v-model="item.end_date" no-title scrollable @input="$refs.date_menu2.save(item.end_date)"
                                        :min="item.start_date" :readonly="!readEvent ? false : true">
                                        <v-spacer></v-spacer>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <!-- end time picker -->
                            <v-flex xs12 sm4 v-show="optionalEnd || item.end_time">
                                <v-menu ref="menu" :close-on-content-click="false" v-model="time_menu2" :nudge-right="40"
                                    :return-value.sync="item.end_time" lazy transition="scale-transition" offset-y
                                    full-width max-width="290px" min-width="290px">
                                    <v-text-field slot="activator" v-model="item.end_time" label="End time"
                                        prepend-icon="access_time" readonly></v-text-field>
                                    <v-time-picker v-if="time_menu2" v-model="item.end_time" @change="$refs.menu.save(item.end_time)" :readonly="!readEvent ? false : true"></v-time-picker>
                                </v-menu>
                            </v-flex>
                        </v-layout>
                        <v-layout>
                            <v-flex xs12 sm12>
                                <v-icon>image</v-icon>
                                <v-badge overlap>
                                    <span slot="badge" v-if="(preview || item.cover_img) && !readEvent" @click="item.cover_img = null; preview= null">x</span>
                                    <v-avatar tile class="elevation-7 v-avatar-custom--size">
                                        <v-img lazy-src aspect-ratio="2" v-if="item.cover_img || preview" 
                                        :src="readEvent || (editEvent && item.cover_img != null && !preview) ? '/img/'+item.cover_img : preview ? preview : null"
                                            alt="profile_image"></v-img>
                                        <v-tooltip bottom v-if="!preview && !item.cover_img && !readEvent">
                                            <input type="file" ref="cover_img" v-on:change="handleFile" style="display:none">
                                            <v-icon large @click="pickFile" slot="activator">
                                                image
                                            </v-icon>
                                            <span>Upload Event Cover Photo</span>
                                        </v-tooltip>
                                        <span v-if="readEvent && !item.cover_img">No cover image</span>
                                    </v-avatar>
                                </v-badge>
                            </v-flex>
                        </v-layout>
                        <v-text-field v-model="item.start" style="display:none">{{start}}</v-text-field>
                        <v-text-field v-model="item.end" style="display:none">{{end}}</v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="primary" flat @click="closeDialog">Close</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" flat type="submit" v-show="!readEvent">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
    </v-layout>
</template>
<script>
    // For testing against the published version
    //import CalendarView from "vue-simple-calendar"
    import {
        CalendarView,
        CalendarViewHeader,
        CalendarMathMixin,
    } from "vue-simple-calendar"
    import moment from 'moment';
    require("vue-simple-calendar/static/css/default.css")
    require("vue-simple-calendar/static/css/holidays-us.css")
    export default {
        components: {
            CalendarView,
            CalendarViewHeader,
        },
        mixins: [CalendarMathMixin],
        data() {
            return {
                /* Show the current month, and give it some fake events to show */
                date_menu1: false,
                date_menu2: false,
                time_menu1: false,
                time_menu2: false,
                addDialog: false,
                showDate: new Date(),
                message: "",
                eventDetail: null,
                startingDayOfWeek: 0,
                disablePast: false,
                disableFuture: false,
                displayPeriodUom: "month",
                displayPeriodCount: 1,
                showEventTimes: true,
                preview: '',
                item: {
                    cover_img: '',
                    title: "",
                    body: null,
                    status: 1,
                    start: null,
                    end: null,
                    start_date: null,
                    end_date: null,
                    start_time: null,
                    end_time: null,
                    address: "",
                    post_type_id: 1,
                    post_id: '',
                    place_id: null,
                    formatted_address: null
                },
                useDefaultTheme: true,
                useHolidayTheme: false,
                events: [],
                currentDate: "",
                optionalEnd: false,
                valid: false,
                sending: false,
                upcomingEvents: [],
                //ltr do edit event
                editEvent: false,
                readEvent: false,
                showOptions: false
            }
        },
        computed: {
            userLocale() {
                return this.getDefaultBrowserLocale
            },
            dayNames() {
                return this.getFormattedWeekdayNames(this.userLocale, "long", 0)
            },
            themeClasses() {
                return {
                    "theme-default": this.useDefaultTheme,
                    "holiday-us-traditional": this.useHolidayTheme,
                    "holiday-us-official": this.useHolidayTheme,
                }
            },
            start: function(){
                var startDate = moment(this.item.start_date, "YYYY-MM-DD")
                var startTime = moment(this.item.start_time, "HH:mm")
                if(startDate.isValid() && startTime.isValid()){
                    return this.item.start = moment(startDate._i + ' ' + startTime._i)._i
                }
            },
            end: function(){
                var endDate = moment(this.item.end_date, "YYYY-MM-DD")
                var endTime = moment(this.item.end_time, "HH:mm")
                if(endDate.isValid() && endTime.isValid()){
                    return this.item.end = moment(endDate._i + ' ' + endTime._i)._i
                }
            }
        },
        mounted() {
            this.start_date = this.isoYearMonthDay(this.today())
            this.end_date = this.isoYearMonthDay(this.today())

            this.currentDate = moment().format('YYYY-MM-DD');
            this.autocomplete = new google.maps.places.Autocomplete(
                (this.$refs.autocomplete),
                // {types: ['geocode']}
            );
            this.autocomplete.setComponentRestrictions({
                'country': ['my', 'sg']
            });
            this.autocomplete.addListener('place_changed', () => {
                let place = this.autocomplete.getPlace();
                let lat = place.geometry.location.lat();
                let lng = place.geometry.location.lng();
                let place_id = place.place_id
                let formatted_address = place.formatted_address

                this.item.address = place.name;
                this.item.map_lat = lat;
                this.item.map_lng = lng;
                this.item.place_id = place_id
                this.item.formatted_address = formatted_address
            });
            this.fetchUpcomingEvents();

            this.fetchEvents();
        },
        methods: {
            fetchUpcomingEvents(){
                axios.get("/api/posts/showUpcomingEvent").then(res => {
                    this.upcomingEvents = res.data;
                });
            },
            fetchEvents() {
                axios.get("/api/posts/showEvent").then(res => {
                    this.events = res.data;
                });
            },
            fetchEventData(data, action){
                this.item.post_id = data.id;
                this.addDialog = true
                action == 'read' ? this.readEvent = true : this.editEvent = true;
                this.item.title = data.title
                this.item.address = data.address
                this.item.cover_img = data.cover_img
                this.item.start_date = data.startDate ? moment(data.startDate).format("YYYY-MM-DD") : null
                this.item.start_time = data.startDate ? moment(data.startDate).format("HH:mm") : null
                this.item.end_date = data.endDate ? moment(data.endDate).format("YYYY-MM-DD") : null
                this.item.end_time = data.endDate ? moment(data.endDate).format("HH:mm") : null
            },
            periodChanged(range) {
                //show date peroid filterd
                console.log(range)
            },
            thisMonth(d, h, m) {
                const t = new Date()
                return new Date(t.getFullYear(), t.getMonth(), d, h || 0, m || 0)
            },
            onClickDay(d) {
                this.message = `You clicked: ${d.toLocaleDateString()}`
            },
            onClickEvent(e) {
                this.eventDetail = e.originalEvent;
            },
            setShowDate(d) {
                this.message = `Changing calendar view to ${d.toLocaleDateString()}`
                this.showDate = d
            },
            onDrop(event, date) {
                this.message = `You dropped ${event.id} on ${date.toLocaleDateString()}`
                // Determine the delta between the old start date and the date chosen,
                // and apply that delta to both the start and end date to move the event.
                const eLength = this.dayDiff(event.startDate, date)
                event.originalEvent.startDate = this.addDays(event.startDate, eLength)
                event.originalEvent.endDate = this.addDays(event.endDate, eLength)
            },
            pickFile() {
                this.$refs.cover_img.click()
            },
            handleFile(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = e => {
                    vm.preview = e.target.result;
                    vm.item.cover_img = e.target.result;
                };
                reader.readAsDataURL(file);
                console.log(file);
            },
            addEvent() {
                if (this.editEvent === false) {
                    if (this.$refs.form.validate()) {
                        this.sending = true;
                        setTimeout(() => {
                            fetch("/api/post", {
                                    method: "post",
                                    body: JSON.stringify(this.item),
                                    headers: {
                                        "content-type": "application/json"
                                    }
                                })
                                .then(res => {
                                    this.$router.push('/posts/calendar')
                                })
                                .then(data => {
                                    this.sending = false
                                    this.addDialog = false
                                    this.$toasted.success(this.item.title +' added', {icon:"check"})
                                    this.$refs.form.reset()
                                    this.item.address = null
                                    this.item.cover_img = null
                                    this.eventDetail = null
                                    this.fetchEvents()
                                    this.fetchUpcomingEvents()
                                })
                                .catch(err => console.log(err));
                        }, 1500);
                    }
                }else{
                    if (this.$refs.form.validate()) {
                        this.sending = true;
                        setTimeout(() => {
                            fetch("/api/post", {
                                    method: "put",
                                    body: JSON.stringify(this.item),
                                    headers: {
                                        "content-type": "application/json"
                                    }
                                })
                                .then(res => {
                                    this.$router.push('/posts/calendar')
                                })
                                .then(data => {
                                    this.sending = false
                                    this.addDialog = false
                                    this.$toasted.success(this.item.title +' updated', {icon:"check"})
                                    this.editEvent = false
                                    this.preview = null
                                    this.$refs.form.reset()
                                    this.item.address = null
                                    this.item.cover_img = null
                                    this.eventDetail = null
                                    this.fetchEvents()
                                    this.fetchUpcomingEvents()
                                })
                                .catch(err => console.log(err));
                        }, 1500);
                    }
                }
            },
            closeDialog()
            {
                this.editEvent=false
                this.addDialog=false
                this.$refs.form.reset()
                this.readEvent=false
                this.preview=null
                this.item.address = null
                this.item.cover_img = null
            },
            deleteEvent(event){
                fetch(`/api/post/${event.id}`,{
                    method: "delete"
                })
                .then(res => this.$router.push('/posts/calendar'))
                .then(data => {
                    this.sending = false
                    this.$toasted.success(this.eventDetail.title +' deleted', {icon:"check"})
                    this.eventDetail = null
                    this.fetchEvents()
                    this.fetchUpcomingEvents()
                })
                .catch(err => console.log(err));
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            },
            timeFormat: function(value){
                if(!value) return ''
                return moment(value).format("YYYY-MM-DD HH:mm")
            }
        }
    }
</script>

<style>
    .theme-default .cv-day.outsideOfMonth {
        background-color: #fff;
    }

    .v-avatar-custom--size {
        height: 120px !important;
        width: 500px !important;
    }

    .theme-default .cv-event:nth-of-type(odd) {
        background-color: rgb(144, 202, 249);
    }

    .theme-default .cv-event:nth-of-type(even) {
        background-color: rgb(159, 168, 218);
    }

    .calendar-controls {
        margin-right: 1rem;
        min-width: 14rem;
        max-width: 14rem;
    }

    .calendar-parent {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        overflow-x: hidden;
        overflow-y: hidden;
        max-height: 80vh;
        background-color: white;
        width: 100%;
    }

    /* For long calendars, ensure each week gets sufficient height. The body of the calendar will scroll if needed */
    .cv-wrapper.period-month.periodCount-2 .cv-week,
    .cv-wrapper.period-month.periodCount-3 .cv-week,
    .cv-wrapper.period-year .cv-week {
        min-height: 6rem;
    }

    /* These styles are optional, to illustrate the flexbility of styling the calendar purely with CSS. */
    /* Add some styling for events tagged with the "birthday" class */
    .calendar .event.birthday {
        background-color: #e0f0e0;
        border-color: #d7e7d7;
    }

    .calendar .event.birthday::before {
        content: "\1F382";
        margin-right: 0.5em;
    }
    .event-cover--image{
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
    }
    .v-toolbar__extension>.v-toolbar__title{
        margin-left: 56px;
    }
    .cv-event{
        cursor: pointer;
    }
    .theme-default .cv-header, .theme-default .cv-header-day{
        background-color: #fff;

    }

    .calendar-parent{
        box-shadow: 0px 3px 3px -2px rgba(0,0,0,0.2), 0px 3px 4px 0px rgba(0,0,0,0.14), 0px 1px 8px 0px rgba(0,0,0,0.12) !important
    }
    .currentPeriod, .previousYear, .previousPeriod, .nextPeriod , .nextYear{
        align-items: center;
        border-radius: 2px;
        display: inline-flex;
        height: 36px;
        flex: 0 0 auto;
        font-size: 14px;
        font-weight: 500;
        justify-content: center;
        margin: 6px 8px;
        min-width: 88px;
        outline: 0;
        text-transform: uppercase;
        text-decoration: none;
        transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1), color 1ms;
        position: relative;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .previousYear, .previousPeriod, .nextPeriod , .nextYear {
        background: transparent;
        box-shadow: none !important;
        border-radius: 50%;
        justify-content: center;
        min-width: 0;
        width: 36px;
    }
    .periodLabel{
        color: #757575!important;
        font-size: 20px!important;
        font-weight: 500;
        line-height: 1!important;
        letter-spacing: .02em!important;
        font-family: Roboto,sans-serif!important;
    }
    .theme-default .cv-event .startTime, .theme-default .cv-event .endTime {
        font-weight: bold;
        color: #fff
    }

    .theme-default .cv-event{
        color: #fff
    }
</style>