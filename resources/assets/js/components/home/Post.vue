<template>
        <v-container fill-height>
        <v-layout>
            <v-flex xs12 sm10 offset-xs1>
                 <v-container grid-list-xl pa-0>
                    <v-flex xs12 class="mb-3">
                        <v-card>
                            <v-img v-show="item.cover_img" style="border-bottom: 6px solid #B30909;" :src="'/img/'+item.cover_img" aspect-ratio="1.7"  max-height="200"></v-img>
                            <v-card-title primary-title :class="{'pb-0': item.event}">
                                <div v-if="!item.event">
                                    <div class="headline">{{item.title}}</div>
                                    <v-icon color="red darken-2" class="mt-2" v-if="!item.event">calendar_today</v-icon> <label class="ml-1" v-if="!item.event">{{item.created_at}}</label>
                                </div>
                                <v-list two-line v-if="item.event">
                                    <v-list-tile>
                                        <v-list-tile-avatar> 
                                            <div style="display:inline-grid; text-align:center;" class="text-uppercase mr-2 body-2">{{item.event.start | formatDate}}</div>
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title class="headline">{{item.title}}</v-list-tile-title>
                                            <v-list-tile-sub-title v-if="item.event">{{item.event | formatTime}} <v-tooltip top><a class="ml-2" slot="activator" :href="`https://www.google.com/maps/search/?api=1&query=${item.event.map_lat},${item.event.map_lng}&query_place_id=${item.event.place_id}`" target="_blank">{{item.event.address}}</a><span>Click to view in google map</span></v-tooltip></v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                                <v-spacer></v-spacer>
                                <v-tooltip top>
                                    <v-btn slot="activator" icon class="blue--text text--darken-4" @click="share(`http://rcj-dev.com/news-stories/${item.id}`,item.title, item.body)">
                                        <v-icon medium>fab fa-facebook</v-icon>
                                    </v-btn>
                                    <span>Share on FB</span>
                                </v-tooltip>
                            </v-card-title>
                            <v-divider></v-divider> 
                            <v-card-text v-html="item.body"></v-card-text>
                        </v-card>
                    </v-flex>
                 </v-container>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
import moment from 'moment'
export default {
    data () {
        return {
            item: {
                id: "",
                title: "",
                post_type_id: "",
                status: true,
                body: "",
                address: "",
                map_lng:"",
                map_lat:"",
                start:null,
                event:null,
                end:null,
                cover_img:null,
                place_id: null,
                formatted_address: null
            },
        };
    },
    filters: {
      formatDate: function (value) {
        if (!value) return ''
        return moment(String(value)).format('MMM DD')
      },
      formatTime: function (obj) {
        if (!obj) return ''
        var current = new moment();
        var start = moment(obj.start);
        var end = moment(obj.end);
        var daysResult = start.diff(current, 'days')
        var hoursResult = start.diff(current, 'hours')
        if(obj.start && !obj.end)
          return daysResult != 0 ? start.format('ddd HH:mm') : (current.isBefore(start) && current.isSame(start, 'day')) ?  "Today " + start.format("HH:mm") : "Tomorrow " + start.format("HH:mm")
        else if(obj.start && obj.end)
          return start.isSame(end, 'day') ? start.format("HH:mm") + " - " + end.format("HH:mm") : start.format("DD MMM") + " - " + end.format("DD MMM")
      }
    },
    mounted() {
        let app = this;
        let id = this.$route.params.id;
        axios.get('/api/post/' + id)
        .then(function (res) {
            app.item = res.data;
            app.item.post_id = res.data.id;
            if(app.item.event){
                app.item.address = res.data.event.address;
                app.item.start_date = res.data.event.start ? moment(res.data.event.start).format("YYYY-MM-DD") : null
                app.item.end_date = res.data.event.end ? moment(res.data.event.end).format("YYYY-MM-DD") : null
                app.item.start_time = res.data.event.start ? moment(res.data.event.start).format("HH:mm") : null
                app.item.end_time = res.data.event.end ? moment(res.data.event.end).format("HH:mm") : null
            }
        })
        .catch(function () {
            app.$toasted.error("Something wrong...", {icon:"error"})
        });
    },
    methods:{
        share(overrideLink, overrideTitle, overrideDescription){
            let app = this;
            FB.ui({
            method: 'share_open_graph',
            action_type: 'og.likes',
            action_properties: JSON.stringify({
                object: {
                    'og:url': overrideLink,
                    'og:title': overrideTitle,
                    'og:description': overrideDescription
                }
            })
        },
        function (response) {
            // Action after response
            if (response && !response.error_message) {
                // alert('Posting completed.');
                app.$toasted.success('Posting completed.' , {icon:"check"})
            } else {
                app.$toasted.error("Something wrong...", {icon:"error"})
            }
        });
      }
    },
}
</script>

<style>

</style>
