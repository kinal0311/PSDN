var map;
function initMap() {
     map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: {lat: 0, lng: -180},
        mapTypeId: 'terrain'
    });

}
function haiHistory(data) {

    const center = new google.maps.LatLng(data[0].lat, data[0].lng);
    // using global variable:
    window.map.panTo(center);
    // map.clear();
    var marker = new google.maps.Marker({
        position: data[0],
        map: map,
        title: 'Start'
    });
    marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');

    var marker2 = new google.maps.Marker({
        position: data[data.length-1],
        map: map,
        title: 'End'
    });
    marker2.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
    var flightPath = new google.maps.Polyline({
        path: data,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);
}
