<template>
    <v-layout>
      <v-flex>
    <v-toolbar flat color="white">
      <v-toolbar-title>Donors</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-btn small color="indigo" dark class="mb-2" :href="'/donors/create'">New Donor</v-btn>
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
      :items="donors"
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
        <td :active="props.selected" @click="props.selected = !props.selected">{{props.item.blood_type['name'] }}</td>
        <td>
          <a :href="'/donors/'+props.item.id+'/edit'" style="display: inline-flex;"> 
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

export default {
  data(){
    return{
    rowsDefaultItem: [10],
    search: '',
    saveSnackbar: {},
    selected: [],
    donors: [],
    headers: [
      {
        text: "Name",
        align: "left",
        sortable: false,
        value: "name"
      },
      { text: "Email", value: "email" },
      { text: "Contact", value: "contact" },
      { text: "Blood Type", value: "blood_type" },
      { text: "Actions", value: "action", sortable: false }
    ]
    };
  },
  created() {
    this.fetchDonors()
  },
  methods: {
    fetchDonors() {
      axios.get("api/donors").then(res => {
        this.donors = res.data;
      });
    },
    toggleAll() {
      if (this.selected.length) this.selected = [];
      else this.selected = this.donors.slice();
    },
    editItem() {
      alert("");
    },
    deleteItem(selectedItem) {
      if(this.selected.length === 0)
        this.selected.push(selectedItem);
      this.selected.forEach(function(v,k){
        axios.delete('api/donor/'+ v.id)
        .catch(err => console.log(err));
      })
      this.saveSnackbar = {
        absolute:true,
        right:true,
        top:true,
        snackbar: true,
        timeout: 3000,
        color: 'green',
        text: this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' donor(s) deleted',
      }
      this.fetchDonors();
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