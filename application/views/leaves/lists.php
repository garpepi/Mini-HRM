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
					<?= $box_title_1?> Active List
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-active">
						<thead>
							<tr>
								<th>Date</th>
								<th>Employee</th>
								<th>Reason</th>
								<th>Action</th>	
							</tr>
						</thead>
						<tbody>
							<?php foreach($contents['active_table'] as $key => $value):?>
								<tr>
									<td><?php echo $value['date'];?></td>
									<td><?php echo $value['employee_name'];?></td>
									<td><?php echo $value['reason'];?></td>
									<td>
										<a href="<?php echo base_url().'leaves/edit/'.$value['id'];?>" class="btn btn-secondary btn-xs"><i class="fa fa-edit"></i> Edit</a>
										<a href="<?php echo base_url().'leaves/revoke/'.$value['id'];?>" class="btn btn-secondary btn-xs"><i class="fa fa-edit"></i> Revoke</a>
									</td>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>
					<!-- /.table-responsive -->
				<!-- /.panel-body -->
				</div>
			<!-- /.panel -->
		</div>
		<div class="panel panel-default">
				<div class="panel-heading">
					<?= $box_title_1?> Inactive List
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-inactive">
						<thead>
							<tr>
								<th>Date</th>
								<th>Employee</th>
								<th>Reason</th>
								<th>Action</th>	
							</tr>
						</thead>
						<tbody>
							<?php foreach($contents['inactive_table'] as $key => $value):?>
								<tr>
									<td><?php echo $value['date'];?></td>
									<td><?php echo $value['employee_name'];?></td>
									<td><?php echo $value['reason'];?></td>
									<td>
										<a href="<?php echo base_url().'leaves/reactivate/'.$value['id'];?>" class="btn btn-secondary btn-xs"><i class="fa fa-edit"></i> Reactivate</a>
									</td>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>
					<!-- /.table-responsive -->
				<!-- /.panel-body -->
				</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->