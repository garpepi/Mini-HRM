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
						<form role="form" method='post' action='<?php echo base_url().'client/'.($this->uri->segment(2) == 'edit' ? 'edit/'.$contents['data']['id'] : 'add');?>'>
						<div class="col-lg-6">
								<div class="form-group">
									<label>Name</label>
									<input name='name' class="form-control" value ='<?php echo (!empty($contents['data']['name']) ? (repopulate_form('name') != '' ? repopulate_form('name') : $contents['data']['name']) : '')?>' required>
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
								<button type="reset" class="btn btn-default">Reset </button>
						</div>
						<div class="col-lg-6">
								<?php if($this->uri->segment(2) == 'edit'):?>
								<div class="form-group">
									<label>Status</label>
									<select class="form-control" name='status' required>
										<option value=1 <?php echo ($contents['data']['status'] == '1' ? ((repopulate_form('status') == 'active') ? 'selected' : '') : '');?> >active</option>
										<option value=0 <?php echo ($contents['data']['status'] == '0' ? ((repopulate_form('status') == 'inactive') ? 'selected' : '') : '');?>>inactive</option>
									</select>
								</div>
								<?php endif;?>
							</form>
						</div>
						<?php if($this->uri->segment(2)!= 'edit'):?>
						<div class="col-lg-12">
							<hr>
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
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'client/edit/'.$value['id'];?>'><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-secondary btn-x confirmation" href='<?php echo base_url().'client/revoke/'.$value['id'];?>'><i class="fa fa-exchange"></i> Change Status</a>
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
														<a class="btn btn-secondary btn-xs confirmation" href='<?php echo base_url().'client/reactivate/'.$value['id'];?>'><i class="fa fa-exchange"></i> Change Status</a>
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
