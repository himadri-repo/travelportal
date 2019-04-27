var myGrid;
//var myLayout, layout1;
$(document).ready(function()
{
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    function doOnLoad(){
        myGrid = new dhtmlXGridObject('gridbox');
        myGrid.setImagePath(baseurl + "js/dhtmlx/skins/skyblue/imgs/");
        myGrid.setHeader("Column A, Column B");
        myGrid.attachHeader("#select_filter,#text_filter");
        myGrid.setInitWidths("80,800");
        myGrid.setColAlign("right,left");
        myGrid.setColTypes("ro,ed");
        myGrid.setColSorting("int,str");
        myGrid.enableMultiselect(false);
        //myGrid.enableAutoHeight(false);
        myGrid.enableAutoHeight(false);
        myGrid.enableAutoWidth(true);
        //myGrid.setSizes();            
        //myGrid.setSkin("dhx_skyblue");
        myGrid.init();
        myGrid.load(baseurl + "js/data.json","json");
    }

    //initLayout();
    doOnLoad();
});
