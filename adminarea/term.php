<?php 
include_once('header.php');
?>
<title>Term & Conditions | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>
								Term & Conditions							
							</h1>							
						</div><!-- /.page-header -->
                        
						<div class="row">
							   <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
								<a href="add_term.php" class="pull-left btn btn-sm btn-primary" id="btn_add">															
									<span class="bigger-110">Add New</span>
								</a>																		
							  </div>
						</div>
						
						<div class="row">
							<div class="col-xs-12">																				
								<div class="table-header" id="result">
									
								</div>
                                <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
									<label class="col-sm-4 control-label no-padding-right" for="form-field-username">Search With Title</label>									
									<div class="col-sm-8" id="div_txt_name">
									  <span class="block input-icon input-icon-left">
									  <input class="col-xs-12 col-sm-12" type="text" name="txt_search" id="txt_search" placeholder="Search..." value="" >														 
									</div>
								</div>									
								<div style="background:#EFF3F8;border:1px solid #ccc" >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>SI No.</th>												
												<th>Title</th>												
												<th>Description</th>												
												<th >Action</th>												
											</tr>
										</thead>

										<tbody id="grid">											                                            
                                             										
										</tbody>
									</table>
									
									<div class="row">
										<div class="col-xs-6">
											<div class="dataTables_info" id="info" role="status" aria-live="polite" style="padding-left:20px">
											
											</div>
										</div>
										<div class="col-xs-6">
											<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
												<ul class="pagination" id="div_pagination">
													
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php include_once('footer.php');?>
 <script src="<?php echo $baseurl;?>/adminarea/script/term.js"></script>  			