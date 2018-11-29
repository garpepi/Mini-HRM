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
								<form role="form" method='post' action='<?php echo base_url().'holiday/'.($this->uri->segment(2) == 'edit' ? 'edit/'.$contents['data']['id'] : 'add');?>'>
									<div class="form-group">
										<label>Name</label>
										<input name='name' class="form-control" value ='<?php echo ($this->uri->segment(2) == 'edit' ? (repopulate_form('name') != '' ? repopulate_form('name') : $contents['data']['name'] ): '') ;?>' >
									</div>
									<div class="form-group">
										<label>Holiday Date</label>
										<div class='input-group date' >
											<input onkeydown="return false" id='date' name='date' type='text' class="form-control" 
											value="<?php echo ($this->uri->segment(2) == 'edit' && repopulate_form('date') == '' ? date('d/m/Y', strtotime($contents['data']['date'])) : repopulate_form('date')) ; ?>"
										required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?> >
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</div>
									<button type="submit" class="btn btn-default">Submit</button>
									<button type="reset" class="btn btn-default">Reset </button><br><br>
								</form>
							</div>
							
							<?php if($this->uri->segment(2)!= 'edit'):?>
							<div class="col-lg-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										Holiday List Active
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-active">
											<thead>
												<tr>
													<th>Name</th>
													<th>Date</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($contents['table_active'] as $key => $value) :?>
												<tr>
													<td><?php echo $value['name']; ?></td>
													<td><?php echo date('Y-M-d',strtotime($value['date'])); ?></td>
													<td>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'holiday/edit/'.$value['id'];?>'><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'holiday/revoke/'.$value['id'];?>'><i class="fa fa-edit"></i> Deactivate</a>
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
										Holiday List Inctive
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-inactive">
											<thead>
												<tr>
													<th>Name</th>
													<th>Date</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($contents['table_inactive'] as $key => $value) :?>
												<tr>
													<td><?php echo $value['name']; ?></td>
													<td><?php echo date('Y-M-d',strtotime($value['date'])); ?></td>
													<td>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'holiday/reactivate/'.$value['id'];?>'><i class="fa fa-edit"></i> Activate</a>
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