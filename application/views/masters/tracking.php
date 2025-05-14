<?php $this->load->view('common/admin_login_header'); ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
 <script src="<?php echo base_url(); ?>public/js/frontend.js?id=<?php echo rand(); ?>"></script>
 
 <!--<script-->
 <!--     src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_F76FCq1xJtvasEC9OxRguRKHxfVJFXc&callback=initMap&libraries=&v=weekly"-->
 <!--     defer-->
 <!--   ></script>-->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx3BvK2E1sHk6jTJGF8ty7Brkh-nP4gd4&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />
 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	  <?php $this->load->view('common/dashboard_top_bar'); ?> 
    <?php $this->load->view('common/left_side_bar'); ?>
  <section class="content">
    <section class="content-header">
      <h1>
        Vehicle Tracking
      </h1>
    </section>
    <div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <p style="float: right;"><b>Vehicle Company Name / Brand Name:&nbsp;&nbsp;</b><span id="vendorName"></span></p>
        <p ><b>Product Name:&nbsp;&nbsp;</b><span id="productName"></span></p>
        <p style="float: right;"><b>Last Updated:&nbsp;&nbsp;</b><span id="lastupdatedTime"></span></p>
        <p ><b>IMEI Number:&nbsp;&nbsp;</b><span id="imei"></span></p>
        <p style="float: right;"><b>Last Updated:&nbsp;&nbsp;</b><span id="lastupdatedTime_ori"></span></p>
        <p ><b>Signal Strength:&nbsp;&nbsp;</b><span id="signalStrength"></span></p>
        <p style="float: right;"><b>Vehicle Number:&nbsp;&nbsp;</b><span id="vehicleRegNumber"></span></p>
        <p ><b>Vehicle Speed:&nbsp;&nbsp;</b><span id="speed"></span></p>
        <p  style="float: right;"><b>latitude/longitude:&nbsp;&nbsp;</b><span id="latitude"></span> / <span id="longitude"></span></p>
        <p><b>SIM Number:&nbsp;&nbsp;</b><span id="simNumber"></span></p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div id="map" style="height: 400px;"></div>
      </div>
    </div>
  </section>
    <script>
    var base_url='<?php echo base_url(); ?>';
    function initMap() 
    {
      var myLatlng = new google.maps.LatLng(<?php echo $_GET["lat"] ?>,<?php echo $_GET["lng"] ?>);
      var mapOptions = {
         zoom: 10,
         center: myLatlng
      }
      var map = new google.maps.Map(document.getElementById("map"), mapOptions);
      var marker = new google.maps.Marker({
       position: myLatlng,
       title:"Hello World!"
      });
// To add the marker to the map, call setMap();
      marker.setMap(map);
    }
      
      var vehId='<?php echo $_GET["vehId"] ?>';
      var SOCKET_URL='<?php echo SOCKET_URL.'?vehId='.$_GET["Sid"]; ?>';
      view_tracking();
    </script>
    <script src='<?php echo SOCKET_URL; ?>/socket.io/socket.io.js'></script>
    <script src="<?php echo base_url(); ?>public/js/tracking.js"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>