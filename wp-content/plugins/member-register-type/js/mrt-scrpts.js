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
    $("[name='agency_email']").change(function () {
        var email = $(this).val();
        $("[name='user_email']").val(email);
    });

});


