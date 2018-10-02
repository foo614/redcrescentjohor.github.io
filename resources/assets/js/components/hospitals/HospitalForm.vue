<template>
    <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="saveItem">
        <v-card>
            <v-progress-linear height=3 :indeterminate="true" v-if="sending"></v-progress-linear>
            <v-card-title primary-title>
                <div class="headline">{{$route.name == "editHospital" ? 'Edit' : 'Add' }} Hospital</div>
            </v-card-title>
                <v-container fluid grid-list-lg>
                    <v-layout row wrap>
                        <v-flex xs12 sm6>
                            <v-text-field
                                v-model="item.name"
                                label="Name"
                                prepend-icon="person"
                                :rules="[v => !!v || 'Name is required']"
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
                                v-model="item.contact"
                                label="Contact"
                                prepend-icon="contact_phone"
                                :rules="[v => !!v || 'Contact is required']"
                                required
                            ></v-text-field>
                        </v-flex>
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
    components: {},
    mounted() {
        this.autocomplete = new google.maps.places.Autocomplete(
            this.$refs.autocomplete
        );
        this.autocomplete.setComponentRestrictions(
            {'country': ['my', 'sg']})
        this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace()
            let lat = place.geometry.location.lat()
            let lng = place.geometry.location.lng()

            this.item.address = place.name
            this.item.map_lat = lat
            this.item.map_lng = lng
        });
        if(this.$route.name === "editHospital"){
            let app = this
            let id = this.$route.params.id
            axios.get('/api/hospital/' + id)
            .then(function (res) {
                app.item = res.data
                app.item.hospital_id = res.data.id
            })
            .catch(function () {
                this.$toasted.error("Something wrong...", {icon:"error"})
            });
        }
    },
    data () {
        return {
            hospitales:[],
            sending: false,
            valid: true,
            item: {
                hospital_id:null,
                id: null,
                name: "",
                email: "",
                contact: "",
                address: "",
            },
        };
    },
    computed: {},
    methods:{
        saveItem(){
            if (this.$refs.form.validate()){
                this.sending = true
                setTimeout(()=> {
                    fetch("/api/hospital", {
                        method: this.$route.name == 'createHospital' ? "post" : "put",
                        body: JSON.stringify(this.item),
                        headers:{"content-type": "application/json"}
                    })
                    .then(res => {
                        this.sending = false
                        let currentPage = this.$route.name
                        this.$router.push({ path: '/hospitals' }, ()=> {
                            this.$toasted.success(this.item.name + (currentPage === 'createHospital' ? ' added' : ' updated') , {icon:"check"})
                        })
                    })
                    .catch(err => console.log(err))
                }, 2000)
            }
        }
    }
}
</script>

<style>

</style>
