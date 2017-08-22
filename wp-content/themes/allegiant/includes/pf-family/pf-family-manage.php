<?php

function manageFamilies() {
    global $wpdb;

    $familySearch = trim(get_query_var('family-search'));
    $familyStatus = get_query_var('family-status');
    $pageNo = max(1, absint(get_query_var('family-page')));
    //echo $familySearch . "<br>" . $familyStatus . "<br>" . $pageNo;

    $perPage = 5;
    $agencyId = 163;
    $agencyFams = getFamiliesByAgencyId($agencyId, $pageNo, $perPage, $familySearch, $familyStatus, true);
    $statusList = getFamStatus();
    ?>

    <?php
    familySearch();
    ?>
    <hr />
    <?php
    if (count($agencyFams['data']) > 0) {
        ?>
        <div class="row list-group">
            <!--Families Start-->
            <?php
            foreach ($agencyFams['data'] as $key => $value) {
                ?>
                <!--Family Start-->
                <div class="item  col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="thumbnail">
                        <img class="group list-group-image" src="http://placehold.it/400x250/000/fff?text=No++Image" alt="" />
                        <div class="caption">
                            <h4 class="group inner list-group-item-heading"><span class="label label-info"><?php echo $value->first_name . " and " . $value->last_name; ?></span></h4>
                            <ul class="list-group">
                                <?php
                                foreach ($statusList as $status) {
                                    ?>
                                    <li class="list-group-item">
                                    <label><!--for="<?php echo $agencyId . $value->pf_profile_id . $status->status_id; ?>"-->
                                            <input name="user_status<?php echo $value->pf_profile_id; ?>" type="radio" value="<?php echo $agencyId . $value->pf_profile_id . $status->status_id; ?>" <?php echo ($value->Status_id == $status->status_id) ? 'checked="true"' : ''; ?> onclick="saveUserStatus(<?php echo $status->status_id; ?>,<?php echo $value->pf_profile_id; ?>, <?php echo $agencyId; ?>);" />
                                            <?php echo $status->status_text; ?>
                                        </label>
                                    <?php }
                                    ?>


                            </ul>
                        </div>
                    </div>
                </div>
                <!--Family End-->
                <?php
            }
            ?>
        </div>
        <!--Families End-->
        <div class="row">
            <?php
            $pages = paginate_links(array(
                'base' => add_query_arg('family-page', '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'total' => ceil($agencyFams['count'] / $perPage),
                'current' => $pageNo,
                'type' => 'array',
            ));

            if (is_array($pages)) {
                $output = '';
                $output .= '<div class="text-center"><ul class="pagination">';
                foreach ($pages as $key => $page) {
//                    if($pageNo != 1)
                    $class = (($key) == $pageNo) && ($pageNo != 1) ? ' class="active"' : "";
                    $output .= "<li $class>$page</li>";
                }
                $output .= '</ul></div>';
                echo $output;
            }
        } else {
            ?>
            <div class="text-center"><p>No Families Found</p></div>
            <!--No Families-->
            <?php
        }
        ?>

    </div>

    <?php
}

function familySearch() {
    ?>
    <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
            <form method="post" action="<?php echo get_permalink(); ?>">
                <div class="input-group">
                    <div class="input-group-btn search-panel">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span id="pf-family-status-search">Filter by</span> <span class="caret"></span>
                        </button>
                        <ul id="status-dropdown" class="dropdown-menu" role="menu">
                            <!--<li><a href="<?php echo get_permalink(); ?>?family-status=">Private Families</a></li>-->
                            <li data-status="1"><a href="<?php echo get_permalink(); ?>?family-status=1">Active</a></li>
                            <li data-status="3"><a href="<?php echo get_permalink(); ?>?family-status=3">Inactive</a></li>
                            <li data-status="2"><a href="<?php echo get_permalink(); ?>?family-status=2">Pending Approval</a></li>
                            <li data-status="4"><a href="<?php echo get_permalink(); ?>?family-status=4">Matched</a></li>
                            <li data-status="5"><a href="<?php echo get_permalink(); ?>?family-status=5">Placed</a></li>
                            <!--<li><a href="<?php echo get_permalink(); ?>?family-status=">Recently Joined</a></li>-->
                        </ul>
                    </div>
                    <input type="hidden" name="search_param" value="all" id="search_param">         
                    <input type="text" class="form-control" id="family-search" name="family-search" value="<?php echo get_query_var('family-search'); ?>" placeholder="Search term...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <?php
}

/**
 * 
 * @param type $agnecyId
 */
function getFamiliesByAgencyId($agnecyId, $pageNo = false, $perPage = false, $familySearch = false, $familyStatus = false, $count = false) {

    global $wpdb;

    $qryForFam = "FROM `pf_profiles` p
            inner join pf_contact_details c on c.pf_profile_id=p.pf_profile_id
            inner join wp_usermeta um on um.user_id=p.wp_users_id            
            where um.meta_key = '%s' and um.meta_value = '%s' and p.pf_agency_id = %d";
    $paramForFam = array('wp_capabilities', 'a:1:{s:15:"adoptive_family";b:1;}', $agnecyId);
    
    if(!empty($familySearch)){
        //$qryForFam .= " and (p.first_name like '%%%s%%' or p.last_name like '%%%s%%')";
        $qryForFam .= " and (concat(p.first_name,p.last_name) like '%%%s%%')";
        array_push($paramForFam, $familySearch);
        //$paramForFam = array_merge($paramForFam, array($familySearch, $familySearch));
        $pageNo = false;$perPage = false;
    }
    
    if(!empty($familyStatus)){
        $qryForFam .= " and p.Status_id = %d";
        array_push($paramForFam, $familyStatus);
    }

    $fams = array();
    if ($count == true) {
        $famTotal = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) " . $qryForFam, $paramForFam));
        $fams['count'] = $famTotal;
    }

    $qryForFam = "SELECT p.*, c.* " . $qryForFam;

    
    //echo $qryForFam;exit;
    if ($pageNo != false && $perPage != false) {
        $offset = ($pageNo - 1) * $perPage;
        $qryForFam .= " LIMIT %d, %d";
        $paramForFam = array_merge($paramForFam, array($offset, $perPage));
    }

    $fams['data'] = $wpdb->get_results($wpdb->prepare($qryForFam, $paramForFam), OBJECT);

    //echo $wpdb->last_query;exit;

    return $fams;
}

function getFamStatus() {
    global $wpdb;
    return $wpdb->get_results('SELECT * FROM pf_user_status', OBJECT);
}

function add_query_vars_filter($vars) {
    $vars[] = "family-page";
    $vars[] = "family-search";
    $vars[] = "family-status";

    return $vars;
}

add_filter('query_vars', 'add_query_vars_filter');


add_action('wp_enqueue_scripts', 'pf_manage_family_enqueue');

function pf_manage_family_enqueue() {
    wp_register_script('pf-manage-famaily', get_template_directory_uri() . '/core/scripts/pf-manage-famaily.js', array('jquery'), 5.8);
    wp_localize_script('pf-manage-famaily', 'pfFamilyObj', array('ajaxUrl' => admin_url('admin-ajax.php'),'pageUrl' => get_permalink(get_page_by_title('Manage Families'))));
}

add_action('wp_ajax_statusUpdate', 'statusUpdateFunc');
add_action('wp_ajax_nopriv_statusUpdate', 'statusUpdateFunc');

function statusUpdateFunc() {

    global $wpdb;
    $status = $wpdb->update('pf_profiles', array('Status_id' => $_REQUEST['status']), array('pf_profile_id' => $_REQUEST['profile_id'], 'pf_agency_id' => $_REQUEST['agency_id']), array('%d'), array('%d', '%d'));
    if ($status != false)
        $data = array('status' => 'success');
    else
        $data = array('status' => 'failed');

    echo json_encode($data);
    die();
}
