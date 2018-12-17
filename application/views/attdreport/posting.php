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
						<?php if(empty($contents['attendant_period'])): ?>
						 <div class="col-lg-12">
						 <form method='POST' action='<?php echo base_url();?>attdreport/posting'>
							<form role="form">
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
									<label>Client - Project</label>
									<select name='project_id' id='project_id' class="form-control" required>
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
						 </form>
						 <br>
						 </div>
						 <?php else:?>
						 <div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Period : <?php echo $contents['period'];?>
									Client : <?php echo $contents['client_name']['name'];?>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
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
												<th>Overtime Late Night Total</th>
												<th>Laptop + Internet Total</th>
												<th>Transport Total</th>
												<th>Meal Allowance Total</th>
												<th>Overtime Meal Allowance Total</th>
												<th>Medical</th>
												<th>Total</th>
												<th>Bank Account</th>												
											</tr>
										</thead>
										<tbody>
											<?php foreach($contents['attendant_period'] as $key => $value):
												$internet_laptop = $contents['allowance']['internet_laptop']['nominal'] * $value['attend_total'];
												$transport = $contents['allowance']['transport']['nominal'] * ($value['attend_total'] - $value['late_total']);
												$meal_allowance = $contents['allowance']['meal_allowance']['nominal'] * $value['daily_report_total'];
												$overtime_meal = $contents['allowance']['overtime_meal_allowance']['nominal'] * $value['overtime_total'];
												$overtime_go_home = $contents['allowance']['overtime_go_home_allowance']['nominal'] * $value['overtime_go_home'];
												$total = $internet_laptop + $transport + $meal_allowance + $overtime_meal + $overtime_go_home + $value['medical_total'];
											?>
											
												<tr>
													<td><?php echo $key+1 ; ?></td>
													<td><?php echo $value['employee_data']['name'] ;?></td>
													<td><?php echo $value['employee_data']['cuti_limit'] - $value['leaves_total'] ;?></td>
													<td><?php echo $value['attend_total'] ;?></td>
													<td><?php echo $value['daily_report_total'] ;?></td>
													<td><?php echo $value['late_total'] ;?></td>
													<td><?php echo $value['overtime_total'] ;?></td>
													<td><?php echo $value['overtime_go_home'] ;?></td>
													<td><?php echo number_format($internet_laptop) ;?></td>
													<td><?php echo number_format($transport) ;?></td>
													<td><?php echo number_format($meal_allowance) ;?></td>
													<td><?php echo number_format($overtime_meal + $overtime_go_home) ;?></td>
													<td><?php echo number_format($value['medical_total']) ;?></td>
													<td><?php echo number_format($total);?></td>
													<td><?php echo $value['employee_data']['bank_account'] ;?></td>
												</tr>
											<?php endforeach;?>
											
										</tbody>
									</table>
									<!-- /.table-responsive -->
								</div>
								<!-- /.panel-body -->
							</div>
							<!-- /.panel -->
						</div>
						<!-- /.col-lg-12 -->
						<div class="col-lg-12">
						<form onsubmit="return confirm('Do you really want to post for period <?php echo $contents['period'];?> ?');" action='<?php echo base_url().'attdreport/dopost/'.$contents['period'].'/'.$contents['client_id'].'/'.$contents['project_id'] ; ?>' method='post'>
							<button type="submit" class="btn btn-default">Post</button>
						</form>
						</div>
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
