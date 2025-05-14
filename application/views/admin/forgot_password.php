<?php $this->load->view('common/admin_login_header'); ?>
<body class="fp-page">
    <div class="fp-box">
        <div class="logo">
            <a href="javascript:void(0);">Admin</a>
            <small>PSDN Technology Pvt Ltd</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="forgot_password" method="POST">
                    <div class="msg">
                        Enter your email address that you used to register. We'll send you an email with your username and a
                        link to reset your password.
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required autofocus>
                        </div>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">RESET MY PASSWORD</button>

                    <div class="row m-t-20 m-b--5 align-center">
                        <a href="<?php echo base_url() ?>">Sign In!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

  <?php $this->load->view('common/admin_login_css_js'); ?>
	<script src="<?php echo base_url() ?>public/js/admin.js"></script>
</body>

<script>
 $('#forgot_password').validate({
		
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        }, 
		submitHandler: function(form) {			
			
			var params={};
			params.email=$('#email').val();
			$.post(SITEURL+"admin/reset_password",params,function(data){
				data=JSON.parse(data);
				if(data.error)
				{
					showBasicMessage(data.error);
				}				
				//Success Response
				if(data.success)
				{
					showBasicMessage(data.message);
				}
				
			});

		}
    });
</script>

</html>