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
						 <?php if(empty($contents['attendant_posted'])):?>
						 <form method='POST' action='<?php echo base_url();?>attendance/input' >
							<form role="form">
								<div class="form-group">
									<label>Emplyoee</label>
									<select name='emp_id' class="form-control" required>
										<option value="">Select Employee</option>
										<?php foreach($contents['employee'] as $key => $value) :?>
											<option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
										<?php endforeach ;?>
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
							<input type="submit" class="btn btn-default">
						 </form>
						 <?php endif;?>
						 </div>
						 <div class="col-lg-12">
							<div class="panel-body">
								<div class="well">
									<h5><?php echo (empty($contents['attendant_detail']) ? 'No Data Available' : $contents['employee_selected'][0]['name']);?></h5>
								</div>
								<?php if(!empty($contents['attendant_detail'])):
									if(!$contents['status_view'])
									{
										echo '<a href='.base_url().'attendance/regenerate/'.$contents['attendant_period'][0]['id'].'>Re-Generate Data!</a>';										
									}
								?>
								<form method='POST' action='<?php echo base_url().'attendance/edit/'.$contents['attendant_period'][0]['id'];?>' id="formulir">
								<table width="100%" class="table table-bordered table-hover" id="dataTables-example">
									<thead>
										<tr>
											<th>Date</th>
											<th>Arrived</th>
											<th>Return</th>
											<th>Attend</th>
											<th>Late</th>
											<th>Leaves</th>
											<th>Sick</th>
											<th>Overtime (not weekend)</th>
											<th>Overtime (Late Night)</th>
											<th>Daily Report</th>
											<th>Medical</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach($contents['attendant_detail'] as $key => $value) :?>
										<tr <?php 
												$leaves_flag = 0;
												$sick_flag = 0;
												
												foreach($contents['sick'] as $sick) 
												{
													if($sick['date'] == $value['date'])
													{
														$sick_flag = 1;
														break;
													}
												}
												
												foreach($contents['leaves'] as $leaves) 
												{
													if($leaves['date'] == $value['date'])
													{
														$leaves_flag = 1;
														break;
													}
												}
												if(date('D',strtotime($value['date']))=='Sun' || date('D',strtotime($value['date']))=='Sat' || in_array($value['date'], $contents['holiday']) ){
													echo 'bgcolor = #ed6363';
												}
												if($leaves_flag == 1)
												{
													echo 'bgcolor = #FFFF00';
												}
												if($sick_flag == 1)
												{
													echo 'bgcolor = #00b200';
												}
											?> >
											<td>
												<input name='id[]' type='hidden' value="<?php echo $value['id'];?>">
												<input name='date[]' type='hidden' value="<?php echo $value['date'];?>">
												<?php echo $value['date'];?>												
											</td>
											<td>
												<div class='input-group date' >
													<input  name='arrived[]' type='text' class="form-control timeep arrived" value="<?php echo $value['arrived'];?>" timeep >
												</div>
											</td>
											<td>
												<div class='input-group date' >
													<input  name='returns[]' type='text' class="form-control timeep returns" value="<?php echo $value['returns'];?>" timeep >
												</div>
											</td>
											<td>
												<div class="input-group">										
													<input  name="attend[]" class="form-control attend" value="<?php echo ($leaves_flag == 1 || $sick_flag == 1 ? 0 : $value['attend']);?>" type=number max=1 min=0>													
												</div>
											</td>
											<td>
												<div class="input-group">
													<input  name="late[]" class="form-control late" value="<?php echo $value['late'];?>" type=number max=1 min=0>
												</div>
											</td>
											<td>
												<div class="input-group">
													<input  name="leaves[]" class="form-control leaves" value="<?php echo ($leaves_flag == 1 ? 1 : $value['leaves']);?>" type=number max=1 min=0>
												</div>
											</td>
											<td>
												<?php echo ($sick_flag != 0 ? '1' : '');?>
												<input type='hidden' name='sicks[]' value=<?php echo $sick_flag;?>>
											</td>
											<td>
												<?php foreach($contents['overtime'] as $overtime) :
														echo (($overtime['date'] == $value['date'] ? 1 : '') );
												endforeach;?>
											</td>
											<td>
												<?php foreach($contents['overtime'] as $overtime) :
														echo (($overtime['date'] == $value['date'] && $overtime['time_go_home'] > '00:00:00' && $overtime['time_go_home'] < '06:00:00'  ? 1 : '') );
												endforeach;?>
											</td>
											<td>
												<?php echo $value['daily_report'];?>
												<input type='hidden' name='daily_reportx[]' value=<?php echo $value['daily_report'];?>>
											</td>
											<td>
												<?php 
												$tmp_med = 0;
												foreach($contents['medical'] as $med_reimburse) :
														$tmp_med = $tmp_med + ($med_reimburse['date'] == $value['date'] ?  $med_reimburse['nominal'] : 0);
												endforeach;
												echo number_format($tmp_med) ;
												?>
											</td>
										</tr>
									<?php endforeach;?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3"><b> Total </b></td>
											<td><b><span id='span_total_attend'><?php echo $contents['attendant_period'][0]['attend_total'];?></span></b></td>
											<td><b><span id='span_total_late'><?php echo $contents['attendant_period'][0]['late_total'];?></span></b></td>
											<td><b><span id='span_total_leaves'><?php echo $contents['attendant_period'][0]['leaves_total'];?></span></b></td>
											<td><b><span id='span_total_sick'><?php echo $contents['attendant_period'][0]['sick_total'];?></span></b></td>
											<td><b><span id='span_total_overtime'><?php echo $contents['attendant_period'][0]['overtime_total'];?></span></b></td>
											<td><b><span id='span_total_overtime'><?php echo $contents['attendant_period'][0]['overtime_go_home'];?></span></b></td>
											<td><b><?php echo $contents['attendant_period'][0]['daily_report_total'];?></b></td>
											<td><b><span id='span_total'><?php echo number_format ( $contents['attendant_period'][0]['medical_total'] );?></span></b></td>
										</tr>
									</tfoot>
								</table>
								<br>
								<button id="submit_input" class="btn btn-default">Calculate </button>
								</form>
								<a href="<?php echo base_url().'attendance/input';?>" class="btn btn-default">Back</a>
								<?php endif;?>
								<br>
								
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
<?php if(!empty($contents['attendant_detail'])):?>
<script>
var posting = '<?php echo base_url().'attendance/edit/'.$contents['attendant_detail'][0]['id'];?>' ;
var status_view = <?php echo $contents['status_view'];?> ;
</script>
<?php endif;?>