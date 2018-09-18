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
              :href="child.link"
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
      <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
        <v-toolbar-side-icon @click.stop="drawer = !drawer" v-if="authCheck==1"></v-toolbar-side-icon>
        <span class="hidden-sm-and-down">Red Crescent Johor</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <button id="drop-item" class="theme--dark" v-if="authCheck==1">
        <v-avatar size="36" v-if="mutableAuth.avatar">
          <img :src="'/img/'+mutableAuth.avatar" alt="mutableAuth.avatar">
        </v-avatar>
        <v-avatar class="mdl-list__item-avatar" v-else>
          <span class="white--text headline">{{mutableAuth.name | getFirstLetter}}</span>              
        </v-avatar>
      </button>
    <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right demo-list-icon mdl-list" style="padding-top:0;" for="drop-item" v-if="authCheck==1">
      <!-- <router-link class="mdl-menu__item mdl-list__item--two-line" style="width: 280px;height: 72px; background-color:#eeeeee;padding:10px;" 
      :to="{ name: 'profile', params: { id: mutableAuth.id}}"> -->
      <li class="mdl-menu__item mdl-list__item--two-line" style="width: 280px;height: 72px; background-color:#eeeeee;padding:10px;" >
        <a :href="'/users/'+mutableAuth.id" style="text-decoration:none !important; color: inherit;">
          <span class="mdl-list__item-primary-content">
            <v-avatar style="margin-right: 16px;" class="material-icons mdl-list__item-avatar" v-if="mutableAuth.avatar">
              <img :src="/img/+mutableAuth.avatar" :alt="mutableAuth.avatar">
            </v-avatar>
            <v-avatar style="margin-right: 16px;" class="material-icons mdl-list__item-avatar" v-else>
              <span class="white--text headline">{{mutableAuth.name | getFirstLetter}}</span>
            </v-avatar>
            <span>{{mutableAuth.name}}</span>
            <span class="mdl-list__item-sub-title">{{mutableAuth.email}}</span>
          </span>
        </a>
      </li>
        <li class="mdl-menu__item mdl-list__item" @click="logout">
            <a title="logout" style="text-decoration: none; color: rgba(0,0,0,.87);">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="transform: rotate(270deg);">save_alt</i>Sign out
                </span>
            </a>
            <form style="display: hidden" action="/logout" method="POST" id="logout">
              <input type="hidden" name="_token" :value="csrf_token"/>
            </form>
        </li>
    </ul>
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
    dialog: false,
    drawer: null,
    menu: false,
    items: [
      { icon: "laptop", text: "Website" },
      {
        icon: "people",
        text: "Member",
        children: [
          { text: "Add member", link: "/users/create" },
          { text: "Manage member", link: "/users" }
        ]
      },
      {
        icon: "supervised_user_circle",
        text: "Donor",
        children: [
          { text: "Add donor", link: "/donors/create" },
          { text: "Manage donor", link: "/donors" },
          { text: "Search donor", link: "/donors/search" }
        ]
      },
      {
        icon: "local_hospital",
        text: "Hospital",
        children: [
          { text: "Add hospital", link: "/hospitals/create" },
          { text: "Manage hospital", link: "/hospitals" }
        ]
      },
      {
        icon: "bookmarks",
        text: "Course",
        children: [
          { text: "Add course", link: "/courses/create" },
          { text: "Manage course", link: "/courses" }
        ]
      },
      {
        icon: "event_note",
        text: "Post",
        children: [
          { text: "Add post", link: "/posts/create" },
          { text: "Manage post", link: "/posts" }
        ]
      },
      {
        icon: "home",
        text: "Branch",
        children: [
          { text: "Add branch", link: "/branches/create" },
          { text: "Manage branch", link: "/branches" }
        ]
      },
      {
        icon: "settings",
        text: "Setting",
        model: false,
        children: [
          { text: "Role", link: "/roles" },
          { text: "Membership type", link: "/membershipTypes" },
          { text: "Blood type", link: "/bloodTypes" },
          { text: "Post category type", link: "/postCategories" }
        ]
      }
    ],
    mutableAuth:{}
  }),
  created() {
    this.mutableAuth = JSON.parse(this.auth);
  },
  methods: {
    logout() {
      document.getElementById("logout").submit();
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
/* .v-menu__content{
   left: -100% !important; 
   min-width: 250px !important; 
} */
</style>

