<?php $this->load->view('common/admin_login_header'); ?>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Admin</a>
            <small>PSDN Technology Pvt Ltd</small>
        </div>
		<div class="alert alert-danger" id="erroralert" style="display:none;">
              <strong></strong>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" novalidate="novalidate">
                    <div class="msg">Sign in to start your session!</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="phone_number"   name="phone_number"  maxlength="12" placeholder="Phone Number" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" id="password_value" name="password_value" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" style="padding: 12px 12px;" type="submit">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6" style="display:none;">
                            <a href="sign-up.html">Register Now!</a>
                        </div>
                        <div class="col-xs-12 align-right">
                            <a href="<?php echo base_url() ?>admin/forgot_password">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> 
	<script src="<?php echo base_url() ?>public/js/pages/function/sign-in.js?t=<?php echo time(); ?>"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>
</body>
</html>
