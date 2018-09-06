
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
import Vue from 'vue'
import VueContentPlaceholders from 'vue-content-placeholders'
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import routes from './routes';

Vue.use(VueRouter)
Vue.use(Vuetify)
Vue.use(VueContentPlaceholders)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const router = new VueRouter({
    mode: 'history',
    routes
});
Vue.component('posts', require('./components/Posts.vue'));
Vue.component('post-list', require('./components/PostList.vue'));
Vue.component('upload-files', require('./components/UploadFiles.vue'));
Vue.component('upload-file', require('./components/UploadFile.vue'));
Vue.component('users-table', require('./components/UsersTable.vue'));
Vue.component('blood-type', require('./components/BloodType.vue'));
Vue.component('navbar', require('./components/Navbar.vue'));

const app = new Vue({
    el: '#app',
    router,
});
