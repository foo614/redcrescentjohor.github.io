<template>
<div>
    <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
        <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">{{$route.name == "editBloodDonationRecord" ? 'Edit' : 'Add' }} Blood Donation Record</div>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="add" v-show="$route.name != 'editBloodDonationRecord'">Add row</v-btn>
            </v-card-title>
                <v-container fluid grid-list-lg>
                    <v-layout>
                        <v-flex xs4 sm2 v-show="$route.name != 'editBloodDonationRecord'">
                            <v-checkbox
                            label="Blood Collection from Event?"
                            v-model="checkbox"
                            ></v-checkbox>
                        </v-flex>
                        <v-flex xs8 v-bind:class="{ sm10 : $route.name == 'createBloodDonationRecord', sm11 : $route.name == 'editBloodDonationRecord' }" v-if="checkbox || $route.name == 'editBloodDonationRecord'">
                            <v-combobox
                                v-model="post"
                                item-text="title"
                                label="Event"
                                prepend-icon="event"
                                :search-input.sync="search"
                                :items="events">
                                <template slot="no-data">
                                    <v-list-tile>
                                        <v-list-tile-content>
                                        <v-list-tile-title>
                                            No results matching "<strong>{{ search }}</strong>"
                                        </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </template>
                            </v-combobox>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap v-for="(donor, index) in donated_donors" :key="index">
                        <v-text-field :v-model="post ? donor.post_id = post.id : '' " v-show="false"></v-text-field>
                        <v-flex xs12 sm5>
                            <v-combobox
                                v-model="donor.user"
                                :items="donors"
                                item-text="name"
                                prepend-icon="person"
                                label="Select a user or create a new one"
                                v-on:keyup="addUser"
                                required
                            >
                                <template slot="no-data">
                                    <v-list-tile>
                                        <v-list-tile-content>
                                        <v-list-tile-title>
                                            No results matching. Press <kbd>enter</kbd> to create a new one
                                        </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </template>
                            </v-combobox>
                        </v-flex>
                        <v-flex xs12 sm3>
                            <v-text-field
                            v-model="donor.volume"
                            label="Volume"
                            value=""
                            suffix="ml"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs11 sm3>
                            <!-- date donated -->
                            <v-menu
                            :ref="date_menu+index"
                            :close-on-content-click="true"
                            :v-model="date_menu+index"
                            :nudge-right="40"
                            lazy
                            transition="scale-transition"
                            offset-y
                            full-width
                            max-width="290px"
                            min-width="290px"
                            >
                            <v-text-field
                                slot="activator"
                                v-model="donor.donate_date"
                                label="Blood Donation Date"
                                persistent-hint
                                prepend-icon="event"
                            ></v-text-field>
                            <v-date-picker v-model="donor.donate_date" no-title scrollable></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs1 sm1 v-if="index != 0" class="mt-3">
                            <v-icon @click="remove(index)">cancel</v-icon>
                        </v-flex>
                    </v-layout>
                </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" type="submit">Submit</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
    <v-dialog v-model="addDonorDialog" max-width="800px">
        <v-form ref="formDonor" v-model="valid" lazy-validation @submit.prevent="saveDonor">
            <v-card>
                <v-progress-linear height=3 :indeterminate="true" v-if="sendingModel" ></v-progress-linear>
                <v-card-title primary-title>
                    <div class="headline">Add Donor</div>
                </v-card-title>
                    <v-container fluid grid-list-lg>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="item.name"
                                    label="Name"
                                    prepend-icon="person"
                                    :rules="[v => !!v || 'Name is required']"
                                    required
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="item.email"
                                    label="Email"
                                    prepend-icon="contact_mail"
                                    :rules="[v => !!v || 'Email is required', v => /.+@.+/.test(v) || 'Email must be valid']"
                                    required
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="item.ic"
                                    label="Identity Card"
                                    prepend-icon="credit_card"
                                    :rules="[v => !!v || 'Identity Card is required']"
                                    required
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="item.contact"
                                    label="Contact"
                                    prepend-icon="contact_phone"
                                    :rules="[v => !!v || 'Contact is required']"
                                    required
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap v-if="$route.name != 'editDonor'">
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="item.password"
                                    type="password"
                                    label="Password"
                                    prepend-icon="vpn_key"
                                    :rules="[v => !!v || 'Password is required', v=> (v && v.length >= 6) || 'Password must be greater equal than 6 characters or numbers']"
                                    required
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="item.password_confirmation"
                                    type="password"
                                    label="Confirm Password"
                                    prepend-icon="vpn_key"
                                    :rules="[v => !!v || 'Password is required', v => v === item.password || 'Password not match']"
                                    required
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-select
                                    v-model="item.blood_type_id"
                                    :items="blood_types"
                                    prepend-icon="opacity"
                                    item-text="name"
                                    item-value="id"
                                    label="Blood Type"
                                    :rules="[v => !!v || 'Blood Type is required']"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm6>
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
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-icon>image</v-icon>
                                <v-badge overlap>
                                    <span slot="badge" v-if="(preview || item.avatar)" @click="item.avatar = null; preview= null">x</span>
                                    <v-avatar tile class="elevation-7 ml-2" size="128">
                                        <v-img lazy-src aspect-ratio="2" v-if="item.avatar || preview" 
                                        :src="(item.avatar && !preview) ? '/img/'+item.avatar : preview ? preview : null"
                                            alt="profile_image"></v-img>
                                        <v-tooltip bottom v-if="!preview && !item.avatar">
                                            <input type="file" ref="avatar" v-on:change="handleFile" style="display:none">
                                            <v-icon large @click="pickFile" slot="activator">
                                                image
                                            </v-icon>
                                            <span>Upload Profile Photo</span>
                                        </v-tooltip>
                                    </v-avatar>
                                </v-badge>
                            </v-flex>
                        </v-layout>
                    </v-container>
                <v-card-actions>
                    <v-btn color="primary" flat @click="addDonorDialog=false">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" type="submit">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</div>
</template>

<script>
import moment from 'moment';
export default {
    components: {},
    mounted() {
        this.autocomplete = new google.maps.places.Autocomplete(
            this.$refs.autocomplete
        );
        this.autocomplete.setComponentRestrictions(
            {'country': ['my', 'sg']})
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
        });
        this.fetchDonors()
        this.fetchEvents()
        this.fetchBloodTypes()
        if(this.$route.name === "editBloodDonationRecord"){
            let app = this
            let id = this.$route.params.id
            axios.get('/api/bloodDonationRecord/' + id)
            .then(function (res) {
                app.donated_donors = []
                app.donated_donors.push(res.data)
                app.donated_donors[0].blood_donation_record_id = res.data.id
                app.post = res.data.post
            })
            .catch(function (error) {
                app.$toasted.error("Something wrong..." + error, {icon:"error"})
                console.log(error)
            });
        }
    },
    data () {
        return {
            dateFormatted: null,
            blood_types:[],
            donors:[],
            events:[],
            sending: false,
            sendingModel: false,
            valid: true,
            search: null,
            searchDonors: null,
            checkbox: false,
            post: null,
            post_id: null,
            addDonorDialog: false,
            preview: null,
            date_menu:false,
            donated_donors:[
                {
                    blood_donation_record_id:null,
                    post_id: null,
                    user: null,
                    volume: null,
                    donate_date: moment().format('YYYY-MM-DD')
                }
            ],
            item: {
                id: null,
                name: "",
                email: "",
                ic: "",
                contact: "",
                password: "",
                blood_type_id: "",
                address: "",
                avatar: "",
            },
        };
    },
    watch:{
        checkbox: function(){
            return this.checkbox == false ? this.post = null : this.post
        },
    },
    computed: {},
    methods:{
        fetchBloodTypes: function(){
            axios.get("/api/bloodTypes").then(res => {
                this.blood_types = res.data
            });
        },
        fetchEvents: function(){
            axios.get("/api/posts/showEvent").then(res =>{
                this.events = res.data;
            })
        },
        fetchDonors: function(){
            axios.get("/api/donors").then(res =>{
                this.donors = res.data;
            })
        },
        pickFile() {
            this.$refs.avatar.click()
        },
        handleFile(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0])
        },
        createImage(file) {
            let reader = new FileReader()
            let vm = this;
            reader.onload = e => {
                vm.preview = e.target.result
                vm.item.avatar = e.target.result
            };
            reader.readAsDataURL(file)
        },
        add: function() {
          this.donated_donors.push({ post_id: null, user: null, volume: null, donate_date: moment().format('YYYY-MM-DD')});
        },
        remove: function(index){
            this.donated_donors.splice(index, 1)
        },
        addUser: function(e){
            if (e.keyCode === 13) {
                this.addDonorDialog = true
            }
        },
        saveDonor(){
            if (this.$refs.formDonor.validate()){
                this.sendingModel = true;
                setTimeout(()=> {
                    fetch("/api/member", {
                    method: "post",
                    body: JSON.stringify(this.item),
                    headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.sendingModel = false
                        this.$toasted.success(this.item.name + ' added', {icon:"check"})
                        this.$refs.formDonor.reset()
                        this.addDonorDialog = false
                        this.fetchDonors()
                    })
                    .catch(err => console.log(err))
                }, 1500)
            }
        },
        saveItem(){
            if (this.$refs.form.validate()){
                this.sending = true
                setTimeout(()=> {
                    fetch("/api/bloodDonationRecord", {
                        method: this.$route.name == 'createBloodDonationRecord' ? "post" : "put",
                        body: JSON.stringify(this.donated_donors),
                        headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.sending = false
                        let currentPage = this.$route.name
                        this.$router.push({ path: '/bloodDonationRecords' }, ()=> {
                            if(currentPage === 'createBloodDonationRecord' )
                                this.$toasted.success((this.donated_donors.length > 1 ? this.donated_donors.length +' item(s) added' : 'record added') , {icon:"check"})
                            else
                                this.$toasted.success((this.donated_donors.length > 1 ? this.donated_donors.length +' item(s) updated' : 'record updated') , {icon:"check"})
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

</style>
