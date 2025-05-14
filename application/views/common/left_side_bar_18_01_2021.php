<!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <?php   
                    $email=$this->session->userdata('user_email');
                    $currentActivePage=$_SESSION['currentActivePage'];
                    $user_type=$this->session->userdata('user_type');
                    $user_info=$this->session->userdata('user_info');
                    $user_phone=$this->session->userdata('user_phone');
                    $user_name=$this->session->userdata('user_name');
                    $user_photo=$this->session->userdata('user_photo');
                    if(!isset($user_photo) || strlen($user_photo)===0 || (string)$user_photo==="")
                    {
                        $user_photo=NO_IMAGE;
                    }else{
                        $user_photo=base_url().$user_photo;
                    }
                    
                    function writeMsg($user_type) {
                        
                        if((string)$user_type ==='0'){
                                $display_user_type ='Super Admin';
                                
                        }else if((string)$user_type ==='1'){
                                $display_user_type ='Dealer';
                                
                        }else if((string)$user_type ==='2'){
                                $display_user_type ='Distributor';
                                
                        }else if((string)$user_type ==='4'){
                                $display_user_type ='Admin';
                                
                        }
                        
                        echo $display_user_type;
                    }

                    

                ?>

                <div class="image">
                    <img src="<?php echo $user_photo; ?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                        
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $user_name." (".$user_phone.")"; ?>
                    </div>
                    
                <div class="email"> <?php writeMsg($user_type); ?> | <?php echo $email; ?>.</div>
                
                <?php if(check_permission($user_type,'menu_profile')){ ?>
                <div class="btn-group user-helper-dropdown">
                     <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            
                          <li><a href="<?php echo base_url() ?>admin/edit_profile">
                                    <i class="material-icons">person</i>Profile</a>
                          </li>

                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list leftbar">
                    <li class="header"><?php echo $user_info; ?></li>
                    
                    <?php
                    $redirectionBase='admin';
                    
                    if((string)$user_type==='1')
                    {
                        $redirectionBase='dealer';
                    }
                    
                    
                    ?>
                                
          
            <?php if(check_permission($user_type,'menu_dashboard')){ ?>

            <li class="<?php echo ($currentActivePage==='Home')?'active':""; ?>" >
                <a href="<?php echo base_url() ?>admin/dashboard">
                   <i class="material-icons">home</i>
                   <span>Dashboard</span>
                </a>
            </li>
            <?php } ?>                  
                    
                    
<?php if(check_permission($user_type,'menu_inventry')){ ?>


                    <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_Company' || $currentActivePage==='Company_List')?'toggled':""; ?>">
                        <i class="material-icons">store</i> <span>Manufacturers</span>                            
                        </a>                        
                        <ul class="ml-menu" >
<?php if(check_permission($user_type,'menu_inventry_create')){ ?>

                            <li><a href="<?php echo base_url() ?>admin/create_company">Create Manufacturers</a></li>
<?php } ?>                  
<?php if(check_permission($user_type,'menu_inventry_list')){ ?>
 
                            <li><a href="<?php echo base_url() ?>admin/company_list">List Of Manufacturers</a></li>
<?php } ?>                  

                        </ul>
                    </li>
<?php } ?>                  

     

<?php if(check_permission($user_type,'menu_product')){ ?>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_Product' || $currentActivePage==='Product_List')?'toggled':""; ?>">
                        <i class="material-icons">store</i> <span>Product</span>                            
                        </a>                        
                        <ul class="ml-menu">
<?php if(check_permission($user_type,'menu_product_create')){ ?>
                            <li><a href="<?php echo base_url() ?>admin/create_product">Create Product</a></li>
<?php } ?>                                              
<?php if(check_permission($user_type,'menu_product_list')){ ?>
                            <li><a href="<?php echo base_url() ?>admin/product_list">Product List</a></li>
<?php } ?>                                              
                        </ul>
                    </li>
<?php } ?>                  
                   
                   
<?php if(check_permission($user_type,'menu_stocks')){ ?>
    <?php if($this->session->userdata("user_id") != '9'){ ?>
                     <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Certificate_List' || $currentActivePage==='Serial_Number_List' || $currentActivePage==='STOCK_INWARD' || $currentActivePage==='Unassigned_Serial_Number_List')?'toggled':""; ?>">
                            <i class="material-icons">format_list_numbered</i>
                            <span>Stock</span>
                        </a>
            <ul class="ml-menu">
                

                <?php if(check_permission($user_type,'menu_stocks_inward')){ ?>             
                    <li>
                        <a href="<?php echo base_url() ?>admin/add_serial_number">Stock Inward</a>
                    </li>
                <?php } ?>         
           
            
<?php if(check_permission($user_type,'menu_stocks_assign_to_distributer')){ ?>       
                    <li>
                       <a href="<?php echo base_url() ?>admin/unassigned_serial_number_list">Assign to Distributor</a>
                    </li>
 <?php } ?>          
 <?php if(check_permission($user_type,'menu_stocks_assign_to_dealer')){ ?>
                    <li><a href="<?php echo base_url() ?>admin/serial_number_list">Assign to Dealer</a></li>
<?php } ?>         
<?php if(check_permission($user_type,'menu_stocks_assign_devices')){ ?> 

                    <li><a href="<?php echo base_url() ?>admin/assigned_serial_number_list">Assigned Devices</a></li>
                    
<?php } ?>         
<?php if(check_permission($user_type,'menu_stocks_cerificate_allocation')){ ?>

                    <li><a href="<?php echo base_url() ?>admin/certificate_list">Certificate Allocation</a></li>

<?php } ?>         
               </ul>
                    </li>                    
<?php } ?>
<?php } ?>                  


                    
                 






                    
                   
<?php if(check_permission($user_type,'menu_cerificate')){ ?>
    <?php if($this->session->userdata("user_id") != '9'){ ?>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_Cerificate' || $currentActivePage==='Cerificate_LIST')?'toggled':""; ?>">
                            <i class="material-icons">assignment</i>
                            <span>Certificates</span>
                        </a>
                        <ul class="ml-menu">
<?php if(check_permission($user_type,'menu_cerificate_create')){ ?>                            
                            <li>
                                <a href="<?php echo base_url() ?>admin/create_new_entry">Create Certificate</a>
                            </li>
<?php } ?>                            
<?php if(check_permission($user_type,'menu_cerificate_list')){ ?>                            
                             <li>
                                <a href="<?php echo base_url() ?>admin/entry_list">Certificate List</a>
                            </li>
<?php } ?>                           
                        </ul>
                    </li>
<?php } ?>
<?php } ?>                      

<?php if(check_permission($user_type,'menu_proforma')){ ?>
    <?php if($this->session->userdata("user_id") != '9'){ ?>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_Cerificate' || $currentActivePage==='Cerificate_LIST')?'toggled':""; ?>">
                            <i class="material-icons">assignment</i>
                            <span>Proforma Invoices</span>
                        </a>
                        <ul class="ml-menu">
<?php if(check_permission($user_type,'menu_proforma_create')){ ?>
                            <li>
                                <a href="<?php echo base_url() ?>invoice/create_proforma_invoice">Create Proforma Invoice</a>
                            </li>
<?php } ?>                            
<?php if(check_permission($user_type,'menu_proforma_list')){ ?>
                             <li>
                                <a href="<?php echo base_url() ?>invoice/proforma_invoice_list">Proforma Invoice List</a>
                            </li>
<?php } ?>                           
                        </ul>
                    </li>
<?php } ?>
<?php } ?>

<?php if(check_permission($user_type,'menu_invoice')){ ?>
    <?php if($this->session->userdata("user_id") != '9'){ ?>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/invoices_list">
                            <i class="material-icons">receipt</i>
                            <span>Invoices</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/invoices_list_customers">
                            <i class="material-icons">receipt</i>
                            <span>Customer Invoices</span>
                        </a>
                    </li>
<?php } ?>
<?php } ?>


<?php if(check_permission($user_type,'menu_customer')){ ?>
    <?php if($this->session->userdata("user_id") != '9'){ ?>                  
                    <li>
                        <a href="<?php echo base_url(); ?>admin/customers_list">
                            <i class="material-icons">supervisor_account</i>
                            <span>Customers</span>
                        </a>
                    </li>
<?php } ?> 
<?php } ?>                      
               
<?php if(check_permission($user_type,'menu_user')){ ?>                            
                         <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_Users' || $currentActivePage==='Users_List')?'toggled':""; ?>">
                            <i class="material-icons">person_add</i>
                            <span>Manage Users</span>
                        </a>
                        <ul class="ml-menu">
<?php if(check_permission($user_type,'menu_user_create')){ ?>                            
                            <li>
                                <a href="<?php echo base_url() ?>admin/create_new_users">Create User</a>
                            </li>
<?php } ?>                            
<?php if(check_permission($user_type,'menu_user_list')){ ?>                            
                             <li>
                                <a href="<?php echo base_url() ?>admin/users_list">Users List</a>
                            </li>
<?php } ?>                            
                        </ul>
                    </li>
<?php } ?>                    
            
            

<?php if(check_permission($user_type,'menu_rto')){ ?> 
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_RTO_Number' || $currentActivePage==='RTO_List')?'toggled':""; ?>">
                            <i class="material-icons">account_box</i>
                            <span>RTO</span>
                        </a>
                        <ul class="ml-menu">
<?php if(check_permission($user_type,'menu_rto_create')){ ?>                             
                            <li>
                                <a href="<?php echo base_url() ?>admin/create_rto">Create RTO</a>
                            </li>
<?php } ?>                           
<?php if(check_permission($user_type,'menu_rto_list')){ ?>                              
                             <li>
                                <a href="<?php echo base_url() ?>admin/rto_list">RTO List</a>
                            </li>
<?php } ?>                             
                        </ul>
                    </li>
<?php } ?>                     


<?php if(check_permission($user_type,'menu_vehicle_make')){ ?>     
                         <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_Vehicle_Make' || $currentActivePage==='Vehicle_Make_List')?'toggled':""; ?>">
                            <i class="material-icons">directions_bus</i>
                            <span>Vehicle Makes</span>
                        </a>
                        <ul class="ml-menu">
<?php if(check_permission($user_type,'menu_vehicle_make_create')){ ?>
                            <li>
                                <a href="<?php echo base_url() ?>admin/create_vehicle_make">Create Vehicle Make</a>
                            </li>
<?php } ?>                             
<?php if(check_permission($user_type,'menu_vehicle_make_list')){ ?>
                             <li>
                                <a href="<?php echo base_url() ?>admin/vehicle_make_list">Vehicle Make List</a>
                            </li>
<?php } ?>                             
                        </ul>
                    </li>
<?php } ?> 
<?php if(check_permission($user_type,'menu_vehicle_model')){ ?>    
                      <li>
                        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_Vehicle_Model' || $currentActivePage==='Vehicle_Model_List')?'toggled':""; ?>">
                            <i class="material-icons">directions_bus</i>
                            <span>Vehicle Model</span>
                        </a>
                        <ul class="ml-menu">
<?php if(check_permission($user_type,'menu_vehicle_model_create')){ ?>
                            <li>
                                <a href="<?php echo base_url() ?>admin/create_vehicle_model">Create Vehicle Model</a>
                            </li>
<?php } ?>                            
<?php if(check_permission($user_type,'menu_vehicle_model_list')){ ?>
                             <li>
                                <a href="<?php echo base_url() ?>admin/vehicle_model_list">Vehicle Model List</a>
                            </li>
<?php } ?>                            
                        </ul>
                    </li>
<?php } ?>                       
       
    <?php if(check_permission($user_type,'menu_report')){ ?> 
    <li>
        <a href="javascript:void(0);" class="menu-toggle <?php echo ($currentActivePage==='Create_RTO_Number' || $currentActivePage==='RTO_List')?'toggled':""; ?>">
            <i class="material-icons">receipt</i>
            <span>Reports</span>
        </a>
        <ul class="ml-menu">
           <!--  <?php if(check_permission($user_type,'menu_report_dealer_sales_report')){ ?>                             
            <li>
                <a href="<?php echo base_url() ?>admin/dealersalesreport">Dealers Report</a>
            </li>
            <?php } ?>  -->                          
            <?php if(check_permission($user_type,'menu_report_inventory_report')){ ?>                              
            <li>
                <a href="<?php echo base_url() ?>admin/inventoryreport">Inventory Report</a>
            </li>
            <?php } ?>
            <?php if(check_permission($user_type,'menu_report_sales_report')){ ?>                              
            <li>
                <a href="<?php echo base_url() ?>admin/salesreport">Sales Report</a>
            </li>
            <?php } ?>                             
        </ul>
    </li>
    <?php } ?>        
<li>
                        <a href="<?php echo base_url(); ?>admin/check_device_status">
                            <i class="material-icons">directions_bus</i>
                            <span>Where is my device?</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/check_device_data/<?php echo date('Y-m-d') ?>/00:00/23:59/0">
                            <i class="material-icons">directions_bus</i>
                            <span>Device Data Check</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/logout">
                            <i class="material-icons">input</i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                    <!-- ### Logout Ends ## -->
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    Copyright&copy; 2019 <a href="javascript:void(0);">PSDN</a>.
                </div>               
                <div class="version">
                    <b>All rights reserved.</b>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red" class="active">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>