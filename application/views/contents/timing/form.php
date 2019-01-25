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
								<form role="form" method='post' action='<?php echo base_url().'attendancetiming/'.($this->uri->segment(2) == 'edit' ? 'edit/'.$contents['data'][0]['client_id'].'/'.$contents['data'][0]['project_id'] : 'add');?>'>

									<div class="form-group">
										<label>Client - Project</label>
										<select name='project_id' id='project_id' class="form-control" required <?php echo ($this->uri->segment(2) != 'add' && $this->uri->segment(2) == 'view') ? 'disabled' : '' ;?>>
											<?php foreach($contents['projects'] as $key=>$value ):?>
												<?php if(repopulate_form('project_id') != '' && repopulate_form('project_id') == $value['id']) :?>
															<option value='<?php echo $value['id'];?>' selected><?php echo $value['client_name'].' - '.$value['name'];?></option>
														<?php else :?>
															<option value='<?php echo $value['id'];?>'><?php echo $value['client_name'].' - '.$value['name'];?></option>
												<?php endif ;?>
											<?php endforeach;?>
										</select>
									</div>
									<div class="form-group date">
										<label>Come In</label>
										<input name='time_in' class="form-control timeep" value ='<?php echo (repopulate_form('comes') != '' ? repopulate_form('comes'): '') ;?>' timeep>
									</div>
									<div class="form-group date">
										<label>Go Home</label>
										<input name='time_out' class="form-control timeep" value ='<?php echo (repopulate_form('go_home') != '' ? repopulate_form('go_home'): '') ;?>' timeep>
									</div>
									<div class="form-group date">
										<label>Start Overtime</label>
										<input name='time_overtime' class="form-control timeep" value ='<?php echo (repopulate_form('start_overtime') != '' ? repopulate_form('start_overtime'): '') ;?>' timeep>
									</div>
									<button type="submit" class="btn btn-default">Submit</button>
									<button type="reset" class="btn btn-default">Reset </button>
								</form>
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