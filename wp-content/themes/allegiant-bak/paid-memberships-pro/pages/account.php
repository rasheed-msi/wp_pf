<?php
echo pmpro_shortcode_account('');
?>
<form action="<?php echo site_url('user-dashboard'); ?>" method="post" >
    <div class="tml-submit-wrap">
        <input name="action" value="agency_selection" type="hidden">
        <input value="Next" type="submit">
    </div>
</form>