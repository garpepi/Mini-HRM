<div >
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
					<?php //if(!empty($contents['inout_data'])):?>
					 <div class="col-lg-12">
						<div class="panel panel-default">
							<!-- /.panel-heading -->
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-examples">
									<thead>
										<tr>
											<th>No</th>
											<th>Name</th>
											<th>Date</th>
											<?php $loop_date = $contents['period'];for($i=1;$i <= date("t",strtotime($contents['period']));$i++):
												$day = date("D",strtotime($loop_date));
											?>
												<th><?php echo $i.' / '.$day ;?></th>
											<?php 
												$loop_date = date("Y-m-d",strtotime($loop_date. "+1 days"));
											endfor;?>
											<th>Late Total</th>
											<th>Daily Report Total</th>
											<th>Leaves Total</th>
											<th>Sick Total</th>
											<th>Overtime Total</th>
											<th>Attend Total</th>
											<th>Leaves Remaining Total </th>
										</tr>
									</thead>
									<tfoot>
								</tfoot>
									<tbody>
										<?php $no = 0;foreach($contents['inout_data'] as $key => $value): $no++;?>
											<tr>
												<td><?php echo $no;?></td>
												<td><?php echo $value['name'];?></td>
												<td>in</td>
												<?php $i=1;
												$date = $contents['period'];
												foreach($value['absen'] as $dates => $data_absen):
													$day = date('w', strtotime($dates));
													$color = 'ffffff';
													if($day == 0 || $day == 6 || in_array($dates, $contents['holiday']))
													{
														$color = 'ed6363';														
													}else{
														if($data_absen['status'] == '' || $data_absen['status'] == 'LV')
														{
															$color = 'FFFF00';														
														}
													}
													
													if($data_absen['status'] == 'OV')
													{
														//overtime
														$color = '7077fd';														
													}
													if($data_absen['status'] == 'S')
													{
														//sick
														$color = '00b200';														
													}
													if($data_absen['status_in'] == 'LT')
													{
														//late
														$color = 'C38ED3';														
													}
													
												?>
													<td bgcolor="#<?php echo $color;?>"><?php echo $data_absen['in'] ;?></td>
												<?php endforeach;?>
												<td><?php echo $data_absen['late_total'];?></td>
												<td><?php echo $data_absen['daily_report_total'];?></td>
												<td><?php echo $data_absen['leaves_total'];?></td>
												<td><?php echo $data_absen['sick_total'];?></td>
												<td><?php echo $data_absen['overtime_total'];?></td>
												<td><?php echo $data_absen['attend_total'];?></td>
												<td><?php echo $data_absen['leaves_remaining'];?></td>
											</tr>
											<tr>
												<td><?php //echo $no;?></td>
												<td><?php //echo $value['name'];?></td>
												<td>out</td>
												<?php $i=1;
												$date = $contents['period'];
												foreach($value['absen'] as $dates => $data_absen):
													$day = date('w', strtotime($dates));
													$color = 'ffffff';
													if($day == 0 || $day == 6 || in_array($dates, $contents['holiday']))
													{
														$color = 'ed6363';														
													}else{
														if($data_absen['status'] == '' || $data_absen['status'] == 'LV')
														{
															$color = 'FFFF00';														
														}
													}
													
													if($data_absen['status'] == 'LT')
													{
														//late
														$color = 'C38ED3';														
													}
													if($data_absen['status'] == 'OV')
													{
														//overtime
														$color = '7077fd';														
													}
													if($data_absen['status'] == 'S')
													{
														//sick
														$color = '00b200';														
													}
													
												?>
													<td bgcolor="#<?php echo $color;?>"><?php echo $data_absen['out'] ;?></td>
												<?php endforeach;?>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										<?php endforeach;?>
										
									</tbody>
								</table>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
						<!-- /.col-lg-12 -->
						 <?php //endif;?>
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
var period = '<?php echo date('Y-m',strtotime($contents['period']));?> ';
</script>
