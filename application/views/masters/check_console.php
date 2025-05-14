<?php $this->load->view('common/admin_login_header'); ?>
<?php
 $user_type=$this->session->userdata('user_type');
 ?>
<script type="text/javascript">
var user_type = '<?php echo $user_type; ?>';
</script>

<style>
/*#apiResponseContainer {*/
/*    background-color: black;*/
/*    color: white;*/
/*    padding: 15px;*/
/*    overflow: auto;*/
/*}*/

#apiResponseContainer {
    background-color: black;
    color: white;
    padding: 15px;
    overflow: auto;
    display: none;
    height: 300px;
}


.event-title {
    text-align: left;
    display: inline-block;
    width: 100px;
    font-weight: bold;
}

.value{
    display: flex;
    align-items: flex-start;
    justify-content: space-around;
}

.event-value {
    text-align: left;
    display: inline-block;
    width: calc(90% - 50px);
    /* Adjust the width as needed */
    font-weight: normal;
    word-wrap: break-word;
    /* Allow long words to break and wrap */
}

#apiResponseContainer hr {
    border-color: white;
}
</style>

<!-- Bootstrap Material Datetime Picker Css -->

<link href="<?php echo base_url() ?>public/css/check_status_table.css" rel="stylesheet">
<link
    href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
    rel="stylesheet" />



<!-- Wait Me Css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />



<!-- Bootstrap Select Css -->

<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">

    <?php $this->load->view('common/top_search_bar'); ?>

    <?php $this->load->view('common/dashboard_top_bar'); ?>

    <?php $this->load->view('common/left_side_bar'); ?>

    <section class="content">

        <div class="container-fluid" style="padding: 0px">

            <div class="block-header" style="display:none;">

                <h2>

                    Create New Company

                </h2>

            </div>

            <!-- Basic Validation -->

            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">

                        <div class="header">

                            <h2>Check Console</h2>

                        </div>

                        <div class="body">

                            <form action="<?php echo base_url() ?>device/registered_data" name="searchfilter"
                                id="searchfilter" method="get" />
                            <input type="hidden" name="offset" id="offset"
                                value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" placeholder="Search by IMEI"
                                                id="search" name="search" required
                                                
                                                <?php echo isset($imei) ? 'value="' . $imei . '"' : ''; ?>>

                                            <span class="help-block imei" id="imeierror"></span>
                                        </div>
                                    </div>
                                        <div id="imeiAlert" style="color: red; display: none;">IMEI field is empty. Please enter an IMEI.</div>
                                    </div>

                                <!-- <div class="col-sm-4">
												<div class="form-group">
													<div class="">
														<button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" name="searchfiltersubmit">Search</button>
													</div>
												</div>
											</div> -->
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="">
                                            <button class="btn btn-primary waves-effect" type="button"
                                                id="searchfiltersubmit" name="searchfiltersubmit"
                                                onclick="addTimeToSession();makeApiRequest();">Search</button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            </form>


                            <div id="apiResponseContainer">
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- #END# Basic Validation -->



        </div>

    </section>



    <!--- Model Dialog --->

    <!-- Default Size -->

    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>

                </div>

                <div class="modal-body" id="defaultModalBody">



                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK</button>

                </div>

            </div>

        </div>

    </div>

    <!--- Model Dialog ---->

    <script>
    


    </script>

    <script src="<?php echo base_url() ?>public/js/pages/function/check_console.js?t=<?php echo time(); ?>"></script>

    <?php $this->load->view('common/admin_login_css_js'); ?>

</body>

</html>