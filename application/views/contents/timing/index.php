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
						<?php if($this->uri->segment(2)== 'edit'):?>
						<div class="col-lg-12">
							<div class="col-lg-12">
								<form role="form" method='post' action='<?php echo base_url().'attendancetiming/'.($this->uri->segment(2) == 'edit' ? 'edit/'.$contents['data']['id'] : 'add');?>'>
									<div class="form-group">
										<label>Name</label>
										<input disabled class="form-control" value ='<?php echo $contents['data']['showed_name'] ;?>' >
									</div>
									<div class="form-group">
										<label>Client</label>
										<input disabled class="form-control" value ='<?php echo $contents['data']['client_name'] ;?>' >
									</div>
									<div class="form-group date">
										<label>Time</label>
										<input name='time' class="form-control timeep" value ='<?php echo (repopulate_form('time') != '' ? repopulate_form('time'): $contents['data']['time']) ;?>' timeep>
									</div>
									<button type="submit" class="btn btn-default">Submit</button>
									<button type="reset" class="btn btn-default">Reset </button>
								</form>
							</div>
							<?php else:?>
							<div class="col-lg-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										Attendance Timing
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-active">
											<thead>
												<tr>
													<th>Client</th>
													<th>Name</th>
													<th>Time</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($contents['table_active'] as $key => $value) :?>
												<tr>
													<td><?php echo $value['client_name']; ?></td>
													<td><?php echo $value['showed_name']; ?></td>
													<td><?php echo $value['time']; ?></td>
													<td>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'attendancetiming/edit/'.$value['id'];?>'><i class="fa fa-edit"></i> Edit</a>
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