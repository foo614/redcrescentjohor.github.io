<template>
    <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="addItem">
        <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">Add Post</div>
            </v-card-title>
                <v-container fluid grid-list-lg>
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-text-field
                            v-model="item.name"
                            label="Title"
                            prepend-icon="event_note"
                            :rules="[v => !!v || 'Title is required']"
                            required
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm4>
                        <v-select
                        v-model="item.post_type_id"
                        :items="items"
                        label="Post Type"
                        item-text="name"
                        item-value="id"
                        prepend-icon="event_note"
                        :rules="[v => !!v || 'You must select to continue!']"
                        ></v-select>
                    </v-flex>
                    <v-flex xs12 sm2>
                        <v-switch
                            :label="`Active Post? ${item.status === true ? 'On' : 'Off'}`"
                            v-model="item.status"
                        ></v-switch>
                    </v-flex>
                    <v-flex xs12 sm12>
                        <vue-ckeditor v-model="item.body" :config="config"/>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" type="submit">Submit</v-btn>
            </v-card-actions>
        </v-card>
        <v-snackbar
            :absolute="saveSnackbar.absolute"
            :right="saveSnackbar.right"
            :top="saveSnackbar.top"
            :color="saveSnackbar.color" 
            v-model="saveSnackbar.snackbar">
            {{ saveSnackbar.text }}
            <v-btn dark flat @click.native="saveSnackbar.snackbar = false">
            <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>
    </v-form>
</template>

<script>
import VueCkeditor from 'vue-ckeditor2';
export default {
    components: { VueCkeditor },
    data () {
    return {
        config: {
            toolbar: [
                ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
            ],
            height: 300
        },
        items:[],
        sending: false,
        edit: false,
        valid: true,
        item: {
          id: "",
          name: "",
          post_type_id: "",
          status: true,
          body: ""
        },
        saveSnackbar:{},
    };
    },
    created(){
        this.fetchPostCategories();
    },
    methods:{
        fetchPostCategories() {
            axios.get("../api/postCategories").then(res => {
                this.items = res.data;
            });
        },
        addItem(){
            this.sending = true;
            if(this.edit === false){
            setTimeout(()=> {
                fetch("../api/post", {
                method: "post",
                body: JSON.stringify(this.item),
                headers:{"content-type": "application/json"}
                })
                .then(res => res.json())
                .then(data => {
                    this.sending = false;
                    this.$refs.form.reset();
                    window.location = data.links.self;
                    this.saveSnackbar = {snackbar: true, color: 'success', text: data.data.name +' added', absolute: true, right: true, top: true}
                })
                .catch(err => console.log(err));
            }, 2000);
            }
        }
    }

  }
</script>

<style>

</style>
