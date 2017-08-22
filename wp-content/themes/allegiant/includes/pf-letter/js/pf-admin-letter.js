/**
 * 
 * @param {type} obj
 * @returns {Boolean}
 */
function add_image(obj) {
    var parent = jQuery(obj).parent().parent('div.field_row');
    var inputField = jQuery(parent).find("input.meta_image_url");

    tb_show('', 'media-upload.php?type=image&amp&TB_iframe=1');

    window.send_to_editor = function(html) {
        var url = jQuery(html).attr('src');
//        console.log(html);
        inputField.val(url);
        jQuery(parent)
                .find("div.image_wrap")
                .html('<img src="' + url + '" height="48" width="48" />');

        // inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>'); 

        tb_remove();
    };

    return false;
}

/**
 * 
 * @param {type} obj
 * @returns {undefined}
 */
function remove_field(obj) {
    var parent = jQuery(obj).parent().parent();
    //console.log(parent)
    parent.remove();
}

/**
 * 
 * @returns {undefined}
 */
function add_field_row() {
    var row = jQuery('#master-row').html();
    jQuery(row).appendTo('#field_wrap');
}