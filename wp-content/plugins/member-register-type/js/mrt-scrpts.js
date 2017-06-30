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
            if (response.status = 200){
                $this.attr('href', response.new_url);
                $this.html(response.new_label);
            }

        }, 'json');
    });

});


