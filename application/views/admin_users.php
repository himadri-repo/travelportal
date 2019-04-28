<!-- Page Content Holder -->
<div id="content">

    <nav class="navbar1 navbar-default">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                    <i class="glyphicon glyphicon-align-left"></i>
                    <span>Toggle Sidebar</span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="float:right">
                    <h1>Manage Users</h1>
                </div>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
            <div id="gridbox" style="width:900px; height:400px; background-color:white;"></div>
        </div>
    </div>
</div>
<!-- End of Page Content Holder -->

<script language="javascript">
    loadResourceFile('css/admin.css', 'css');
    loadResourceFile('js/admin_users.js', 'js');
    var companyid = <?php echo $this->session->userdata("current_user")["companyid"];?>;
    // $(document).ready(function()
    // {
    //     loadUsers();
    // });
</script>
