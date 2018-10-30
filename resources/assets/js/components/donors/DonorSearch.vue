<template>
    <v-card class="pa-3" flat>
        <v-layout row wrap>
        <v-flex xs12 sm4>
            <v-select
            :items="bloodTypes"
                v-model="item.blood_type"
                item-text="name"
                menu-props="auto"
                label="Blood Type"
                hide-details
                prepend-icon="opacity"
                single-line
                @change="onChange"
            ></v-select>
        </v-flex>
        <v-flex xs12 sm4>
            <v-select
                :items="hospitals"
                v-model="item.hospital"
                item-text="name"
                menu-props="auto"
                label="Hospital"
                hide-details
                prepend-icon="local_hospital"
                single-line
                @change="onChange"
                return-object
            ></v-select>
        </v-flex>
        <v-flex xs12 sm4>
            <v-select
                :items="radius"
                v-model="item.search_radius"
                menu-props="auto"
                label="Radius (kilometer)"
                hide-details
                prepend-icon="360"
                single-line
                @change="onChange"
            ></v-select>
        </v-flex>
        <v-flex sm8 xs12 mt-3>
            <div class="google-map" :id="mapName"></div>
        </v-flex>
        <v-flex sm4 xs12 mt-3>
            <v-card elevation-5 style="margin-left:10px">   
                <v-list>
                    <v-list-tile>
                    <v-list-tile-content>
                        <v-list-tile-title>Donator(s) Nearby</v-list-tile-title>
                        <v-list-tile-sub-title>
                            <v-avatar size="16"><img src="http://maps.google.com/mapfiles/ms/icons/green-dot.png" /></v-avatar> Donator 
                            <v-avatar size="16"><img src="http://maps.google.com/mapfiles/ms/icons/blue-dot.png" /></v-avatar> Hospital
                        </v-list-tile-sub-title>
                    </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
                <v-list v-if="searchResults.length > 0">
                    <v-list-tile>
                    <v-list-tile-action>
                        <v-checkbox ripple v-model="selectAll" :indeterminate="selected.length > 0 && selected.length < searchResults.length" :input-value="selected.length === searchResults.length ? true : false"></v-checkbox>
                    </v-list-tile-action>
                    <v-list-tile-title>Name</v-list-tile-title>
                    <v-list-tile-title>Distance</v-list-tile-title>
                    <v-list-tile-action>Status</v-list-tile-action>
                    </v-list-tile>
                </v-list>
                <v-list>
                    <v-list-tile-content v-if="searchResults.length < 1" class="ml-2"> Let's discover donators nearby</v-list-tile-content>
                    <v-list-tile 
                    v-for="(result, index) in searchResults"
                    :key="index"                    
                    >
                    <v-list-tile-action>
                        <v-checkbox v-model="selected" :value="index" multiple ripple></v-checkbox>
                    </v-list-tile-action>
                    <v-list-tile-title @click="onClickDonor(result.donorMarker)" style="cursor: pointer">{{result.donor['name']}}</v-list-tile-title>
                    <v-list-tile-title>{{result.verifyDistanceBetweenLocation | formatDistance}}</v-list-tile-title>
                    <v-list-tile-action>
                        <v-scroll-x-transition>
                            <v-icon
                                color="green lighten-1"
                                v-if="result.search == true"
                                >
                                check
                            </v-icon>
                        </v-scroll-x-transition>
                </v-list-tile-action>
                    </v-list-tile>
                </v-list>
        
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <!-- <v-btn color="primary" :disabled="searchResults.length < 1 || selected.length === 0" @click="sendNotificationBySelected(this, selected , item.hospital.id, searchResults)">Send</v-btn> -->
                    <v-btn :loading="loadingState"
                        :disabled="searchResults.length < 1 || selected.length === 0 || loadingState"
                        color="primary"
                        @click.native="sendNotificationBySelected(this, selected , item.hospital.id, searchResults)"
                        >
                        Send
                        <v-icon right dark>send</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
        </v-layout>
    </v-card>
</template>
<script>
const CircularJSON = require('circular-json');
export default {
    data: function () {
        return {
        csrf_token: window.csrf_token,
        radius:[5,10,15,20,25,30,35,40,45,50,55,60,75,80,85,90,95,100],
        bloodTypes:[],
        hospitals:[],
        hospitalMarker:null,
        loader: null,
        loadingState: false,
        item:{
            blood_type:'',
            hospital:'',
            search_radius:'',
            icon:true
        },
        mapName: "google-map",
        originMarker: [{latitude:51.501527, longitude:-0.1921837}],
        markerCoordinates:[{
        latitude: 51.505874,
        longitude: -0.1838486
      }, {
        latitude: 51.4998973,
        longitude: -0.202432
      }],
        map: null,
        bounds: null,
        markers: [],
        marker: null,
        donorMarkers: [],
        donorInfoWindows: [],
        customMarker: 'https://developers.google.com/maps/documentation/javascript/images/marker_green',
        searchResults:[],
        selected: [],
        }
    },
    created() {
        this.fetchSelectTypes()
    },
    mounted: function () {
        this.bounds = new google.maps.LatLngBounds();
        const element = document.getElementById(this.mapName)
        const mapCentre = this.originMarker[0]
        let loc = new google.maps.LatLng(1.534054, 103.621729);
        const options = {
            zoom:8,
            center: loc
        }
        this.map = new google.maps.Map(element, options);

        //function send
        window.sendNotification = function (elm, donorId, hospitalId) {
            $(elm).prop('disabled', true);
            $(elm).text('Sending...');
            $.ajax('/mail/send', {
                method: 'GET',
                data: {
                    _token: csrf_token,
                    donor_id: donorId,
                    hospital_id: hospitalId
                },
                success: function () {
                    $(elm).text('Notification Sent');
                },
                error: function () {
                    $(elm).text('Failed');
                    setTimeout(function () {
                        $(elm).text('Try resend');
                        $(elm).prop('disabled', false);
                    }, 1500);
                }
            });
        };
    },
    methods:{
        fetchSelectTypes() {
            axios.get("/api/bloodTypes").then(res => {
                this.bloodTypes = res.data
            });
            axios.get("/api/hospitals").then(res => {
                this.hospitals = res.data
            });
        },
        onChange(){
            if(!this.item.blood_type || !this.item.hospital || !this.item.search_radius)
                return
            this.findDonors()
        },
        findDonors(){
            axios.get("api/donors").then(res => {
                this.donors = res.data;
                this.filterDonors(this.donors);
            });
        },
        filterDonors(donors){
            let search_radius = this.item.search_radius * 1000
            if(this.item.hospital){
                if(this.hospitalMarker)
                this.hospitalMarker.setMap(null)

                //hospitalLocation
                this.hospitalLocation = new google.maps.LatLng(this.item.hospital.map_lat, this.item.hospital.map_lng)
                this.hospitalMarker = new google.maps.Marker({
                    map: this.map,
                    position: this.hospitalLocation,
                    icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                });
            }

            // check whether circle exist inside the map
            //if true remove then
            if(this.circle)
                this.circle.setMap(null)

            //insert circle
            this.circle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: this.map,
                center: this.hospitalLocation,
                radius: search_radius
            })

            //put donor marker
            this.donorMarkers.forEach(function (donorMarker) {
                donorMarker.setMap(null);
            })

            let bounds = new google.maps.LatLngBounds(null)
            bounds.extend(this.hospitalLocation)

            if(donors.length === 0){
                this.$toasted.info('No donors in records' , {icon:"error"})
            }else{
                this.searchResults=[]
                for(let i=0; i<donors.length; i++){
                    let donor = donors[i]
                    if(donor.blood_type.name != this.item.blood_type)
                        continue
                    let donorLocation = new google.maps.LatLng(donor.map_lat, donor.map_lng)
                    let verifyDistanceBetweenLocation = google.maps.geometry.spherical.computeDistanceBetween(this.hospitalLocation, donorLocation)
                    if(verifyDistanceBetweenLocation > search_radius)
                        continue
                    let markerDonorLetter = donor.name.charAt(0).toUpperCase()
                    let markerDonorIcon = this.customMarker + markerDonorLetter + '.png'
                    let donorMarker = new google.maps.Marker({
                        map: this.map,
                        position: donorLocation,
                        animation: google.maps.Animation.DROP,
                        icon: markerDonorIcon
                    })
                    this.makeInfoWindow(donor, donorMarker)

                    this.donorMarkers.push(donorMarker)

                    bounds.extend(donorLocation)

                    let search = false
                    this.searchResults.push({donor, donorMarker, verifyDistanceBetweenLocation, search})
                }
                if(this.searchResults.length === 0)
                    this.$toasted.error('No donors found!' , {icon:"error"})
            this.map.setCenter(this.hospitalLocation);
            this.map.fitBounds(bounds);
            this.map.panToBounds(bounds);
            }

        },
        makeInfoWindow(donor, marker){
            let contentString =
            '<div class="info-box"><h2 style="display: inline-flex"><i class="material-icons mr-1">person</i>' + donor.name +'</h2>'+ 
            '<div><hr class="v-divider theme--light mt-1 mb-1"><h4>Blood Type: ' + donor.blood_type.name +'</h4></div><div><h4>Contact: '
            + donor.contact +'</h4></div><div><h4>Email: '+ donor.email +'</h4></div><div><h4>Location: '+ donor.address + 
            '</h4></div><div class="v-btn theme--light success ml-0"><a class="v-btn__content" onclick="sendNotification(this, ' + donor.id +',' + this.item.hospital.id +')">Send</a></div></div>';
            let infoWindow = new google.maps.InfoWindow({
                content: contentString,
            })
            let app = this
            marker.addListener('click', function(){
                app.donorInfoWindows.forEach(function (iw){
                    iw.close();
                })
                infoWindow.open(app.map, marker)
            })
            app.donorInfoWindows.push(infoWindow)
        },
        sendNotificationBySelected(elm, donorId, hospitalId, status){
            if(donorId){
                this.loader = 'loadingState'
                if(donorId.length > 0){
                    donorId.forEach(function(v){
                        var donor_id = status[v].donor['id']
                        setTimeout(() => {$.ajax('/mail/send', {
                            method: 'GET',
                            data: {
                                _token: csrf_token,
                                donor_id: donor_id,
                                hospital_id: hospitalId
                            },
                        })} , 1500)
                        setTimeout(() =>status[v].search = true, 3000)
                    })
                }
                setTimeout(() => this.$toasted.success('Notification Sent' , {icon:"check"}), 3000)
                this.selected = [];
            }
        },
        onClickDonor(donorMarker){
            google.maps.event.trigger(donorMarker, 'click');
        }
    },
    computed: {
        selectAll: {
            get: function () {
                return this.searchResults ? this.selected.length == this.searchResults.length : false;
            },
            set: function (value) {
                var selected = [];

                if (value) {
                    this.searchResults.forEach(function (obj,index) {
                        selected.push(index);
                    });
                }

                this.selected = selected;
            }
        }
    },
    filters:{
        formatDistance(v) {
            let val = (v/1000).toFixed(1) + ' km'
            return val
        }
    },
    watch:{
        loader () {
            const l = this.loader
            this[l] = !this[l]

            setTimeout(() => (this[l] = false), 3000)

            this.loader = null
        }
    }
};
</script>
<style scoped>
.google-map {
  /* width: 800px; */
  height: 600px;
  margin: 0 auto;
  background: gray;
}
.info-box {
    height: 200px;    
    width: 300px; 
    -webkit-user-select: none; 
    background-color: white; 
}
  @-moz-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-webkit-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-o-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
</style>