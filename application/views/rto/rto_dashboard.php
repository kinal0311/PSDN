 <?php $this->load->view('common/admin_login_header'); ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	 <?php $this->load->view('common/dashboard_top_bar'); ?> 
  <?php $this->load->view('rto/rto_left_side_bar'); ?>


    <section class="content">
        <div class="container-fluid">
           
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Today's Entry Details</h2>                           
                        </div>
                        

                        <!--- table Start-- -->
                         <div class="body">
                             <div class="table-responsive">
                    <!--- Search---->
                                <form action="" name="searchfilter"  id="searchfilter" method="get" />             
                                                      
                                        <div class="row clearfix">
                                           
                                             <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div class="form-line">    

                                                        <input type="text" class="datetimepicker form-control"  name="start_date"  id="start_date" value="<?php echo $selectedReportDate; ?>" placeholder="Please Choose Date.." >
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <div class="form-line">
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
                       <th>Serial No</th>
                       <th>Invoice No</th>
                       <th>Rc No</th>                     
                       <th>Chassis No</th>                     
                       <th>Action</th>                                            
                   </thead>
                    <tbody>  
                        <?php
                if(count($listofvehicles)>0)
                {
                        
                        $sno=1;
                        foreach($listofvehicles as $key=>$value)
                        {                           
                            
                        ?>
                        <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $value['s_serial_number']; ?></td>
                        <td><?php echo $value['veh_invoice_no']; ?></td>
                        <td><?php echo $value['veh_rc_no']; ?></td>
                        <td><?php echo $value['veh_chassis_no']; ?></td>
                    
                        <?php
                            $pdfEncode=base64_encode(base64_encode(base64_encode($value['veh_id'])));
                            $href=base_url()."admin/downloadwebpdf?id=".$pdfEncode;
                        ?>
                        <td class="text-left">                                               
                            <a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="return ShowPdfGenerateMail('<?php echo $href; ?>','<?php echo $value["veh_id"]; ?>')">
                                <span class="glyphicon glyphicon-download-alt"></span></a>
                        </td></tr>
                        <?php   
                        $sno++;
                        }
                }else{
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

                        <!--- table End---->



                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog ---->
	
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/rto_details.js"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
    <script>
    function ShowPdfGenerateMail(url,veh_id) {
        swal({
            title: "Download Pdf",
            text: "Click here.<a target='_blank' href='"+url+"'>Download it</a><br />",
        //    type: "input",
             html: true,
          //  showCancelButton: true,
          //  closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Enter the email address to get pdf."
        }, function (inputValue) {
           
            return true;
        });
    }
    </script>
    <script>    
        $(function() {
                $('#pageformat').pagination({
                    items: '<?php echo $totalNoOfVehicles; ?>',
                    itemsOnPage: '<?php echo LIST_PAGE_LIMIT; ?>',
                    cssStyle: 'light-theme',
                    onPageClick:function(no){
                        var offsetValue=$('#offset').val();
                        if(offsetValue==no)
                        {
                            
                        }else{
                            $('#offset').val(no);                           
                            $('#searchfiltersubmit').trigger('click');
                        }
                    }
                });
                <?php
                if(isset($_GET['offset']) && (int)$_GET['offset']>0)
                {
                ?>
                $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
                <?php
                }
                ?>
        });
    </script>
</body>
</html>