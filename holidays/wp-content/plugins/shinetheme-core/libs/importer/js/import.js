/**
 * Created by me664 on 2/4/15.
 */
jQuery(document).ready(function($){
    var import_debug=$('#myModal');
    if(import_debug.length <= 0 ) return;
    var modal_data=$('.modal-body');
    var modal_footer = $('.modal-footer');
    var pro = $('.import_progress');
    var pc = $('.progress-wrap span');
    import_debug.modal('hide');
    $('.btn_stp_do_import').click(function(){
        var comf = confirm ('WARNING: Importing data is recommended on fresh installs only once. Importing on sites with content or importing twice will duplicate menus, pages and all posts.');
        if(comf == true){
            pc.html('0%');
            import_debug.find('.modal-content .st-close-button').remove();
            import_debug.modal('show');
            modal_data.html('<img class="loading_import" src="'+stp_importer.loading_src+'">&nbsp;Working ...');
            function start_loop_import(url){
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    success:function(html){
                        if(html){
                            if(html.status == 1){
                                $('.loading_import').remove();
                            }

                            if(html.messenger){

                                modal_data.html('<img class="loading_import" src="'+stp_importer.loading_src+'">&nbsp;'+html.messenger);

                            }
                            if(html.step){
                                var tt = html.step/parseInt(import_debug.data('step'));
                                pro.css({'width':(tt*100)+'%'});
                                pc.html(parseInt(tt*100)+'%');
                            }

                            if(html.next_url != ""){
                                start_loop_import(html.next_url) ;
                                window.onbeforeunload = leave_page;
                            }else{
                                import_debug.find('.modal-content').append('<button type="button" class="st-close-button" data-dismiss="modal"><i class="dashicons-before dashicons-no-alt"></i></button>');
                                $('.loading_import').remove();
                                window.onbeforeunload = stop_leave;
                            }

                            import_debug.scrollTop(import_debug[0].scrollHeight - import_debug.height());
                        }
                    },
                    error:function(html){
                        modal_data.html(html.responseText);
                        modal_data.append('<br><span class="red">Stop Working</span>');
                        modal_footer.append('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                        import_debug.scrollTop(import_debug[0].scrollHeight - import_debug.height());
                    }
                });
            }
            // start fist
            start_loop_import( $(this).data('url') );
        }
    });

    function leave_page(e, d) {
        if(!e) e = window.event;
        //e.cancelBubble is supported by IE - this will kill the bubbling process.
        e.cancelBubble = true;
        e.returnValue = 'You sure you want to leave?'; //This is displayed on the dialog

        //e.stopPropagation works in Firefox.
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
    }
    function stop_leave(e){
        if(!e) e = window.event;
        //e.cancelBubble is supported by IE - this will kill the bubbling process.
        e.cancelBubble = false;
    }

});
