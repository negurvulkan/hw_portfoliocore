(function($){
    $(document).ready(function(){
        var frame;
        $('.hpc-add-images').on('click', function(e){
            e.preventDefault();
            var $button = $(this);
            var $list = $button.closest('.hpc-gallery-wrapper').find('.hpc-gallery-list');
            var $input = $('#projekt_gallery');

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media({
                title: $button.text(),
                button: { text: $button.text() },
                multiple: true
            });

            frame.on('select', function(){
                var attachments = frame.state().get('selection').toJSON();
                attachments.forEach(function(attachment){
                    var thumb = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    $list.append('<li data-attachment-id="' + attachment.id + '"><img src="' + thumb + '" /><span class="hpc-remove">&times;</span></li>');
                });
                updateInput();
            });

            frame.open();

            $list.on('click', '.hpc-remove', function(){
                $(this).closest('li').remove();
                updateInput();
            });

            function updateInput(){
                var ids = [];
                $list.find('li').each(function(){
                    ids.push($(this).data('attachment-id'));
                });
                $input.val(ids.join(','));
            }
        });
    });
})(jQuery);
