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
                                <v-text-field box disabled label="Amount Paid" prepend-icon="attach_money" :value="selectedCourse.course_fee"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field box disabled label="Transaction Date" prepend-icon="date_range" :value="currentDate"></v-text-field>
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
                                Note: Registration is only completed when the Register Button is clicked. For the company that needs to register for more than 1 participant, you may click on Add New Participant. When registration steps are completed, there will be an acknowledgment email sent.
                                </v-alert>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" v-model="addParticipant">Add New Participant</v-btn>
                        <v-btn flat color="primary" type="submit">Register</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
            <v-card>
                <v-card-title primary-title>
                    <div class="headline">Payment Information</div>
                </v-card-title>
                <v-container fluid grid-list-lg>
                    <v-form id="payment-form">
                        <v-flex xs12>
                            <div class="uk-margin uk-text-center">
                            <p class="stripeError" v-if="stripeError">
                                {{stripeError}}
                            </p>
                            </div>
                            <div class="uk-margin uk-text-left">
                            <label class="uk-form-label" for="Card Number">
                                Card Number
                            </label>
                            <div class="uk-form-controls">
                                <div id="card-number" class="uk-input" :class="{ 'uk-form-danger': cardNumberError }"></div>
                                <span class="help-block" v-if="cardNumberError">
                                    {{cardNumberError}}
                                </span>
                            </div>
                            </div>
                            <div class="uk-grid-small uk-text-left" uk-grid>
                            <div class="uk-width-1-2@s">
                                <label class="uk-form-label" for="Card CVC">
                                    Card CVC
                                </label>
                                <div class="uk-form-controls">
                                    <div id="card-cvc" class="uk-input" :class="{ 'uk-form-danger': cardCvcError }"></div>
                                    <span class="help-block" v-if="cardCvcError">
                                        {{cardCvcError}}
                                    </span>
                                </div>
                            </div>
                            <div class="uk-width-1-2@s">
                                <label class="uk-form-label" for="Expiry Month">
                                    Expiry
                                </label>
                                <div class="uk-form-controls">
                                    <div id="card-expiry" class="uk-input" :class="{ 'uk-form-danger': cardExpiryError }"></div>
                                    <span class="help-block" v-if="cardExpiryError">
                                        {{cardExpiryError}}
                                    </span>
                                </div>
                            </div>
                            </div>
                            <div class="uk-margin uk-margin-remove-bottom uk-text-right">
                            <button class="uk-button uk-button-small uk-button-default" @click.prevent="reset()">
                                Reset
                            </button>
                            <button class="uk-button uk-button-small uk-button-primary" @click.prevent="submitFormToCreateToken()">
                                <span v-if="loading">processing...</span>
                                <span v-if="!loading">Donate $1200</span>
                            </button>
                            </div>
                            <div class="form-row">
    <label for="card-element">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>
  </div>
                        </v-flex>
                    </v-form>
                </v-container>
            </v-card>
        </v-container>
        <v-container fluid grid-list-md v-if="submitCompleted">
            <v-card>
            <v-alert
                :value="true"
                color="success"
                icon="check"
                >
                Form has been successfully submitted. Thank you.
            </v-alert>
            </v-card>
        </v-container>
    </div>
</template>

<script>
import moment from 'moment'
    export default {
        data() {
            return {
                card: {
                    cvc: '',
                    number: '',
                    expiry: ''
                },

                //elements
                cardNumber: '',
                cardExpiry: '',
                cardCvc: '',
                stripe: null,

                // errors
                stripeError: '',
                cardCvcError: '',
                cardExpiryError: '',
                cardNumberError: '',

                loading: null,

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
            this.setUpStripe();

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
            },
        },
        methods:{
            saveItem(){
                if (this.$refs.form.validate()){
                    this.sending = true
                    setTimeout(()=> {
                        fetch("/api/registerCourse", {
                        method: this.$route.name == 'registerCourse' ? "post" : "put",
                        body: JSON.stringify(this.item),
                        headers:{"content-type": "application/json"}
                        })
                        .then(res => {
                            this.sending = false
                            this.submitCompleted = true
                        })
                        .catch(err => console.log(err))
                    }, 1500)
                }
            },
            checkRegisteredEmail () {
                let app = this
                app.registeredEmail.forEach(function(val){ 
                    app.result = val.includes(app.item.email)
                })
                app.error_email = app.item.email && app.result
                ? 'Duplicated email entry. Please try another email!'
                : ''

                return true
            },
            setUpStripe() {
                var style = {
  base: {
    color: '#32325d',
    lineHeight: '18px',
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
};
                if (window.Stripe === undefined) {
                alert('Stripe V3 library not loaded!');
                } else {
                const stripe = window.Stripe('pk_test_LDbEuqCXwf92FjUHk4C9Spyc');
                this.stripe = stripe;

                const elements = stripe.elements();
                this.cardCvc = elements.create('cardCvc');
                this.cardExpiry = elements.create('cardExpiry');
                this.cardNumber = elements.create('cardNumber');

                this.cardCvc.mount('#card-cvc');
                this.cardExpiry.mount('#card-expiry');
                this.cardNumber.mount('#card-number', {style: style});

                this.listenForErrors();
                }
            },

            listenForErrors() {
                const vm = this;

                this.cardNumber.addEventListener('change', (event) => {
                vm.toggleError(event);
                vm.cardNumberError = ''
                vm.card.number = event.complete ? true : false
                });
                        
                this.cardExpiry.addEventListener('change', (event) => {
                vm.toggleError(event);
                vm.cardExpiryError = ''
                vm.card.expiry = event.complete ? true : false
                });
                
                this.cardCvc.addEventListener('change', (event) => {
                vm.toggleError(event);
                vm.cardCvcError = ''
                vm.card.cvc = event.complete ? true : false
                });
            },

            toggleError (event) {
                if (event.error) {
                this.stripeError = event.error.message;
                } else {
                this.stripeError = '';
                }
            },

            submitFormToCreateToken() {
                this.clearCardErrors();
                let valid = true;

                if (!this.card.number) {
                valid = false;
                this.cardNumberError = "Card Number is Required";
                }
                if (!this.card.cvc) {
                valid = false;
                this.cardCvcError = "CVC is Required";
                }
                if (!this.card.expiry) {
                valid = false;
                this.cardExpiryError = "Month is Required";
                }
                if (this.stripeError) {
                valid = false;
                }
                if (valid) {
                this.createToken()
                }
            },

            createToken() {
                this.stripe.createToken(this.cardNumber).then((result) => {
                    if (result.error) {
                    this.stripeError = result.error.message;
                    } else {
                    const token = result.token.id
                    alert('Thanks for donating.')
                        //send the token to your server
                        //clear the inputs
                    }
                })
            },

            clearElementsInputs() {
                this.cardCvc.clear()
                this.cardExpiry.clear()
                this.cardNumber.clear()
            },

            clearCardErrors() {
                this.stripeError = ''
                this.cardCvcError = ''
                this.cardExpiryError = ''
                this.cardNumberError = ''
            },
			
			reset() {
				this.clearElementsInputs()
				this.clearCardErrors()
			}
        }
    };
</script>

<style>
/**
 * The CSS shown here will not be introduced in the Quickstart guide, but shows
 * how you can use CSS to style your Element's container.
 */
.StripeElement {
  background-color: white;
  height: 40px;
  padding: 10px 12px;
  border-radius: 4px;
  border: 1px solid transparent;
  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>