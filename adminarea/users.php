<?php 
include_once('header.php');
if(!in_array(12,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
?>
<title> Users | <?php echo $row_top['site_title']; ?></title>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header" id="header">
							<h1>
								
								Users Table								
							</h1>							
						</div><!-- /.page-header -->
                        
						<div class="row">
							   <div class="form-group has-info col-sm-6" style="float:left;margin-top:15px">									
								<a href="user_form.php" class="pull-left btn btn-sm btn-primary" id="btn_add">															
									<span class="bigger-110">Add New</span>
								</a>																		
							  </div>
						</div>
						
						<div class="row">
							<div class="col-xs-12">																				
								<div class="table-header" id="result">
									
								</div>
                               								
								<div style="background:#EFF3F8;border:1px solid #ccc" >
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>SI No.</th>		                                                												
												<th>Name</th>																																				
												<th>Username</th>																																	
												<th>Pasword</th>
												<th >Action</th>												
											</tr>
										</thead>

										<tbody id="grid">											                                            
                                             <?php
											  $ctr=1;
                                              $sql="SELECT * FROM admin_tbl WHERE admin_id>1";
											  $result=mysql_query($sql);
											  while($row=mysql_fetch_array($result))
											  {
                                             ?>	
                                               <tr>
												<td><?php echo $ctr; ?></td>		                                                												
												<td><?php echo $row["name"]; ?></td>																																				
												<td><?php echo $row["user_name"]; ?></td>																																	
												<td><?php echo $row["password"]; ?></td>
												<td>
													<div class="hidden-sm hidden-xs action-buttons">
														<a class="green" style="cursor:pointer" href="user_form.php?id=<?php echo $row["admin_id"]; ?>">
														<i class="ace-icon fa fa-pencil bigger-130"></i>
														</a>
													</div>
												</td>												
											</tr>
                                            <?php
											$ctr++;
											  }
                                            ?>											
										</tbody>
									</table>
									
									
								</div>
							</div>
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<?php include_once('footer.php');?>
<script>
$(document).ready(function()
{
	$("#menu_users").addClass("active");
});
</script>  			