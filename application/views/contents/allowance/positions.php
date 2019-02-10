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
							<a href="<?php echo base_url().'allowance/set/'.$contents['data']['project'].'/'.$contents['data']['position'] ;?>" class="btn btn-default" role="button" > Set Allowance</a>
						</div>
						<hr><hr>
						<div class="col-lg-12">
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-active">
								<thead>
									<tr>
										<th>Client</th>
										<th>Name</th>
										<th>Nominal</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($contents['table_active'] as $key => $value) :?>
									<tr>
										<td><?php echo $value['client_name']; ?></td>
										<td><?php echo $value['showed_name']; ?></td>
										<td><?php echo number_format($value['nominal']); ?></td>
										<td>
											<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'allowance/edit/'.$value['id'];?>'><i class="fa fa-edit"></i> Edit</a>
										</td>
									</tr>
									<?php endforeach;?>
								</tbody>
							</table>
							<!-- /.table-responsive -->
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