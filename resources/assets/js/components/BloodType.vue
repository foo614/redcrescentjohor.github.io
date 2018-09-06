<template>
  <div>
    <div class="mdl-grid mt-0 pt-0">
      <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-progress mdl-js-progress mdl-progress__indeterminate" v-if="sending"></div>
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Add / Edit Blood type</h2>
        </div>
        <form @submit.prevent="addItem">
        <div class="mdl-card__supporting-text" style="padding:0;">
            <fieldset style="padding: 0px 16px 0px;">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop mt-0">
                        <v-text-field
                          v-model="bloodType.name"
                          label="Name"
                          required
                        ></v-text-field>
                    </div>
                </div>
            </fieldset>
            <div class="mdl-card__actions mdl-card--border">
              <v-btn color="red" dark :disabled="sending" type="submit">Submit</v-btn>
            </div> 
        </div>
        </form>
      </div>
    </div> 
    <div>
      <v-card flat color="white">
        <v-card-title>
          <h3 class="headline">Blood List</h3>
          <v-divider
            class="mx-2"
            inset
            vertical
          ></v-divider>
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
        v-model="selected"
        :headers= "headers"
        :items = "bloodTypes"
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
            <td class="layout px-0 ml-4">
              <v-icon
                small
                class="mr-2"
                @click="editItem(props.item)"
              >
              edit 
              </v-icon>
              <v-icon
                small
                @click="deleteItem(props.item)"
              >
                delete
              </v-icon>
            </td>
        </template>
        <v-alert slot="no-results" :value="true" color="red" icon="warning">
          Your search for "{{ search }}" found no results.
        </v-alert>
      </v-data-table>
      <p>{{selected}}</p>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
  export default {
    data() {
      return {
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
        edit: false
      };
    },
    created(){
      this.fetchBloodTypes()
    },
    methods: {
      fetchBloodTypes: function(){
        axios.get("api/bloodTypes").then(res =>{
          this.bloodTypes = res.data;
        })
      },
      addItem(){
        if(this.edit === false){
          this.sending = true;

          setTimeout(()=> {
            fetch("api/bloodType", {
              method: "post",
              body: JSON.stringify(this.bloodType),
              headers:{"content-type": "application/json"}
            })
            .then(res => res.json())
            .then(data => {
              this.bloodType.name = "";
              this.fetchBloodTypes();
            })
            .catch(err => console.log(err));
          }, 1500);
        }else{
          fetch("api/bloodType",{
            method: "put",
            body: JSON.stringify(this.bloodType),
            headers:{"content-type": "application/json"}
          })
          .then(res => res.json())
            .then(data => {
              this.bloodType.name = "";
              this.fetchBloodTypes();
            })
            .catch(err => console.log(err));
        }
      },
      editItem(item) {
        this.edit = true;
        this.bloodType.id = item.id;
        this.bloodType.blood_type_id = item.id;
        this.bloodType.name = item.name;
      },
      deleteItem(selectedItem){
        if(this.selected.length === 0)
          this.selected.push(selectedItem);
          this.selected.forEach(function(v,k){
            // fetch(`api/bloodType/${v.id}`, {})
            console.log('delete' + v.id)
            // .catch(err => console.log(err));
          })
          
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