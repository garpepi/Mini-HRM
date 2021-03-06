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
						<!--
						<form role="form" method='post' action='<?php echo base_url().'allowance/set'.($this->uri->segment(2) == 'edit' ? 'edit/'.$contents['data']['id'] : 'add');?>'>
						-->
						<form role="form" method='post' action='<?php echo base_url().'allowance/set/'.$contents['data']['project'].'/'.$contents['data']['position'];?>'>
							<div class="col-lg-12">
								<div class="col-lg-6">
									<div class="form-group">
										<label>Client - Project</label>
										<input class="form-control" value = '<?php echo $contents['projects']['client_name'].' - '.$contents['projects']['name'];?>' disabled>
									</div>
									<div class="form-group">
										<label>Positions</label>
										<input class="form-control" value = '<?php echo $contents['positions'][0]['name'];?>' disabled>
									</div>
								</div>
								<div class="col-lg-6">
									<?php if($this->uri->segment(2) != 'edit'):?>
										<div class="form-group">
											<label>Internet & Laptop</label>
											<input type ='number' name='internet_laptop' class="form-control" value ='<?php echo (!empty($contents['data']['internet_laptop']) ? (repopulate_form('internet_laptop') != '' ? repopulate_form('internet_laptop') : $contents['data']['internet_laptop']) : '')?>' required>
										</div>
										<div class="form-group">
											<label>Transport</label>
											<input type ='number' name='transport' class="form-control" value ='<?php echo (!empty($contents['data']['transport']) ? (repopulate_form('transport') != '' ? repopulate_form('transport') : $contents['data']['transport']) : '')?>' required>
										</div>
										<div class="form-group">
											<label>Meal Allowance</label>
											<input type ='number' name='meal_allowance' class="form-control" value ='<?php echo (!empty($contents['data']['meal_allowance']) ? (repopulate_form('meal_allowance') != '' ? repopulate_form('meal_allowance') : $contents['data']['meal_allowance']) : '')?>' required>
										</div>
										<?php endif;?>
										<?php if($this->uri->segment(2) == 'edit'):?>
										<div class="form-group">
											<label>Status</label>
											<select class="form-control" name='status' required>
												<option value=1 <?php echo ($contents['data']['status'] == '1' ? ((repopulate_form('status') == 'active') ? 'selected' : '') : '');?> >active</option>
												<option value=0 <?php echo ($contents['data']['status'] == '0' ? ((repopulate_form('status') == 'inactive') ? 'selected' : '') : '');?>>inactive</option>
											</select>
										</div>
										<?php endif;?>
								</div>
								<hr>
								<?php if($this->uri->segment(2) != 'edit'):?>
								<label>Overtimes</label>
								<div class="col-lg-12">
									<div class="col-lg-6">
										<?php for($i=1;$i<=15;$i++){ $param = "overtime_".$i."h";?>
										<div class="form-group">
											<label>Overtime  <?php echo $i;?> Hour</label>
											<input type ='number' name='overtime[]' class="form-control" value ='<?php echo (!empty($contents['data'][$param]) ? (repopulate_form($param) != '0' ? repopulate_form($param) : $contents['data'][$param]) : '0')?>' required>
										</div>
										<?php }?>
									</div>
									<div class="col-lg-6">
										<?php for($i=1;$i<=15;$i++){ $param = "overtime_we_".$i."h";?>
										<div class="form-group">
											<label>Weekend Overtime  <?php echo $i;?> Hour</label>
											<input type ='number' name='we_overtime_[]' class="form-control" value ='<?php echo (!empty($contents['data'][$param]) ? (repopulate_form($param) != '0' ? repopulate_form($param) : $contents['data'][$param]) : '0')?>' required>
										</div>
										<?php }?>
									</div>
								</div>
								<?php endif;?>
							</div>
							<div class="col-lg-12">
								<button type="submit" class="btn btn-default">Submit</button>
								<button type="reset" class="btn btn-default">Reset </button>
							</div>
						</form>
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