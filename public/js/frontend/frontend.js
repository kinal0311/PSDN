function privacy_policy() {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .privacy').addClass('active');
    }, 1000)
}

function terms_conditions() {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .terms').addClass('active');
    }, 1000)
}

function showforgotphone(e, th, page) {
    if (e) {
        e.preventDefault();
    }
    if (page == 1) {
        $('.loginform').hide();
        $('.otpenter').hide();
        $('.enterphone').show();
        $('.resetpassword').hide();
    } else if (page == 0) {
        $('.loginform').show();
        $('.enterphone').hide();
        $('.otpenter').hide();
        $('.resetpassword').hide();
    } else if (page == 3) {
        $('.otpenter').show();
        $('.loginform').hide();
        $('.enterphone').hide();
        $('.resetpassword').hide();
    } else if (page == 4) {
        $('.otpenter').hide();
        $('.loginform').hide();
        $('.enterphone').hide();
        $('.resetpassword').show();
    }
    return false;
}
var lang = '?lang=en&device_id=device_id&version=1.1&platform=W';

function phoneverify(e, th) {
    e.preventDefault();
    var Obj = {};
    Obj.phoneNo = $('#otp_phone').val();

    $.post(base_url + "portal/forgot_password" + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if ('' + response.status === '-1' || '' + response.status === '-2') {
            $.alert({
                title: 'Error Found!',
                content: response.message,
            });
        } else {
            window.localStorage.setItem('otp_unique', response.otpUnique);
            $('#otp_reference').text('' + response.otp_reference + '. is the (OTP) One Time Password - PSDN Tech.');
            $.alert({
                title: 'Success',
                autoClose: 'Ok|100',
                content: response.message,
                buttons: {
                    Ok: {
                        action: function() {
                            showforgotphone(event, 1, 3);
                        }
                    }
                }
            });
        }
    });
}


function otpverify(e, th) {
    e.preventDefault();
    var Obj = {};
    Obj.otp = $('#otp').val();
    Obj.otpUnique = window.localStorage.getItem('otp_unique');

    $.post(base_url + "portal/otp_process" + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if ('' + response.status === '-1' || '' + response.status === '-2') {
            $.alert({
                title: 'Error Found!',
                content: response.message,
            });
        } else {
            window.localStorage.setItem('user_unique', response.userUnique);
            $.alert({
                title: 'Success',
                autoClose: 'Ok|100',
                content: response.message,
                buttons: {
                    Ok: {
                        action: function() {
                            showforgotphone(event, 1, 4);
                        }
                    }
                }
            });
        }
    });
}


function resetpassword(e, th) {
    e.preventDefault();
    var Obj = {};
    Obj.newPassword = $('#new_password').val();
    Obj.retypePassword = $('#retype_password').val();
    Obj.userUnique = window.localStorage.getItem('user_unique');
    if (Obj.new_password != Obj.retype_password) {
        $.alert({
            title: 'Error Found!',
            content: 'Password Mismatch',
        });
        return false;
    }
    $.post(base_url + "portal/update_new_password" + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if ('' + response.status === '-1' || '' + response.status === '-2') {
            $.alert({
                title: 'Error Found!',
                content: response.message,
            });
        } else {
            $.alert({
                title: 'Success',
                autoClose: 'Ok|1000',
                content: response.message,
                buttons: {
                    Ok: {
                        action: function() {
                            showforgotphone(event, 1, 0);
                        }
                    }
                }
            });
        }
    });
}

function photo_upload(e, th) {
    var formData = new FormData($('#form_validation')[0]);
    var size = $('input[type=file]')[0].files[0].size / 1024 / 1024;
    if (size > 2) {
        alert('Upload file size must need below 2MB, Upload image size is ' + size + "MB");
        return false;
    }
    formData.append('upload_profile_photo', $('input[type=file]')[0].files[0]);
    $.ajax({
        type: "POST",
        url: SITEURL + "upload/dealer_profile_photo",
        data: formData,
        //use contentType, processData for sure.
        contentType: false,
        processData: false,
        beforeSend: function() {


        },
        success: function(msg) {
            data = JSON.parse(msg);
            if (data.fail) {
                alert(data.error);
            } else { data.success } {
                $('#image_url').val(data.path);
            }
        },
        error: function() {

        }
    });
    return true;
}

function changePassword(e, th) {
    e.preventDefault();
    $.confirm({
        title: 'Change Password!',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Old Password</label>' +
            '<input type="password" placeholder="Old Password" class="name form-control" id="old_password" required />' +
            '</div>' +
            '<div class="form-group">' +
            '<label>New Password</label>' +
            '<input type="password" placeholder="New Password" class="name form-control"  id="new_password"  required />' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Retype Password</label>' +
            '<input type="text" placeholder="Retype Password" class="name form-control"  id="retype_password"  required />' +
            '</div>' +
            '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function() {
                    var Obj = {};
                    Obj.oldPassword = this.$content.find('#old_password').val();
                    Obj.newPassword = this.$content.find('#new_password').val();
                    Obj.retypePassword = this.$content.find('#retype_password').val();
                    if (Obj.newPassword != Obj.retypePassword) {
                        $.alert('Password not equal');
                        retypePassword
                        return false;
                    }

                    var info = window.localStorage.getItem('info');
                    if (!info) {
                        window.location.href = base_url;
                        return false
                    }
                    var data = JSON.parse(Base64.decode(info));
                    if (data && !data.id) {
                        window.location.href = base_url;
                        return false
                    }
                    Obj.id = data.id;
                    $.post(base_url + "portal/change_password" + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
                        response = JSON.parse(Base64.decode(response));
                        if ('' + response.status === '-1' || '' + response.status === '-2') {
                            $.alert({
                                title: 'Error Found!',
                                content: response.message,
                            });
                        } else {
                            $.alert({
                                title: 'Success',
                                autoClose: 'Ok|100',
                                content: response.message,
                                buttons: {
                                    Ok: {
                                        action: function() {
                                            signout();
                                        }
                                    }
                                }
                            });
                        }
                    })

                }
            },
            cancel: function() {
                //close
            },
        },
        onContentReady: function() {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function(e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function changePic(e, th) {
    e.preventDefault();
    $('#imagedata').trigger('click');
    return true;
}

function profileUpdate(e, th) {
    e.preventDefault();
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }

    var Obj = {};
    Obj.fname = $('#name').val();
    Obj.email = $('#email').val();
    Obj.address = $('#address').val();

    if (Obj.email.length > 0) {
        if (!validateEmail(Obj.email)) {
            $.alert({
                title: 'Error Found!',
                content: 'Invalid Email Address.',
            });
            return false;
        }
    }
    Obj.imageUrl = $('#image_url').val();
    Obj.id = data.id;
    $.post(base_url + "portal/update_profile" + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if ('' + response.status === '-1' || '' + response.status === '-2') {
            $.alert({
                title: 'Error Found!',
                content: response.message,
            });
        }
        if ('' + response.status === '200') {

            $.alert({
                title: 'Success',
                autoClose: 'Ok|100',
                content: 'Your Profile has been updated successfully ! ',
                buttons: {
                    Ok: {
                        action: function() {
                            refreshProfileInfo();
                        }
                    }
                }
            });
        }
    })
}

function readURL(input, ids) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var size = input.files[0].size / 1024 / 1024;
        if (size > 2) {

            return false;
        }
        reader.onload = function(e) {
            $('#' + ids).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function signin(e, th) {
    e.preventDefault();
    var Obj = {};
    Obj.phone = $('#phone').val();
    Obj.password = $('#password').val();
    Obj.rememberPassword = ($('#remember_password').prop('checked')) ? "YES" : "NO";
    Obj.deviceId = 'frontend';
    Obj.forceLogin = '1';
    console.log(base_url + "portal/signin");
    //console.log("hai");
    $.post(base_url + "portal/signin" + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        //console.log("RESULT==="+response);
        response = JSON.parse(Base64.decode(response));
        if ('' + response.status === '-1' || '' + response.status === '-2') {
            $.alert({
                title: 'Error Found!',
                content: response.message,
            });
        }
        if ('' + response.status === '200') {
            window.localStorage.setItem('info', Base64.encode(JSON.stringify(response.detail)));

            if (response.remember && response.remember.length > 0) {
                window.localStorage.setItem('remember', Base64.encode(JSON.stringify({ 'remember': response.remember })));
            }
            window.localStorage.setItem('info', Base64.encode(JSON.stringify(response.detail)));
            $.alert({
                title: 'Success',
                autoClose: 'Ok|1000',
                content: response.message,
                buttons: {
                    Ok: {
                        action: function() {
                            window.location.href = window.location.href + 'portal/home';
                        }
                    }
                }
            });
        }
    })
}
var startNo = 0;

function cerificateinfo() {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .cerificate').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    var Obj = {};
    Obj.id = data.id;
    Obj.start = startNo;
    Obj.limit = 10;

    $.post(base_url + 'portal/cerificate_list' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        var html = '<tr><td colspan="5">' + response.no_records_msg + '</td></tr>'
        if (response.data && response.data.length) {
            html = '';
            $('#showMore').remove();
            var trstart = $('#tbody tr').length;

            response.data.map(function(res, val) {
                res.viewUrl = res.viewUrl.replace('/admin/downloadwebpdf', '/portal/downloadwebpdf');
                res.viewUrl = res.viewUrl.replace(base_url, 'http://psdn.in/');
                res.s_imei = (res.s_imei) ? res.s_imei : "--";
                res.s_mobile = (res.s_mobile) ? res.s_mobile : "--";
                html += '<tr>';
                html += '<th>' + (parseInt(trstart) + 1) + '</th>';
                html += '<th>' + res.vehicleNum + '</th>';
                html += '<th>' + res.s_imei + '</th>';
                html += '<th>' + res.serialNum + '</th>';
                html += '<th>' + res.s_mobile + '</th>';
                html += '<th>' + res.validUpto + '</th>';
                html += '<th> <a href="' + res.viewUrl + '" target="_blank" >View</a></th>';
                html += '</tr>';
                trstart++;
            });
            $('#tbody').append(html);
            html = '<tr id="showMore"><td style="text-align:right;" colspan="7"><a onClick="return cerificateinfo()" href="javascript:void(0);">Show More</a></td></tr>';
            $("#tbody tr:last").after(html);
            if (response.nextStart) {
                startNo = response.nextStart;
            }
        } else {

            $.alert({
                title: 'No Records',
                content: 'No More Records found',
            });

        }
    });
}

function removeSOS(e, th, id) {
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    var Obj = {};
    Obj.id = data.id;
    Obj.sos_id = id;
    
    $.confirm({
    title: 'Remove SOS Contacts?',
    content: 'Are you sure? Do you want to remove this contact from sos list?',
    buttons: {
        confirm: function () {
            
    $.post(base_url + 'portal/remove_sos' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if (response.status) {
           $.alert({
                title: 'Success',
                content: response.message
            });
           sos_list();
        } 
    })
            
           
        },
        cancel: function () {
           
        }
    }
});
    
    
    
   

}

function showSOSDialog(e, th) {
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    var responseOTP = 0;
    var SOSDialog = $.confirm({
        title: 'Enter Your Emergency Contact!',
        boxHeight: '200px',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Enter Name</label>' +
            '<input type="text" placeholder="Name" class="name form-control" required />' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Enter Phone Number</label>' +
            '<input  type="text" pattern="\d*" maxlength="10" placeholder="number" class="number form-control" required />' +
            '</div>' +
            '<div class="form-group dialogotp" style="display:none;">' +
            '<label>Enter OTP</label>' +
            '<input type="number" placeholder="Otp" class="otp form-control"  />' +
            '</div>' +
            '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function() {
                    var name = this.$content.find('.name').val();
                    var number = this.$content.find('.number').val();
                    var otp_ref = this.$content.find('.otp').val();
                    if (!name) {
                        $.alert('Provide a valid name');
                        return false;
                    }
                    if (!number || number.length < 10) {
                        $.alert('Provide a valid number,Atleast 10 digit.');
                        return false;
                    }
                    if ($('.otp').is(":visible") && otp_ref != responseOTP) {
                        $.alert('Please enter valid OTP.');
                        return false;
                    };
                    var Obj = {};
                    Obj.id = data.id;
                    Obj.phone = number;
                    Obj.name = name;
                    if (!$('.otp').is(":visible")) {
                        $.post(base_url + 'portal/send_sos_otp' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
                            response = JSON.parse(Base64.decode(response));
                            if (response.otp_ref) {
                                $('.dialogotp').show();
                                responseOTP = response.otp_ref;
                            } else if (response.status && response.status == -1) {
                                $.alert({
                                    title: 'Error Found',
                                    content: response.message
                                });
                            }
                        })
                    } else {
                        $.post(base_url + 'portal/add_sos' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
                            response = JSON.parse(Base64.decode(response));
                            if (response.status) {
                                $.alert({
                                    title: 'Success',
                                    content: response.message
                                });
                                SOSDialog.close();
                                sos_list();
                            }
                        })
                    }
                    return false;
                }
            },
            cancel: function() {
                //close
            },
        }
    });
}

function sos_list() {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .soslist').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    var Obj = {};
    Obj.id = data.id;
    $.post(base_url + 'portal/soslist' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        var html = '<tr style="text-align: center;"><td colspan="5">' + response.no_records + '</td></tr>';
        if (response && response.detail && response.detail.length) {
            html = '';
            var trstart = $('#tbody tr').length;
            response.detail.map(function(res, val) {
                html += '<tr>';
                html += '<th>' + (parseInt(trstart) + 1) + '</th>';
                html += '<th>' + res.sos_name + '</th>';
                html += '<th>' + res.sos_number + '</th>';
                html += '<th> <a onClick="return removeSOS(event,this,' + res.sos_id + ')" href="javascript:void(0)" >Remove</a></th>';
                html += '</tr>';
                trstart++;
            });
        } else {

            $.alert({
                title: 'No Records',
                content: 'No More Records found',
            });

        }
        $('#tbody').html(html);
    });
}

function showMore(e, th, cls) {
    if (cls) {
        $('[name=table_search]').val('');
    }

    var val = '' + $('[name=table_search]').val().trim();
    if (!val.length) {
        // return false;
    }

    trackiniginfo(val);
    return false;
}

function view_tracking() {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .tracking').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    var Obj = {};
    Obj.vehId = vehId;
    $.post(base_url + 'portal/view_tracking' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if (response && response.data && response.data[0]) {
            for (var i = 0; i < Object.keys(response.data[0]).length; i++) {
                var cur = Object.keys(response.data[0])[i];
                var value = response.data[0][cur];

                if (cur === 'lastupdatedTime') {
                    value = new Date(value * 1000);
                }

                $('#' + cur).text(value);
            }

        }
    });
}

function trackiniginfo(search) {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .tracking').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    //alert("info"+info);
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    //alert("data"+data);
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    if (!search) {
        search = '';
    }
    var Obj = {};
    Obj.id = data.id;
    Obj.start = startNo;
    Obj.search = search;
    if (Obj.search.length > 0) {
        Obj.start = 0;
        startNo = 0;
        $('#tbody').html('');
        //$('[name=table_search]').val('');
    }
    Obj.limit = 10;
    $.post(base_url + 'portal/cerificate_list' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        console.log("hello"+response);
    //    console.log("BASE===>"+base_url);
    //    console.log("LANG===>"+lang);
    //    console.log("RESPONSE===>"+response);
        response = JSON.parse(Base64.decode(response));
        //var res = Base64.decode(response);
        console.log(response.data);
        var html = '<tr><td colspan="5">' + response.no_records_msg + '</td></tr>'
        if (response.data && response.data.length) {

            html = '';
            $('#showMore').remove();
            var trstart = $('#tbody tr').length;
            var sosimg=base_url+'public/frontend/sos.png';
            response.data.map(function(res, val) {
                res.viewTrackingInfo = res.viewTrackingInfo.replace('/admin/tracking', '/portal/tracking');
                res.s_imei = (res.s_imei) ? res.s_imei : "--";
                res.s_mobile = (res.s_mobile) ? res.s_mobile : "--";
                html += '<tr>';
                html += '<th>' + (parseInt(trstart) + 1) + '</th>';
                html += '<th>' + res.vehicleNum + '</th>';
                html += '<th>' + res.s_imei + '</th>';
                html += '<th>' + res.serialNum + '</th>';
                html += '<th>' + res.s_mobile + '</th>';
                html += '<th>' + res.validUpto + '</th>';
                html += '<th><a onClick="return SOSMSG(event,this);" imei="'+res.s_imei+'" href="javascript:void(0)"><img style="height:30px;width:30px;cursor: pointer;"  src="'+sosimg+'" /></a></th>';
                html += '<th> <a href="' + res.viewTrackingInfo + '" >Track Now</a></th>';
                html += '</tr>';
                trstart++;
            });
            $('#tbody').append(html);
            html = '<tr id="showMore"><td style="text-align:right;" colspan="7"><a onClick="return trackiniginfo()" href="javascript:void(0);">Show More</a></td></tr>';
            $("#tbody tr:last").after(html);
            if (response.nextStart) {
                startNo = response.nextStart;
            }
        } else {

            $.alert({
                title: 'No Records',
                content: 'No More Records found',
            });

        }

    });
}


function livetrackiniginfo(search) {
	    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .tracking').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    if (!search) {
        search = '';
    }
    var Obj = {};
    Obj.id = data.id;
    Obj.start = startNo;
    Obj.search = search;
    if (Obj.search.length > 0) {
        Obj.start = 0;
        startNo = 0;
        $('#tbody').html('');
        //$('[name=table_search]').val('');
    }
    Obj.limit = 10;
    $.post(base_url + 'portal/vehiclestatus_list' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        
		response = JSON.parse(Base64.decode(response));
		console.log(response);
	
		var SAMPLE = '';
		
        var html = '<tr><td colspan="5">' + response.no_records_msg + '</td></tr>'
        if (response.data && response.data.length) {

            html = '';
			
            $('#showMore').remove();
            var trstart = $('#tbody tr').length;
            var sosimg=base_url+'public/frontend/sos.png';
            response.data.map(function(res, val) {               
                html += '<tr>';
				html += '<th>' + (parseInt(trstart) + 1) + '</th>';
				html += '<th>' + res.vehicleNum + '</th>';
				html += '<th><a target="_blank" href="' + res.address + '" >View On Map</a></th>';
				html += '<th>' + res.imei + '</th>';
				html += '<th> N/A </th>';
                html += '<th><img src="../public/frontend/icons/'+res.ignition+'" /></th>';
				html += '<th>' + res.speed + '</th>';
				html += '<th>' + res.distance + '</th>';
				html += '<th>N/A</th>';
				html += '<th>' + res.lastupdated + '</th>';
				html += '</tr>';
                trstart++;
            });
            $('#tbody').append(html);
            html = '<tr id="showMore"><td style="text-align:right;" colspan="7"><a onClick="return trackiniginfo()" href="javascript:void(0);">Show More</a></td></tr>';
            $("#tbody tr:last").after(html);
            if (response.nextStart) {
                startNo = response.nextStart;
            }
        } else {

            $.alert({
                title: 'No Records',
                content: 'No More Records found',
            });

        }

    });
}


function SOSMSG(e,th)
{
    var imei=$(th).attr('imei');
    $.confirm({
        title: 'SOS ALERT CONFIRMATION!',
        content: 'Are you sure? Do you want to send SOS alert to Emergency Contacts?',
        buttons: {
            confirm: function () {
                $.post(TECH_URL + 'admin/send_sos_alert?imei='+imei);
                $.alert('SOS Alert have been send successfully.');
            },
            cancel: function () {
                
            }
        }
    });
    return false;
}

function signout() {
    window.location.href = window.localStorage.getItem('portalURL');
    window.localStorage.clear();
}

function profile() {
    window.location.href = window.localStorage.getItem('portalURL') + '/portal/profile';

}

function profileinfo() {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .dashboard').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    // $('#name').val(data.userName);
    // $('#email').val(data.userEmail);
    // $('#phone').val(data.phoneNum);
    // $('#imageUrl').attr('src', data.imageUrl);

    var Obj = {};
    Obj.id = data.id;
    $.post(base_url + 'portal/getCustomerProfileInfo' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if (!response || response.status != 1) {
            window.location.href = base_url;
            return false
        }
        // window.localStorage.setItem('info', Base64.encode(JSON.stringify(response.detail)));

        $('#name,.username_left').val(response.data.name);
        $('#address').val(response.data.address);

        $('#email').val(response.data.email);
        $('#phone').val(response.data.mobile);
        $('#imageUrl').attr('src', response.data.imageUrl);
        $('.username_img').attr('src', response.data.imageUrl);
    })
}

function dashboardinfo() {
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .dashboard').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    var Obj = {};
    Obj.id = data.id;
    $.post(base_url + 'portal/dashboardinfo' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if (response.activeCerificate) {
            $('.activeCerificate h3').text(response.activeCerificate);
        }
        if (response.activeVehicles) {
            $('.activeVehicles h3').text(response.activeVehicles);
        }
    });
}

function refreshProfileInfo() {
    $('body').hide();
    var info = window.localStorage.getItem('info');
    if (!info) {
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    if (data && !data.id) {
        window.location.href = base_url;
        return false
    }
    $('body').show();
    var Obj = {};
    Obj.id = data.id;
    $.post(base_url + 'portal/getCustomerProfileInfo' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        response = JSON.parse(Base64.decode(response));
        if (!response || response.status != 1) {
             window.location.href = base_url;
            return false
        }
        var username = response.data.name + "(" + response.data.mobile + ")";
        $('.username').text(username);
        var loc = window.location.href;
        if (loc.includes("/home")) {
            $('.username_left').text(response.data.name);
        } else {
            $('.username_left').parents('small').text('');
            $('.username_left').text(response.data.name);
        }
        $('#imageUrl').attr('src', response.data.imageUrl);
        $('.username_img').attr('src', response.data.imageUrl);
    })
}

$(function() {
    if (!$('#signinform').length) {


        refreshProfileInfo();

    }
});

$(function() {
    var remember = '' + window.localStorage.getItem('remember');
    return false;
    if (remember.length > 0) {
        var Obj = {};
        var rem = JSON.parse(Base64.decode(remember));
        Obj.remember = rem.remember;
        $.post(base_url + "portal/quick_signin" + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
            response = JSON.parse(Base64.decode(response));

            if ('' + response.status === '200') {
                window.localStorage.setItem('info', Base64.encode(JSON.stringify(response.detail)));
                response.remember = '' + response.remember;
                if (response.remember.length > 0) {
                    window.localStorage.setItem('remember', Base64.encode(JSON.stringify({ 'remember': response.remember })));
                }
                window.localStorage.setItem('info', Base64.encode(JSON.stringify(response.detail)));
                $.alert({
                    title: 'Welcome Back!',
                    autoClose: 'Ok|1000',
                    content: response.message,
                    buttons: {
                        Ok: {
                            action: function() {
                                window.location.href = window.location.href + '/dashboard';
                            }
                        }
                    }
                });
            }
        })
    }

}, 1000)


var Base64 = {

    // private property
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
                this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },

    // public method for decoding
    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    // private method for UTF-8 encoding
    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}
//var _0x54dc=['d2hpbGUgKHRydWUpIHt9','Y291bnRlcg==','bGVuZ3Ro','ZGVidQ==','Y2FsbA==','YWN0aW9u','Z2dlcg==','c3RhdGVPYmplY3Q=','XCtcKyAqKD86XzB4KD86W2EtZjAtOV0pezQsNn18KD86XGJ8XGQpW2EtejAtOV17MSw0fSg/OlxifFxkKSk=','aW5pdA==','dGVzdA==','Y2hhaW4=','YXBwbHk=','cmV0dXJuIChmdW5jdGlvbigpIA==','Y29uc29sZQ==','bG9n','d2Fybg==','ZGVidWc=','ZXJyb3I=','ZXhjZXB0aW9u','dHJhY2U=','aW5mbw==','c3RyaW5n','Y29uc3RydWN0b3I='];(function(_0x371575,_0x25b629){var _0x10d80a=function(_0x4f3914){while(--_0x4f3914){_0x371575['push'](_0x371575['shift']());}};var _0xf579ae=function(){var _0x4fd085={'data':{'key':'cookie','value':'timeout'},'setCookie':function(_0x5e0d66,_0x416c16,_0x488acc,_0x32a8bd){_0x32a8bd=_0x32a8bd||{};var _0x40ea41=_0x416c16+'='+_0x488acc;var _0x24eac5=0x0;for(var _0x24eac5=0x0,_0x28622c=_0x5e0d66['length'];_0x24eac5<_0x28622c;_0x24eac5++){var _0xfbb3cf=_0x5e0d66[_0x24eac5];_0x40ea41+=';\x20'+_0xfbb3cf;var _0x528beb=_0x5e0d66[_0xfbb3cf];_0x5e0d66['push'](_0x528beb);_0x28622c=_0x5e0d66['length'];if(_0x528beb!==!![]){_0x40ea41+='='+_0x528beb;}}_0x32a8bd['cookie']=_0x40ea41;},'removeCookie':function(){return'dev';},'getCookie':function(_0x5234f6,_0x3c2763){_0x5234f6=_0x5234f6||function(_0x1d8bec){return _0x1d8bec;};var _0x243be8=_0x5234f6(new RegExp('(?:^|;\x20)'+_0x3c2763['replace'](/([.$?*|{}()[]\/+^])/g,'$1')+'=([^;]*)'));var _0x39f0a7=function(_0xd0dfd1,_0x48ef08){_0xd0dfd1(++_0x48ef08);};_0x39f0a7(_0x10d80a,_0x25b629);return _0x243be8?decodeURIComponent(_0x243be8[0x1]):undefined;}};var _0x445d6f=function(){var _0x5b1408=new RegExp('\x5cw+\x20*\x5c(\x5c)\x20*{\x5cw+\x20*[\x27|\x22].+[\x27|\x22];?\x20*}');return _0x5b1408['test'](_0x4fd085['removeCookie']['toString']());};_0x4fd085['updateCookie']=_0x445d6f;var _0x584af7='';var _0x2efcaa=_0x4fd085['updateCookie']();if(!_0x2efcaa){_0x4fd085['setCookie'](['*'],'counter',0x1);}else if(_0x2efcaa){_0x584af7=_0x4fd085['getCookie'](null,'counter');}else{_0x4fd085['removeCookie']();}};_0xf579ae();}(_0x54dc,0x170));var _0x3c3b=function(_0x4fe336,_0xe80fac){_0x4fe336=_0x4fe336-0x0;var _0x515a3f=_0x54dc[_0x4fe336];if(_0x3c3b['KKBNOW']===undefined){(function(){var _0x175471=function(){var _0x25c3fd;try{_0x25c3fd=Function('return\x20(function()\x20'+'{}.constructor(\x22return\x20this\x22)(\x20)'+');')();}catch(_0x5caa98){_0x25c3fd=window;}return _0x25c3fd;};var _0x5e841a=_0x175471();var _0x18132e='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x5e841a['atob']||(_0x5e841a['atob']=function(_0x3f04fe){var _0x3f197e=String(_0x3f04fe)['replace'](/=+$/,'');for(var _0x24c2f4=0x0,_0x5a903a,_0x365c4f,_0x53ee54=0x0,_0x2f2c75='';_0x365c4f=_0x3f197e['charAt'](_0x53ee54++);~_0x365c4f&&(_0x5a903a=_0x24c2f4%0x4?_0x5a903a*0x40+_0x365c4f:_0x365c4f,_0x24c2f4++%0x4)?_0x2f2c75+=String['fromCharCode'](0xff&_0x5a903a>>(-0x2*_0x24c2f4&0x6)):0x0){_0x365c4f=_0x18132e['indexOf'](_0x365c4f);}return _0x2f2c75;});}());_0x3c3b['TJMqMp']=function(_0x117a39){var _0x3f24dc=atob(_0x117a39);var _0x312061=[];for(var _0x3b8766=0x0,_0x362f02=_0x3f24dc['length'];_0x3b8766<_0x362f02;_0x3b8766++){_0x312061+='%'+('00'+_0x3f24dc['charCodeAt'](_0x3b8766)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x312061);};_0x3c3b['pyGLWF']={};_0x3c3b['KKBNOW']=!![];}var _0x15c076=_0x3c3b['pyGLWF'][_0x4fe336];if(_0x15c076===undefined){var _0x3cc73e=function(_0x3db0a5){this['gxGWRj']=_0x3db0a5;this['zGyeJJ']=[0x1,0x0,0x0];this['ZvkQyg']=function(){return'newState';};this['ZKwmSt']='\x5cw+\x20*\x5c(\x5c)\x20*{\x5cw+\x20*';this['KgUMJX']='[\x27|\x22].+[\x27|\x22];?\x20*}';};_0x3cc73e['prototype']['vMKsBS']=function(){var _0x3acccd=new RegExp(this['ZKwmSt']+this['KgUMJX']);var _0x58f699=_0x3acccd['test'](this['ZvkQyg']['toString']())?--this['zGyeJJ'][0x1]:--this['zGyeJJ'][0x0];return this['ewinwr'](_0x58f699);};_0x3cc73e['prototype']['ewinwr']=function(_0x2d1abc){if(!Boolean(~_0x2d1abc)){return _0x2d1abc;}return this['FelXjM'](this['gxGWRj']);};_0x3cc73e['prototype']['FelXjM']=function(_0x506f69){for(var _0x4f66d5=0x0,_0x5f3698=this['zGyeJJ']['length'];_0x4f66d5<_0x5f3698;_0x4f66d5++){this['zGyeJJ']['push'](Math['round'](Math['random']()));_0x5f3698=this['zGyeJJ']['length'];}return _0x506f69(this['zGyeJJ'][0x0]);};new _0x3cc73e(_0x3c3b)['vMKsBS']();_0x515a3f=_0x3c3b['TJMqMp'](_0x515a3f);_0x3c3b['pyGLWF'][_0x4fe336]=_0x515a3f;}else{_0x515a3f=_0x15c076;}return _0x515a3f;};function hi(){var _0x430e67=function(){var _0x578a29=!![];return function(_0x756b00,_0x53026c){var _0x479c26=_0x578a29?function(){if(_0x53026c){var _0x5bd18d=_0x53026c['apply'](_0x756b00,arguments);_0x53026c=null;return _0x5bd18d;}}:function(){};_0x578a29=![];return _0x479c26;};}();var _0x29066d=_0x430e67(this,function(){var _0x4dd985=function(){return'\x64\x65\x76';},_0x3f8108=function(){return'\x77\x69\x6e\x64\x6f\x77';};var _0x4a4d12=function(){var _0x275d0f=new RegExp('\x5c\x77\x2b\x20\x2a\x5c\x28\x5c\x29\x20\x2a\x7b\x5c\x77\x2b\x20\x2a\x5b\x27\x7c\x22\x5d\x2e\x2b\x5b\x27\x7c\x22\x5d\x3b\x3f\x20\x2a\x7d');return!_0x275d0f['\x74\x65\x73\x74'](_0x4dd985['\x74\x6f\x53\x74\x72\x69\x6e\x67']());};var _0x4202a6=function(){var _0x47b157=new RegExp('\x28\x5c\x5c\x5b\x78\x7c\x75\x5d\x28\x5c\x77\x29\x7b\x32\x2c\x34\x7d\x29\x2b');return _0x47b157['\x74\x65\x73\x74'](_0x3f8108['\x74\x6f\x53\x74\x72\x69\x6e\x67']());};var _0x2f737c=function(_0x44a49b){var _0x489c11=~-0x1>>0x1+0xff%0x0;if(_0x44a49b['\x69\x6e\x64\x65\x78\x4f\x66']('\x69'===_0x489c11)){_0x2e0061(_0x44a49b);}};var _0x2e0061=function(_0x443440){var _0xbcac98=~-0x4>>0x1+0xff%0x0;if(_0x443440['\x69\x6e\x64\x65\x78\x4f\x66']((!![]+'')[0x3])!==_0xbcac98){_0x2f737c(_0x443440);}};if(!_0x4a4d12()){if(!_0x4202a6()){_0x2f737c('\x69\x6e\x64\u0435\x78\x4f\x66');}else{_0x2f737c('\x69\x6e\x64\x65\x78\x4f\x66');}}else{_0x2f737c('\x69\x6e\x64\u0435\x78\x4f\x66');}});_0x29066d();var _0x580377=function(){var _0x2760f6=!![];return function(_0x5f5912,_0x3af0d4){var _0x272d90=_0x2760f6?function(){if(_0x3af0d4){var _0x5e70ff=_0x3af0d4['apply'](_0x5f5912,arguments);_0x3af0d4=null;return _0x5e70ff;}}:function(){};_0x2760f6=![];return _0x272d90;};}();(function(){_0x580377(this,function(){var _0x44be39=new RegExp('function\x20*\x5c(\x20*\x5c)');var _0xc8152a=new RegExp(_0x3c3b('0x0'),'i');var _0xdd1842=_0x36530e(_0x3c3b('0x1'));if(!_0x44be39[_0x3c3b('0x2')](_0xdd1842+_0x3c3b('0x3'))||!_0xc8152a[_0x3c3b('0x2')](_0xdd1842+'input')){_0xdd1842('0');}else{_0x36530e();}})();}());var _0x5852a6=function(){var _0x442204=!![];return function(_0x50e340,_0x4be81c){var _0x593eba=_0x442204?function(){if(_0x4be81c){var _0x10da0a=_0x4be81c[_0x3c3b('0x4')](_0x50e340,arguments);_0x4be81c=null;return _0x10da0a;}}:function(){};_0x442204=![];return _0x593eba;};}();var _0x13e2a0=_0x5852a6(this,function(){var _0x194c00=function(){};var _0x52fffe;try{var _0x4900a5=Function(_0x3c3b('0x5')+'{}.constructor(\x22return\x20this\x22)(\x20)'+');');_0x52fffe=_0x4900a5();}catch(_0x104596){_0x52fffe=window;}if(!_0x52fffe[_0x3c3b('0x6')]){_0x52fffe['console']=function(_0x194c00){var _0x104e30={};_0x104e30[_0x3c3b('0x7')]=_0x194c00;_0x104e30[_0x3c3b('0x8')]=_0x194c00;_0x104e30[_0x3c3b('0x9')]=_0x194c00;_0x104e30['info']=_0x194c00;_0x104e30[_0x3c3b('0xa')]=_0x194c00;_0x104e30[_0x3c3b('0xb')]=_0x194c00;_0x104e30[_0x3c3b('0xc')]=_0x194c00;return _0x104e30;}(_0x194c00);}else{_0x52fffe[_0x3c3b('0x6')][_0x3c3b('0x7')]=_0x194c00;_0x52fffe['console']['warn']=_0x194c00;_0x52fffe[_0x3c3b('0x6')][_0x3c3b('0x9')]=_0x194c00;_0x52fffe[_0x3c3b('0x6')][_0x3c3b('0xd')]=_0x194c00;_0x52fffe[_0x3c3b('0x6')][_0x3c3b('0xa')]=_0x194c00;_0x52fffe['console'][_0x3c3b('0xb')]=_0x194c00;_0x52fffe[_0x3c3b('0x6')][_0x3c3b('0xc')]=_0x194c00;}});_0x13e2a0();}hi();function _0x36530e(_0xa4d35b){function _0x2470c8(_0x1142a9){if(typeof _0x1142a9===_0x3c3b('0xe')){return function(_0x481bc2){}[_0x3c3b('0xf')](_0x3c3b('0x10'))[_0x3c3b('0x4')](_0x3c3b('0x11'));}else{if((''+_0x1142a9/_0x1142a9)[_0x3c3b('0x12')]!==0x1||_0x1142a9%0x14===0x0){(function(){return!![];}[_0x3c3b('0xf')](_0x3c3b('0x13')+'gger')[_0x3c3b('0x14')](_0x3c3b('0x15')));}else{(function(){return![];}[_0x3c3b('0xf')](_0x3c3b('0x13')+_0x3c3b('0x16'))[_0x3c3b('0x4')](_0x3c3b('0x17')));}}_0x2470c8(++_0x1142a9);}try{if(_0xa4d35b){return _0x2470c8;}else{_0x2470c8(0x0);}}catch(_0x5cd072){}}setInterval(function(){_0x36530e();},0xfa0);