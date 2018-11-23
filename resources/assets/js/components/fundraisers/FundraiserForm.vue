<template>
        <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
        <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">{{$route.name == "editFundraiser" ? 'Edit' : 'Add' }} Fundraiser</div>
            </v-card-title>
                <v-container fluid grid-list-lg>
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-text-field
                            v-model="item.title"
                            label="Title"
                            prepend-icon="event_note"
                            :rules="[v => !!v || 'Title is required']"
                            required
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm4>
                        <v-text-field
                            v-model="item.target_amount"
                            label="Target Amount"
                            prepend-icon="attach_money"
                            type="number"
                            :rules="[v => !!v || 'Target Amount is required']"
                            required
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm2>
                        <v-switch
                            :label="`Fundraiser ${item.status === 1 || item.status === true ? 'Active' : (item.status === 0 || item.status === false) ? 'Inactive' : (item.status === 2 ? 'Pending' : '') }`"
                            v-model="item.status"
                        ></v-switch>
                    </v-flex>
                    <v-flex xs12 sm12 class="mt-2">
                        <div style="display: flex;">
                            <v-tooltip bottom>
                                <v-icon slot="activator">description</v-icon>
                                <span>Content</span>
                            </v-tooltip>
                            <ckeditor style="width:100%" height="250px" class="ml-2" v-model="item.body" language="en" extraplugins="divarea"/>
                        </div>
                    </v-flex>
                    <v-flex xs12 sm12>
                        <v-icon>image</v-icon>
                        <v-badge overlap>
                            <span slot="badge" v-if="(preview || item.cover_img)" @click="item.cover_img = null; preview= null">x</span>
                            <v-avatar tile class="elevation-7 v-avatar-custom--size">
                                <v-img lazy-src aspect-ratio="2" v-if="item.cover_img || preview" 
                                :src="item.cover_img && !preview ? '/img/'+item.cover_img : preview ? preview : null"
                                    alt="profile_image"></v-img>
                                <v-tooltip bottom v-if="!preview && !item.cover_img">
                                    <input type="file" ref="cover_img" v-on:change="handleFile" style="display:none">
                                    <v-icon large @click="pickFile" slot="activator">
                                        image
                                    </v-icon>
                                    <span>Upload Fundraiser Cover Photo</span>
                                </v-tooltip>
                            </v-avatar>
                        </v-badge>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="primary" type="submit">Submit</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
export default {
    mounted() {
        if(this.$route.name === "editFundraiser"){
            let app = this;
            let id = this.$route.params.id;
            axios.get('/api/fundraiser/' + id)
            .then(function (res) {
                app.item = res.data;
                app.item.fundraiser_id = res.data.id;
                if(app.item.status === 2) app.item.status = false
            })
            .catch(function () {
                this.$toasted.error("Something wrong...", {icon:"error"})
            });
        }
    },
    data () {
        return {
            sending: false,
            valid: true,
            item: {
                fundraiser_id:'',
                id: "",
                title: "",
                status: true,
                body: "",
                cover_img: null,
                target_amount: null,
            },
            preview: '',
        };
    },
     methods:{
        pickFile() {
            this.$refs.cover_img.click()
        },
        handleFile(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = e => {
                vm.preview = e.target.result;
                vm.item.cover_img = e.target.result;
            };
            reader.readAsDataURL(file);
            console.log(file);
        },
        saveItem(){
            if (this.$refs.form.validate()){
                this.sending = true;
                // CKEDITOR.removeAllListeners();
                setTimeout(()=> {
                    fetch("/api/fundraiser", {
                    method: this.$route.name == 'createFundraiser' ? "post" : "put",
                    body: JSON.stringify(this.item),
                    headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.sending = false
                        let currentPage = this.$route.name
                        this.$router.push({ path: '/fundraisers' }, ()=> {
                            this.$toasted.success(this.item.title + (currentPage === 'createFundraiser' ? ' added' : ' updated') , {icon:"check"})
                        })
                    })
                    .catch(err => console.log(err))
                }, 1500)
            }
        }
     }
}
</script>

<style>
.v-avatar-custom--size {
    height: 120px !important;
    width: 500px !important;
}
</style>
