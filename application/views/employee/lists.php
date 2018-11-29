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
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Employee ID</th>
								<th>Finger ID</th>
								<th>Full Name</th>
								<th>Email</th>
								<th>Status</th>
								<th>Action</th>	
							</tr>
						</thead>
						<tbody>
							<?php foreach($contents['employee'] as $key => $value):?>
								<tr>
									<td  class="center"><?php echo $value['id'];?></td>
									<td><?php echo $value['finger_id'];?></td>
									<td><?php echo $value['name'];?></td>
									<td><?php echo $value['email'];?></td>
									<td><?php echo $value['status'];?></td>
									<td>
										<a href="<?php echo base_url().'employee/edit/'.$value['id'];?>" class="btn btn-secondary btn-xs"><i class="fa fa-edit"></i> Edit</a>
										<a href="<?php echo base_url().'employee/view/'.$value['id'];?>" class="btn btn-secondary btn-xs"><i class="fa fa-share-square-o"></i> View</a>
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