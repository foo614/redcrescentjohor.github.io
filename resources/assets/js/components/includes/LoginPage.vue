<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
        <v-card class="elevation-12">
            <v-toolbar dark color="primary">
            <v-toolbar-title>Login form</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
            <v-form>
                <v-text-field prepend-icon="person" v-model="data.email" label="Login" type="text"></v-text-field>
                <v-text-field prepend-icon="lock" v-model="data.password" label="Password" id="password" type="password"></v-text-field>
            </v-form>
            </v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" type="submit" @click.prevent="login">Login</v-btn>
            </v-card-actions>
        </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
    export default {
        data() {
            return {
                csrf_token: window.csrf_token,
                data:{
                    email:'',
                    password:'',
                    remember:true
                }
            }
        },
        methods:{
            login(){
                let vm = this;
                axios.post('/login', vm.data)
                .then(function(res){
                    console.log(res);
                })
                .catch(function(err){
                    console.log(err);
                })
            }
        }
    }
</script>
<style>
</style>
