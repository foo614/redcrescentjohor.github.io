<template>
  <div>
    <div class="md-layout">
      <div class="md-layout-item md-size-80 md-small-size-100 md-alignment-top-center">
        <form @submit.prevent="addPost">
          <md-card>
            <md-card-header>
              <div class="md-title">Create/Edit Post</div>
            </md-card-header>
            <md-card-content>
              <md-field>
                <label>Name</label>
                <md-input v-model="post.name"></md-input>
              </md-field>
              <md-field>
                <label>Body</label>
                <md-input v-model="post.body"></md-input>
              </md-field>
            </md-card-content>

            <md-progress-bar md-mode="indeterminate" v-if="sending" />

            <md-card-actions>
              <md-button type="submit" class="md-primary" :disabled="sending">Create</md-button>
            </md-card-actions>
          </md-card>

          <md-snackbar :md-active.sync="postSaved">The user was saved with success!</md-snackbar>
        </form>
      </div>
    </div>
    <!-- <content-placeholders  v-show="loading === true">
                <content-placeholders-heading :img="true" />
                <content-placeholders-text :lines="3" />
            </content-placeholders> -->
    <ul class="pagination">
      <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page_item">
        <a class="page-link" href="javascript:void(0)" aria-label="Previous" @click="fetchPosts(pagination.prev_page_url)">
          <span aria-hidden="true">«</span>
        </a>
      </li>
      <li class="page-item disabled">
        <a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
      </li>
      <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page_item">
        <a class="page-link" href="javascript:void(0)" aria-label="Next" @click="fetchPosts(pagination.next_page_url)">
          <span aria-hidden="true">»</span>
        </a>
      </li>
    </ul>
    <!-- <div class="md-layout md-gutter">
      <md-progress-spinner id="spinner" v-show="loading === true" :md-diameter="100" :md-stroke="6" md-mode="indeterminate"></md-progress-spinner>
      <div class="md-layout-item" v-for="post in posts" :key="post.id">
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
          <md-card-actions>
            <md-button class="md-icon-button" @click="editPost(post)">
              <md-icon>create</md-icon>
            </md-button>
            <md-button class="md-icon-button" @click="deletePost(post.id)">
              <md-icon>delete</md-icon>
            </md-button>
          </md-card-actions>
        </md-card>
      </div>
    </div> -->
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
          <md-card-actions>
            <md-button class="md-icon-button" @click="editPost(post)">
              <md-icon>create</md-icon>
            </md-button>
            <md-button class="md-icon-button" @click="deletePost(post.id)">
              <md-icon>delete</md-icon>
            </md-button>
          </md-card-actions>
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
        post: {
          id: "",
          name: "",
          body: ""
        },
        edit: false,
        // pageCount: 1,
        // endpoint: "api/posts?page=",
        pagination: {},
        loading: false,
        postSaved: false,
        sending: false
      };
    },
    // created() {
    //   this.fetchPosts();
    // },
    methods: {
      // fetch(page = 1) {
      //     axios.get(this.endpoint + page)
      //         .then(({data}) => {
      //             this.posts = data.data;
      //             this.pageCount = data.meta.last_page;
      //         });
      // },
      // fetchPosts(page_url) {
      //   let vm = this;
      //   page_url = page_url || '/api/posts';
      //   this.loading = true;
      //   setTimeout(() => {
      //     fetch(page_url)
      //       .then(res => res.json())
      //       .then(res => {
      //         this.posts = res.data;
      //         // this.pageCount = res.meta.last_page;
      //         // vm.makePagination(res.meta, res.links);
      //         this.loading = false;
      //       })
      //       .catch(err => console.log(err));
      //   }, 1000);
      // },
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
      makePagination(meta, links) {
        let pagination = {
          current_page: meta.current_page,
          last_page: meta.last_page,
          next_page_url: links.next,
          prev_page_url: links.prev
        };
        this.pagination = pagination;
      },
      // report: function(id) {
      //   if (confirm("Are you sure you want to report this signature?")) {
      //     axios
      //       .put("api/posts/" + id + "/report")
      //       .then(({ response }) => this.removeSignature(id));
      //   }
      // },
      // removeSignature(id) {
      //   this.signatures = _.remove(this.signatures, function(signature) {
      //     return signature.id !== id;
      //   });
      // },
      deletePost(id) {
        if (confirm("Are you want to delete the post?")) {
          fetch(`api/post/${id}`, {
              method: "delete"
            })
            .then(res => res.json())
            .then(data => {
              alert("Post removed");
              this.fetchPosts();
            })
            .catch(err => console.log(err));
        }
      },
      addPost() {
        if (this.edit === false) {
          //add
          this.sending = true;

          setTimeout(() => {
            fetch("api/post", {
                method: "post",
                body: JSON.stringify(this.post),
                headers: {
                  "content-type": "application/json"
                }
              })
              .then(res => res.json())
              .then(data => {
                this.post.name = "";
                this.post.body = "";
                this.postSaved = true
                this.sending = false
                this.fetchPosts();
              })
              .catch(err => console.log(err));
          }, 1500);
        } else {
          // Update
          fetch("api/post", {
              method: "put",
              body: JSON.stringify(this.post),
              headers: {
                "content-type": "application/json"
              }
            })
            .then(res => res.json())
            .then(data => {
              this.post.name = "";
              this.post.body = "";
              // alert("Post Updated");
              this.updated = true;
              this.fetchPosts();
            })
            .catch(err => console.log(err));
        }
      },
      editPost(post) {
        this.edit = true;
        this.post.id = post.id;
        this.post.post_id = post.id;
        this.post.name = post.name;
        this.post.body = post.body;
      }
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

  .md-progress-bar {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
  }
</style>
<style lang="css">
  .pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
    text-decoration: none;
  }

  .pagination>li {
    display: inline;
  }

  .pagination .page-item.active .page-link {
    background-color: #09c;
  }

  .pagination>li>a,
  .pagination>li>span {
    -o-transition: all 0.3s linear;
    transition: all 0.3s linear;
    outline: 0;
    border: 0;
    background-color: transparent;
    font-size: 0.9rem;
    position: relative;
    /* display: block; */
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    /* border: 1px solid #dee2e6; */
    text-decoration: none !important;
  }

  .page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
  }

  .page-link:not(:disabled):not(.disabled) {
    cursor: pointer;
  }

  .pagination>li:first-child>a,
  .pagination>li:first-child>span {
    margin-left: 0;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
  }

  .pagination>li:last-child>a,
  .pagination>li:last-child>span {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
  }

  .pagination>li>a:focus,
  .pagination>li>a:hover,
  .pagination>li>span:focus,
  .pagination>li>span:hover {
    z-index: 2;
    color: #23527c;
    background-color: #eee;
    border-color: #ddd;
  }

  .pagination>.active>a,
  .pagination>.active>a:focus,
  .pagination>.active>a:hover,
  .pagination>.active>span,
  .pagination>.active>span:focus,
  .pagination>.active>span:hover {
    -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
    -webkit-transition: all 0.2s linear;
    -o-transition: all 0.2s linear;
    transition: all 0.2s linear;
    -webkit-border-radius: 0.125rem;
    border-radius: 0.125rem;
    background-color: #4285f4;
    color: #fff;
  }

  .pagination>.disabled>a,
  .pagination>.disabled>a:focus,
  .pagination>.disabled>a:hover,
  .pagination>.disabled>span,
  .pagination>.disabled>span:focus,
  .pagination>.disabled>span:hover {
    color: #777;
    cursor: not-allowed;
    background-color: #fff;
    border-color: #ddd;
  }

  .pagination-lg>li>a,
  .pagination-lg>li>span {
    padding: 10px 16px;
    font-size: 18px;
    line-height: 1.3333333;
  }

  .pagination-lg>li:first-child>a,
  .pagination-lg>li:first-child>span {
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
  }

  .pagination-lg>li:last-child>a,
  .pagination-lg>li:last-child>span {
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
  }

  .pagination-sm>li>a,
  .pagination-sm>li>span {
    padding: 5px 10px;
    font-size: 12px;
    line-height: 1.5;
  }

  .pagination-sm>li:first-child>a,
  .pagination-sm>li:first-child>span {
    border-top-left-radius: 3px;
    border-bottom-left-radius: 3px;
  }

  .pagination-sm>li:last-child>a,
  .pagination-sm>li:last-child>span {
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
  }

  #spinner {
    position: fixed;
    left: 50%;
    top: 50%;
    z-index: 100000;
  }
</style>