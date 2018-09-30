<template>
    <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="addItem">
                {{item}}
            <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">Add Donor</div>
            </v-card-title>
                <v-container fluid grid-list-lg>
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.name"
                                label="Name"
                                prepend-icon="person"
                                :rules="[v => !!v || 'Title is required']"
                                required
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.email"
                                label="Email"
                                prepend-icon="contact_mail"
                                :rules="[v => !!v || 'Email is required', v => /.+@.+/.test(v) || 'Email must be valid']"
                                required
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.ic"
                                label="Identity Card"
                                prepend-icon="credit_card"
                                :rules="[v => !!v || 'Identity Card is required']"
                                required
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.contact"
                                label="Contact"
                                prepend-icon="contact_phone"
                                :rules="[v => !!v || 'Contact is required']"
                                required
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.password"
                                type="password"
                                label="Password"
                                prepend-icon="vpn_key"
                                :rules="[v => !!v || 'Password is required', v=> (v && v.length >= 6) || 'Password must be greater equal than 6 characters or numbers']"
                                required
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.password_confirmation"
                                type="password"
                                label="Confirm Password"
                                prepend-icon="vpn_key"
                                :rules="[v => !!v || 'Password is required', v => v === item.password || 'Password not match']"
                                required
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <v-select
                                v-model="item.membership_type_id"
                                :items="membershipTypes"
                                prepend-icon="people"
                                item-text="name"
                                item-value="id"
                                label="Membership Type"
                                :rules="[v => !!v || 'Membership Type is required']"
                            ></v-select>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-select
                                v-model="item.blood_type_id"
                                :items="bloodTypes"
                                prepend-icon="opacity"
                                item-text="name"
                                item-value="id"
                                label="Blood Type"
                                :rules="[v => !!v || 'Blood Type is required']"
                            ></v-select>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <div class="v-input v-text-field theme--light">
                                <div class="v-input__prepend-outer">
                                    <div class="v-input__icon v-input__icon--prepend">
                                        <i aria-hidden="true" class="v-icon material-icons theme--light">map</i>
                                    </div>
                                </div>
                                <div class="v-input__control">
                                    <div class="v-input__slot">
                                        <div class="v-text-field__slot">
                                            <label aria-hidden="true" class="v-label v-label--active theme--light" style="left: 0px; right: auto; position: absolute;">Location</label>
                                            <input ref="autocomplete" 
                                            placeholder="Search" 
                                            class="search-location"
                                            v-model="item.address"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </v-flex>
                        <v-flex xs12 sm6>
                            <v-icon>image</v-icon>
                            <v-badge overlap>
                                <span slot="badge" v-if="(preview || item.avatar)" @click="item.avatar = null; preview= null">x</span>
                                <v-avatar tile class="elevation-7 ml-2" size="128">
                                    <v-img lazy-src aspect-ratio="2" v-if="item.avatar || preview" 
                                    :src="(item.avatar && !preview) ? '/img/'+item.avatar : preview ? preview : null"
                                        alt="profile_image"></v-img>
                                    <v-tooltip bottom v-if="!preview && !item.avatar">
                                        <input type="file" ref="avatar" v-on:change="handleFile" style="display:none">
                                        <v-icon large @click="pickFile" slot="activator">
                                            image
                                        </v-icon>
                                        <span>Upload Profile Photo</span>
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
export default {
    components: {},
    mounted() {
        this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
            // {types: ['geocode']}
        );
        this.autocomplete.setComponentRestrictions(
            {'country': ['my', 'sg']});
        this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace();
            let lat = place.geometry.location.lat();
            let lng = place.geometry.location.lng();

            this.item.address = place.name;
            this.item.map_lat = lat;
            this.item.map_lng = lng;
        });
    },
    data () {
        return {
            preview: "",
            bloodTypes:[],
            membershipTypes:[],
            items:[],
            sending: false,
            valid: true,
            item: {
                user_id:null,
                id: null,
                name: "",
                email: "",
                ic: "",
                contact: "",
                password: "",
                membership_type_id: "",
                blood_type_id: "",
                address: "",
                avatar: "",
            },
            saveSnackbar:{},
        };
    },
    computed: {},
    created(){
        this.fetchSelectTypes();
    },
    methods:{
        fetchSelectTypes() {
            axios.get("../api/membershipTypes").then(res => {
                this.membershipTypes = res.data;
            });
            axios.get("../api/bloodTypes").then(res => {
                this.bloodTypes = res.data;
            });
        },
        pickFile() {
            this.$refs.avatar.click()
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
                vm.item.avatar = e.target.result;
            };
            reader.readAsDataURL(file);
            console.log(file);
        },
        addItem(){
            if (this.$refs.form.validate()){
                this.sending = true;
                setTimeout(()=> {
                    fetch("../api/member", {
                    method: "post",
                    body: JSON.stringify(this.item),
                    headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.$router.push({name:'usersIndex'})
                    })
                    .then(data => {
                        this.sending = false;
                        this.saveSnackbar = {snackbar: true, color: 'success', text: this.item.name +' added', absolute: true, right: true, top: true}
                        this.$refs.form.reset();
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
