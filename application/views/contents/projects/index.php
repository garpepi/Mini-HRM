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
						<?php if($this->uri->segment(2)!= 'edit'):?>
						<div class="col-lg-12">
							<div class="col-lg-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										Active List
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-active">
											<thead>
												<tr>
													<th>Name</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($contents['table_active'] as $key => $value) :?>
												<tr>
													<td><?php echo $value['name']; ?></td>
													<td>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'projects/edit/'.$value['id'];?>'><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-secondary btn-x confirmation" href='<?php echo base_url().'projects/revoke/'.$value['id'];?>'><i class="fa fa-exchange"></i> Change Status</a>
													</td>
												</tr>
												<?php endforeach;?>
											</tbody>
										</table>
										<!-- /.table-responsive -->
									</div>
									<!-- /.panel-body -->
								</div>
							</div>
							<div class="col-lg-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										Inactive List
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-inactive">
											<thead>
												<tr>
													<th>Name</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($contents['table_inactive'] as $key => $value) :?>
												<tr>
													<td><?php echo $value['name']; ?></td>
													<td>
														<a class="btn btn-secondary btn-xs confirmation" href='<?php echo base_url().'projects/reactivate/'.$value['id'];?>'><i class="fa fa-exchange"></i> Change Status</a>
													</td>
												</tr>
												<?php endforeach;?>
											</tbody>
										</table>
										<!-- /.table-responsive -->
									</div>
									<!-- /.panel-body -->
								</div>
							</div>
						</div>
						<?php endif;?>
						<hr>
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<?php echo ($this->uri->segment(2) == 'edit' ? 'Edit Projects' : 'New Projects');?>
								</div>
								<div class="panel-body">				
									<form role="form" method='post' action='<?php echo base_url().'projects/'.($this->uri->segment(2) == 'edit' ? 'edit/'.$contents['data']['id'] : 'add');?>'>
										<div class="col-lg-12">
											<div class="col-lg-6">
												<div class="form-group">
													<label>Client</label>
													<select name='client_id' id='client' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
														<?php foreach($contents['client'] as $key=>$value ):?>
															<?php
															if($this->uri->segment(2) == 'edit')
															{
																if(!empty($contents['data']['client_id']) && $contents['data']['client_id'] == $value['id']) :?>
																			<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
																		<?php else :?>
																			<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
																<?php endif ;	
															}else
															{
																if(repopulate_form('client_id') != '' && repopulate_form('client_id') == $value['id']) :?>
																			<option value='<?php echo $value['id'];?>' selected><?php echo $value['name'];?></option>
																		<?php else :?>
																			<option value='<?php echo $value['id'];?>'><?php echo $value['name'];?></option>
																<?php endif ;																
															}
														endforeach;?>
													</select>
												</div>
												<div class="form-group">
													<label>Project Name</label>
													<input name='name' class="form-control" value ='<?php echo (!empty($contents['data']['name']) ? (repopulate_form('name') != '' ? repopulate_form('name') : $contents['data']['name']) : '')?>' required>
												</div>
												<div class="form-group checkbox">
													  <label><input name="leaves_sub" type="checkbox" value="1"<?php echo (!empty($contents['data']['leaves_sub']) ? 'checked' : '');?>>Subtitute Overtime to Leaves</label>
												</div>
												<?php if($this->uri->segment(2) != 'edit'):?>
												<div class="form-group">
													<label>Meal Allowance</label>
													<input type ='number' name='meal_allowance' class="form-control" value ='<?php echo (!empty($contents['data']['meal_allowance']) ? (repopulate_form('meal_allowance') != '' ? repopulate_form('meal_allowance') : $contents['data']['meal_allowance']) : '')?>' required>
												</div>
												<div class="form-group">
													<label>Transport</label>
													<input type ='number' name='transport' class="form-control" value ='<?php echo (!empty($contents['data']['transport']) ? (repopulate_form('transport') != '' ? repopulate_form('transport') : $contents['data']['transport']) : '')?>' required>
												</div>
												<?php endif;?>
											</div>
											<div class="col-lg-6">
												<?php if($this->uri->segment(2) != 'edit'):?>
													<div class="form-group">
														<label>Internet & Laptop</label>
														<input type ='number' name='internet_laptop' class="form-control" value ='<?php echo (!empty($contents['data']['internet_laptop']) ? (repopulate_form('internet_laptop') != '' ? repopulate_form('internet_laptop') : $contents['data']['internet_laptop']) : '')?>' required>
													</div>
													<div class="form-group date">
														<label>Come In</label>
														<input name='time_in' class="form-control timeep" value ='<?php echo (!empty($contents['data']['time_in']) ? (repopulate_form('time_in') != '' ? repopulate_form('time_in'): $contents['data']['time_in']) : '') ;?>' timeep>
													</div>
													<div class="form-group date">
														<label>Go Home</label>
														<input name='time_out' class="form-control timeep" value ='<?php echo (!empty($contents['data']['time_out']) ? (repopulate_form('time_out') != '' ? repopulate_form('time_out'): $contents['data']['time_out']) : '') ;?>' timeep>
													</div>
													<div class="form-group date">
														<label>Start Overtime</label>
														<input name='time_overtime' class="form-control timeep" value ='<?php echo (!empty($contents['data']['time_overtime']) ? (repopulate_form('time_overtime') != '' ? repopulate_form('time_overtime'): $contents['data']['time_overtime']) : '') ;?>' timeep>
													</div>
													<?php endif;?>
													<?php if($this->uri->segment(2) == 'edit'):?>
													<div class="form-group">
														<label>Status</label>
														<select class="form-control" name='status' required>
															<option value=1 <?php echo ($contents['data']['status'] == '1' ? ((repopulate_form('status') == 'active') ? 'selected' : '') : '');?> >active</option>
															<option value=0 <?php echo ($contents['data']['status'] == '0' ? ((repopulate_form('status') == 'inactive') ? 'selected' : '') : '');?>>inactive</option>
														</select>
													</div>
													<?php endif;?>
											</div>
										</div>
										<?php if($this->uri->segment(2) != 'edit'):?>
										<label>Overtimes</label>
										<div class="col-lg-12">
											<div class="col-lg-6">
												<?php for($i=1;$i<=15;$i++){ $param = "overtime_".$i."h";?>
												<div class="form-group">
													<label>Overtime  <?php echo $i;?> Hour</label>
													<input type ='number' name='overtime[]' class="form-control" value ='<?php echo (!empty($contents['data'][$param]) ? (repopulate_form($param) != '0' ? repopulate_form($param) : $contents['data'][$param]) : '0')?>' required>
												</div>
												<?php }?>
											</div>
											<div class="col-lg-6">
												<?php for($i=1;$i<=15;$i++){ $param = "overtime_we_".$i."h";?>
												<div class="form-group">
													<label>Weekend Overtime  <?php echo $i;?> Hour</label>
													<input type ='number' name='we_overtime_[]' class="form-control" value ='<?php echo (!empty($contents['data'][$param]) ? (repopulate_form($param) != '0' ? repopulate_form($param) : $contents['data'][$param]) : '0')?>' required>
												</div>
												<?php }?>
											</div>
										</div>
										<?php endif;?>
										<div class="col-lg-12">
											<button type="submit" class="btn btn-default">Submit</button>
											<button type="reset" class="btn btn-default">Reset </button>
										</div>
									</form>
								</div>
							</div>
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