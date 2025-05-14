<!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
           
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list leftbar">
                    <li class="header">RTO Details</li>
								
					
                    <li class="active" style="">
                        <a href="<?php echo base_url() ?>admin/dashboard">
                        <?php
                        $rto_number=$this->session->userdata('rto_number');
                        $rto_place=$this->session->userdata('rto_place');
                        ?>
                            <span>RTO Name : <?php echo $rto_number; ?></span>
                        </a>
                        
                    </li>		
                    <li class="active" style="">
                       <a href="<?php echo base_url() ?>admin/dashboard">                      
                            <span>Place : <?php echo $rto_place; ?></span>
                        </a>
                    </li>   			
					
					<li>
                        <a href="<?php echo base_url(); ?>admin/logout">
                            <i class="material-icons">input</i>
                            <span>Sign Out</span>
                        </a>
                    </li>
					
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    Copyright&copy; 2017 <a href="javascript:void(0);">Universal Tele Services</a>.
                </div>               
				<div class="version">
                    <b>All rights reserved.</b>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
       
        <!-- #END# Right Sidebar -->
    </section>