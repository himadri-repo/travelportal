<?php 
include_once('header.php');

?>
<title>Add Profession | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header"><!--.page-header -->
							<h1>
								Add Profession 							
							</h1>							
						</div><!-- /.page-header -->
						
                        <div class="row">
							   <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="button" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_save">															
									<span class="bigger-110">Save</span>
								</button>																		
							  </div>
							  
							  <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<a href="profession.php" class="pull-left btn btn-sm btn-default col-md-10" id="btn_cancel">															
									<span class="bigger-110">Cancel</span>
								</a>																		
							  </div>
						</div>
						
						<div class="row">
							<div class="col-xs-12 col-sm-8"><!--Widget col-md-8 start-->
							    <div class="widget-box" style="float:left;width:100%"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Enter  Details</small>
												</h4>
									</div>
									<div class="widget-body"><!--Widget Body start-->
										<form  id="frm_post" enctype="multipart/form-data">							
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Profession (required) </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" id="txt_title" name="txt_title" placeholder="Profession" class="col-xs-12 col-sm-12">
													</div>
											</div>																																
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-8 end-->
							
							
							

                            <!--<div class="col-xs-12 col-sm-4">
							    <div class="widget-box" style="float:left;width:100%">
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Thumbnail</small>
												</h4>
									</div>
									<div class="widget-body">
									    <div id="preview" class="col-xs-12 col-sm-10 col-sm-offset-1">
										</div>
										<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Choose Image</label>
													<div class="col-xs-12 col-sm-12" id="slct_cat">
														<input type="file" id="file_image" name="file_image" />
														<input type="hidden" id="hid_image" name="hid_image" value="" />
													</div>
										</div>	
									</div>
									
									<div class="row">
									   <div class="form-group has-info col-sm-6 col-sm-offset-3" style="float:left;margin-top:15px">									
										<button type="button" class="pull-center btn btn-sm btn-danger col-md-12" id="btn_remove">															
											<span class="bigger-110">Remove</span>
										</button>																		
									  </div>
									</div>  
								</div>-->
							
								

                            </div>	<!--Widget col-md-4 end-->	
                            </form> 							
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php include_once('footer.php');?>
 <script src="<?php echo $baseurl;?>/adminarea/script/add_profession.js"></script>  