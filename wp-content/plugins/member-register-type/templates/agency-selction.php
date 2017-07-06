<?php $agencies = State::get_approved_agencies_html_select(); ?>
<div>
    <h2>Agency Selection</h2>
    <form action="" method="post" >
        
        <table class="agency_selection">
            <thead>
                <tr>
                    <th>Agency</th>
                    <th>Default</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agencies as $key => $value): ?>
                    <?php $selected = (in_array($key, $data['agencies'])) ? 'checked' : ''; ?>
                    <?php $default_selected = ($data['default'] == $key) ? 'checked' : ''; ?>
                    <tr>
                        <td><label><input class="ch-agency" type="checkbox" name="agencies[]" value="<?php echo $key; ?>" <?php echo $selected ?>> <?php echo $value; ?></label></td>
                        <td><input type="radio" name="default" value="<?php echo $key; ?>" <?php echo $default_selected ?>></td>
                    </tr>
                <?php endforeach; ?>


            </tbody>
        </table>

        <div class="tml-submit-wrap">
            <input type="hidden" name="action" value="agency_selection">
            <input type="submit" value="Submit">
        </div>


    </form>
</div>