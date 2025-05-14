<html>
<head>
<script src="<?php echo base_url(); ?>public/css/superadmin/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link href="<?php echo base_url(); ?>public/css/superadmin/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="<?php echo base_url(); ?>public/css/superadmin/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/css/superadmin/jquery-1.11.1.min.js" ></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/superadmin/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/superadmin/superadmin.css";>
 <script src="<?php echo base_url() ?>public/plugins/jquery-validation/jquery.validate.js"></script>
</head>
<body>
<div class="main">
    <script>
    var SITEURL="<?php echo base_url() ?>";
  </script>
    
    <div class="container">
<center>
<div class="middle">
      <div id="login">
        <form action="javascript:void(0);" id="sign_in" method="get">

          <fieldset class="clearfix">

            <p ><span class="fa fa-user"></span><input type="text"  Placeholder="Username" id="phone_number" required></p> <!-- JS because of IE support; better: placeholder="Username" -->
            <p><span class="fa fa-lock"></span><input type="password"  Placeholder="Password" id="password_value" required></p> <!-- JS because of IE support; better: placeholder="Password" -->
            
             <div>
                                <span style="width:48%; text-align:left;  display: inline-block;display: none;"><a class="small-text" href="#">Forgot
                                password?</a></span>
                                <span style="width:50%; text-align:right;  display: inline-block;"><input type="submit" value="Sign In"></span>
                            </div>

          </fieldset>
<div class="clearfix"></div>
        </form>

        <div class="clearfix"></div>

      </div> <!-- end login -->
      <div class="logo">LOGO
          
          <div class="clearfix"></div>
      </div>
      
      </div>
</center>
    </div>

</div>
  <script src="<?php echo base_url() ?>public/js/pages/function/subadmin_sign_in.js?t=<?php echo time(); ?>"></script>

</body>
</html>