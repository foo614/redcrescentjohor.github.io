<template>
    <v-container>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-tabs centered grow v-model="tabs">
                        <v-tabs-slider color="#ca0000"></v-tabs-slider>
                        <v-tab href="#tab-1">
                            REGISTER
                        </v-tab>
                        <v-tab href="#tab-2">
                            LOGIN
                        </v-tab>
                        <v-tab-item value="tab-1">
                            <v-card flat>
                                <v-card-text style="text-align:center" class="px-5 pt-3">
                                    <v-btn round color="#dd4b39" dark href="/redirect/google" class="my-4">
                                        <v-icon left>fab fa-google-plus-g</v-icon><label>REGISTER WITH GOOGLE</label>
                                    </v-btn><br/>
                                    <v-btn round color="#355a9f" dark href="/redirect/facebook" class="mb-2">
                                        <v-icon left>fab fa-facebook-square</v-icon><label>REGISTER WITH FACEBOOK</label>
                                    </v-btn>
                                    <div class="or"></div>
                                    <v-form ref="form_register" v-model="valid" lazy-validation @submit.prevent="registerUser">
                                        <input type="hidden" name="_token" :value="csrf_token">
                                        <v-text-field 
                                            prepend-icon="person" 
                                            v-model="register.name" 
                                            name="name" 
                                            label="Name" 
                                            :rules="[v => !!v || 'Name is required']"
                                            type="text">
                                        </v-text-field>
                                        <v-text-field 
                                            prepend-icon="mail" 
                                            v-model="register.email" 
                                            name="email" 
                                            label="Email" 
                                            :rules="[v => !!v || 'E-mail is required', v => /.+@.+/.test(v) || 'E-mail must be valid']" 
                                            type="email">
                                        </v-text-field>
                                        <v-text-field 
                                            prepend-icon="lock" 
                                            v-model="register.password" 
                                            name="password" 
                                            label="Password" 
                                            id="password1"
                                            :rules="[v => !!v || 'Password is required', v => (v && v.length >= 6) || 'Password must be greater than 6 characters']"
                                            type="password">
                                        </v-text-field>
                                        <v-btn color="primary" type="submit">Register</v-btn>
                                    </v-form>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item value="tab-2">
                            <v-card flat>
                                <v-card-text style="text-align:center" class="px-5 pt-3">
                                    <v-btn round color="#dd4b39" dark href="/redirect/google" class="my-4">
                                        <v-icon left>fab fa-google-plus-g</v-icon><label>REGISTER WITH GOOGLE</label>
                                    </v-btn>
                                    <v-btn round color="#355a9f" dark href="/redirect/facebook" class="mb-2">
                                        <v-icon left>fab fa-facebook-f</v-icon><label>LOGIN WITH FACEBOOK</label>
                                    </v-btn>
                                    <div class="or"></div>
                                    <v-form ref="form_login" v-model="valid" lazy-validation @submit.prevent="loginUser">
                                        <input type="hidden" name="_token" :value="csrf_token">
                                        <v-text-field 
                                            prepend-icon="person" 
                                            name="email" 
                                            label="Email"
                                            v-model="login.email" 
                                            :rules="[v => !!v || 'E-mail is required', v => /.+@.+/.test(v) || 'E-mail must be valid']" 
                                            type="email">
                                        </v-text-field>
                                        <v-text-field 
                                            prepend-icon="lock" 
                                            name="password" 
                                            label="Password" 
                                            id="password2"
                                            v-model="login.password" 
                                            :rules="[v => !!v || 'Password is required', v => (v && v.length >= 6) || 'Password must be greater than 6 characters']"
                                            type="password">
                                        </v-text-field>
                                        <v-btn color="primary" @click="loginUser">Login</v-btn>
                                        <strong>{{emailError}}</strong>
                                        <strong>{{passwordError}}</strong>
                                    </v-form>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                    </v-tabs>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                csrf_token: window.csrf_token,
                register:{
                    name: null,
                    email: null,
                    password: null,
                },
                login:{
                    name: null,
                    email: null,
                    password: null,
                },
                valid: true,
                sending: false,
                tabs: 'tab-2',
                emailError: null,
                passwordError: null,
            }
        },
        methods:{
            registerUser(){
                if (this.$refs.form_register.validate()){
                    this.sending = true
                    setTimeout(()=> {
                        fetch("/api/member", {
                        method: "post",
                        body: JSON.stringify(this.register),
                        headers:{"content-type": "application/json"}
                        })
                        .then(res => {
                            this.sending = false
                            let currentPage = this.$route.name
                            this.$router.push({ path: '/social-login' }, ()=> {
                                this.$toasted.success('Register done! Try to login' , {icon:"check"})
                            })
                        })
                        .catch(err => console.log(err))
                    }, 1500)
                }
            },
            loginUser(){
                if (this.$refs.form_login.validate()){
                this.sending = true
                let vm = this
                axios.post('/login', vm.login)
                .then(function (response) {
                    vm.$router.push({name:'dashboard'}, ()=> {
                        vm.$toasted.success("Welcome." , {icon:"check"})
                    })
                })
                .catch(function (error) {
                    var errors = error.response
                    if(errors.statusText === 'Unprocessable Entity'){
                        if(errors.data){
                            if(errors.data.email){
                            vm.errorsEmail = true
                            vm.emailError = _.isArray(errors.data.email) ? errors.data.email[0]: errors.data.email
                            }
                            if(errors.data.password){
                            vm.errorsPassword = true
                            vm.passwordError = _.isArray(errors.data.password) ? errors.data.password[0] : errors.data.password
                            }
                        }
                    }
                });
                }
            }
        }
    }
</script>

<style>
    .hr-container {
        margin: 10px 40px 18px;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
    }

    .hr-line {
        -webkit-box-flex: 1;
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -webkit-flex-shrink: 1;
        -ms-flex-negative: 1;
        flex-shrink: 1;
        background-color: #efefef;
        height: 1px;
        position: relative;
        top: .45em;
    }

    .hr-middle-word {
        color: #999;
        -webkit-box-flex: 0;
        -webkit-flex-grow: 0;
        -ms-flex-positive: 0;
        flex-grow: 0;
        -webkit-flex-shrink: 0;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        font-size: 13px;
        font-weight: 600;
        line-height: 15px;
        margin: 0 18px;
        text-transform: uppercase;
    }

    .or {
        display: block;
        width: 100%;
        height: 1px;
        border-bottom: 1px solid #dee3e4;
        position: relative;
        margin: 20px 0;
    }

    .or:before {
        content: 'or';
        width: 40px;
        height: 18px;
        position: absolute;
        top: -5px;
        right: calc(50% - 20px);
        background-color: #fff;
        text-align: center;
        line-height: 10px;
        color: #999;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }
</style>