<?php

/**
 * Post Type : Journals
 */

function pf_journal_scripts() {
    wp_enqueue_script( 'pf-journal', get_template_directory_uri() . '/includes/js/pf-journal.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'pf_journal_scripts' );