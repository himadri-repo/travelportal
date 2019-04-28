<!-- Page Content Holder -->
<div id="content">

    <nav class="navbar1 navbar-default">
        <div class="container-fluid">

            <div class="navbar-header" style="width:100%">
                <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                    <i class="glyphicon glyphicon-align-left"></i>
                    <span>Toggle Sidebar</span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="float:right">
                    <h1>Manage Wholesalers</h1>
                </div>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">My Wholesalers</a></li>
                <li><a data-toggle="tab" href="#search">Search Wholesalers</a></li>
            </ul>
        </div>
    </div>
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div style="background:#EFF3F8;border:1px solid #ccc; min-height:50vh" >
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>SI No.</th>
                                    <th>Supplier Name</th>
                                    <th>Primary Contact</th>
                                    <th>Services Offered</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="grid">											                                            
                                <?php
                                $ctr=1;
                                if($wholesalers) {
                                    foreach($wholesalers as $row)
                                    {
                                    ?>	
                                        <tr>
                                            <td><?php echo $ctr; ?></td>
                                            <td><?php echo $row["name"]; ?></td>
                                            <td><?php echo $row["primary_user_name"]; ?><br/><?php echo $row["primary_user_mobile"]; ?><br/><?php echo $row["primary_user_email"]; ?></td>
                                            <td><?php echo $row["services"]; ?></td>
                                            <td>
                                                <div class="hidden-sm hidden-xs action-buttons">
                                                    <a class="green" style="cursor:pointer" href="user_form.php?id=<?php echo $row["id"]; ?>">
                                                        <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                    </a>
                                                </div>
                                            </td>												
                                        </tr>
                                    <?php
                                        $ctr++;
                                    }
                                }
                                else {
                                ?>
                                    <tr><td colspan="5"><span style="padding:10px">No wholesalers registered!!</span></td></tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="search" class="tab-pane fade">
            <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div style="background:#EFF3F8;border:1px solid #ccc; min-height:50vh" >
                        <span style="padding:10px">Search wholesalers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Page Content Holder -->

<script language="javascript">
    loadResourceFile('css/admin.css', 'css');
    //loadResourceFile('js/admin_suppliers.js', 'js');
    loadResourceFile('js/admin_users.js', 'js');
    var companyid = <?php echo $this->session->userdata("current_user")["companyid"];?>;
    // $(document).ready(function()
    // {
    //     loadUsers();
    // });
</script>
