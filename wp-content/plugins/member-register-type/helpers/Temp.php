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
            $return['new_status_id'] = 3;
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

    public static function vitals($profile, $info, $preferences) {
        
        $html = '';
        ob_start();
        ?>
        <div class="articles">
            <div class="row articleRow">
                <div class="col-lg-5 col-md-5 articleColumn">
                    <div class="articleItem">
                        <div class="articleItemHead pink">Our adoption preferences</div>
                        <div class="articleItemContents">
                            <div class="articleItemWidget">
                                <h6 class="text-capitalize">Ethnicity:</h6>
                                <p><?php echo State::get_array_comma_separated($preferences['ethnicity'], 'ethnicity'); ?></p>
                            </div>
                            <div class="articleItemWidget">
                                <h6 class="text-capitalize">Age:</h6>
                                <p><?php echo State::get_array_comma_separated($preferences['age'], 'Age_group'); ?></p>
                            </div>
                            <div class="articleItemWidget">
                                <h6 class="text-capitalize">Adoption type:</h6>
                                <p><?php echo State::get_array_comma_separated($preferences['type'], 'adoption_type'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 articleColumn">
                    <div class="articleItem flexbox">

                        <div class="articleItemColumn flexFullChild">
                            <div class="articleItemHead blue"> <?php echo $profile['first_name']; ?></div>
                            <div class="articleItemContents">
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Gender:</h6>
                                    <p><?php echo $info['gender']; ?></p>
                                </div>
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Ethnicity:</h6>
                                    <p><?php echo $info['ethnicity']; ?></p>
                                </div>
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Education:</h6>
                                    <p><?php echo $info['education']; ?></p>
                                </div>
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Religion:</h6>
                                    <p><?php echo $info['religion']; ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }

}
