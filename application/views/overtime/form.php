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
							<form role="form" method='post' action=<?php echo base_url().($this->uri->segment(2)!= 'add' && $this->uri->segment(2) == 'edit' ? 'overtime/edit/'.$contents['overtime']['id'] : ($this->uri->segment(2)=='view' ? '#' : 'overtime/add' ));?>>
							</div>
						<div class="col-lg-6">
							<?php if($this->uri->segment(2) == 'add') :?>
								<div class="form-group">
									<label>Employee </label>
									<select name='emp_id' id='emp_id' class="form-control" required>
										<option value=''> Select Employee</option>
										<?php foreach($contents['employee'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('emp_id') != '' && repopulate_form('emp_id') == $value['id'] ) || (repopulate_form('emp_id') == '' && $contents['overtime']['emp_id'] == $value['id']) ) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else  :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php else : // normal
													if(repopulate_form('emp_id') != '' && repopulate_form('emp_id') == $value['id']) :?>
														<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
													<?php else :?>
														<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
													<?php endif ;?>
											<?php endif ;?>

										<?php endforeach;?>
									</select>
								</div>
								<?php else:?>
								<div class="form-group">
									<label>Employee </label>
									<input type=hidden name='emp_id' id='emp_id' class="form-control" value="<?php echo $contents['overtime']['emp_id']?>">
									<input type=text name='emp_name' class="form-control" value="<?php echo $contents['overtime']['employee_name']?>" disabled>
								</div>
								<?php endif;?>
								<div class="form-group">
									<label>Request date for</label>
									<div class='input-group date' >
										<input onkeydown="return false" id='date' name='date' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('date') == '' ? date('d/m/Y', strtotime($contents['overtime']['date'])) : repopulate_form('date')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Reason </label>
									<textarea required name='reason' class="form-control" rows="3" <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>><?php echo ($this->uri->segment(2) != 'add' && repopulate_form('reason') == '' ? $contents['overtime']['reason'] : repopulate_form('reason')) ; ?></textarea>

								</div>
								<?php if($this->uri->segment(2) != 'add' && isset($contents['overtime']['time_go_home']) && $contents['overtime']['time_go_home'] != NULL) :?>
								<div class="form-group">
									<label>Time go Home</label>
									<div class='input-group time' >
										<input onkeydown="return false" id='time_go_home' name='time_go_home' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('time_go_home') == '' ? $contents['overtime']['time_go_home'] : repopulate_form('time_go_home')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</span>
									</div>
								</div>
								<?php else:?>
								<div class="form-group">
									<label>Start Overtime</label>
									<div class='input-group datetimes' >
										<input onkeydown="return false" name='start_in' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('start_in') == '' ? $contents['overtime']['start_in'] : repopulate_form('start_in')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>End Overtime</label>
									<div class='input-group datetimes' >
										<input onkeydown="return false" name='end_out' type='text' class="form-control"
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('end_out') == '' ? $contents['overtime']['end_out'] : repopulate_form('end_out')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</span>
									</div>
								</div>
								<?php endif;?>

						</div>
						<!-- /.col-lg-6 (nested) -->
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
