<template>
    <v-container fluid grid-list-md>
    <v-data-iterator
      :items="courses"
      content-tag="v-layout"
      row
      wrap
      hide-actions
    >
      <v-flex
        slot="item"
        slot-scope="props"
        xs12
        sm6
        md4
        lg3
      >
        <v-card>
          <v-card-title class="py-4 white--text" style="background:red"><h3>{{ props.item.name }}</h3></v-card-title>
          <v-divider></v-divider>
          <v-list dense>
            <v-list-tile>
              <v-list-tile-content>Course fee:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ props.item.course_fee === 0 ? "FREE" : 'RM' + props.item.course_fee }}</v-list-tile-content>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-content>Start on:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ props.item.start_date }}</v-list-tile-content>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-content>End at:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ props.item.carbs }}</v-list-tile-content>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-content>Time period:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ props.item.start_time + ' - ' + props.item.end_time }}</v-list-tile-content>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-content>Available Seat(s)</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ props.item.available_seat }}</v-list-tile-content>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-content>Venue</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ props.item.venue }}</v-list-tile-content>
            </v-list-tile>
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
              <v-list-tile slot="activator">
                <v-list-tile-action>
                  <v-btn flat>Find Out More <v-icon color="grey lighten-1" class="ml-1">info</v-icon></v-btn>
                </v-list-tile-action>
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
          </v-list>
        </v-card>
      </v-flex>
    </v-data-iterator>
  </v-container>
</template>

<script>
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
    }
}
</script>

<style>

</style>
