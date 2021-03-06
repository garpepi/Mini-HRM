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
							<form role="form" method='post' action=<?php echo base_url().($this->uri->segment(2)!= 'add' && $this->uri->segment(2) == 'edit' ? 'medical/edit/'.$contents['medical']['id'] : ($this->uri->segment(2)=='view' ? '#' : 'medical/add' ));?>>
								<?php if($this->uri->segment(2) != 'add'):?>
								<div class="form-group">
									<label>Medical Reimbursment Id</label>
									<input type=number min=1 name='medical_id' id='medical_id' class="form-control"
									value="<?php echo $contents['medical']['id']; ?>" 
									required disabled>
								</div>	
								<?php endif;?>								
							</div>
						<div class="col-lg-6">
							<?php if($this->uri->segment(2) == 'add') :?>
								<div class="form-group">
									<label>Employee </label>
									<select name='emp_id' id='emp_id' class="form-control" required>
										<option value=''> Select Employee</option>
										<?php foreach($contents['employee'] as $key=>$value ):?>
											<?php if($this->uri->segment(2) != 'add' ) : // edit/view
													if((repopulate_form('emp_id') != '' && repopulate_form('emp_id') == $value['id'] ) || (repopulate_form('emp_id') == '' && $contents['medical']['emp_id'] == $value['id']) ) :?>
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
									<input type=hidden name='emp_id' id='emp_id' class="form-control" value="<?php echo $contents['medical']['emp_id']?>">
									<input type=text name='emp_name' class="form-control" value="<?php echo $contents['medical']['employee_name']?>" disabled>
								</div>
								<?php endif;?>
								<div class="form-group">
									<label>Date of Reimbursment</label>
									<div class='input-group date' >
										<input onkeydown="return false" id='date' name='date' type='text' class="form-control" 
										value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('date') == '' ? date('d/m/Y', strtotime($contents['medical']['date'])) : repopulate_form('date')) ; ?>"
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Real Nominal </label>
									<input type=number min=0 name='real_nominal' id='real_nominal' class="form-control" 
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('real_nominal') == '' ? $contents['medical']['real_nominal'] : repopulate_form('real_nominal')) ; ?>" 
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>Accepted Nominal </label>
									<input readonly type=number min=0 name='nominal' id='nominal' class="form-control" 
									value="<?php echo ($this->uri->segment(2) != 'add' && repopulate_form('nominal') == '' ? $contents['medical']['nominal'] : repopulate_form('nominal')) ; ?>" 
									required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
								</div>
								<div class="form-group">
									<label>	Ballance </label>
									<span>IDR </span><span id="balance">0</span>
								</div>
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