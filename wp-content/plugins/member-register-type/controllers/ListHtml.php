<?php

class ListHtml {

    private $form;

    function __construct() {
        
    }

    function create_list($form) {

        $this->form = $form;

        $html = '';
        
        if(!is_array($this->form['fields'])){
            return false;
        }

        foreach ($this->form['fields'] as $key => $field) {
            $html .= $this->form_group_html($field);
        }

        return $html;
    }

    function form_group_html($field) {

        extract($field);

        ob_start();

        $col = (isset($col) && $col == 6) ? '<div class="col-sm-6 col-md-6">' : '<div class="col-sm-12 col-md-12">';

        switch ($type):


            case 'email':
            case 'password':
            case 'phone':
            case 'text':
            case 'url':
                ?>
                <?php echo $col; ?>

                <p><?php echo $label ?>: <?php echo $value; ?></p>

                <?php echo '</div>'; ?>

                <?php
                break;

            case 'textarea':
                ?><?php echo $col; ?>
                <h3><?php echo $label ?></h3>
                <p><?php echo $value; ?></p>

                <?php echo '</div>'; ?>

                <?php
                break;

            case 'radio':
                ?>
                <?php echo $col; ?>
                <?php
                foreach ($options as $key => $optval){
                    if($key == $value){
                        $option_value = $optval;
                        break;
                    }   
                } 
                ?>
                <p><?php echo $label . ': ' . $option_value; ?></p>
                <?php echo '</div>'; ?>
                <?php
                break;

            case 'checkbox':
                ?>
                <?php echo $col; ?>
                <?php
                $option_value = [];
                foreach ($options as $key => $optval){
                    if($key == $value){
                        $option_value[] = $optval;
                        break;
                    }   
                } 
                ?>
                <p><?php echo $label . ': ' . implode(', ', $option_value); ?></p>
                <?php echo '</div>'; ?>
                <?php
                break;

            case 'select':
                ?>
                <?php echo $col; ?>
                <?php
                foreach ($options as $key => $optval){
                    if($key == $value){
                        $option_value = $optval;
                        break;
                    }   
                } 
                ?>
                <p><?php echo $label . ': ' . $option_value; ?></p>
                <?php echo '</div>'; ?>
                <?php
                break;

            case 'select-multiple':
                ?>
                <?php echo $col; ?>
                        <?php
                        $checked = '';
                        foreach ($options as $key => $option_value):
                            $checked = ($key == $value) ? ', ' . $option_value : '';
                        endforeach; ?>
                    <p><?php echo $label . ': ' . $option_value?></p>
                <?php echo '</div>'; ?>
                <?php
                break;

            case 'hidden':
                ?><input type="hidden" id="<?php echo $id ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>"><?php
                break;

            case 'file':
                $description = (isset($description) && $description != '') ? $description : '';
                $placeholder = (isset($placeholder)) ? $placeholder : 'Upload';
                echo Temp::upload_file($field);
                ?>
                <?php echo $col; ?>
                <div class="form-group">
                    <label><?php echo $label; ?></label>
                    <p class="help-description"><?php echo $description; ?></p>

                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#<?php echo $id; ?>"><i class="fa fa-picture-o"></i>&nbsp; <?php echo $placeholder; ?></a>
                    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
                    <br>
                    <div class="upload-file-preview">

                        <?php if (isset($value) && $value != ''): ?>
                            <?php echo Temp::get_media_html($value); ?>
                        <?php endif; ?>
                    </div>
                </div>
                </div>
                <?php
                break;

            case 'clearfix':
                echo '<div class="clearfix"></div>';
                break;

        endswitch;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    function get_submit_button() {
        $label = (isset($this->form['submit'])) ? $this->form['submit'] : 'Submit';
        ob_start();
        ?>
        <div class="col-sm-12 col-md-12"> 
            <button type="submit" class="btn btn-primary"><?php echo $label; ?></button>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}
