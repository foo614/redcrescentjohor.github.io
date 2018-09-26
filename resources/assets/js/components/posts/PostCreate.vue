<template>
    <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="addItem">
                {{item}}
            <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">Add Post</div>
            </v-card-title>
                <v-container fluid grid-list-lg>
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-text-field
                            v-model="item.name"
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
                            :label="`Active Post? ${item.status === true ? 'On' : 'Off'}`"
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
                                readonly
                                ></v-text-field>
                                <v-time-picker
                                v-if="time_menu1"
                                v-model="item.start_time"
                                @change="$refs.menu1.save(item.start_time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs12 sm6><v-btn @click="optionalEnd = !optionalEnd" v-show="!item.end_date || !item.end_time">{{ optionalEnd ? 'REMOVE ' : 'ADD ' }}End DateTime</v-btn></v-flex>
                        <!-- end date picker -->
                        <v-flex xs12 sm3 v-show="optionalEnd || item.end_date">
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
                        <v-flex xs12 sm3 v-show="optionalEnd || item.end_time">
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
                    <v-text-field v-model="item.start" style="display:none">{{start}}</v-text-field>
                    <v-text-field v-model="item.end" style="display:none">{{end}}</v-text-field>
                    <v-flex xs12 sm12>
                        <vue-ckeditor v-model="item.body"/>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" type="submit">Submit</v-btn>
            </v-card-actions>
        </v-card>
        <v-snackbar
            :absolute="saveSnackbar.absolute"
            :right="saveSnackbar.right"
            :top="saveSnackbar.top"
            :color="saveSnackbar.color" 
            v-model="saveSnackbar.snackbar">
            {{ saveSnackbar.text }}
            <v-btn dark flat @click.native="saveSnackbar.snackbar = false">
            <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>
    </v-form>
</template>

<script>
import moment from 'moment';
import VueCkeditor from 'vue-ckeditor2';
export default {
    components: { VueCkeditor},
    mounted() {
        this.currentDate = moment().format('YYYY-MM-DD');
        this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
            // {types: ['geocode']}
        );
        this.autocomplete.setComponentRestrictions(
            {'country': ['my', 'sg']});
        this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace();
            let lat = place.geometry.location.lat();
            let lng = place.geometry.location.lng();

            this.item.address = place.name;
            this.item.map_lat = lat;
            this.item.map_lng = lng;
        });
    },
    data () {
    return {
        items:[],
        sending: false,
        edit: false,
        valid: true,
        item: {
            post_id:'',
            id: "",
            name: "",
            post_type_id: "",
            status: true,
            body: "",
            address: "",
            map_lng:"",
            map_lat:"",
            start:null,
            end:null,
            start_date:null,
            end_date:null,
            start_time:null,
            end_time:null,
        },
        saveSnackbar:{},
        currentDate:"",
        optionalEnd:false,
        date_menu1:false,
        date_menu2:false,
        time_menu1:false,
        time_menu2:false,
    };
    },
    computed: {
        // start: function () {
        //     return this.item.start = moment.utc((this.item.start_date + " " + this.item.start_time)).format("YYYY-MM-DD HH:mm:ss");
        // },
        // end: function(){
        //     return this.item.end = moment.utc((this.item.end_date + " " + this.item.end_time)).format("YYYY-MM-DD HH:mm:ss");
        // }
        start:{
            get: function(){
                return this.item.start = moment.utc((this.item.start_date + " " + this.item.start_time)).format("YYYY-MM-DD HH:mm:ss");
            }
        },
        end:{
            get: function(){
                return this.item.end = moment.utc((this.item.end_date + " " + this.item.end_time)).format("YYYY-MM-DD HH:mm:ss");
            }
        }
    },
    created(){
        this.fetchPostCategories();
    },
    methods:{
        fetchPostCategories() {
            axios.get("../api/postCategories").then(res => {
                this.items = res.data;
            });
        },
        addItem(){
            if(this.edit === false){
                 if (this.$refs.form.validate()){
                    this.sending = true;
                    setTimeout(()=> {
                        fetch("../api/post", {
                        method: "post",
                        body: JSON.stringify(this.item),
                        headers:{"content-type": "application/json"}
                        })
                        .then(res => {
                            this.$router.push('/posts')
                        })
                        .then(data => {
                            this.sending = false;
                            this.$refs.form.reset();
                            this.saveSnackbar = {snackbar: true, color: 'success', text: data.data.name +' added', absolute: true, right: true, top: true}
                        })
                        .catch(err => console.log(err));
                    }, 2000);
                }
            }
        }
    }

  }
</script>

<style>

</style>
