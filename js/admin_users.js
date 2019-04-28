var myGrid;
//var myLayout, layout1;

function doGridInit(){
    myGrid = new dhtmlXGridObject('gridbox');
    myGrid.setImagePath(baseurl + "js/dhtmlx/skins/skyblue/imgs/");
    myGrid.setHeader("Id, Code, Name, Email, Mobile, Supplier, Customer, Active, Type, Credit, Admin");
    myGrid.attachHeader(",,#text_filter,#text_filter,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter,#select_filter");
    //myGrid.setInitWidths("10,10,40,25,15,5,5,5,10,5,5");
    //myGrid.setInitWidths("10,10,40,25");
    myGrid.setColAlign("left,right, right, right, right, center, center, center, right, center,center");
    myGrid.setColTypes("ro,ro,ro,ro,ro,ch,ch,ch,ro,ch,ch");
    myGrid.setColSorting("int,str,str,str,str,int,int,int,str,int,int");
    myGrid.enableMultiselect(false);
    //myGrid.enableAutoHeight(false);
    myGrid.enableAutoHeight(false);
    myGrid.enableAutoWidth(true);
    //myGrid.setSizes();            
    //myGrid.setSkin("dhx_skyblue");
    myGrid.init();
    //myGrid.load(baseurl + "js/data.json","json");
}

$(document).ready(function()
{
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    //initLayout();
    doGridInit();
    loadUsers(companyid);
});

function loadUsers(companyid) {
    myGrid.load(baseurl + "admin/get_users/"+companyid,"json");
}