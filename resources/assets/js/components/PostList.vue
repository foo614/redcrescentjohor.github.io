<template>
  <div class="page-content">
    <div class="md-layout md-gutter">
      <div class="md-layout-item md-size-50" v-for="post in posts" :key="post.id">
        <md-card>
          <md-card-media>
            <img :src="post.avatar" :alt="post.name">
          </md-card-media>
          <md-card-header>
            <div class="md-title">{{post.name}}</div>
            <div class="md-subhead">
              <md-icon>person</md-icon>By{{post.name}}</div>
          </md-card-header>
          <md-card-content>
            {{post.body}}
          </md-card-content>
        </md-card>
      </div>
    </div>
    <infinite-loading @infinite="infiniteHandler">
      <span slot="no-more">
        No more posts
      </span>
    </infinite-loading>
  </div>
</template>
<script>
import InfiniteLoading from 'vue-infinite-loading';
import axios from 'axios';
  export default {
    data() {
      return {
        posts: [],
      };
    },
    methods: {
      fetchPosts: function (){
        axios.get('api/posts').then(res =>{
          this.posts = res.data.data;
          this.meta = res.data.meta;
        })
      },
      infiniteHandler: function ($state) {
        let limit = this.posts.length/6 + 1;
          axios.get('/api/posts/', { params: {page:limit} })
          .then(({data}) => {
            if ( data.data.length ) {
              this.posts = this.posts.concat(data.data);
              $state.loaded();
              if (data.meta.total == this.posts.length) {
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
</style>
