<template>
  <v-layout>
    <v-flex>
    <v-card flat color="white">
      <v-card-title>
        <h3 class="headline">Branches</h3>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-btn small color="indigo" dark class="mb-2" :href="'/branches/create'">New Branch</v-btn>
        <v-btn small color="red" v-show="selected.length > 0"  @click="deleteItem(selected)" dark class="mb-2">Delete</v-btn>
        <v-spacer></v-spacer>
        <v-text-field
          append-icon="search"
          label="Search"
          single-line
          hide-details
          v-model="search"
        ></v-text-field>
      </v-card-title>
    </v-card>
      <v-data-table
      :rows-per-page-items="rowsDefaultItem"
      v-model="selected"
      :headers= "headers"
      :items = "branches"
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
        <td>
          <a :href="'/branches/'+props.item.id+'/edit'" style="display: inline-flex;"> 
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
          </a>
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
  export default {
    data() {
      return {
        title: "Add",
        valid: true,
        saveSnackbar: {},
        rowsDefaultItem: [5],
        selected: [],
        search: '',
        branches: [],
        branch: {
          id: "",
          name: ""
        },
        headers:[
          {text: "Name", align: "left", sortable: false, value: "name"},
          { text: "Actions", value: "name", sortable: false }
        ],
        sending: false,
        edit: false,
        nameRules: [
          v => !!v || 'Name is required'
        ],
      };
    },
    created(){
      this.fetchBranches()
    },
    methods: {
      fetchBranches: function(){
        axios.get("api/branches")
        .then(res =>{
          this.branches = res.data;
        })
      },
      addItem(){
        this.sending = true;
        if(this.edit === false){
          setTimeout(()=> {
            fetch("api/branch", {
              method: "post",
              body: JSON.stringify(this.branch),
              headers:{"content-type": "application/json"}
            })
            .then(res => res.json())
            .then(data => {
              this.fetchBranches();
              this.sending = false;
              this.$refs.form.reset();
            })
            .catch(err => console.log(err));
          }, 1000);
        }else{
          setTimeout(()=> {
          fetch("api/branch",{
            method: "put",
            body: JSON.stringify(this.branch),
            headers:{"content-type": "application/json"}
          })
          .then(res => res.json())
            .then(data => {
              this.fetchBranches();
              this.$refs.form.reset();
              this.sending = false;
              this.title = "Add";
              this.edit = false;
            })
            .catch(err => console.log(err));
            }, 1000);
        }
      },
      editItem(item) {
        this.edit = true;
        this.title = "Edit";
        this.branch.id = item.id;
        this.branch.branch_id = item.id;
        this.branch.name = item.name;
      },
      deleteItem(selectedItem){
        if(this.selected.length > 1){
          this.selected.forEach(function(v,k){
            fetch(`api/branch/${v.id}`,{
              method: "delete"
            })
            .then(res => res.json())
            .catch(err => console.log(err));
          });
          this.fetchBranches();
        }else{
          fetch(`api/branch/${selectedItem.id}`,{
            method: "delete"
          })
          .then(res => res.json())
          .then(data => {
            this.fetchBranches();
          })
          .catch(err => console.log(err));
        }
          this.saveSnackbar = {
            absolute:true,
            right:true,
            top:true,
            snackbar: true,
            timeout: 3000,
            color: 'success',
            text: this.selected.length > 1 ? this.selected.length+' branches deleted' : selectedItem.name+' deleted',
          }
          this.$refs.form.reset();
          this.fetchBranches();
      },
      reset () {
        this.$refs.form.reset();
        this.title = "Add";
      }
    },
  };
</script>