<template>
    <div>
        <v-container fluid grid-list-md v-if="!submitCompleted">
            <v-card class="mb-3">
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-card-title primary-title>
                            <div class="headline">Selected Course Details:</div>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6>
                    <v-card-title primary-title>
                        <div class="headline"> {{selectedCourse.name}}</div></v-card-title>
                    <v-divider></v-divider>
                    <v-card-text style="font-size: 16px;">
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <div>Course fee: <strong>{{ selectedCourse.course_fee === 0 ? "FREE" : 'RM' + selectedCourse.course_fee }}</strong></div>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <div>Date: <strong>{{ selectedCourse | formatDate }}</strong></div>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <div>Time period: <strong>{{ selectedCourse.start_time | formatTime }} - {{ selectedCourse.end_time | formatTime }}</strong></div>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <div>Info: <strong>{{ selectedCourse.info }}</strong></div>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <div>Venue <strong>{{ selectedCourse.venue }}</strong></div>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    </v-flex>
                </v-layout>
            </v-card>
            <v-card class="mb-3">
                <v-card-text>
                    <ul>
                        <li>All Courses fees displayed are before government subsidies.</li>
                        <li>Learners who have received funding as a result of attending the same course with other training providers WILL NOT be eligible for funding.</li>
                        <li>Please read through the 
                            <v-dialog
                            v-model="dialog"
                            >
                            <a slot="activator">Terms and conditions</a> 
                            <v-card>
                                <v-card-title
                                class="headline grey lighten-2"
                                primary-title
                                >
                                Terms & Conditions
                                </v-card-title>

                                <v-card-text>
                                    <ol type="number">
                                        <li>The class will be held at our MALAYSIAN RED CRESCENT, NATIONAL HEADQUARTERS LOT PT 54, LENGKOK BELFIELD OFF JALAN  WISMA PUTRA, 50460 KUALA LUMPUR.</li>
                                        <li>The course will start at 8.30 am for registration, 8.45 am to 6.00 pm for learning.</li>
                                        <li>Certificates will be ready after 10 days for hand collection or by post. Certificates will be valid for three (3) years (upon which candidates will need to attend a refresher course to renew the certificate).</li>
                                        <li>Courses will be conducted only one (1) day if attendees are less than ten (10).</li>
                                        <li>Anyone who wants to renew the certificate, please bring the original certificate that has expired. In the event of failure to do so, the Malaysian Red Crescent considers students/candidates never undergoing the course.</li>
                                        <li>PERSONAL DATA PROTECTION ACT (PDPA) - All personal information collected will solely be used for administrative purposes (e.g. course confirmation, certificate validity reminder, etc.). Please refer to Personal Data Protection Act at <a href="http://www.agc.gov.my/agcportal/uploads/files/Publications/LOM/EN/Act%20709%2014%206%202016.pdf">http://www.agc.gov.my.</a></li>
                                    </ol>
                                </v-card-text>

                                <v-divider></v-divider>

                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="primary"
                                    flat
                                    @click="dialog = false"
                                >
                                    CLOSE
                                </v-btn>
                                </v-card-actions>
                            </v-card>
                            </v-dialog>
                            before registering for our workshops.
                        </li>
                        <li>Please call us an enquiry <strong>012-254 0021</strong> if you encounter any problem in the registration and we will connect with you soon.</li>
                    </ul>
                </v-card-text>
            </v-card>
            <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
                <v-card class="mb-3">
                    <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
                    <v-card-title primary-title>
                        <div class="headline">Participant Information</div>
                    </v-card-title>
                    <v-container fluid grid-list-lg class="pt-0">
                        <v-divider></v-divider>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <!-- <v-autocomplete
                                    v-if="verifyMember"
                                    v-model="model"
                                    :items="items"
                                    :loading="isLoading"
                                    :search-input.sync="search"
                                    :append-icon="null"
                                    hide-selected
                                    hide-no-data
                                    :item-text="item => item.name + ' - ' + item.email"
                                    label="Member"
                                    placeholder="Full Name"
                                    prepend-icon="person"
                                    return-object
                                >
                                    <template
                                        slot="item"
                                        slot-scope="data"
                                    >
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                                            <v-list-tile-sub-title v-html="data.item.email"></v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </template>
                                </v-autocomplete> -->
                                <v-text-field 
                                    v-model="item.name" label="Name"
                                    prepend-icon="contact_mail"
                                    :rules="[v => !!v || 'Name is required']"
                                    required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field v-model="item.email" label="Email" prepend-icon="contact_mail" :error-messages="error_email"
                                    :rules="[
                                            v => checkRegisteredEmail(),
                                            ]"
                                    required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-text-field v-model="item.ic" label="IC Number" prepend-icon="credit_card"
                                    :rules="[v => !!v || 'IC Number is required']" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field v-model="item.contact" label="Contact" prepend-icon="contact_phone"
                                    :rules="[v => !!v || 'Contact is required']" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
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
                                                <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Address</label>
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

                        <v-card-title primary-title>
                            <div class="headline">Payment Information</div>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-text-field box disabled label="Amount Paid" prepend-icon="attach_money" :value="selectedCourse.course_fee" suffix="MYR"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field box disabled label="Transaction Date" prepend-icon="date_range" :value="currentDate"></v-text-field>
                            </v-flex>
                            <v-flex xs12 v-if="selectedCourse.course_fee != 0">
                                <card class='stripe-card'
                                :class='{ complete }'
                                stripe='pk_test_LDbEuqCXwf92FjUHk4C9Spyc'
                                :options='stripeOptions'
                                @change='complete = $event.complete'
                                />
                                <!-- <div ref="card"></div> -->
                                <div class="red--text">{{cardError}}</div>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-checkbox
                                label="I accept the terms and conditions"
                                v-model="tnc"
                                ></v-checkbox>
                                <v-alert
                                :value="true"
                                type="info"
                                >
                                Note: Registration is only completed when the Register Button is clicked. 
                                When registration steps are completed, there will be an acknowledgment email sent.
                                </v-alert>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <!-- <v-btn flat color="primary" v-model="addParticipant">Add New Participant</v-btn> -->
                        <v-btn flat color="primary" type="submit" :disabled="!tnc">Register</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-container>
        <v-container fluid grid-list-md v-if="submitCompleted">
            <v-alert
            :value="true"
            type="success"
            >
                The registration is completed. Kindly refer information in your mailbox.
            </v-alert>
        </v-container>
    </div>
</template>

<script>
import { Card, createToken } from 'vue-stripe-elements-plus'
import moment from 'moment'
    export default {
        data() {
            return {
                loading: null,
                stripeOptions:{
                    style : {
                        base: {
                            color: "#000",
                            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                            fontSmoothing: 'antialiased',
                            fontSize: '16px',
                            '::placeholder': {
                            color: '#aab7c4'
                            }
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    }
                },
                cardError: "",
                complete: false,
                submitCompleted: false,
                tnc: null,
                result: null,
                addParticipant: null,
                sending: false,
                valid: true,
                item: {
                    id: null,
                    name: "",
                    email: "",
                    ic: "",
                    contact: "",
                    address: "",
                    amount: "",
                    course_id: ""
                },
                selectedCourse: {},
                currentDate: moment().format('DD/MM/YYYY'),
                items: [],
                isLoading: false,
                // model: null,
                search: null,
                dialog: false,
                error_email: null,
                registeredEmail: [],
            };
        },
        components: { Card },
        created(){
            let app = this
            fetch("/api/registerCourses")
                .then(res => res.json())
                .then(res => {
                    res.forEach(function(elm){
                        app.registeredEmail.push(elm.users)
                    })
                })
        },
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

                this.item.address = place.formatted_address
                this.item.map_lat = lat
                this.item.map_lng = lng
                this.item.place_id = place_id
            });

            let app = this;
            let id = this.$route.params.id;
            axios.get('/api/course/' + id)
            .then(function (res) {
                app.selectedCourse = res.data;
            })
            .catch(function () {
                app.$toasted.error("Something wrong...", {icon:"error"})
            });
        },
        filters:{
            formatTime: function(val){
                if(!val) return ''
                return moment(val,"HH:mm:ss").format("HH:mm")
            },
            formatDate: function(obj){
                var start = moment(obj.start_date)
                var end = moment(obj.end_date)
                return start.isSame(end, 'day') ? start.format("DD MMM") : start.format("DD MMM") + " - " + end.format("DD MMM")
            }
        },
        watch:{
            selectedCourse: function(){
                this.item.course_id = this.selectedCourse.id
                this.item.amount = this.selectedCourse.course_fee
                this.item.selectedCourse = this.selectedCourse
            }
        },
        methods:{
            saveItem(){
                if (this.$refs.form.validate()){
                    if(this.selectedCourse.course_fee != 0 && this.complete){
                        this.pay()
                        this.sending = true
                        setTimeout(()=> {
                            fetch("/api/registerCourse", {
                            method: "post",
                            body: JSON.stringify(this.item),
                            headers:{"content-type": "application/json"}
                            })
                            .catch(err => console.log(err))
                        }, 1500)
                    }else
                        this.cardError = "Your card information is incomplete"
                }
            },
            checkRegisteredEmail() {
                let app = this
                app.registeredEmail.forEach(function(val){ 
                    app.result = val.includes(app.item.email)
                })
                app.error_email = app.item.email && app.result
                ? 'Duplicated email entry. Please try another email!'
                : ''

                return true
            },
            pay: function () {
                createToken()
                    .then(data => fetch("/api/payment", {
                    method: "post",
                    body: JSON.stringify([data.token, this.item]),
                    headers:{"content-type": "application/json"}
                    }))                        
                    .then(res => {
                        this.sending = false
                        this.submitCompleted = true
                    })
                    .catch(function(err) {
                        console.log('Error :', err);
                    });
            },
        }
    };
</script>

<style>
/* .stripe-card {
  border-bottom: 1px solid rgba(0,0,0,0.42);
  padding: 10px;
}
.stripe-card.complete {
  border-color: green;
} */
.stripe-card {
  height: 40px;
  padding: 10px 12px;
  border-radius: 4px;
  border: 1px solid #00000026;
  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.stripe-card--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.stripe-card--invalid {
  border-color: #fa755a;
}

.stripe-card--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>