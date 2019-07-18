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
					<?= $box_title_1?> List
				</div>
				<div class="panel-body">
					<form role="form" method='post' action=<?php echo base_url().'overtime/raw_queue'?>>
						<div class="row">
							<div class="col-md-2 pull-right">
								<input type = "submit" class="btn btn-default"/>
							</div>
							<div class="col-md-12">
								<table width="100%" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>No</th>
											<th>Date</th>
											<th>EID</th>
											<th>Emply Name</th>
											<th>Sys Emply Name </th>
											<th>In</th>
											<th>Out</th>
											<th>Action</th>	
										</tr>
									</thead>
									<tbody>
									<?php foreach($contents['queue'] as $key => $value) 
									{?>
										<tr>
											<td><?php echo $value['no'];?></td>
											<td><?php echo site_show_date_format($value['date']);?></td>
											<td><?php echo $value['emp_id'];?></td>
											<td><?php echo $value['name'];?></td>
											<td><?php echo $value['employee_name'];?></td>
											<td><?php echo date("d/m/Y H:i:s" , strtotime($value['start_in']));?></td>
											<td><?php echo date("d/m/Y H:i:s" , strtotime($value['end_out']));?></td>
											<td>
											<label class="radio-inline">
											  <input type=radio name="status[<?php echo $value['id'];?>]" value='accepted' required >Accept
											</label>
											<label class="radio-inline">
											  <input type=radio name="status[<?php echo $value['id'];?>]" value='reject' >Reject
											</label>
											</td>	
										</tr>
									<?php }?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /.table-responsive -->
						<div class="row">
							<div class="col-md-2 pull-right">
							<input type = "submit" class="btn btn-default"/>
							</div>
						</div>						
					</form>
				<!-- /.panel-body -->
				</div>
			<!-- /.panel -->
		</div>
		<div class="panel panel-default">
				<div class="panel-heading">
					<?= $box_title_1?> Reject List
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-rejected">
						<thead>
							<tr>
								<th>No</th>
								<th>Date</th>
								<th>EID</th>
								<th>Emply Name</th>
								<th>Sys Emply Name </th>
								<th>In</th>
								<th>Out</th>
								<th>Status</th>
								<th>Desc Status</th>
							</tr>
						</thead>
						<tbody>
							
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