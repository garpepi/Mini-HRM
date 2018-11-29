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
							<form role="form" method='post' action=<?php echo base_url().'reports/summattendance/proc';?>>							
							</div>
						<div class="col-lg-6">							
								<div class="form-group">
									<label>Request date from</label>
									<div class='input-group datefrom' >
										<input onkeydown="return false" id='date-from' name='date-from' type='text' class="form-control" value="" required >
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Request date to</label>
									<div class='input-group dateto' >
										<input onkeydown="return false" id='date-to' name='date-to' type='text' class="form-control" value="" required >
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Client</label>
									<select name='client' id='client' class="form-control" required>
									<option value='' selected>Choose Client</option>
									<?php 
										foreach($contents['clients'] as $key => $value){
											?>
											<option value='<?php echo $value['id'];?>' ><?php echo $value['name'];?></option>
											<?php
										}
									?>
									</select>
								</div>
						</div>						
						<!-- /.col-lg-6 (nested) -->
						<div class="col-lg-12">
						<hr>
							<button type="submit" class="btn btn-default">Submit Button</button>
												<br><br><br>
							</form>							
						</div>
					</div>

					<?php if(!empty($contents['leaves_data'])):?>
					 <div class="col-lg-12">
						<div class="panel panel-default">
							<!-- /.panel-heading -->
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
									<thead>
										<tr>
											<th>No</th>
											<th>Name</th>
											<th>Attendance Total</th>
											<th>Daily Report Total</th>
											<th>Late Total</th>
											<th>Overtime Total</th>
											<th>Leaves Total</th>
											<th>Leaves Remaining</th>
											<th>Leaves Used Total</th>										
										</tr>
									</thead>
									<tfoot>
								</tfoot>
									<tbody>
										<?php $no = 0;foreach($contents['leaves_data'] as $key => $value):?>
											<tr>
												<td><?php echo ++$no ; ?></td>
												<td><?php echo $value['name'] ;?></td>
												<td><?php echo $value['attend_total'] ;?></td>
												<td><?php echo $value['daily_report_total'] ;?></td>
												<td><?php echo $value['late_total'] ;?></td>
												<td><?php echo $value['overtime_total'] ;?></td>
												<td><?php echo $value['leaves_total_calc'] ;?></td>
												<td><?php echo $value['leaves_remaining'] ;?></td>
												<td><?php echo $value['leaves_total_used'] ;?></td>
											</tr>
										<?php endforeach;?>
										
									</tbody>
								</table>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
						<!-- /.col-lg-12 -->
						 <?php endif;?>
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
<script>
var period = '<?php echo $contents['period'];?>';
</script>
