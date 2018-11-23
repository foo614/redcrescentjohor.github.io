<template>
    <v-container fluid grid-list-md>
            <v-layout row wrap align-center>
                <v-flex xs12 md6 offset-md3>
                    <v-card class="mb-3">
                        <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="updateItem">
                        <div class="text-xs-center">
                            <v-avatar size="125px" color="#757575" class="material-icons elevation-7 mb-1">
                                <span class="white--text headline" v-if="!show && !item.avatar">{{item.name | getFirstLetter}}</span>
                                <img
                                    v-show="item.avatar"
                                    :src= "!preview ? (!show ? (!item.avatar ? null : item.avatar ): item.avatar = null) : preview"
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
                                                v-model="item.blood_type_id"
                                                :items="bloodTypes"
                                                prepend-icon="opacity"
                                                item-text="name"
                                                item-value="id"
                                                label="Blood Type"
                                                :rules="[v => !!v || 'Blood Type is required']"
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
                        </v-form>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row>
                <v-flex xs12 md6 offset-md3 class="my-4">
                <v-card max-height="200px">
                    <v-toolbar color="#ca0000" dark>
                        <v-toolbar-title>Blood Donation Records</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <h4 class="display-1 pl-3" v-if="sum_volume"> Total : {{ sum_volume}} ml</h4>
                    </v-toolbar>
                    <v-list two-line subheader>
                    <v-list-tile v-if="items.length === 0">
                        No donation record(s)
                    </v-list-tile>
                    <v-list-tile
                        v-else
                        v-for="item in items"
                        :key="item.title"
                        avatar
                    >
                        <v-list-tile-content>
                        <v-list-tile-title>{{ item.post ? item.post.title : 'RCJ' }}</v-list-tile-title>
                        <v-list-tile-sub-title>{{ item.donate_date }}</v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-list-tile-action>
                            {{item.volume}}  ml
                        </v-list-tile-action>
                    </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
            </v-layout>
      </v-container>
</template>

<script>
export default {
    data () {
      return {
        item:{
            name:'',
            email:'',
            contact:'',
            user_id: '',
            avatar: '',
            place_id:null
        },
        show:false,
        valid: true,
        loading: false,
        preview:'',
        bloodTypes:[],
        items: [],
        sum_volume: null
      }
    },
    mounted() {
        this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
            // {types: ['geocode']}
        );
        this.autocomplete.setComponentRestrictions(
            {'country': ['my', 'sg']});
        this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace()
            let lat = place.geometry.location.lat()
            let lng = place.geometry.location.lng()
            let place_id = place.place_id
            let formatted_address = place.formatted_address

            this.item.place_id = place_id
            this.item.address = place.name
            this.item.map_lat = lat
            this.item.map_lng = lng
            this.item.formatted_address = formatted_address
        });
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
        axios.get("/api/bloodTypes").then(res => {
            this.bloodTypes = res.data
        });

        axios.get( `/api/bloodDonationRecordsFromUser/${id}`).then(function(res){app.items = res.data;}).catch(function(){console.log('failed to get blood records')})
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
            .join("").slice(0,2);
        }
    },
    watch:{
        items: function () {
            let app = this
            this.items.forEach(function(elm) {
                return app.sum_volume += elm.volume
            })
        },
    }
}
</script>

<style>

</style>
