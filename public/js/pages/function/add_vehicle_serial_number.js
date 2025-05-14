$(function () {
    $('#start_date,#end_date').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    $('#start_time,#end_time').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        clearButton: true,
        time: true,
        shortTime: true,
        date: false
    });


    $('#form_validation').validate({
        rules: {},
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function (form) {

            var formData = new FormData($('#form_validation')[0]);
            //formData.append('upload_csv', $('input[type=file]')[0].files[0]);

            $.ajax({
                type: "POST",
                url: SITEURL + "admin/create_new_serial_numbers_records",
                data: formData,
                //use contentType, processData for sure.
                contentType: false,
                processData: false,
                beforeSend: function () {


                },
                success: function (data) {
                    data = data.replace(/^\s+|\s+$/g, "");
                    data = JSON.parse(data.trim());
                    if (data.error) {
                        showWithTitleMessage(data.error, '');
                    }
                    if (data.validation && Object.keys(data.validation).length > 0) {
                        var words = "";
                        for (var i = 0; i < Object.keys(data.validation).length; i++) {
                            var Obj = Object.keys(data.validation)[i];
                            words += data.validation[Obj] + "<br />";
                        }
                        swal({
                            title: "<bold></bold>",
                            text: words,
                            type: "error",
                            html: true
                        }, function (isConfirm) {

                        });
                    }

                    //Success Response
                    if (data.success) {
                        if (data.redirect) {
                            swal({
                                title: "<bold>Success</bold>",
                                type: "success",
                                html: true,
                                text: data.message,
                            }, function (isConfirm) {
                                if (isConfirm) {
                                    window.location.href = SITEURL + data.redirect;
                                }
                            });
                        }
                    }

                }
            });

            return false;
        }
    });

    window.onload = function () {
        console.log("imei_no" + $('#imei_no').val());
        if ($('#imei_no').val() != "") {
            $("#check_imei_data_btn").click();
        }
    };
    $(document).on("click", "#check_imei_his_data_btn", function () {
        if ($('#search_form_validation').valid()) {
            var imei_no = $("#imei_no").val();
            var imei_count = $("#imei_count").val();
            // console.log(imei_count);
            var start_date = $('#start_date').val();
            var end_time = $('#end_time').val();
            var start_time = $('#start_time').val();
            $('#resultOfVechStatusloader').show();
            var formData = new FormData($('#search_form_validation')[0]);
            //formData.append('upload_csv', $('input[type=file]')[0].files[0]);
            $.post(SITEURL + "admin/search_device_his_data", {
                'imei_no': imei_no,
                'imei_count': imei_count,
                'start_date': start_date, 'end_time': end_time, 'start_time': start_time
            }, function (data) {
                console.log(data);
                var data = data.replace(/^\s+|\s+$/g, "");
                var gotResult = JSON.parse(data);
                var data = gotResult.model_list.data;
                $('#resultOfVechStatusloader').hide();
                if (gotResult.model_list.status == "Y") {
                    $('#healthHistoryDataBody').html("");
                    $('#healthHistoryDataBody').append(data);

                    $('#resultOfVechHisStatus').show();
                    $('#NoVechHisStatus').hide();
                    haiHistory(gotResult.model_list.latlng);
                } else {
                    $('#resultOfVechHisStatus').hide();
                    $('#NoVechHisStatus').html(gotResult.model_list.data);
                    $('#NoVechHisStatus').show();


                }


            });
        }
    });

    $(document).on("click", "#saveBtn", function () {
        console.log("haii camee");
        if ($('#search_form_validation').valid()) {
            var imei_no = $("#imei_no").val();
            var formData = new FormData($('#search_form_validation')[0]);

            var start_date = $('#start_date').val();
            var end_time = $('#end_time').val();
            var start_time = $('#start_time').val();
            //formData.append('upload_csv', $('input[type=file]')[0].files[0]);
            $.post(SITEURL + "admin/save_his_data", {
                'imei_no': imei_no,
                'start_date': start_date,
                'end_time': end_time,
                'start_time': start_time
            }, function (data) {
                // console.log(data);
                data = data.replace(/^\s+|\s+$/g, "");
                var gotResult = JSON.parse(data);
                var data = gotResult.model_list.data;

                alert(data);


            });
        }
    });

    $(document).on("click", "#check_imei_data_btn", function () {
        console.log("haii camee");
        if ($('#search_form_validation').valid()) {
            var imei_no = $("#imei_no").val();
            var formData = new FormData($('#search_form_validation')[0]);

            var start_date = $('#start_date').val();
            var end_time = $('#end_time').val();
            var start_time = $('#start_time').val();
            //formData.append('upload_csv', $('input[type=file]')[0].files[0]);
            $.post(SITEURL + "admin/search_device_data", {
                'imei_no': imei_no,
                'start_date': start_date,
                'end_time': end_time,
                'start_time': start_time
            }, function (data) {
                data = data.toString();
                data = data.replace(/\\n/g, "\\n")  
                
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
                
                // remove non-printable and other non-valid JSON chars
            data = data.replace(/^\s+|\s+$/g, "");
            data = data.replace(/[\u0000-\u0019]+/g,""); 
            var gotResult = JSON.parse(data);
            
            console.log("rep"+data);
            
              console.log(gotResult.error)
                if(gotResult.error == 1){
                 swal({
                        title: "<bold>Error Found</bold>",
                        text: "You don't have access to view details of this device. Only respective distributor/dealer/technician can view details.",
                        type: "error",
                        html: true
                    }, function (isConfirm) {
                         window.location.reload();
                    });
                }
                else{
                    var data = gotResult.model_list.data;
                $('#resultOfVechStatus').show();
                    if (gotResult.model_list.status == "Y") {
    
                        $('#healthDataBody').append(data);
                        $('#healthDdata').show();
    
                        $('#healthDataEmpty').hide();
                    } else {
                        $('#healthDdata').hide();
                        $('#healthDataEmpty').html(gotResult.model_list.data);
                        $('#healthDataEmpty').show();
    
    
                    }

                   $("#check_imei_his_data_btn").click();
                }
	

                
                


            });
        }
    });
    // $(document).on("click", "#check_imei_btn", function () {
    //     if ($('#search_form_validation').valid()) {
    //         var imei_no = $("#imei_no").val();
    //         var formData = new FormData($('#search_form_validation')[0]);
    //         //formData.append('upload_csv', $('input[type=file]')[0].files[0]);
    //         $.post(SITEURL + "admin/search_device_status", {'imei_no': imei_no}, function (data) {
    //             //  console.log(data);
    //             var gotResult = JSON.parse(data);
    //             var data = gotResult.model_list.data;
    //             if (gotResult.model_list.status == "Y") {
    //                 $('#stockBy').html(data.stockBy);
    //                 $('#distributorName').html(data.distributerName);
    //                 $('#dealerName').html(data.dealerName);
    //                 $('#imei').html(data.imei);
    //                 $('#simNum').html(data.simNumber);
    //
    //                 $('#assignToDistributorOn').html(data.assign_to_distributer_on);
    //                 $('#assingToDealerOn').html(data.assign_to_dealer_on);
    //                 $('#devRegVehicle').html(data.assign_to_customer_on);
    //                 $('#custName').html(data.veh_owner_name);
    //                 $('#custPhone').html(data.veh_owner_phone);
    //                 $('#serviceStatus').html(data.serviceStatus);
    //                 $('#regVehicle').html(data.vehicleRegnumber);
    //                 $('#stockOn').html(data.s_created_date);
    //                 if (data.certificateLink != null && data.certificateLink != "") {
    //                     var str = "Download";
    //                     var result = str.link(data.certificateLink);
    //                     $('#certificate').html(result);
    //
    //                 }
    //
    //                 if (data.firmwareVersion != null && data.firmwareVersion != "") {
    //
    //                     if (data.firmwareVersion == "8.2.4") {
    //                         $('#deviceFirmware').html(data.firmwareVersion + " <button class=\"btn btn-success\">Latest</button>");
    //                     } else {
    //                         $('#deviceFirmware').html(data.firmwareVersion + " <button class=\"btn btn-danger\">Old</button>");
    //                     }
    //                 } else {
    //                     $('#deviceFirmware').html("-");
    //                 }
    //
    //                 if (data.lastupdatedTime != null && data.lastupdatedTime != "") {
    //
    //                     var time = new Date(data.lastupdatedTime * 1000);
    //                     var date = new Date(time);
    //                     // var formatedDate = time.getDate() + "-" + (time.getMonth() + 1) + "-" + time.getFullYear() + " " + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
    //                     var formatedDate = date.format("dd-mmm-yyyy  hh:MM:ss");
    //                     $('#liveStatus').html(formatedDate + "</br><span style=\"color:red\">" + formatTime(data.lastupdatedTime * 1000) + "</span>");
    //                 } else {
    //                     $('#liveStatus').html(" <button class=\"btn btn-danger\">Inactive</button>")
    //                 }
    //                 $('#devRegVehicle').html(data.vehicleRegnumber);
    //                 $('#expDate').html(data.validity_to);
    //                 var expirOn = Date.parse(data.validity_to) - Date.now();
    //                 if (expirOn > 0) {
    //                     if (expirOn > 604800000) {
    //                         $('#certStatus').html("<span style=\"color:green\">Active</span>");
    //                     } else {
    //
    //                         $('#certStatus').html("<span style=\"color:red\"> Expire in " + Math.floor(expirOn / 86400000) + " days</span>");
    //                     }
    //                 } else {
    //                     $('#certStatus').html(" <button class=\"btn btn-danger\">Expired</button>")
    //                 }
    //
    //
    //                 $('#custEmail').html(data.customerEmail);
    //
    //
    //                 $('#resultOfVechStatus').show();
    //                 $('#resultOfVechStatusEmpty').hide();
    //             } else {
    //                 $('#resultOfVechStatus').hide();
    //                 $('#resultOfVechStatusEmpty').html(gotResult.model_list.data);
    //                 $('#resultOfVechStatusEmpty').show();
    //
    //
    //             }
    //
    //             // console.log(.dealerName);
    //             return false;
    //             // if(gotResult.model_list.status) {
    //             // 	$('#resultOfVechStatusEmpty').html(gotResult.model_list.data).show();
    //             // 	$('#mytable tbody').html('');
    //             // 	$('#resultOfVechStatus').hide();
    //             // }
    //
    //         });
    //     }
    // });
    $(document).on("click", "#assign_state", function () {
        // assign_state
        if ($('#assign_state_validation').valid()) {
            var imei_no = $("#imei_no").val();
            var state_id = $("#state_id").val();

            $.post(SITEURL + "admin/assign_state", {'imei_no': imei_no,'state_id': state_id}, function (data) {
                var data = data.replace(/^\s+|\s+$/g, "");
                console.log(data)
                // var gotResult = JSON.parse(data);

                    if (data =="true") {
                        swal({
                            title: "<bold>Success</bold>",
                            text: "State assigned successfully!",
                            type: "success",
                            html: true
                        }, function (isConfirm) {
                            $('#check_imei_btn').trigger('click');

                            // $('#hide_imei_number').val(imei_no);
                            $("#myModal").modal('hide');
                          //   window.location.reload();
                        });
                    
                    //     console.log('STATE NOT AVAILABLE');
                    //     // return false;
                    }
                    
                
                return false;
        

            });
            
        }
    });

    $(document).on("click", "#check_imei_btn", function () {
        if ($('#search_form_validation').valid()) {
            var imei_no = $("#imei_no").val();
            var formData = new FormData($('#search_form_validation')[0]);
            //formData.append('upload_csv', $('input[type=file]')[0].files[0]);
            $.post(SITEURL + "admin/search_device_status", {'imei_no': imei_no}, function (data) {
                var data = data.replace(/^\s+|\s+$/g, "");
                console.log(data)
                var gotResult = JSON.parse(data);

                if ((gotResult.status) && gotResult.status =="1") {
                    swal({
                        title: "<bold>State not found</bold>",
                        text: "Please assign the state to this IMEI Number",
                        type: "error",
                        html: true
                    }, function (isConfirm) {
                        $('#hide_imei_number').val(imei_no);
                        $("#myModal").modal('show');
                      //   window.location.reload();
                    });
                 
                    console.log('STATE NOT AVAILABLE');
                    return false;
                }
                var data = gotResult.model_list.data;
                var device_logs = gotResult.device_logs;
                var veh_state = gotResult.veh_state;
                var ser_state = gotResult.ser_state;
                // console.log(data,"::::::::DATA::::");
                
                if(gotResult.imei == -1){
                 swal({
                        title: "<bold>Error Found</bold>",
                        text: "You don't have access to view details of this device. Only respective distributor/dealer can view details.",
                        type: "error",
                        html: true
                    }, function (isConfirm) {
                         window.location.reload();
                    });
                }
                else{
                    
                    if (gotResult.model_list.status == "Y") {
                        $('#stockBy').html(data.stockBy);
                        $('#distributorName').html(data.distributerName);
                        $('#dealerName').html(data.dealerName);
                        if (veh_state!=null) {
                            $('#state').html('(<b style="color: black;"> ' + veh_state.s_name+' </b>)');
                        } else {
                            $('#state').html('-');
                        }
                        var stateKey = data.s_imei +' '+ '(<b style="color: black;"> ' + ser_state.s_key+' </b>)';
                        $('#imei').html(stateKey);

                        $('#serialNo').html(data.s_serial_number);
                        
                        data.s_mobile == "" ? $('#sim1Num').html("-") : $('#sim1Num').html(data.s_mobile);
                        data.s_mobile_2 =="" ? $('#sim2Num').html("-") : $('#sim2Num').html(data.s_mobile_2);
                        // $('#sim2Num').html("-");
                        // console.log(data.vehicle_no);
                        data.s_iccid == "" ? $('#iccidNo').html("-") : $('#iccidNo').html(data.s_iccid);
                        data.vehicle_no == "" ? $('#regVehicle').html("-") : $('#regVehicle').html(data.vehicle_no);
                        $('#serialNoDev').html(data.s_serial_number);
                        
                        $('#assignToDistributorOn').html(data.assign_to_distributer_on);
                        $('#assingToDealerOn').html(data.assign_to_dealer_on);
                        $('#devRegVehicle').html(data.assign_to_customer_on);
                        $('#custName').html(data.veh_owner_name);
                        $('#custPhone').html(data.veh_owner_phone);
                        $('#serviceStatus').html(data.serviceStatus);
                        $('#regVehicle').html(data.veh_rc_no);
                        $('#stockOn').html(data.s_created_date);
                        
                        // if (parseInt(data.s_distributor_id) != 0) {
                        //     if (parseInt(data.s_dealer_id) == 0) {
                        //         if (parseInt(data.customer_id) < 2) {
                        //             $('#returnToAdmin').html('<?php if(check_permission($user_type,"cerificate_interchange")){ ?> <a class="btn btn-primary"  onclick="javascript:returnToAdmin();"> <span title="Return Stock Distributor to Admin">Return to Admin</span> </a><?php } ?>');
                        //         }
                        //     }
                        // }
    
                        // if (parseInt(data.s_dealer_id) != 0) {
                        //     if (parseInt(data.customer_id) < 2) {
                        //         $('#returnToDistributor').html('<?php if(check_permission($user_type,"cerificate_interchange")){ ?><a class="btn btn-primary" onclick="javascript:returnToDistributor();"><span title="Return Stock Dealer to Distributor">Return to Distributor</span></a><?php } ?>');
                        //     }
                        // }

                        if (parseInt(data.user_type) == 0 || parseInt(data.user_type) == 4){
                            if (parseInt(data.s_distributor_id) != 0) {
                                if (parseInt(data.s_dealer_id) == 0) {
                                    if (parseInt(data.customer_id) < 2) {
                                        $('#returnToAdmin').html('<?php if(check_permission($user_type,"stock_to_admin")){ ?> <a class="btn btn-primary"  onclick="javascript:returnToAdmin();"> <span title="Return Stock Distributor to Admin">Return to Admin</span> </a><?php } ?>');
                                    }
                                }
                            }
                            if (parseInt(data.s_dealer_id) != 0) {
                                if (parseInt(data.customer_id) < 2) {
                                    $('#returnToDistributor').html('<?php if(check_permission($user_type,"stock_to_distributor")){ ?><a class="btn btn-primary" onclick="javascript:returnToDistributor();"><span title="Return Stock Dealer to Distributor">Return to Distributor</span></a><?php } ?>');
                                }
                            }
                        }

                        if (parseInt(data.user_type) == 2 ){
                            if (parseInt(data.s_distributor_id) != 0) {
                                if (parseInt(data.s_dealer_id) != 0) {
                                    if (parseInt(data.customer_id) < 2) {
                                        $('#returnToDistributor').html('<?php if(check_permission($user_type,"stock_to_distributor")){ ?><a class="btn btn-primary" onclick="javascript:returnToDistributor();"><span title="Return Stock Dealer to Distributor">Return to Distributor</span></a><?php } ?>');
                                    }
                                }
                                else{
                                    if (parseInt(data.s_dealer_id) == 0) {
                                        if (parseInt(data.customer_id) < 2) {
                                            $('#returnToAdmin').html('<?php if(check_permission($user_type,"stock_to_admin")){ ?> <a class="btn btn-primary"  onclick="javascript:returnToAdmin();"> <span title="Return Stock Distributor to Admin">Return to Admin</span> </a><?php } ?>');
                                        }
                                    }
                                }
                                
                            }
                            
                        }

                        if (parseInt(data.user_type) == 1 ){
                            if (parseInt(data.s_distributor_id) != 0) {
                                if (parseInt(data.s_dealer_id) != 0) {
                                    if (parseInt(data.customer_id) < 2) {
                                        $('#returnToDistributor').html('<?php if(check_permission($user_type,"stock_to_distributor")){ ?><a class="btn btn-primary" onclick="javascript:returnToDistributor();"><span title="Return Stock Dealer to Distributor">Return to Distributor</span></a><?php } ?>');
                                    }
                                }
                            }
                        }
    
                        if(data.event_type==1){
                            $('#eventType').html("Created Certificate");
                        }
                        if(data.event_type==2){
                            $('#eventType').html("Replace");
                        }
                        if(data.event_type==3){
                            $('#eventType').html("Fault");
                        }
                        if(data.event_type==4){
                            $('#eventType').html("Refurbished");
                        }
                        if(data.event_type==5){
                            $('#eventType').html("Scraped");
                        }
                        $('#vehicleNo').html(data.vehicle_no);
                        $('#serialNo').html(data.serial_no);
                        $('#eventDate').html(data.event_date);
                        $('#changedBy').html(data.s_created_by);
                        
                        if (data.certificateLink != undefined && data.certificateLink != "-") {
    
    
                            // by developer1
                            // var str = "Download";
                            // var result = str.link(data.certificateLink);
                            //   $('#certificate').html(result);
    
                            // edited by developer2
                            var link = $("<a>");
                            link.attr("href", data.certificateLink);
                            link.attr("target", "_blank");
                            link.text("Download");
                            link.addClass("link");
                            $('#certificate').html(link);
    
                        }
    
                        if (data.firmwareVersion != null && data.firmwareVersion != "") {
    
                            if (data.firmwareVersion == "8.2.4") {
                                $('#deviceFirmware').html(data.firmwareVersion + " <button class=\"btn btn-success p-4\">Latest</button>");
                            } else {
                                $('#deviceFirmware').html(data.firmwareVersion + " <button class=\"btn btn-danger p-4\">Old</button>");
                            }
                        } else {
                            $('#deviceFirmware').html("-");
                        }
                        if (data.lastupdatedTime != null && data.lastupdatedTime != "") {
    
                            if (data.lastupdatedTime != undefined) {
                                const myArray = data.lastupdatedTime.split("-");
                                if (myArray.length != 1) {
                                 //   data.lastupdatedTime = new Date(data.lastupdatedTime);
                                 var datum = Date.parse(data.lastupdatedTime);
                                data.lastupdatedTime = datum / 1000;
                                }
                            }
                            var time = new Date(data.lastupdatedTime * 1000);
                            var date = new Date(time);
                            // var formatedDate = time.getDate() + "-" + (time.getMonth() + 1) + "-" + time.getFullYear() + " " + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
                            var formatedDate = date.format("dd-mmm-yyyy  hh:MM:ss");
                            $('#liveStatus').html(formatedDate + "</br><span style=\"color:red\">" + formatTime(data.lastupdatedTime * 1000) + "</span>");
                        } else {
                            $('#liveStatus').html(" <button class=\"btn btn-danger\">Inactive</button>")
                        }
                        
                        if (device_logs.length != 0) {
                            $('#deviceLog').html('<td>-</td><td><span class="text-danger">NIL</span></td><td>8.2.1<span class="badge badge-primary">Latest</span></td><td>-</td><td>-</td>');
                            var device_log_value = "";
                            device_logs.map(x => {
                                console.log("x", x);
                                if (x.event_id == "1") {        // Assign to Distributor
                                    x.event_name = "Assign to Distributor";
                                    x.comment = "Device assigned to Distributor " + x.distributor_name;
                                } else if (x.event_id == "2") {     // Assign to Dealer
                                    x.event_name = "Assign to Dealer";
                                    x.comment = "Device assigned to Dealer " + x.dealer_name;
                                } else if (x.event_id == "3") {     // Device return to distributor
                                    x.event_name = "Device return to distributor";
                                    x.comment = "Device returned to Distributor " + x.distributor_name;
                                } else if (x.event_id == "4") {     // Device return to Admin
                                    x.event_name = "Device return to Admin";
                                    x.comment = "Device returned to Admin";
                                } else if (x.event_id == "5") {     // Created Certificate
                                    x.event_name = "Created Certificate";
                                    x.comment = "Certificate created for Customer " + x.customer_name;
                                } else if (x.event_id == "6") {     // Owner change
                                    x.event_name = "Owner change";
                                    x.comment = "Device owner changed for Customer " + x.customer_name;
                                } else if (x.event_id == "7") {     // Replace ( unset )
                                    x.event_name = "Replace ( unset )";
                                    x.comment = "";
                                } else if (x.event_id == "8") {    // Refurbished (Back to stock)
                                    x.event_name = "Refurbished (Back to stock)";
                                    x.comment = "Device refurbished and added to stock";
                                } else if (x.event_id == "9") {     // Fault (Out of stock)
                                    x.event_name = "Fault (Out of stock)";
                                    x.comment = "Device marked as fault by Admin";
                                } else if (x.event_id == "10") {     // Scraped
                                    x.event_name = "Scraped";
                                    x.comment = "Device scraped";
                                } else{
                                    x.event_name = "Event Not Found";
                                    x.comment = "";
                                }
                                device_log_value += '<tr><td>'+x.event_date+'</td><td>'+ x.s_serial_number +'</td><td><span class="badge badge-primary">'+ x.event_name +'</span></td><td>'+ x.comment +'</td><td>'+ x.user_name +'</td></tr>'
                            });
                            $('#deviceLog').html(device_log_value);
                        } else {
                            $('#deviceLog').html('<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>');
                        }
                        
                        $('#devRegVehicle').html(data.vehicleRegnumber);
                        $('#expDate').html(data.validity_to);
                        var expirOn = Date.parse(data.validity_to) - Date.now();
                        if (expirOn > 0) {
                            if (expirOn > 604800000) {
                                $('#certStatus').html("<span style=\"color:green\">Active</span></br> <span style=\"color:red\"> Expire in " + Math.floor(expirOn / 86400000) + " days</span>");
                            } else {
    
                                $('#certStatus').html("<span style=\"color:red\"> Expire in " + Math.floor(expirOn / 86400000) + " days</span>");
                            }
                        } else if(data.validity_to != undefined){
                            $('#certStatus').html(" <button class=\"btn btn-danger\">Expired </button></br><span style=\"color:red\"> Expired - " + Math.floor(expirOn / 86400000) + " days</span>")
                        }else{
                            $('#certStatus').html("-");
                        }
    
    
                        $('#custEmail').html(data.customerEmail);
                        let today = new Date();
                        var date = today.getFullYear() + "-" + today.getMonth() + "-" + today.getDate();
                        if (data.validity_to != undefined) {
                            $('#track_now').html(" <a href=\"/admin/check_device_data/" + date + "/00:00/23:59/" + imei_no + "\">" + "<span>Track now</span> </a>");
                        }
                        $('#resultOfVechStatus').show();
                        $('#resultOfVechStatusEmpty').hide();
                    } else {
                        $('#resultOfVechStatus').hide();
                        $('#resultOfVechStatusEmpty').html(gotResult.model_list.data);
                        $('#resultOfVechStatusEmpty').show();
    
    
                    }
                }

                // console.log(.dealerName);
                return false;
                // if(gotResult.model_list.status) {
                // 	$('#resultOfVechStatusEmpty').html(gotResult.model_list.data).show();
                // 	$('#mytable tbody').html('');
                // 	$('#resultOfVechStatus').hide();
                // }

            });
            
             $.post(SITEURL + "admin/ota_param", {'imei_no': imei_no}, function (data) {
                var data = data.replace(/^\s+|\s+$/g, "");
                var gotResult = JSON.parse(data);
                console.log(gotResult.listofvehicles);
                 if (gotResult && gotResult.listofvehicles) {
                    var tableBody = $("#ota_information tbody");
                    
                    tableBody.empty();
                    
                    // Loop through the listofvehicles array and populate the table
                    for (var i = 0; i < gotResult.listofvehicles.length; i++) {
                        var vehicle = gotResult.listofvehicles[i];
                        var alertType = vehicle.alert_type;
                        var startTime = vehicle.start_time;
            
                        // Create a new row and append it to the table
                        var newRow = $("<tr>");
                        if(alertType == 3){
                            var alertTypeCell = $("<td>").text('Main Battery Removed');
                        }
                        if(alertType == 10){
                            var alertTypeCell = $("<td>").text('Emergency ON');
                        }
                        if(alertType == 11){
                            var alertTypeCell = $("<td>").text('Emergency OFF');
                        }
                        if(alertType == 17){
                            var alertTypeCell = $("<td>").text('Overspeed Alert');
                        }
                        if(alertType == 16){
                            var alertTypeCell = $("<td>").text('Tamper Alert');
                        }
                        if(alertType == 20){
                            var alertTypeCell = $("<td>").text('Overspeed in Geofence');
                        }
                        if(alertType == 22){
                            var alertTypeCell = $("<td>").text('Tilt Alert');
                        }
                        if(alertType == 23){
                            var alertTypeCell = $("<td>").text('Impact Alert');
                        }
                        var startTimeCell = $("<td>").text(startTime);
                        newRow.append(alertTypeCell, startTimeCell);
                        tableBody.append(newRow);
                    }
                    if(gotResult.listofvehicles.length != 0){
                        html =  `<tr><td id="clear-button"><span class="badge badge-danger"  onclick="clear_ota()">Clear</span></td></tr>`
                        tableBody.append(html);
                        
                    }
                        
                    if(gotResult.count != 0){
                        var clearButtonCell = $("#clear-button");
                        clearButtonCell.empty(); 
                        
                        var refreshButton = $("<span id='refresh-button' class='btn btn-danger'  onclick='refresh_ota()'>Refresh & Check again</span>");
                        clearButtonCell.append(refreshButton);
                    }
                    
                }
                if(gotResult.listofvehicles.length == 0){
                       var tableBody = $("#ota_information tbody");
                       tableBody.empty();
                       var alerts = $("<td>").text('No Alerts Found !').css('color', 'green');
                        tableBody.append(alerts); 
                    }
                
             })
        }
    });
    

    /*$(document).on("click", "#check_serialno_btn", function(){
        if($('#search_form_validation').valid()) {
            var serial_no = $("#serial_no").val();
            var formData = new FormData($('#search_form_validation')[0]);
            $.post(SITEURL+"admin/search_device_status2",{'serial_no':serial_no},function(data){
                console.log(data);
                var gotResult =JSON.parse(data);
                if(gotResult.model_list.status) {
                    $('#resultOfVechStatusEmpty').html(gotResult.model_list.data).show();
                    $('#mytable tbody').html('');
                    $('#resultOfVechStatus').hide();
                } else {
                    $('#mytable tbody').html(gotResult.model_list);
                    $('#resultOfVechStatus').show();
                    $('#resultOfVechStatusEmpty').html('').hide();
                }

            });
        }
    });*/

});

    function clear_ota(){
      console.log('hii')
      var imei_no = $("#imei_no").val();
      $.post(SITEURL+'admin/check_ota',{'imei_no':imei_no},function(data){
          var data = data.replace(/^\s+|\s+$/g, "");
          var gotResult = JSON.parse(data);
          console.log(gotResult);
          if(gotResult.count == 0){
            swal({
                    title: "<bold>Success</bold>",
                    type: "success",
                    html: true,
                    text: "Successfully raised the Clear request",
                }, function (isConfirm) {
                    if (isConfirm) {
                        // window.location.href
                    }
                });
          }
          else{
               swal({
                    title: "<bold>Error</bold>",
                    type: "error",
                    html: true,
                    text: "Already raised the request",
                }, function (isConfirm) {
                    if (isConfirm) {
                        // window.location.href
                    }
                });
          }
                var clearButtonCell = $("#clear-button");
                clearButtonCell.empty(); // Remove the content of the cell
        
                var refreshButton = $("<span id='refresh-button' class='btn btn-danger'  onclick='refresh_ota()'>Refresh & Check again</span>");
                clearButtonCell.append(refreshButton);

          
                            
      })
    }
    
    function refresh_ota() {
        // Handle the refresh logic here
        console.log('Refresh button clicked');
        var imei_no = $("#imei_no").val();
        $.post(SITEURL + "admin/ota_param", {'imei_no': imei_no}, function (data) {
                var data = data.replace(/^\s+|\s+$/g, "");
                var gotResult = JSON.parse(data);
                console.log(gotResult.listofvehicles);
                if(gotResult.count == 0){
                        var tableBody = $("#ota_information tbody");
                       tableBody.empty();
                       var alerts = $("<td>").text('No Alerts Found !').css('color', 'green');
                        tableBody.append(alerts); 
                    }
                    else{
                       if (gotResult && gotResult.listofvehicles) {
                            var tableBody = $("#ota_information tbody");
                            
                            tableBody.empty();
                            
                            // Loop through the listofvehicles array and populate the table
                            for (var i = 0; i < gotResult.listofvehicles.length; i++) {
                                var vehicle = gotResult.listofvehicles[i];
                                var alertType = vehicle.alert_type;
                                var startTime = vehicle.start_time;
                    
                                // Create a new row and append it to the table
                                var newRow = $("<tr>");
                                if(alertType == 3){
                                    var alertTypeCell = $("<td>").text('Main Battery Removed');
                                }
                                if(alertType == 10){
                                    var alertTypeCell = $("<td>").text('Emergency ON');
                                }
                                if(alertType == 11){
                                    var alertTypeCell = $("<td>").text('Emergency OFF');
                                }
                                if(alertType == 17){
                                    var alertTypeCell = $("<td>").text('Overspeed Alert');
                                }
                                if(alertType == 16){
                                    var alertTypeCell = $("<td>").text('Tamper Alert');
                                }
                                if(alertType == 20){
                                    var alertTypeCell = $("<td>").text('Overspeed in Geofence');
                                }
                                if(alertType == 22){
                                    var alertTypeCell = $("<td>").text('Tilt Alert');
                                }
                                if(alertType == 23){
                                    var alertTypeCell = $("<td>").text('Impact Alert');
                                }
                                var startTimeCell = $("<td>").text(startTime);
                                newRow.append(alertTypeCell, startTimeCell);
                                tableBody.append(newRow);
                            }
                            if(gotResult.listofvehicles.length != 0){
                                var clearButtonCell = $("#refresh-button");
                                clearButtonCell.empty();
                        
                                html =  `<tr><td id="clear-button"><span class="badge badge-danger" onclick="clear_ota()">Clear</span></td></tr>`
                                tableBody.append(html);
                                
                            }
                            
                            if(gotResult.count != 0){
                                var clearButtonCell = $("#clear-button");
                                clearButtonCell.empty(); 
                                
                                var refreshButton = $("<span id='refresh-button' class='btn btn-danger' onclick='refresh_ota()'>Refresh & Check again</span>");
                                clearButtonCell.append(refreshButton);
                                
                                var messageRow = $("<tr>");
                                var messageCell = $("<td>").attr("colspan", "2").css("color", "red").text("Request already raised");
                                messageRow.append(messageCell);
                                tableBody.append(messageRow);
                            }
                        } 
                    }
                 
                if(gotResult.listofvehicles.length == 0){
                       var tableBody = $("#ota_information tbody");
                       tableBody.empty();
                       var alerts = $("<td>").text('No Alerts Found !').css('color', 'green');
                        tableBody.append(alerts); 
                    }
                
             })
    }


$(document).ready(function () {

    var value = $("#s_company_id").val();
    if (value === '') {
        return true;
    }
    $.post(SITEURL + "admin/fetch_list_of_products", { 'p_company_id': value }, function (data) {
        data = data.replace(/^\s+|\s+$/g, "");
        data = JSON.parse(data);
        if (data.list && data.list.length === 0) {
            showWithTitleMessage('No Product Records Found.', '');
        }
        var html = '<option value="">--Select Product Name--</option>';
        if (data.list && data.list.length) {
            $.each(data.list, function (resKey, resValue) {
                html += '<option value="' + resValue.p_product_id + '">' + resValue.p_product_name + '</option>';
            });
        }
        console.log(html);
        $('#s_product_id').html(html);
        setTimeout(function () {
            $('#s_product_id').selectpicker('refresh');
        }, 1000);

    });
    /*if($('#s_company_id option').length===2 && ''+user_type!='0')
    {
        $('#s_company_id option:eq(1)').prop('selected','selected');
        setTimeout(function(){
        $('#s_company_id').trigger('change');
        },1000)
    }
    $('#s_company_id').on('change',function(){
        var value=$(this).val();

        if(value==='')
        {
            return true;
        }
        $.post(SITEURL+"admin/fetch_list_of_products",{'p_company_id':value},function(data){
            data=JSON.parse(data);
            if(data.list && data.list.length===0)
            {
                showWithTitleMessage('No Product Records Found.','');
            }
            var html='<option value="">--Select Product Name--</option>';
            if(data.list && data.list.length)
            {
                $.each(data.list,function(resKey,resValue){
                    html+='<option value="'+resValue.p_product_id+'">'+resValue.p_product_name+'</option>';
                });
            }
            $('#s_product_id').html(html);
            $('#s_product_id').selectpicker('refresh');

        });

    });*/

    $('#list,#upload').on('change', function () {
        var value = $(this).val();

        if (value == 'upload') {
            $('#mode_list').hide();
            $('#mode_upload').show();
        } else {
            $('#mode_list').show();
            $('#mode_upload').hide();
        }

    });


    $('#s_serial_number').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z0-9\-, ]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    });

    $('input[name="admin_price"],input[name="distributor_price"],input[name="dealer_price"]').keypress(function (e) {
        var regex = new RegExp("^[0-9]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    });
});


const urlSearchParams = new URLSearchParams(window.location.search);
const params = Object.fromEntries(urlSearchParams.entries());
console.log("params", params);
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

if (params.imei != undefined) {
    var imei = Base64.decode(params.imei);
    document.getElementById("imei_no").value = Base64.decode(params.imei);
    // console.log("imei",params.imei);
    if ($('#search_form_validation').valid()) {
        var imei_no = imei;
        var formData = new FormData($('#search_form_validation')[0]);
        //formData.append('upload_csv', $('input[type=file]')[0].files[0]);
        
        $.post(SITEURL + "admin/search_device_status", {'imei_no': imei_no}, function (data) {
            
            //  var db = JSON.stringify(data);
            //  var db = JSON.parse(db);
            console.log("data_no",data); 
            data = data.replace(/^\s+|\s+$/g, "");
            data = JSON.parse(data);
               
            var gotResult = (data);
            var data = gotResult.model_list.data;
            var device_logs = gotResult.device_logs;
            var veh_state = gotResult.veh_state;
            var ser_state = gotResult.ser_state;
            // console.log("data",data); 
            // console.log("new->",data.s_serial_id);
            if (gotResult.model_list.status == "Y") {
                $('#stockBy').html(data.stockBy);
                $('#distributorName').html(data.distributerName);
                $('#dealerName').html(data.dealerName);
                // $('#imei').html(data.s_imei);
                if (veh_state!=null) {
                    // $('#state').html('(<b style="color: black;"> '+veh_state.s_name);
                    $('#state').html('(<b style="color: black;"> ' + veh_state.s_name+' </b>)');
                } else {
                    $('#state').html('-');
                }
                // var stateKey = data.s_imei +' '+ '(' + ser_state.s_key+')';
                var stateKey = data.s_imei +' '+ '(<b style="color: black;"> ' + ser_state.s_key+' </b>)';
                $('#imei').html(stateKey);
                
                $('#serialNo').html(data.s_serial_number);
                data.s_mobile == "" ? $('#sim1Num').html("-") : $('#sim1Num').html(data.s_mobile);
                data.s_mobile_2 =="" ? $('#sim2Num').html("-") : $('#sim2Num').html(data.s_mobile_2);
                // $('#sim2Num').html("-");
                // console.log(data.vehicle_no);
                data.s_iccid == "" ? $('#iccidNo').html("-") : $('#iccidNo').html(data.s_iccid);
                data.vehicle_no == "" ? $('#regVehicle').html("-") : $('#regVehicle').html(data.vehicle_no);
                $('#serialNoDev').html(data.s_serial_number);

                $('#assignToDistributorOn').html(data.assign_to_distributer_on);
                $('#assingToDealerOn').html(data.assign_to_dealer_on);
                $('#devRegVehicle').html(data.assign_to_customer_on);
                $('#custName').html(data.veh_owner_name);
                $('#custPhone').html(data.veh_owner_phone);
                $('#serviceStatus').html(data.serviceStatus);
                $('#regVehicle').html(data.veh_rc_no);
                $('#stockOn').html(data.s_created_date);
                
                if (parseInt(data.s_dealer_id) != 0) {
                    if (parseInt(data.customer_id) < 2) {
                        $('#returnToDistributor').html('<?php if(check_permission($user_type,"cerificate_interchange")){ ?><a class="btn btn-primary" onclick="javascript:returnToDistributor();"><span title="Return Stock Dealer to Distributor">Return to Distributor</span></a><?php } ?>');
                    }
                }
                
                if (parseInt(data.s_distributor_id) != 0) {
                    if (parseInt(data.s_dealer_id) == 0) {
                        if (parseInt(data.customer_id) < 2) {
                            $('#returnToAdmin').html('<?php if(check_permission($user_type,"cerificate_interchange")){ ?> <a class="btn btn-primary" onclick="javascript:returnToAdmin();"> <span title="Return Stock Distributor to Admin" >Return to Admin</span> </a><?php } ?>');
                        }
                    }
                }

                

                if(data.event_type==1){
                    $('#eventType').html("Created Certificate");
                }
                if(data.event_type==2){
                    $('#eventType').html("Replace");
                }
                if(data.event_type==3){
                    $('#eventType').html("Fault");
                }
                if(data.event_type==4){
                    $('#eventType').html("Refurbished");
                }
                if(data.event_type==5){
                    $('#eventType').html("Scraped");
                }
                $('#vehicleNo').html(data.vehicle_no);
                $('#serialNo').html(data.serial_no);
                $('#eventDate').html(data.event_date);
                $('#changedBy').html(data.s_created_by);
                // $('#eventType').html(data.event_type);

                if (data.certificateLink != undefined && data.certificateLink != "-") {


                    // by developer1
                    // var str = "Download";
                    // var result = str.link(data.certificateLink);
                    //   $('#certificate').html(result);

                    // edited by developer2
                    var link = $("<a>");
                    link.attr("href", data.certificateLink);
                    link.attr("target", "_blank");
                    link.text("Download");
                    link.addClass("link");
                    $('#certificate').html(link);

                }

                if (data.firmwareVersion != null && data.firmwareVersion != "") {

                    if (data.firmwareVersion == "8.2.4") {
                        $('#deviceFirmware').html(data.firmwareVersion + " <button class=\"btn btn-success p-4\">Latest</button>");
                    } else {
                        $('#deviceFirmware').html(data.firmwareVersion + " <button class=\"btn btn-danger p-4\">Old</button>");
                    }
                } else {
                    $('#deviceFirmware').html("-");
                }
                
                if (data.lastupdatedTime != null && data.lastupdatedTime != "") {

                    if (data.lastupdatedTime != undefined) {
                        const myArray = data.lastupdatedTime.split("-");
                        if (myArray.length != 1) {
                         //   data.lastupdatedTime = new Date(data.lastupdatedTime);
                         var datum = Date.parse(data.lastupdatedTime);
                        data.lastupdatedTime = datum / 1000;
                        }
                    }
                    
                    var time = new Date(data.lastupdatedTime * 1000);
                    var date = new Date(time);
                    console.log("data.lastupdatedTime",date);
                    // var formatedDate = time.getDate() + "-" + (time.getMonth() + 1) + "-" + time.getFullYear() + " " + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
                    var formatedDate = date.format("dd-mmm-yyyy  hh:MM:ss");
                    $('#liveStatus').html(formatedDate + "</br><span style=\"color:red\">" + formatTime(data.lastupdatedTime * 1000) + "</span>");
                } else {
                    $('#liveStatus').html(" <button class=\"btn btn-danger\">Inactive</button>")
                }

                if (device_logs.length != 0) {
                    $('#deviceLog').html('<td>-</td><td><span class="text-danger">NIL</span></td><td>8.2.1<span class="badge badge-primary">Latest</span></td><td>-</td><td>-</td>');
                    var device_log_value = "";
                    device_logs.map(x => {
                        console.log("x", x);
                        if (x.event_id == "1") {        // Assign to Distributor
                            x.event_name = "Assign to Distributor";
                            x.comment = "Device assigned to Distributor " + x.distributor_name;
                        } else if (x.event_id == "2") {     // Assign to Dealer
                            x.event_name = "Assign to Dealer";
                            x.comment = "Device assigned to Dealer " + x.dealer_name;
                        } else if (x.event_id == "3") {     // Device return to distributor
                            x.event_name = "Device return to distributor";
                            x.comment = "Device returned to Distributor " + x.distributor_name;
                        } else if (x.event_id == "4") {     // Device return to Admin
                            x.event_name = "Device return to Admin";
                            x.comment = "Device returned to Admin";
                        } else if (x.event_id == "5") {     // Created Certificate
                            x.event_name = "Created Certificate";
                            x.comment = "Certificate created for Customer " + x.customer_name;
                        } else if (x.event_id == "6") {     // Owner change
                            x.event_name = "Owner change";
                            x.comment = "Device owner changed for Customer " + x.customer_name;
                        } else if (x.event_id == "7") {     // Replace ( unset )
                            x.event_name = "Replace ( unset )";
                            x.comment = "";
                        } else if (x.event_id == "8") {    // Refurbished (Back to stock)
                            x.event_name = "Refurbished (Back to stock)";
                            x.comment = "Device refurbished and added to stock";
                        } else if (x.event_id == "9") {     // Fault (Out of stock)
                            x.event_name = "Fault (Out of stock)";
                            x.comment = "Device marked as fault by Admin";
                        } else if (x.event_id == "10") {     // Scraped
                            x.event_name = "Scraped";
                            x.comment = "Device scraped";
                        } else{
                            x.event_name = "Event Not Found";
                            x.comment = "";
                        }
                        device_log_value += '<tr><td>'+x.event_date+'</td><td>'+ x.s_serial_number +'</td><td><span class="badge badge-primary">'+ x.event_name +'</span></td><td>'+ x.comment +'</td><td>'+ x.user_name +'</td></tr>'
                    });
                    $('#deviceLog').html(device_log_value);
                } else {
                    $('#deviceLog').html('<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>');
                }
                $('#devRegVehicle').html(data.vehicleRegnumber);
                $('#expDate').html(data.validity_to);
                var expirOn = Date.parse(data.validity_to) - Date.now();
                if (expirOn > 0) {
                    if (expirOn > 604800000) {
                        $('#certStatus').html("<span style=\"color:green\">Active</span></br> <span style=\"color:red\"> Expire in " + Math.floor(expirOn / 86400000) + " days</span>");
                    } else {

                        $('#certStatus').html("<span style=\"color:red\"> Expire in " + Math.floor(expirOn / 86400000) + " days</span>");
                    }
                } else if(data.validity_to != undefined){
                    $('#certStatus').html(" <button class=\"btn btn-danger\">Expired </button></br><span style=\"color:red\"> Expired - " + Math.floor(expirOn / 86400000) + " days</span>")
                }else{
                    $('#certStatus').html("-");
                }


                $('#custEmail').html(data.customerEmail);
                let today = new Date();
                var date = today.getFullYear() + "-" + today.getMonth() + "-" + today.getDate();
                if (data.validity_to != undefined) {
                    $('#track_now').html(" <a href=\"/admin/check_device_data/" + date + "/00:00/23:59/" + imei_no + "\">" + "<span>Track now</span> </a>");
                }
                $('#resultOfVechStatus').show();
                $('#resultOfVechStatusEmpty').hide();
            } else {
                $('#resultOfVechStatus').hide();
                $('#resultOfVechStatusEmpty').html(gotResult.model_list.data);
                $('#resultOfVechStatusEmpty').show();
            }
            return false;

        });
    }
}


    var templates = {
        prefix: "",
        suffix: " ago",
        seconds: "less than a minute",
        minute: "about a minute",
        minutes: "%d minutes",
        hour: "about an hour",
        hours: "about %d hours",
        day: "a day",
        days: "%d days",
        month: "about a month",
        months: "%d months",
        year: "about a year",
        years: "%d years"
    };
    var template = function (t, n) {
        return templates[t] && templates[t].replace(/%d/i, Math.abs(Math.round(n)));
    };

    function formatTime(time) {

        var now = new Date();
        var seconds = ((now.getTime() - time) * .001) >> 0;
        var minutes = seconds / 60;
        var hours = minutes / 60;
        var days = hours / 24;
        var years = days / 365;

        return templates.prefix + (
            seconds < 45 && template('seconds', seconds) ||
            seconds < 90 && template('minute', 1) ||
            minutes < 45 && template('minutes', minutes) ||
            minutes < 90 && template('hour', 1) ||
            hours < 24 && template('hours', hours) ||
            hours < 42 && template('day', 1) ||
            days < 30 && template('days', days) ||
            days < 45 && template('month', 1) ||
            days < 365 && template('months', days / 30) ||
            years < 1.5 && template('year', 1) ||
            template('years', years)
        ) + templates.suffix;

    }
    
    function redirectToConsole() {
        // Change the window location to the desired URL
        var imei_no = $("#imei_no").val();
        // console.log(btoa(imei_no));
        // console.log(SITEURL);
        window.location.href = SITEURL + 'admin/check_console?imei='+ btoa(imei_no);
    }
    
    
// function returnToAdmin() {
//     // swal({
//     //     title: "Are you sure?",
//     //     // text: "You will not be able to recover this imaginary file!",
//     //     type: "warning",
//     //     showCancelButton: true,
//     //     confirmButtonColor: "#DD6B55",
//     //     confirmButtonText: "Yes",
//     //     cancelButtonText: "Cancel",
//     //     closeOnConfirm: false,
//     //     closeOnCancel: false
//     // }, function (isConfirm) {
//     //     if (isConfirm) {
//     // var imei_no = $("#imei_no").val();
//     // var req = new FormData();
//     // req.append("imei", imei_no);

//     // $.ajax({
//     //     url: SITEURL + "admin/stockReturnToAdmin",
//     //     type: "POST",
//     //     cache: !1,
//     //     data: req,
//     //     processData: !1,
//     //     contentType: !1,
//     //     success: function(datasresult) {
//     //         data = datasresult.replace(/^\s+|\s+$/g, "");
//     //         data=JSON.parse(data);
//     //         if(data.error)
//     //         {					
//     //             showWithTitleMessage(data.error,'');
//     //         }	
//     //         if(data.validation && Object.keys(data.validation).length>0)
//     //         {
//     //             var words="";
//     //             for(var i=0;i<Object.keys(data.validation).length;i++)
//     //             {
//     //                 var Obj=Object.keys(data.validation)[i];
//     //                 words+=data.validation[Obj]+"<br />";
//     //             }
//     //             swal({
//     //                 title: "<bold>Error Found</bold>",
//     //                 text: words,
//     //                 type: "error",
//     //                 html: true
//     //             }, function (isConfirm) {
                    
//     //             });
//     //         }	
                            
//     //         //Success Response
//     //         if(data.success)
//     //         {
//     //             swal({
//     //                 title: "<bold>Success</bold>",						
//     //                 type: "success",	
//     //                 html: true,
//     //                 text: "Stock return to admin successfully.",
//     //             }, function (isConfirm) {
//     //                 if(isConfirm)
//     //                 {
//     //                     location.reload();
//     //                 }
//     //             });		
//     //         } else {
//     //             swal({
//     //                 title: "<bold>Error Found</bold>",
//     //                 text: "Something Went Wrong",
//     //                 type: "error",
//     //                 html: true
//     //             }, function (isConfirm) {
                    
//     //             });
//     //         }
//     //     }
//     // })
//     // } else {
//     //     swal("Cancelled", "", "error");
//     // }
//     // });
    
//     var imei_no = $("#imei_no").val();
//     var req = new FormData();
//     req.append("imei", imei_no);

//     $.ajax({
//         url: SITEURL + "admin/stockReturnToAdmin",
//         type: "POST",
//         cache: !1,
//         data: req,
//         processData: !1,
//         contentType: !1,
//         success: function(datasresult) {
//             data = datasresult.replace(/^\s+|\s+$/g, "");
//             data=JSON.parse(data);
//             if(data.error)
//             {					
//                 showWithTitleMessage(data.error,'');
//             }	
//             if(data.validation && Object.keys(data.validation).length>0)
//             {
//                 var words="";
//                 for(var i=0;i<Object.keys(data.validation).length;i++)
//                 {
//                     var Obj=Object.keys(data.validation)[i];
//                     words+=data.validation[Obj]+"<br />";
//                 }
//                 swal({
//                     title: "<bold>Error Found</bold>",
//                     text: words,
//                     type: "error",
//                     html: true
//                 }, function (isConfirm) {
                    
//                 });
//             }	
                            
//             //Success Response
//             if(data.success)
//             {
//                 swal({
//                     title: "<bold>Success</bold>",						
//                     type: "success",	
//                     html: true,
//                     text: "Stock return to admin successfully.",
//                 }, function (isConfirm) {
//                     if(isConfirm)
//                     {
//                         location.reload();
//                     }
//                 });		
//             } else {
//                 swal({
//                     title: "<bold>Error Found</bold>",
//                     text: "Something Went Wrong",
//                     type: "error",
//                     html: true
//                 }, function (isConfirm) {
                    
//                 });
//             }
//         }
//     })
// }


// function returnToDistributor() {
//     // swal({
//     //     title: "Are you sure?",
//     //     // text: "You will not be able to recover this imaginary file!",
//     //     type: "warning",
//     //     showCancelButton: true,
//     //     confirmButtonColor: "#DD6B55",
//     //     confirmButtonText: "Yes",
//     //     cancelButtonText: "Cancel",
//     //     closeOnConfirm: false,
//     //     closeOnCancel: false
//     // }, function (isConfirm) {
//     //     if (isConfirm) {
//     //         var imei_no = $("#imei_no").val();
//     // // txttodate = txttodate_arr[0] + "-" + txttodate_arr[1] + "-" + txttodate_arr[2];
//     // var req = new FormData();
//     // req.append("imei", imei_no);
//     // $.ajax({
//     //     url: SITEURL + "admin/stockReturnToDistributor",
//     //     type: "POST",
//     //     cache: !1,
//     //     data: req,
//     //     processData: !1,
//     //     contentType: !1,
//     //     success: function(datasresult) {
//     //         data = datasresult.replace(/^\s+|\s+$/g, "");
//     //         data=JSON.parse(data);
//     //         if(data.error)
//     //         {					
//     //             showWithTitleMessage(data.error,'');
//     //         }	
//     //         if(data.validation && Object.keys(data.validation).length>0)
//     //         {
//     //             var words="";
//     //             for(var i=0;i<Object.keys(data.validation).length;i++)
//     //             {
//     //                 var Obj=Object.keys(data.validation)[i];
//     //                 words+=data.validation[Obj]+"<br />";
//     //             }
//     //             swal({
//     //                 title: "<bold>Error Found</bold>",
//     //                 text: words,
//     //                 type: "error",
//     //                 html: true
//     //             }, function (isConfirm) {
                    
//     //             });
//     //         }	
                            
//     //         //Success Response
//     //         if(data.success)
//     //         {
//     //             swal({
//     //                 title: "<bold>Success</bold>",						
//     //                 type: "success",	
//     //                 html: true,
//     //                 text: "Stock return to distributor successfully.",
//     //             }, function (isConfirm) {
//     //                 if(isConfirm)
//     //                 {
//     //                     location.reload();
//     //                 }
//     //             });		
//     //         } else {
//     //             swal({
//     //                 title: "<bold>Error Found</bold>",
//     //                 text: "Something Went Wrong",
//     //                 type: "error",
//     //                 html: true
//     //             }, function (isConfirm) {
                    
//     //             });
//     //         }
//     //     }
//     // })
//     //     } else {
//     //         swal("Cancelled", "", "error");
//     //     }
//     // });
    
//     var imei_no = $("#imei_no").val();
//     // txttodate = txttodate_arr[0] + "-" + txttodate_arr[1] + "-" + txttodate_arr[2];
//     var req = new FormData();
//     req.append("imei", imei_no);
//     $.ajax({
//         url: SITEURL + "admin/stockReturnToDistributor",
//         type: "POST",
//         cache: !1,
//         data: req,
//         processData: !1,
//         contentType: !1,
//         success: function(datasresult) {
//             data = datasresult.replace(/^\s+|\s+$/g, "");
//             data=JSON.parse(data);
//             if(data.error)
//             {					
//                 showWithTitleMessage(data.error,'');
//             }	
//             if(data.validation && Object.keys(data.validation).length>0)
//             {
//                 var words="";
//                 for(var i=0;i<Object.keys(data.validation).length;i++)
//                 {
//                     var Obj=Object.keys(data.validation)[i];
//                     words+=data.validation[Obj]+"<br />";
//                 }
//                 swal({
//                     title: "<bold>Error Found</bold>",
//                     text: words,
//                     type: "error",
//                     html: true
//                 }, function (isConfirm) {
                    
//                 });
//             }	
                            
//             //Success Response
//             if(data.success)
//             {
//                 swal({
//                     title: "<bold>Success</bold>",						
//                     type: "success",	
//                     html: true,
//                     text: "Stock return to distributor successfully.",
//                 }, function (isConfirm) {
//                     if(isConfirm)
//                     {
//                         location.reload();
//                     }
//                 });		
//             } else {
//                 swal({
//                     title: "<bold>Error Found</bold>",
//                     text: "Something Went Wrong",
//                     type: "error",
//                     html: true
//                 }, function (isConfirm) {
                    
//                 });
//             }
//         }
//     })
// }

function returnToAdmin() {
    swal({
        title: "Are you sure?",
        // text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            var imei_no = $("#imei_no").val();
            var req = new FormData();
            req.append("imei", imei_no);

            $.ajax({
                url: SITEURL + "admin/stockReturnToAdmin",
                type: "POST",
                cache: !1,
                data: req,
                processData: !1,
                contentType: !1,
                success: function (datasresult) {
                    data = datasresult.replace(/^\s+|\s+$/g, "");
                    data = JSON.parse(data);
                    if (data.error) {
                        showWithTitleMessage(data.error, '');
                    }
                    if (data.validation && Object.keys(data.validation).length > 0) {
                        var words = "";
                        for (var i = 0; i < Object.keys(data.validation).length; i++) {
                            var Obj = Object.keys(data.validation)[i];
                            words += data.validation[Obj] + "<br />";
                        }
                        swal({
                            title: "<bold>Error Found</bold>",
                            text: words,
                            type: "error",
                            html: true
                        }, function (isConfirm) {

                        });
                    }

                    //Success Response
                    if (data.success) {
                        swal({
                            title: "<bold>Success</bold>",
                            type: "success",
                            html: true,
                            text: "Stock return to admin successfully.",
                        }, function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    } else {
                        swal({
                            title: "<bold>Error Found</bold>",
                            text: "Something Went Wrong",
                            type: "error",
                            html: true
                        }, function (isConfirm) {

                        });
                    }
                }
            })
        } else {
            swal("Cancelled", "", "error");
        }
    });
}

function returnToDistributor() {
    swal({
        title: "Are you sure?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            var imei_no = $("#imei_no").val();
            // txttodate = txttodate_arr[0] + "-" + txttodate_arr[1] + "-" + txttodate_arr[2];
            var req = new FormData();
            req.append("imei", imei_no);
            $.ajax({
                url: SITEURL + "admin/stockReturnToDistributor",
                type: "POST",
                cache: !1,
                data: req,
                processData: !1,
                contentType: !1,
                success: function (datasresult) {
                    data = datasresult.replace(/^\s+|\s+$/g, "");
                    data = JSON.parse(data);
                    if (data.error) {
                        showWithTitleMessage(data.error, '');
                    }
                    if (data.validation && Object.keys(data.validation).length > 0) {
                        var words = "";
                        for (var i = 0; i < Object.keys(data.validation).length; i++) {
                            var Obj = Object.keys(data.validation)[i];
                            words += data.validation[Obj] + "<br />";
                        }
                        swal({
                            title: "<bold>Error Found</bold>",
                            text: words,
                            type: "error",
                            html: true
                        }, function (isConfirm) {

                        });
                    }

                    //Success Response
                    if (data.success) {
                        swal({
                            title: "<bold>Success</bold>",
                            type: "success",
                            html: true,
                            text: "Stock return to distributor successfully.",
                        }, function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    } else {
                        swal({
                            title: "<bold>Error Found</bold>",
                            text: "Something Went Wrong",
                            type: "error",
                            html: true
                        }, function (isConfirm) {

                        });
                    }
                }
            })
        } else {
            swal("Cancelled", "", "error");
        }
    });
}