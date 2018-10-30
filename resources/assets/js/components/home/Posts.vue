<template>

    <v-container>
      <v-layout wrap>
        <v-flex xs12 sm12>
          <v-tabs fixed-tabs v-model="active" color="#ca0000" dark class="mb-4" centered max slider-color="white" height="64px" show-arrows>
            <v-tab>All</v-tab>
            <v-tab
              v-for="postCategory in postCategories" :key="postCategory.id"
            >
              {{postCategory.name}}
            </v-tab>
          </v-tabs>
        </v-flex>
        <!-- <v-flex xs12 sm2>
          <v-container grid-list-xl pa-0>
            <v-flex xs12 class="mb-3">
              <h2><label style="border-bottom:3px solid #f44336">Categories</label></h2>
            </v-flex>
            <v-layout column wrap>
              <v-flex mr-2>
                <v-card v-for="postCategory in postCategories" :key="postCategory.id">
                  <v-list>
                    <v-list-tile @click="filterList(postCategory.name)">
                      <v-list-tile-content>
                        <v-list-tile-title>{{postCategory.name}}</v-list-tile-title>
                      </v-list-tile-content>
                      <v-list-tile-action>
                        {{postCategory.total}}
                      </v-list-tile-action>
                    </v-list-tile>
                  </v-list>
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
        </v-flex> -->
        <v-flex xs12 sm10 offset-xs0 offset-sm1>
          <v-container grid-list-xl pa-0>
            <v-flex xs12 class="mb-3">
              <h2><label style="border-bottom:3px solid #f44336">Posts</label></h2>
            </v-flex>
            <!-- <v-layout row wrap> -->
              <transition-group name="fade-transition" tag="v-layout" class="wrap child-flex" style="min-height: 450px;">
                <v-flex xs12 sm6 v-for="post in customFilter" :key="post.id" class="posts-card">
                  <v-card>
                    <v-img v-if="post.cover_img" style="border-bottom: 6px solid #B30909;" :src="'/img/'+post.cover_img"
                      :lazy-src="'/img/'+post.cover_img" aspect-ratio="1" max-height="200"></v-img>
                    <v-list two-line>
                      <v-list-tile>
                        <v-list-tile-avatar v-if="post.event"> 
                          <div style="display:inline-grid; text-align:center;" class="text-uppercase mr-2 body-2">{{post.event.start | formatDate}}</div>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="post.title"></v-list-tile-title>
                          <v-list-tile-sub-title v-if="post.event">{{post.event | formatTime}} <v-tooltip top><a class="ml-2" slot="activator" :href="`https://www.google.com/maps/search/?api=1&query=${post.event.map_lat},${post.event.map_lng}&query_place_id=${post.event.place_id}`" target="_blank">{{post.event.address}}</a><span>Click to view in google map</span></v-tooltip></v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    </v-list>
                    <v-card-actions>
                      <v-icon color="red darken-2" v-if="!post.event">calendar_today</v-icon> <label class="ml-1" v-if="!post.event">{{post.created_at }}</label>
                      <v-icon color="red darken-2" v-if="!post.event" class="ml-5 mr-1">remove_red_eye</v-icon> <label v-if="!post.event">{{post.page_view ? post.page_view.total_view : 0}} </label>
                      <v-spacer></v-spacer>
                      <!-- <v-btn flat color="red" :to="{name:'showPost', params:{id: post.id}}">Read More <v-icon small>arrow_forward</v-icon></v-btn> -->
                      <v-btn flat color="red" :href="`/news-stories/${post.id}`">Read More <v-icon small>arrow_forward</v-icon></v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
              </transition-group>
            <!-- </v-layout> -->
            <infinite-loading @infinite="infiniteHandler" spinner="spiral">
              <span slot="no-more">
                No more posts
              </span>
            </infinite-loading>
          </v-container>
        </v-flex>
      </v-layout>
    </v-container>

</template>
<script>
  import moment from 'moment'
  import InfiniteLoading from 'vue-infinite-loading';
  export default {
    data() {
      return {
        posts: [],
        postCategories:[],
        active:null,
        viewCount:[]
      };
    },
    created(){
      this.fetchPostCategories();
    },
    computed:{
      customFilter(){
        return this.posts.filter((elm) => {
          if(this.active != 0){
            let postType = this.postCategories[this.active - 1].name
            return elm.post_category['name'] === postType
          }else
            return this.posts
        })
      },
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
    methods: {
      filterList(name){
        return this.posts.filter((elm) => {
          return elm.post_category['name'] === name
        })
      },
      fetchPostCategories() {
        axios.get("/api/postCategories").then(res => {
          this.postCategories = res.data;
        });
      },
      infiniteHandler($state) {
        axios.get('api/posts')
          .then(({data}) => {
            if (data.length) {
              setTimeout(() => {
                const temp = [];
                for (let i = this.posts.length; i <= this.posts.length + 12; i++) {
                  temp.push(data[i])
                }
                temp.find(function (elm, index) {
                  if (elm === undefined) {
                    return temp.pop(index)
                  }
                })
                this.posts = this.posts.concat(temp)
                $state.loaded();
              }, 1000)

              if (this.posts.length === data.length) {
                $state.complete();
              }
            } else {
              $state.complete();
            }
          });
      },
    },
    components: {
      InfiniteLoading,
    },
  };
</script>
<style lang="scss" scoped>
  .md-card {
    width: 320px;
    margin: 4px;
    display: inline-block;
    vertical-align: top;
  }

  .md-layout-item {
    &:after {
      width: 100%;
      height: 100%;
      display: block;
    }
  }

  .posts-card {
      transform-origin: center center 0;
      transition: .3s cubic-bezier(.25,.8,.5,1)
  }

  .fade-transition-leave-active, .fade-transition-leave-to, .fade-transition-leave {
      display: none
  }
</style>