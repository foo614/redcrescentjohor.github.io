<template>
    <v-card class="pa-3" flat>
        <v-layout row wrap>
        <v-flex xs12 sm4>
            <v-select
            :items="bloodTypes"
                v-model="item.blood_type"
                item-text="name"
                menu-props="auto"
                label="Select"
                hide-details
                prepend-icon="map"
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
                label="Select"
                hide-details
                prepend-icon="map"
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
                label="Select"
                hide-details
                prepend-icon="map"
                single-line
                @change="onChange"
            ></v-select>
        </v-flex>
        <v-flex sm8 xs12 mt-3>
            <div class="google-map" :id="mapName"></div>
            <!-- <v-list-tile dense id="legend"></v-list-tile> -->
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
                <v-list>
                    <v-list-tile>
                    <v-list-tile-action>
                        <v-switch color="purple"></v-switch>
                    </v-list-tile-action>
                    <v-list-tile-title>Enable messages</v-list-tile-title>
                    </v-list-tile>
                </v-list>
        
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn flat @click="menu = false">Cancel</v-btn>
                    <v-btn color="primary" flat @click="menu = false">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
        </v-layout>
    </v-card>
</template>
<script>
export default {
    name: 'google-map',
    props: ['name'],
    data: function () {
        return {
        csrf_token: window.csrf_token,
        radius:[5,10,15,20,25,30,35,40,45,50,55,60,75,80,85,90,95,100],
        bloodTypes:[],
        hospitals:[],
        hospitalMarker:null,
        item:{
            blood_type:'',
            hospital:'',
            search_radius:''
        },
        mapName: this.name + "-map",
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
        filteredList: [],
        customMarker: 'https://developers.google.com/maps/documentation/javascript/images/marker_green',
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
            zoom:10,
            center: loc
        }
        this.map = new google.maps.Map(element, options);

        //function send
        window.sendNotification = function (elm, donorId, hospitalId) {
            $(elm).prop('disabled', true);
            $(elm).text('Sending...');
            $.ajax('mail/send', {
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
                        $(elm).text('Send Notification');
                        $(elm).prop('disabled', false);
                    }, 1000);
                }
            });
        };
    },
    watch:{},
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
            this.findDonors();
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

            for(let i=0; i<donors.length; i++){
                let donor = donors[i]
                if(donor.blood_type.name != this.item.blood_type)
                    continue
                let donorLocation = new google.maps.LatLng(donor.map_lat, donor.map_lng)
                if(google.maps.geometry.spherical.computeDistanceBetween(this.hospitalLocation, donorLocation) > search_radius)
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

                
            }
            this.map.setCenter(this.hospitalLocation);
            this.map.fitBounds(bounds);
            this.map.panToBounds(bounds);


        },
        makeInfoWindow(donor, marker){
            let contentString =
            '<div class="info-box"><h2 style="display: inline-flex"><i class="material-icons mr-1">people</i>' + donor.name + '</h2>'+ '<div><hr class="v-divider theme--light mt-1 mb-1"><h4>Blood Type: ' + donor.blood_type.name +'</h4></div><div><h4>Contact: '+ donor.contact + '</h4></div><div><h4>Email: '+ donor.email + '</h4></div><div><h4>Location: ' + donor.address + '</h4></div></div><a class="v-btn__content" onclick="sendNotification(this, ' + donor.id +',' + this.item.hospital.id +')">Send</a>';
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

    },
    
    
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
</style>