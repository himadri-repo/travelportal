<?php 
include_once('header.php');

?>
 <title> Change Password | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
				include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header"><!--.page-header -->
							<h1>
								Change Password 							
							</h1>							
						</div><!-- /.page-header -->
						
                        <div class="row">
							   <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="button" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_change">															
									<span class="bigger-110">Change</span>
								</button>																		
							  </div>
							  
							  <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="button" class="pull-left btn btn-sm btn-default col-md-10" id="btn_cancel">															
									<span class="bigger-110">Cancel</span>
								</button>																		
							  </div>
						</div>
						
						<div class="row">
							<div class="col-xs-12 col-sm-6"><!--Widget col-md-8 start-->
							    <div class="widget-box" style="float:left"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Change your password here</small>
												</h4>
									</div>
									<div class="widget-body"><!--Widget Body start-->
										<form  id="frm_change_password" enctype="multipart/form-data">							
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Enter Old Password </label>
													<div class="col-xs-12 col-sm-12">
														<input  type="password" id="txt_old_password" name="txt_old_password" placeholder="Old Password" class="col-xs-12 col-sm-12" maxlength="30">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Enter New Password</label>
													<div class="col-xs-12 col-sm-12">
														<input type="password" id="txt_password" name="txt_password" placeholder="Enter New Password" class="col-xs-12 col-sm-12" maxlength="30">
													</div>
														
											</div>
										    <div class="col-xs-10 col-sm-10 col-sm-offset-1" id="complexity" >
															<div class="progress-bar" ></div>
										    </div>
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Confirm Password</label>
													<div class="col-xs-12 col-sm-12">
														<input type="password" id="txt_confirm_password" name="txt_confirm_password" placeholder="Confirm Password" class="col-xs-12 col-sm-12" maxlength="30">
													</div>
											</div>																						
																					
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-8 end-->														                            																					
                            </form> 							
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php include_once('footer.php');?>
 <script src="<?php echo $baseurl;?>/adminarea/script/change_password.js"></script> 
 <script>
$("#change_password").addClass('active');
</script>
 
 