<?php
/*
  Template Name: Agency Manage Families
 */

get_header();
wp_enqueue_script('pf-manage-famaily');
?>

<section id="primary" class="content-area col-sm-12">
    <main id="main" class="site-main" role="main">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php manageFamilies(); ?>
            </div><!-- .entry-content -->

        </article><!-- #post-## -->

    </main><!-- #main -->
</section><!-- #primary -->


<?php
get_footer();
