    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
				<?php
					@$userType=$this->session->userdata('user_type');
					if(isset($userType) && (string)$userType==='0')
					{
						$title='UTS - Admin Panel';
					}else if(isset($userType) && (string)$userType==='1')
					{
						$title='UTS - Dealer Panel';
					}else if(isset($userType) && (string)$userType==='2')
                    {
                        $title='UTS - Distributor Panel';
                    }else 
                    {
                        $title='UTS - RTO Panel';
                    }

                    $redirectionBase='admin';
                    if((string)$userType==='1')
                    {
                        $redirectionBase='dealer';
                    }
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";     
                    $Create='style="color: white;    font-size: initial;    padding-right: 20px;
    padding-left: 20px;"';
                    $List='style="color: white;    font-size: initial;    padding-right: 20px;
    padding-left: 20px;"';            
                    $dealersalesreport='style="color: white;    font-size: initial;    padding-right: 20px;
    padding-left: 20px;"';            
                    $inventoryreport='style="color: white;    font-size: initial;    padding-right: 20px;
    padding-left: 20px;"';            
                    $salesreport='style="color: white;    font-size: initial;    padding-right: 20px;
    padding-left: 20px;"';            
                    if (strpos($actual_link, 'admin/create_new_entry') !== false) {
                         $Create='style="text-decoration: underline;font-size: initial;padding-right: 20px;
    padding-left: 20px;"';
                    }
                    if (strpos($actual_link, 'admin/entry_list') !== false) {
                         $List='style="text-decoration: underline;font-size: initial;padding-right: 20px;
    padding-left: 20px;"';
                    }
                    if (strpos($actual_link, 'admin/dealersalesreport') !== false) {
                         $dealersalesreport='style="    text-decoration: underline;font-size: initial;    padding-right: 20px; padding-left: 20px;"';
                    }
                     if (strpos($actual_link, 'admin/inventoryreport') !== false) {
                         $inventoryreport='style="    text-decoration: underline;font-size: initial;    padding-right: 20px; padding-left: 20px;"';
                    }
                    if (strpos($actual_link, 'admin/salesreport') !== false) {
                         $salesreport='style="text-decoration: underline;font-size: initial;    padding-right: 20px;
    padding-left: 20px;"';
                    }

				?>
             
                       <a class="navbar-brand center"  href="javascript:void(0); "> <p> <span style="color:red"> <img  src="<?php echo AWS_S3_BUCKET_URL ?>public/images/psdn_logo.jpg" class="margin-bottom: 20px" alt="" width="40" height="25"></span> PSDN Technology Pvt Ltd</p> 
                    <?php //echo $title; ?></a>

            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right" style="background-color:#9BC53E">
                
				
				
				
                 <?php /*
                if((string)$userType==='0' || (string)$userType==='1' || (string)$userType==='2')
                {
                ?>
                <li>
                 <a <?php echo $dealersalesreport; ?> class="navbar-brand" style=" background-color:!important" href="<?php echo base_url().$redirectionBase; ?>/dealersalesreport">Dealer Sales Report</a>
                 </li>
                 <li>
                  <a <?php echo $inventoryreport; ?> class="navbar-brand" href="<?php echo base_url().$redirectionBase; ?>/inventoryreport">Inventory Report</a>
                  </li>
                  <?php 
                  if((string)$userType==='0')
                  {
                  ?>                  
                  <li>
                   <a <?php echo $salesreport; ?> class="navbar-brand" href="<?php echo base_url().$redirectionBase; ?>/salesreport">Sales Report</a>
                   </li>
                   <?php 
                    }
                    ?>
                <?php
                }  */
                ?>
                   
                    <li><a class="navbar-brand" href="<?php echo ((string)$userType === '5') ? base_url() . "device/logout" : base_url() . "admin/logout"; ?>">Logout</a></li>
                   <?php /* ?>
                    <li>
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">input</i>
                            <span class="label-count">7</span>
                        </a>
                    </li> <?php */ ?>
                    <li style="display:none;"><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a style="display:none;" href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count">7</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICATIONS</li>
                            <li class="body">
                                <ul class="menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">add_shopping_cart</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>4 sales made</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 22 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-red">
                                                <i class="material-icons">delete_forever</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy Doe</b> deleted account</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-orange">
                                                <i class="material-icons">mode_edit</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy</b> changed name</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 2 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-blue-grey">
                                                <i class="material-icons">comment</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> commented your post</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 4 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">cached</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> updated status</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-purple">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Settings updated</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> Yesterday
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Notifications</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                    <li class="dropdown">
                        <a style="display:none;" href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">flag</i>
                            <span class="label-count">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">TASKS</li>
                            <li class="body">
                                <ul class="menu tasks">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Footer display issue
                                                <small>32%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 32%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Make new buttons
                                                <small>45%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Create new dashboard
                                                <small>54%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 54%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Solve transition issue
                                                <small>65%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 65%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Answer GitHub questions
                                                <small>92%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 92%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Tasks -->
                    <li style="display:none;" class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->