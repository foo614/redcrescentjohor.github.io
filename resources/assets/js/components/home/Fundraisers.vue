<template>
       <div>
        <v-container fluid grid-list-md>
            <v-card class="mb-3">
                <div class="text-xs-center">
                <v-layout row wrap>
                    <v-flex xs12>
                    <v-card-title primary-title class="justify-center">
                        <div class="headline"> Create a fundraising campaign </div></v-card-title>
                    <v-divider></v-divider>
                    <v-card-text style="font-size: 16px;">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <div class="mb-2">Do more than just giving and create a fundraising campaign on Red Crescent Johor</div>
                                <v-btn color="#ca0000" dark href="/fundraisers-campaign/create">Become a Fundraiser now!</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    </v-flex>
                </v-layout>
                </div>
            </v-card>
        </v-container>
        <v-container fluid grid-list-md>
            <v-data-iterator
                :items="fundraisers"
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
                <v-toolbar-title>Campaigns</v-toolbar-title>
                </v-toolbar>
                <v-flex
                slot="item"
                slot-scope="props"
                xs12
                sm6
                md4
                lg3
                d-flex
                >
                    <v-hover>
                        <v-card tile slot-scope="{ hover }" :class="`elevation-${hover ? 8 : 2}`">
                            <v-img
                            contain
                            aspect-ratio="2"
                            :src="props.item.cover_img ? '/img/'+props.item.cover_img : '/img/test2.jpg'"
                            height="200px"
                            >
                            </v-img>

                            <v-card-title primary-title>
                            <div>
                                <div class="headline">{{props.item.title}}</div>
                                <span class="grey--text">{{props.item.created_at}}</span>
                            </div>
                            </v-card-title>
                            <v-card-text v-html="props.item.body" style="overflow: hidden; height:100px;"></v-card-text>
                            <v-card-text>
                                <div>
                                    <span class="grey--text">By {{props.item.user_id === 1 ? 'Red Crescent Johor' : props.item.user}}</span><br>
                                </div>
                            </v-card-text>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn flat color="#ca0000" :href="`/fundraisers-campaign/donate/${props.item.id}`">Details</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-hover>
                </v-flex>
            </v-data-iterator>
        </v-container>
    </div>
</template>

<script>
export default {
    data(){
        return{
            fundraisers:[],
            dialog: false
        }
    },
    mounted(){
        this.fetchFundraisers()
    },
    methods:{
        fetchFundraisers: function(){
            axios.get("api/fundraisersActive").then(res => {
                this.fundraisers = res.data
            })
        }
    },
}
</script>

<style>

</style>
