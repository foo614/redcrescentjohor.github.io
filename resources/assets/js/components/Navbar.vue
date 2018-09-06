<template>
<div>
    <v-navigation-drawer
      fixed
      :clipped="$vuetify.breakpoint.mdAndUp"
      app
      v-model="drawer"
      width=250
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
              @click=""
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
      color="transparent"
      app
      :clipped-left="$vuetify.breakpoint.mdAndUp"
      fixed
    >
      <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
        <span class="hidden-sm-and-down">Red Crescent Johor</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
  </div>
</template>

<script>
export default {
data: () => ({
      dialog: false,
      drawer: null,
       menu: false,
      items: [
        {   icon: 'laptop', text: 'Website' },
        {   icon: 'people',
            text: 'Member',
            children: [
                { text: 'Add member'},
                { text: 'Manage member'}
            ]
        },
        {   icon: 'supervised_user_circle', 
            text: 'Donor',
            children: [
                { text: 'Add donor'},
                { text: 'Manage donor'},
                { text: 'Search donor'}
            ]
        },
        {   icon: 'local_hospital', 
            text: 'Hospital',
            children: [
                { text: 'Add hospital'},
                { text: 'Manage hospital'}
            ]
        },
        {   icon: 'bookmarks', 
            text: 'Course',
            children: [
                { text: 'Add course'},
                { text: 'Manage course'},
            ]
        },
        {   icon: 'event_note', 
            text: 'Post',
            children: [
                { text: 'Add post'},
                { text: 'Manage post'},
            ]
        },
        {
          icon: 'settings',
          text: 'Setting',
          model: false,
          children: [
            { text: 'Role' },
            { text: 'Membership type' },
            { text: 'Blood type' },
            { text: 'Post category type' }
          ]
        }
      ],
    }),
    props:{
      authName:String,
      authEmail:String,
      authAvatar:String
    }
}
</script>

<style>
.v-menu__content{
  left: -100% !important;
  /* min-width: 250px !important; */
}
</style>

