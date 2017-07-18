<?php 
//echo $_SESSION['logged_user_access_token'];exit;
?>
<div id="profilebuilder" ng-app="ui.bootstrap.demo">
    <div>
        <div ng-controller="TabsCtrl">
            <div class="unsaved" id="unsavedData" ng-if="show" style="margin-right: 430px;">
                <div  style="width:35px;margin-left: -20px;"><img src="<?php echo get_template_directory_uri() . '/inc/pf-edit-profile/assets/images/exclamation_mark_circle_danger.png';?>" alt="exclamation" height="25" width="25"></div>
                <div style="margin-top: -29px;">&nbsp;&nbsp;&nbsp;&nbsp;<b>YOU HAVE UNSAVED DATA. PLEASE CLICK ON</b><br>&nbsp;&nbsp;&nbsp;&nbsp;<b>SAVE OR CANCEL BUTTON TO MOVE FORWARD.</b></div>
                <div class="clear_both"></div>
            </div>
            <div class="unsaved" ng-if="saved" style="margin-right: 720px;"><img src="<?php echo get_template_directory_uri() . '/inc/pf-edit-profile/assets/images/thumbs-up-clipart.png';?>" alt="exclamation" height="25" width="25"></div>
            <input type="hidden" value="<?php echo $_SESSION['logged_user_access_token']; ?>" id="access_token" ng-model="access_token" ng-init="access_token = '<?php echo $_SESSION['logged_user_access_token']; ?>'" />
            <input type="hidden" value="<?php home_url(); ?>" id="site_url" ng-model="site_url" ng-init="site_url = '<?php home_url(); ?>'" />
            <input type="hidden" value="<?php echo get_stylesheet_directory_uri(); ?>/inc/pf-edit-profile/" id="template_root_path" ng-model="template_root_path" ng-init="template_root_path = '<?php echo get_stylesheet_directory_uri(); ?>/inc/pf-edit-profile/'" />
            <uib-tabset active="active">
                <uib-tab ng-repeat="tab in model.tabs" index="$index" heading="{{tab.title}}" deselect="onTabdeselect($selectedIndex, $event)">
                    <div ng-if="tab.isLoaded" ng-include="tab.templateUrl"></div>
                </uib-tab>
            </uib-tabset>
        </div>
    </div>
</div>
