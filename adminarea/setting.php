<?php 
include_once('header.php');
if(!in_array(13,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
?>

 <title>Setting | <?php echo $row_top['site_title']; ?></title>
</head>

            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header"><!--.page-header -->
							<h1>
								Setting 							
							</h1>							
						</div><!-- /.page-header -->
                        <div class="row">
							   <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="button" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_save">														
									<span class="bigger-110">Save</span>
								</button>																		

							  </div>

							  

							  <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="button" class="pull-left btn btn-sm btn-default col-md-10" id="btn_cancel">															
									<span class="bigger-110">Cancel</span>

								</button>																		

							  </div>

						</div>

						

						<div class="row">

							<div class="col-xs-12 col-sm-8"><!--Widget col-md-8 start-->

							    <div class="widget-box" style="float:left"><!--Widget Box start-->

								    <div class="widget-header">

												<h4 class="smaller">													

													<small>Enter Following Details</small>

												</h4>

									</div>

									<div class="widget-body"><!--Widget Body start-->

										<form  id="frm_setting" enctype="multipart/form-data">							

											

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Site Title </label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" id="txt_site_title" msg=""  name="txt_site_title" placeholder="Site Title" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>

											

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Phone No </label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" id="txt_phone_no" msg="" name="txt_phone_no" placeholder="Phone No" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>

											

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Help Line No.</label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" id="txt_fax" msg="" name="txt_fax" placeholder="Help Line No." class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>

											

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Email</label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" id="txt_email" msg="" name="txt_email" placeholder="Email" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>

											

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Address</label>

													<div class="col-xs-12 col-sm-12">

														<textarea id="txt_address" msg="" name="txt_address" placeholder="Address" class="col-xs-12 col-sm-12" maxlength="1000"></textarea>

													</div>

											</div>

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Map</label>

													<div class="col-xs-12 col-sm-12">

														<textarea id="map" msg="" name="map" placeholder="Map" class="col-xs-12 col-sm-12" ></textarea>

													</div>

											</div>

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Facebook Link </label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" msg="" id="txt_facebook_link" name="txt_facebook_link" placeholder="Facebook Link" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>

											

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Twitter Link </label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" msg="" id="txt_twitter_link" name="txt_twitter_link" placeholder="Twitter Link" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>

											

											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Google+ Link </label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" msg="" id="txt_google_link" name="txt_google_link" placeholder="Google+ Link" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Pinterest Link </label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" msg="" id="txt_pin_link" name="txt_pin_link" placeholder="Pinterest Link" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Instagram Link </label>

													<div class="col-xs-12 col-sm-12">

														<input type="text" msg="" id="txt_insta_link" name="txt_insta_link" placeholder="Instagram Link" class="col-xs-12 col-sm-12" maxlength="500">

													</div>

											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Linkedin Link </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_linkedin_link" name="txt_linkedin_link" placeholder="Linkedin Link" class="col-xs-12 col-sm-12" maxlength="500">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left">Service Charge ( Flat Rate ) </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text"  id="txt_service_charge" name="txt_service_charge" placeholder="Service Charge" class="col-xs-12 col-sm-12" >
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> CGST </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_cgst" name="txt_cgst" placeholder="CGST" class="col-xs-12 col-sm-12" >
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> SGST </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_sgst" name="txt_sgst" placeholder="SGST" class="col-xs-12 col-sm-12" maxlength="500">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> IGST </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_igst" name="txt_igst" placeholder="IGST" class="col-xs-12 col-sm-12" maxlength="500">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Bank Name </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text"  id="txt_bank_name" name="txt_bank_name" placeholder="Bank Name" class="col-xs-12 col-sm-12" maxlength="50">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Branch </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_branch" name="txt_branch" placeholder="Branch" class="col-xs-12 col-sm-12" maxlength="50">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> A/c Name </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_acc_name" name="txt_acc_name" placeholder="A/c Name " class="col-xs-12 col-sm-12" maxlength="50">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> A/c No. </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_acc_no" name="txt_acc_no" placeholder="A/c No " class="col-xs-12 col-sm-12" maxlength="50">
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-12">
													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> IFSC Code </label>
													<div class="col-xs-12 col-sm-12">
														<input type="text" msg="" id="txt_ifsc" name="txt_ifsc" placeholder="IFSC Code" class="col-xs-12 col-sm-12" maxlength="50">
													</div>
											</div>
                                            
                                            


											

										

                                    </div>	<!--Widget Body end-->								

                                </div><!--Widget Box start-->								

							</div><!--Widget col-md-8 end-->

							

							



                            <div class="col-xs-12 col-sm-4"><!--Widget col-md-4 start-->

							    <div class="widget-box" style="float:left;width:100%"><!--Widget Box start-->

								    <div class="widget-header">

												<h4 class="smaller">													

													<small>Logo</small>

												</h4>

									</div>

									<div class="widget-body">

									    <div id="logo_preview" class="col-xs-12 col-sm-10 col-sm-offset-1">

										</div>

										<div class="form-group has-info col-xs-12 col-sm-12">

													<label class="col-sm-12 control-label no-padding-right" for="form-field-1" style="text-align:left"> Choose Image</label>

													<div class="col-xs-12 col-sm-12" id="slct_cat">

														<input type="file" id="file_logo" name="file_logo" />

														<input type="hidden" id="hid_logo" name="hid_logo" value="" />

													</div>

										</div>	

									</div>

									

									<div class="row">

									   <div class="form-group has-info col-sm-6 col-sm-offset-3" style="float:left;margin-top:15px">									

										<button type="button" class="pull-center btn btn-sm btn-danger col-md-12" id="btn_logo_remove">															

											<span class="bigger-110">Remove</span>

										</button>																		

									  </div>

									</div>  

								</div><!--Widget Box end-->

							

								



                            </div>	<!--Widget col-md-4 end-->	

							

							

						

							

								



                            </div>	<!--Widget col-md-4 end-->	

                            </form> 							

						</div><!-- /.row -->

					</div><!-- /.page-content -->

				</div>

			</div><!-- /.main-content -->



<?php include_once('footer.php');?>
 <script src="<?php echo $baseurl;?>/adminarea/script/setting.js"></script> 