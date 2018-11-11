<template>
    <div>
        <v-container fluid grid-list-md  v-if="!submitCompleted && !sending">
            <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
                <v-card>
                    <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
                    <v-card-title primary-title>
                        <div class="headline">Fundraiser Information</div>
                    </v-card-title> 
                    <v-container fluid grid-list-lg>
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-text-field v-model="item.name" label="Name" prepend-icon="person" :rules="[v => !!v || 'Name is required']" required></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field v-model="item.email" label="Email" prepend-icon="contact_mail" :rules="[v => !!v || 'Email is required', v => /.+@.+/.test(v) || 'Email must be valid']" required></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-card-title primary-title>
                        <div class="headline">Create A Fundrasing Campaign</div>
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
                            <v-flex xs12 sm6>
                                <v-text-field
                                    v-model="item.target_amount"
                                    label="Target Amount"
                                    prepend-icon="attach_money"
                                    type="number"
                                    :rules="[v => !!v || 'Target Amount is required']"
                                    required
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 class="mt-2">
                                <div style="display: flex;">
                                    <v-tooltip bottom>
                                        <v-icon slot="activator">description</v-icon>
                                        <span>Content</span>
                                    </v-tooltip>
                                    <ckeditor style="width:100%" height="180px" class="ml-2" v-model="item.body" language="zh" extraplugins="divarea"/>
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
        </v-container>
        <!-- <v-container fluid grid-list-md v-if="submitCompleted">
            <v-alert
            :value="true"
            type="success"
            >
                Thank you for create a fundraising campaign. Kindly refer receipt in your mailbox. <br/>
                Thanks again for your generosity and support,
            </v-alert>
        </v-container> -->
        <v-container align-center justify-center>
            <v-card>
                <div class="text-xs-center">
                    <v-card-text v-if="sending">
                        <v-progress-circular
                        :size="138"
                        :width="7"
                        color="grey lighten-1"
                        indeterminate
                        ></v-progress-circular>
                    </v-card-text>
                    <v-card-text v-if="submitCompleted">
                            <v-icon size="168px" color="grey lighten-1">check_circle_outline</v-icon>
                            <div>
                            <h3>Create campaign successful</h3>
                            Thank you for create a fundraising campaign. Please wait for approving of your campaign.<br/>
                            Thanks again for your generosity and support,
                            </div>
                    </v-card-text>
                </div>
            </v-card>
        </v-container>
    </div>
</template>

<script>
export default {
    data () {
        return {
            sending: false,
            auth: window.user,
            sending: false,
            valid: true,
            item: {
                fundraise_id:'',
                id: "",
                title: "",
                status: 2,
                body: "",
                cover_img: null,
                target_amount: null,
                name: "",
                email: "",
            },
            preview: '',
            submitCompleted: false,
        };
    },
    mounted() { 
        let app = this;
        app.item.name = this.auth[0] != undefined ? this.auth[0] : ''
        app.item.email = this.auth[1] != undefined ? this.auth[1] : ''
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
                    method: 'post',
                    body: JSON.stringify(this.item),
                    headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.sending = false
                        this.submitCompleted = true
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
