import Vue from 'vue'
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import BloodDonationIndex from './components/bloodDonationRecords/BloodDonationRecordsTable.vue';
import BloodDonationForm from './components/bloodDonationRecords/BloodDonationRecordsForm.vue';

import PostsIndex from './components/posts/PostsTable.vue';
import PostForm from './components/posts/PostForm.vue';
// import PostEdit from './components/posts/PostEdit.vue';
import PostCalendar from './components/posts/PostCalendar.vue';

import ProfileIndex from './components/profile/ProfileIndex.vue';
import UserIndex from './components/users/UsersTable.vue';
import UserForm from './components/users/UserForm.vue';

import DonorsIndex from './components/donors/DonorsTable.vue';
import DonorForm from './components/donors/DonorForm.vue';
import DonorSearch from './components/donors/DonorSearch.vue';

import BranchesIndex from './components/branches/BranchesTable.vue';

import BranchForm from './components/branches/BranchForm.vue';

import HospitalsIndex from './components/hospitals/HospitalsTable.vue';
import HospitalForm from './components/hospitals/HospitalForm.vue';

import CoursesIndex from './components/courses/CoursesTable.vue'
import CourseForm from './components/courses/CourseForm.vue'

import Dashboard from './components/includes/Dashboard.vue';
import Login from './components/includes/LoginPage.vue';

import MembershipTypeSetting from './components/settings/MembershipType.vue';
import BloodTypeSetting from './components/settings/BloodType.vue';
import MemberRoleTypeSetting from './components/settings/MemberRoleType.vue';
import PostCategoryTypeSetting from './components/settings/PostCategoryType.vue';

import Home from './components/home/Layout.vue';
import Posts from './components/home/Posts.vue';
import ShowPost from './components/home/Post.vue';

const empty = {
    template: `
      <div>
        <h2>Post Selected: {{ $route.params.id }}</h2>
        <router-view></router-view>
      </div>
    `
  }
const routes = [
    //home page
    {path: '/', component: Home, name:'home'},
    //posts
    {path:'/news-stories', component: empty, children: [
        {
          path: '',
          component: Posts,
          name: 'posts'
        },
        {
          // UserProfile will be rendered inside User's <router-view>
          // when /user/:id/profile is matched
          path: ':id',
          component: ShowPost,
          name:'showPost'
        }
      ]},
    // {path:'/news-stories/:id', component: ShowPost, name:'showPost' },

    //admin Panel
    //login
    {path: '/login',components: {login: Login}},
    //dashboard
    {path: '/dashboard', component: Dashboard, name:'dashboard'},
    //settings
    {path: '/settings/roles', component: MemberRoleTypeSetting, name: 'roles'},
    {path: '/settings/membershipTypes', component: MembershipTypeSetting, name: 'membershipTypes'},
    {path: '/settings/bloodTypes', component: BloodTypeSetting, name: 'bloodTypes'},
    {path: '/settings/postCategories', component: PostCategoryTypeSetting, name: 'postCategories'},
    //bloodDonationRecords
    {path: '/bloodDonationRecords', component: BloodDonationIndex, name:'listBloodDonationRecords'},
    {path: '/bloodDonationRecords/create', component: BloodDonationForm, name: 'createBloodDonationRecord'},
    {path: '/bloodDonationRecords/:id/edit', component: BloodDonationForm, name: 'editBloodDonationRecord'},
    //posts
    {path: '/posts',component: PostsIndex, name:'listPosts'},
    {path: '/posts/create', component: PostForm, name: 'createPost'},
    {path: '/posts/:id/edit', component: PostForm, name: 'editPost'},
    {path: '/posts/calendar', component: PostCalendar, name: 'viewPost'},
    //users
    {path: '/users', component:UserIndex, name:'listUsers'},
    {path: '/users/:id/edit', component: UserForm, name: 'editUser'},
    {path: '/users/create', component: UserForm, name: 'createUser'},
    //user profile
    {path:'/users/:id', component: ProfileIndex, name: 'profile'},
    //donors
    {path: '/donors', component: DonorsIndex, name:'listDonors'},
    {path: '/donors/create', component: DonorForm, name: 'createDonor'},
    {path: '/donors/:id/edit', component: DonorForm, name: 'editDonor'},
    {path: '/search', component: DonorSearch, name: 'searchDonor'},
    //branches
    {path: '/branches', component: BranchesIndex, name:'listBranches'},
    {path: '/branches/:id/edit', component: BranchForm, name: 'editBranch'},
    {path: '/branches/create', component: BranchForm, name: 'createBranch'},
    //hospitals
    {path: '/hospitals', component: HospitalsIndex, name:'listHospitals'},
    {path: '/hospitals/:id/edit', component: HospitalForm, name: 'editHospital'},
    {path: '/hospitals/create', component: HospitalForm, name: 'createHospital'},
    //courses
    {path: '/courses', component: CoursesIndex, name:'listCourses'},
    {path: '/courses/:id/edit', component: CourseForm, name: 'editCourse'},
    {path: '/courses/create', component: CourseForm, name: 'createCourse'},
]
const router = new VueRouter({mode: 'history', routes })
export default router