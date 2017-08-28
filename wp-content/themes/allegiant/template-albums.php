<?php
/**
 * 
 * Template Name: Albums
 */
get_header();
?>

<section class="container"  ng-app="appParentfinder">
    
    

    <div class="dashboardTabs flexbox" style="margin-top:50px;" ng-controller="albumController">

        <div id="ajaxloader" ng-show="showAjaxLoader"></div>
        <?php get_sidebar('albums'); ?>

        <div class="dashboardTabsContent flexFullChild">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab1">

                    <div class="dashboardTabsHeader flexbox verticalAlign">
                        <div class="dashboardTabsHeaderContent flexFullChild">
                            <h4>{{heading}}</h4>
                            <p>Need some help? Read the documentation or watch a video</p>
                        </div>
                        <div class="dashboardTabsHeaderButton" ng-if="backButton" ng-click="executeBackButton(backButton)">
                            <a href="#" class="btn buttons clearfix"><i class="fa fa-angle-left"></i><span>Back</span></a>
                        </div>
                    </div>
                    <section class="album">
                        <div class="row albumGroup">

                            <div ng-if="pages.album">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 albumColumn" ng-show="albumSettings.htmlAddBox">
                                    <div class="albumItem">
                                        <div class="albumItemImage">
                                            <figure><img src="<?php echo MRT_URL_DEFAULT_PHOTOS_THUMB ?>" alt=""></figure>
                                        </div>
                                        <div class="dashBoardAlbumContents">
                                            <div class="dashBoardAlbumTitle text-center verticalAlign">
                                                <form name="formAlbum">
                                                    <input type="text" class="span-caption" name="caption" ng-maxlength="28" ng-class="{error: formAlbum.caption.$invalid}" ng-model="newAlbum.caption" ng-keypress="enterPressedAlbum($event, 'newalbum')" ng-blur="addAlbum()">
                                                </form>
                                            </div>                                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 albumColumn" ng-repeat="album in albums" ng-class="{selected: hasInSelectList(album)}">
                                    <div class="albumItem">
                                        <div class="albumItemImage">
                                            <figure ng-click="changeSelectList(album)" ng-dblclick="showPhoto(album)"><img src="{{album.album_thumb}}" alt=""></figure>
                                        </div>
                                        <div class="dashBoardAlbumContents">
                                            <div class="dashBoardAlbumTitle text-center verticalAlign">

                                                <form name="formAlbum">
                                                    <input type="text" class="span-caption" name="caption" ng-maxlength="28" ng-click="editAlbumTitle(album, true)" ng-class="{error: formAlbum.caption.$invalid}" ng-model="album.caption" ng-blur="editAlbumTitle(album, false)" ng-show="albumSettings.htmlTitleInput == album.pf_album_id || album.caption == ''" ng-keypress="enterPressedAlbum($event, 'album', album)">
                                                    <span class="flexFullChild" ng-click="editAlbumTitle(album, true)" ng-show="!(albumSettings.htmlTitleInput == album.pf_album_id || album.caption == '')">{{album.caption}}</span>
                                                </form>
                                            </div>                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div ng-if="pages.photo">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 albumColumn" ng-repeat="photo in photos">
                                    <div class="albumItem">
                                        <div class="albumItemImage">
                                            <figure ng-click="showPhotoSingle(photo)"><a href="#"><img ng-src="{{photo.thumb}}" alt=""></a></figure>
                                        </div>
                                        <div class="dashBoardAlbumContents">
                                            <div class="dashBoardAlbumTitle text-center verticalAlign">
                                                <form name="formPhoto">
                                                    <input type="text" name="Title" ng-model="photo.Title" ng-blur="editPhotoTitle(photo, false)" ng-show="showEditBox == photo.pf_photo_id">
                                                    <p ng-show="formPhoto.Title.$touched && formPhoto.Title.$invalid"> Please enter title</p>
                                                    <span class="flexFullChild" ng-click="editPhotoTitle(photo, true)" ng-show="(showEditBox != photo.pf_photo_id)">{{photo.Title}}</span>
                                                </form>
                                            </div>
                                            <div class="ashBoardAlbumContentBottom">
                                                <div class="albumWidgets text-center"><span class="albumCounts green">16</span><span class="albumCounts yellow"><i class="fa fa-image"></i></span></div>
                                                <div class="albumWidgets text-center albumFor">image quality <span class="albumForLabel web">web</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div ng-if="pages.photoSingle">
                                <div class="col-lg-12">
                                    <img src="{{photo.webview}}" alt="">
                                </div>
                            </div>


                        </div>
                    </section>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab2">
                    2
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab3">
                    3
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab4">
                    4
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab5">
                    5
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab6">
                    6
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab7">
                    7
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab8">
                    8
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tabSub1">
                    Sub1
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tabSub2">
                    Sub2
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tabSub3">
                    Sub3
                </div>
            </div>
        </div>

        <div class="manageButton text-center">Manage <i class="fa fa-angle-down"></i></div>

    </div>

</section>

<?php get_footer('custom'); ?>