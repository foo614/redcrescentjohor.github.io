
var hospitalLocation;
var hospitalMarker;
var donorMarkers = [];
var donorInfoWindows = [];
var array = [];
var cityCircle;
var customMarker = 'https://developers.google.com/maps/documentation/javascript/images/marker_green';
var icons = {
    hospital: {
      name: 'Hospital',
      icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
    },
    donor: {
        name: 'Donator',
        icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
    }
}
var hospitalSelectedId = 0;

window.initMap = function () {
    document.dispatchEvent(new Event('mapinit'));
    window.mapInitialized = true;

    if (document.getElementById('address-input-map') !== null) {
        initAddressInputMaps();
        initAutocomplete();
        tryPreviousLocation();
        tryGeoLocation();
    }

    if (document.getElementById('find-map') !== null) {
        initFindMap();
    }
};

var map;
var marker;

function initAddressInputMaps () {
    let elm = document.getElementById('address-input-map');
    let loc = {lat: 1.534054, lng: 103.621729};
    map = new google.maps.Map(elm, {
        zoom: 4,
        center: loc
    });
}

function initAutocomplete () {
    let input = document.getElementById('pac-input');
    let searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    input.addEventListener('keydown', function (evt) {
        if (evt.keyCode === 13) {
            evt.preventDefault();
            return false;
        }
    });

    map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function () {
        let places = searchBox.getPlaces();

        if (places.length === 0)
            return;

        let prefPlace = places[0];
        let hospital_name = document.getElementById('name');
        let hospital_contact = document.getElementById('contact');
        let hospital_email = document.getElementById('email');

        if(prefPlace && (window.$.ajaxSettings.url == "http://127.0.0.1:8000/branches/create" || window.$.ajaxSettings.url == "http://127.0.0.1:8000/hospitals/create" || window.$.ajaxSettings.url == "http://127.0.0.1:8000/donors/create" || window.$.ajaxSettings.url == "http://127.0.0.1:8000/users/create")){
            if(window.$.ajaxSettings.url == "http://127.0.0.1:8000/hospitals/create" || window.$.ajaxSettings.url == "http://127.0.0.1:8000/branches/create"){    
                hospital_name.value = prefPlace.name ? prefPlace.name : '-';
                document.getElementById('name-is-found').classList.add('is-dirty');
                hospital_contact.value = prefPlace.formatted_phone_number ? prefPlace.formatted_phone_number : '-';
                document.getElementById('contact-is-found').classList.add('is-dirty');
                hospital_email.value = '-';
                document.getElementById('email-is-not-found').classList.add('is-dirty');
            }
            document.getElementById('address-is-found').classList.add('is-dirty');
        }
        if (!prefPlace.geometry) {
            console.log('Returned place contains no geometry');
            return;
        }
        changeAddress(prefPlace.geometry.location, prefPlace.formatted_address);

        if (prefPlace.geometry.viewport) {
            map.fitBounds(prefPlace.geometry.viewport);
        }
    });
}

function tryPreviousLocation () {
    let mapLat = document.getElementById('map_lat').value;
    let mapLng = document.getElementById('map_lng').value;

    if (!mapLat || !mapLng)
        return;

    console.log(mapLat + ' ' + mapLng);
    let location = {lat: parseFloat(mapLat), lng: parseFloat(mapLng)};
    changeAddress(location);
}

//get current location
function tryGeoLocation () {
    if (document.getElementById('map_lat').value || document.getElementById('map_lng').value)
        return;

    if (!('geolocation' in navigator))
        handleLocationError(true, infoWindow, map.getCenter());

    navigator.geolocation.getCurrentPosition(function (pos) {
        let location = {lat: pos.coords.latitude, lng: pos.coords.longitude};
        changeAddress(location);
    },
    function(error){
        if(error.code == error.PERMISSION_DENIED)
        console.warn('permission denied!')
    });
}

function handleLocationError(hasGeolocation, infoWindow, pos){
    infoWindow.setPosition(pos);
    infoWindow.setContent(hasGeolocation ? 'Error: Geolocation service failed' : 'Error: Your browser doesn\'t support geolication.');
    infoWindow.open(map);
}

function changeAddress (location, formattedAddress) {
    if (marker instanceof google.maps.Marker)
        marker.setMap(null);

    marker = new google.maps.Marker({
        map: map,
        position: location,
        draggable: true
    });
    map.setCenter(location);
    map.setZoom(17);

    let addressInput = document.getElementById('address');

    if (formattedAddress) {
        addressInput.value = formattedAddress;
        return;
    }

    let geocoder = new google.maps.Geocoder();
    geocoder.geocode({location: location}, function (results, status) {
        if (status !== 'OK') {
            window.alert('Geocoder failed due to: ' + status);
            return;
        }

        if (!results[0]) {
            window.alert('No results found');
            return;
        }
    });
}
function initFindMap () {
    let elm = document.getElementById('find-map');
    let loc = new google.maps.LatLng(1.534054, 103.621729);
    map = new google.maps.Map(elm, {
        zoom: 8,
        center: loc
    });
    var legend = document.getElementById('legend');
    for (var key in icons) {
      var type = icons[key];
      var name = type.name;
      var icon = type.icon;
      var div = document.createElement('div');
      div.innerHTML = '<img src="' + icon + '"> ' + name;
      legend.appendChild(div);
    }

    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend);
    findDonors();
    clearResults();
    addEventListeners();
}

function addEventListeners () {
    if (!document.getElementById('hospital') || !document.getElementById('blood_type') || !document.getElementById('search_radius'))
        return;
    $('#hospital').change(inputChangeCallback);
    $('#blood_type').change(inputChangeCallback);
    $('#search_radius').change(inputChangeCallback);
    function inputChangeCallback () {
        findDonors();
    }
}
var filteredList = [];
//function find donor
function findDonors () {
    // get all donor list
    $.ajax('/donor', {
        success: function (donors) {
            clearResults();
            filterDonors(donors);    
        },
        error: function () {
            console.log('An error occured while fetching donors');
        }
    });
    // filter donor list
    function filterDonors (donors) {
        let hospital = $('#hospital').val();
        let bloodType = $('#blood_type').val();
        let searchRadius = $('#search_radius').val() * 1000;
        
        if(hospital != null){
            // reset market if exist
            if(hospitalMarker){
                hospitalMarker.setMap(null);
            }
            //hospital location 
            hospitalLocation = new google.maps.LatLng(donors[1][hospital].map_lat, donors[1][hospital].map_lng);
            hospitalMarker = new google.maps.Marker({
                map: map,
                position: hospitalLocation,
                icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            });
        }
        // check if circle exist in map
        if(cityCircle){
            cityCircle.setMap(null);
        }
        // draw circle
        cityCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: hospitalLocation,
            radius: searchRadius
        }); 
        //put donator's marker 
        donorMarkers.forEach(function (mkr) {
            mkr.setMap(null);
        });
        
        donorMarkers = [];
        donorInfoWindows = [];
        filteredList = [];

        let bounds = new google.maps.LatLngBounds(null);
        bounds.extend(hospitalLocation);
        hospitalSelectedId = parseInt(document.getElementById('hospital').value) + 1;
        for (let i = 0; i < donors[0].length; i++) {
            let donor = donors[0][i];

            if ((donor.blood_type ? donor.blood_type.name : null) !== bloodType)
                continue;

            let loc = new google.maps.LatLng(donor.map_lat, donor.map_lng);

            if (google.maps.geometry.spherical.computeDistanceBetween(hospitalLocation, loc) > searchRadius)
                continue;
            // var markerLetter = String.fromCharCode('A'.charCodeAt(0) + (i % 26));
            var markerLetter = donor.name.charAt(0).toUpperCase();
            var markerIcon = customMarker + markerLetter + '.png';
            let mkr = new google.maps.Marker({
                map: map,
                position: loc,
                animation: google.maps.Animation.DROP,
                icon: markerIcon
            });
            
            makeInfoWindow(donor, mkr);

            donorMarkers.push(mkr);

            bounds.extend(loc);

            addResult(donors[0], i, mkr);

            filteredList.push(''+donors[0][i].user_id+'');
        }
        map.setCenter(hospitalLocation);
        map.fitBounds(bounds);
        map.panToBounds(bounds);
    }
    
    function makeInfoWindow (donor, mkr) {
        let contentString = '<div class=" mdl-card mdl-shadow--2dp">'+
                '<div class="mdl-card__supporting-text">' + 
                '<h4 class="mdl-card__title-text"><i class="material-icons">person</i> ' + donor.name + '</h4>'+
                '<strong>IC Number:</strong> ' + donor.ic + '<br/>' + 
                '<strong>Blood Type:</strong> ' + donor.blood_type.name + '<br/>' +
                '<strong>Contact Number:</strong> ' + donor.contact + '<br/>' +
                '<strong>Address:</strong> ' + donor.address  + '</div>'+
                '<div class="mdl-card__actions mdl-card--border"><a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary" onclick="sendNotification(this, ' + donor.id +',' + hospitalSelectedId +')">' + 'Send Invitation'  + '</a></div>'

        let infoWindow = new google.maps.InfoWindow({
            content: contentString
        });

        mkr.addListener('click', function () {
            donorInfoWindows.forEach(function (iw) {
                iw.close();
            });

            infoWindow.open(map, mkr);
        });

        donorInfoWindows.push(infoWindow);
    }
}
window.sendNotificationSelected = function(elm){
    $(elm).prop('disabled', true);
    $(elm).text('Sending...');
    $.each(filteredList, function(index, value){
        $.ajax('mail/send',{
            method: 'GET',
            data:{
                _token: Laravel.csrfToken,
                donor_id: value,
                hospital_id: hospitalSelectedId
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
    });
};

//function send
window.sendNotification = function (elm, donorId, hospitalId) {
    $(elm).prop('disabled', true);
    $(elm).text('Sending...');

    $.ajax('mail/send', {
        method: 'GET',
        data: {
            _token: Laravel.csrfToken,
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

$(function () {
    if (document.getElementById('address-input-map') !== null) {
        $('#address-input-map').parents('form')[0].onsubmit = function (e) {
            if (!(marker instanceof google.maps.Marker))
                return false;

            let mapLat = $('#map_lat');
            let mapLng = $('#map_lng');

            mapLat.attr('value', marker.getPosition().lat());
            mapLng.attr('value', marker.getPosition().lng());

            return true;
        };
    }
});

function addResult(result, i, mkr) {
    var results = document.getElementById('results');
    var markerLetter = result[i].name.charAt(0).toUpperCase();
    var markerIcon = customMarker + markerLetter + '.png';

    var tr = document.createElement('tr');

    var iconTd = document.createElement('td');
    var nameTd = document.createElement('td');
    var icon = document.createElement('img');
    icon.src = markerIcon;

    tr.setAttribute('style', 'cursor: pointer;');

    var label = document.createElement("label");
    label.className = "mdl-checkbox mdl-js-checkbox";
    label.setAttribute('for', 'checkbox'+i);

    var checkbox = document.createElement("input");
    checkbox.className = "mdl-checkbox__input";
    // checkbox.style = "width:60px;"
    checkbox.setAttribute("type", "checkbox");
    checkbox.setAttribute("id", "checkbox"+i);
    // checkbox.setAttribute("name", "dd");
    checkbox.checked = true; 

    label.appendChild(checkbox);
    var name = document.createTextNode(result[i].name);

    iconTd.appendChild(label);
    nameTd.appendChild(name);
    // componentHandler.upgradeElement(label);
    tr.appendChild(iconTd);
    tr.appendChild(nameTd);  

    tr.onclick = function() {
        google.maps.event.trigger(mkr, 'click');
        console.log("donor click!" + result[i]);
    };
    results.appendChild(tr);

  }
  
  function clearResults() {
    var results = document.getElementById('results');
    while (results.childNodes[0]) {
      results.removeChild(results.childNodes[0]);
    }
  }
