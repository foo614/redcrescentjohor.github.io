<template>
<div>
    <v-navigation-drawer
      fixed
      :clipped="$vuetify.breakpoint.mdAndUp"
      app
      v-model="drawer"
      width=250
      v-if="authCheck==1"
    >
      <v-list dense>
        <v-list-tile href="/">
          <v-icon>laptop</v-icon>
          <v-list-tile-content>
            <v-list-tile-title style="margin-left: 35px;">
              Website
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <template v-for="item in items">
          <v-list-group
            v-if="item.children"
            v-model="item.model"
            :key="item.text"
            :prepend-icon="item.icon"
          >
            <v-list-tile slot="activator">
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ item.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile
              v-for="(child, i) in item.children"
              :key="i"
              exact
              :to="child.link"
            >
              <v-list-tile-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ child.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list-group>
          <v-list-tile v-else :key="item.text">
            <v-list-tile-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>
                {{ item.text }}
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar
      color="white"
      app
      :clipped-left="$vuetify.breakpoint.mdAndUp"
      fixed
    >
        <img src="/img/64x64.png" height="38px" width="38px">
        <router-link to="/" style="text-decoration:none; color:black"><span class="hidden-xs-and-down" style="font-weight: 500; font-size: 18px;">Red Crescent Johor</span></router-link>
        <v-toolbar-side-icon @click.stop="drawer = !drawer" v-if="authCheck==1"></v-toolbar-side-icon>
      <v-spacer></v-spacer>
        <v-menu offset-y v-model="showMenu" v-if="authCheck==1">
            <v-avatar size="36" v-if="mutableAuth.avatar" slot="activator">
              <img :src="'/img/'+mutableAuth.avatar" alt="mutableAuth.avatar">
            </v-avatar>
            <v-avatar color="#757575" slot="activator" v-else>
              <span class="white--text headline">{{mutableAuth.name | getFirstLetter}}</span>              
            </v-avatar> 
          <v-list style="min-width: 250px;">
            <v-list-tile :to="{ name: 'profile', params: { id: mutableAuth.id}}">
              <v-list-tile-avatar color="#757575">
                <img :src="/img/+mutableAuth.avatar" :alt="mutableAuth.avatar" v-if="mutableAuth.avatar">
                <span class="white--text headline" v-else>{{mutableAuth.name | getFirstLetter}}</span>
              </v-list-tile-avatar>
              <v-list-tile-content>
                <v-list-tile-title>{{mutableAuth.name}}</v-list-tile-title>
                <v-list-tile-sub-title>{{mutableAuth.email}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider inset></v-divider>
            <v-list-tile @click="logout">
              <v-list-tile-action>
                <v-icon style="transform: rotate(270deg);">save_alt</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>Logout</v-list-tile-title>
              </v-list-tile-content>
              <form style="display: hidden" action="/logout" method="POST" id="logout-form">
                <input type="hidden" name="_token" :value="csrf_token"/>
              </form>
            </v-list-tile>
          </v-list>
        </v-menu>
    </v-toolbar>
  </div>
</template>

<script>
export default {
  props: 
    ['auth', 'authCheck']
  ,
  data: () => ({
    csrf_token: window.csrf_token,
    showMenu: false,
    dialog: false,
    drawer: null,
    menu: false,
    items: [
      {
        accessBy: "master_admin",
        icon: "people",
        text: "Member",
        children: [
          { text: "Add member", link: "/users/create" },
          { text: "Manage member", link: "/users" }
        ]
      },
      {
        accessBy: "administrator",
        icon: "supervised_user_circle",
        text: "Donor",
        children: [
          { text: "Add donor", link: "/donors/create" },
          { text: "Manage donor", link: "/donors" },
          { text: "Search donor", link: "/search" }
        ]
      },
      {
        accessBy: "administrator",
        icon: "local_hospital",
        text: "Hospital",
        children: [
          { text: "Add hospital", link: "/hospitals/create" },
          { text: "Manage hospital", link: "/hospitals" }
        ]
      },
      {
        accessBy: "administrator",
        icon: "bookmarks",
        text: "Course",
        children: [
          { text: "Add course", link: "/courses/create" },
          { text: "Manage course", link: "/courses" }
        ]
      },
      {
        accessBy: "administrator",
        icon: "event_note",
        text: "Post",
        children: [
          { text: "Add post", link: "/posts/create" },
          { text: "Manage post", link: "/posts" },
          { text: "View event", link: "/posts/calendar" },
        ]
      },
      {
        accessBy: "administrator",
        icon: "event_note",
        text: "Blood Donation",
        children: [
          { text: "Add record", link: "/bloodDonationRecords/create" },
          { text: "Manage record", link: "/bloodDonationRecords" }
        ]
      },
      {
        accessBy: "administrator",
        icon: "home",
        text: "Branch",
        children: [
          { text: "Add branch", link: "/branches/create" },
          { text: "Manage branch", link: "/branches" }
        ]
      },
      {
        accessBy: "master_admin",
        icon: "settings",
        text: "Setting",
        model: false,
        children: [
          { text: "Role", link: "/settings/roles", name:'roles' },
          { text: "Membership type", link: "/settings/membershipTypes", name:"membershipTypes" },
          { text: "Blood type", link: "/settings/bloodTypes", name:"bloodTypes" },
          { text: "Post category type", link: "/settings/postCategories", name:"postCategories" }
        ]
      }
    ],
    mutableAuth:{}
  }),
  created() {
    this.mutableAuth = this.auth ? JSON.parse(this.auth) : "";
  },
  methods: {
    logout() {
      document.getElementById("logout-form").submit();
    }
  },
  filters: {
    getFirstLetter: function(value) {
      if (!value) return "";
      return value
        .split(" ")
        .map(function(item) {
          return item[0];
        })
        .join("");
    }
  }
};
</script>

<style>
</style>

