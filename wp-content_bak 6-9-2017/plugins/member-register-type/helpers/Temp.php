<?php

class Temp {

    public static function agency_status_update_button($agency_id, $agency_status) {
        
        $new_agency_status = self::get_new_agency_status($agency_status);
        
        $format_param = [
            'agency_id' => $agency_id, 
            'status_id' => $new_agency_status['new_status_id']
        ];


        $url = MrtApiController::format('set_agency_status_approve', $format_param);
        $html = '';
        ob_start();
        ?>
        <a class="button button-primary status_change" href="<?php echo $url; ?>"><?php echo $new_agency_status['new_label']; ?></a>
        <?php
        $html = ob_get_clean();
        return $html;
    }

    public static function get_new_agency_status($agency_status) {
        $return = [];
        if ($agency_status == 1) {
            $return['new_label'] = 'Suspend';
            $return['new_status_id']  = 3;
        } elseif ($agency_status == 2) {
            $return['new_label'] = 'Approve';
            $return['new_status_id'] = 1;
        } elseif ($agency_status == 3) {
            $return['new_label'] = 'Approve';
            $return['new_status_id'] = 1;
        } else {
            $return['new_label'] = 'Approve';
            $return['new_status_id'] = 1;
        }
        
        return $return;
    }

}
