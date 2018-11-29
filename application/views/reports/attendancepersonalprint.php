<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<style>
		.center-div
			{
				 margin: 0 auto;
				 text-align: center;
			}
		.smallfont td{
			font-size: 9px;
			height: 10%;
			padding:5px !important;
		}
		.table {
			text-align: center;
			max-height: 500px; 
		}
		th{
			text-align: center;
		}
		@media print {
		  .custom td { 
				background-color: #ed6363 !important; 
			} 
		  .rows-print-as-pages {
			page-break-before: always;
		  }
		}
		</style>
	</head>
	
	<body>
	<?php
		foreach($data as $period => $values){
			foreach ($values as $value){
				?>
					<div class="container rows-print-as-pages">
						<div class="header center-div">
							<h1>DAFTAR KEHADIRAN</h1>
						</div>
						<div class="row">
							<table>
								<tr>
									<td>
										<label>Nama </label>
									</td>
									<td>
										<label>:</label>
									</td>
									<td>
										<?php echo $value['name'];?>
									</td>
								</tr>
								<tr>
									<td>
										<label>Period </label>
									</td>
									<td>
										<label>:   </label>
									</td>
									<td>
										<?php echo $value['period'];?>
									</td>
								</tr>
								<tr>
									<td>
										<label>Generate time </label>
									</td>
									<td>
										<label>:   </label>
									</td>
									<td>
										<?php echo $value['generate'];?>
									</td>
								</tr>
								
							</table>
							<br>
						</div>
						<div class="row center-div table-responsive">
							<table class="table table-bordered smallfont "  >
								<tr>
									<th>
										Tanggal 
									</th>
									<th>
										Jam Masuk
									</th>
									<th>
										Jam Keluar
									</th>
									<th>
										Durasi
									</th>
									<th>
										Keterangan
									</th>
								</tr>
								<?php foreach($value['detail'] as $perday){
									?>
										<tr <?php echo ($perday['holiday']==1 ? ' class=custom style="background-color:#ed6363;"' : '');?>>
											<td>
												<?php echo $perday['date'];?>
											</td>
											<td>
												<?php echo $perday['arrived'];?>
											</td>
											<td>
												<?php echo $perday['returns'];?>
											</td>
											<td>
												<?php echo $perday['duration'];?>
											</td>
											<td>
												<?php echo $perday['desc'];?>
											</td>
										</tr>
									<?php
								}?>
							</table>
						</div>
					</div>
				<?php
			}
		}
	?>
	</body>
</html>