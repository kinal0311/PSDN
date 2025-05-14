<?php $this->load->view('common/admin_login_header'); ?>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Device Team Login</a>
            <small>PSDN Technology Pvt Ltd</small>
        </div>
		<div class="alert alert-danger" id="erroralert" style="display:none;">
              <strong></strong>
        </div>
        <div class="card">
            <div class="body">
                <form id="device_login" method="POST" novalidate="novalidate">
                    <div class="msg">Sign in to start your session</div>
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
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" style="padding: 12px 12px;" type="submit">SIGN IN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<script>
        $(document).ready(function(){
            $('#phone_number').val(window.localStorage.getItem('phone_number'));
            $('#password_value').val(window.localStorage.getItem('password_value'));            

            $('#device_login').validate({
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
                    window.localStorage.setItem('phone_number',"");
                    window.localStorage.setItem('password_value',"");
                    // Get Value
                    var phone_number=$('#phone_number').val();
                    var password_value=$('#password_value').val();
                    if($('#rememberme').prop('checked'))
                    {
                        window.localStorage.setItem('phone_number',phone_number);
                        window.localStorage.setItem('password_value',password_value);
                    }
                    var params={};
                    params.phone_number=phone_number;
                    params.password_value=password_value;
                    $('#erroralert').hide();
                    $.post(SITEURL+"device/verifyuser",params,function(data){
                        data = data.replace(/^\s+|\s+$/g, "");
                        data=JSON.parse(data);
                        if(data.error)
                        {
                            $('#erroralert').show();
                            $('#erroralert').find('strong').text(data.error);
                        }               
                        //Success Response
                        if(data.success)
                        {
                            if(data.redirect)
                            {
                                window.location.href=SITEURL+data.redirect;
                            }
                        }
                        
                    });

                }
            });
        })
    </script>
    <?php $this->load->view('common/admin_login_css_js'); ?>
</body>
</html>