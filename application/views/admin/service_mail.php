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

<link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />


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
                                LIST OF EMAIL
                            </h2>

                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <form id="form_validation" method="POST" enctype="multipart/form-data"
                                    action="<?php echo base_url() . $redirectionBase; ?>/service_mail"
                                    name="searchfilter" id="searchfilter" method="get">
                                    <div class="row clearfix">
                                        <input type="hidden" name="offset" id="offset"
                                            value="<?php echo isset($_GET['offset']) ? $_GET['offset'] : 0; ?>">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="email" class="form-control"
                                                        placeholder="Enter New Email address" id="mail" name="mail">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <div>
                                                    <button class="btn btn-primary waves-effect" type="submit"
                                                        id="searchfiltersubmit" name="searchfiltersubmit">Add
                                                        Email</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <table id="mytable" class="table table-bordred table-striped"
                                    style="width: 2800px !important;">

                                    <thead>
                                        <th>#</th>
                                        <th>Email address</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            if (count($mail_list) > 0) {

                                                $sno = 1;
                                                if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
                                                    $sno = (((int)$_GET['offset'] - 1) * LIST_PAGE_LIMIT) + 1;
                                                }
                                                foreach ($mail_list as $key => $value) {
                                            ?>
                                        <tr>
                                            <td><?php echo $sno; ?></td>
                                            <td><?php echo $value['email_address']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>
                                            <td class="text-left">

                                                <?php if (check_permission($user_type, 'cerificate_edit')) { ?>


                                                <a class="btn btn-danger btn-sm"
                                                    onClick="showConfirmmesage(<?php echo $value['id']?>)">
                                                    <span class="glyphicon glyphicon-trash"
                                                        title="Delete Certificate"></span>
                                                </a>
                                                <?php } ?>

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
                            </div>
                        </div>

                    </div>
                </div>
                <!-- </div> -->


                <!-- </div> -->
                <!-- #END# Basic Validation -->

            </div>
        </div>
    </section>

    <script src="<?php echo base_url() ?>public/js/pages/function/service_mail.js"></script>
    <?php $this->load->view('common/admin_login_css_js'); ?>

    <script>
    $(function() {
        $('#pageformat').pagination({
            items: '<?php echo $mail_list_count; ?>',
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
        if ('<?php echo intval($mail_list_count); ?>' < 25) {
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