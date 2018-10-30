<template>
    <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
        <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">{{$route.name == "editCourse" ? 'Edit' : 'Add' }} Course</div>
            </v-card-title>
                <v-container fluid grid-list-lg>
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.name"
                                label="Name"
                                prepend-icon="class"
                                :rules="[v => !!v || 'Name is required']"
                                required
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.course_fee"
                                label="Course Fee (RM)"
                                prepend-icon="attach_money"
                                required
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <!-- start date -->
                        <v-flex xs12 sm3>
                            <v-menu
                                ref="date_menu1"
                                :close-on-content-click="false"
                                v-model="date_menu1"
                                :nudge-right="40"
                                :return-value.sync="item.start_date"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.start_date"
                                label="Start Date"
                                prepend-icon="event"
                                readonly
                                ></v-text-field>
                                <v-date-picker 
                                    v-model="item.start_date" 
                                    no-title 
                                    scrollable 
                                    @input="$refs.date_menu1.save(item.start_date)"
                                    :min= currentDate>
                                <v-spacer></v-spacer>
                                </v-date-picker>
                            </v-menu>
                        </v-flex>
                        <!-- end date -->
                        <v-flex xs12 sm3>
                            <v-menu
                                ref="date_menu2"
                                :close-on-content-click="false"
                                v-model="date_menu2"
                                :nudge-right="40"
                                :return-value.sync="item.end_date"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.end_date"
                                label="End Date"
                                prepend-icon="event"
                                readonly
                                ></v-text-field>
                                <v-date-picker 
                                    v-model="item.end_date" 
                                    no-title 
                                    scrollable 
                                    @input="$refs.date_menu2.save(item.end_date)"
                                    :min= item.start_date>
                                <v-spacer></v-spacer>
                                </v-date-picker>
                            </v-menu>
                        </v-flex>
                        <!-- start time -->
                        <v-flex xs12 sm3>
                            <v-menu
                                ref="menu1"
                                :close-on-content-click="false"
                                v-model="time_menu1"
                                :nudge-right="40"
                                :return-value.sync="item.start_time"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.start_time"
                                label="Start time"
                                prepend-icon="access_time"
                                readonly
                                ></v-text-field>
                                <v-time-picker
                                v-if="time_menu1"
                                v-model="item.start_time"
                                @change="$refs.menu1.save(item.start_time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-flex>
                        <!-- end time -->
                        <v-flex xs12 sm3>
                            <v-menu
                                ref="menu2"
                                :close-on-content-click="false"
                                v-model="time_menu2"
                                :nudge-right="40"
                                :return-value.sync="item.end_time"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                            >
                                <v-text-field
                                slot="activator"
                                v-model="item.end_time"
                                label="End time"
                                prepend-icon="access_time"
                                readonly
                                ></v-text-field>
                                <v-time-picker
                                v-if="time_menu2"
                                v-model="item.end_time"
                                @change="$refs.menu2.save(item.end_time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                    <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.available_seat"
                                label="Available seat"
                                type="number"
                                prepend-icon="event_seat"
                                :rules="[v => !!v || 'Available seat is required']"
                                required
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-combobox
                                v-model="item.venue"
                                :items="branches"
                                :return-object="false"
                                item-text="name"
                                item-value="name"
                                prepend-icon="place"
                                label="Select a venue or create a new one"
                                required
                            >
                            <template slot="no-data">
                                <v-list-tile>
                                    <v-list-tile-content>
                                    <v-list-tile-title>
                                        No results matching "<strong>{{ search }}</strong>". Press <kbd>enter</kbd> to create a new one
                                    </v-list-tile-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                </template>
                            </v-combobox>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-textarea
                            v-model="item.info"
                            label="Exam Date & any information (optional)"
                            placeholder="Exam on 19/6 - 9AM"
                            prepend-icon="info"
                            auto-grow
                            rows="1"
                            ></v-textarea>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex xs12 sm12 class="mt-2">
                            <div style="display: flex;">
                                <v-tooltip bottom>
                                    <v-icon slot="activator">description</v-icon>
                                    <span>Description</span>
                                </v-tooltip>
                                <ckeditor style="width:100%" height="180px" class="ml-2" v-model="item.description" language="zh" extraplugins="divarea"/>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" type="submit">Submit</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
import moment from 'moment';
import VueCkeditor from 'vue-ckeditor2';
export default {
    components: { VueCkeditor},
    mounted() {
        this.currentDate = moment().format('YYYY-MM-DD');
        if(this.$route.name === "editCourse"){
            let app = this
            let id = this.$route.params.id
            axios.get('/api/course/' + id)
            .then(function (res) {
                app.item = res.data
                app.item.course_id = res.data.id
            })
            .catch(function () {
                this.$toasted.error("Something wrong...", {icon:"error"})
            });
        }
    },
    data () {
        return {
            branches:[],
            courses:[],
            sending: false,
            valid: true,
            item: {
                course_id:null,
                id: null,
                name: null,
                course_fee: 0,
                start_date: null,
                end_date: null,
                start_time: null,
                end_time: null,
                available_seat: null,
                venue: null,
                info: null,
                description: "course details, objective, module, prerequisite, assessment,certification, subsidy eligiibility..."
            },
            search: null,
            currentDate:'',
            date_menu1:false,
            date_menu2:false,
            time_menu1:false,
            time_menu2:false,
        };
    },
    watch: {
        // whenever question changes, this function will run
        start_time: function () {
            return this.item.start_time = moment.utc((this.item.start_date + " " + this.item.start_time)).format("YYYY-MM-DD HH:mm:ss");
        }
    },
    created(){
        this.fetchBranches();
    },
    methods:{
        fetchBranches(){
            axios.get("/api/branches").then(res => {
                this.branches = res.data
            });
        },
        saveItem(){
            if (this.$refs.form.validate()){
                this.sending = true
                setTimeout(()=> {
                    fetch("/api/course", {
                        method: this.$route.name == 'createCourse' ? "post" : "put",
                        body: JSON.stringify(this.item),
                        headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.sending = false
                        let currentPage = this.$route.name
                        this.$router.push({ path: '/courses' }, ()=> {
                            this.$toasted.success(this.item.name + (currentPage === 'createCourse' ? ' added' : ' updated') , {icon:"check"})
                        })
                    })
                    .catch(err => console.log(err))
                }, 1500)
            }
        }
    }
}
</script>

<style>

</style>
