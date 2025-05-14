<?php 
 $this->load->view('common/admin_login_header'); ?>
  <style>
        body
    {
      overflow-y: hidden;
    }
    .content-header
    {
      margin-top: -70px;
    }
    #searchbar
    {
       margin-left: 4%;
       margin-top: 20%;
       padding:15px;
       border-radius: 10px;
    }
   #list
   {
    font-size:  1.5em;
    margin-left: 5%;
    margin-right: 20%;
   }
   .vehicles
   {
    display: list-item;
   }
    .sidenav 
    {
        height: 100%;
        width: 300px;
        position: fixed;
        z-index: 1;
        top: 80px;
        right: 0;
        margin-bottom: 80px;
        background-color: rgb(255, 255, 255);
        overflow-x: hidden;
        padding-top: 50px;
        border: 1px solid lightgray;
    }

    .sidenav a 
    {
        padding: 6px 8px 6px 40px;
        text-decoration: none;
        font-size: 10px;
        color: #818181;
        display: block;
    }

    .sidenav a:hover 
    {
        color: #f1f1f1;
    }
    .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: row;
            width: 300px;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

    .main 
    {
        margin-right: 300px;
        margin-bottom: -5px;
        /* Same as the width of the sidenav */
        font-size: 28px;
        /* Increased text to enable scrolling */
        padding: 0px 10px;
        /* overflow-y: auto; */
        height: 500px;
    }
    .pd-1 
    {
        padding: 12px;
        font-size: 13px;
        margin-left: -20px;
    }
    .pdt-1 
    {
        padding-top: 6px;
    }

    .pdb-1 
    {
        padding-bottom: 6px;
    }

    .pd0 
    {
        padding: 0;
    }

    .mr-1 
    {
        margin: 12px;
    }

    .mrb-1 
    {
        margin-bottom: 12px;
    }

    .bgc-blue 
    {
        background-color: rgb(77, 209, 235);
    }

    .float-right 
    {
        float: right;
        position: absolute;
        right: 12px;
        top: 12px;
    }

    .mr-0 
    {
       margin: 0;
    }
    .head
    {
       text-align:center;
    }
    .veh-list
    {
       margin-top: -60px;
    }
    .dashboard
    {
       margin-top: -20px;
    }

    /* cursor pointer  */
      .vehicles {
        display: list-item;
        cursor: pointer; /* Add this line to set the cursor to a hand */
      }
      
    @media screen and (max-height: 450px) 
    {
        .sidenav 
        {
          padding-top: 15px;
        }
        .sidenav a 
        {
           font-size: 18px;
        }
    }

  </style>
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
 <script src="<?php echo base_url(); ?>public/js/frontend.js?id=<?php echo rand(); ?>"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
  <!--<script -->
  <!--  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_F76FCq1xJtvasEC9OxRguRKHxfVJFXc&callback=initMap"-->
  <!--    defer-->
  <!--  ></script>-->
  <script 
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx3BvK2E1sHk6jTJGF8ty7Brkh-nP4gd4&callback=initMap"
      defer
    ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </head>
  <body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	  <div class="dashboard">
	    <?php $this->load->view('common/dashboard_top_bar');?>
    </div>
    <?php $this->load->view('common/left_side_bar'); ?>
    <!-- input tag -->

    <!--SIDEBAR STARTS HERE-->
    <div class="sidenav mr-0  pd0" id="test">
      <div id="driver-details " class="bgc-blue pd-1 mrb-1">
          <h3 class="head">Vehicles List</h3>
      </div>
      <div class="veh-list">
      <div class="pd-1">
        <input id="searchbar" class="form-control" onkeyup="search_vehicle()" type="text" name="search_imei" placeholder="Search vehicles..">
      </div>
      <?php 
       for ($i = 0; $i < count($listofCustomers); $i++) {
        if ($listofCustomers[$i]['latitude'] != '' && $listofCustomers[$i]['longitude'] != '' && $listofCustomers[$i]['imei'] != '' && $listofCustomers[$i]['vehicleRegNumber'] != '') {?>
            <!-- <div id="list" class="vehicles" onclick="zoom_latlng(<?php echo $listofCustomers[$i]['latitude'] ?>,<?php echo $listofCustomers[$i]['longitude'] ?>)"> -->
            <div id="list" class="vehicles"
                onclick="zoom_latlng('<?php echo $listofCustomers[$i]['latitude']; ?>', '<?php echo $listofCustomers[$i]['longitude']; ?>', '<?php echo $listofCustomers[$i]['imei']; ?>');">

                <div class="card pd-1">
                    <div>
                        <p><b>IMEI NUMBER:</b> <?php echo $listofCustomers[$i]['imei']; ?></p>
                        <p><b>VEHICLE NUMBER:</b> <?php echo $listofCustomers[$i]['vehicleRegNumber']; ?></p>
                    </div>
                </div>
            </div>
            <?php
        }
      }
      ?>
    </div>
      </div>

    <!--SIDEBAR ENDS HERE-->
    <div class="main">
     <section class="content">
        <!--<div id="map" style="height: 400px;"></div>-->
        <div id="map" style="height: 540px;margin-top: -80px; position: relative; overflow: hidden;"></div>

     </section>
    </div>
     
	<script>
  

  const locations = [];
  <?php for ($i = 0; $i < count($listofCustomers); $i++) {
      if ($listofCustomers[$i]['latitude'] != '' && $listofCustomers[$i]['longitude'] != '' && (int)$listofCustomers[$i]['longitude'] != 0 && $listofCustomers[$i]['vtrackingId'] != '' && $listofCustomers[$i]['imei'] != '') { ?>
        var val = {
          vehid: <?php echo $listofCustomers[$i]['vtrackingId']; ?>,
          lat: <?php echo $listofCustomers[$i]['latitude']; ?>,
          lng: <?php echo $listofCustomers[$i]['longitude']; ?>,
          imei: <?php echo $listofCustomers[$i]['imei']; ?>
        };
        locations.push(val);
    <?php
      }}?>
      
   var map;
   var c=0;
//   function zoom_latlng(a,b, imei)
//   {
//     // alert("lat----lang"+a+"----"+b);
//      var c=1;
//      var lt=a;
//      var lang=b;
//      initMap1(lt,lang,c);
//   }

  function zoom_latlng(a,b,imei)
   {
    // alert("lat----lang"+a+"----"+b);
     var c=1;
     var lt=parseFloat(a);
     var lang=parseFloat(b);
     var imei = imei;
     console.log(lt);
     console.log(lang);
     console.log(imei);
     validateLatitude(lt)
     validateLongitude(lang) 
     if (validateLatitude(lt) && validateLongitude(lang) && lt != 12.345 && lang != 12.345) {
            initMap1(lt, lang, c,imei);
        } else {
            console.log('not valid')
            var formData = new FormData();
            formData.append("imei", imei),
            $.ajax({
                type: "POST",
                url: SITEURL + "admin/getValitLatLngByImie",
                data: formData,
                //use contentType, processData for sure.
                contentType: false,
                processData: false,
                beforeSend: function() {
                  
          
                },
                success: function(msg) {
                  data = msg.replace(/^\s+|\s+$/g, "");
                  data = JSON.parse(data);
                  var arrayLength = data.length;
                  if(arrayLength == 0){
                    swal({
                             title: "<bold>ERROR!</bold>",						
                             type: "error",	
                             html: true,
                             text: 'No data found for this Vehicle',
                        }, function (isConfirm) {
                            if(isConfirm)
                            {
                             }
                     });
                   }
                  var lt   = parseFloat(data[0].latitude);
                  var lang = parseFloat(data[0].longitude);
                  console.log(lt,lang);
                  initMap1(lt, lang, c,imei);

                
                }
                
              });
              
           

        }
    }
    
     function validateLatitude(latitude) {
      console.log('latitudel',latitude)
        const latitudePattern = /^[-]?((\d|[1-8]\d)(\.\d+)?|90(\.0+)?)$/;
        if (latitudePattern.test(latitude) && parseFloat(latitude) !== 0) {
           console.log("true")
            return true;
            
        }
        console.log("false")

        return false;
    }

    function validateLongitude(longitude) {
      console.log(longitude)
        const longitudePattern = /^[-]?((\d{1,2}|1[0-7]\d)(\.\d+)?|180(\.0+)?)$/;
        if (longitudePattern.test(longitude) && parseFloat(longitude) !== 0) {
            console.log("true")
            
          return true;

        }
        console.log("false")

        return false;
    }
    
    
    
//   function initMap() 
//   {
//     console.log(JSON.stringify(locations));
//     map = new google.maps.Map(document.getElementById("map"), {
//       zoom: 5,
//       center: { lat: 11.022323, lng: 76.937531},
//     });
//     var infoWin = new google.maps.InfoWindow();
//     const markers = locations.map((location, i) => {
//     var marker = new google.maps.Marker({
//       position: location, 
//       url: "<?php echo base_url().'portal/tracking?vehId='?>"+location.vehid+"&Sid="+location.imei+"&lat="+location.lat+"&lng="+location.lng,
//       });
    
//      google.maps.event.addListener(marker, 'click', (function(marker) {
//       return function() {
//         window.location.href = marker.url;
//       }
//      })(marker));

//       google.maps.event.addListener(marker, 'mouseover', function(evt) {
//       infoWin.setContent("IMEI NUMBER:"+JSON.stringify(location.imei)+"   "+"VEHICLE ID:"+JSON.stringify(location.vehid));
//       infoWin.open(map, marker);
//       })

//       return marker;
//     }); 

//     new MarkerClusterer(map, markers, {
//     imagePath:
//       "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
//     });

//   }

  function initMap() 
  {
    console.log(JSON.stringify(locations));
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 5,
      center: { lat: 20.747767, lng: 79.519043},
    });
    var infoWin = new google.maps.InfoWindow();
    const markers = locations.map((location, i) => {
    var marker = new google.maps.Marker({
      position: location, 
      url: "<?php echo base_url().'portal/tracking?vehId='?>"+location.vehid+"&Sid="+location.imei+"&lat="+location.lat+"&lng="+location.lng,
      });
    
     google.maps.event.addListener(marker, 'click', (function(marker) {
      return function() {
        window.location.href = marker.url;
      }
     })(marker));

      google.maps.event.addListener(marker, 'mouseover', function(evt) {
      infoWin.setContent("IMEI NUMBER:"+JSON.stringify(location.imei)+"   "+"VEHICLE ID:"+JSON.stringify(location.vehid));
      infoWin.open(map, marker);
      })

      return marker;
    }); 

    new MarkerClusterer(map, markers, {
    imagePath:
      "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
    });

  }
  
//   old code
//   function initMap1(lt,lang,c)
//   {
//     console.log(JSON.stringify(locations));
//     if(c==1)
//     {
//       map = new google.maps.Map(document.getElementById("map"), {
//         zoom: 20,
//         center: { lat: lt, lng: lang},
//       });
//     }
//     else
//     {
//       map = new google.maps.Map(document.getElementById("map"), {
//         zoom: 5,
//         center: { lat: 11.022323, lng: 76.937531},
//       });
//     }

//     var infoWin = new google.maps.InfoWindow();

//     const markers = locations.map((location, i) => {
//     var marker = new google.maps.Marker({
//       position: location, 
//       url: "<?php echo base_url().'portal/tracking?vehId='?>"+location.vehid+"&Sid="+location.imei+"&lat="+location.lat+"&lng="+location.lng,
//       });
    
//      google.maps.event.addListener(marker, 'click', (function(marker) {
//       return function() {
//         window.location.href = marker.url;
//       }
//      })(marker));

//       google.maps.event.addListener(marker, 'mouseover', function(evt) {
//       infoWin.setContent("IMEI NUMBER:"+JSON.stringify(location.imei)+"   "+"VEHICLE ID:"+JSON.stringify(location.vehid));
//       infoWin.open(map, marker);
//       })

//       return marker;
//     }); 

//     new MarkerClusterer(map, markers, {
//     imagePath:
//       "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
//     });

//   }

  function initMap1(lt,lang,c, imei)
  { 
      
      const indexToUpdate = locations.findIndex(location => location.imei == imei);
      console.log(locations.length);
      console.log("imei ",imei);
      for (let i = 0; i < locations.length; i++) {
        if (locations[i].imei == imei) {
          locations[i].lat = lt;
          locations[i].lng = lang;
          console.log(i)
          break;
        }
      }

  
    console.log(locations[indexToUpdate]);
    
    if(c==1)
    {
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 19,
        center: { lat: lt, lng: lang},
      });
    }
    else
    {
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 5,
        center: { lat: 11.1271, lng: 78.6569},
      });
    }

    var infoWin = new google.maps.InfoWindow();

    const markers = locations.map((location, i) => {
    var marker = new google.maps.Marker({
      position: location,
      url: "<?php echo base_url() . 'portal/tracking?vehId=' ?>"+location.vehid+"&Sid="+location.imei+"&lat="+location.lat+"&lng="+location.lng,
      });

     google.maps.event.addListener(marker, 'click', (function(marker) {
      return function() {
        window.location.href = marker.url;
      }
     })(marker));

      google.maps.event.addListener(marker, 'mouseover', function(evt) {
      infoWin.setContent("IMEI NUMBER:"+JSON.stringify(location.imei)+"   "+"VEHICLE ID:"+JSON.stringify(location.vehid));
      infoWin.open(map, marker);
      })

      return marker;
    });
    new MarkerClusterer(map, markers, {
    imagePath:
      "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
    });

    // $("#test").css("display", "block");
    // var mapDiv = document.getElementById('map').getElementsByTagName('div')[0];
    // mapDiv.appendChild(document.getElementById("test"));
  }
  
  // /portal/all_customer_vehicles
	</script>
   <?php $this->load->view('common/admin_login_css_js'); ?> 	
  </body>
</html>