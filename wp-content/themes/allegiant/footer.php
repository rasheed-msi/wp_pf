<?php do_action('cpotheme_after_main'); ?>
<?php if (is_page(['mrt-page-manager', 'login'])): ?>
    <div style="height:280px"></div>
<?php endif; ?>
<?php if (isset($_GET['action']) && in_array($_GET['action'], ['payments', 'subscriptions', 'payments'])): ?>
    <div class="clearfix"></div>
    <div style="height:420px"></div>
<?php endif; ?>			
<?php get_sidebar('footer'); ?>

<?php do_action('cpotheme_before_footer'); ?>

<footer id="footer" class="footer secondary-color-bg dark">
    <div class="container">
        <?php do_action('cpotheme_footer'); ?>
    </div>
</footer>
<?php do_action('cpotheme_after_footer'); ?>

<div class="clear"></div>
</div><!-- wrapper -->
<?php do_action('cpotheme_after_wrapper'); ?>
</div><!-- outer -->
<?php wp_footer(); ?>
</body>
</html>
