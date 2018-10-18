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
        <router-link :to="{name:'createDonor'}">	<v-btn small color="indigo" dark class="mb-2">New Donor</v-btn></router-link>
        <v-btn small color="red" v-show="selected.length > 0"  @click="deleteItem(selected)" dark class="mb-2">Delete</v-btn>
        <v-select
          label="Blood Type"
          :items="blood_types"
          v-model="blood_type"
          item-text="name"
          multiple
          chips
          deletable-chips=""
        ></v-select>
        <v-spacer></v-spacer>
        <v-text-field
          append-icon="search"
          label="Search"
          v-model="search"
        ></v-text-field>
    </v-toolbar>
      <v-data-table
      :rows-per-page-items="rowsDefaultItem"
      v-model="selected"
      :headers="headers"
      :items="filteredItems"
      :search="search"
      select-all
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
          <router-link :to="{name:'editDonor', params:{id: props.item.id}}">
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
          </router-link>
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
  </v-flex>
</v-layout>
</template>

<script>

export default {
  data(){
    return{
    rowsDefaultItem: [10],
    blood_types:[],
    search: '',
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
    ],
    blood_type: []
    };
  },
  computed: {
    filteredItems() {
      return this.donors.filter((donor) =>{
          return (this.blood_type.length < 1) || (this.blood_type.includes(donor.blood_type['name']))
      })
    }
  },
  created() {
    this.fetchDonors()
  },
  methods: {
    fetchDonors() {
      axios.get("api/donors").then(res => {
        this.donors = res.data;
      });
      axios.get("api/bloodTypes").then(res => {
        this.blood_types = res.data;
      });
    },
    toggleAll() {
      if (this.selected.length) this.selected = [];
      else this.selected = this.donors.slice();
    },
    deleteItem(selectedItem) {
      let self = this;
      if(self.selected.length === 0){
        self.selected.push(selectedItem)
      }
      self.selected.forEach(function(v,k){
        fetch(`/api/member/${v.id}`, {
          method: "delete"
        })
        .then(res => res.json())
        .then(data => {
          self.fetchDonors()
        })
        .catch(err => console.log(err));
      })
      self.$toasted.success(this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' donor(s) deleted' , {icon:"check"})
      self.selected = []
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