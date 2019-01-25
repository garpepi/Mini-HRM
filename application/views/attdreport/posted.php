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
						 <form method='POST' action='<?php echo base_url();?>attdreport' role='form'>
								<div class="form-group">
									<label>Employee</label>
									<select name='employee[]' multiple='multiple' class="form-control basic-multiple" required>
											<option value="0"> All </option>
										<?php foreach($contents['employee'] as $key => $value):?>
											<option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
										<?php endforeach;?>
									</select>
								</div>
								<div class="form-group">
									<label>Month</label>
									<select name='month' class="form-control" required>
										<option value="">Select Month</option>
										<?php for ($i=1 ; $i<13; $i++):?>
											<option value='<?php echo sprintf("%02d", $i);?>' ><?php echo date('M',mktime(0, 0, 0, $i, 10));?></option>
										<?php endfor;?>
									</select>
								</div>
								<div class="form-group">
									<label>Year</label>
									<select name='year' class="form-control" required>
										<option value="">Select Year</option>
										<option value='<?php echo date('Y',strtotime('-1 years'));?>' ><?php echo date('Y',strtotime('-1 years'));?></option>
										<option value='<?php echo date('Y');?>' ><?php echo date('Y');?></option>
										<option value='<?php echo date('Y',strtotime('+1 years'));?>' ><?php echo date('Y',strtotime('+1 years'));?></option>
									</select>
								</div>
								<div class="form-group">
									<label>Client</label>
									<select name='project' id='project' class="form-control" required>
									<option value='' selected>Choose Client - Project</option>
									<?php 
										foreach($contents['projects'] as $key => $value){
											?>
											<option value='<?php echo $value['id'];?>'><?php echo $value['name'].' - '.$value['client_name'];?></option>
											<?php
										}
									?>
									</select>
								</div>
							<input type="submit" class="btn btn-default">
							<input type="submit" name="send_flag" value='Sending Email' class="btn btn-default">
						 </form>
						 <br>
						 </div>
						 <?php if(!empty($contents['attendant_posted'])):?>

						 <div class="col-lg-12">
							<div class="panel panel-default">
								<!-- /.panel-heading -->
								<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
										<thead>
											<tr>
												<th>No</th>
												<th>Name</th>
												<th>Leaves Remaining</th>
												<th>Attendance Total</th>
												<th>Daily Report Total</th>
												<th>Late Total</th>
												<th>Overtime Total</th>
												<th>Laptop + Internet Total</th>
												<th>Transport Total</th>
												<th>Meal Allowance Total</th>
												<th>Overtime Meal Allowance Total</th>
												<th>Medical</th>
												<th>Total</th>
												<th>BCA Account</th>
											</tr>
										</thead>
										<tfoot>
										<tr>
											<th colspan = 11></th>
											<th style="text-align:right">Total:</th>
											<th></th>
											<th></th>
										</tr>
									</tfoot>
										<tbody>
											<?php foreach($contents['attendant_posted'] as $key => $value):?>
												<tr>
													<td><?php echo $key+1 ; ?></td>
													<td><?php echo $value['name'] ;?></td>
													<td><?php echo $value['leaves_remaining'] ;?></td>
													<td><?php echo $value['attend_total'] ;?></td>
													<td><?php echo $value['daily_report_total'] ;?></td>
													<td><?php echo $value['late_total'] ;?></td>
													<td><?php echo $value['overtime_total'] ;?></td>
													<td><?php echo number_format($value['laptop_internet_total']) ;?></td>
													<td><?php echo number_format($value['transport_total']) ;?></td>
													<td><?php echo number_format($value['meal_allowance_total']) ;?></td>
													<td><?php echo number_format($value['overtime_meal_allowance_total']) ;?></td>
													<td><?php echo number_format($value['medical_total']) ;?></td>
													<td><?php echo number_format($value['total']) ;?></td>
													<td><?php echo $value['bank_account_number'] ;?></td>
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
	var period = '<?php echo $contents['period'] ;?>';
</script>
