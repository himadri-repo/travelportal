<?php 
include_once('header.php');
if(!in_array(3,$auth))
{
?>
<script>window.location.href="dashboard.php"</script>
<?php
 
}
$is_supplier=isset($_POST['is_supplier'])? $_POST['is_supplier']:"";
$is_customer=isset($_POST['is_customer'])? $_POST['is_customer']:"";
$active=isset($_POST['active'])? $_POST['active']:"";
$type=isset($_POST['type'])? $_POST['type']:"";
$credit_ac=isset($_POST['credit_ac'])? $_POST['credit_ac']:"";

if(isset($_POST["btn_update"]))
{	
	$sql="UPDATE user_tbl SET
	is_supplier='".$is_supplier."',
	is_customer='".$is_customer."',
	active='".$active."',
	type='".$type."',
	credit_ac='".$credit_ac."'
	WHERE id='".$_GET['id']."'
	";
	$result=mysql_query($sql);   
	if($result)
	{
		$_SESSION["cel_msg"]="User Updated Successfully";
		?>
		<script>window.location.href="celebrity.php"</script>
		<?php
	}
	
}
if(isset($_GET['id']) && is_numeric($_GET['id'])) 
{
  $sql="SELECT * FROM user_tbl WHERE id='".$_GET['id']."'";
  $result=mysql_query($sql);
  if(mysql_num_rows($result)>0)
  {
    $row=mysql_fetch_array($result);	  
	$is_supplier=$row["is_supplier"];
	$is_customer=$row["is_customer"];
	$active=$row["active"];
	$type=$row["type"];
	$credit_ac=$row["credit_ac"];
  }
  else
  {
	  ?>
		<script>window.location.href="celebrity.php";</script>
		<?php
  }
}



?>
<link rel="stylesheet" href="<?php echo $baseurl;?>/adminassets/css/select2.min.css" />	
<link href="<?php echo $baseurl;?>/adminassets/css/datepicker/datepicker.css" rel="stylesheet" type="text/css" media="all"/>
<title>Celebrity | <?php echo $row_top['site_title']; ?></title>
<style>
.pink
{
  min-height:78px;
}
#img img
{
	width:200px;
	height:auto;
}
</style>
</head>
            <?php 
			include_once('leftbar.php');
			?>
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<form id="frm_product" action="" enctype="multipart/form-data" method="post" >
						<div class="page-header">
							<h1>
							  <?php 
							   if(!isset($_GET['id'])) 
							   {
							     echo "Add ";
							   }
							   else
							   {
							      echo "Update ";
							   }
							   ?>
								
							</h1>
						</div><!-- /.page-header -->
                        <div class="row">
							   <?php 
							   if(!isset($_GET['id'])) 
							   {?>
							    <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_create" name="btn_create">															
									<span class="bigger-110">Add</span>
								</button>																		
							    </div>
								
							  <?php
							  }
							  else
							  {
							  ?>
							    <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<button type="submit" class="pull-left btn btn-sm btn-primary col-md-10" id="btn_update" name="btn_update">															
									<span class="bigger-110">Update</span>
								</button>																		
							    </div>
								
							  <?php
							  }
							  ?>
							  
							  <div class="form-group has-info col-sm-2" style="float:left;margin-top:15px">									
								<a href="celebrity.php" class="pull-left btn btn-sm btn-default col-md-10" id="btn_cancel">															
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
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Supplier  </label>
													<div class="col-xs-12 col-sm-12">
														<select name="is_supplier" class="col-xs-12 col-sm-12" required>	
														  <option value="1" <?php if($is_supplier==1) echo "selected";?>>Yes</option>
														  <option value="0" <?php if($is_supplier==0) echo "selected";?>>No</option>
														</select>
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Customer  </label>
													<div class="col-xs-12 col-sm-12">
														<select name="is_customer" class="col-xs-12 col-sm-12" required>	
														  <option value="1" <?php if($is_customer==1) echo "selected";?>>Yes</option>
														  <option value="0" <?php if($is_customer==0) echo "selected";?>>No</option>
														</select>											
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Approve</label>
													<div class="col-xs-12 col-sm-12">
														<select name="active" class="col-xs-12 col-sm-12" required>	
														  <option value="1" <?php if($active==1) echo "selected";?>>Yes</option>
														  <option value="0" <?php if($active==0) echo "selected";?>>No</option>
														</select>													
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Type (required) </label>
													<div class="col-xs-12 col-sm-12">
														<select name="type" class="col-xs-12 col-sm-12" required>	
														  <option value="">--Select Type</option>
														  <option value="B2C" <?php if($type=="B2C") echo "selected";?>>B2C</option>
														  <option value="B2B" <?php if($type=="B2B") echo "selected";?>>B2B</option>
														</select>													
													</div>
											</div>
											
											<div class="form-group has-info col-xs-12 col-sm-6 pink">
													<label class="col-sm-12 control-label" for="form-field-1" style="text-align:left"> Credit A/c </label>
													<div class="col-xs-12 col-sm-12">
														<select name="credit_ac" class="col-xs-12 col-sm-12" required>	
														  <option value="">--Select</option>
														  <option value="0" <?php if($credit_ac=="0") echo "selected";?>>No</option>
														  <option value="1" <?php if($credit_ac=="1") echo "selected";?>>Yes</option>
														</select>													
													</div>
											</div>
											
											
										
                                    </div>	<!--Widget Body end-->								
                                </div><!--Widget Box start-->								
							</div><!--Widget col-md-8 end-->
							
							
							

                          
					    </div><!-- row -->
						</form>
				    </div><!-- /page-content -->
				</div><!-- /.page-content -->
			</div>
			
<?php include_once('footer.php');?>
<script type="text/javascript" src="<?php echo $baseurl;?>/adminassets/js/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $baseurl;?>/adminassets/js/select2.min.js"></script>	
<script src="<?php echo $baseurl;?>/adminassets/ckeditor/ckeditor.js"></script>
<script src="<?php echo $baseurl;?>/adminassets/ckeditor/samples/js/sample.js"></script>
<script>
initSample();

</script>				

<script type="text/javascript">
jQuery(function($) 
{										
 $(".select2").css('width','300px').select2({allowClear:true})
.on('change', function()
{
	
	
}); 
$('#select2-multiple-style .btn').on('click', function(e)
				{
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('.select2').addClass('tag-input-style');
					 else $('.select2').removeClass('tag-input-style');
				});
$("#menu_celebrity").addClass("active");													
});
</script>
<script>
    $(document).ready(function()
	{	
        						
		$('a[data-action=delete]').
		on('click', function(e)
		{
							
							$(this).closest('.col-md-12').hide(300, function(){ $(this).remove() });
		});
		
        $('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
		    autoclose: true,
		});	
		$('#id-add-attachment').click( function()
		{
		   var file =$("#lbl-file").clone().appendTo("#div-img");
		   file.closest('.ace-file-input')
						.addClass('width-90 inline')
						.wrap('<div class="form-group file-input-container"><div class="col-sm-12"></div></div>')
						.parent().append('<div class="action-buttons pull-right col-xs-1">'+
							'<a href="#" data-action="delete" class="middle">'+
								'<i class="ace-icon fa fa-trash-o red bigger-130 middle"></i>'+
							'</a>'+
						'</div>')
						.find('a[data-action=delete]').on('click', function(e){
							
							e.preventDefault();
							$(this).closest('.file-input-container').hide(300, function(){ $(this).remove() });
						});
		});
		$("#birth_region").change(function()
		{
				  $.ajax
				   ({
						url: ''+baseurl+'/adminarea/ajax/fetch_sql.php',   	
						type: "POST",      	
						data: "tag=get_city&id="+$("#birth_region").val(),	
						cache: false,							
						success: function(data)  		
						{
							$("#birth_place").html('');	
							var data	=	$.parseJSON(data);									
							$.each(data, function(k,v) 
							{										 
								 if(k=='no_records')
								   {											   
									  $("#birth_place").append('<option value="">No Birth Place found</option>');																								 
								   }
								   else
								   {	                                              								  
									  $("#birth_place").append('<option value="'+v['id']+'">'+v['city']+'</option>');
								   }
							});
						}
				   });
		});
		
		 
		  $('#name').bind('keyup change', function(e) 
		  {
				  if($(this).val().length<1)
				  {
					$("#name").parent().find("#div_err").remove();   
					$("#name").parent().append("<span id='div_err' style='color:red'>Please Enter Name</div>");
					 
				  }
				  else
				  {
					 $("#name").parent().find("#div_err").remove();					
				  }		   
		  });
		   $('#profession').change(function(e) 
		  {
				  if($(this).val()=="")
				  {
					$("#profession").parent().find("#div_err").remove();   
					$("#profession").parent().append("<span id='div_err' style='color:red'>Please Select Profession</div>");
					 
				  }
				  else
				  {
					 $("#profession").parent().find("#div_err").remove();					
				  }		   
		  });
		  $('#zodiac_sign').change(function(e) 
		  {
				  if($(this).val()=="")
				  {
					$("#zodiac_sign").parent().find("#div_err").remove();   
					$("#zodiac_sign").parent().append("<span id='div_err' style='color:red'>Please Select Zodiac Sign</div>");
					 
				  }
				  else
				  {
					 $("#zodiac_sign").parent().find("#div_err").remove();					
				  }		   
		  });
		  $('#birth_region').bind('change', function(e) 
		  {
				  if($(this).val()=="")
				  {
					$("#birth_region").parent().find("#div_err").remove();   
					$("#birth_region").parent().append("<span id='div_err' style='color:red'>Please Select Birth Region </div>");
					 
				  }
				  else
				  {
					 $("#birth_region").parent().find("#div_err").remove();					
				  }		   
		  });
		  $('#nationality').bind('change', function(e) 
		  {
				  if($(this).val()=="")
				  {
					$("#nationality").parent().find("#div_err").remove();   
					$("#nationality").parent().append("<span id='div_err' style='color:red'>Please Select Nationality </div>");
					 
				  }
				  else
				  {
					 $("#nationality").parent().find("#div_err").remove();					
				  }		   
		  });
		  $('#birth_place').bind('change', function(e) 
		  {
				  if($(this).val()=="")
				  {
					$("#birth_place").parent().find("#div_err").remove();   
					$("#birth_place").parent().append("<span id='div_err' style='color:red'>Please Select Sub Category </div>");
					 
				  }
				  else
				  {
					 $("#birth_place").parent().find("#div_err").remove();					
				  }		   
		  });
		  $('#dob').bind('keyup change', function(e) 
		  {
				  if($(this).val().length<1)
				  {
					$("#dob").parent().find("#div_err").remove();   
					$("#dob").parent().append("<span id='div_err' style='color:red'>Please Select Date Of Birth</div>");
					 
				  }
				  else
				  {
					 $("#dob").parent().find("#div_err").remove();					
				  }		   
		  });
		  /*$('#dod').bind('keyup change', function(e) 
		  {
				  if($(this).val().length<1)
				  {
					$("#dod").parent().find("#div_err").remove();   
					$("#dod").parent().append("<span id='div_err' style='color:red'>Please Select Date Of Death</div>");
					 
				  }
				  else
				  {
					 $("#dod").parent().find("#div_err").remove();					
				  }		   
		  });*/
		  
		   
			
			
	});
	
    function validation()
	{
		$("#hid_profession").val($("#profession").val());
		$("#description").val(CKEDITOR.instances.editor.getData());
		if($("#name").val()=="")
		{			
			$("#name").parent().find("#div_err").remove();
			$("#name").parent().append("<span id='div_err' style='color:red'>Please Enter Name</div>");
			return false;
			
		}
		else if($("#profession").val()=="")
		{			
			$("#profession").parent().find("#div_err").remove();
			$("#profession").parent().append("<span id='div_err' style='color:red'>Please Select Profession</div>");
			return false;
			
		}
		else if($("#birth_region").val()=="")
		{			
			$("#birth_region").parent().find("#div_err").remove();
			$("#birth_region").parent().append("<span id='div_err' style='color:red'>Please Select Birth Region </div>");
			return false;
			
		}
		else if($("#birth_place").val()=="")
		{		
           
			$("#birth_place").parent().find("#div_err").remove();
			$("#birth_place").parent().append("<span id='div_err' style='color:red'>Please Select Birth Place </div>");
			return false;
			
		}
		else if($("#dob").val()=="")
		{			
			$("#dob").parent().find("#div_err").remove();
			$("#dob").parent().append("<span id='div_err' style='color:red'>Please Select Date of Birth</div>");
			return false;
			
		}
		/*else if($("#dod").val()=="")
		{			
			$("#dod").parent().find("#div_err").remove();
			$("#dod").parent().append("<span id='div_err' style='color:red'>Please Select Date of Death</div>");
			return false;
			
		}*/
		else if($("#nationality").val()=="")
		{			
			$("#nationality").parent().find("#div_err").remove();
			$("#nationality").parent().append("<span id='div_err' style='color:red'>Please Select Nationality</div>");
			return false;
			
		}
		else if($("#zodiac_sign").val()=="")
		{			
			$("#zodiac_sign").parent().find("#div_err").remove();
			$("#zodiac_sign").parent().append("<span id='div_err' style='color:red'>Please Select Zodiac Sign</div>");
			return false;
			
		}
		else
		{
			return true;
		}
	}
	function isNumber(evt) 
	{
			var iKeyCode = (evt.which) ? evt.which : evt.keyCode
			if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
				return false;

			return true;
	}
	function upload(x)
	{
		 var ext = $(x).val().split('.').pop();
		if(ext=='png' || ext=='jpg' || ext=='jpeg' || ext=='bmp' || ext=='gif')
		{
		   var file_size=($(x)[0].files[0].size/ 1048576);
		   
		   if(file_size<2)
		   {
			   
			  $(x).parent().parent().find("#img").remove();
                
              if (x.files[0]) 
			   {
					var reader = new FileReader();		
					reader.onload = function (e) 
					{
						$(x).parent().parent().prepend('<center id="img"><img src="'+e.target.result+'" alt="Uploading...." ></center>');
						
					}

					reader.readAsDataURL(x.files[0]);
				} 			  
			  /*$(x).parent().parent().find("#loading").remove(); 
			  $(x).parent().parent().prepend('<img src="'+baseurl+'/adminassets/images/loading.gif" alt="Uploading...." id="loading">'); */
		      
		      /*$.ajax
				({
					url: ''+baseurl+'/adminarea/ajax/upload_celebrity_image.php',    	
					type: "POST",      	
					data: new FormData(),	
					data:  new FormData(document.getElementById("frm_product")),
					contentType: false,       		
					cache: false,					
					processData:false,  			
					success: function(data)  		
					{
						var data	=	$.parseJSON(data);	
							$.each(data, function(k,v) 
							{
								if(k=='success')
								{
								  alert(v)	
								  var image=$("#hid_image").val();
								  if(image=="")
								  {
									   $("#hid_image").val(v);
								  }
								  else
								  {
									   $("#hid_image").val(image+","+v);
								  }
								 
								  $(x).parent().parent().find("#loading").remove(); 
								
								  $(x).parent().parent().prepend('<center id="img"><img src="'+baseurl+'/upload/thumb/'+v+'" alt="Uploading...." ></center>'); 
								}
							});
                    }
                });*/					
		    }           
		   else		   
		    alert("Please Upload up to 2MB Image")
		}
		else		   
		    alert("Please Upload Image File")
	}
</script> 