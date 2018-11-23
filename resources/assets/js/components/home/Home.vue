<template>
    <div>
        <v-parallax src="img/hero.jpg">
            <v-layout column align-center justify-center class="white--text">
                <label class="display-4 white--text text-xs-center">Donate</label>
                <label class="display-3">for a Better World</label>
                <!-- <transition name="fade" v-if="show"><label>{{bannerText}}</label></transition> -->
                <div class="subheading white--text text-xs-center my-3">
                   Works around the globe to save lives, defeat poverty and achieve social justice.
                </div>
                <div class="mt-4">
                    <v-btn round color="#ca0000" large dark href="/fundraisers-campaign">Donate Now</v-btn>
                    <v-btn color="#ca0000" round large dark>Read More</v-btn>
                </div>
            </v-layout>
        </v-parallax>
        <v-content>
            <div style="transform-origin: center top 0px;">
                <v-container fill-height>
                    <v-layout wrap>
                        <v-flex xs12 sm8>
                            <v-container grid-list-xl pa-0>
                                <v-flex xs12 class="mb-3">
                                    <h2 class="section-title">Latest Articles</h2>
                                </v-flex>
                                <v-layout row wrap justify-center class="mr-1">
                                    <v-flex xs12 sm6 d-flex v-for="post in posts" :key="post.id">
                                        <v-card flat tile style="height:auto">
                                            <v-img height="200" style="border-bottom: 6px solid #B30909;" aspect-ratio="2.75" :src="'img/'+post.cover_img"></v-img>
                                            <v-card-title  primary-title>
                                                <h3 class="headline mb-0 text-truncate">{{post.title}}</h3>
                                            </v-card-title>
                                            <v-card-actions>
                                                <v-icon color="red darken-2">calendar_today</v-icon> <label class="ml-1">{{post.created_at | formatDate}}</label>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="red darken-2" :href="`/news-stories/${post.id}`">View More</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                        <v-flex xs12 sm4>
                            <v-container grid-list-xl pa-0>
                                <v-flex xs12 class="mb-3">
                                    <h2 class="section-title">Donates</h2>
                                </v-flex>
                                <v-layout column wrap justify-center>
                                    <v-flex xs12>
                                        <v-card class="mb-2">
                                            <v-card-title primary-title>
                                                <div>
                                                    <h3 class="headline mb-0">Make A Donation</h3>
                                                    <div>Everyday, you could be helping vulnerable <br>people have a better quality of life.</div>
                                                </div>
                                            </v-card-title>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="red" href="/fundraisers-campaign">DONATE HERE</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                        <v-card>
                                            <v-card-title primary-title>
                                                <div>
                                                    <h3 class="headline mb-0">Memory Giving</h3>
                                                    <div>The perfect tribute to keep the lasting memory of your loved ones alive.</div>
                                                </div>
                                            </v-card-title>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="red" href="/fundraisers-campaign">DONATE HERE</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-parallax class="parallax-banner" src="https://redcross.sg/images/IMG_20181001_103155.jpg" height="480">
                    <v-layout align-center column justify-center>
                        <v-container fill-height grid-list-xl>
                            <v-layout align-center justify-space-around wrap>
                                <v-flex xs10 md6>
                                    <div class="alpha-testimonial">
                                        <div class="alpha-testimonial__content">
                                            <i aria-hidden="true" class="icon primary-home--text material-icons" style="font-size: 40px;">format_quote</i> 
                                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maxime recusandae ut nostrum facere incidunt consectetur. Corporis perferendis atque id fugiat ratione doloribus tempore quae maiores recusandae? Asperiores quod dolores quos?
                                                 Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus soluta ullam eligendi earum cupiditate amet dolorum architecto porro, repellat incidunt assumenda tempora tenetur modi. Facere, aut velit. Consequatur, vero asperiores.</p>
                                        </div> 
                                        <footer>
                                            <div class="alpha-testimonial__footer-bar primary-home--line"></div> 
                                            <div class="headline">Sulawesi Earthquake & Tsunami</div> 
                                            <div class="title">Indonesia</div>
                                        </footer>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-parallax>
                <v-container fill-height>
                    <v-layout wrap>
                        <v-flex xs12>
                            <v-container grid-list-xl pa-0>
                                <v-flex xs12 class="mb-3">
                                    <h2 class="section-title">Training Courses</h2>
                                </v-flex>
                                <v-layout row wrap class="mr-1">
                                        <v-flex xs12 sm3 d-flex v-for="course in courses" :key="course.id">
                                            <v-card flat tile style="height:auto">
                                            <v-card-title class="py-4 white--text" style="background:#ca0000"><h3>{{ course.name }}</h3></v-card-title>
                                            <v-divider></v-divider>
                                            <v-list dense>
                                            <v-list-tile>
                                                <v-list-tile-content>Course fee:</v-list-tile-content>
                                                <v-list-tile-content class="align-end">{{ course.course_fee === 0 ? "FREE" : 'RM' + course.course_fee }}</v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile>
                                                <v-list-tile-content>Date:</v-list-tile-content>
                                                <v-list-tile-content class="align-end">{{ course | formatDateCourse }}</v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile>
                                                <v-list-tile-content>Time period:</v-list-tile-content>
                                                <v-list-tile-content class="align-end">{{ course.start_time | formatTime }} - {{ course.end_time | formatTime }}</v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile>
                                                <v-list-tile-content>Available Seat(s)</v-list-tile-content>
                                                <v-list-tile-content class="align-end">{{ course.available_seat - course.computed.total }}</v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile>
                                                <v-list-tile-content>Venue</v-list-tile-content>
                                                <v-list-tile-content class="align-end">{{ course.venue }}</v-list-tile-content>
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
                                                            <v-card-text v-html="course.description"></v-card-text>
                                                        </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                    </v-card>
                                                </v-dialog>
                                            </div>
                                            <v-list-tile>
                                                <div class="action-btn--center">
                                                <v-btn :href="`/course-registration/${course.id}/register`" flat v-text="course.available_seat == 0 ? 'Registration is closed' : 'Register Now'"> </v-btn>
                                                </div>
                                            </v-list-tile>
                                            </v-list>
                                            </v-card>
                                        </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
            </div>
        </v-content>
    </div>
</template>

<script>
import moment from 'moment'
    export default {
        data: () => ({
            posts:[],
            courses:[],
            dialog: false
        }),
        created() {
            this.fetch()
        },
        methods: {
            fetch() {
                axios.get("/api/latestEventDisplayHomePage").then(res => {
                    this.posts = res.data;
                });
                axios.get("api/courses").then(res => {
                    this.courses = res.data
                })
            },
        },
        filters: {
            formatDate: function (value) {
                if (!value) return ''
                return moment(String(value)).format('MMMM DD,YYYY hh:ss a')
            },
            formatTime: function(val){
                if(!val) return ''
                return moment(val,"HH:mm:ss").format("HH:mm")
            },
            formatDateCourse: function(obj){
                var start = moment(obj.start_date)
                var end = moment(obj.end_date)
                return start.isSame(end, 'day') ? start.format("DD MMM") : start.format("DD MMM") + " - " + end.format("DD MMM")
            }
        }
    }
    // export default {
    //     data: () => ({
    //         myText:["","HOPE","", "LOVE","", "WORLD",""],
    //         bannerText: "LIFE",
    //         bannerTextColor: '#000000',
    //         show: true
    //     }),
    //     mounted(){
    //         this.startInterval()
    //     },
    //     methods:{
    //         startInterval: function () {
    //             var x = 0
    //             let vm = this
    //             setInterval(() => {
    //                 vm.bannerText = vm.myText[x++]
    //                 vm.show = !vm.show
    //                 // this.bannerTextColor = '#'+(Math.random()*0xFFFFFF<<0).toString(16)
    //                 if(x == vm.myText.length){
    //                     x=0
    //                 }
    //             }, 2000);
    //         }
    //     }
    // }
    
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
.section-title::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 64px;
    height: 4px;
    border-radius: 2px;
    background: #f44336;
}
.section-title{
    position: relative;
    padding-bottom: 12px;
    font-size: 36px;
    font-weight: 600;
}
.action-btn--center{
width: 100%;
text-align: center;
}
</style>
