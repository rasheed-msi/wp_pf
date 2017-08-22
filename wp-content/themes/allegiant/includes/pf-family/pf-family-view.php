<?php

function loadFamiles() {
    global $wpdb;
    $preQry = "SELECT 
    p.pf_profile_id,p.first_name, p.last_name, p.dob,p.waiting_id,p.ethnicity_id,
    pc.pf_profile_id couple_pf_profile_id,pc.first_name couple_first_name, pc.last_name couple_last_name, 
    pc.dob couple_dob,pc.waiting_id couple_waiting_id,pc.ethnicity_id couple_ethnicity_id,
    u.ID,u.user_login,u.display_name,c.State
    FROM `pf_profiles` p
    inner join pf_contact_details c on c.pf_profile_id=p.pf_profile_id
    left join `pf_profiles` pc on pc.pf_profile_id=p.couple_id
    inner join wp_usermeta um on um.user_id=p.wp_users_id
    inner join wp_users u on u.ID=um.user_id
    where   p.pf_agency_id = %d and  um.meta_key = '%s' 
    and um.meta_value = '%s'";

    $preVal = array('wp_capabilities', 'a:1:{s:15:"adoptive_family";b:1;}', 163);

    $filtFam = $wpdb->get_results($wpdb->prepare($preQry, $preVal), OBJECT);
//    echo "<pre>";print_r($filtFam);exit;
    ?>
    <div class="row list-group"><?php
        if (count($filtFam) > 0) :
            foreach ($filtFam as $key => $value):
//                if (($key + 1) % 4 == 0):
                ?>
                <div class="item  col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="thumbnail">
                        <img class="group list-group-image" src="http://placehold.it/400x250/000/fff?text=No++Image" alt="" />
                        <div class="caption">
                            <h4 class="group inner list-group-item-heading"><span class="label label-info"><?php echo $value->first_name . " and " . $value->last_name; ?></span></h4>
            <!--                                    <p class="group inner list-group-item-text">
                                Product description... Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                                sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>-->
                            <ul class="list-group">
                                <!--<li class="list-group-item"><i class="glyphicon glyphicon-map-marker"></i>Cras justo odio</li>-->
                                <li class="list-group-item"><strong>Age</strong><span class="pull-right"><?php echo ageCalculator($value->dob); ?></span></li>
                                <li class="list-group-item"><strong>State</strong><span class="pull-right"><?php echo getStateById($value->State); ?></span></li>
                                <li class="list-group-item"><strong>Waiting</strong><span class="pull-right"><?php echo $value->waiting_id; ?></span></li>
                                <li class="list-group-item"><strong>Children</strong><span class="pull-right"><?php //echo $value->dob;   ?></span></li>
                                <li class="list-group-item"><strong>Faith</strong><span class="pull-right"><?php echo getEthinicityById($value->ethnicity_id); ?></span></li>
                            </ul>
                            <!--                            <div class="row">
                                                            <div class="col-xs-12 col-md-6">
                                                                <p class="lead">
                                                                    $21.000</p>
                                                            </div>
                                                            <div class="col-xs-12 col-md-6">
                                                                <a class="btn btn-success" href="http://www.jquery2dotnet.com">Add to cart</a>
                                                            </div>
                                                        </div>-->
                        </div>
                    </div>
                </div>

                <?php
            endforeach;
        endif;
        ?>
    </div>
    <?php
}

/**
 * Get State by Id
 * @global type $wpdb
 * @param type $stateId
 * @return type
 */
function getStateById($stateId) {
    global $wpdb;
    $stateQry = 'SELECT State FROM pf_states WHERE `state_id`=%s';
    return $wpdb->get_var($wpdb->prepare($stateQry, $stateId));
}

/**
 * Get Ethinicity/Faith by Id
 * @global type $wpdb
 * @param type $ethnctyId
 * @return type
 */
function getEthinicityById($ethnctyId) {
    global $wpdb;
    $stateQry = 'SELECT ethnicity FROM pf_ethnicity WHERE `ethnicity_id`=%s';
    return $wpdb->get_var($wpdb->prepare($stateQry, $ethnctyId));
}

/**
 * Calculate Age from DOB (y-m-d)
 * @param type $dob
 * @return int
 */
function ageCalculator($dob) {
    if (!empty($dob)) {
        $birthdate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    } else {
        return 0;
    }
}

add_action('wp_enqueue_scripts', 'wp_pf_scripts');

/**
 * pf family enqueue script
 */
function wp_pf_scripts() {
    wp_register_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), 3, FALSE);
    wp_register_style('wp-pf-family-search', get_template_directory_uri() . '/core/css/pf-family.css', array('bootstrap'), 5.8);
    wp_register_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), 3, FALSE);
    wp_register_script('wp-pf-family-search', get_template_directory_uri() . '/core/scripts/pf-family.js', array('jquery', 'bootstrap'), '20151215', true);
}
