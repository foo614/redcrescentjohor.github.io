<template>
    <div>
        <v-container fluid grid-list-md v-if="!submitCompleted">
            <v-card class="mb-3">
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                    <v-card-title primary-title><div class="headline"> {{selectedFundraiser.title}}</div></v-card-title>
                    <v-divider></v-divider>
                    <v-card-text style="font-size: 16px;">
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <div>Created Date <strong>{{ selectedFundraiser.created_at }}</strong></div>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <div>Created By <strong>{{ selectedFundraiser.user_id === 1 ? 'Red Crescent Johor' : selectedFundraiser.user}}</strong></div>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    </v-flex>
                    <v-flex xs12 sm6 class="pa-3">
                        <span>{{totalDonation}} MYR of {{selectedFundraiser.target_amount}} MYR raised</span>
                        <v-progress-linear v-model="donationProgress" color="#CA0000"></v-progress-linear>

                        <span>Donations will go to</span>
                        <div style="display: -webkit-box;"><v-img src="/img/64x64.png" height="38px" width="38px"></v-img> <div style="margin-top: 10px; margin-left: 10px;">Red Crescent Johor</div></div>
                    </v-flex>
                </v-layout>
            </v-card>
            <v-card>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title primary-title><div class="headline"> Story </div></v-card-title>
                        <div>
                            <v-card-text v-html="selectedFundraiser.body"></v-card-text>
                        </div>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" persistent max-width="800px">
                        <v-btn color="#ca0000" flat slot="activator">Donate Now</v-btn>
                        <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
                            <v-card>
                                <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
                                <v-card-title>
                                <span class="headline">Donate - {{selectedFundraiser.title}}</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-layout row wrap>
                                        <v-flex xs6>
                                            <v-text-field label="Amount Paid" prepend-icon="attach_money" v-model="item.amount" suffix="MYR" :rules="[v => !!v || 'Amount is required']"></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-item-group class="ml-1">
                                                <v-item
                                                v-for="donate_price in donate_price_list"
                                                :key="donate_price"
                                                v-model="selected"
                                                >
                                                <v-chip
                                                    slot-scope="{ active, toggle }"
                                                    :selected="active"
                                                    @click="toggle, selected = donate_price"
                                                >
                                                    RM {{donate_price}}
                                                </v-chip>
                                                </v-item>
                                            </v-item-group>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field label="Transaction Date" prepend-icon="date_range" :value="currentDate" readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="item.name" label="Name" prepend-icon="person" :rules="[v => !!v || 'Name is required']" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="item.email" label="Email" prepend-icon="contact_mail" :rules="[v => !!v || 'Email is required', v => /.+@.+/.test(v) || 'Email must be valid']" required></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout>
                                        <v-flex xs12>
                                            <v-card-title>
                                                <span class="headline">Credit or debit card</span>
                                            </v-card-title>
                                            <card class='stripe-card'
                                            :class='{ complete }'
                                            stripe='pk_test_LDbEuqCXwf92FjUHk4C9Spyc'
                                            :options='stripeOptions'
                                            @change='complete = $event.complete'
                                            />
                                            <div class="red--text">{{cardError}}</div>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click.native="dialog = false">Close</v-btn>
                                <v-btn color="blue darken-1" flat @click="saveItem" :disabled='!complete'>Donate</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-form>
                    </v-dialog>
                </v-card-actions>
            </v-card>
        </v-container>
        <v-container fluid grid-list-md v-if="submitCompleted">
            <v-alert
            :value="true"
            type="success"
            >
                Thank you for donation. Kindly refer receipt in your mailbox. <br/>
                Thanks again for your generosity and support,
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
            valid: true,
            auth: window.user,
            selected: null,
            selectedFundraiser: {},
            donationProgress: 0,
            dialog: false,
            donate_price_list:[10, 20, 50, 100, 500],
            selected_donate_price: null,
            stripeOptions:{
                style: {
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
            item: {
                id: null,
                email: "",
                name: "",
                amount: "",
                fundraiser_id: "",
                fundraiser_title: ""
            },
            sending: false,
            cardError: "",
            complete: false,
            currentDate: moment().format('DD/MM/YYYY'),
            submitCompleted: false,
            totalDonation: null
        };
    },
    components: { Card },
    created(){
        let app = this;
        let id = this.$route.params.id;
        axios.get('/api/totalDonation/'+ id)
        .then(function(res){
            app.totalDonation = res.data
        })
    },
    mounted() { 
        let app = this;
        let id = this.$route.params.id;
        axios.get('/api/fundraiser/' + id)
        .then(function (res) {
            app.selectedFundraiser = res.data;
        })
        .catch(function () {
            app.$toasted.error("Something wrong...", {icon:"error"})
        });
    },
    watch:{
        selected: function(){
            return this.item.amount = this.selected;
        },
        selectedFundraiser: function(){
            let app = this;
            app.item.fundraiser_id = this.selectedFundraiser.id
            app.item.fundraiser_title = this.selectedFundraiser.title
            app.item.name = this.auth.name
            app.item.email = this.auth.email
            app.donationProgress = (app.totalDonation/this.selectedFundraiser.target_amount)*100
        },
    },
    methods:{
        saveItem(){
            if (this.$refs.form.validate()){
                this.sending = true
                if(this.selectedFundraiser && this.complete){
                    setTimeout(()=> {
                        createToken()
                        .then(data => fetch("/api/payment", {
                        method: "post",
                        body: JSON.stringify([data.token, this.item]),
                        headers:{"content-type": "application/json"}
                        }))                        
                        .then(res => {
                            this.sending = false
                            this.dialog = false
                            this.submitCompleted = true
                        })
                        .catch(function(err) {
                            console.log('Error :', err);
                        });
                    }, 1500)
                }else
                    this.cardError = "Your card information is incomplete"
            }
        },
    }
    }
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
