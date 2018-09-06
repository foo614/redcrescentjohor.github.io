<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>Members List</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-btn small color="indigo" dark class="mb-2">New User</v-btn>
        <v-btn small color="red" v-show="selected.length > 0"  @click="deleteItem(selected)" dark class="mb-2">Delete</v-btn>
        <v-spacer></v-spacer>
        <v-text-field
          append-icon="search"
          label="Search"
          single-line
          hide-details
          v-model="search"
        ></v-text-field>
    </v-toolbar>
      <v-data-table
      v-model="selected"
      :headers="headers"
      :items="users"
      :search="search"
      select-all
      item-key="name"
      class="elevation-1"
    >
      <template slot="headerCell" slot-scope="props">
        <span>
          {{ props.header.text }}
        </span>
      </template>
      <template slot="items" slot-scope="props">
          <td :key="props.item.id">
            <v-checkbox
              v-model="props.selected"
              primary
              hide-details
            ></v-checkbox>
          </td>
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.email }}</td>
          <td>{{ props.item.contact }}</td>
          <td>
            <span v-for="role in props.item.roles" :key="role.id">
              <v-chip :color="role.name === 'member' ? 'orange' : 'red'" text-color="white">
                <v-avatar>
                  <v-icon>account_circle</v-icon>
                </v-avatar>
                {{role.name}}
              </v-chip>
            </span>
          </td>
          <td class="layout px-0 ml-4">
            <a :href="'/users/'+props.item.id+'/edit'" style="display: inline-flex;"> 
            <v-icon
              small
              class="mr-2"
            >
             edit 
            </v-icon>
            </a>
            <v-icon
              small
              @click="deleteItem(props.item)"
            >
              delete
            </v-icon>
          </td>
      </template>
    </v-data-table>

  <p>{{selected}}</p>
  <v-snackbar :bottom="true"
            :absolute="true"
            :timeout="saveSnackbar.timeout"
            :color="saveSnackbar.color"
            v-model="saveSnackbar.snackbar">
            {{ saveSnackbar.text }}
            <v-btn dark flat @click.native="saveSnackbar.snackbar = false">
              <v-icon>close</v-icon>
            </v-btn>
</v-snackbar>
</div>
</template>

<script>
// import axios from 'axios';

// const toLower = text => {
//   return text.toString().toLowerCase()
// }

// const searchByName = (items, term) => {
//   if (term) {
//     return items.filter(
//       item => toLower(item.name).includes(toLower(term))
//     );
//   }
//   return items
// }
//   export default {
//     data: () => ({
//       search: null,
//       selected: [],
//       items:[],
//       data:[],
//     }),
//     created(){
//       this.fetchPosts();
//     },
//     methods: {
//       fetchPosts: function (){
//         axios.get('api/users').then(res =>{
//           this.data = res.data;
//           this.items = this.data;
//         })
//       },
//       onSelect (items) {
//         this.selected = items
//       },
//       getAlternateLabel (count) {
//         let plural = ''

//         if (count > 1) {
//           plural = 's'
//         }

//         return `${count} user${plural} selected`
//       },
//       searchOnTable () {
//         this.items = searchByName(this.data, this.search);
//       }
//     }
//   }
import axios from "axios";
export default {
  data(){
    return{
    search: '',
    saveSnackbar: {
        snackbar: false,  /* Initial state */
        icon: '',
        color: '',
        timeout: null,  /* Milliseconds */
        text: ''
    },
    selected: [],
    users: [],
    headers: [
      {
        text: "Name",
        align: "left",
        sortable: false,
        value: "name"
      },
      { text: "Email", value: "email" },
      { text: "Contact", value: "contact" },
      { text: "Roles", value: "roles" },
      { text: "Actions", value: "name", sortable: false }
    ]
    };
  },
  created() {
    this.fetchUsers()
  },
  methods: {
    fetchUsers: function() {
      axios.get("api/users").then(res => {
        this.users = res.data;
      });
    },
    toggleAll() {
      if (this.selected.length) this.selected = [];
      else this.selected = this.users.slice();
    },
    editItem() {
      alert("");
    },
    deleteItem(selectedItem) {
      if(this.selected.length === 0) 
        this.selected.push(selectedItem);
        this.selected.forEach(function(v, k){
          fetch(`api/users/${v.id}`, {
            method: "delete"
          })
          .catch(err => console.log(err));
        })
        this.saveSnackbar = {
          snackbar: true,
          timeout: 6000,
          color: 'green',
          text: this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' user(s) deleted',
        }
    }
  }
};
</script>

<style lang="scss" scoped>
.md-table + .md-table {
  margin-top: 16px;
}
.md-field {
  max-width: 300px;
}
</style>