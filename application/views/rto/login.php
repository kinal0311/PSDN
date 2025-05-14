<?php $this->load->view('common/admin_login_header'); ?>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">RTO Login</a>
            <small>Universal Tele Services</small>
        </div>
		<div class="alert alert-danger" id="erroralert" style="display:none;">
              <strong></strong>
        </div>
        <style>
        .dropdown-toggle{display: none;}
        </style>
        <div class="card">
            <div class="body">
              <form id="sign_in" method="POST" novalidate="novalidate">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-group form-floa">
                        <div class="form-line">
                            <select class="form-control show-tick" name="rto_number"  id="rto_number" data-live-search="true" required>
                                <option value="">--Select RTO--</option>
                                <?php
                                foreach ($rto_list as $key => $value) {
                                ?>
                                <option value="<?php echo $value['rto_no']; ?>"><?php echo $value['rto_number']; ?></option>
                                <?php
                                }
                                ?>
                             </select>
                        </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" id="rto_pwd" name="rto_pwd" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit" style="padding: 12px 12px;" >SIGN IN</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
	<script src="<?php echo base_url() ?>public/js/pages/function/rto_sign_in.js"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>
</body>
</html>