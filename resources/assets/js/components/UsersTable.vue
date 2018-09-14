<template>
    <v-layout>
      <v-flex>
    <v-toolbar flat color="white">
      <v-toolbar-title>Members</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-btn small color="indigo" dark class="mb-2" :href="'/users/create'">New Member</v-btn>
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
      :rows-per-page-items="rowsDefaultItem"
      v-model="selected"
      :headers="headers"
      :items="members"
      :search="search"
      select-all
      item-key="name"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td :key="props.item.id" :active="props.selected" @click="props.selected = !props.selected">
          <v-checkbox
            v-model="props.selected"
            primary
            hide-details
          ></v-checkbox>
        </td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.name }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.email }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.contact }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">
          <span v-for="role in props.item.roles" :key="role.id">
            <v-chip :color="role.name === 'member' ? 'orange' : 'red'" text-color="white">
              <v-avatar>
                <v-icon>account_circle</v-icon>
              </v-avatar>
              {{role.name}}
            </v-chip>
          </span>
        </td>
        <td>
          <a :href="'/users/'+props.item.id+'/edit'" style="display: inline-flex;"> 
            <v-tooltip bottom>
              <v-icon
                slot="activator"
                small
                class="mr-2"
              >
              edit 
              </v-icon>
              <span>
                edit
              </span>
            </v-tooltip>
          </a>
          <v-tooltip bottom>
            <v-icon
              slot="activator"
              small
              @click="deleteItem(props.item)"
            >
              delete
            </v-icon>
            <span>delete</span>
          </v-tooltip>
        </td>
      </template>
      <v-alert slot="no-results" :value="true" color="error" icon="warning">
        Your search for "{{ search }}" found no results.
      </v-alert>
      <template slot="pageText" slot-scope="{ pageStart, pageStop }">
        From {{ pageStart }} - {{ pageStop }}
      </template>
    </v-data-table>

    <p>{{selected}}</p>
    <v-snackbar
      :absolute="saveSnackbar.absolute"
      :right="saveSnackbar.right"
      :top="saveSnackbar.top"
      :timeout="saveSnackbar.timeout" 
      :color="saveSnackbar.color" 
      v-model="saveSnackbar.snackbar">
    {{ saveSnackbar.text }}
    <v-btn dark flat @click.native="saveSnackbar.snackbar = false">
      <v-icon>close</v-icon>
    </v-btn>
    </v-snackbar>
  </v-flex>
</v-layout>
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
//         axios.get('api/members').then(res =>{
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
export default {
  data(){
    return{
    rowsDefaultItem: [10],
    search: '',
    saveSnackbar: {},
    selected: [],
    members: [],
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
    this.fetchMembers()
  },
  methods: {
    fetchMembers() {
      axios.get("api/members").then(res => {
        this.members = res.data;
      });
    },
    toggleAll() {
      if (this.selected.length) this.selected = [];
      else this.selected = this.members.slice();
    },
    editItem() {
      alert("");
    },
    deleteItem(selectedItem) {
      if(this.selected.length === 0)
        this.selected.push(selectedItem);
      this.selected.forEach(function(v,k){
        axios.delete('api/members/'+ v.id)
        .catch(err => console.log(err));
      })
      this.saveSnackbar = {
        absolute:true,
        right:true,
        top:true,
        snackbar: true,
        timeout: 3000,
        color: 'green',
        text: this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' user(s) deleted',
      }
      this.fetchMembers();
      this.selected = [];
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