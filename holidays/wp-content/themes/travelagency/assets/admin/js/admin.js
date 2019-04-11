(function($){

    'use strict';

    $(document).ready(function() {
        var media_uploader;
        $('body').on('click', '.upload-button', function (event) {
            event.preventDefault();
            var parent = $(this).closest('.upload-wrapper');
            media_uploader = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {
                    text: $(this).data('uploader_button_text')
                },
                multiple: false
            });
            media_uploader.open();
            select_media(parent);
        });

        $('body').on('click', '.delete-button', function (event) {
            event.preventDefault();
            var title = $(this).data('delete-title');
            if (confirm(title)) {
                var parent = $(this).closest('.upload-wrapper');
                parent.find('.save-image-id').val('');
                parent.find('.upload-items').empty();
                $(this).addClass('none');
            }
        });

        function select_media(parent) {
            media_uploader.on("select", function (event) {

                var json = media_uploader.state().get("selection").first().toJSON();
                var image_url = json.url;
                if (typeof image_url == 'string' && image_url != '') {
                    console.log(image_url);
                    var html = '<div class="upload-item"> ' +
                        '<img src="' + image_url + '" alt="" class="frontend-image img-responsive">' +
                        '</div>';
                    $('.upload-items', parent).html(html);
                    parent.find('.upload-button').removeClass('no_image');
                } else {
                    $('.upload-items', parent).empty();
                }
                $('.save-image-id', parent).val(json.id);
                $('.delete-button', parent).removeClass('none');
            });
        }

        $(document).on('click', '.st-metabox-content-wrapper .btn_upload_image' ,function (e) {
            $(this).addClass('active');
            var parent = $(this).parent();
            var img_frame;
            var imgIdInput =parent.find('.fg_metadata');

            e.preventDefault();
            if ( img_frame ) {
                img_frame.open();
                return;
            }

            img_frame = wp.media({
                title: 'Select or image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            img_frame.on( 'select', function() {
                var attachment = img_frame.state().get('selection').first().toJSON();

                parent.find('.img-demo-upload').empty().append('<img class="demo-image settings-demo-image" />').find('img').attr('src',attachment.url);
                imgIdInput.val( attachment.url );
                $('.btn_remove_demo_image_2', parent).removeClass('hidden');
            });

            // Finally, open the modal on click
            img_frame.open();

        }) ;
        $(document).on('click', '.btn_remove_demo_image_2', function(){
            var t = $(this);
            if(confirm('Delete this image?')){
                t.addClass('hidden');
                t.parent().find('.fg_metadata').val('');
                t.parent().find('.img-demo-upload').empty();
            }
        });
    })
})(jQuery);