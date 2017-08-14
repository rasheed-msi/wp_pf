<?php
/*

  Template Name: Family Search

 */

get_header();
wp_enqueue_style('bootstrap');
wp_enqueue_style('wp-pf-family-search');
wp_enqueue_script('bootstrap');
wp_enqueue_script('wp-pf-family-search');
?>
<div id="main" class="main">
<div class="container">
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse"> <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>

            </button>

        </div>
        <div class="collapse navbar-collapse js-navbar-collapse">
            <ul id="kids" class="nav navbar-nav">
                <li class="dropdown mega-dropdown">
                    <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown">Kids In Family<span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul class="dropdown-menu mega-dropdown-menu row">
                        <li class="col-sm-12">
                            <ul>
                                <li><a href="#" data-val="Familysize" data-option="0">No Children</a></li>
                                <li><a href="#" data-val="Familysize" data-option="1">1 Child</a></li>
                                <li><a href="#" data-val="Familysize" data-option="2">2 Child</a></li>
                                <li><a href="#" data-val="Familysize" data-option="3">3 Child</a></li>
                                <li><a href="#" data-val="Familysize" data-option="4">4 Child</a></li>
                                <li><a href="#" data-val="Familysize" data-option="5">5 Child</a></li>
                                <li><a href="#" data-val="Familysize" data-option="6">6 Child</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul id="child-pref" class="nav navbar-nav">
                <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown">Child Preference <span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul class="dropdown-menu mega-dropdown-menu row">
                        <li class="col-sm-3">
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
                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-option="Caucasian/Asian" data-val="ethnicity">Caucasian/Asian</a></li>
                                <li><a href="#" data-option="Caucasian/African American" data-val="ethnicity">Caucasian/African American</a></li>
                                <li><a href="#" data-option="Caucasian/Hispanic" data-val="ethnicity">Caucasian/Hispanic</a></li>
                                <li><a href="#" data-option="European" data-val="ethnicity">European</a></li>
                                <li><a href="#" data-option="Caucasian/Native American" data-val="ethnicity">Caucasian/Native American</a></li>
                                <li><a href="#" data-option="Eastern European/Slavic/Russian" data-val="ethnicity">Eastern European/Slavic/Russian</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-option="Hispanic" data-val="ethnicity">Hispanic</a></li>
                                <li><a href="#" data-option="Hispanic/African American" data-val="ethnicity">Hispanic/African American</a></li>
                                <li><a href="#" data-option="Hispanic or South/Central American" data-val="ethnicity">Hispanic or South/Central American</a></li>
                                <li><a href="#" data-option="Jewish" data-val="ethnicity">Jewish</a></li>
                            </ul>
                        </li>
                        <li class="col-sm-3">
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
            </ul>
            <ul id="religion" class="nav navbar-nav">
                <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown">Religion<span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
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
                <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown">Region<span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
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
                <li class="dropdown mega-dropdown"> <a id="dLabel" role="button"  data-target="#" href="/page.html" class="parent-item dropdown-toggle" data-toggle="dropdown">Country/State<span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul id="myTabs" class="dropdown-menu mega-dropdown-menu row">
                        <li class="col-sm-12">

<!--                            <h3>Country/State</h3>
                            <hr/>-->
                            <div class="col-xs-3">
                                <!-- required for floating -->
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs tabs-left">
                                    <li class="active"><a href="#home" data-toggle="tab">All (67)</a></li>
                                    <li><a href="#profile" data-toggle="tab">US (66)</a></li>
                                    <!--                                    <li><a href="#messages" data-toggle="tab">Messages</a></li>
                                                                        <li><a href="#settings" data-toggle="tab">Settings</a></li>-->
                                </ul>
                            </div>
                            <div class="col-xs-9">
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <!--                                    <div class="tab-pane active" id="home">Home Tab.</div>-->
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
                                    <!--                                    <div class="tab-pane" id="messages">Messages Tab.</div>
                                                                        <div class="tab-pane" id="settings">Settings Tab.</div>-->
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul id="sort-by" class="nav navbar-nav">
                <li class="dropdown mega-dropdown"> <a href="#" class="parent-item dropdown-toggle" data-toggle="dropdown">Sort By<span class="glyphicon glyphicon-chevron-down pull-right"></span></a>
                    <ul class="dropdown-menu mega-dropdown-menu row">

                        <li class="col-sm-3">
                            <ul>
                                <li><a href="#" data-val="Sortby" data-option="newFirst">Newest First</a></li>
                                <li><a href="#" data-val="Sortby" data-option="oldFirst">Oldest First</a></li>
                                <li><a href="#" data-val="Sortby" data-option="FirstName">First Name</a></li>
                                <li><a href="#" data-val="Sortby" data-option="random">Random</a></li>
                            </ul>
                        </li>
                    </ul></li>
            </ul>
            <ul id="pf-search" class="nav navbar-nav">
                <li class="dropdown mega-dropdown">
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
                </li>
            </ul>
        </div>

        <!-- /.nav-collapse -->
    </nav>
</div>
<div class="container">
    <?php loadFamiles();?>
</div>
</div>

<?php
get_footer();
?>