<!-- <div class="grayIcons icoBuilder">FAMILY PROFILE BUILDER</div> -->
<section id="profilebuilder" ng-app="ui.bootstrap.demo" class="container">
    <div ng-controller="TabsCtrl" class="dashboardTabs flexbox">
        <div class="row">
            <div class="unsaved" id="unsavedData" ng-if="show">
                <div  style="width:35px;margin-left: -20px;"><img src="<?php echo get_template_directory_uri() . '/includes/pf-edit-profile/assets/images/exclamation_mark_circle_danger.png'; ?>" alt="exclamation" height="25" width="25"></div>
                <div style="margin-top: -29px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>YOU HAVE UNSAVED DATA. PLEASE CLICK ON</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<b>SAVE OR CANCEL BUTTON TO MOVE FORWARD.</b></div>
                <div class="clear_both"></div>
            </div>
            <div  id="savedData"  class="unsaved" ng-if="saved"><img src="<?php echo get_template_directory_uri() . '/includes/pf-edit-profile/assets/images/thumbs-up-clipart.png'; ?>" alt="exclamation" height="25" width="25"></div>
            <input type="hidden" value="<?php echo $_SESSION['logged_user_access_token']; ?>" id="access_token" ng-model="access_token" ng-init="access_token = '<?php echo $_SESSION['logged_user_access_token']; ?>'" />
            <input type="hidden" value="<?php home_url(); ?>" id="site_url" ng-model="site_url" ng-init="site_url = '<?php home_url(); ?>'" />
            <input type="hidden" value="<?php echo get_stylesheet_directory_uri(); ?>/includes/pf-edit-profile/" id="template_root_path" ng-model="template_root_path" ng-init="template_root_path = '<?php echo get_stylesheet_directory_uri(); ?>/includes/pf-edit-profile/'" />
        </div>
        <uib-tabset active="active" vertical="true">
            <uib-tab ng-repeat="tab in model.tabs" index="$index" heading="{{tab.title}}" deselect="onTabdeselect($selectedIndex, $event)">
                <div ng-if="tab.isLoaded" ng-include="tab.templateUrl"></div>
            </uib-tab>
        </uib-tabset>
    </div>
</section>