<template>
  <v-layout>
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Branches</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <router-link :to="{name:'createBranch'}">	<v-btn small color="indigo" dark class="mb-2">New Branch</v-btn></router-link>
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
      :headers= "headers"
      :items = "branches"
      :search="search"
      select-all
      item-key="id"
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
        <td>
          <router-link :to="{name:'editBranch', params:{id: props.item.id}}">
            <v-tooltip bottom>
              <v-icon
              small
              class="mr-2"
              slot="activator"
              >
              edit 
              </v-icon>
              <span>edit</span>
            </v-tooltip>
          </router-link>
          <v-tooltip bottom>
            <v-icon
              small
              @click="deleteItem(props.item)"
              slot="activator"
            >
            delete
            </v-icon>
            <span>delete</span>
          </v-tooltip>
        </td>
      </template>
      <v-alert slot="no-results" :value="true" color="red" icon="warning">
        Your search for "{{ search }}" found no results.
      </v-alert>
    </v-data-table>
    </v-flex>
  </v-layout>
</template>
<script>
  export default {
    data() {
      return {
        rowsDefaultItem: [5],
        selected: [],
        search: '',
        branches: [],
        headers:[
          {text: "Name", align: "left", sortable: false, value: "name"},
          {text: "Actions", value: "name", sortable: false}
        ],
      };
    },
    mounted(){
      this.fetchBranches()
    },
    methods: {
      fetchBranches: function(){
        axios.get("api/branches").then(res =>{
          this.branches = res.data;
        })
      },
      toggleAll() {
        if (this.selected.length) this.selected = [];
        else this.selected = this.hospitals.slice();
      },
      deleteItem(selectedItem) {
        let self = this;
        if(self.selected.length === 0){
          self.selected.push(selectedItem)
        }
        self.selected.forEach(function(v,k){
          fetch(`/api/branch/${v.id}`, {
            method: "delete"
          })
          .then(res => res.json())
          .then(data => {
            self.fetchBranches()
          })
          .catch(err => console.log(err));
        })
        self.$toasted.success(this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' branch(es) deleted' , {icon:"check"})
        self.selected = [];
      }
    }
  }
</script>

<style lang="scss" scoped>
.md-table + .md-table {
  margin-top: 16px;
}
.md-field {
  max-width: 300px;
}
</style>  