jQuery(function($) {

    /*tinymce init*/
    tinymce.init( {
        mode : "exact",
        elements : 'letter-editor',
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

    $(document).on('mouseenter','.author-letter-container',function() {
        $(this).find('.edit-letter,.delete-letter').css('visibility','visible');
    }).on('mouseleave','.author-letter-container', function() {
        $(this).find('.edit-letter,.delete-letter').css('visibility','hidden');
    });

    $(document).on('click', '.edit-letter', function() {
        $('#edit-letter-modal').find('.modal-title').text('Edit Letter');
        var postId = $(this).attr('id').replace('edit-post-', '');
        
        var titleElem = $('#post-title-'+ postId).text();
        var contentElem = $('#post-content-'+ postId).text();

        //set values
        $('#letter-id').val(postId);
        $('#letter-action').val('update');
        $('#letter-title').val(titleElem);
        tinymce.get('letter-editor').setContent(contentElem);

        //show popup
        $('#edit-letter-modal').modal('show');
        
    });

    $(document).on('click','.edit-letter-btn', function() {
        var currentBtnElem = $(this);
        currentBtnElem.attr('disabled', 'disabled');
        var letterID = parseInt($('#letter-id').val(), 10), letterTitle = $('#letter-title').val(), jornalContent = tinymce.get('letter-editor').getContent(), letterAction = $('#letter-action').val();
        if(letterTitle.trim() == ''){
            $('<div id="letter-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Title Required.\n\
                        </div></div>').prependTo('#letter-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                        });
                currentBtnElem.removeAttr('disabled');
                return false;
        }

        if(jornalContent.trim() == ''){
            $('<div id="letter-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Content Required.\n\
                        </div></div>').prependTo('#letter-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                        });
            currentBtnElem.removeAttr('disabled');
            return false; 
        }

        if(letterAction == 'update' && (letterID == 0 || letterID == false )  ){
            $('<div id="letter-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong .Please try later.\n\
                        </div></div>').prependTo('#letter-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                        });
            currentBtnElem.removeAttr('disabled');
            return false;
        }

        $.ajax({
            method: 'POST',
            url: letter_obj.ajax_url,
            dataType: 'json',
            data:{action:'edit_letter', letter_id : letterID, jornal_action: letterAction, letter_title: letterTitle, jornal_content: jornalContent},
            success: function(data){
                   if(data.status == 200){

                        $('<div class="alert alert-success">\n\
                            <strong>Success!</strong> Letter saved successfully.\n\
                        </div>').prependTo('#letter-form').delay(1000).fadeOut(function(){
                            $(this).remove();
                            if(letterAction == 'update'){
                                $('#post-title-' + letterID).text(letterTitle);
                                $('#post-content-' + letterID).html(jornalContent);
                            }else{
                                var addLetter = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-letter-container letter-'+data.data.ID+'">\n\
                                    <a class="buttons text-center edit-letter" title="Edit this letter" id="edit-post-'+data.data.ID+'"><i class="fa fa-pencil"></i></a>\n\
                                    <a class="buttons text-center delete-letter" title="Delete this letter" id="delete-post-'+data.data.ID+'"><i class="fa fa-trash"></i></a>\n\
                                    <div class="articleItem">\n\
                                        <div class="articleItemHead clearfix noBg"><span class="pull-left " id="post-title-'+data.data.ID+'">'+ data.data.post_title +'</span><span class="pull-right postDate">'+ data.data.post_date+'</span></div>\n\
                                        <div class="articleItemContents noPad" id="post-content-'+data.data.ID+'">\n\
                                            '+data.data.post_content+'\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
                                var addBtnParent = $('#add-letter').parent();
                                $(addLetter).insertAfter(addBtnParent);
                            }
                            //clear content
                            $('#edit-letter-modal').modal('hide');
                            $('#letter-id').val('');
                            $('#letter-action').val('');
                            $('#letter-title').val('');
                            tinymce.get('letter-editor').setContent('');
                            currentBtnElem.removeAttr('disabled');
                        });
                    }else{
                        $('<div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong. Please try later.\n\
                        </div>').prependTo('#letter-form').delay(1000).fadeOut(function(){
                        $('#edit-letter-modal').modal('hide');
                        //clear content
                        $('#letter-id').val('');
                        $('#letter-action').val('');
                        $('#letter-title').val('');
                        tinymce.get('letter-editor').setContent('');
                        currentBtnElem.removeAttr('disabled');
                       });
                    }
                }
        });

        
    });

    $(document).on('click','.delete-letter', function() {
        var jornalID = $(this).attr('id').replace('delete-post-', '');
        $('#del-letter-id').val(jornalID);
        $('#delete-letter-modal').modal('show');
    });

    $(document).on('click', '.delete-btn', function(e) {
            var currentBtnElem = $(this), jornalID = parseInt( $('#del-letter-id').val(),10);
            currentBtnElem.attr('disabled', 'disabled');
          $.ajax({
            method: 'POST',
            url: letter_obj.ajax_url,
            dataType: 'json',
            data:{action:'delete_letter', 'letter-id' : jornalID},
            success: function(data){
                    if(data.status == 200){
                        $('<div class="alert alert-success">\n\
                            <strong>Success!</strong> Letter removed successfully.\n\
                        </div>').prependTo('#delete-letter-modal .modal-body').delay(1000).fadeOut(function(){
                            $('.letter-'+jornalID).fadeOut(500);
                            $(this).remove();
                            $('#delete-letter-modal').modal('hide');
                            currentBtnElem.removeAttr('disabled');
                        });
                    }else{
                        $('<div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong. Please try later.\n\
                        </div>').prependTo('#delete-letter-modal .modal-body').delay(1000).fadeOut(function(){
                       $('#delete-letter-modal').modal('hide');
                       currentBtnElem.removeAttr('disabled');
                       });
                    }
                }
            });
        });


    $('#add-letter').on('click', function(){
        var currentBtnElem = $(this);
        currentBtnElem.attr('disabled', 'disabled');
        $('#edit-letter-modal').find('.modal-title').text('Add Letter');
        $('#letter-action').val('add');
        $('#letter-id').val(0);
        $('#edit-letter-modal').modal('show');
        currentBtnElem.removeAttr('disabled');
    });

    $(document).on('click', '.cancel', function(){
        $('#letter-id').val('');
        $('#letter-action').val('');
        $('#letter-title').val('');
        tinymce.get('letter-editor').setContent('');
        $('#del-letter-id').val('');//del letter id
    })

    
});