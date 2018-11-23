<template>
      <v-layout>
      <v-flex>
        <v-toolbar flat color="white">
          <v-toolbar-title>Courses Enrollment</v-toolbar-title>
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
          :items="courses_enrollment"
          :pagination.sync="pagination"
          item-key="id"
          class="elevation-1"
          :search="search"
        >
          <template slot="items" slot-scope="props">
            <tr @click="props.expanded = !props.expanded">
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.start_date | formatDate }}</td>
            </tr>
          </template>
          <template slot="expand" slot-scope="props">
            <!-- <v-card flat>
              <v-layout>
                <v-flex xs12>
                  <h4 class="pa-3">Course Enrollment List - {{props.item.users.length}} person have been registered.</h4>
                </v-flex>
              </v-layout>
              <v-layout v-for="user in props.item.users" :key="user.id">
                <v-flex xs6>
                  <v-layout>
                    <v-flex xs4><v-card-text class="pt-0"><v-icon>person</v-icon>{{ user.name }}</v-card-text></v-flex>
                    <v-flex xs4><v-card-text class="pt-0"><v-icon>contact_mail</v-icon>{{ user.email }}</v-card-text></v-flex>
                    <v-flex xs4><v-card-text class="pt-0"><v-icon>contact_phone</v-icon>{{ user.contact }}</v-card-text></v-flex>
                  </v-layout>
                </v-flex>
              </v-layout>
            </v-card> -->
            <v-layout>
              <v-flex xs12>
                <h4 class="pa-3">Total Enrollment - {{props.item.users.length}} person have been registered.</h4>
              </v-flex>
            </v-layout>
            <v-data-table
              :items="props.item.users"
              class="elevation-1"
              hide-actions
              hide-headers
              style="max-height:300px; overflow:auto;"
            >
              <template slot="items" slot-scope="props">
                <td><v-icon style="margin-bottom: -4px; margin-right: 10px;">person</v-icon><label>{{ props.item.name }}</label></td>
                <td><v-icon style="margin-bottom: -4px; margin-right: 10px;">contact_mail</v-icon><label>{{ props.item.email }}</label></td>
                <td><v-icon style="margin-bottom: -4px; margin-right: 10px;">contact_phone</v-icon><label>{{ props.item.contact }}</label></td>
              </template>
            </v-data-table>
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
        text: "Couse Name",
        align: "left",
        value: "name"
      },
      {
        text: "Start Date",
        value: "start_date"
      }
    ],
    courses_enrollment: [],
  }),
  created() {
    this.fetchcourses_enrollment()
  },
  methods: {
    fetchcourses_enrollment() {
        axios.get("/api/coursesEnrollment").then(res => {
            this.courses_enrollment = res.data;
        });
    },
  },
  filters: {
      formatDate: function (value) {
        if (!value) return ''
        return  moment(value).format("YYYY-MM-DD")
      },
  },
}
</script>

<style>

</style>
