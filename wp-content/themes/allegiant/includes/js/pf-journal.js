jQuery(function($) {
    $('.edit-journal-container').mouseenter(function() {
        $(this).find('.edit-journal').show();
    }).mouseleave(function() {
        $(this).find('.edit-journal').hide();
    });

    $('.edit-journal').on('click', function() {
        var postId = $(this).attr('id').replace('post-', '');
        
        var titleElem = $('#post-title-'+ postId);
        var contentElem = $('#post-content-'+ postId);
        
        alert(titleElem.text());
        alert(contentElem.text());
        
        var switchToInput = function() {
            var $input = $("<input>", {
                val: $(this).text(),
                type: "text"
            });
            $input.addClass("loadNum");
            $(this).replaceWith($input);
            $input.on("blur", switchToSpan);
            $input.select();
        };
        var switchToSpan = function() {
            var $span = $("<span>", {
                text: $(this).val()
            });
            $span.addClass("loadNum");
            $(this).replaceWith($span);
            $span.on("click", switchToInput);
        };
        $(".loadNum").on("click", switchToInput);
    });
});