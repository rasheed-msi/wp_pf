<div class="dashboardTabMenu">
    <ul ng-if="pages.album">
        <li class="active">
            <a href="#tab1" class="flexbox verticalAlign" aria-controls="tab1" role="tab" data-toggle="tab">
                <div class="tabMenuItems">
                    <span><i class="fa fa-image"></i> Albums</span>
                </div>
            </a>
        </li>

        <li>
            <a class="flexbox verticalAlign" ng-click="displayHtmlAddBox()">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-plus-circle"></i> New</span>
                </div>
            </a>
        </li>

        <li ng-click="bulkDeleteAlbum()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-trash"></i> Delete</span>
                </div>
            </a>
        </li>

        <li ng-click="selectAllAlbum()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-check-circle"></i> {{albumSettings.selectAllLabel}}</span>
                </div>
            </a>
        </li>

        <li ng-click="showDownload()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-download"></i> Download</span>
                </div>
            </a>
        </li>

        <!--
        <li>
            <a href="#" class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-gear"></i> Options</span>
                </div>
            </a>
            <ul class="tabSubMenu">
                <li><a href="#tabSub1" aria-controls="tabSub1" role="tab" data-toggle="tab">Who can view</a></li>
                <li><a href="#tabSub2" aria-controls="tabSub2" role="tab" data-toggle="tab">Print pictures</a></li>
                <li><a href="#tabSub3" aria-controls="tabSub3" role="tab" data-toggle="tab">Lorem ipsum</a></li>
            </ul>
        </li>
        -->
    </ul>

    <ul ng-if="pages.photo && !downloadSettings.isActive">
        <li class="active">
            <a href="#tab1" class="flexbox verticalAlign" aria-controls="tab1" role="tab" data-toggle="tab">
                <div class="tabMenuItems">
                    <span><i class="fa fa-image"></i> Photos</span>
                </div>
            </a>
        </li>
        <li class="file-uploader" data-userid="<?php echo get_current_user_id(); ?>" data-albumid="{{selectedAlbumId}}">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign" ng-click="onClickNewPhoto()">
                    <span><i class="fa fa-plus-circle"></i> New</span>
                </div>
            </a>
        </li>
        
        <li ng-if="albumSettings.count" ng-click="showMovePhotoAlbum()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-arrows"></i> Move</span>
                </div>
            </a>
        </li>

        <li ng-click="bulkDeletePhoto()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-trash"></i> Delete</span>
                </div>
            </a>
        </li>

        <li ng-click="selectAllPhotos()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-check-circle"></i> {{photoSettings.selectAllLabel}}</span>
                </div>
            </a>
        </li>

<!--                <li>
                    <a class="flexbox verticalAlign">
                        <div class="tabMenuItems flexbox verticalAlign">
                            <span><i class="fa fa-check-circle"></i> Move</span>
                        </div>
                    </a>
                </li>-->

        <li ng-click="downloadPhotos()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-download"></i> Download</span>
                </div>
            </a>
        </li>
        
        <li ng-click="changeWhoCanView()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-eye"></i> {{albumSettings.allowAlbumViewText}}</span>
                </div>
            </a>
        </li>

    </ul>

    <ul ng-if="pages.move">
        <li ng-click="showAlbum()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems">
                    <span><i class="fa fa-image"></i> Albums</span>
                </div>
            </a>
        </li>
        <li ng-click="showMovePhotoAlbum()" class="active">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-arrows"></i> Move</span>
                </div>
            </a>
        </li>
    </ul>
    
    
    <ul ng-if="pages.photo && downloadSettings.isActive">
        <li>
            <a href="#tab1" class="flexbox verticalAlign" aria-controls="tab1" role="tab" data-toggle="tab">
                <div class="tabMenuItems">
                    <span><i class="fa fa-image"></i> Photos</span>
                </div>
            </a>
        </li>


        <li ng-click="selectAllPhotos()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-check-circle"></i> {{photoSettings.selectAllLabel}}</span>
                </div>
            </a>
        </li>

        <li class="active">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-download"></i> Download</span>
                </div>
            </a>
        </li>

    </ul>

    <ul ng-if="pages.photoSingle">
        <li class="active">
            <a href="#tab1" class="flexbox verticalAlign" aria-controls="tab1" role="tab" data-toggle="tab">
                <div class="tabMenuItems">
                    <span><i class="fa fa-image"></i> Photo</span>
                </div>
            </a>
        </li>
    </ul>

    <ul ng-if="pages.download">
        <li ng-click="showAlbum()">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems">
                    <span><i class="fa fa-image"></i> Albums</span>
                </div>
            </a>
        </li>


        <li ng-click="showDownload()" class="active">
            <a class="flexbox verticalAlign">
                <div class="tabMenuItems flexbox verticalAlign">
                    <span><i class="fa fa-download"></i> Download</span>
                </div>
            </a>
        </li>
    </ul>
</div>
