jQuery(function($) {

    $(".articlePosts").sortable({
        items: '.author-letter-container',
        tolerance: 'pointer',
        revert: 100,
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
        editor_selector: "tinymce-enabled",
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
        var photoIds = $('#selected_assoc_img' + postId).val();
        var assocImgData = getAssocImgData(photoIds);
        checkSelectedImgs(photoIds);
        //set values
        $('#letter-id').val(postId);
        $('#letter-action').val('update');
        $('#letter-title-text strong').text(titleElem);
        $('#is_intro').prop('checked', isIntro);
        tinymce.get('letter-editor').setContent(contentElem);
        $('#selected-assoc-list').html(assocImgData);
        $('#photo-ids').val(photoIds);

        showTextOnly();
        //show popup
        $('#edit-letter-modal').modal('show');

    });

    $(document).on('click', '.edit-letter-btn', function() {
        var n = $("input[name='asso_image[]']:checked").length;
        var currentBtnElem = $(this);
        disableButton(currentBtnElem);
        var letterID = parseInt($('#letter-id').val(), 10),
                letterTitle = '', letterContent = tinymce.get('letter-editor').getContent(),
                letterAction = $('#letter-action').val(), letterLabel = '',
                isIntro = $('#is_intro').is(':checked') ? 1 : 0, isIntroLetterId = parseInt($('#is_intro_selected').val(), 10),
                photoIds = $('#photo-ids').val();
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

            if (checkTitleExist(letterTitle, letterID) == false) {
                $('<div id="letter-valid-msg"><div class="alert alert-danger">\n\
                            <strong>Failed!</strong> Letter Title Exist.\n\
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
            data: {action: 'edit_letter', letter_id: letterID, letter_action: letterAction, letter_title: letterTitle, letter_content: letterContent, letter_label: letterLabel, is_intro: isIntro, current_intro_id: isIntroLetterId, photo_ids: photoIds},
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
                                } else {
                                    $('#is_intro_selected').val(letterID);
                                }

                            }
                            $('#selected_assoc_img' + letterID).val(photoIds);
                        } else {
                            var addLetter = '<div data-id="' + data.data.ID + '" class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-letter-container letter-' + data.data.ID + '">\n\
                                    <a class="buttons text-center edit-letter" title="Edit this letter" id="edit-letter-' + data.data.ID + '"><i class="fa fa-pencil"></i></a>';
                            if (letterLabel == 'other') {
                                addLetter += '<a class="buttons text-center delete-letter" title="Delete this letter" id="delete-letter-' + data.data.ID + '"><i class="fa fa-trash"></i></a>';
                            } else {

                            }
                            addLetter += '<div class="articleItem">\n\
                            <input type="hidden" id="selected_assoc_img' + data.data.ID + '" name="selected_assoc_img' + data.data.ID + '" value="' + photoIds + '"/>\n\
                                        <div class="articleItemHead clearfix noBg" id="letter-title-' + data.data.ID + '">' + data.data.post_title + '</div>\n\
                                        <div class="articleItemContents noPad" id="letter-content-' + data.data.ID + '">\n\
                                            ' + data.data.post_content + '\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
                            var addBtnParent = $('#add-letter').parent();
                            $(addLetter).insertAfter(addBtnParent);
                            if (isIntro == 1) {
                                if (!($('#is_intro_selected').length > 0)) {
                                    $('<input>').attr({
                                        type: 'hidden',
                                        id: 'is_intro_selected',
                                        name: 'is_intro_selected',
                                        value: parseInt(data.data.ID, 10)
                                    }).prependTo('#letter-title-' + letterID)
                                } else {
                                    $('#is_intro_selected').val(parseInt(data.data.ID, 10));
                                }

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

    $(".image-checkbox").each(function() {
        if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
            $(this).addClass('image-checkbox-checked');
        }
        else {
            $(this).removeClass('image-checkbox-checked');
        }
    });

// sync the state to the input
    $(".image-checkbox").on("click", function(e) {
        $(this).toggleClass('image-checkbox-checked');
        var $checkbox = $(this).find('input[type="checkbox"]');
        $checkbox.prop("checked", !$checkbox.prop("checked"))

        e.preventDefault();
    });


    $('#edit-letter-modal, #delete-letter-modal').on('hidden.bs.modal', function() {
        clearLetterModal();
    });


    $('#selected-assoc-list').on('click', '.assoc-close', function() {
        var photoId = String($(this).data('id')), curSelImgs = $('#photo-ids').val();
        if (typeof curSelImgs != 'undefined') {
            var curSelImgsObj = curSelImgs.split(',');
            var index = curSelImgsObj.indexOf(photoId);
            if (index > -1) {
                curSelImgsObj.splice(index, 1);
                $('#photo-ids').val(curSelImgsObj.join());
            }
            clearSelectedImgs(photoId);
        }

        $(this).parents('li').remove();


        if (!($('#selected-assoc-list li').length > 0)) {
            var assocImgData = getAssocImgData('');
            $('#selected-assoc-list').html(assocImgData);
            $('#photo-ids').val('');
        }
    });

    $('#associate-img-container').on("click", ".image-checkbox", function(e) {

        var curElemObj = $(this).find('input[id^=asso_image]');
        var curElemChkd = curElemObj.is(':checked'), curElemVal = curElemObj.val();
        var photoIds = $('#photo-ids').val();

        var AlbumCheckObj = $("input[name='asso_image[]']:checked");
        if (AlbumCheckObj.length > 0) {
            var photoObj = [];
            AlbumCheckObj.each(function() {
                photoObj.push($(this).val());
            });

            var curSelImgsObj = (photoIds != 'undefined' && photoIds != '') ? photoIds.split(',') : [];
            if (photoObj.sort().compare(curSelImgsObj.sort())) {
                $('#add-assoc-images').addClass('hide');
            } else {
                $('#add-assoc-images').removeClass('hide');
            }

//            if (photoObj != [])
//                $('#photo-ids').val(photoObj.sort().join());
//            else
//                $('#photo-ids').val('');
        } else {
            if (photoIds != 'undefined' && photoIds != '') {
                $('#add-assoc-images').removeClass('hide');
            } else {
                $('#add-assoc-images').addClass('hide');
            }
            //$('#photo-ids').val('');
        }
    });

    $('#add-assoc-images').click(function() {
        var AlbumCheckObj = $("input[name='asso_image[]']:checked");
        if (AlbumCheckObj.length > 0) {
            var photoObj = [];
            $('#selected-assoc-list').html('');
            AlbumCheckObj.each(function() {
                var photoId = $(this).val();
                photoObj.push(photoId);
            });
            var photoIds = photoObj.sort().join();
            $('#photo-ids').val(photoIds);
            var assocData = getAssocImgData(photoIds);
        } else {
            $('#photo-ids').val('');
            var assocData = getAssocImgData('');
        }

        $('#selected-assoc-list').html(assocData);
        $('#add-assoc-images').addClass('hide');
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
        $('#associate-img-container, #add-assoc-images').addClass('hide');
        $("input[name='asso_image[]']:checkbox").prop('checked', false);
        $(".image-checkbox").removeClass('image-checkbox-checked');
        $('.panel-collapse').removeClass('in').addClass('collapse');
        tinymce.get('letter-editor').setContent('');
        $('#selected-assoc-list').html('');
        var photoIds = $('#photo-ids').val();
        clearSelectedImgs(photoIds);
        $('#photo-ids').val('');

        var assocImgData = getAssocImgData('');
        $('#selected-assoc-list').html(assocImgData);
    }

    function getAssocImgData(letterPhotoIds) {
        if (typeof letterPhotoIds != 'undefined' && letterPhotoIds != '') {
            var letterPhotoIdsObj = letterPhotoIds.split(',');
            var BuildHtml = '';
            $.each(letterPhotoIdsObj, function(index, value) {
                var assocImageObj = $('#assoc-img-' + value);
                var photoSrc = assocImageObj.attr('src');
                BuildHtml += '<li class="col-md-2"><div class="thumbnail"><a data-id="' + value + '" class="assoc-close" href="#">×</a><img src="' + photoSrc + '" class="img-responsive"></li>';
            });
            return BuildHtml;
        } else {
            return '<p class="text-center">No images selected</p>';
        }
    }

    function checkSelectedImgs(letterPhotoIds) {
        if (typeof letterPhotoIds != 'undefined' && letterPhotoIds != '') {
            var letterPhotoIdsObj = letterPhotoIds.split(',')
            $.each(letterPhotoIdsObj, function(index, value) {
                $('#asso_image' + value).prop('checked', true);
                $('#asso_image' + value).parent('label').addClass('image-checkbox-checked');
            });
        }
    }


    function clearSelectedImgs(letterPhotoIds) {
        if (typeof letterPhotoIds != 'undefined' && letterPhotoIds != '') {
            var letterPhotoIdsObj = letterPhotoIds.split(',')
            $.each(letterPhotoIdsObj, function(index, value) {
                $('#asso_image' + value).prop('checked', false);
                $('#asso_image' + value).parent('label').removeClass('image-checkbox-checked');
            });
        }
    }

    function checkTitleExist(title, id) {
        var LetterTitles = $('.articleItem div[id^=letter-title-]');
        if (LetterTitles.length > 0) {
            title = title.trim().toLowerCase();
            var status = true;
            $.each(LetterTitles, function(LetterTitle) {
                if (id !== parseInt($(this).attr('id').replace('letter-title-', ''), 10)) {
                    if ($(this).text().trim().toLowerCase() == title) {
                        status = false;
                    }
                }
            });
            return status;
        } else {
            return true;
        }
    }
});


Array.prototype.compare = function(testArr) {
    if (this.length != testArr.length)
        return false;
    for (var i = 0; i < testArr.length; i++) {
        if (this[i].compare) { //To test values in nested arrays
            if (!this[i].compare(testArr[i]))
                return false;
        }
        else if (this[i] !== testArr[i])
            return false;
    }
    return true;
}