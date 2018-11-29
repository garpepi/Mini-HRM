<!-- SIDEBAR -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="<?php echo base_url().'home'?>"><i class="fa fa-dashboard fa-fw"></i> Home</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-user-md fa-fw"></i> Admin<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'users'?>"><i class="fa fa-users fa-fw"></i> Manage and Assign Admin</a>
								</li>
								<li>
									<a href="<?php echo base_url().'users/edit'?>"><i class="fa fa-user fa-fw"></i> Change Password</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-user-md fa-fw"></i> Employee<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'employee'?>"><i class="fa fa-users fa-fw"></i> Manage Employee</a>
								</li>
								<li>
									<a href="<?php echo base_url().'employee/add'?>"><i class="fa fa-user fa-fw"></i> Add Employee</a>
								</li>
                                <li>
									<a href="<?php echo base_url().'employee/printt'?>"><i class="fa fa-print fa-fw"></i> Print Employee</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-keyboard-o fa-fw"></i> Attendance<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'attendance/upload'?>"><i class="fa fa-upload fa-fw"></i> Upload Attendance</a>
								</li>
								<li>
									<a href="<?php echo base_url().'attendance/input'?>"><i class="fa fa-sign-in fa-fw"></i> Employees Attendances</a>
								</li>
								<li>
									<a href="<?php echo base_url().'attdreport/posting'?>"><i class="fa fa-check-square-o fa-fw"></i> Posting Employees Attendance</a>
								</li>
								<li>
									<a href="<?php echo base_url().'attdreport'?>"><i class="fa fa-copy fa-fw"></i> Posted Employee Attendance Attendance</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-plus fa-fw"></i> Medical<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'medical'?>"><i class="fa fa-list fa-fw"></i> Medical Reimbursment List</a>
								</li>
								<li>
									<a href="<?php echo base_url().'medical/add'?>"><i class="fa fa-medkit fa-fw"></i> Medical Reimbursment</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-plus fa-fw"></i> Leaves<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'leaves'?>"><i class="fa fa-list fa-fw"></i> Leaves List</a>
								</li>
								<li>
									<a href="<?php echo base_url().'leaves/add'?>"><i class="fa fa-medkit fa-fw"></i> Add Leaves Record</a>
								</li>
								<li>
									<a href="<?php echo base_url().'leaves/multipleadd'?>"><i class="fa fa-medkit fa-fw"></i> Multiple Add Leaves Record</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-plus fa-fw"></i> Sick<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'sick'?>"><i class="fa fa-list fa-fw"></i> Sick List</a>
								</li>
								<li>
									<a href="<?php echo base_url().'sick/add'?>"><i class="fa fa-medkit fa-fw"></i> Add Sick Record</a>
								</li>
								<li>
									<a href="<?php echo base_url().'sick/multipleadd'?>"><i class="fa fa-medkit fa-fw"></i> Multiple Add Sick Record</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-plus fa-fw"></i> Overtime<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'overtime'?>"><i class="fa fa-list fa-fw"></i> Overtime List</a>
								</li>
								<li>
									<a href="<?php echo base_url().'overtime/add'?>"><i class="fa fa-medkit fa-fw"></i> Add Overtime Record</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-plus fa-fw"></i> Reports<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'reports/summattendance'?>"><i class="fa fa-list fa-fw"></i> Attendance Summary</a>
								</li>
								<li>
									<a href="<?php echo base_url().'reports/inoutattendance'?>"><i class="fa fa-medkit fa-fw"></i> Attendance In-Out</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li>
                            <a href="#"><i class="fa fa-gears fa-fw"></i> Contents<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="<?php echo base_url().'client'?>"><i class="fa fa-university fa-fw"></i> Client</a>
                                </li>
								<li>
									<a href="<?php echo base_url().'projects'?>"><i class="fa fa-file fa-fw"></i> Projects</a>
                                </li>
								<li>
									<a href="<?php echo base_url().'job'?>"><i class="fa fa-briefcase fa-fw"></i> Job</a>
								</li>
								<li>
									<a href="<?php echo base_url().'division'?>"><i class="fa fa-gear fa-fw"></i> Division</a>
								</li>
								<li>
									<a href="<?php echo base_url().'allowance'?>"><i class="fa fa-money fa-fw"></i> Allowance</a>
								</li>
								<li>
									<a href="<?php echo base_url().'attendancetiming'?>"><i class="fa fa-calendar fa-fw"></i> Timing Attendance</a>
								</li>
								<li>
									<a href="<?php echo base_url().'holiday'?>"><i class="fa fa-umbrella fa-fw"></i> Holiday</a>
                                </li>
                                <li>
									<a href="<?php echo base_url().'autoreportemail'?>"><i class="fa fa-list fa-fw"></i> Autoreport Email list</a>
								</li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->