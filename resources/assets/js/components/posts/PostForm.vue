<template>
    <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
        <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">{{$route.name == "editPost" ? 'Edit' : 'Add' }} Post</div>
            </v-card-title>
                <v-container fluid grid-list-lg>
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-text-field
                            v-model="item.title"
                            label="Title"
                            prepend-icon="event_note"
                            :rules="[v => !!v || 'Title is required']"
                            required
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm4>
                        <v-select
                        v-model="item.post_type_id"
                        :items="items"
                        label="Post Type"
                        item-text="name"
                        item-value="id"
                        prepend-icon="event_note"
                        :rules="[v => !!v || 'You must select to continue!']"
                        ></v-select>
                    </v-flex>
                    <v-flex xs12 sm2>
                        <v-switch
                            :label="`Post ${item.status === 1 || item.status === true ? 'Active' : (item.status === 0 || item.status === false) ? 'Inactive' : (item.status === 2 ? 'Pending' : '') }`"
                            v-model="item.status"
                        ></v-switch>
                    </v-flex>
                    <v-layout row wrap v-show="item.post_type_id === 1">
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
                                            <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Location</label>
                                            <input ref="autocomplete" 
                                            placeholder="Search" 
                                            class="search-location"
                                            v-model="item.address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </v-flex>
                        <!-- start date picker -->
                        <v-flex xs12 sm3>
                            <v-menu
                                ref="date_menu1"
                                :close-on-content-click="false"
                                v-model="date_menu1"
                                :nudge-right="40"
                                :return-value.sync="item.start_date"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.start_date"
                                label="Start Date"
                                prepend-icon="event"
                                readonly
                                ></v-text-field>
                                <v-date-picker 
                                    v-model="item.start_date"
                                    no-title 
                                    scrollable 
                                    @input="$refs.date_menu1.save(item.start_date)"
                                    :min= currentDate>
                                <v-spacer></v-spacer>
                                </v-date-picker>
                            </v-menu>
                        </v-flex>
                        <!-- start time picker -->
                        <v-flex xs-12 sm3>
                            <v-menu
                                ref="menu1"
                                :close-on-content-click="false"
                                v-model="time_menu1"
                                :nudge-right="40"
                                :return-value.sync="item.start_time"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.start_time"
                                label="Start time"
                                prepend-icon="access_time"
                                persistent-hint
                                readonly
                                ></v-text-field>
                                <v-time-picker
                                v-if="time_menu1"
                                v-model="item.start_time"
                                @change="$refs.menu1.save(item.start_time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs12 sm6><v-btn @click="optionalEnd = !optionalEnd">{{ optionalEnd ? 'REMOVE ' : 'ADD ' }}End DateTime</v-btn></v-flex>
                        <!-- end date picker -->
                        <v-flex xs12 sm3 v-show="optionalEnd">
                            <v-menu
                                ref="date_menu2"
                                :close-on-content-click="false"
                                v-model="date_menu2"
                                :nudge-right="40"
                                :return-value.sync="item.end_date"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.end_date"
                                label="End Date"
                                prepend-icon="event"
                                readonly
                                ></v-text-field>
                                <v-date-picker 
                                    v-model="item.end_date" 
                                    no-title 
                                    scrollable 
                                    @input="$refs.date_menu2.save(item.end_date)"
                                    :min= item.start_date>
                                <v-spacer></v-spacer>
                                </v-date-picker>
                            </v-menu>
                        </v-flex>
                        <!-- end time picker -->
                        <v-flex xs12 sm3 v-show="optionalEnd">
                            <v-menu
                                ref="menu"
                                :close-on-content-click="false"
                                v-model="time_menu2"
                                :nudge-right="40"
                                :return-value.sync="item.end_time"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.end_time"
                                label="End time"
                                prepend-icon="access_time"
                                persistent-hint
                                readonly
                                ></v-text-field>
                                <v-time-picker
                                v-if="time_menu2"
                                v-model="item.end_time"
                                @change="$refs.menu.save(item.end_time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-text-field v-model="item.start" v-show="false">{{start}}</v-text-field>
                    <v-text-field v-model="item.end" v-show="false">{{end}}</v-text-field>
                    <v-flex xs12 sm12 class="mt-2">
                        <div style="display: flex;">
                            <v-tooltip bottom>
                                <v-icon slot="activator">description</v-icon>
                                <span>Content</span>
                            </v-tooltip>
                            <ckeditor style="width:100%" height="180px" class="ml-2" v-model="item.body" language="zh" extraplugins="divarea"/>
                        </div>
                    </v-flex>
                    <v-flex xs12 sm12>
                        <v-icon>image</v-icon>
                        <v-badge overlap>
                            <span slot="badge" v-if="(preview || item.cover_img)" @click="item.cover_img = null; preview= null">x</span>
                            <v-avatar tile class="elevation-7 v-avatar-custom--size">
                                <v-img lazy-src aspect-ratio="2.75" v-if="item.cover_img || preview" 
                                :src="item.cover_img && !preview ? '/img/'+item.cover_img : preview ? preview : null"
                                    alt="profile_image"></v-img>
                                <v-tooltip bottom v-if="!preview && !item.cover_img">
                                    <input type="file" ref="cover_img" v-on:change="handleFile" style="display:none">
                                    <v-icon large @click="pickFile" slot="activator">
                                        image
                                    </v-icon>
                                    <span>Upload Cover Photo</span>
                                </v-tooltip>
                            </v-avatar>
                        </v-badge>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" type="submit">Submit</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
import moment from 'moment';
export default {
    mounted() {
        this.currentDate = moment().format('YYYY-MM-DD');
        this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
        );
        this.autocomplete.setComponentRestrictions(
            {'country': ['my', 'sg']});
        this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace()
            let lat = place.geometry.location.lat()
            let lng = place.geometry.location.lng()
            let place_id = place.place_id
            let formatted_address = place.formatted_address

            this.item.address = place.name
            this.item.map_lat = lat
            this.item.map_lng = lng
            this.item.place_id = place_id
            this.item.formatted_address = formatted_address
        })
        if(this.$route.name === "editPost"){
            let app = this;
            let id = this.$route.params.id;
            axios.get('/api/post/' + id)
            .then(function (res) {
                app.item = res.data;
                app.item.post_id = res.data.id;
                if(app.item.event){
                    app.item.address = res.data.event.address;
                    app.item.start_date = res.data.event.start ? moment(res.data.event.start).format("YYYY-MM-DD") : null
                    app.item.end_date = res.data.event.end ? moment(res.data.event.end).format("YYYY-MM-DD") : null
                    app.item.start_time = res.data.event.start ? moment(res.data.event.start).format("HH:mm") : null
                    app.item.end_time = res.data.event.end ? moment(res.data.event.end).format("HH:mm") : null
                }
            })
            .catch(function () {
                this.$toasted.error("Something wrong...", {icon:"error"})
            });
        }
    },
    data () {
    return {
        items:[],
        sending: false,
        valid: true,
        item: {
            post_id:'',
            id: "",
            title: "",
            post_type_id: "",
            status: true,
            body: "",
            address: "",
            map_lng:"",
            map_lat:"",
            start:null,
            end:null,
            start_date: moment().format("YYYY-MM-DD"),
            end_date: this.optionalEnd ? moment().add(1, 'days').format("YYYY-MM-DD") : null,
            start_time: moment().format("HH:mm"),
            end_time: this.optionalEnd ? moment().add(3, 'hours').format("HH:mm") : null,
            cover_img:null,
            place_id: null,
            formatted_address: null
        },
        preview: '',
        currentDate:"",
        optionalEnd:false,
        date_menu1:false,
        date_menu2:false,
        time_menu1:false,
        time_menu2:false,
    };
    },
    computed: {
        start: function(){
            var startDate = moment(this.item.start_date, "YYYY-MM-DD")
            var startTime = moment(this.item.start_time, "HH:mm")
            if(startDate.isValid() && startTime.isValid()){
                return this.item.start = moment(startDate._i + ' ' + startTime._i+":00")._i
            }
        },
        end: function(){
            var endDate = moment(this.item.end_date, "YYYY-MM-DD")
            var endTime = moment(this.item.end_time, "HH:mm")
            if(endDate.isValid() && endTime.isValid()){
                return this.item.end = moment(endDate._i + ' ' + endTime._i+":00")._i
            }
        }
    },
    created(){
        this.fetchPostCategories()
    },
    methods:{
        fetchPostCategories() {
            axios.get("/api/postCategories").then(res => {
                this.items = res.data
            })
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
        saveItem(){
            if (this.$refs.form.validate()){
                this.sending = true;
                // CKEDITOR.removeAllListeners();
                setTimeout(()=> {
                    fetch("/api/post", {
                    method: this.$route.name == 'createPost' ? "post" : "put",
                    body: JSON.stringify(this.item),
                    headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.sending = false
                        let currentPage = this.$route.name
                        this.$router.push({ path: '/posts' }, ()=> {
                            this.$toasted.success(this.item.title + (currentPage === 'createPost' ? ' added' : ' updated') , {icon:"check"})
                        })
                    })
                    .catch(err => console.log(err))
                }, 1500)
            }
        }
    }
}
</script>

<style>
.v-avatar-custom--size {
    height: 200px !important;
    width: 600px !important;
}
</style>
