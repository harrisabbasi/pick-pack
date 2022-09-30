(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

})(jQuery);

jQuery(document).ready(function() {

    jQuery("#pick_pack_product_image_upload").click(function(e) {
        add_click_upload(e);
    });

    function add_click_upload(e) {
        
        e.preventDefault();
        var name = e.target.name;
        var id = e.target.id;

        var arrChkBox = jQuery("input[name="+name+"]");

        
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        image_frame = wp.media({
            title: 'Select Media',
            multiple: false,
            library: {
                type: 'image',
            }
        });
        image_frame.on('close', function() {

        });
        image_frame.on('open', function() {
            var selection = image_frame.state().get('selection');
            var ids_value = jQuery(arrChkBox).val();

            if (ids_value.length > 0) {
                attachment = wp.media.attachment(ids_value);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            }
        });

        image_frame.on('select', function() {
            var uploadedImages = image_frame.state().get("selection").first();
            var selectedImages = uploadedImages.toJSON();

            jQuery(arrChkBox).val(selectedImages.id);

        });
        image_frame.open();
    }
});