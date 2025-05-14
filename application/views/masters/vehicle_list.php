<?php  
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="path/to/toastr.css">
<script src="path/to/toastr.js"></script>
<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<style>
.swal-text-size {
    font-size: 3.2rem !important;
    /* Adjust the font size as desired */
}

.swal2-title {
    /* font-family: Courier New, monospace; */
    /* font-style: italic; */
    font-size: 2.8rem !important;
}

.swal-box-size {
    height: 200px !important;
    width: 400px !important;

    width: 500px !important;
    /* Adjust the width as desired */
    height: 300px !important;
    /* Adjust the height as desired */
}
</style>

<style>
.glyphicon {
    line-height: 2 !important;
}

.my-swal-title {
    font-size: 4px !important;
}

.my-swal-text {
    font-size: 8px !important;
}

.pagination>li>a,
.pagination>li>span {
    border-radius: 50% !important;
    margin: 0 5px;
}

.text-left {
    display: flex;
    justify-content: flex-start;
    gap: 5px;
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
                    $redirectionBase = 'dealer';
                }
                $hideHomeFromDealer = '';
                if ((string)$user_type === '1') {
                    $hideHomeFromDealer = 'display:none;';
                }
                ?>
            </div>
            <!-- Basic Validation -->
            <!-- <form  method="post" enctype="multipart/form-data">
                <input type="file" name="userfile" />
                <input type="submit" value="Upload" />
            </form> -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                LIST OF GPS CERTIFICATES
                            </h2>
                            <!--<ul class="header-dropdown m-r--5">-->
                            <!--    <li class="dropdown">-->
                            <!--        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"-->
                            <!--            role="button" aria-haspopup="true" aria-expanded="false">-->
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
                            <div class="table-responsive">
                                <form action="<?php echo base_url() . $redirectionBase; ?>/entry_list"
                                    name="searchfilter" id="searchfilter" method="get">
                        
                                    <div class="row clearfix">
                                        <!-- <div class="col-sm-2">
                                            <div class="form-group">
                                                <input type="checkbox" id="scales" name="scales"
                                                    onclick="rcCheckFunction()"
                                                    <?php if(isset($_GET['scales'])) { echo 'checked="checked"'; } ?>>
                                                <label for="scales">Click for Date
                                                    filter</label>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <?php
                                                        $dateValue = "";
                                                        if (isset($_GET['start_date']) ? $_GET['start_date'] : '') {
                                                            $dateValue = $_GET['start_date'];
                                                        }
                                                        ?>
                                                    <input type="text" class="datetimepicker form-control"
                                                        name="start_date" id="start_date"
                                                        placeholder="Select Start date"
                                                        value="<?php echo $dateValue; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <?php
                                            $dateValue1 = "";
                                            if (isset($_GET['end_date']) ? $_GET['end_date'] : '') {
                                                $dateValue1 = $_GET['end_date'];
                                            } 
                                            ?>
                                                    <input type="text" class="datetimepicker form-control"
                                                        name="end_date" id="end_date" placeholder="Select End date"
                                                        value="<?php echo $dateValue1; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="state_id" id="state_id"
                                                        data-live-search="true">
                                                        <option value="">--Select State--</option>
                                                        <?php
                                                            foreach ($stateList as $key => $value) {
                                                                $selected = "";
                                                                if (isset($_GET['state_id']) && (string)$_GET['state_id'] === (string)$value['id']) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            ?>
                                                        <option <?php echo $selected; ?>
                                                            value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['s_name']; ?></option>
                                                        <?php  } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="veh_rto_no"
                                                        id="veh_rto_no" data-live-search="true">
                                                        <option value="">--Select RTO--</option>
                                                        <?php
                                                            foreach ($rto_list as $key => $value) {
                                                                $selected = "";
                                                                if (isset($_GET['veh_rto_no']) && (string)$_GET['veh_rto_no'] === (string)$value['rto_no']) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            ?>
                                                        <option <?php echo $selected; ?>
                                                            value="<?php echo $value['rto_no']; ?>">
                                                            <!--<?php echo $value['rto_number']; echo"-"; echo $value['rto_place']; ?>-->
                                                            <?php echo $value['rto_place'];echo  '_RTO_'; echo $value['rto_number'];?></option>
                                                        </option>
                                                        <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!--- Search---->
                                    <div class="row clearfix">
                                        <?php
                                                    $user_type = $this->session->userdata('user_type');
                                                    if ($user_type == 0 || $user_type == 4 ) { ?>
                                        <input type="hidden" name="offset" id="offset"
                                            value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search"
                                                        id="search"
                                                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ""; ?>"
                                                        name="search">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="distributor_id"
                                                        id="distributor_id" data-live-search="true">
                                                        <option value="">--Select distributor--</option>
                                                        <?php
                                                            foreach ($distributor_list as $key => $value) {
                                                                $selected = "";
                                                                if (isset($_GET['distributor_id']) && (string)$_GET['distributor_id'] === (string)$value['user_id']) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            ?>
                                                        <option <?php echo $selected; ?>
                                                            value="<?php echo $value['user_id']; ?>">
                                                            <?php echo $value['user_name']; ?></option>
                                                        <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="dealer_id"
                                                        id="dealer_id" data-live-search="true">
                                                        <option value="">--Select dealer--</option>
                                                        <?php
                                                            foreach ($dealer_list as $key => $value) {
                                                                $selected = "";
                                                                if (isset($_GET['dealer_id']) && (string)$_GET['dealer_id'] === (string)$value['user_id']) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            ?>
                                                        <option <?php echo $selected; ?>
                                                            value="<?php echo $value['user_id']; ?>">
                                                            <?php echo $value['user_name']; ?></option>
                                                        <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- dealer -->
                                        <?php } ?>
                                        <?php
                                                $user_type = $this->session->userdata('user_type');
                                                if ($user_type == 1) { ?>
                                        <input type="hidden" name="offset" id="offset"
                                            value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search" id="search"
                                                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ""; ?>"
                                                        name="search">
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <!-- dealer end-->

                                        <!-- distributor -->

                                        <?php
                                                $user_type = $this->session->userdata('user_type');
                                                if ($user_type == 2) { ?>
                                        <input type="hidden" name="offset" id="offset"
                                            value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search"
                                                        id="search"
                                                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ""; ?>"
                                                        name="search">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="dealer_id"
                                                        id="dealer_id" data-live-search="true">
                                                        <option value="">--Select dealer--</option>
                                                        <?php
                                                            foreach ($dealer_list as $key => $value) {
                                                                $selected = "";
                                                                if (isset($_GET['dealer_id']) && (string)$_GET['dealer_id'] === (string)$value['user_id']) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            ?>
                                                        <option <?php echo $selected; ?>
                                                            value="<?php echo $value['user_id']; ?>">
                                                            <?php echo $value['user_name']; ?></option>
                                                        <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                                }
                                                    ?>
                                        <!-- distributor end-->
                                        <?php 
                                    $user_id = $this->session->userdata('user_id');
                                    $user_type = $this->session->userdata('user_type');
                                    if ((string) $user_type == '1') {
                                        $_GET['dealer_id'] = $user_id;
                                        ?>
                                     
                                    <input type="hidden" id="dealer_id" class="form-control" name="dealer_id" value="<?php echo $user_id; ?>">

                                    <?php } 
                                       if ((string) $user_type == '2') { 
                                        $_GET['distributor_id'] = $user_id;
                                        ?> 
                                        <input type="hidden" id="distributor_id" name="distributor_id" class="form-control" value="<?php echo $user_id; ?>">

                                    <?php } ?>


                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <div>
                                                    <button class="btn btn-primary waves-effect" type="submit"
                                                        id="searchfiltersubmit"
                                                        name="searchfiltersubmit">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!---- Search ---->

                                <table id="mytable" class="table table-bordred table-striped"
                                    style="width: 2800px !important;">

                                    <thead>
                                        <th>#</th>
                                        <th>Serial No</th>
                                        <!--th>Invoice No</th-->
                                        <th>Rc No</th>
                                        <th>IMEI No</th>
                                        <th style="width: 242px;">Customer Name</th>
                                        <th>Phone No</th>
                                        <?php if (check_permission($user_type, 'cerificate_edit')) { ?>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                        <?php } ?>
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
                                            <td><?php
                                                $href = base_url() . "admin/device_qrcode?serialNumber=" . base64_encode($value['s_serial_number']);
                                                echo '<a href="' . $href . '">' . $value['s_serial_number'] . '</a>';
                                                ?>
                                            </td>
                                            <td><?php
                                                    if ($value['veh_rc_no'] == "") {
                                                     echo "NEW REGISTRATION";
                                                    } else {
                                                     echo $value['veh_rc_no'];
                                                 } ?></td>
                                            <td><?php echo $value['s_imei']; ?></td>
                                            <td><?php echo $value['veh_owner_name']; ?></td>
                                            <td><?php echo $value['veh_owner_phone']; ?></td>
                                            <?php
                                                                $channel = isset($value['veh_channel']) ? $value['veh_channel'] : 0;
                                                                $M = "";
                                                                if ((string)$channel == 1) {
                                                                    $M = AWS_S3_BUCKET_URL . "public/images/android.png";
                                                                } else {
                                                                    $M = AWS_S3_BUCKET_URL . "public/images/laptop.png";
                                                                }
                                                                ?>

                                            <?php
                                                                $pdfEncode = base64_encode(base64_encode(base64_encode($value['veh_id'])));
                                                                $href = base_url() . "admin/downloadwebpdf?id=" . $pdfEncode;
                                                                $user_type = $this->session->userdata('user_type');

                                                                $enc_veh_Id = base64_encode($value['veh_id']);

                                                                ?>
                                            <td class="text-left">

                                                <?php if (check_permission($user_type, 'cerificate_edit')) { ?>
                                                <img src=" <?php echo $M ?>" alt="image" width="24px" />

                                                <a class='btn btn-Basic btn-xs'
                                                    href="<?php echo base_url()."admin/edit_entry/?q=" . $enc_veh_Id; ?>">
                                                    <span class="glyphicon glyphicon-edit" title="Edit"></span>
                                                </a>

                                                <?php } ?>
                                                <?php if (check_permission($user_type, 'cerificate_download')) { ?>
                                                <a href="javascript:void(0)" class="btn btn-success btn-xs"
                                                    onClick="return ShowPdfGenerateMail('<?php echo $href; ?>','<?php echo $value["veh_id"]; ?>')">
                                                    <span class="glyphicon glyphicon-download-alt"
                                                        title="Download"></span></a>
                                                <?php } ?>
                                                <?php if (check_permission($user_type, 'cerificate_interchange')) { ?>
                                                <a class="btn btn-primary btn-xs"
                                                    href="<?php echo base_url() . "admin/inter_change_device/?q=" . $enc_veh_Id; ?>">
                                                    <span class="glyphicon glyphicon-refresh"
                                                        title="Inter_Change_Device"></span></a>
                                                <?php } ?>
                                                <?php if (check_permission($user_type, 'cerificate_interchange')) { ?>
                                                <a class="btn btn-warning btn-xs"
                                                    href="<?php echo base_url() . "apicontroller/owner_inter_change/?q=" . $enc_veh_Id; ?>">
                                                    <span class="glyphicon glyphicon-transfer"
                                                        title="Ownership_Interchange"></span></a>
                                                <?php } ?>
                                                <?php if (check_permission($user_type, 'cerificate_delete')) { ?>
                                                <a class='btn btn-danger btn-xs'
                                                    onClick="showConfirmmesage(<?php echo $value['veh_id']?>)">
                                                    <span class="glyphicon glyphicon-trash"
                                                        title="Delete Certificate"></span>
                                                </a>
                                                <?php } ?>

                                                <!-- <?php if (check_permission($user_type, 'cerificate_remove')) { ?>
                                                 							 <a class='btn btn-danger btn-xs' href="javascript:void(0)" onClick="showConfirmmesage(<?php echo $value['veh_id']; ?>)">
                                                 								<span class="glyphicon glyphicon-trash"></span>
                                                 							 </a>
                                                  <?php } ?>	 -->

                                            </td>
                                        </tr>
                                        <?php
                                                            $sno++;
                                                        }
                                                    } else {
                                                        ?>
                                        <tr style=" text-align: center;">
                                            <td colspan="6">No Records Found</td>
                                        </tr>
                                        <?php
                                                    }
                                                    ?>

                                    </tbody>

                                </table>
                                <div style="float:right;" id="pageformat"></div>
                                <?php
                                // print_r($_SESSION);
                                $user_type = $this->session->userdata('user_type');
                                if ((string) $user_type === '0' || (string) $user_type === '1' || (string) $user_type === '2') { ?>

                                    <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary waves-effect"
                                        onclick="javascript:downloadbutton();">Excel<i
                                            class="fa fa-fw fa-download"></i></button>&nbsp;&nbsp;
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
        <!-- #END# Basic Validation -->
    </section>

    <script src="<?php echo base_url() ?>public/js/pages/function/dealer_list.js"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>
    <script>
    function ShowPdfGenerateMail(url, veh_id) {
        console.log(url)
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
        var startDate = "YYYY-MM-DD"
        var EndDate = "YYYY-MM-DD"
        var date1 = new Date(startDate);
        var date2 = new Date(EndDate);
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        const date = new Date();

        let currentDay = String(date.getDate()).padStart(2, '0');

        let currentMonth = String(date.getMonth() + 1).padStart(2, "0");

        let currentYear = date.getFullYear();

        // we will display the date as DD-MM-YYYY 

        let currentDate = `${currentYear}-${currentMonth}-${currentDay}`;
        // let currentDate = `${currentDay}-${currentMonth}-${currentYear}`;

        let currentDate2 = `${currentYear}-${currentMonth}-01`;

        console.log("The current date is " + currentDate)
        console.log("The current date is " + currentDate2)

        // console.log(diffTime + " milliseconds");
        // console.log(diffDays + " days");
        console.log(date2)
        if (diffDays >= 90) {
            document.getElementById("start_date").value = currentDate2
            document.getElementById("end_date").value = currentDate
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


    function rcCheckFunction() {
        var checkBox = document.getElementById("scales");
        console.log(checkBox)
        if (checkBox.checked == true) {
            localStorage.setItem("checkedbox", $(this).prop('checked'));
            return document.getElementById("scales").value = "on";
        }
        if (checkBox.checked != true) {
            return document.getElementById("scales").value = "off";
        }
    }



    $(function() {
        $('#pageformat').pagination({
            items: '<?php echo $totalNoOfVehicles; ?>',
            itemsOnPage: '<?php echo LIST_PAGE_LIMIT; ?>',
            cssStyle: 'light-theme',
            onPageClick: function(no) {
                var offsetValue = $('#offset').val();
                // console.log(checkBox)
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
            $('#pageformat').pagination('selectPage', '1');
        } else {
            // $('#offset').val(no);
            $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
        }
        <?php
            }
            ?>
    });
    </script>
</body>

</html>