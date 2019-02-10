<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?= $title?></h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
		<?php if($this->session->flashdata('form_msg')):?>
			<div class="alert <?php echo ($this->session->flashdata('form_status') == 1 ? 'alert-success' : 'alert-danger'); ?> alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php echo $this->session->flashdata('form_msg'); ?>
			</div>
		<?php endif;?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<?= $box_title_1?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="col-lg-12">
							<form role="form" method='post' action=<?php echo base_url().($this->uri->segment(2)!= 'add' && $this->uri->segment(2) == 'edit' ? 'employee/edit/'.$contents['employee']['id'] : ($this->uri->segment(2)=='view' ? '#' : 'employee/add' ));?>>
								<?php if($this->uri->segment(2) != 'add'):?>
								<div class="form-group">
									<label>Employee Id</label>
									<input type=number min=1 name='employee_id' id='employee_id' class="form-control"
									value="<?php echo $contents['employee']['id']; ?>"
									required readonly>
								</div>
								<?php endif;?>
								<div class="form-group">
									<label>Finger Id</label>
									<input type=number min=1 name='finger_id' id='finger_id' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('finger_id') == '' ? $contents['employee']['finger_id'] : repopulate_form('finger_id')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Full Name</label>
									<input type=text name='name' id='name' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('name') == '' ? $contents['employee']['name'] : repopulate_form('name')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Nick Name</label>
									<input type=text name='nick_name' id='nick_name' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('nick_name') == '' ? $contents['employee']['nick_name'] : repopulate_form('nick_name')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >

								</div>
							</div>
						<div class="col-lg-6">
								<div class="form-group">
									<label>Job</label>
									<select name='job_id' id='job' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
										<?php foreach($contents['job'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('job_id') != '' && repopulate_form('job_id') == $value['id'] ) || (repopulate_form('job_id') == '' && $contents['employee']['job_id'] == $value['id']) ) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else  :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php else : // normal
													if(repopulate_form('job_id') != '' && repopulate_form('job_id') == $value['id']) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php endif ;?>

										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group">
									<label>Division</label>
									<select name='div_id' id='div' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
										<?php foreach($contents['div'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('div_id') != '' && repopulate_form('div_id') == $value['id'] ) || (repopulate_form('div_id') == '' && $contents['employee']['div_id'] == $value['id']) ) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else  :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php else : // normal
													if(repopulate_form('div_id') != '' && repopulate_form('div_id') == $value['id']) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php endif ;?>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group">
									<label>Date of Birth</label>
									<div class='input-group date datep' >
										<input onkeydown="return false" id='birth_of_date' name='birth_of_date' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('birth_of_date') == '' ? date('d/m/Y', strtotime($contents['employee']['birth_of_date'])) : repopulate_form('birth_of_date')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Employee Status</label>
									<select name='employee_status' id='employee_status' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
										<?php foreach($contents['employee_status'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('employee_status') != '' && repopulate_form('employee_status') == $value['id'] ) || (repopulate_form('employee_status') == '' && $contents['employee']['employee_status'] == $value['id']) ) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else  :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php else : // normal
													if(repopulate_form('employee_status') != '' && repopulate_form('employee_status') == $value['id']) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php endif ;?>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group">
									<label>Employee Position</label>
									<select name='employee_position' id='employee_position' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
									<option value='' > Select Position</option>
										<?php foreach($contents['employee_position'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('employee_position') != '' && repopulate_form('employee_position') == $value['id'] ) || (repopulate_form('employee_position') == '' && $contents['employee']['employee_position'] == $value['id']) ) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else  :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php else : // normal
													if(repopulate_form('employee_position') != '' && repopulate_form('employee_position') == $value['id']) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php endif ;?>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group">
									<label>Hp</label>
									<input type=text name='hp' id='hp' class="form-control" onkeypress="return isNumberKey(event)"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('hp') == '' ? $contents['employee']['hp'] : repopulate_form('hp')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Hp2</label>
									<input type=text name='hp2' id='hp2' onkeypress="return isNumberKey(event)" class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('hp2') == '' ? $contents['employee']['hp2'] : repopulate_form('hp2')) ; ?>"
									<?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Address</label>
									<textarea name='address' class="form-control" rows="3" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> ><?php echo ($this->uri->segment(2) != 'add' && repopulate_form('address') == '' ? $contents['employee']['address'] : repopulate_form('address')) ; ?></textarea>
								</div>
								<div class="form-group">
									<label>Bank Account</label>
									<input type=text name='bank_account' id='bank_account' onkeypress="return isNumberKey(event)" class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('bank_account') == '' ? $contents['employee']['bank_account'] : repopulate_form('bank_account')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Bank Name</label>
									<select name='bank_id' id='bank_id' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
										<?php foreach($contents['bank_id'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('bank_id') != '' && repopulate_form('bank_id') == $value['id'] ) || (repopulate_form('bank_id') == '' && $contents['employee']['bank_id'] == $value['id']) ) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else  :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php else : // normal
													if(repopulate_form('bank_id') != '' && repopulate_form('bank_id') == $value['id']) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php endif ;?>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group">
									<label>Insurance Number</label>
									<input type=text name='aia_account' id='aia_account' onkeypress="return isNumberKey(event)" class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('aia_account') == '' ? $contents['employee']['aia_account'] : repopulate_form('aia_account')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Client-Project</label>
									<select name='project_id' id='project_id' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
										<option value='0' selected>No Client - Project</option>
										<?php foreach($contents['projects_id'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('project_id') != '' && repopulate_form('project_id') == $value['id'] ) || (repopulate_form('project_id') == '' && $contents['employee']['project_id'] == $value['id']) ) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['client_name']." - ".$value['name'];?></option>
													<?php else  :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['client_name']." - ".$value['name'];?></option>
													<?php endif ;?>
											<?php else : // normal
													if(repopulate_form('project_id') != '' && repopulate_form('project_id') == $value['id']) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['client_name']." - ".$value['name'];?></option>
													<?php else :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['client_name']." - ".$value['name'];?></option>
													<?php endif ;?>
											<?php endif ;?>
										<?php endforeach;?>
									</select>
								</div>
						</div>
						<div class="col-lg-6">
								<div class="form-group">
									<label>Email</label>
									<input type=email name='email' id='email' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('email') == '' ? $contents['employee']['email'] : repopulate_form('email')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Email2</label>
									<input type=email name='email2' id='email2' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('email2') == '' ? $contents['employee']['email2'] : repopulate_form('email2')) ; ?>"
									<?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Medical Limit</label>
									<input type=number name='medical_limit' min=0 id='medical_limit' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('medical_limit') == '' ? ($contents['employee']['medical_limit'] == '' ? 0 : $contents['employee']['medical_limit']) : (repopulate_form('medical_limit') == '' ? 0 : repopulate_form('medical_limit'))) ; ?>"
									<?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Cuti Limit</label>
									<input type=number name='cuti_limit' min=0 id='cuti_limit' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('cuti_limit') == '' ? ($contents['employee']['cuti_limit'] == '' ? 0 : $contents['employee']['cuti_limit']) : (repopulate_form('cuti_limit') == '' ? 0 : repopulate_form('cuti_limit'))) ; ?>"
									<?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Join Date</label>
									<div class='input-group date datep' >
										<input onkeydown="return false" id='join_date' name='join_date' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('join_date') == '' ? date('d/m/Y', strtotime($contents['employee']['join_date'])) : repopulate_form('join_date')) ; ?>"
										required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group  contract">
									<label>Contract Start</label>
									<div class='input-group date datep' >
										<input onkeydown="return false" id='contract_start' name='contract_start' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('contract_start') == '' ? (empty($contents['employee']['contract_start']) ? '' : date('d/m/Y', strtotime($contents['employee']['contract_start'])) ) : (empty(repopulate_form('contract_start'))? '' : repopulate_form('contract_start'))) ; ?>"
										 <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group contract">
									<label>Contract end</label>
									<div class='input-group date datep' >
										<input onkeydown="return false" id='contract_end' name='contract_end' type='text' class="form-control "
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('contract_end') == '' ? (empty($contents['employee']['contract_end']) ? '' : date('d/m/Y', strtotime($contents['employee']['contract_end'])) ) : (empty(repopulate_form('contract_end'))? '' : repopulate_form('contract_end'))) ; ?>"
										 <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<?php if($this->uri->segment(2) != 'add' ):?>
								<div class="form-group">
									<label>Active Status</label>
									<select name='status' id='status' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
										<option value='active' <?php echo ( ($this->uri->segment(2) != 'add' && $contents['employee']['status'] == 'active') ||(repopulate_form('status') == 'active' ) ? 'selected' : '' );?>>Active</option>
										<option value='inactive' <?php echo ( ($this->uri->segment(2) != 'add' && $contents['employee']['status'] == 'inactive') ||(repopulate_form('status') == 'inactive' ) ? 'selected' : '' );?>>Inactive</option>
									</select>
								</div>
								<div class="form-group">
									<label>Non Active Date</label>
									<div class='input-group date datep'>
										<input onkeydown="return false"  id='non_active_date' name='non_active_date' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('non_active_date') == '' ?  (!empty($contents['employee']['non_active_date']) ? date('d/m/Y', strtotime($contents['employee']['non_active_date'])) : '') : repopulate_form('non_active_date')) ; ?>"
										<?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<?php endif;?>
								<div class="form-group">
									<label>Note</label>
									<textarea name='note' class="form-control" rows="3" <?php echo ($this->uri->segment(2) !='add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> ><?php echo ($this->uri->segment(2) !='add' && repopulate_form('note') == '' ? $contents['employee']['note'] : repopulate_form('note')) ; ?></textarea>
								</div>
								<div class="form-group">
									<label>Emergency Phone</label>
									<input type=text name='emergency_number' id='emergency_number' onkeypress="return isNumberKey(event)" class="form-control"
									value="<?php echo ($this->uri->segment(2) !='add' && repopulate_form('emergency_number') == '' ? $contents['employee']['emergency_number'] : repopulate_form('emergency_number')) ; ?>"
									required <?php echo ($this->uri->segment(2) !='add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Emergency Name</label>
									<input type=text name='emergency_name' id='emergency_name' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('emergency_name') == '' ? $contents['employee']['emergency_name'] : repopulate_form('emergency_name')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Emergency Relation</label>
									<input type=text name='emergency_relation' id='emergency_relation' class="form-control"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('emergency_relation') == '' ? $contents['employee']['emergency_relation'] : repopulate_form('emergency_relation')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>NPWP</label>
									<input type=text name='npwp' id='npwp' class="form-control" onkeypress="return isNumberKey(event)"
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('npwp') == '' ? $contents['employee']['npwp'] : repopulate_form('npwp')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>

						</div>
						<!-- /.col-lg-6 (nested) -->

						<div class="col-lg-12">
							<hr>
							<h3>Application </h3>
							<label>Qatracker</label>
							<?php
								$status = 'not';
								if(!empty($contents['applications']['qatracker'])){
									if($contents['applications']['qatracker']->status == 'inactive'){
										$status = 'not';
									}elseif($contents['applications']['qatracker']->type == 1){
										$status = 'admin';
									}elseif($contents['applications']['qatracker']->type == 3){
										$status = 'super_admin';
									}else{
										$status = 'tester';
									}
								}
							?>
							<div class="form-group">
								<label class="radio-inline">
									<input type="radio" name="applications[qatracker]" id="qatracker1" value="not" <?php echo ($status == 'not' ? 'checked' : '');?>>Not Registered
								</label>
								<label class="radio-inline">
									<input type="radio" name="applications[qatracker]" id="qatracker2" value="admin" <?php echo ($status == 'admin' ? 'checked' : '');?>>Admin
								</label>
								<label class="radio-inline">
									<input type="radio" name="applications[qatracker]" id="qatracker3" value="tester" <?php echo ($status == 'tester' ? 'checked' : '');?>>Tester
								</label>
								<label class="radio-inline">
									<input type="radio" name="applications[qatracker]" id="qatracker4" value="sup_admin" <?php echo ($status == 'super_admin' ? 'checked' : '');?>>Super Admin
								</label>
							</div>
						</div>
						<div class="col-lg-12">
						<hr>
							<button type="submit" class="btn btn-default">Submit Button</button>
							<button type="reset" class="btn btn-default">Reset Button</button>
							</form>
						</div>
					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
