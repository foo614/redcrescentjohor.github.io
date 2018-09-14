<template>
  <div>
    <v-layout>
      <v-flex>
        <v-card>
          <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
          <v-card-title primary-title>
            <div>
              <h3 class="headline mb-0 mt-0" >{{title}} Blood type</h3>
            </div>
          </v-card-title>
          <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="addItem">
            <v-card-text>
                <v-text-field
                  v-model="bloodType.name"
                  :rules="nameRules"
                  label="Name"
                  required
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat color="primary" @click="reset" v-show="title === 'Edit'">Add Blood Type</v-btn>
              <v-btn flat color="primary" type="submit">Submit</v-btn>
            </v-card-actions>
          </v-form>
        </v-card>
      </v-flex>
    </v-layout>
    <br/>
    <v-layout>
      <v-flex>
      <v-card flat color="white">
        <v-card-title>
          <h3 class="headline">Blood Types</h3>
          <v-divider
            class="mx-2"
            inset
            vertical
          ></v-divider>
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
        :items = "bloodTypes"
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
            <v-tooltip bottom>
              <v-icon
                small
                class="mr-2"
                @click="editItem(props.item)"
                slot="activator"
              >
              edit 
              </v-icon>
              <span>edit</span>
            </v-tooltip>
            <v-tooltip bottom>
              <v-icon
                small
                @click="deleteItem(props.item.id)"
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
  </div>
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
        bloodTypes: [],
        bloodType: {
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
      this.fetchBloodTypes()
    },
    methods: {
      fetchBloodTypes: function(){
        axios.get("api/bloodTypes")
        .then(res =>{
          this.bloodTypes = res.data;
        })
      },
      addItem(){
        this.sending = true;
        if(this.edit === false){
          setTimeout(()=> {
            fetch("api/bloodType", {
              method: "post",
              body: JSON.stringify(this.bloodType),
              headers:{"content-type": "application/json"}
            })
            .then(res => res.json())
            .then(data => {
              this.fetchBloodTypes();
              this.$refs.form.reset();
              this.sending = false;
            })
            .catch(err => console.log(err));
          }, 1000);
        }else{
          setTimeout(()=> {
          fetch("api/bloodType",{
            method: "put",
            body: JSON.stringify(this.bloodType),
            headers:{"content-type": "application/json"}
          })
          .then(res => res.json())
            .then(data => {
              this.fetchBloodTypes();
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
        this.bloodType.id = item.id;
        this.bloodType.blood_type_id = item.id;
        this.bloodType.name = item.name;
      },
      deleteItem(selectedItem){
        if(this.selected.length > 1){
          this.selected.forEach(function(v,k){
            fetch(`api/bloodType/${v.id}`,{
              method: "delete"
            })
            .then(res => res.json())
            .catch(err => console.log(err));
          });
        }else{
          fetch(`api/bloodType/${selectedItem}`,{
            method: "delete"
          })
          .then(res => res.json())
          .catch(err => console.log(err));
        }
          this.saveSnackbar = {
            absolute:true,
            right:true,
            top:true,
            snackbar: true,
            timeout: 3000,
            color: 'success',
            text: this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' user(s) deleted',
          }
          this.fetchBloodTypes();
      },
      reset () {
        this.$refs.form.reset();
        this.title = "Add";
      }
    },
  };
</script>
<style lang="css">
  #spinner {
    position: fixed;
    left: 50%;
    top: 50%;
    z-index: 100000;
  }
  .mdl-progress{
    width: 100%;
  }
</style>