
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import Vue from 'vue'
// inifinity load
import VueContentPlaceholders from 'vue-content-placeholders'

//vue toasted
import Toasted from 'vue-toasted'
Vue.use(Toasted, {
    iconPack : 'material',
    theme: "bubble", 
    position: "top-right",
    icon : 'check',
    duration: 2500,
})

import Vuetify from 'vuetify'

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

import PostsIndex from './components/posts/PostsTable.vue';
import PostCreate from './components/posts/PostCreate.vue';
import PostEdit from './components/posts/PostEdit.vue';
import PostCalendar from './components/posts/PostCalendar.vue';

import ProfileIndex from './components/profile/ProfileIndex.vue';
import UserIndex from './components/users/UsersTable.vue';
// import UserEdit from './components/users/UserEdit.vue';
import UserForm from './components/users/UserCreate.vue';

import DonorsIndex from './components/donors/DonorsTable.vue';
import DonorCreate from './components/donors/DonorCreate.vue';
import DonorEdit from './components/donors/DonorEdit.vue';

const routes = [
    //posts
    {path: '/posts',components: {postsIndex: PostsIndex}},
    {path: '/posts/create', component: PostCreate, name: 'createPost'},
    {path: '/posts/:id/edit', component: PostEdit, name: 'editPost'},
    {path: '/posts/calendar', component: PostCalendar, name: 'viewPost'},
    //users
    {path: '/users', components: {usersIndex: UserIndex}},
    {path: '/users/:id/edit', component: UserForm, name: 'editUser'},
    {path: '/users/create', component: UserForm, name: 'createUser'},
    //user profile
    {path:'/users/:id', component: ProfileIndex,name: 'profile'},
    //donors
    {path: '/donors', components:{donorsIndex: DonorsIndex}},
    {path: '/donors/create', component: DonorCreate, name: 'createDonor'},
    {path: '/donors/:id/edit', component: DonorEdit, name: 'editDonor'},
]
const router = new VueRouter({mode: 'history', routes })
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
Vue.component('password-field', require('./components/Password.vue'));

//homepage
Vue.component('posts', require('./components/Posts.vue'));
Vue.component('post-list', require('./components/PostList.vue'));

//users || members || donors
// Vue.component('user-create', require('./components/users/UserCreate.vue'));
// Vue.component('users-table', require('./components/users/UsersTable.vue'));

Vue.component('hospital-table', require('./components/HospitalsTable.vue'));
Vue.component('branch-table', require('./components/BranchesTable.vue'));
// Vue.component('donors-table', require('./components/donors/DonorsTable.vue'));

//upload components
Vue.component('upload-files', require('./components/imageUploads/UploadFiles.vue'));
Vue.component('upload-file', require('./components/imageUploads/UploadFile.vue'));

// settings
Vue.component('membership-type', require('./components/settings/MembershipType.vue'));
Vue.component('blood-type', require('./components/settings/BloodType.vue'));
Vue.component('member-role-type', require('./components/settings/MemberRoleType.vue'));
Vue.component('post-category-type', require('./components/settings/PostCategoryType.vue'));

const app = new Vue({
    el: '#app',
    router
});
