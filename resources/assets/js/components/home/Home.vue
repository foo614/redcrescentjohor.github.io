<template>
    <div>
        <v-parallax src="http://www.who.int/images/default-source/big-stories/outbreaks/learning-about-ebola.tmb-1920v.jpg?sfvrsn=2106692a_2">
            <v-layout column align-center justify-center class="white--text">
                <h1 class="display-2 white--text">Building a Culture of <transition name="fade" v-if="show"><label>{{bannerText}}</label></transition></h1>

                <div class="subheading white--text text-xs-center">
                   Works around the globe to save lives, defeat poverty and achieve social justice.</div>
            </v-layout>
        </v-parallax>
        <v-content>
            <div style="transform-origin: center top 0px;">
                <v-container fill-height>
                    <v-layout wrap>
                        <v-flex xs12 sm8>
                            <v-container grid-list-xl pa-0>
                                <v-flex xs12 class="mb-3">
                                    <h2><label style="border-bottom:3px solid #f44336">Articles</label></h2>
                                </v-flex>
                                <v-layout row wrap justify-center class="mr-1">
                                    <v-flex xs12 sm6 d-flex>
                                        <v-card flat tile style="height:auto">
                                            <v-img height="200" style="border-bottom: 6px solid #B30909;" aspect-ratio="2.75" src="http://www.who.int/images/default-source/big-stories/outbreaks/learning-about-ebola.tmb-1920v.jpg?sfvrsn=2106692a_2"></v-img>
                                            <v-card-title  primary-title>
                                                <h3 class="headline mb-0 text-truncate">Award-winning architecture</h3>
                                            </v-card-title>
                                            <v-card-actions>
                                                <v-icon color="red darken-2">calendar_today</v-icon> <label class="ml-1">October 16, 2018 09:48pm</label>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="red darken-2">View More</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs12 sm6 d-flex>
                                        <v-card flat tile style="height:auto">
                                            <v-img height="200" style="border-bottom: 6px solid #B30909;" aspect-ratio="2.75" src="http://www.who.int/images/default-source/big-stories/outbreaks/learning-about-ebola.tmb-1920v.jpg?sfvrsn=2106692a_2"></v-img>
                                            <v-card-title  primary-title>
                                                <h3 class="headline mb-0 text-truncate">Award-winning architecture</h3>
                                            </v-card-title>
                                            <v-card-actions>
                                                <v-icon color="red darken-2">calendar_today</v-icon> <label class="ml-1">October 16, 2018 09:48pm</label>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="red darken-2">View More</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                        <v-flex xs12 sm4>
                            <v-container grid-list-xl pa-0>
                                <v-flex xs12 class="mb-3">
                                    <h2><label style="border-bottom:3px solid #f44336">Donates</label></h2>
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
                                                <v-btn flat color="red">DONATE HERE</v-btn>
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
                                                <v-btn flat color="red">DONATE HERE</v-btn>
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
                                            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus soluta ullam eligendi earum cupiditate amet dolorum architecto porro, repellat incidunt assumenda tempora tenetur modi. Facere, aut velit. Consequatur, vero asperiores.</p>
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
            </div>
        </v-content>
    </div>
</template>

<script>
    export default {
        data: () => ({
            myText:["","HOPE","", "LOVE","", "RESPECT","", "KINDNESS"],
            bannerText: "FAMILY",
            bannerTextColor: '#000000',
            show: true
        }),
        mounted(){
            this.startInterval()
        },
        methods:{
            startInterval: function () {
                var x = 0
                let vm = this
                setInterval(() => {
                    vm.bannerText = vm.myText[x++]
                    vm.show = !vm.show
                    this.bannerTextColor = '#'+(Math.random()*0xFFFFFF<<0).toString(16)
                    if(x == vm.myText.length){
                        x=0
                    }
                }, 3500);
            }
        }
    }
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
