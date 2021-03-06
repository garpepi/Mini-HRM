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
							<form role="form" method='post' action=<?php echo base_url().'reports/inoutattendance/proc';?>>							
							</div>
						<div class="col-lg-6">							
								<div class="form-group">
									<label>Request Period </label>
									<div class='input-group datefrom' >
										<input onkeydown="return false" id='date-from' name='date-from' type='text' class="form-control" value="" required >
											<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label>Project - Client</label>
									<select name='project' id='client' class="form-control" required>
									<option value='' selected>Choose Project - Client</option>
									<?php 
										foreach($contents['projects'] as $key => $value){
											?>
											<option value='<?php echo $value['id'];?>' ><?php echo $value['name'].' - '.$value['client_name'];?></option>
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

					<?php if(!empty($contents['inout_data'])):?>
					 <div class="col-lg-12">
						<div class="panel panel-default">
							<!-- /.panel-heading -->
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
									<thead>
										<tr>
											<th>No</th>
											<th>Name</th>
											<th>Attendance Total</th>									
										</tr>
									</thead>
									<tfoot>
								</tfoot>
									<tbody>
										<?php $no = 0;//foreach($contents['leaves_data'] as $key => $value):?>
											<tr>
												<td colspan='2'><?php echo ++$no ; ?></td>
												<td>in</td>
												<td>out</td>
											</tr>
										<?php //endforeach;?>
										
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
var period = '<?php echo '';?> ';
</script>
