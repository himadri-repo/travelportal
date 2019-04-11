<?php 
//This is added just to keep mysql function calls as it is but still using mysqki library
include_once('../config.php');
if(isset($_SESSION['borntoday_admin_id']))
{
header('location:'.$baseurl.'/adminarea/dashboard.php');
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />		
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />		
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/font-awesome/4.2.0/css/font-awesome.min.css" />	
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/fonts/fonts.googleapis.com.css" />		
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/style.min.css" />
		<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/custom.css" />
        		
		<script type="text/javascript">
		 var baseurl=<?php echo "'".$baseurl."'"; ?>
		</script>
    <title>Admin Login | <?php echo $row_top['site_title'];?></title>
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="col-sm-6 col-sm-offset-3">
							<div class="center">
								<h1>									
									<span class="red"><?php echo $row_top['site_title'];?> </span>
									
								</h1>
								
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger" style="padding:left-30px">												
												ADMIN LOGIN
											</h4>
											<div class="space-6"></div>
											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="txt_login_id" id="txt_login_id" class="form-control" placeholder="User Name" value=""  maxlength="50"/>
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="txt_password" id="txt_password" class="form-control" placeholder="Password" value=""  maxlength="30"/>
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													
													
                                                    <div class="form-group has-info ">
														<div class="col-xs-12 col-sm-12" >
															<button type="button" class="width-35 pull-right btn btn-sm btn-primary" id="btn_login">
																<i class="ace-icon fa fa-key"></i>
																<span class="bigger-110">Login</span>
															</button>
														</div>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											
										</div><!-- /.widget-main -->

										
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

															
							</div><!-- /.position-relative -->							
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		
		<script src="<?php echo $baseurl;?>/adminassets/js/jquery.2.1.1.min.js"></script>
		<script src="<?php echo $baseurl;?>/adminassets/js/bootstrap.min.js"></script>
		<script src="<?php echo $baseurl;?>/adminassets/js/bootbox.min.js" ></script>
		<script src="<?php echo $baseurl;?>/adminarea/script/login.js"></script>			
	    <script>		
		$('body').attr('class', 'login-layout light-login');
		</script>
		<script type="text/javascript">
			jQuery(function($) 
			{			    			
				 $(document).on('click', '.toolbar a[data-target]', function(e) 
				 {
					e.preventDefault();
					var target = $(this).data('target');
					$('.widget-box.visible').removeClass('visible');
					$(target).addClass('visible');
				 });
			});												
		</script>
	</body>
</html>
