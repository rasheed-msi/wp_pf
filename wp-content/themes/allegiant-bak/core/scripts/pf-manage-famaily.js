jQuery(document).ready(function($) {
    $.urlParam = function(name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        }
        else {
            return decodeURI(results[1]) || 0;
        }
    }
    
    var status = $.urlParam('family-status');
    if (status != null){
        var filterText = $('#pf-family-status-search').text(), 
         selectedText = $('#status-dropdown li[data-status='+status+'] a').text();
        $('#pf-family-status-search').text(filterText+':'+selectedText);
        $('#status-dropdown').prepend('<li><a href="'+pfFamilyObj.pageUrl+'">Filter By</a></li>');
    }
            

});
function saveUserStatus(status, profileId, agencyId) {
    jQuery.ajax({
        data:{action:'statusUpdate',status:status,profile_id:profileId, agency_id:agencyId},
        url:pfFamilyObj.ajaxUrl,
        type: 'POST',
        dataType: 'json',
        success: function(data, textStatus, jqXHR) {
            if(data.status == 'success'){
                alert('User Status updated success fully');
            }else{
                alert('Failed to update User Status');
            }
        }
    });
}