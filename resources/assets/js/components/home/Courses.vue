<template>
    <v-container fluid grid-list-md>
      <v-data-iterator
        :items="courses"
        content-tag="v-layout"
        row
        wrap
        hide-actions
      >
        <v-toolbar
          slot="header"
          class="mb-2"
          style="background-color:#ca0000"
          dark
          flat
        >
          <v-toolbar-title>Public Course Registration</v-toolbar-title>
        </v-toolbar>
        <v-flex
          slot="item"
          slot-scope="props"
          xs12
          sm6
          md4
          lg3
        >
          <v-card>
            <v-card-title class="py-4 white--text" style="background:#ca0000"><h3>{{ props.item.name }}</h3></v-card-title>
            <v-divider></v-divider>
            <v-list dense>
              <v-list-tile>
                <v-list-tile-content>Course fee:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ props.item.course_fee === 0 ? "FREE" : 'RM' + props.item.course_fee }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content>Date:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ props.item | formatDate }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content>Time period:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ props.item.start_time | formatTime }} - {{ props.item.end_time | formatTime }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content>Available Seat(s)</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ props.item.available_seat - props.item.computed.total }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content>Venue</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ props.item.venue }}</v-list-tile-content>
              </v-list-tile>
              <div class="action-btn--center">
              <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-list-tile slot="activator">
                    <v-btn flat>Find Out More <v-icon color="grey lighten-1" class="ml-1">info</v-icon></v-btn>
                </v-list-tile>
                <v-card>
                  <v-toolbar color="white">
                    <v-btn icon @click.native="dialog = false">
                      <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Course Details</v-toolbar-title>
                  </v-toolbar>
                  <v-container>
                    <v-layout>
                      <v-flex xs12>
                        <v-card-text v-html="props.item.description"></v-card-text>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
              </v-dialog>
              </div>
              <v-list-tile>
                <div class="action-btn--center">
                  <v-btn :href="`/course-registration/${props.item.id}/register`" flat v-text="props.item.available_seat == 0 ? 'Registration is closed' : 'Register Now'"> </v-btn>
                </div>
              </v-list-tile>
            </v-list>
          </v-card>
        </v-flex>
      </v-data-iterator>
  </v-container>
</template>

<script>
import moment from 'moment'
export default {
    data(){
        return{
            courses:[],
            dialog: false
        }
    },
    mounted(){
        this.fetchCourses()
    },
    methods:{
        fetchCourses: function(){
            axios.get("api/courses").then(res => {
                this.courses = res.data
            })
        }
    },
    filters:{
      formatTime: function(val){
        if(!val) return ''
        return moment(val,"HH:mm:ss").format("HH:mm")
      },
      formatDate: function(obj){
        var start = moment(obj.start_date)
        var end = moment(obj.end_date)
        return start.isSame(end, 'day') ? start.format("DD MMM") : start.format("DD MMM") + " - " + end.format("DD MMM")
      }
    }
}
</script>

<style>
  .action-btn--center{
    width: 100%;
    text-align: center;
  }
</style>
