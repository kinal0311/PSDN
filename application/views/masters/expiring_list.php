<?php
            //echo $value['veh_id'];
            //echo "<pre>"; print_r($listofvehicles); exit;
            //echo "<pre>"; print_r($listofvehicles[0]['veh_id']); exit;
            //echo hi(); exit;
            //$enc_veh_Id = urlencode($this->encrypt->encode($listofvehicles[0]['veh_id']));

            //echo "id".$enc_veh_Id; exit;

            $this->load->view('common/admin_login_header'); ?>
<?php
$user_type = $this->session->userdata('user_type');



?>
<!-- Bootstrap Material Datetime Picker Css -->
<!-- <link
     href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
     rel="stylesheet" /> -->

<!-- Wait Me Css -->
<link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<style>
    .glyphicon {
        line-height: 2 !important;
    }

    .pagination>li>a,
    .pagination>li>span {
        border-radius: 50% !important;
        margin: 0 5px;
    }
</style>

<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>
    <?php $this->load->view('common/dashboard_top_bar'); ?>
    <?php $this->load->view('common/left_side_bar'); ?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    Vehicle List
                </h2>

                <?php
                $user_type = $this->session->userdata('user_type');
                $redirectionBase = 'admin';
                if ((string)$user_type === '1') {
                    $redirectionBase = 'admin';
                }
                $hideHomeFromDealer = '';
                if ((string)$user_type === '1') {
                    $hideHomeFromDealer = 'display:none;';
                }
                ?>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                EXPIRING SUBSCRIPTION REPORTS
                            </h2>
                            <!--<ul class="header-dropdown m-r--5">-->
                            <!--    <li class="dropdown">-->
                            <!--        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">-->
                            <!--            <i class="material-icons">more_vert</i>-->
                            <!--        </a>-->
                            <!--        <ul class="dropdown-menu pull-right">-->
                            <!--            <li><a href="<?php echo base_url() . 'admin/create_new_entry'; ?>">Create</a>-->
                            <!--            </li>-->
                            <!--        </ul>-->
                            <!--    </li>-->
                            <!--</ul>-->
                        </div>

                        <div class="body">

                            <!-- <div class="table-responsive"> -->
                            <form action="<?php echo base_url() . $redirectionBase; ?>/expiring_list" name="searchfilter" id="searchfilter" method="get" />

                            <div class="table-responsive">
                                <!--- Search---->
                                <!-- user type =0 =>admin -->
                                <?php
                                $user_type = $this->session->userdata('user_type');
                                if ($user_type == 0 || $user_type == 4) { ?>
                                    <form action="<?php echo base_url() . $redirectionBase; ?>/expiring_list" name="searchfilter" id="searchfilter" method="get" />
                                    <input type="hidden" name="offset" id="offset" value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">
                                    <div class="row clearfix">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" placeholder="Search By Serial no, Phone" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ""; ?>" name="search">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="distributor_id" id="distributor_id" data-live-search="true">
                                                        <option value="">--Select distributor--</option>
                                                        <?php
                                                        foreach ($distributor_list as $key => $value) {
                                                            $selected = "";
                                                            if (isset($_GET['distributor_id']) && (string)$_GET['distributor_id'] === (string)$value['user_id']) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                        ?>
                                                            <option <?php echo $selected; ?> value="<?php echo $value['user_id']; ?>">
                                                                <?php echo $value['user_name']; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="dealer_id" id="dealer_id" data-live-search="true">
                                                        <option value="">--Select dealer--</option>
                                                        <?php
                                                        foreach ($dealer_list as $key => $value) {
                                                            $selected = "";
                                                            if (isset($_GET['dealer_id']) && (string)$_GET['dealer_id'] === (string)$value['user_id']) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                        ?>
                                                            <option <?php echo $selected; ?> value="<?php echo $value['user_id']; ?>">
                                                                <?php echo $value['user_name']; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    <?php } ?>
                                    
                                        <div class="col-sm-2">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="s_state_id"
                                                        id="s_state_id" data-live-search="true">
                                                        <option value="">--Select State--</option>
                                                        <?php
                                                            foreach ($stateList as $key => $value) {
																$selected = "";
                                                                if (isset($_GET['s_state_id']) && (string)$_GET['s_state_id'] === (string)$value['id']) {
																	$selected = 'selected="selected"';
                                                                }
                                                            ?>
                                                        <option <?php echo $selected; ?>
                                                            value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['s_name'];?></option>
                                                        </option>
                                                        <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="days"
                                                        id="days" data-live-search="true">
                                                        <option value="">--Select Days left--</option>
                                            
                                                        <option <?php echo ($_GET['days'] == '7') ? 'selected="selected"' : ""; ?> value="7" >7 Days</option>
                                                        <option <?php echo ($_GET['days'] == '15') ? 'selected="selected"' : ""; ?> value="15">15 Days</option>
                                                        <option <?php echo ($_GET['days'] == '30') ? 'selected="selected"' : ""; ?> value="30">30 Days</option>
                                                        <option <?php echo ($_GET['days'] == '45') ? 'selected="selected"' : ""; ?> value="45">45 Days</option>
                                                        
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        

                                    <!-- user type =1 =>dealer -->
                                    <?php
                                    $user_type = $this->session->userdata('user_type');
                                    if ($user_type == 1) { ?>
                                        <form action="<?php echo base_url() . $redirectionBase; ?>/expiring_list" name="searchfilter" id="searchfilter" method="get" />
                                        <input type="hidden" name="offset" id="offset" value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">
                                        <div class="row clearfix">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" placeholder="Search By Serial no, Phone" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ""; ?>" name="search">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <div>
                                                    <button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" name="searchfiltersubmit">Search</button>
                                                </div>
                                            </div>
                                        </div>

                                        </div>
                                        </form>

                                        <!---- Search ---->

                                        <table id="mytable" class="table table-bordred table-striped">

                                            <thead>
                                                <th>#</th>
                                                <th style="width: 150px;">Customer Name</th>
                                                <th style="width: 150px;">Phone No</th>
                                                <!-- <th>Address</th> -->
                                                <th style="width: 150px;">Serial No</th>
                                                <th style="width: 20px;height: 80px;">Dealer</th>
                                                <?php
                                                $user_type = $this->session->userdata('user_type');
                                                if ($user_type == 0) { ?>
                                                    <th style="width: 150px;">Distributor</th>
                                                <?php } ?>
                                                <th style="width: 150px;">Subscription starting</th>
                                                <th style="width: 150px;">Subscription ending</th>
                                                <th style="width: 150px;">Days to Expiry</th>

                                            </thead>
                                            <tbody>
                                                <?php
                                                if (count($listofvehicles) > 0) {

                                                    $sno = 1;
                                                    if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
                                                        $sno = (((int)$_GET['offset'] - 1) * LIST_PAGE_LIMIT) + 1;
                                                    }
                                                    foreach ($listofvehicles as $key => $value) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $sno; ?></td>
                                                            <td><?php echo $value['veh_owner_name']; ?></td>
                                                            <td><?php echo $value['veh_owner_phone']; ?></td>
                                                            <!-- <td><?php echo $value['veh_address']; ?></td> -->
                                                            <td><?php echo $value['s_serial_number']; ?></td>
                                                            <td style="width: 150px;"><?php echo $value['dealer_name']; ?></td>
                                                            <?php
                                                            $user_type = $this->session->userdata('user_type');
                                                            if ($user_type == 0) { ?>
                                                                <td><?php echo $value['distributor_name']; ?></td>
                                                            <?php } ?>
                                                            <td><?php echo $value['validity_from']; ?></td>
                                                            <td><?php echo $value['validity_to']; ?></td>
                                                            <?php $date1 = new DateTime($value['validity_to']);
                                                            $date2 = new DateTime(date('Y-m-d H:i:s'));
                                                            $leftDays = 0;
                                                            if ($date1 < $date2) {
                                                                $ansDays = $date1->diff($date2)->days;
                                                                $leftDays = $ansDays;
                                                            } else {
                                                                $leftDays = $date1->diff($date2)->days;
                                                            }
                                                            ?>
                                                            <td><?php echo $leftDays ?></td>
                                                            <!-- <td><?php echo $value['veh_owner_name']; ?></td> -->
                                                            <!-- <td><?php
                                                                        $href = base_url() . "admin/device_qrcode?serialNumber=" . base64_encode($value['veh_owner_name']);
                                                                        echo '<a href="' . $href . '">' . $value['s_serial_number'] . '</a>';
                                                                        ?>
                                             </td> -->
                                                            <!--td><?php echo $value['veh_invoice_no']; ?></td-->
                                                            <!--<td><?php //echo $value['veh_rc_no']; 
                                                                    ?></td>-->
                                                            <!-- <td><?php
                                                                        if ($value['veh_rc_no'] == "") {
                                                                            echo "NEW REGISTRATION";
                                                                        } else {
                                                                            echo $value['veh_rc_no'];
                                                                        } ?></td>
                                             <td><?php echo $value['veh_owner_name']; ?></td>
                                             <td><?php echo $value['veh_owner_phone']; ?></td>
                                             <?php
                                                        $channel = isset($value['veh_channel']) ? $value['veh_channel'] : 0;
                                                        $M = "";
                                                        if ((string)$channel == 1) {
                                                            $M = base_url() . "public/images/android.png";
                                                        } else {
                                                            $M = base_url() . "public/images/laptop.png";
                                                        }
                                                ?>

                                             <?php
                                                        $pdfEncode = base64_encode(base64_encode(base64_encode($value['veh_id'])));
                                                        $href = base_url() . "admin/downloadwebpdf?id=" . $pdfEncode;
                                                        $user_type = $this->session->userdata('user_type');

                                                        $enc_veh_Id = base64_encode($value['veh_id']);

                                                ?> -->

                                                        </tr>
                                                    <?php
                                                        $sno++;
                                                    }
                                                } else {
                                                    ?>
                                                    <tr style=" text-align: center;">
                                                        <td colspan="10">No Records Found</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>

                                            </tbody>

                                        </table>
                                        <div style="float:right;" id="pageformat"></div>
                                        <div class="col-sm-1">
                                            <!-- <button type="button" class="btn btn-primary waves-effect" onclick="javascript:downloadbuExceltton();"><i -->
                                            <!-- class="fa fa-fw fa-download"></i></button>&nbsp;&nbsp; -->

                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>


                </div>
                <!-- #END# Basic Validation -->

            </div>
    </section>

    <script src="<?php echo base_url() ?>public/js/pages/function/expiry_list.js"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>
    <script>
        function ShowPdfGenerateMail(url, veh_id) {
            swal({
                title: "Download Pdf",
                text: "Click here.<a target='_blank' href='" + url + "'>Download it</a><br />",
                //    type: "input",
                html: true,
                //  showCancelButton: true,
                //  closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Enter the email address to get pdf."
            }, function(inputValue) {

                return true;
            });
        }
    </script>

    <script>
        function checkDiff(val) {
            var startDate = document.getElementById("start_date").value
            var EndDate = document.getElementById("end_date").value
            var date1 = new Date(startDate);
            var date2 = new Date(EndDate);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            // console.log(diffTime + " milliseconds");
            // console.log(diffDays + " days");
            // console.log(diffDays)
            if (diffDays >= 90) {
                showWithTitleMessage('Please Select Date range between 3Months');

            }
        }
    </script>

    <script>
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        }


        $(function() {
            $('#pageformat').pagination({
                items: '<?php echo $totalNoOfVehicles; ?>',
                itemsOnPage: '<?php echo LIST_PAGE_LIMIT; ?>',
                cssStyle: 'light-theme',
                onPageClick: function(no) {
                    var offsetValue = $('#offset').val();
                    if (offsetValue == no) {

                    } else {
                        $('#offset').val(no);
                        $('#searchfiltersubmit').trigger('click');
                    }
                }
            });
            <?php
            if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
            ?>
                $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
                if ('<?php echo intval($totalNoOfVehicles); ?>' < 25) {
                    console.log("<25");
                    $('#pageformat').pagination('selectPage', '1');
                } else {
                    console.log(">25");
                    // $('#offset').val(no);
                    $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
                }
            <?php
            }
            ?>
        });

		$(document).ready(function(){
			$('[name=s_country_id]').on('change', function () {
		var value = $(this).val();
		if (value === '') {
			return true;
		}
		// console.log("data", value);
		$.post(SITEURL + "admin/getStateByCountryById", { 'id': value }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.state_list && data.state_list.length === 0) {
				showWithTitleMessage('No Records Found', "Selected Country Doesn't have any State records.");
			}
			var html = '';
			html = '<option value="" selected="selected">--Select State--</option>';
			if (data.state_list && data.state_list.length) {
				$.each(data.state_list, function (resKey, resValue) {
					html += '<option value="' + resValue.id + '">' + resValue.s_name + '</option>';
				});
			}
			$('#s_state_id').html(html);
			$('#s_state_id').selectpicker('refresh');
		});
    	
    	});
		});
    </script>
</body>

</html>