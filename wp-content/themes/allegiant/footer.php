
<?php if (isset($_GET['applogout']) && $_GET['applogout'] == '1'): ?>
    <script type="text/javascript">
        history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
        window.addEventListener('popstate', function (event) {
            window.location.assign('<?php echo home_url() . '?applogout=1' ?>');
        });

    </script>
<?php endif; ?>
    
<?php wp_footer(); ?>

</body>
</html>