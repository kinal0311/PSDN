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
<!-- <link rel="stylesheet" href="path/to/toastr.css">
<script src="path/to/toastr.js"></script> -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<style>
  .swal-text-size {
    font-size: 3.2rem !important; /* Adjust the font size as desired */
  }

  .swal2-title {
  /* font-family: Courier New, monospace; */
  /* font-style: italic; */
  font-size: 2.8rem !important;
  }

  .swal-box-size {
    height: 200px !important;
    width: 400px !important;

    width: 500px !important; /* Adjust the width as desired */
    height: 300px !important; /* Adjust the height as desired */
  }

</style>

<style>
.glyphicon {
    line-height: 2 !important;
}

.my-swal-title {
  font-size: 4px  !important;
}

.my-swal-text {
  font-size: 8px  !important;
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
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                LIST OF USED DEVICES
                            </h2>
                            
                        </div>
                    <div class="body">
                        <div class="table-responsive">
                                <form action="<?php echo base_url() ?>admin/used_device_list"
                                    name="searchfilter" id="searchfilter" method="get">
                                        <div class="row clearfix">
                                                <input type="hidden" name="offset" id="offset"
                                                value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">           
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search by IMEI or Serial Number"
                                                        id="search"
                                                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ""; ?>"
                                                        name="search">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- dealer end-->

                                        <!-- distributor -->
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

                                <table id="mytable" class="table table-bordred table-striped" style="width: 2800px !important;">

                                    <thead>
                                        <th>#</th>
                                        <th>Serial Number</th>
                                        <th>IMEI</th>
                                        <th>ICCID</th>
                                        <!-- <th>Distributor Name</th>
                                        <th>Dealer Name</th> -->
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <?php
                                                    if (count($device) > 0) {

                                                        $sno = 1;
                                                        if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
                                                            $sno = (((int)$_GET['offset'] - 1) * LIST_PAGE_LIMIT) + 1;
                                                        }
                                                        foreach ($device as $key => $value) {
                                                    ?>
                                        <tr>
                                            <td><?php echo $sno; ?></td>
                                            <td><?php echo $value['s_serial_number']; ?></td>
                                            <!-- <td>
                                                <?php
										        $href=base_url()."admin/device_qrcode?imei=".base64_encode($value['s_imei']);
										        echo '<a href="'.$href.'">'.$value['s_imei'].'</a>'; 
									             ?>
                                            </td> -->
                                            <td><?php echo $value['s_imei']; ?></td>
                                            <td><?php echo $value['s_iccid']; ?></td> 

                                            <!-- <td><?php echo $value['distributor_name']; ?></td>
                                            <td><?php echo $value['dealer_name']; ?></td> -->
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
                                <div class="col-sm-1">
                                    </div>
                                </div>
                            </div>
                </div>
    
            </div>
        </div>
    </section>

    <?php $this->load->view('common/admin_login_css_js'); ?>
    
    <script>
    
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


