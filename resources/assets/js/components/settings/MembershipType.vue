<template>
  <div>
    <v-layout>
      <v-flex>
        <v-card>
          <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
          <v-card-title primary-title>
            <div>
              <h3 class="headline mb-0 mt-0" >{{title}} Membership Type</h3>
            </div>
          </v-card-title>
          <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
            <v-card-text>
              <v-text-field
                v-model="membershipType.name"
                :rules="[v => !!v || 'Name is required']"
                label="Name"
                required
              ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn flat color="primary" @click="reset" v-show="title === 'Edit'">Add Membership Type</v-btn>
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
          <h3 class="headline">Membership Types</h3>
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
        :items = "membershipTypes"
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
  </div>
</template>
<script>
  export default {
    data() {
      return {
        title: "Add",
        valid: true,
        rowsDefaultItem: [5],
        selected: [],
        search: '',
        membershipTypes: [],
        membershipType: {
          id: "",
          name: ""
        },
        headers:[
          {text: "Name", align: "left", sortable: false, value: "name"},
          { text: "Actions", value: "name", sortable: false }
        ],
        sending: false,
        edit: false,
      };
    },
    created(){
      this.fetchMembershipTypes()
    },
    methods: {
      fetchMembershipTypes: function(){
        axios.get("/api/membershipTypes")
        .then(res =>{
          this.membershipTypes = res.data;
        })
      },
      saveItem(){
        this.sending = true;
          setTimeout(()=> {
            fetch("/api/membershipType", {
              method: this.edit === false ? "post" : "put",
              body: JSON.stringify(this.membershipType),
              headers:{"content-type": "application/json"}
            })
            .then(res => {
                this.$toasted.success(this.membershipType.name + (this.edit === false ? ' added' : ' updated') , {icon:"check"})
              this.fetchMembershipTypes();
                this.$refs.form.reset();
                this.sending = false;
                this.title = "Add";
                this.edit = false;
            })
            .catch(err => console.log(err));
          }, 1500);
      },
      editItem(item) {
        this.edit = true;
        this.title = "Edit";
        this.membershipType.id = item.id;
        this.membershipType.membership_type_id = item.id;
        this.membershipType.name = item.name;
      },
      deleteItem(selectedItem){
        let self = this;
        if(self.selected.length === 0){
          self.selected.push(selectedItem)
        }
        self.selected.forEach(function(v,k){
          fetch(`/api/membershipType/${v.id}`, {
            method: "delete"
          })
          .then(res => res.json())
          .then(data => {
            self.fetchMembershipTypes()
          })
          .catch(err => console.log(err));
        })
        self.$toasted.success(this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' user(s) deleted' , {icon:"check"})
        self.selected = []
      },
      reset () {
        this.$refs.form.reset();
        this.title = "Add";
      }
    },
  };
</script>