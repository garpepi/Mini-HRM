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
								<form role="form" method='post' action='<?php echo base_url().'autoreportemail/'.($this->uri->segment(2) == 'edit' ? 'edit/'.$contents['data']['id'] : 'add');?>'>
									<div class="form-group">
										<label>Email</label>
										<input type='email' name='email' class="form-control" value ='<?php echo ($this->uri->segment(2) == 'edit' ? (repopulate_form('email') != '' ? repopulate_form('email') : $contents['data']['email'] ): '') ;?>' >
									</div>
									<div class="form-group">
										<label>Autoreport Email Client</label>
										<select name='client' id='client' class="form-control" required >
											<option value=''>CHOOSE CLIENT</option>
											<option value='mandiri' <?php echo (repopulate_form('client') =='mandiri' ? 'selected' : '') ;?>>BANK MANDIRI</option>
											<option value='bi' <?php echo (repopulate_form('client') =='bi' ? 'selected' : '') ;?>>BANK INDONESIA</option>
											<option value='adidata' <?php echo (repopulate_form('client') =='adidata' ? 'selected' : '') ;?>>ADIDATA</option>
										</select>
									</div>
									<!--
									<div class="form-group">
										<label>Autoreport Email Client</label>
										<input name='client' class="form-control" value ='<?php echo ($this->uri->segment(2) == 'edit' ? (repopulate_form('client') != '' ? repopulate_form('client') : $contents['data']['client'] ): '') ;?>' >
									</div>
									-->
									<button type="submit" class="btn btn-default">Submit</button>
									<button type="reset" class="btn btn-default">Reset </button><br><br>
								</form>
							</div>
							
							<?php if($this->uri->segment(2)!= 'edit'):?>
							<div class="col-lg-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										Autoreport Email List Active
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-active">
											<thead>
												<tr>
													<th>Email</th>
													<th>Client</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($contents['table_active'] as $key => $value) :?>
												<tr>
													<td><?php echo $value['email']; ?></td>
													<td>
														<?php
														switch ($value['client']) {
															case "mandiri":
																echo "BANK MANDIRI";
																break;
															case "adidata":
																echo "ADIDATA";
																break;
															case "bi":
																echo "BANK INDONESIA";
																break;
															default:
																echo $value['client'];
														}
														?>
													</td>
													<td>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'autoreportemail/edit/'.$value['id'];?>'><i class="fa fa-edit"></i> Edit</a>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'autoreportemail/revoke/'.$value['id'];?>'><i class="fa fa-edit"></i> Deactivate</a>
													</td>
												</tr>
												<?php endforeach;?>
											</tbody>
										</table>
										<!-- /.table-responsive -->
									</div>
									<!-- /.panel-body -->
								</div>
							</div>
							<div class="col-lg-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										Autoreport Email List Inctive
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-inactive">
											<thead>
												<tr>
													<th>Email</th>
													<th>Client</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($contents['table_inactive'] as $key => $value) :?>
												<tr>
													<td><?php echo $value['email']; ?></td>
													<td>
														<?php
														switch ($value['client']) {
															case "mandiri":
																echo "BANK MANDIRI";
																break;
															case "adidata":
																echo "ADIDATA";
																break;
															case "bi":
																echo "BANK INDONESIA";
																break;
															default:
																echo $value['client'];
														}
														?>
													</td>
													<td>
														<a class="btn btn-secondary btn-xs" href='<?php echo base_url().'autoreportemail/reactivate/'.$value['id'];?>'><i class="fa fa-edit"></i> Activate</a>
													</td>
												</tr>
												<?php endforeach;?>
											</tbody>
										</table>
										<!-- /.table-responsive -->
									</div>
									<!-- /.panel-body -->
								</div>
							</div>
						</div>
						<?php endif;?>
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