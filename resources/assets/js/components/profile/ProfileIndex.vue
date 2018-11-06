<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 md6>
            <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="updateItem">
                <v-card>
                    <div class="text-xs-center">
                        <v-avatar size="125px" color="#757575" class="material-icons elevation-7 mb-1">
                            <span class="white--text headline" v-if="!show && !item.avatar">{{item.name | getFirstLetter}}</span>
                            <img
                                v-show="item.avatar"
                                :src= "!preview ? (!show ? (!item.avatar ? null : '/img/'+item.avatar ): item.avatar = null) : preview"
                                alt="profile_image"
                            >
                            <v-tooltip bottom v-if="show && !preview">
                                <input type="file" ref="avatar" v-on:change="handleFile" style="display:none" v-if="show">
                                <v-icon
                                    large
                                    dark
                                    @click="pickFile"
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
                                        <v-select
                                            :items="branches"
                                            v-model="item.branch_id"
                                            item-text="name"
                                            item-value="id"
                                            menu-props="auto"
                                            label="Select"
                                            hide-details
                                            prepend-icon="map"
                                            single-line
                                        ></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            v-model="item.contact"
                                            label="Contact"
                                            prepend-icon="contact_phone"
                                            :rules="[v => !!v || 'Contact is required']"
                                            required
                                        ></v-text-field>
                                        <v-text-field
                                            v-model="item.ic"
                                            label="IC"
                                            prepend-icon="credit_card"
                                            :rules="[v => !!v || 'IC is required']"
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
                branch_id: '',
                ic: '',
            },
            show:false,
            valid: true,
            loading: false,
            preview:'',
            branches:[]
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
                this.$toasted.error("Something wrong...", {icon:"error"})
            });
        axios.get('/api/branches').then(function(res){app.branches = res.data;}).catch(function(){console.log('failed to get branches')})
    },
    methods:{
        cancelItem(){
            this.show = false
        },
        updateItem(){
            fetch("/api/member", {
            method: "put",
            body: JSON.stringify(this.item),
            headers:{"content-type": "application/json"}
            })
            .then(res => {
                this.$toasted.success(this.item.name + ' updated' , {icon:"check"})
                this.show = false
            })
            .catch(err => console.log(err));
        },
        pickFile(){
            this.$refs.avatar.click()
        },
        handleFile(e){
            let files = e.target.files || e.dataTransfer.files;
            if(!files.length)
            return;
            this.createImage(files[0]);
        },
        createImage(file){
            let reader = new FileReader();
            let vm = this;
            reader.onload = e => {
                vm.preview = e.target.result;
                vm.item.avatar = e.target.result;
            };
            reader.readAsDataURL(file);
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
            .join("");
        }
    }
}
</script>

<style>
</style>
