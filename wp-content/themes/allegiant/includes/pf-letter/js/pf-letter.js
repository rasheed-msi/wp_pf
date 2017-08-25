jQuery(function($) {

    $(".articlePosts").sortable({
        items: '.author-letter-container',
        tolerance: 'pointer',
        revert: 'invalid',
        placeholder: 'col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-letter-container placeholder',
        forceHelperSize: true,
        start: function(e, ui) {
            ui.placeholder.height(ui.item.height());
        },
        update: function(event, ui) {
            var data = $(this).sortable('toArray', {attribute: 'data-id'});
            $.ajax({
                data: {letter_order: data, action: 'update_letter_order'},
                type: 'POST',
                dataType: 'json',
                url: letter_obj.ajax_url,
                success: function(data, textStatus, jqXHR) {
                    if (data.status == 200) {
                        $('<div id="letter-valid-msg" class="col-xs-12"><div class="alert alert-success">\n\
                            <strong>Success!</strong> Order updated.\n\
                        </div></div>').insertBefore('.add-letter-container')
                                .delay(1000).fadeOut(function() {
                            $(this).remove();
                        });
                    } else {
                        $('<div id="letter-valid-msg" class="col-xs-12"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Please try later.\n\
                        </div></div>').insertBefore('.add-letter-container')
                                .delay(1000).fadeOut(function() {
                            $(this).remove();
                        });
                    }
                }
            })
        }
    });
    /*tinymce init*/
    tinymce.init({
        mode: "exact",
        editor_selector :"tinymce-enabled",
        elements: 'letter-editor',
        theme: "modern",
        skin: "lightgray",
        menubar: true,
        statusbar: true,
        toolbar: [
            "bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | undo redo | link image"
        ],
        file_picker_types: 'image', 
        plugins: "paste image",
        image_title: true, 
        paste_auto_cleanup_on_paste: true,
        paste_postprocess: function(pl, o) {
            o.node.innerHTML = o.node.innerHTML.replace(/&nbsp;+/ig, " ");
        }
    });
    /*tinymce init*/

    $(document).on('mouseenter', '.author-letter-container', function() {
        $(this).find('.edit-letter,.delete-letter').css('visibility', 'visible');
    }).on('mouseleave', '.author-letter-container', function() {
        $(this).find('.edit-letter,.delete-letter').css('visibility', 'hidden');
    });

    $(document).on('click', '.edit-letter', function() {
        $('#edit-letter-modal').find('.modal-title').text('Edit Letter');
        var postId = $(this).attr('id').replace('edit-letter-', '');

        var titleElem = $('#letter-title-' + postId).text();
        var contentElem = $('#letter-content-' + postId).html();
        var isIntro = parseInt($('#is_intro_selected').val(), 10);
        isIntro = (isIntro == postId) ? true : false;

        //set values
        $('#letter-id').val(postId);
        $('#letter-action').val('update');
        $('#letter-title-text strong').text(titleElem);
        $('#is_intro').prop('checked', isIntro);
        tinymce.get('letter-editor').setContent(contentElem);

        showTextOnly();
        //show popup
        $('#edit-letter-modal').modal('show');

    });

    $(document).on('click', '.edit-letter-btn', function() {
        var currentBtnElem = $(this);
        disableButton(currentBtnElem);
        var letterID = parseInt($('#letter-id').val(), 10),
                letterTitle = '', letterContent = tinymce.get('letter-editor').getContent(),
                letterAction = $('#letter-action').val(), letterLabel = '',
                isIntro = $('#is_intro').is(':checked') ? 1 : 0, isIntroLetterId = parseInt($('#is_intro_selected').val(), 10);
        isIntroLetterId = isNaN(isIntroLetterId) ? 0 : isIntroLetterId;
        if (letterAction == 'add') {
            letterLabel = $('#letter-label').val();
            letterTitle = $('#letter-title').val();

            if (letterTitle.trim() == '') {
                $('<div id="letter-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Letter Title Required.\n\
                        </div></div>').prependTo('#letter-form').delay(1000).fadeOut(function() {
                    $(this).remove();
                });
                currentBtnElem.removeAttr('disabled');
                return false;
            }
        }

        if (letterContent.trim() == '') {
            $('<div id="letter-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Content Required.\n\
                        </div></div>').prependTo('#letter-form').delay(1000).fadeOut(function() {
                $(this).remove();
            });
            currentBtnElem.removeAttr('disabled');
            return false;
        }

        if (letterAction == 'update' && (letterID == 0 || letterID == false)) {
            $('<div id="letter-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong .Please try later.\n\
                        </div></div>').prependTo('#letter-form').delay(1000).fadeOut(function() {
                $(this).remove();
            });
            currentBtnElem.removeAttr('disabled');
            return false;
        }

        $.ajax({
            method: 'POST',
            url: letter_obj.ajax_url,
            dataType: 'json',
            data: {action: 'edit_letter', letter_id: letterID, letter_action: letterAction, letter_title: letterTitle, letter_content: letterContent, letter_label: letterLabel, is_intro: isIntro, current_intro_id: isIntroLetterId},
            success: function(data) {
                if (data.status == 200) {

                    $('<div class="alert alert-success">\n\
                            <strong>Success!</strong> Letter saved successfully.\n\
                        </div>').prependTo('#letter-form').delay(1000).fadeOut(function() {
                        $(this).remove();
                        if (letterAction == 'update') {
                            $('#letter-content-' + letterID).html(letterContent);
                            if (isIntro == 1) {
                                if (!($('#is_intro_selected').length > 0)) {
                                    $('<input>').attr({
                                        type: 'hidden',
                                        id: 'is_intro_selected',
                                        name: 'is_intro_selected',
                                        value: letterID
                                    }).prependTo('#letter-title-' + letterID)
                                }
                                $('#is_intro_selected').val(letterID);
                            }
                        } else {
                            var addLetter = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-letter-container letter-' + data.data.ID + '">\n\
                                    <a class="buttons text-center edit-letter" title="Edit this letter" id="edit-letter-' + data.data.ID + '"><i class="fa fa-pencil"></i></a>';
                            if (letterLabel == 'other') {
                                addLetter += '<a class="buttons text-center delete-letter" title="Delete this letter" id="delete-letter-' + data.data.ID + '"><i class="fa fa-trash"></i></a>';
                            } else {

                            }
                            addLetter += '<div class="articleItem">\n\
                                        <div class="articleItemHead clearfix noBg" id="letter-title-' + data.data.ID + '">' + data.data.post_title + '</div>\n\
                                        <div class="articleItemContents noPad" id="letter-content-' + data.data.ID + '">\n\
                                            ' + data.data.post_content + '\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
                            var addBtnParent = $('#add-letter').parent();
                            $(addLetter).insertAfter(addBtnParent);
                            if (isIntro == 1) {
                                $('#is_intro_selected').val(parseInt(data.data.ID, 10));
                            }
                        }

                        if (letterAction == 'add' && letterLabel !== 'other') {
                            $("select option[value*='" + letterLabel + "']").prop('disabled', true);
                        }

                        //clear content
                        $('#edit-letter-modal').modal('hide');
                        clearLetterModal();
                        enableButton(currentBtnElem);
                    });
                } else {
                    $('<div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong. Please try later.\n\
                        </div>').prependTo('#letter-form').delay(1000).fadeOut(function() {
                        $('#edit-letter-modal').modal('hide');
                        //clear content
                        clearLetterModal();
                        enableButton(currentBtnElem);
                    });
                }
            }
        });


    });

    $(document).on('click', '.delete-letter', function() {
        var letterID = $(this).attr('id').replace('delete-letter-', '');
        $('#del-letter-id').val(letterID);
        $('#delete-letter-modal').modal('show');
    });

    $(document).on('click', '.delete-btn', function(e) {
        var currentBtnElem = $(this), letterID = parseInt($('#del-letter-id').val(), 10);
        disableButton(currentBtnElem);

        $.ajax({
            method: 'POST',
            url: letter_obj.ajax_url,
            dataType: 'json',
            data: {action: 'delete_letter', 'letter-id': letterID},
            success: function(data) {
                if (data.status == 200) {
                    $('<div class="alert alert-success">\n\
                            <strong>Success!</strong> Letter removed successfully.\n\
                        </div>').prependTo('#delete-letter-modal .modal-body').delay(1000).fadeOut(function() {
                        $('.letter-' + letterID).fadeOut(500);
                        $(this).remove();
                        $('#delete-letter-modal').modal('hide');
                        enableButton(currentBtnElem);
                    });
                } else {
                    $('<div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Something went wrong. Please try later.\n\
                        </div>').prependTo('#delete-letter-modal .modal-body').delay(1000).fadeOut(function() {
                        $('#delete-letter-modal').modal('hide');
                        enableButton(currentBtnElem);
                    });
                }
            }
        });
    });


    $('#add-letter').on('click', function() {
        disableButton($(this));
        clearLetterModal();
        showLabelOnly();
        $('#letter-action').val('add');
        $('#edit-letter-modal').find('.modal-title').text('Add Letter');
        $('#letter-action').val('add');
        $('#edit-letter-modal').modal('show');

        enableButton($(this));
    });

    $(document).on('click', '.cancel', function() {
        clearLetterModal();
        showLabelOnly();
    });

    //asociative image

    $(document).on('click', '#asociate-img', function() {
        $('#associate-img-container').toggleClass('hide');
    });


    //letter func
    $('#letter-label').on('change', function() {
        var optVal = $(this).val();
        if (optVal == 'other') {
            $('#letter-title').val('');
            $('#letter-title-group').removeClass('hide');
        } else {
            $('#letter-title').val($(this).find('option:selected').text());
            $('#letter-title-group').addClass('hide');
        }
    });

    function showLabelOnly() {
        $('#letter-title-text').addClass('hide');
        $('#letter-title-group').addClass('hide');
        $('#letter-label-group').removeClass('hide');
    }

    function showTextOnly() {
        $('#letter-label-group').addClass('hide');
        $('#letter-title-group').addClass('hide');
        $('#letter-title-text').removeClass('hide');
    }

    function disableButton(currentBtnElem) {
        currentBtnElem.attr('disabled', 'disabled');
    }

    function enableButton(currentBtnElem) {
        currentBtnElem.removeAttr('disabled');
    }

    function clearLetterModal() {
        $('#letter-id').val(0);
        $('#letter-action').val('');
        $('#letter-title').val('');
        $('#is_intro').prop('checked', false);
        $('#letter-label').val('');
        $('#letter-title-text strong').text('');
        $('#del-letter-id').val('');//del letter id
        tinymce.get('letter-editor').setContent('');
    }

    function ArrangeLetter(data) {

    }
});