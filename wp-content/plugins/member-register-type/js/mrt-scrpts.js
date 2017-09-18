
jQuery(function ($) {
    $(".select_country").change(function () {
        var countryId = $(this).val();
        $.get(appConst.apiRequest + '/states/' + countryId, function (resp) {
            var html = '';
            $.each(resp, function (index, value) {
                html += '<option value="' + value.state_id + '"> ' + value.State + '</option>';
            });
            $(".select_state").html(html);
        });
    });


    $('#example').DataTable({
        "order": [[0, "desc"]]
    });


    $('.status_change').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var url = $(this).attr('href');
        $.get(url, function (response) {
            console.log(response);
            if (response.status = 200) {
                $this.attr('href', response.new_url);
                $this.html(response.new_label);
            }

        }, 'json');
    });

    $(".ch-agency").change(function () {
        if (this.checked) {
            $(this).parents("tr").find('[name="default"]').prop("checked", true);
        } else {
            // ON UNCHECK
            $(this).parents("tr").find('[name="default"]').prop("checked", false);

            var defaultChecked = true;
            $('tbody [name="default"]').each(function () {
                if ($(this).checked) {
                    defaultChecked = true;
                    return false;
                } else {
                    defaultChecked = false;
                }
            });

            if (!defaultChecked) {

                $('tbody .ch-agency').each(function () {
                    if (this.checked) {
                        $(this).parents("tr").find('[name="default"]').prop("checked", true);
                    }
                });
            }
        }
    });

    $("[name='user_email']").change(function () {
        var email = $(this).val();
        $("[name='user_login']").val(email);
    });
    
    $("[name='bemail']").keyup(function () {
        var email = $(this).val();
        $("[name='username']").val(email);
    });
    
    $("[name='agency_email']").keyup(function () {
        var email = $(this).val();
        $("[name='user_email']").val(email);
        $("[name='user_login']").val(email);
    });
    
    $("[name='user_email']").keyup(function () {
        var email = $(this).val();
        $("[name='user_login']").val(email);
    });


    $("body").on("click", ".file-uploader",function () {
        console.log("clicked");

        var userid = $(this).data('userid');
        var albumid = $(this).data('albumid');

        var client = filestack.init(appConst.fileStackClient);

        client.pick({
            accept: 'image/*',
            
            // fromSources: ['local_file_system', 'facebook', 'flickr', 'instagram', 'picasa'],
            fromSources: ['local_file_system'],
            maxFiles: 50,
            storeTo: {
                path: '/' + appConst.s3Domain + '/' + userid + '/album/' + albumid + '/original/',
                location: 'S3',
                access: 'public'
            },
            uploadInBackground: false,
            disableTransformer: true,
            onFileUploadProgress: function (file, progressEvent) {
                // console.log(JSON.stringify(progressEvent.totalProgressPercent))
            },
            onFileUploadFailed: function (file, error) {
                console.log(error);
            }
        }).then(function (result) {
            result.filesUploaded.forEach
            $.each(result.filesUploaded, function(index, value){
                value.pf_album_id = albumid;
                console.log(value);
                $.ajax({
                    url: appConst.apiRequest + '/' + value.pf_album_id + '/filestack-album-processing',
                    method: 'POST',
                    dataType: 'json',
                    data: value,
                    headers: {
                        Token: appConst.mrtToken
                    }
                }).success(function (response) {
                    console.log(response);
                });
            });
        });
    });


//    $('[name="loginform"]').submit(function (e) {
//
//        e.preventDefault();
//
//        var log = $('[name="log"]').val();
//        var pwd = $('[name="pwd"]').val();
//
//        $.ajax({
//            url: appConst.apiRequest + '/users/login',
//            method: 'POST',
//            dataType: 'json',
//            data: {
//                username: log,
//                password: pwd,
//            }
//        }).success(function (response) {
//            localStorage.setItem("Token", response.token);
//            window.location.href = appConst.base_url + '/dashboard';
//        });
//        
//        
//
//    });

    

});


