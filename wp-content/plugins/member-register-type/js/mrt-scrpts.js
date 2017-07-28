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
    $("[name='agency_email']").change(function () {
        var email = $(this).val();
        $("[name='user_email']").val(email);
    });


    $("#uploader").click(function () {
        var client = filestack.init('Aym4Su0dJRFaqnWPrLu0Az');
        client.pick({
            accept: 'image/*',
            // fromSources: ['local_file_system', 'facebook', 'flickr', 'instagram', 'picasa'],
            fromSources: ['local_file_system'],
            maxFiles: 50,
            storeTo: {
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
            console.log(JSON.stringify(result.filesUploaded))
        });
    });

    function base64_encode(stringToEncode) { // eslint-disable-line camelcase
        //  discuss at: http://locutus.io/php/base64_encode/
        // original by: Tyler Akins (http://rumkin.com)
        // improved by: Bayron Guevara
        // improved by: Thunder.m
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // improved by: Rafał Kukawski (http://blog.kukawski.pl)
        // bugfixed by: Pellentesque Malesuada
        // improved by: Indigo744
        //   example 1: base64_encode('Kevin van Zonneveld')
        //   returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
        //   example 2: base64_encode('a')
        //   returns 2: 'YQ=='
        //   example 3: base64_encode('✓ à la mode')
        //   returns 3: '4pyTIMOgIGxhIG1vZGU='
        // encodeUTF8string()
        // Internal function to encode properly UTF8 string
        // Adapted from Solution #1 at https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding
        var encodeUTF8string = function (str) {
            // first we use encodeURIComponent to get percent-encoded UTF-8,
            // then we convert the percent encodings into raw bytes which
            // can be fed into the base64 encoding algorithm.
            return encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
                    function toSolidBytes(match, p1) {
                        return String.fromCharCode('0x' + p1)
                    })
        }
        if (typeof window !== 'undefined') {
            if (typeof window.btoa !== 'undefined') {
                return window.btoa(encodeUTF8string(stringToEncode))
            }
        } else {
            return new Buffer(stringToEncode).toString('base64')
        }
        var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/='
        var o1
        var o2
        var o3
        var h1
        var h2
        var h3
        var h4
        var bits
        var i = 0
        var ac = 0
        var enc = ''
        var tmpArr = []
        if (!stringToEncode) {
            return stringToEncode
        }
        stringToEncode = encodeUTF8string(stringToEncode)
        do {
            // pack three octets into four hexets
            o1 = stringToEncode.charCodeAt(i++)
            o2 = stringToEncode.charCodeAt(i++)
            o3 = stringToEncode.charCodeAt(i++)
            bits = o1 << 16 | o2 << 8 | o3
            h1 = bits >> 18 & 0x3f
            h2 = bits >> 12 & 0x3f
            h3 = bits >> 6 & 0x3f
            h4 = bits & 0x3f
            // use hexets to index into b64, and append result to encoded string
            tmpArr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4)
        } while (i < stringToEncode.length)
        enc = tmpArr.join('')
        var r = stringToEncode.length % 3
        return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3)
    }


    $.ajax({
        url: appConst.apiRequest + '/current-user',
        dataType: 'json',
        headers: {
            _token: 'MTMzNDExMTkzMDk0MTU0'
        }
    }).done(function (response) {
        console.log(response);
    });
    
});


