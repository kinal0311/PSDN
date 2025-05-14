<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="<?php echo base_url(); ?>public/favicon.ico?v=1">
<title>Universal Tele Services</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?php echo base_url(); ?>public/template/css.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>public/template/default.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">
	
.banner{ background-image: url(<?php echo base_url(); ?>public/images/banner.png); background-position:center;} 
.title h2 a{
    font-weight: 400;
    color: #B10058;
    text-decoration: none;
}
</style>
</head>
<body>
<div id="wrapper">
	<div id="header-wrapper">
		<div id="header" class="container banner">
			<div id="logo">
				<h2><a href="#" >&nbsp; </a></h2>				
			</div>
		</div>
	</div>

	<div id="page" class="container">
		<div id="content" style=" text-align: center;">
		<style type="text/css">
		ul li {
    margin: 10px;
    padding: 0px;
}
		</style>
			<div class="title">
				<h2 style="    font-family: initial;"><a href='<?php echo base_url(); ?>'>Welcome to Speed Governor MIS!</a></h2>
				<span class="byline"></span> </div>
	<ul style=" list-style-type: none;width: 40%;
    margin-left: 25%;">
     
  		<li>
  					<address style="font-family: 'Chivo', sans-serif;">
              <?php echo $userinfo['user_info']; ?>
            </address>
      </li> 
       <li>Contact Person : <?php echo $userinfo['user_name']; ?></li>        
      <li>
        Contact No :  <?php echo $userinfo['user_phone']; ?>
      </li>
      <li>
        Email :  <a href="mailto:<?php echo $userinfo['user_email']; ?>" target="_top"><?php echo $userinfo['user_email']; ?></a>
      </li>
	</ul>
		</div>
	</div>
</div>

<footer class="footer">
  <div class="copyright" style="font-size: 13px;white-space: nowrap;-ms-text-overflow: ellipsis;-o-text-overflow: ellipsis;
    text-overflow: ellipsis;overflow: hidden;">
      Copyright© 2017 <a href="javascript:void(0);">Universal Tele Services</a>.
   </div>
</footer>
</body>
</html>
