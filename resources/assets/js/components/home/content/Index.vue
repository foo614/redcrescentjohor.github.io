<template>
    <div>
        <div class="container fill-height">
            <div class="layout justify-center wrap">
                <div class="flex xs12 sm8 mb-5">
                    <div class="container grid-list-xl pa-0">
                        <div class="flex xs12 mb-3">
                            <h2>Articles</h2>
                        </div>
                        <div class="layout row wrap justify-center mr-1">
                            <div class="flex xs12 sm6 d-flex">
                                <div class="card card--flat card--tile" style="height: auto;">
                                    <div class="card__media" style="height: 200px;">
                                        <div class="card__media__background" style="background: url(http://www.who.int/images/default-source/big-stories/outbreaks/learning-about-ebola.tmb-1920v.jpg?sfvrsn=2106692a_2) center center / cover no-repeat;"></div>
                                        <div class="card__media__content"></div>
                                    </div>
                                    <div class="card__title title">Award-winning architecture</div>
                                </div>
                            </div>
                            <div class="flex xs12 sm6 d-flex">
                                <div class="card card--flat card--tile" style="height: auto;">
                                    <div class="card__media" style="height: 200px;">
                                        <div class="card__media__background" style="background: url(https://redcross.sg/media/thumbnails/timthumb.php?src=https://redcross.sg/images/IMG_20181001_103155.jpg&w=310&h=210&a=t) center center / cover no-repeat;"></div>
                                        <div class="card__media__content"></div>
                                    </div>
                                    <div class="card__title title">Relentless pursuit of your vision</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex xs12 sm4 mb-5">
                    <div class="container grid-list-xl pa-0">
                        <div class="flex xs12 mb-3">
                            <h2>Donates</h2>
                        </div>
                        <div class="layout column wrap justify-center">
                            <div class="flex xs12">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <v-parallax src="https://redcross.sg/images/IMG_20181001_103155.jpg">
            <v-layout align-center column justify-center>
                <div class="headline white--text mb-3 text-xs-center">Sulawesi Earthquake & Tsunami</div>
                <em> Launches Fundraising Appeal, Commits Humanitarian Supplies To Affected Communities </em>
                <v-btn class="blue lighten-2 mt-5" dark large href="/pre-made-themes">
                    Read More
                </v-btn>
            </v-layout>
        </v-parallax>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>
