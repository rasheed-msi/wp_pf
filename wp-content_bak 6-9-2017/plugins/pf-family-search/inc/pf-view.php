<?php

class PF_FSView {

    protected static $instance;

    public static function init() {
        is_null(self::$instance) AND self::$instance = new self();
        return self::$instance;
    }

    public function __construct() {
        add_shortcode('pf-family-search', array(&$this, 'pfFamilySearch'));
        add_action('wp_enqueue_scripts', array(&$this, 'pffsEnqueue'));
    }

    public function pfFamilySearch($atts) {
        wp_enqueue_script('pffs-js');
        wp_enqueue_style('pffs-css');

        $args = shortcode_atts(array(
            'heading' => '',
            'baz' => '',
                ), $atts);
        ob_start();
        ?>

        <div id="pffs" class="container">
            <div id="pf_view_id"></div>
            <h3>View Our Families<span id="reload"></span></h3>
            <nav class="navbar">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a class="parent-item dropdown-toggle" data-toggle="dropdown" href="#">Kids In Family<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" data-val="Familysize" data-option="0" >No Children</a></li>
                            <li><a href="#" data-val="Familysize" data-option="1">1 Child</a></li>
                            <li><a href="#" data-val="Familysize" data-option="2">2 Child</a></li>
                            <li><a href="#" data-val="Familysize" data-option="3">3 Child</a></li>
                            <li><a href="#" data-val="Familysize" data-option="4">4 Child</a></li>
                            <li><a href="#" data-val="Familysize" data-option="5">5 Child</a></li>
                            <li><a href="#" data-val="Familysize" data-option="6">6 Child</a></li>
                        </ul>
                    </li>
                    <!-- <li><a class="parent-item" href="#">Link</a></li> -->
                    <li class="dropdown"><a class="parent-item dropdown-toggle" data-toggle="dropdown" href="#">Child Preference <span class="caret"></span></a>
                        <ul class="dropdown-menu mega-menu">
                            <li class="mega-menu-column">
                                <ul>
                                    <li><a href="#" data-option="Middle Eastern" data-val="ethnicity">Middle Eastern</a></li>
                                    <li><a href="#" data-option="Asian" data-val="ethnicity">Asian</a></li>
                                    <li><a href="#" data-option="African American" data-val="ethnicity">African American</a></li>
                                    <li><a href="#" data-option="African American/Asian" data-val="ethnicity">African American/Asian</a></li>
                                    <li><a href="#" data-option="Asian/Hispanic" data-val="ethnicity">Asian/Hispanic</a></li>
                                    <li><a href="#" data-option="Bi-Racial" data-val="ethnicity">Bi-Racial</a></li>
                                    <li><a href="#" data-option="Caucasian" data-val="ethnicity">Caucasian</a></li>
                                </ul>
                            </li>
                            <li class="mega-menu-column">
                                <ul>
                                    <li><a href="#" data-option="Caucasian/Asian" data-val="ethnicity">Caucasian/Asian</a></li>
                                    <li><a href="#" data-option="Caucasian/African American" data-val="ethnicity">Caucasian/African American</a></li>
                                    <li><a href="#" data-option="Caucasian/Hispanic" data-val="ethnicity">Caucasian/Hispanic</a></li>
                                    <li><a href="#" data-option="European" data-val="ethnicity">European</a></li>
                                    <li><a href="#" data-option="Caucasian/Native American" data-val="ethnicity">Caucasian/Native American</a></li>
                                    <li><a href="#" data-option="Eastern European/Slavic/Russian" data-val="ethnicity">Eastern European/Slavic/Russian</a></li>
                                </ul>
                            </li>
                            <li class="mega-menu-column">
                                <ul>
                                    <li><a href="#" data-option="Hispanic" data-val="ethnicity">Hispanic</a></li>
                                    <li><a href="#" data-option="Hispanic/African American" data-val="ethnicity">Hispanic/African American</a></li>
                                    <li><a href="#" data-option="Hispanic or South/Central American" data-val="ethnicity">Hispanic or South/Central American</a></li>
                                    <li><a href="#" data-option="Jewish" data-val="ethnicity">Jewish</a></li>
                                </ul>
                            </li>
                            <li class="mega-menu-column">
                                <ul>
                                    <li><a href="#" data-option="Mediterranean" data-val="ethnicity">Mediterranean</a></li>
                                    <li><a href="#" data-option="Multi-Racial" data-val="ethnicity">Multi-Racial</a></li>
                                    <li><a href="#" data-option="Native American (American Indian)" data-val="ethnicity">Native American (American Indian)</a></li>
                                    <li><a href="#" data-option="Pacific Islander" data-val="ethnicity">Pacific Islander</a></li>
                                    <li><a href="#" data-option="Other" data-val="ethnicity">Other</a></li>
                                </ul>                                
                            </li>
                        </ul>
                    </li>
                
                <li class="dropdown">
                    <a class="parent-item dropdown-toggle" data-toggle="dropdown" href="#">Religion <span class="caret"></span></a>
                    <ul class="dropdown-menu mega-menu">
                        <li><a href="#" data-option="Anglican" data-val="Religion">Anglican</span></li>
                        <li><a href="#" data-option="Bahai" data-val="Religion">Bahai</span></li>
                        <li><a href="#" data-option="Baptist" data-val="Religion">Baptist</span></li>
                        <li><a href="#" data-option="Buddhist" data-val="Religion">Buddhist</span></li>
                        <li><a href="#" data-option="Catholic" data-val="Religion">Catholic</span></li>
                        <li><a href="#" data-option="Christian" data-val="Religion">Christian</span></li>
                        <li><a href="#" data-option="Church of Christ" data-val="Religion">Church of Christ</span></li>
                        <li><a href="#" data-option="Episcopal" data-val="Religion">Episcopal</span></li>
                        <li><a href="#" data-option="Hindu" data-val="Religion">Hindu</span></li>
                        <li><a href="#" data-option="Jewish" data-val="Religion">Jewish</span></li>
                        <li><a href="#" data-option="Lutheran" data-val="Religion">Lutheran</span></li>
                        <li><a href="#" data-option="Methodist" data-val="Religion">Methodist</span></li>
                        <li><a href="#" data-option="Non-denominational" data-val="Religion">Non-denominational</span></li>
                        <li><a href="#" data-option="None" data-val="Religion">None</span></li>
                        <li><a href="#" data-option="Other" data-val="Religion">Other</span></li>
                        <li><a href="#" data-option="Presbyterian" data-val="Religion">Presbyterian</span></li>
                        <li><a href="#" data-option="Protestant" data-val="Religion">Protestant</span></li>
                        <li><a href="#" data-option="Unitarian" data-val="Religion">Unitarian</span></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="parent-item dropdown-toggle" data-toggle="dropdown" href="#">Region <span class="caret"></span></a>
                    <ul class="dropdown-menu mega-menu">
                        <li><a href="#" data-option="Anglican" data-val="Religion">Anglican</span></li>
                        <li><a href="#" data-option="Bahai" data-val="Religion">Bahai</span></li>
                        <li><a href="#" data-option="Baptist" data-val="Religion">Baptist</span></li>
                        <li><a href="#" data-option="Buddhist" data-val="Religion">Buddhist</span></li>
                        <li><a href="#" data-option="Catholic" data-val="Religion">Catholic</span></li>
                        <li><a href="#" data-option="Christian" data-val="Religion">Christian</span></li>
                        <li><a href="#" data-option="Church of Christ" data-val="Religion">Church of Christ</span></li>
                        <li><a href="#" data-option="Episcopal" data-val="Religion">Episcopal</span></li>
                        <li><a href="#" data-option="Hindu" data-val="Religion">Hindu</span></li>
                        <li><a href="#" data-option="Jewish" data-val="Religion">Jewish</span></li>
                        <li><a href="#" data-option="Lutheran" data-val="Religion">Lutheran</span></li>
                        <li><a href="#" data-option="Methodist" data-val="Religion">Methodist</span></li>
                        <li><a href="#" data-option="Non-denominational" data-val="Religion">Non-denominational</span></li>
                        <li><a href="#" data-option="None" data-val="Religion">None</span></li>
                        <li><a href="#" data-option="Other" data-val="Religion">Other</span></li>
                        <li><a href="#" data-option="Presbyterian" data-val="Religion">Presbyterian</span></li>
                        <li><a href="#" data-option="Protestant" data-val="Religion">Protestant</span></li>
                        <li><a href="#" data-option="Unitarian" data-val="Religion">Unitarian</span></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="parent-item dropdown-toggle" data-toggle="dropdown" href="#">Country/State<span class="caret"></span></a>
                    <ul class="dropdown-menu mega-menu">
                        <li data-val="Country" id="all" type="State">All Countries(67)</li>
                        <li class="dropdown" id="US" type="Country">
                            <a href="#" data-val="Country" data-option="US" class="dropdown-toggle" data-toggle="dropdown">United States(66)<span class="caret"></span></a>
                            <ul class="State dropdown-menu mega-menu">
                                <li id="Alabama" type="State"><a href="#" data-val="State" data-country="US" data-option="Alabama">Alabama(0)</a></li>
                                <li id="Alaska" type="State"><a href="#" data-val="State" data-country="US" data-option="Alaska">Alaska(0)</a></li>
                                <li id="Arizona" type="State"><a href="#" data-val="State" data-country="US" data-option="Arizona">Arizona(0)</a></li>
                                <li id="Arkansas" type="State"><a href="#" data-val="State" data-country="US" data-option="Arkansas">Arkansas(0)</a></li>
                                <li id="California" type="State"><a href="#" data-val="State" data-country="US" data-option="California">California(0)</a></li>
                                <li id="Colorado" type="State"><a href="#" data-val="State" data-country="US" data-option="Colorado">Colorado(1)</a></li>
                                <li id="Connecticut" type="State"><a href="#" data-val="State" data-country="US" data-option="Connecticut">Connecticut(0)</a></li>
                                <li id="Delaware" type="State"><a href="#" data-val="State" data-country="US" data-option="Delaware">Delaware(0)</a></li>
                                <li id="Florida" type="State"><a href="#" data-val="State" data-country="US" data-option="Florida">Florida(0)</a></li>
                                <li id="Georgia" type="State"><a href="#" data-val="State" data-country="US" data-option="Georgia">Georgia(0)</a></li>
                                <li id="Hawaii" type="State"><a href="#" data-val="State" data-country="US" data-option="Hawaii">Hawaii(0)</a></li>
                                <li id="Idaho" type="State"><a href="#" data-val="State" data-country="US" data-option="Idaho">Idaho(0)</a></li>
                                <li id="Illinois" type="State"><a href="#" data-val="State" data-country="US" data-option="Illinois">Illinois(0)</a></li>
                                <li id="Indiana" type="State"><a href="#" data-val="State" data-country="US" data-option="Indiana">Indiana(1)</a></li>
                                <li id="Iowa" type="State"><a href="#" data-val="State" data-country="US" data-option="Iowa">Iowa(0)</a></li>
                                <li id="Kansas" type="State"><a href="#" data-val="State" data-country="US" data-option="Kansas">Kansas(0)</a></li>
                                <li id="Kentucky" type="State"><a href="#" data-val="State" data-country="US" data-option="Kentucky">Kentucky(0)</a></li>
                                <li id="Louisiana" type="State"><a href="#" data-val="State" data-country="US" data-option="Louisiana">Louisiana(0)</a></li>
                                <li id="Maine" type="State"><a href="#" data-val="State" data-country="US" data-option="Maine">Maine(0)</a></li>
                                <li id="Maryland" type="State"><a href="#" data-val="State" data-country="US" data-option="Maryland">Maryland(1)</a></li>
                                <li id="Massachusetts" type="State"><a href="#" data-val="State" data-country="US" data-option="Massachusetts">Massachusetts(0)</a></li>
                                <li id="Michigan" type="State"><a href="#" data-val="State" data-country="US" data-option="Michigan">Michigan(59)</a></li>
                                <li id="Minnesota" type="State"><a href="#" data-val="State" data-country="US" data-option="Minnesota">Minnesota(1)</a></li>
                                <li id="Mississippi" type="State"><a href="#" data-val="State" data-country="US" data-option="Mississippi">Mississippi(0)</a></li>
                                <li id="Missouri" type="State"><a href="#" data-val="State" data-country="US" data-option="Missouri">Missouri(0)</a></li>
                                <li id="Montana" type="State"><a href="#" data-val="State" data-country="US" data-option="Montana">Montana(0)</a></li>
                                <li id="Nebraska" type="State"><a href="#" data-val="State" data-country="US" data-option="Nebraska">Nebraska(0)</a></li>
                                <li id="Nevada" type="State"><a href="#" data-val="State" data-country="US" data-option="Nevada">Nevada(0)</a></li>
                                <li id="New Hampshire" type="State"><a href="#" data-val="State" data-country="US" data-option="New Hampshire">New Hampshire(0)</a></li>
                                <li id="New Jersey" type="State"><a href="#" data-val="State" data-country="US" data-option="New Jersey">New Jersey(0)</a></li>
                                <li id="New Mexico" type="State"><a href="#" data-val="State" data-country="US" data-option="New Mexico">New Mexico(0)</a></li>
                                <li id="New York" type="State"><a href="#" data-val="State" data-country="US" data-option="New York">New York(0)</a></li>
                                <li id="North Carolina" type="State"><a href="#" data-val="State" data-country="US" data-option="North Carolina">North Carolina(0)</a></li>
                                <li id="North Dakota" type="State"><a href="#" data-val="State" data-country="US" data-option="North Dakota">North Dakota(0)</a></li>
                                <li id="Not specified" type="State"><a href="#" data-val="State" data-country="US" data-option="Not specified">Not specified(1)</a></li>
                                <li id="Ohio" type="State"><a href="#" data-val="State" data-country="US" data-option="Ohio">Ohio(0)</a></li>
                                <li id="Oklahoma" type="State"><a href="#" data-val="State" data-country="US" data-option="Oklahoma">Oklahoma(0)</a></li>
                                <li id="Oregon" type="State"><a href="#" data-val="State" data-country="US" data-option="Oregon">Oregon(0)</a></li>
                                <li id="Pennsylvania" type="State"><a href="#" data-val="State" data-country="US" data-option="Pennsylvania">Pennsylvania(0)</a></li>
                                <li id="Rhode Island" type="State"><a href="#" data-val="State" data-country="US" data-option="Rhode Island">Rhode Island(0)</a></li>
                                <li id="South Carolina" type="State"><a href="#" data-val="State" data-country="US" data-option="South Carolina">South Carolina(0)</a></li>
                                <li id="South Dakota" type="State"><a href="#" data-val="State" data-country="US" data-option="South Dakota">South Dakota(0)</a></li>
                                <li id="Tennessee" type="State"><a href="#" data-val="State" data-country="US" data-option="Tennessee">Tennessee(0)</a></li>
                                <li id="Texas" type="State"><a href="#" data-val="State" data-country="US" data-option="Texas">Texas(0)</a></li>
                                <li id="Utah" type="State"><a href="#" data-val="State" data-country="US" data-option="Utah">Utah(0)</a></li>
                                <li id="Vermont" type="State"><a href="#" data-val="State" data-country="US" data-option="Vermont">Vermont(0)</a></li>
                                <li id="Virginia" type="State"><a href="#" data-val="State" data-country="US" data-option="Virginia">Virginia(2)</a></li>
                                <li id="Washington" type="State"><a href="#" data-val="State" data-country="US" data-option="Washington">Washington(0)</a></li>
                                <li id="West Virginia" type="State"><a href="#" data-val="State" data-country="US" data-option="West Virginia">West Virginia(0)</a></li>
                                <li id="Wisconsin" type="State"><a href="#" data-val="State" data-country="US" data-option="Wisconsin">Wisconsin(0)</a></li>
                                <li id="Wyoming" type="State"><a href="#" data-val="State" data-country="US" data-option="Wyoming">Wyoming(0)</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="parent-item dropdown-toggle" data-toggle="dropdown" href="#">Sort By<span class="caret"></span></a>
                    <ul class="dropdown-menu mega-menu">
                        <li><a href="#" data-val="Sortby" data-option="newFirst">Newest First</span></li>
                        <li><a href="#" data-val="Sortby" data-option="oldFirst">Oldest First</span></li>
                        <li><a href="#" data-val="Sortby" data-option="FirstName">First Name</span></li>
                        <li><a href="#" data-val="Sortby" data-option="random">Random</span></li>
                    </ul>
                </li>
                </ul>
                <form class="navbar-form navbar-left pull-right">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </nav> 
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function pffsEnqueue() {
        wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), 3, FALSE);
        wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), 3, FALSE);
        wp_enqueue_style('pffs-css', PFFS_URL . 'css/pffs.css', array('bootstrap'), false, true);
        wp_enqueue_script('pffs-js', PFFS_URL . 'js/pffs.js', array('jquery', 'bootstrap'), false, true);
    }

}

PF_FSView::init();



