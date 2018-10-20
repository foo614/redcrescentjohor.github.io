
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
window.Vue = require('vue');
import Vue from 'vue'
import Router from './router.js'
// inifinity load
import VueContentPlaceholders from 'vue-content-placeholders'

//vue toasted
import Toasted from 'vue-toasted'
Vue.use(Toasted, {
    iconPack : 'material',
    theme: "bubble", 
    // position: "top-right",
    position:"bottom-center",
    duration: 2500,
})

import Vuetify from 'vuetify'
Vue.use(Vuetify,{
    theme: {
        primary: '#1976D2',
        secondary: '#424242',
        accent: '#82B1FF',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107'
      }
})
Vue.use(VueContentPlaceholders)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('fld-navbar', require('./components/includes/Navbar.vue'));
// Vue.component('fld-login', require('./components/includes/LoginPage.vue'));
Vue.component('password-field', require('./components/Password.vue'));

//upload components
Vue.component('upload-files', require('./components/imageUploads/UploadFiles.vue'));
Vue.component('upload-file', require('./components/imageUploads/UploadFile.vue'));

Vue.component('home-footer', require('./components/home/Footer.vue'));
Vue.component('home-header', require('./components/home/Navbar.vue'));


new Vue({
    el: '#app',
    router: Router
});
