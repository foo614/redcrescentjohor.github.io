<template>
  <v-layout>
      <v-flex>
        <v-toolbar flat color="white">
          <v-toolbar-title>Transactions</v-toolbar-title>
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
          :items="transactions"
          :pagination.sync="pagination"
          item-key="id"
          class="elevation-1"
          :search="search"
        >
          <template slot="items" slot-scope="props">
            <tr @click="props.expanded = !props.expanded">
              <td>{{ props.item.amount/100+' '+(props.item.currency.toUpperCase()) }}</td>
              <td><v-chip color="green" text-color="white">{{ props.item.status }}<v-icon right>check</v-icon></v-chip></td>
              <td>{{ props.item.description + ' ' +props.item.id }}</td>
              <td>{{ props.item.created | formatDate }}</td>
            </tr>
          </template>
          <template slot="expand" slot-scope="props">
            <v-card flat>
              <v-layout>
                <v-flex xs12>
                  <h4 class="pa-3">Card Information</h4>
                </v-flex>
              </v-layout>
              <v-layout>
                <v-flex xs6>
                  <v-layout>
                    <v-flex xs6><v-card-text class="pt-0">ID</v-card-text></v-flex>
                    <v-flex xs6><v-card-text class="pt-0">{{ props.item.source.id }}</v-card-text></v-flex>
                  </v-layout>
                </v-flex>
                <v-flex xs6>
                  <v-layout>
                    <v-flex xs6><v-card-text class="pt-0">Name</v-card-text></v-flex>
                    <v-flex xs6><v-card-text class="pt-0">{{ props.item.source.name ? props.item.source.name : 'No name provided'}}</v-card-text></v-flex>
                  </v-layout>
                </v-flex>
              </v-layout>
              <v-layout>
                <v-flex xs6>
                  <v-layout>
                    <v-flex xs6><v-card-text class="pt-0">Number</v-card-text></v-flex>
                    <v-flex xs6><v-card-text class="pt-0">•••• {{ props.item.source.last4 }}</v-card-text></v-flex>
                  </v-layout>
                </v-flex>
                <v-flex xs6>
                  <v-layout>
                    <v-flex xs6><v-card-text class="pt-0">Expires</v-card-text></v-flex>
                    <v-flex xs6><v-card-text class="pt-0">{{ props.item.source.exp_month+ '/' +props.item.source.exp_year }}</v-card-text></v-flex>
                  </v-layout>
                </v-flex>
              </v-layout>
              <v-layout>
                <v-flex xs6>
                  <v-layout>
                    <v-flex xs6><v-card-text class="pt-0">Type</v-card-text></v-flex>
                    <v-flex xs6><v-card-text class="pt-0">{{ props.item.source.brand + ' ' + props.item.source.funding + ' ' + props.item.source.object }}</v-card-text></v-flex>
                  </v-layout>
                </v-flex>
                <v-flex xs6>
                  <v-layout>
                    <v-flex xs6><v-card-text class="pt-0">Postal code</v-card-text></v-flex>
                    <v-flex xs6><v-card-text class="pt-0">{{ props.item.source.address_zip }}</v-card-text></v-flex>
                  </v-layout>
                </v-flex>
              </v-layout>
            </v-card>
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
      sortBy: 'id',
      rowsPerPage:10
    },
    selected: [],
    headers: [
      {
        text: "Amount",
        align: "left",
        value: "amount"
      },
      {
        text: "",
        value: "status"
      },
      {
        text: "Description",
        value: "id"
      },
      {
        text: "Date",
        value: "date"
      },
    ],
    transactions: [],
  }),
  created() {
    this.fetchTransactions()
  },
  methods: {
    fetchTransactions() {
        axios.get("/api/payments").then(res => {
            this.transactions = res.data;
        });
    },
  },
  filters: {
      formatDate: function (value) {
        if (!value) return ''
        return moment.unix(Number(value)).format('MMMM DD, YYYY, hh:mm a');
      },
  },
}
</script>

<style>

</style>
