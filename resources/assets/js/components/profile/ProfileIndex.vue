<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 md6>
            <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="updateItem">
                <v-card>
                    <div class="text-xs-center">
                        <v-avatar size="125px" color="teal lighten-2" class="material-icons mdl-list__item-avatar elevation-7 mb-1">
                            <span class="white--text headline" v-if="!show && !item.avatar">{{item.name | getFirstLetter}}</span>
                            <img
                                v-if="imgUrl || item.avatar"
                                class="img-circle elevation-7 mb-1"
                                :src="imgUrl ? imgUrl : item.avatar"
                                :alt="item.avatar"
                            >
                            <v-tooltip bottom v-if="show && !item.avatar">
                            <input type="file" ref="image" v-on:change="handleFile" style="display:none" v-if="show">
                            <v-icon
                                dark
                                large
                                @click="pickFile"
                                v-model="item.avatar"
                                slot="activator"
                            >
                                image
                            </v-icon>
                            <span>Upload profile photo</span>
                            </v-tooltip>
                        </v-avatar>
                        <v-card-text>
                            <div v-if="!show" class="headline">{{item.name}}</div>
                            <v-text-field
                                v-else
                                class="headline"
                                v-model="item.name"
                                label="Name"
                                :rules="[v => !!v || 'Name is required']"
                                required
                            ></v-text-field>
                            <div class="subheading text-xs-center grey--text pt-1 pb-3">joined on {{item.created_at}}</div>
                        </v-card-text>
                        <v-slide-y-transition>
                            <v-card-text v-show="show">
                                <v-layout row wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            v-model="item.email"
                                            label="Email"
                                            prepend-icon="email"
                                            :rules="[v => !!v || 'Email is required']"
                                            required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            v-model="item.contact"
                                            label="Contact"
                                            prepend-icon="phone"
                                            :rules="[v => !!v || 'Contact is required']"
                                            required
                                        ></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-slide-y-transition>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <div class="text-xs-center">
                                <v-btn 
                                outline 
                                round 
                                color="primary" 
                                dark
                                v-if="!show"
                                @click="show = !show"
                                >Edit Profile</v-btn>
                                <v-btn 
                                outline 
                                round 
                                color="primary" 
                                @click="cancelItem()"
                                dark
                                v-if="show"
                                >Cancel</v-btn>
                                <v-btn outline round color="primary" v-if="show" type="submit">Save</v-btn>
                            </div>
                        </v-card-actions>
                    </div>
                </v-card>
            </v-form>
        </v-flex>
        <v-snackbar
            :absolute="saveSnackbar.absolute"
            :right="saveSnackbar.right"
            :top="saveSnackbar.top"
            :color="saveSnackbar.color" 
            :timeout="saveSnackbar.timeout" 
            v-model="saveSnackbar.snackbar">
            {{ saveSnackbar.text }}
            <v-btn dark flat @click.native="saveSnackbar.snackbar = false">
            <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>
    </v-layout>
</template>

<script>
export default {
    data(){
        return{
            item:{
                name:'',
                email:'',
                contact:'',
                user_id: '',
                avatar: '',
            },
            show:false,
            valid: true,
            saveSnackbar:{},
            loading: false,
            imgUrl: '',
            imgFile:'',
        }
    },
    mounted() {
        let app = this;
        let id = this.$route.params.id;
        axios.get('/api/member/' + id)
            .then(function (res) {
                app.item = res.data;
                app.item.user_id = res.data.id;
            })
            .catch(function () {
                alert("Load error")
            });
    },
    methods:{
        cancelItem(){
            this.show = false
            this.imgUrl = null
            this.item.avatar = null
        },
        updateItem(){
            fetch("/api/member", {
            method: "put",
            body: JSON.stringify(this.item),
            headers:{"content-type": "application/json"}
            })
            .then(res => console.log(res))
            .then(data => {
                this.saveSnackbar = {timeout:3000, snackbar: true, color: 'success', text: this.item.name +' updated', absolute: true, right: true, top: true}
                this.show = false
            })
            .catch(err => console.log(err));
        },
        pickFile(){
            this.$refs.image.click()
        },
        handleFile(e){
            const file = e.target.files
            if(file[0] !== undefined){
                this.item.avatar = file[0].name
                if(this.item.avatar.lastIndexOf('.') <= 0){
                    return
                }
                const read = new FileReader()
                read.readAsDataURL(file[0])
                read.addEventListener('load', () => {
                    this.imgUrl = read.result
                    this.imgFile = file[0] //send this one
                })
            }else{
                this.imgUrl = ''
                this.imgFile = ''
                this.item.avatar = ''
            }
        }
    },
    filters: {
        getFirstLetter: function(value) {
        if (!value) return "";
        return value
            .split(" ")
            .map(function(item) {
            return item[0];
            })
            .join("").toUpperCase()
        }
    }
}
</script>

<style>
</style>
