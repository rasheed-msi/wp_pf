jQuery(function() {
    var elements;
    if (pf_tml_page == 'tml_email') {
        elements = "theme_my_login_email_new_user_message,theme_my_login_email_new_user_admin_message,theme_my_login_email_retrieve_pass_message,theme_my_login_email_reset_pass_admin_message,theme_my_login_email_user_activation_message,theme_my_login_email_user_approval_message,theme_my_login_email_user_approval_admin_message,theme_my_login_email_user_denial_message";
    }
    if(pf_tml_page == 'pf_email'){
        elements = "pf_theme_my_login_email_new_agency_message";
        postboxes.add_postbox_toggles(pagenow);
    }

    if (typeof (tinyMCE) == "object") {
        tinyMCE.init({
            mode: "exact",
            elements: elements,
            selector: 'textarea',
            height: 250,
            theme: 'modern',
            plugins: [
                'lists image charmap hr',
                //'fullscreen',
                'media directionality',
                'paste textcolor colorpicker'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            toolbar2: 'forecolor backcolor emoticons',
            image_advtab: true,
            templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
    }


    
});
