jQuery(function($) {

    /*tinymce init*/
    tinymce.init( {
        mode : "exact",
        elements : 'journal-editor',
        theme: "modern",
        skin: "lightgray",
        menubar : true,
        statusbar : true,
        toolbar: [
            "bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | undo redo"
        ],
        plugins : "paste",
        paste_auto_cleanup_on_paste : true,
        paste_postprocess : function( pl, o ) {
            o.node.innerHTML = o.node.innerHTML.replace( /&nbsp;+/ig, " " );
        }
    });
    /*tinymce init*/

    $(document).on('mouseenter','.author-journal-container',function() {
        $(this).find('.edit-journal,.delete-journal').css('visibility','visible');
    }).on('mouseleave','.author-journal-container', function() {
        $(this).find('.edit-journal,.delete-journal').css('visibility','hidden');
    });

    $(document).on('click', '.edit-journal', function() {
        $('#edit-journal-modal').find('.modal-title').text('Edit Journal');
        var postId = $(this).attr('id').replace('edit-post-', '');
        
        var titleElem = $('#post-title-'+ postId).text();
        var contentElem = $('#post-content-'+ postId).text();

        //set values
        $('#journal-id').val(postId);
        $('#journal-action').val('update');
        $('#journal-title').val(titleElem);
        tinymce.get('journal-editor').setContent(contentElem);

        //show popup
        $('#edit-journal-modal').modal('show');
        
    });

    $(document).on('click','.edit-journal-btn', function() {
        var journalID = parseInt($('#journal-id').val(), 10), journalTitle = $('#journal-title').val(), jornalContent = tinymce.get('journal-editor').getContent(), journalAction = $('#journal-action').val();
        if(journalTitle.trim() == ''){
            $('<div id="journal-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Title Required.\n\
                        </div></div>').prependTo('#journal-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                        });
                return false;
        }

        if(jornalContent.trim() == ''){
            $('<div id="journal-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Content Required.\n\
                        </div></div>').prependTo('#journal-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                        });
            return false; 
        }

        if(journalAction == 'update' && (journalID == 0 || journalID == false )  ){
            $('<div id="journal-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong .Please try later.\n\
                        </div></div>').prependTo('#journal-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                        });
            return false;
        }

        $.ajax({
            method: 'POST',
            url: journal_obj.ajax_url,
            dataType: 'json',
            data:{action:'edit_journal', journal_id : journalID, jornal_action: journalAction, journal_title: journalTitle, jornal_content: jornalContent},
            success: function(data){
                   if(data.status == 200){

                        $('<div class="alert alert-success">\n\
                            <strong>Success!</strong> Journal removed successfully.\n\
                        </div>').prependTo('#journal-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                            if(journalAction == 'update'){
                                $('#post-title-' + journalID).text(journalTitle);
                                $('#post-content-' + journalID).html(jornalContent);
                            }else{
                                var addJournal = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-journal-container journal-'+data.data.ID+'">\n\
                                    <a class="buttons text-center edit-journal" title="Edit this journal" id="edit-post-'+data.data.ID+'"><i class="fa fa-pencil"></i></a>\n\
                                    <a class="buttons text-center delete-journal" title="Delete this journal" id="delete-post-'+data.data.ID+'"><i class="fa fa-trash"></i></a>\n\
                                    <div class="articleItem">\n\
                                        <div class="articleItemHead clearfix noBg"><span class="pull-left " id="post-title-'+data.data.ID+'">'+ data.data.post_title +'</span><span class="pull-right postDate">'+ data.data.post_date+'</span></div>\n\
                                        <div class="articleItemContents noPad" id="post-content-'+data.data.ID+'">\n\
                                            +data.journalContent\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
                                var addBtnParent = $('#add-journal').parent();
                                $(addJournal).insertAfter(addBtnParent);
                            }
                            //clear content
                            $('#edit-journal-modal').modal('hide');
                            $('#journal-id').val('');
                            $('#journal-action').val('');
                            $('#journal-title').val('');
                            tinymce.get('journal-editor').setContent('');
                        });
                    }else{
                        $('<div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong. Please try later.\n\
                        </div>').prependTo('#journal-form').delay(1000).fadeOut(function(){
                        $('#edit-journal-modal').modal('hide');
                        //clear content
                        $('#journal-id').val('');
                        $('#journal-action').val('');
                        $('#journal-title').val('');
                        tinymce.get('journal-editor').setContent('');
                       });
                    }
                }
        });

        
    });

    $(document).on('click','.delete-journal', function() {
        var jornalID = $(this).attr('id').replace('delete-post-', '');
        $('#delete-journal-modal').modal({
          backdrop: 'static',
          keyboard: false
        }).one('click', '.delete-btn', function(e) {
          $.ajax({
            method: 'POST',
            url: journal_obj.ajax_url,
            dataType: 'json',
            data:{action:'delete_journal', 'journal-id' : jornalID},
            success: function(data){
                    if(data.status == 200){
                        $('<div class="alert alert-success">\n\
                            <strong>Success!</strong> Journal removed successfully.\n\
                        </div>').prependTo('#delete-journal-modal .modal-body').delay(1000).fadeOut(function(){
                            $('.journal-'+jornalID).fadeOut(500);
                            $(this).remove();
                            $('#delete-journal-modal').modal('hide');
                        });
                        //$(this).("Journal removed successfully");
                    }else{
                        $('<div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong. Please try later.\n\
                        </div>').prependTo('#delete-journal-modal .modal-body').delay(1000).fadeOut(function(){
                       $('#delete-journal-modal').modal('hide');
                       });
                    }
                }
            });
        });
        });


    $('#add-journal').on('click', function(){
        $('#edit-journal-modal').find('.modal-title').text('Add Journal');
        $('#journal-action').val('add');
        $('#journal-id').val(0);
        $('#edit-journal-modal').modal('show');
    });

    
});

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}