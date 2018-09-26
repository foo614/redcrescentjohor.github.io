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
                        @click-event="onClickEvent">
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
            <v-card>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card>
                            <v-card-title primary-title>
                                <div class="headline">Options</div>
                            </v-card-title>
                            <v-card-text>
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
                        </v-card>
                    </v-flex>
                    <v-flex xs12 class="mt-2" v-if="eventDetail">
                        <v-card>
                            <v-toolbar
                                dark
                                :style="'background-image:url(/img/'+eventDetail.cover_img+')'"
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
                                    <v-list-tile @click="">
                                        <v-list-tile-title>Delete</v-list-tile-title>
                                    </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </v-toolbar>
                            <v-card-text>
                                <!-- <div>
                                    <span>Title: {{eventDetail.title}}</span><br>
                                    <span>Location: {{eventDetail.address}}</span><br>
                                    <span>Start: {{eventDetail.startDate}}</span><br>
                                    <span>End: {{eventDetail.endDate}}</span>
                                </div>
                                <div>{{message}}</div> -->
                                <v-list dense>

                                    <v-list-tile v-if="eventDetail.startDate">
                                        <v-list-tile-avatar>
                                        <v-icon>access_time</v-icon>
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title>{{ eventDetail.startDate }}</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile v-if="eventDetail.endDate">
                                        <v-list-tile-avatar>
                                        <v-icon>access_time</v-icon>
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title>{{ eventDetail.endDate }}</v-list-tile-title>
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
                        <v-card-title primary-title>
                            <div class="headline">Upcoming events</div>
                        </v-card-title>
                        <v-card-text style="height:355px; overflow: auto;">
                            <v-list two-line subheader>
                                <v-list-tile v-for="upcomingEvent in upcomingEvents" v-model="item.active" :key="upcomingEvent.title"
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
                    </v-flex>
                </v-layout>
            </v-card>
        </v-flex>
        <v-fab-transition>
            <v-btn color="primary" dark @click="addDialog = true" bottom fixed right fab>
                <v-icon>add</v-icon>
            </v-btn>
        </v-fab-transition>
        <v-dialog v-model="addDialog" max-width="600px" persistent>
            <v-form v-model="valid" ref="form" @submit.prevent="addEvent">
                <v-card>
                    <v-card-title>
                       {{readEvent ? 'View ' : editEvent ? 'Edit ' : 'Add '}} Event
                    </v-card-title>
                    <v-card-text>
                        <!-- title -->
                        <v-layout>
                            <v-flex xs12 sm12>
                                <v-text-field v-model="item.name" label="Title" prepend-icon="event_note" :rules="[v => !!v || 'Title is required']"
                                    required :readonly="!readEvent ? false : true"></v-text-field>
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
                                                    v-model="item.address" :readonly="!readEvent ? false : true"/>
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
                                <v-menu ref="date_menu2" :close-on-content-click="false" v-model="date_menu2"
                                    :nudge-right="40" :return-value.sync="item.end_date" lazy transition="scale-transition"
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
                                    <span slot="badge" v-if="preview && !readEvent" @click="item.cover_img = null; preview= null">x</span>
                                    <v-avatar tile class="elevation-7 v-avatar-custom--size">
                                        <v-img lazy-src aspect-ratio="2" v-if="item.cover_img || preview" 
                                        :src="readEvent || editEvent ? '/img/'+preview : preview ? preview : null"
                                            alt="profile_image"></v-img>
                                        <v-tooltip bottom v-if="!preview">
                                            <input type="file" ref="cover_img" v-on:change="handleFile" style="display:none">
                                            <v-icon large @click="pickFile" slot="activator">
                                                image
                                            </v-icon>
                                            <span>Upload Event Cover Photo</span>
                                        </v-tooltip>
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
        <v-snackbar :absolute="saveSnackbar.absolute" :right="saveSnackbar.right" :top="saveSnackbar.top" :color="saveSnackbar.color"
            v-model="saveSnackbar.snackbar">
            {{ saveSnackbar.text }}
            <v-btn dark flat @click.native="saveSnackbar.snackbar = false">
                <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>
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
                    name: "",
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
                    post_id: ''
                },
                useDefaultTheme: true,
                useHolidayTheme: false,
                events: [],
                currentDate: "",
                optionalEnd: false,
                valid: false,
                sending: false,
                saveSnackbar: {},
                upcomingEvents: [],
                //ltr do edit event
                editEvent: false,
                readEvent: false
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
            start: {
                get: function () {
                    return this.item.start = moment.utc((this.item.start_date + " " + this.item.start_time)).format(
                        "YYYY-MM-DD HH:mm:ss");
                }
            },
            end: {
                get: function () {
                    return this.item.end = moment.utc((this.item.end_date + " " + this.item.end_time)).format(
                        "YYYY-MM-DD HH:mm:ss");
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

                this.item.address = place.name;
                this.item.map_lat = lat;
                this.item.map_lng = lng;
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
                this.item.name = data.title
                this.item.address = data.address
                this.preview = data.cover_img
                this.item.start_date = data.startDate ? moment(data.startDate).format("YYYY-MM-DD") : null
                this.item.start_time = data.startDate ? moment(data.startDate).format("HH:mm:ss") : null
                this.item.end_date = data.endDate ? moment(data.endDate).format("YYYY-MM-DD") : null
                this.item.end_time = data.endDate ? moment(data.endDate).format("HH:mm:ss") : null
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
                            fetch("../api/post", {
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
                                    this.saveSnackbar = {
                                        snackbar: true,
                                        color: 'success',
                                        text: this.item.name + ' added',
                                        absolute: true,
                                        right: true,
                                        top: true
                                    }
                                    this.$refs.form.reset()
                                    this.fetchEvents()
                                    this.fetchUpcomingEvents()
                                })
                                .catch(err => console.log(err));
                        }, 2000);
                    }
                }else{
                    if (this.$refs.form.validate()) {
                        this.sending = true;
                        setTimeout(() => {
                            fetch("../api/post", {
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
                                    this.saveSnackbar = {
                                        snackbar: true,
                                        color: 'success',
                                        text: this.item.name + ' added',
                                        absolute: true,
                                        right: true,
                                        top: true
                                    }
                                    this.$refs.form.reset()
                                    this.fetchEvents()
                                    this.fetchUpcomingEvents()
                                })
                                .catch(err => console.log(err));
                        }, 2000);
                    }
                }
            },
            closeDialog()
            {
                this.addDialog=false
                this.$refs.form.reset()
                this.readEvent=false
                this.preview=null
                this.item.address = null
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
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
        background-color: gold;
    }

    .theme-default .cv-event:nth-of-type(even) {
        background-color: lightblue;
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
</style>