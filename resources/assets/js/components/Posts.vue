<template>
  <div>
      <form class="mb-3" @submit.prevent="addPost">
      <div class="form-group">
          <input type="text" class="form-control" placeholder="name" v-model="post.name">
      </div>
      <div class="form-group">
          <textarea type="text" class="form-control" placeholder="body" v-model="post.body"></textarea>
      </div>
        <button type="submit" class="btn btn-lgight btn-block">Save</button>
      </form>
      <div class="panel panel-default" v-for="post in posts" :key="post.id">
          <div class="panel-heading">
              <span class="glyphicon glyphicon-user" id="start"></span>
              <label for="" id="started">By</label> {{post.name}}
          </div>
          <div class="panel-body">
                <div class="col-md-12">
                    <div class="thumbnail">
                        <img :src="post.avatar" :alt="post.name">
                    </div>
                </div>
                <p>{{post.body}}</p>
          </div>
          <hr>
          <button class="btn btn-warning mb-2" @click="editPost(post)">Delete</button>
          <button class="btn btn-danger" @click="deletePost(post.id)">Delete</button>
      </div>
      <paginate
        :page-count="pageCount"
        :click-handler="fetchPosts"
        :prev-text="'Prev'"
        :next-text="'Next'" 
        :container-class="'pagination'"
      ></paginate>
  </div>
</template>
<script>
    export default {
        data() {
            return {
                posts: [],
                post:{
                    id: '',
                    name:'',
                    body:''
                },
                edit: false,
                pageCount: 1,
                endpoint: 'api/posts?page='
            };
        },
        created() {
            // this.fetch();
            this.fetchPosts();
        },
        methods: {
            // fetch(page = 1) {
            //     axios.get(this.endpoint + page)
            //         .then(({data}) => {
            //             this.posts = data.data;
            //             this.pageCount = data.meta.last_page;
            //         });
            // },
            fetchPosts(page = 1){
                fetch(this.endpoint + page)
                    .then(res => res.json())
                    .then(res => {
                        this.posts = res.data;
                        this.pageCount = res.meta.last_page;
                    });
            },
            report: function(id) {
                if(confirm('Are you sure you want to report this signature?')) {
                    axios.put('api/posts/'+id+'/report')
                    .then(({response}) => this.removeSignature(id));
                }
            },
            removeSignature(id) {
                this.signatures = _.remove(this.signatures, function (signature) {
                    return signature.id !== id;
                });
            },
            deletePost(id){
                if(confirm('Are you want to delete the post?')){
                    fetch(`api/post/${id}`,{
                        method:'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Post removed');
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err));
                }
            },
            addPost(){
                if(this.edit === false){
                    //add
                    fetch('api/post',{
                        method:'post',
                        body: JSON.stringify(this.post),
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.name = '';
                        this.post.body = '';
                        alert("Post Added");
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err));
                }else{
                    // Update
                    fetch('api/post', {
                    method: 'put',
                    body: JSON.stringify(this.post),
                    headers: {
                        'content-type': 'application/json'
                    }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.name = '';
                        this.post.body = '';
                        alert('Post Updated');
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
        }
    }
</script>