/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery('#country_state_menu').on('hide.bs.dropdown', function(e) {
    if ( jQuery(this).find('li').first().hasClass('active') ){
        e.preventDefault();
    }
    jQuery(this).find('li').first().removeClass('active');
});

jQuery('#myTabs').on('click', '.nav-tabs a', function(){
    jQuery(this).closest('.dropdown').addClass('active');
})