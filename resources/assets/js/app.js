
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
import Vue from 'vue'
// import VueMaterial  from 'vue-material/dist/components'
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import VueContentPlaceholders from 'vue-content-placeholders'
Vue.use(VueMaterial)
// Vue.use(MdMenu)
// Vue.use(MdAvatar)
// Vue.use(MdButton)
// Vue.use(MdContent)
// Vue.use(MdTabs)
// Vue.use(MdList)
// Vue.use(MdIcon)
// Vue.use(MdDatepicker)
// Vue.use(MdCard)
// Vue.use(MdLayout)
// Vue.use(MdProgress)
Vue.use(VueContentPlaceholders)
// Vue.use(MdField)
// Vue.use(MdSnackbar)

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('posts', require('./components/Posts.vue'));
Vue.component('post-list', require('./components/PostList.vue'));
Vue.component('upload-files', require('./components/UploadFiles.vue'));
Vue.component('upload-file', require('./components/UploadFile.vue'));
Vue.component('fld-table', require('./components/UsersTable.vue'));

const app = new Vue({
    el: '#app',
});
