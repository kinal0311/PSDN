<html>
<head><link href="<?php echo base_url(); ?>public/css/superadmin/dealerbootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="<?php echo base_url(); ?>public/css/superadmin/jquery-1.11.1.min.js"></script>
<script>
    var SITEURL="<?php echo base_url() ?>";
  </script>
    <script src="<?php echo base_url() ?>public/plugins/jquery-validation/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>public/css/superadmin/bootstrap.min.js"></script>
<link href="<?php echo base_url(); ?>public/css/superadmin/dealer.css" rel="stylesheet" id="bootstrap-css">
<!------ Include the above in your HEAD tag ---------->
</head>
<body>
<div class="container">
	<div class="row">
		
<!-- Mixins-->
<!-- Pen Title-->
<div class="pen-title">
  <h1>PSDN Dealer Login </h1>
</div>
<div class="container">
  <div class=""></div>
  <div class="card">
    <h1 class="title">Login</h1>
    <form id="sign_in" autocomplete="off" onsubmit="return false;">
      <div class="input-container">
        <input type="text" id="phone_number" value="" readonly="readonly" onfocus="javascript: this.removeAttribute('readonly')" required="required"/>
        <label for="Username">Username</label>
        <div class="bar"></div>
      </div>
      <div class="input-container">
        <input type="password" id="password_value" value="" readonly="readonly" onfocus="javascript: this.removeAttribute('readonly')" required="required"/>
        <label for="Password">Password</label>
        <div class="bar"></div>
      </div>
      <div class="button-container">
        <button><span>Go</span></button>
      </div>
      <div style="display: none;" class="footer"><a href="#">Forgot your password?</a></div>
    </form>
  </div>
 
</div>

	</div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> 
  <script src="<?php echo base_url() ?>public/js/pages/function/dealer_sign_in.js?t=<?php echo time(); ?>"></script>

</body>
</html>