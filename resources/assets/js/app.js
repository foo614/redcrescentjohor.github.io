
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
    duration: 2500,
})

import Vuetify from 'vuetify'

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

import PostsIndex from './components/posts/PostsTable.vue';
import PostForm from './components/posts/PostForm.vue';
// import PostEdit from './components/posts/PostEdit.vue';
import PostCalendar from './components/posts/PostCalendar.vue';

import ProfileIndex from './components/profile/ProfileIndex.vue';
import UserIndex from './components/users/UsersTable.vue';
import UserForm from './components/users/UserForm.vue';

import DonorsIndex from './components/donors/DonorsTable.vue';
import DonorForm from './components/donors/DonorForm.vue';

import BranchesIndex from './components/branches/BranchesTable.vue';
import BranchForm from './components/branches/BranchForm.vue';

import HospitalsIndex from './components/hospitals/HospitalsTable.vue';
import HospitalForm from './components/hospitals/HospitalForm.vue';

import Dashboard from './components/includes/Dashboard.vue';
// import Login from './components/includes/LoginPage.vue';

const routes = [
    //login
    // {path: '/login',components: {login: Login}},
    //dashboard
    {path: '/',components: {dashboard: Dashboard}},
    //posts
    {path: '/posts',components: {postsIndex: PostsIndex}},
    {path: '/posts/create', component: PostForm, name: 'createPost'},
    {path: '/posts/:id/edit', component: PostForm, name: 'editPost'},
    {path: '/posts/calendar', component: PostCalendar, name: 'viewPost'},
    //users
    {path: '/users', components: {usersIndex: UserIndex}},
    {path: '/users/:id/edit', component: UserForm, name: 'editUser'},
    {path: '/users/create', component: UserForm, name: 'createUser'},
    //user profile
    {path:'/users/:id', component: ProfileIndex, name: 'profile'},
    //donors
    {path: '/donors', components:{donorsIndex: DonorsIndex}},
    {path: '/donors/create', component: DonorForm, name: 'createDonor'},
    {path: '/donors/:id/edit', component: DonorForm, name: 'editDonor'},
    //branches
    {path: '/branches', components:{branchesIndex: BranchesIndex}},
    {path: '/branches/:id/edit', component: BranchForm, name: 'editBranch'},
    {path: '/branches/create', component: BranchForm, name: 'createBranch'},
    //hospitals
    {path: '/hospitals', components:{hospitalsIndex: HospitalsIndex}},
    {path: '/hospitals/:id/edit', component: HospitalForm, name: 'editHospital'},
    {path: '/hospitals/create', component: HospitalForm, name: 'createHospital'},
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
// Vue.component('fld-login', require('./components/includes/LoginPage.vue'));
Vue.component('password-field', require('./components/Password.vue'));

//homepage
Vue.component('posts', require('./components/Posts.vue'));
Vue.component('post-list', require('./components/PostList.vue'));

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
