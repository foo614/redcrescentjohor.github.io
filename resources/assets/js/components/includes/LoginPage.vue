<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
        <v-card class="elevation-12">
            <v-toolbar dark color="red">
            <v-toolbar-title>Login form</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation @click.prevent="login">
                <v-text-field 
                    prepend-icon="person" 
                    v-model="loginDetails.email" 
                    :rules="[v => !!v || 'E-mail is required', v => /.+@.+/.test(v) || 'E-mail must be valid']" 
                    label="Login"
                >
                </v-text-field>
                <v-text-field 
                prepend-icon="lock" 
                v-model="loginDetails.password"
                :type="show ? 'text' : 'password'"
                :append-icon="show ? 'visibility_off' : 'visibility'"
                @click:append="show = !show"
                :rules="[v => !!v || 'Password is required', v => (v && v.length >= 6) || 'Password must be greater than 6 characters']"
                label="Password" 
                ></v-text-field>
                <v-checkbox color="red" label="Remember Me" v-model="loginDetails.remember"></v-checkbox>
            </v-form>
            </v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn dark color="red" type="submit" @click="login">Login</v-btn>
            </v-card-actions>
        </v-card>
        </v-flex>
        <strong>{{emailError}}</strong>
        <strong>{{passwordError}}</strong>
    </v-layout>
</template>
<script>
    export default {
        data() {
            return {
                csrf_token: window.csrf_token,
                loginDetails:{
                    email:'',
                    password:'',
                    remember:true
                },
                emailError: null,
                passwordError: null,
                show: false,
                sending: false,
                valid: true,
            }
        },
        methods:{
            login(){
                if (this.$refs.form.validate()){
                this.sending = true
                let vm = this
                axios.post('/login', vm.loginDetails)
                .then(function (response) {
                    vm.$router.push({name:'home'}, ()=> {
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
</style>
