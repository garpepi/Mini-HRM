<!DOCTYPE html>
<html lang="en">

<head>
   <?php 
		if($header) echo $header ;
	?>
	
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Adidata HRMS</a>
            </div>
            <!-- /.navbar-header -->
			<?php 
				if($top_nav) echo $top_nav ;
				if($sidebar) echo $sidebar ;
			?>
        </nav>
		
		<?php 
			if($contents) echo $contents ;
		?>

    </div>
	<div class="se-pre-con"></div>
    <!-- /#wrapper -->
	<footer>
	  <div class="pull-right">
		SB-Admin 2 Themes - HRMAPP by <a href="https://garpepi.com">Garpepi Hanief Aotearoa</a>
	  </div>
	  <div class="clearfix"></div>
	</footer>
	 <!-- jQuery -->
    <script src="<?php echo base_url();?>assets/vendor/jquery/jquery.min.js"></script>
	
	<!-- modernizr -->
    <script src="<?php echo base_url();?>assets/vendor/modernizr/modernizr.js"></script>
	
	<!-- Loading -->
	<script src="<?php echo base_url();?>assets/js/loading.js"></script>
	
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url();?>assets/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url();?>assets/dist/js/sb-admin-2.js"></script>
	
	<!-- Adding Page Script -->
		<?php
			foreach($page_js as $js):
				echo '<script src="'.base_url().'assets/'.$js.'"></script>'."\n		";
			endforeach;
		?>
<!-- Custom Theme Scripts -->

</body>

</html>
