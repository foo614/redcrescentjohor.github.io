<template>
  <div>
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
      </div>
      <paginate
        :page-count="pageCount"
        :click-handler="fetch"
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
                pageCount: 1,
                endpoint: 'api/posts?page='
            };
        },
        created() {
            this.fetch();
        },
        methods: {
            fetch(page = 1) {
                axios.get(this.endpoint + page)
                    .then(({data}) => {
                        this.posts = data.data;
                        this.pageCount = data.meta.last_page;
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
            }
        }
    }
</script>