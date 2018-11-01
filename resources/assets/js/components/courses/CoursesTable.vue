<template>
  <v-layout>
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Courses</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <router-link :to="{name:'createCourse'}">	<v-btn small color="indigo" dark class="mb-2">New Course</v-btn></router-link>
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
      :items = "courses"
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
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.course_fee === 0 ? "FREE" : props.item.course_fee }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.start_date + ' - '  +  props.item.end_date }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.start_time + ' - ' +  props.item.end_time }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.available_seat }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.created_at }}</td>
        <td>
          <router-link :to="{name:'editCourse', params:{id: props.item.id}}">
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
import moment from 'moment';
  export default {
    data() {
      return {
        rowsDefaultItem: [10],
        selected: [],
        search: '',
        courses: [],
        headers:[
          { text: "Name", align: "left", sortable: false, value: "name" },
          { text: "Fee", sortable: false, value: "course_fee" },
          { text: "Date", value: "date", sortable: true },
          { text: "Time", value: "time", sortable: true },
          { text: "Available seat", value: "available_seat", sortable: true },
          { text: "Created at", value: "created_at", sortable: true },
          { text: "Actions", value: "name", sortable: false }
        ],
      };
    },
    mounted(){
      this.fetchCourses()
    },
    methods: {
      fetchCourses: function(){
        axios.get("api/courses").then(res =>{
          this.courses = res.data;
        })
      },
      toggleAll() {
        if (this.selected.length) this.selected = [];
        else this.selected = this.courses.slice();
      },
      deleteItem(selectedItem) {
        let self = this;
        if(self.selected.length === 0){
          self.selected.push(selectedItem)
        }
        self.selected.forEach(function(v,k){
          fetch(`/api/course/${v.id}`, {
            method: "delete"
          })
          .then(res => res.json())
          .then(data => {
            self.fetchCourses()
          })
          .catch(err => console.log(err));
        })
        self.$toasted.success(this.selected.length === 1 ? this.selected[0].name+' deleted' : this.selected.length+' course(s) deleted' , {icon:"check"})
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