<template>
  <v-layout>
      <v-flex>
        <v-toolbar flat color="white">
          <v-toolbar-title>Fundraisers</v-toolbar-title>
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
        </v-toolbar>
        <v-data-table
          v-model="selected"
          :headers="headers"
          :items="fundraisers"
          :pagination.sync="pagination"
          item-key="id"
          class="elevation-1"
          :search="search"
        >
          <template slot="items" slot-scope="props">
            <tr>
              <td>{{ props.item.title }}</td>
              <td>
                  <v-chip color="green" v-if="props.item.status === 1" text-color="white">{{ props.item.status === 1 ? "Active" : "" }}</v-chip>
                  <v-chip color="red" v-if="props.item.status === 0" text-color="white">{{ props.item.status === 0 ? "Inactive" : "" }}</v-chip>      
              </td>
              <td>{{ props.item.target_amount}}</td>
              <td>{{ props.item.user}}</td>
              <td>{{ props.item.created_at }}</td>
              <td>
                <router-link :to="{name:'editFundraiser', params:{id: props.item.id}}">
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
              </td>
            </tr>
          </template>
        </v-data-table>
      </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment';
export default {
  data: () => ({
    search: '',
    pagination: {
      sortBy: 'title',
      rowsPerPage:10
    },
    selected: [],
    headers: [
      {
        text: "Title",
        align: "left",
        value: "title"
      },
      {
        text: "",
        value: "status"
      },
      {
        text: "Target amount",
        value: "target_amount"
      },
      {
        text: "Created By",
        value: "user"
      },
      {
        text: "Created Date",
        value: "created_at"
      },
      {
          text:'Actions',
          value: ""
      }
    ],
    fundraisers: [],
  }),
  created() {
    this.fetchFundraisers()
  },
  methods: {
    fetchFundraisers() {
        axios.get("/api/fundraisers").then(res => {
            this.fundraisers = res.data;
        });
    }
  },
}
</script>

<style>

</style>
