<?php 
include_once('header.php');

?>
<title>Edit Content | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header"><!--.page-header -->
							<h1>
								Edit Content						
							</h1>							
						</div><!-- /.page-header -->
						
                        <div class="row">
							   <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="button" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_update">															
									<span class="bigger-110">Update</span>
								</button>																		
							  </div>
							  
							  <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<a href="post.php" class="pull-left btn btn-sm btn-default col-md-10" id="btn_cancel">															
									<span class="bigger-110">Cancel</span>
								</a>																		
							  </div>
						</div>
						
						<div class="row">
							<div class="col-xs-12 col-sm-8"><!--Widget col-md-8 start-->
							    <div class="widget-box" style="float:left"><!--Widget Box start-->
								    <div class="widget-header">
												<h4 class="smaller">													
													<small>Enter Details</small>
												</h4>
									</div>
									<div class="widget-body"><!--Widget Body start-->
										<form  id="frm_slider" enctype="multipart/form-data">							
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Title (required) </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" id="txt_title" name="txt_title" placeholder="Title" class="col-xs-12 col-sm-12">
													</div>
											</div>
										
											
											<div class="form-group has-info col-xs-12 col-sm-12 ">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Description </label>
													<div class="col-xs-12 col-sm-12">
														<div id="editor" contenteditable="true">
															
														</div>
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
 <script src="<?php echo $baseurl;?>/adminarea/script/edit_post.js"></script> 
 <script src="<?php echo $baseurl;?>/adminassets/ckeditor/ckeditor.js"></script>
 <script src="<?php echo $baseurl;?>/adminassets/ckeditor/samples/js/sample.js"></script>
 <script>
	initSample();	 
</script>
 