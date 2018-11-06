<template>
    <v-layout>
      <v-flex>
    <v-toolbar flat color="white">
      <v-toolbar-title>Posts</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <router-link :to="{name: 'createPost'}"><v-btn small color="indigo" dark class="mb-2">New Posts</v-btn></router-link>
        <v-btn small color="red" v-show="selected.length > 0"  @click="deleteItem(selected)" dark class="mb-2">Delete</v-btn>
        <v-spacer></v-spacer>
        <v-text-field
          append-icon="search"
          label="Search"
          single-line
          hide-details
          v-model="search"
        ></v-text-field>
    </v-toolbar>
      <v-data-table
      :rows-per-page-items="rowsDefaultItem"
      v-model="selected"
      :headers="headers"
      :items="posts"
      :search="search"
      select-all
      item-key="id"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td :key="props.item.id" :active="props.selected" @click="props.selected = !props.selected">
          <v-checkbox
            v-model="props.selected"
            primary
            hide-details
          ></v-checkbox>
        </td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.title }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.post_category ? props.item.post_category.name : '' }}</td>
        <td :active="props.selected" @click="props.selected = !props.selected">{{ props.item.created_at }}</td>
        <td>
          <router-link :to="{name:'editPost', params:{id: props.item.id}}">
              <v-tooltip bottom>
              <v-icon
                slot="activator"
                small
                class="mr-2"
              >
              edit 
              </v-icon>
              <span>
                edit
              </span>
            </v-tooltip>
          </router-link>
          <v-tooltip bottom>
            <v-icon
              slot="activator"
              small
              @click="deleteItem(props.item)"
            >
              delete
            </v-icon>
            <span>delete</span>
          </v-tooltip>
        </td>
      </template>
      <v-alert slot="no-results" :value="true" color="error" icon="warning">
        Your search for "{{ search }}" found no results.
      </v-alert>
      <template slot="pageText" slot-scope="{ pageStart, pageStop }">
        From {{ pageStart }} - {{ pageStop }}
      </template>
    </v-data-table>
  </v-flex>
</v-layout>
</template>

<script>
export default {
  data(){
    return{
    rowsDefaultItem: [10],
    search: '',
    selected: [],
    posts: [],
    headers: [
      {
        text: "Title",
        align: "left",
        sortable: false,
        value: "title"
      },
      { text: "Post Type", value: "type", sortable: true },
      { text: "Created at", value: "created_at", sortable: true },
      { text: "Actions", sortable: false }
    ]
    };
  },
  created() {
    this.fetchPosts()
  },
  methods: {
    fetchPosts() {
      axios.get("/api/posts").then(res => {
        this.posts = res.data;
      });
    },
    deleteItem(selectedItem) {
      let self = this;
      if(self.selected.length === 0){
        self.selected.push(selectedItem)
      }
      self.selected.forEach(function(v,k){
        fetch(`/api/post/${v.id}`, {
          method: "delete"
        })
        .then(res => res.json())
        .then(data => {
          self.fetchPosts()
        })
        .catch(err => console.log(err));
      })
      self.$toasted.success(this.selected.length === 1 ? this.selected[0].title+' deleted' : this.selected.length+' user(s) deleted' , {icon:"check"})
      self.selected = []
    }
  }
};
</script>

<style lang="scss" scoped>
.md-table + .md-table {
  margin-top: 16px;
}
.md-field {
  max-width: 300px;
}
</style>  