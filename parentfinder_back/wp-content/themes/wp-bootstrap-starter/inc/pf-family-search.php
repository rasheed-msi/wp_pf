<?php

function loadFamilyNav() {

    $noOfKids = pfKids();
    $childPref = pfGetChildPreference();
    ?>
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse"> <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse js-navbar-collapse">
            <?php
            if (!empty($noOfKids)):
                ?>
                <ul id="kids" class="nav navbar-nav">
                    <li class="dropdown mega-dropdown">
                        <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown"><?php _e('Kids In Family', ''); ?><span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                        <ul class="dropdown-menu mega-dropdown-menu row">
                            <li class="col-sm-12">
                                <ul>
                                    <?php
                                    foreach ($noOfKids as $key => $kidCount):
                                        ?>
                                        <li><a href="#" data-val="Familysize" data-option="<?php echo $kidCount->kids_id; ?>"><?php echo $kidCount->description; ?></a></li>
                                        <?php
                                    endforeach;
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
            <?php if (!empty($childPref)): ?>
                <ul id="child-pref" class="nav navbar-nav">
                    <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown"><?php _e('Child Preference', ''); ?><span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                        <ul class="dropdown-menu mega-dropdown-menu row">
                            <?php
                            $prefCount = count($childPref);
                            $prefPerCol = ($prefCount > 4 ) ? ceil($prefCount / 4) : 1;
                            $prefI = 0;
                            ?>
                            <li class="col-sm-3">
                                <ul>
                                    <?php
                                    foreach ($childPref as $key => $pref):
                                        if ($prefI == $prefPerCol):
                                            $prefI = 0;
                                            ?>
                                        </ul>
                                    </li>
                                    <li class="col-sm-3">
                                        <ul>
                                        <?php endif; ?>
                                        <li><a href="#" data-option="<?php echo $pref->ethnicity; ?>" data-val="ethnicity"><?php echo $pref->ethnicity; ?></a></li>
                                        <?php
                                        $prefI = $prefI + 1;
                                    endforeach;
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
            <ul id="religion" class="nav navbar-nav">
                <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown"><?php _e('Religion', ''); ?><span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul class="dropdown-menu mega-dropdown-menu row">
                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-option="Anglican" data-val="Religion">Anglican</a></li>
                                <li><a href="#" data-option="Bahai" data-val="Religion">Bahai</a></li>
                                <li><a href="#" data-option="Baptist" data-val="Religion">Baptist</a></li>
                                <li><a href="#" data-option="Buddhist" data-val="Religion">Buddhist</a></li>
                                <li><a href="#" data-option="Catholic" data-val="Religion">Catholic</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-option="Christian" data-val="Religion">Christian</a></li>
                                <li><a href="#" data-option="Church of Christ" data-val="Religion">Church of Christ</a></li>
                                <li><a href="#" data-option="Episcopal" data-val="Religion">Episcopal</a></li>
                                <li><a href="#" data-option="Hindu" data-val="Religion">Hindu</a></li>
                                <li><a href="#" data-option="Jewish" data-val="Religion">Jewish</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-option="Lutheran" data-val="Religion">Lutheran</a></li>
                                <li><a href="#" data-option="Methodist" data-val="Religion">Methodist</a></li>
                                <li><a href="#" data-option="Non-denominational" data-val="Religion">Non-denominational</a></li>
                                <li><a href="#" data-option="None" data-val="Religion">None</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-option="Other" data-val="Religion">Other</a></li>
                                <li><a href="#" data-option="Presbyterian" data-val="Religion">Presbyterian</a></li>
                                <li><a href="#" data-option="Protestant" data-val="Religion">Protestant</a></li>
                                <li><a href="#" data-option="Unitarian" data-val="Religion">Unitarian</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul id="region" class="nav navbar-nav">
                <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown"><?php _e('Region', ''); ?><span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul class="dropdown-menu mega-dropdown-menu row">

                        <li class="col-sm-6">
                            <ul>
                                <li><a href="#" data-option="Non US" data-val="Region">Non US</a></li>
                                <li><a href="#" data-option="North-central" data-val="Region">North-central</a></li>
                                <li><a href="#" data-option="Northeast" data-val="Region">Northeast</a></li>
                                <li><a href="#" data-option="Northwest" data-val="Region">Northwest</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-6">
                            <ul>
                                <li><a href="#" data-option="South-central" data-val="Region">South-central</a></li>
                                <li><a href="#" data-option="Southeast" data-val="Region">Southeast</a></li>
                                <li><a href="#" data-option="Southwest" data-val="Region">Southwest</a></li>
                                <li><a href="#" data-option="West" data-val="Region">West</a></li>
                                <li><a href="#" data-option="Netherlands" data-val="Region">Netherlands</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul id="country_state_menu" class="nav navbar-nav">
                <li class="dropdown mega-dropdown"> <a id="dLabel" role="button"  data-target="#" href="/page.html" class="parent-item dropdown-toggle" data-toggle="dropdown"><?php _e('Country/State', ''); ?><span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul id="myTabs" class="dropdown-menu mega-dropdown-menu row">
                        <li class="col-sm-12">
                            <div class="col-xs-3">
                                <!-- required for floating -->
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs tabs-left">
                                    <li class="active"><a href="#home" data-toggle="tab">All (67)</a></li>
                                    <li><a href="#profile" data-toggle="tab">US (66)</a></li>
                                </ul>
                            </div>
                            <div class="col-xs-9">
                                <div class="tab-content">
                                    <div class="tab-pane" id="profile">
                                        <ul class="State list-group row">
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Alabama" type="State"><a href="#" data-val="State" data-country="US" data-option="Alabama">Alabama(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Alaska" type="State"><a href="#" data-val="State" data-country="US" data-option="Alaska">Alaska(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Arizona" type="State"><a href="#" data-val="State" data-country="US" data-option="Arizona">Arizona(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Arkansas" type="State"><a href="#" data-val="State" data-country="US" data-option="Arkansas">Arkansas(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="California" type="State"><a href="#" data-val="State" data-country="US" data-option="California">California(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Colorado" type="State"><a href="#" data-val="State" data-country="US" data-option="Colorado">Colorado(1)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Connecticut" type="State"><a href="#" data-val="State" data-country="US" data-option="Connecticut">Connecticut(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Delaware" type="State"><a href="#" data-val="State" data-country="US" data-option="Delaware">Delaware(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Florida" type="State"><a href="#" data-val="State" data-country="US" data-option="Florida">Florida(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Georgia" type="State"><a href="#" data-val="State" data-country="US" data-option="Georgia">Georgia(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Hawaii" type="State"><a href="#" data-val="State" data-country="US" data-option="Hawaii">Hawaii(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Idaho" type="State"><a href="#" data-val="State" data-country="US" data-option="Idaho">Idaho(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Illinois" type="State"><a href="#" data-val="State" data-country="US" data-option="Illinois">Illinois(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Indiana" type="State"><a href="#" data-val="State" data-country="US" data-option="Indiana">Indiana(1)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Iowa" type="State"><a href="#" data-val="State" data-country="US" data-option="Iowa">Iowa(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Kansas" type="State"><a href="#" data-val="State" data-country="US" data-option="Kansas">Kansas(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Kentucky" type="State"><a href="#" data-val="State" data-country="US" data-option="Kentucky">Kentucky(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Louisiana" type="State"><a href="#" data-val="State" data-country="US" data-option="Louisiana">Louisiana(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Maine" type="State"><a href="#" data-val="State" data-country="US" data-option="Maine">Maine(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Maryland" type="State"><a href="#" data-val="State" data-country="US" data-option="Maryland">Maryland(1)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Massachusetts" type="State"><a href="#" data-val="State" data-country="US" data-option="Massachusetts">Massachusetts(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Michigan" type="State"><a href="#" data-val="State" data-country="US" data-option="Michigan">Michigan(59)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Minnesota" type="State"><a href="#" data-val="State" data-country="US" data-option="Minnesota">Minnesota(1)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Mississippi" type="State"><a href="#" data-val="State" data-country="US" data-option="Mississippi">Mississippi(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Missouri" type="State"><a href="#" data-val="State" data-country="US" data-option="Missouri">Missouri(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Montana" type="State"><a href="#" data-val="State" data-country="US" data-option="Montana">Montana(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Nebraska" type="State"><a href="#" data-val="State" data-country="US" data-option="Nebraska">Nebraska(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Nevada" type="State"><a href="#" data-val="State" data-country="US" data-option="Nevada">Nevada(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="New Hampshire" type="State"><a href="#" data-val="State" data-country="US" data-option="New Hampshire">New Hampshire(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="New Jersey" type="State"><a href="#" data-val="State" data-country="US" data-option="New Jersey">New Jersey(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="New Mexico" type="State"><a href="#" data-val="State" data-country="US" data-option="New Mexico">New Mexico(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="New York" type="State"><a href="#" data-val="State" data-country="US" data-option="New York">New York(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="North Carolina" type="State"><a href="#" data-val="State" data-country="US" data-option="North Carolina">North Carolina(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="North Dakota" type="State"><a href="#" data-val="State" data-country="US" data-option="North Dakota">North Dakota(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Not specified" type="State"><a href="#" data-val="State" data-country="US" data-option="Not specified">Not specified(1)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Ohio" type="State"><a href="#" data-val="State" data-country="US" data-option="Ohio">Ohio(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Oklahoma" type="State"><a href="#" data-val="State" data-country="US" data-option="Oklahoma">Oklahoma(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Oregon" type="State"><a href="#" data-val="State" data-country="US" data-option="Oregon">Oregon(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Pennsylvania" type="State"><a href="#" data-val="State" data-country="US" data-option="Pennsylvania">Pennsylvania(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Rhode Island" type="State"><a href="#" data-val="State" data-country="US" data-option="Rhode Island">Rhode Island(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="South Carolina" type="State"><a href="#" data-val="State" data-country="US" data-option="South Carolina">South Carolina(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="South Dakota" type="State"><a href="#" data-val="State" data-country="US" data-option="South Dakota">South Dakota(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Tennessee" type="State"><a href="#" data-val="State" data-country="US" data-option="Tennessee">Tennessee(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Texas" type="State"><a href="#" data-val="State" data-country="US" data-option="Texas">Texas(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Utah" type="State"><a href="#" data-val="State" data-country="US" data-option="Utah">Utah(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Vermont" type="State"><a href="#" data-val="State" data-country="US" data-option="Vermont">Vermont(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Virginia" type="State"><a href="#" data-val="State" data-country="US" data-option="Virginia">Virginia(2)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Washington" type="State"><a href="#" data-val="State" data-country="US" data-option="Washington">Washington(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="West Virginia" type="State"><a href="#" data-val="State" data-country="US" data-option="West Virginia">West Virginia(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Wisconsin" type="State"><a href="#" data-val="State" data-country="US" data-option="Wisconsin">Wisconsin(0)</a></li>
                                            <li class="list-group-item col-xs-6 col-sm-4 col-md-3" id="Wyoming" type="State"><a href="#" data-val="State" data-country="US" data-option="Wyoming">Wyoming(0)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul id="sort-by" class="nav navbar-nav">
                <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown"><?php _e('Sort By', ''); ?><span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul class="dropdown-menu mega-dropdown-menu row">
                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-val="Sortby" data-option="newFirst"><?php _e('Newest First', ''); ?></a></li>
                                <li><a href="#" data-val="Sortby" data-option="oldFirst"><?php _e('Oldest First', ''); ?></a></li>
                                <li><a href="#" data-val="Sortby" data-option="FirstName"><?php _e('First Name', ''); ?></a></li>
                                <li><a href="#" data-val="Sortby" data-option="random"><?php _e('Random', ''); ?></a></li>
                            </ul>
                        </li>
                    </ul></li>
            </ul>
            <ul id="pf-search" class="nav navbar-nav">
                <li class="dropdown mega-dropdown">
                    <form class="navbar-form navbar-left pull-right">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="<?php _e('Search', ''); ?>">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>

        <!-- /.nav-collapse -->
    </nav>
    <?php
}

function loadFamiles() {
    global $wpdb;

    $agencyId = 163;


    $preQry = "SELECT 
                    p.pf_profile_id,p.first_name, p.last_name, p.dob,p.waiting_id,p.ethnicity_id,
                    pc.pf_profile_id couple_pf_profile_id,pc.first_name couple_first_name, pc.last_name couple_last_name, 
                    pc.dob couple_dob,pc.waiting_id couple_waiting_id,pc.ethnicity_id couple_ethnicity_id,
                    u.ID,u.user_login,u.display_name,c.State
                FROM `pf_profiles` p
                INNER JOIN pf_contact_details c on c.pf_profile_id=p.pf_profile_id
                LEFT JOIN `pf_profiles` pc on pc.pf_profile_id=p.couple_id
                INNER JOIN wp_usermeta um on um.user_id=p.wp_users_id
                INNER JOIN wp_users u on u.ID=um.user_id
                WHERE   p.pf_agency_id = %d AND  um.meta_key = '%s' 
	    	AND um.meta_value = '%s'";

    $preVal = array('wp_capabilities', 'a:1:{s:15:"adoptive_family";b:1;}', $agencyId);

    $filtFam = $wpdb->get_results($wpdb->prepare($preQry, $preVal), OBJECT);
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
                                <li class="list-group-item"><strong>Children</strong><span class="pull-right"><?php //echo $value->dob;                  ?></span></li>
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

//menu Items
//function to get kids
function pfKids() {
    global $wpdb;
    return $wpdb->get_results('SELECT kids_id, description FROM pf_kids_in_family ORDER BY sort_order ASC');
}

//function to get childpreference
function pfGetChildPreference() {
    global $wpdb;
    return $wpdb->get_results('SELECT * FROM pf_ethnicity');
}

//function to get religion
function pfGetReligionByAgencyId() {
    
}

//function to get region
function pfGetRegionByAgencyId() {
    
}

//function to get country/state
function pfGetCountryStateByAgencyId() {
    
}
