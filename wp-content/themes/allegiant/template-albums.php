<?php
/**
 * 
 * Template Name: Albums
 */
get_header();
?>

<section class="container" ng-app="appParentfinder">

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

                        <div class="dashboardTabsHeaderButton" ng-show="showBackButton" ng-click="executeBackButton()">
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
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 albumColumn" ng-repeat="album in albums" ng-class="{selected: hasInAlbumSelectList(album)}">
                                    <div class="albumItem">
                                        <div class="albumItemImage">
                                            <figure ng-click="changeAlbumSelectList(album)" ng-dblclick="showPhoto(album)"><img src="{{album.album_thumb}}" alt=""></figure>
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


                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 albumColumn" ng-repeat="x in photoSettings.photoLoader">
                                    <div class="albumItem">
                                        <div class="albumItemImage">
                                            <figure><span class="preloader-image"></span></figure>
                                        </div>
                                        <div class="dashBoardAlbumContents">
                                            <div class="dashBoardAlbumTitle text-center verticalAlign">
                                                <form name="formPhoto">
                                                    <span class="flexFullChild"></span>
                                                </form>
                                            </div>
                                            <div class="ashBoardAlbumContentBottom">
                                                <div class="albumWidgets text-center"><span class="albumCounts green">0</span><span class="albumCounts yellow"><i class="fa fa-image"></i></span></div>
                                                <div class="albumWidgets text-center albumFor">image quality <span class="albumForLabel web">web</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 albumColumn" ng-repeat="photo in photos" ng-class="{selected: hasInPhotoSelectList(photo)}">
                                    <div class="albumItem">
                                        <div class="albumItemImage">
                                            <figure ng-click="changePhotoSelectList(photo)" ng-dblclick="showPhotoSingle(photo)"><img ng-src="{{photo.thumb}}" alt=""></figure>
                                        </div>
                                        <div class="dashBoardAlbumContents">
                                            <div class="dashBoardAlbumTitle text-center verticalAlign">
                                                <form name="formPhoto">
                                                    <input type="text" class="span-caption" name="Title" ng-click="editPhotoTitle(photo, true)" ng-model="photo.Title" ng-blur="editPhotoTitle(photo, false)" ng-show="photoSettings.htmlTitleInput == photo.pf_photo_id || photo.Title == ''" ng-keypress="enterPressedPhoto($event, 'photo', photo)">
                                                    <span class="flexFullChild" ng-click="editPhotoTitle(photo, true)" ng-show="!(photoSettings.htmlTitleInput == photo.pf_photo_id || photo.Title == '')">{{photo.Title}}</span>
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

                            <div ng-if="pages.move" class="movealbumlist">
                                <ul >
                                    <li ng-repeat="album in movePhotoAlbum" ng-click="changeMoveAlbum(album)" ng-class="{selected: isMoveAlbum(album)}">{{album.caption}}</li>
                                </ul>
                                <button ng-click="toMoveAlbum()" class="btn buttons clearfix">Move</button>
                            </div>
                            
                            <div ng-if="pages.photoSingle">


                                <div class="commentsContent">
                                    <figure>
                                        <img src="{{photo.webview}}" alt="">
                                    </figure>

                                    <div class="commentsContentBox">
                                        <form name="photoComment">
                                            <input name="content" ng-model="comment.content" class="form-control" placeholder="Type a message here" type="text" ng-keypress="enterPressedPhoto($event, 'photoComment', comment)">
                                        </form>
                                    </div>

                                    <div class="commentsContentBox" ng-repeat="comment in photoCommentSettings.comments">
                                        <p class="comment-content">{{comment.content}}</p>
                                        <p class="comment-name">{{comment.display_name}}</p>
                                        <p class="comment-name">{{comment.created_at | date:'medium'}}</p>
                                    </div>


                                </div>
                            </div>

                            <div ng-if="pages.download">
                                <div ng-show="downloadSettings.showAlert">
                                    <p class="msg-alert">You can select multiple photos from different albums</p>
                                    <p class="msg-alert">Number of photos Selected: {{photoSettings.selectListCount}}</p>
                                    <p class="msg-alert msg-link" ng-click="downloadPhotos()" ng-show="photoSettings.selectListCount">Download selected photos</p>
                                </div>

                                <div ng-repeat="album in downloadAlbums">

                                    <div class="clearfix"></div>
                                    <h4>{{album.caption}}</h4>



                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 albumColumn" ng-repeat="photo in album.photos" ng-class="{selected: hasInPhotoSelectList(photo)}">
                                        <div class="albumItem">
                                            <div class="albumItemImage">
                                                <figure ng-click="changePhotoSelectList(photo)"><img ng-src="{{photo.thumb}}" alt=""></figure>
                                            </div>
                                            <div class="dashBoardAlbumContents">
                                                <div class="dashBoardAlbumTitle text-center verticalAlign">
                                                    <form name="formPhoto">
                                                        <span class="flexFullChild">{{ photo.Title | titleHyphen }}</span>
                                                    </form>
                                                </div>
                                                <div class="ashBoardAlbumContentBottom">
                                                    <div class="albumWidgets text-center"><span class="albumCounts green">{{photo.Size}}</span><span class="albumCounts yellow"><i class="fa fa-image"></i></span></div>
                                                    <div class="albumWidgets text-center albumFor">image quality <span class="albumForLabel web">web</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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