<?php $this->load->view('common/admin_login_header'); ?> 
    <!-- Morris Chart Css-->
<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
   

  <?php $this->load->view('common/top_search_bar'); ?>      
  <?php $this->load->view('common/dashboard_top_bar'); ?> 
  <?php $this->load->view('common/left_side_bar'); ?>
    
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink "> <?php //Removed css: hover-expand-effect ?>
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <?php if(check_permission($user_type,'menu_cerificate_list')){ ?> 
                            <a style="color:#FFF;" href="<?php echo base_url() ?>admin/entry_list">Cerificates</a>
                            <?php }else{ ?>
                            Cerificates
                            <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">
							<?php echo $countList['vehicle']; ?></div>
                        </div>
                    </div>
                </div>
                
               
               <?php if ($user_type !=1) { ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan"> <?php //Removed css: hover-expand-effect ?>                    
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <?php if(check_permission($user_type,'menu_user_list')){ ?>  
                            <a style="color:#FFF;" href="<?php echo base_url() ?>admin/users_list?offset=0&search=&user_type=1">Dealers</a>
							<?php }else{ ?>
                            	Dealers
                            <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"><?php echo $countList['dealer']; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($user_type !=2  && $user_type !=1) { ?>
                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-green"> <?php //Removed css: hover-expand-effect ?>
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <?php if(check_permission($user_type,'menu_user_list')){ ?>  
                        <a style="color:#FFF;" href="<?php echo base_url() ?>admin/users_list?offset=0&search=&user_type=2">Distributors</a>
							<?php }else{ ?>
                            	Distributors
                            <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"><?php echo $countList['distributor']; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue-grey"> <?php //Removed css: hover-expand-effect ?>
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <?php 

							if(check_permission($user_type,'menu_rto_list')){ ?>
                            <a style="color:#FFF;" href="<?php echo base_url() ?>admin/rto_list">RTOs</a>
							<?php }else{ ?>
                            	RTOs
                            <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"><?php echo $countList['rto']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-amber"> <?php // hover-expand-effect ?>
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <?php if(check_permission($user_type,'menu_customer')){ ?>
                            <a style="color:#FFF;" href="<?php echo base_url() ?>admin/customers_list">Customers</a>
							<?php }else{ ?>
                            Customers
                            <?php } ?>
                            
                            
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"><?php echo $countList['customer']; ?></div>
                        </div>
                    </div>
                </div>

                <!--<div class="row clearfix">-->
                <!--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">-->
                <!--    <div class="info-box bg-amber  hover-expand-effect">-->
                <!--        <div class="icon">-->
                <!--            <i class="material-icons">playlist_add_check</i>-->
                <!--        </div>-->
                <!--        <div class="content">-->
                <!--            <div class="text">Today Certificates</div>-->
                <!--                <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"><?php echo $countList['today']; ?></div>-->
                <!--            </div>-->
                <!--    </div>-->
                <!--</div>-->
              <!--</div>-->
            <!--</div>-->
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-teal-800" style="background-color: rgb(17 94 89)">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                                <?php if (check_permission($user_type, 'menu_customer')) { ?>
                                <a style="color:#FFF;"
                                    href="<?php echo base_url() ?>admin/entry_list?scales=on&start_date=<?php echo date('Y-m-d') ?>&end_date=<?php echo date('Y-m-d') ?>&offset=0&search=&distributor_id=&dealer_id=&searchfiltersubmit=">Today
                                    Certificates</a>
                                <?php } else { ?>
                                Today Certificates
                                <?php } ?>
                                <!-- <div class="text">Today Certificates</div> -->
                                <div class="number count-to" style="color:white" data-from="0" data-to="125"
                                    data-speed="15" data-fresh-interval="20"><?php echo $countList['today']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php $user_type = $this->session->userdata('user_type');
            if ($user_type == 0 || $user_type == 4 ) { ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-violet-900" style="background-color: rgb(76 29 149)"> <?php //Removed css: hover-expand-effect 
                                                        ?>
                    <div class="icon">
                        <i class="material-icons">person_add</i>
                    </div>
                    <div class="content">
                        <div class="text" style="color:white">
                            <?php if (check_permission($user_type, 'menu_user_list')) { ?>
                            <a style="color:#FFF;"
                                href="<?php echo base_url() ?>admin/vehicle_model_list?offset=0&search=">Vehicle
                                Model</a>
                            <?php } else { ?>
                            Distributors
                            <?php } ?>
                        </div>
                        <div class="number count-to" style="color:white" data-from="0" data-to="1225"
                            data-speed="1000" data-fresh-interval="20"><?php echo $countList['model']; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-purple"> <?php //Removed css: hover-expand-effect 
                                                        ?>
                    <div class="icon">
                        <i class="material-icons">person_add</i>
                    </div>
                    <div class="content">
                        <div class="text">
                            <?php if (check_permission($user_type, 'menu_user_list')) { ?>
                            <a style="color:#FFFF;"
                                href="<?php echo base_url() ?>admin/vehicle_make_list?offset=0&search=">Vehicle
                                Make</a>
                            <?php } else { ?>
                            Distributors
                            <?php } ?>
                        </div>
                        <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                            data-fresh-interval="20"><?php echo $countList['make']; ?></div>
                    </div>
                </div>
            </div>
            <?php } ?>
            
           <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-green"> <?php //Removed css: hover-expand-effect 
                                                            ?>
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                                <?php if (check_permission($user_type, 'menu_user_list')) { ?>
                                 <a style="color:#FFFF;"
                                    href="<?php echo base_url() ?>admin/total_device_list?offset=0&search="> Total Devices</a>
                                <?php } else { ?>
                                Distributors
                                <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['totalDevices']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-grey"> <?php //Removed css: hover-expand-effect 
                                                            ?>
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                                <?php if (check_permission($user_type, 'menu_user_list')) { ?>
                                <a style="color:#FFFF;"
                                    href="<?php echo base_url() ?>admin/used_device_list?offset=0&search=">Used Devices</a>
                                <?php } else { ?>
                                Distributors
                                <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['usedDevices']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink"> <?php //Removed css: hover-expand-effect 
                                                            ?>
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                                <?php if (check_permission($user_type, 'menu_user_list')) { ?>
                                <a style="color:#FFFF;"
                                    href="<?php echo base_url() ?>admin/unUsed_device?offset=0&search=">Unused Devices</a>
                                <?php } else { ?>
                                Distributors
                                <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['unUsedDevices']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue-grey"> 
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                                <?php if (check_permission($user_type, 'menu_user_list')) { ?>
                                <a style="color:#FFFF;"
                                href="<?php echo base_url() ?>admin/offline_device?offset=0&search=&hour=5">Offline</a>
                                <?php } else { ?>
                                Distributors
                                <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['offline']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-teal"> 
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                                <?php if (check_permission($user_type, 'menu_user_list')) { ?>
                                <a style="color:#FFFF;"
                                    href="<?php echo base_url() ?>admin/live_device?offset=0&search=">Live</a>
                                <?php } else { ?>
                                Distributors
                                <?php } ?>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['live']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-red"> <?php //Removed css: hover-expand-effect 
                                                            ?>
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                                <a style="color:#FFF;" href="<?php echo base_url() ?>admin/faulty_device?offset=0&search=">Faulty
                                    Devices</a>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['faulty']; ?></div>
                        </div>
                    </div>
                </div>

                <?php if ($user_type == 0 || $user_type == 4) { ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue-grey">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <a style="color:#FFF;" href="<?php echo base_url() ?>admin/inscan_device?offset=0&search=">InScan Devices</a>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['inScan']; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>


                <?php if ($user_type == 0 || $user_type == 4) { ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <a style="color:#FFF;" href="<?php echo base_url() ?>admin/expiring_list">Expiring Certificate</a>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['expiring_certi_counts']; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if ($user_type == 0 || $user_type == 4) { ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">
                            <a style="color:#FFF;" href="<?php echo base_url() ?>admin/expired_list">Expired Certificate</a>
                            </div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000"
                                data-fresh-interval="20"><?php echo $countList['expired_certi_counts']; ?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
            <!-- #END# Widgets -->
  

            <!-- CPU Usage -->
            <div class="row clearfix" style="display: none;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>CPU USAGE (%)</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                                    <div class="switch panel-switch-btn">
                                        <span class="m-r-10 font-12">REAL TIME</span>
                                        <label>OFF<input type="checkbox" id="realtime" checked><span class="lever switch-col-cyan"></span>ON</label>
                                    </div>
                                </div>
                            </div>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="real_time_chart" class="dashboard-flot-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# CPU Usage -->
            <div class="row clearfix"  style="display: none;">
                <!-- Visitors -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-pink">
                            <div class="sparkline" data-type="line" data-spot-Radius="4" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#fff"
                                 data-min-Spot-Color="rgb(255,255,255)" data-max-Spot-Color="rgb(255,255,255)" data-spot-Color="rgb(255,255,255)"
                                 data-offset="90" data-width="100%" data-height="92px" data-line-Width="2" data-line-Color="rgba(255,255,255,0.7)"
                                 data-fill-Color="rgba(0, 188, 212, 0)">
                                12,10,9,6,5,6,10,5,7,5,12,13,7,12,11
                            </div>
                            <ul class="dashboard-stat-list">
                                <li>
                                    TODAY
                                    <span class="pull-right"><b>1 200</b> <small>USERS</small></span>
                                </li>
                                <li>
                                    YESTERDAY
                                    <span class="pull-right"><b>3 872</b> <small>USERS</small></span>
                                </li>
                                <li>
                                    LAST WEEK
                                    <span class="pull-right"><b>26 582</b> <small>USERS</small></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Visitors -->
                <!-- Latest Social Trends -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-cyan">
                            <div class="m-b--35 font-bold">LATEST SOCIAL TRENDS</div>
                            <ul class="dashboard-stat-list">
                                <li>
                                    #socialtrends
                                    <span class="pull-right">
                                        <i class="material-icons">trending_up</i>
                                    </span>
                                </li>
                                <li>
                                    #materialdesign
                                    <span class="pull-right">
                                        <i class="material-icons">trending_up</i>
                                    </span>
                                </li>
                                <li>#adminbsb</li>
                                <li>#freeadmintemplate</li>
                                <li>#bootstraptemplate</li>
                                <li>
                                    #freehtmltemplate
                                    <span class="pull-right">
                                        <i class="material-icons">trending_up</i>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Latest Social Trends -->
                <!-- Answered Tickets -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-teal">
                            <div class="font-bold m-b--35">ANSWERED TICKETS</div>
                            <ul class="dashboard-stat-list">
                                <li>
                                    TODAY
                                    <span class="pull-right"><b>12</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    YESTERDAY
                                    <span class="pull-right"><b>15</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    LAST WEEK
                                    <span class="pull-right"><b>90</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    LAST MONTH
                                    <span class="pull-right"><b>342</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    LAST YEAR
                                    <span class="pull-right"><b>4 225</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    ALL
                                    <span class="pull-right"><b>8 752</b> <small>TICKETS</small></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Answered Tickets -->
            </div>

            <div class="row clearfix"  style="display: none;">
                <!-- Task Info -->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="header">
                            <h2>TASK INFOS</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Task</th>
                                            <th>Status</th>
                                            <th>Manager</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Task A</td>
                                            <td><span class="label bg-green">Doing</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100" style="width: 62%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Task B</td>
                                            <td><span class="label bg-blue">To Do</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Task C</td>
                                            <td><span class="label bg-light-blue">On Hold</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-light-blue" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Task D</td>
                                            <td><span class="label bg-orange">Wait Approvel</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Task E</td>
                                            <td>
                                                <span class="label bg-red">Suspended</span>
                                            </td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-red" role="progressbar" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100" style="width: 87%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Task Info -->
                <!-- Browser Usage -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2>BROWSER USAGE</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="donut_chart" class="dashboard-donut-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- #END# Browser Usage -->
            </div>

        </div>
        <!--<?php if($user_type == 0){ ?>-->
        <!--    <div class="block-header">-->
        <!--        <h2>SERVICES<?php echo " (Last updated Time:"; echo $currentDate. ")";?> <a-->
        <!--                class="fa-sharp fa-solid fa-rotate" href="<?php echo base_url() . "admin/dashboard "?>">-->
        <!--                <span class="glyphicon glyphicon-refresh" title="Inter_Change_Device"></span></a></h2>-->
        <!--    </div>-->

            <!-- Widgets -->
        <!--    <div class="row clearfix">-->
        <!--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">-->

        <!--            <div class="info-box bg-green" id="node">-->
        <!--                <div class="icon"-->
        <!--                     style="display: flex;flex-direction: column;justify-content: center;align-items: center;">-->
        <!--                    <div class="text">Node js</div>-->
        <!--                </div>-->
        <!--                <div class="content">-->
        <!--                    <div class="text" id="nodeText"-->
        <!--                        style="display: flex;flex-direction: column;justify-content: center;align-items: center;height: 7vh;color:white;font-size:20px">-->
        <!--                        <?php echo "Online" ?></div>-->
        <!--                </div>-->
        <!--            </div>-->

        <!--        </div>-->


        <!--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">-->
        <!--            <div class="info-box bg-green">-->
        <!--                <div class="icon"-->
        <!--                    style="display: flex;flex-direction: column;justify-content: center;align-items: center;">-->
        <!--                    <div class="text">Golang</div>-->
        <!--                </div>-->
        <!--                <div class="content">-->
        <!--                    <div class="text"-->
        <!--                        style="display: flex;flex-direction: column;justify-content: center;align-items: center;height: 7vh;color:white;font-size:20px">-->
        <!--                        <?php echo "Online" ?></div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <!--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">-->
        <!--            <div class="info-box bg-green" >-->
        <!--                <div class="icon"-->
        <!--                    style="display: flex;flex-direction: column;justify-content: center;align-items: center;">-->
        <!--                    <div class="text">PSDN Tech</div>-->
        <!--                </div>-->
        <!--                <div class="content">-->
        <!--                    <div class="text"-->
        <!--                        style="display: flex;flex-direction: column;justify-content: center;align-items: center;height: 7vh;color:white;font-size:20px">-->
        <!--                        <?php echo "Online" ?></div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <!--        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">-->
        <!--            <div class="info-box bg-green" id="live">-->
        <!--                <div class="icon" -->
        <!--                    style="display: flex;flex-direction: column;justify-content: center;align-items: center;">-->
        <!--                    <div class="text">PSDN Live</div>-->
        <!--                </div>-->
        <!--                <div class="content">-->
        <!--                    <div class="text" id="textLive"-->
        <!--                        style="display: flex;flex-direction: column;justify-content: center;align-items: center;height: 7vh;color:white;font-size:20px">-->
        <!--                        <?php echo "Online" ?></div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <!-- <?php }?>-->
    </section>
    <?php $this->load->view('common/admin_login_css_js'); ?> 
    
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url() ?>public/plugins/node-waves/waves.js"></script>
    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo base_url() ?>public/plugins/jquery-countto/jquery.countTo.js"></script>   
       
    <!-- Custom Js -->
    <script src="<?php echo base_url() ?>public/js/pages/index.js"></script>

</body>

</html>