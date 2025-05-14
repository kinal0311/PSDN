// var latlngbounds=new google.maps.LatLngBounds,map=new google.maps.Map(document.getElementById("map"),{zoom:18,center:{lat:11.1271,lng:78.6569},mapTypeId:"roadmap",travelMode:"DRIVING"});function latlngconversion(a){return lat_bind=a,lat_bind_length=7-lat_bind.length,0!=lat_bind_length&&("1"==lat_bind_length?lat_bind+="0":"2"==lat_bind_length?lat_bind+="00":"3"==lat_bind_length?lat_bind+="000":"4"==lat_bind_length&&(lat_bind+="0000")),lat_bind}$(function(){var a=new FormData;a.append("hidimei",$("#hidimei").val()),a.append("hidnow",$("#hidnow").val()),$.ajax({url:base_url+"vehiclehistory/getvehicletrackingdatas",type:"POST",cache:!1,data:a,processData:!1,contentType:!1,success:function(a){var e=[];for(ii=0;ii<a.rawdatas.length;ii++)e.push({lat:parseFloat(latlngconversion(a.rawdatas[ii].latitude)),lng:parseFloat(latlngconversion(a.rawdatas[ii].longitude))});var t,n,o=[];if(a.rawdatas.length>1){var l=a.rawdatas.length-1;o.push({lat:parseFloat(latlngconversion(a.rawdatas[0].latitude)),lng:parseFloat(latlngconversion(a.rawdatas[0].longitude))}),o.push({lat:parseFloat(latlngconversion(a.rawdatas[l].latitude)),lng:parseFloat(latlngconversion(a.rawdatas[l].longitude))})}else o.push({lat:parseFloat(latlngconversion(a.rawdatas[0].latitude)),lng:parseFloat(latlngconversion(a.rawdatas[0].longitude))});for(new google.maps.Polyline({path:e,geodesic:!1,strokeColor:"#222d32",strokeOpacity:1,strokeWeight:2}).setMap(map),n=0;n<o.length;n++)"0"==n?t=new google.maps.Marker({position:o[n],icon:"http://maps.google.com/mapfiles/ms/icons/green-dot.png",map:map}):"1"==n&&(t=new google.maps.Marker({position:o[n],icon:"http://maps.google.com/mapfiles/ms/icons/red-dot.png",map:map})),latlngbounds.extend(t.position);new google.maps.LatLngBounds;map.setCenter(latlngbounds.getCenter()),map.fitBounds(latlngbounds)}})});var prevmarkers,socket=io(SOCKET_URL);socket.on("connect",function(a){});var prevmarkers_arr=[];socket.on("trackingData",function(a){var e,t,n=a.data;parseFloat(n.latitude),parseFloat(n.longitude);$("#signelStrength").text(n.signalStrength),$("#simNumber").text(n.simNumber),$("#lastupdatedTime_ori").text(new Date(1e3*parseFloat(n.lastupdatedTime)).toLocaleDateString("en-GB",{year:"numeric",month:"long",day:"numeric",hour:"numeric",minute:"numeric",second:"numeric"})),$("#lastupdatedTime").text(new Date(1e3*parseFloat(n.lastupdatedTime))),$("#speed").text(n.speed),console.log(n),e=new Date,t=new Date(1e3*parseFloat(n.lastupdatedTime));a=Math.abs(e-t)/1e3;var o=Math.floor(a/86400),l=Math.floor(a/3600)%24,r=Math.floor(a/60)%60,s=a%60,p="";parseInt(o)>0&&(p+=parseInt(o)+" days, "),parseInt(l)>0&&(p+=parseInt(l)+" hours, "),parseInt(r)>0&&(p+=parseInt(r)+" minutes, "),parseInt(s)>=0&&(p+=parseInt(s)+" seconds "),p+=" ago.",$("#lastupdatedTime").text(p);var d='<a target="_blank" href="https://www.google.co.in/maps?q='+n.latitude+","+n.longitude+'">'+n.latitude+","+n.longitude+"</a>";$("#latlng").html(d);var g=new google.maps.LatLng(parseFloat(n.latitude),parseFloat(n.longitude));for(marker=new google.maps.Marker({position:g,icon:"http://www.psdn.live/public/frontend/icons/icon1.png",map:map}),prevmarkers_arr.push(marker),prevmarkers="yes",i=0;i<prevmarkers_arr.length-1;i++)prevmarkers_arr[i].setIcon("http://www.psdn.live/public/images/black-dot.jpg");map.setZoom(18),map.setCenter(g)});


var latlngbounds = new google.maps.LatLngBounds,
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 18,
        center: {
            lat: 11.1271,
            lng: 78.6569
        },
        mapTypeId: "roadmap",
        travelMode: "DRIVING"
    });

function latlngconversion(a) {
    return lat_bind = a, lat_bind_length = 7 - lat_bind.length, 0 != lat_bind_length && ("1" == lat_bind_length ? lat_bind += "0" : "2" == lat_bind_length ? lat_bind += "00" : "3" == lat_bind_length ? lat_bind += "000" : "4" == lat_bind_length && (lat_bind += "0000")), lat_bind
}
$(function() {
    var a = new FormData;
    a.append("hidimei", $("#hidimei").val()), a.append("hidnow", $("#hidnow").val()), $.ajax({
        url: base_url + "vehiclehistory/getvehicletrackingdatas",
        type: "POST",
        cache: !1,
        data: a,
        processData: !1,
        contentType: !1,
        success: function(a) {
            var e = [];
            for (ii = 0; ii < a.rawdatas.length; ii++) e.push({
                lat: parseFloat(latlngconversion(a.rawdatas[ii].latitude)),
                lng: parseFloat(latlngconversion(a.rawdatas[ii].longitude))
            });
            var t, n, o = [];
            if (a.rawdatas.length > 1) {
                var l = a.rawdatas.length - 1;
                o.push({
                    lat: parseFloat(latlngconversion(a.rawdatas[0].latitude)),
                    lng: parseFloat(latlngconversion(a.rawdatas[0].longitude))
                }), o.push({
                    lat: parseFloat(latlngconversion(a.rawdatas[l].latitude)),
                    lng: parseFloat(latlngconversion(a.rawdatas[l].longitude))
                })
            } else o.push({
                lat: parseFloat(latlngconversion(a.rawdatas[0].latitude)),
                lng: parseFloat(latlngconversion(a.rawdatas[0].longitude))
            });
            for (new google.maps.Polyline({
                    path: e,
                    geodesic: !1,
                    strokeColor: "#222d32",
                    strokeOpacity: 1,
                    strokeWeight: 2
                }).setMap(map), n = 0; n < o.length; n++) "0" == n ? t = new google.maps.Marker({
                position: o[n],
                icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
                map: map
            }) : "1" == n && (t = new google.maps.Marker({
                position: o[n],
                icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
                map: map
            })), latlngbounds.extend(t.position);
            new google.maps.LatLngBounds;
            map.setCenter(latlngbounds.getCenter()), map.fitBounds(latlngbounds)
        }
    })
});
var prevmarkers, socket = io(SOCKET_URL);
socket.on("connect", function(a) {});
var prevmarkers_arr = [];
socket.on("trackingData", function(a) {
    var e, t, n = a.data;
    parseFloat(n.latitude), parseFloat(n.longitude);
    $("#signelStrength").text(n.signalStrength), $("#simNumber").text(n.simNumber), $("#lastupdatedTime_ori").text(new Date(1e3 * parseFloat(n.lastupdatedTime)).toLocaleDateString("en-GB", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric"
    })), $("#lastupdatedTime").text(new Date(1e3 * parseFloat(n.lastupdatedTime))), $("#speed").text(n.speed), console.log(n), e = new Date, t = new Date(1e3 * parseFloat(n.lastupdatedTime));
    a = Math.abs(e - t) / 1e3;
    var o = Math.floor(a / 86400),
        l = Math.floor(a / 3600) % 24,
        r = Math.floor(a / 60) % 60,
        s = a % 60,
        p = "";
    parseInt(o) > 0 && (p += parseInt(o) + " days, "), parseInt(l) > 0 && (p += parseInt(l) + " hours, "), parseInt(r) > 0 && (p += parseInt(r) + " minutes, "), parseInt(s) >= 0 && (p += parseInt(s) + " seconds "), p += " ago.", $("#lastupdatedTime").text(p);
    var d = '<a target="_blank" href="https://www.google.co.in/maps?q=' + n.latitude + "," + n.longitude + '">' + n.latitude + "," + n.longitude + "</a>";
    $("#latlng").html(d);
    var g = new google.maps.LatLng(parseFloat(n.latitude), parseFloat(n.longitude));
    for (marker = new google.maps.Marker({
            position: g,
            icon: "http://www.psdn.live/public/frontend/icons/icon1.png",
            map: map
        }), prevmarkers_arr.push(marker), prevmarkers = "yes", i = 0; i < prevmarkers_arr.length - 1; i++) prevmarkers_arr[i].setIcon("http://www.psdn.live/public/images/black-dot.jpg");
    map.setZoom(18), map.setCenter(g)
});