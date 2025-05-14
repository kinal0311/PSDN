<?php $this->load->view('common/admin_login_header'); ?>
<?php
$user_type=$this->session->userdata('user_type');
?>
<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

<!-- Wait Me Css -->
<link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />
<!-- Font Awesome -->
<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/superadmin/font-awesome.min.css">
<!--<link rel="stylesheet"
      href="http://www.psdn.live/public/frontend/bower_components/font-awesome/css/font-awesome.min.css">-->
<!-- Bootstrap Select Css -->
<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<style>
    .glyphicon {
        line-height: 2 !important;
    }
    .pagination>li>a, .pagination>li>span { border-radius: 50% !important;margin: 0 5px;}
</style>


<style>
    .panel {
        margin-bottom: 0px;
    }



    #savedHistory tr {
        vertical-align: middle
    }

    tbody tr td a {
        font-size: 18px;
    }

    .removeItem {
        color: red;
    }

    .view {
        color: #535353;
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
                Saved History Reference
            </h2>
        </div>
        <!-- Basic Validation -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Saved History Reference
                        </h2>

                    </div>
                    <div class="body">


                        <div class="table-responsive">
                            <!--- Search---->

                            <!---- Search ---->



                            <table id="mytable" class="table table-bordred table-striped">

                                <thead>
                                <th scope="col">#</th>
                                <th scope="col">IMEI</th>
                                <th scope="col">Saved On</th>
                                <th scope="col">From</th>
                                <th scope="col">To</th>
                                <th scope="col">View</th>
                                <th scope="col">Delete</th>
                                </thead>
                                <tbody id="savedHistory">

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->

        </div>
</section>

<!--- Model Dialog ---->


<?php $this->load->view('common/admin_login_css_js'); ?>
<!--<script src="--><?php //echo base_url() ?><!--public/js/pages/function/vehiclehistory.js"></script>-->


<script>
    var base_url = "<?php echo base_url() ?>";
</script>
<script src="<?php echo base_url() ?>public/js/pages/function/saved_history.js?t=<?php echo time(); ?>"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx3BvK2E1sHk6jTJGF8ty7Brkh-nP4gd4&callback=initMap"
        async defer></script>
</body>
</html>



