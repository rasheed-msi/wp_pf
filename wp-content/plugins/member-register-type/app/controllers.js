app.controller('albumController', function ($http, $scope, AppService, PhotoService, AlbumService, WebService) {

    $scope.pages = {
        album: true,
        photo: false,
        photoSingle: false,
    };

    $scope.albumSettings = {
        htmlAddBox: false,
        htmlTitleInput: false,
        selectList: [],
    };

    $scope.resetAlbumSettings = function () {
        $scope.albumSettings = {
            htmlAddBox: false,
            htmlTitleInput: false,
            selectList: [],
        };
    }

    $scope.showAjaxLoader = false;
    $scope.setAjaxLoader = function () {
        $scope.showAjaxLoader = ($scope.showAjaxLoader) ? false : true;
    }

    $scope.enterPressedAlbum = function ($event, type, album) {
       
        var keyCode = $event.which || $event.keyCode;
        if (keyCode === 13) {
            if(type == 'newalbum'){
                $scope.addAlbum();
            }else if(type == 'album'){
                $scope.editAlbumTitle(album, false)
            }
        }
    }


    $scope.showAlbum = function () {

        $scope.setAjaxLoader();
        $scope.pages = AppService.showPage('album', $scope.pages);
        $scope.backButton = false;
        
        AlbumService.getItems().then(function (response) {

            $scope.albums = response;
            var albumCount = $scope.albums.length;
            albumCount = (albumCount) ? albumCount : 'no';
            $scope.heading = "You have " + albumCount + " Albums";

            $scope.resetAlbumSettings();
            $scope.setAjaxLoader();
        });
    }

    // Init ...
    $scope.showAlbum();


    // Title Edit

    $scope.editAlbumTitle = function (model, showInput) {

        if (showInput) {
            // show text box
            $scope.albumSettings.htmlTitleInput = model.pf_album_id;
            $scope.albumSettings.selectList = [];
        } else {
            // hide textbox and save
            if (typeof model.caption != 'undefined') {
                $scope.albumSettings.htmlTitleInput = false;

                AlbumService.update({
                    pf_album_id: model.pf_album_id,
                    caption: model.caption,
                }).then(function (response) {});
            }
        }
    }


    /**
     * 
     * Add Album
     */
    $scope.newAlbum = {};
    $scope.addAlbum = function () {
        if ($scope.newAlbum.caption != '') {
            AlbumService.create({
                caption: $scope.newAlbum.caption,
            }).then(function (response) {
                $scope.showAlbum();
                $scope.newAlbum = {};

            });
        }
    }


    $scope.changeSelectList = function (model) {
        $scope.albumSettings.htmlTitleInput = false;
        var id = model.pf_album_id;
        if ($scope.albumSettings.selectList.indexOf(id) == -1) {
            $scope.albumSettings.selectList.push(id);
        } else {
            $scope.albumSettings.selectList.remove(id);
        }
    }

    $scope.hasInSelectList = function (model) {
        var id = model.pf_album_id;
        return ($scope.albumSettings.selectList.indexOf(id) == -1) ? false : true;
    }

    $scope.bulkDeleteAlbum = function () {
        var deleteList = [];
        angular.forEach($scope.albumSettings.selectList, function (id, key) {
            AlbumService.delete(id).then(function (response) {});

            deleteList.push(id);

            if ($scope.albumSettings.selectList.length == deleteList.length) {
                $scope.showAlbum();
            }
        });
    }


    $scope.displayHtmlAddBox = function () {
        $scope.albumSettings.htmlAddBox = ($scope.albumSettings.htmlAddBox) ? false : true;
    }

    /**
     * 
     * Select & deselect items
     */
    var selectAllAlbum = false;
    $scope.selectAllText = "SELECT ALL";
    $scope.selectAllAlbum = function () {
        if (selectAllAlbum) {
            $scope.albumSettings.selectList = [];
            selectAllAlbum = false;
            $scope.selectAllText = "SELECT ALL";
        } else {
            angular.forEach($scope.albums, function (album, key) {
                $scope.albumSettings.selectList.push(album.pf_album_id);
            });
            selectAllAlbum = true;
            $scope.selectAllText = "DESELECT ALL";
        }
    }


    /**
     * 
     * Photos
     */
    $scope.showPhoto = function (data) {
        $scope.pages = AppService.showPage('photo', $scope.pages);
        $scope.backButton = 'photo';
        $scope.photos = [];
        $scope.heading = data.caption;
        $scope.lastModel = data;

        PhotoService.getItems(data).then(function (response) {
            $scope.photos = response;

        });
    }

    $scope.showPhotoSingle = function (model) {
        $scope.pages = AppService.showPage('photoSingle', $scope.pages);
        $scope.backButton = 'photoSingle';
        $scope.photo = [];
        $scope.heading = model.Title;

        PhotoService.getItem(model).then(function (response) {
            $scope.photo = response;
        });
    }

    $scope.executeBackButton = function (viewing) {
        if (viewing == 'photoSingle') {
            $scope.showPhoto($scope.lastModel);
            $scope.backButton = 'photo';
        } else if (viewing == 'photo') {
            $scope.showAlbum();
            $scope.backButton = false;
        }
    }



    $scope.editPhotoTitle = function (model, showInput) {
        if (showInput) {
            // show text box
            $scope.showEditBox = model.pf_photo_id;
        } else {
            // hide textbox and save
            if (typeof model.Title != 'undefined') {
                $scope.showEditBox = false;
                PhotoService.update({
                    pf_album_id: model.pf_album_id,
                    pf_photo_id: model.pf_photo_id,
                    Title: model.Title,
                }).then(function (response) {});
            }
        }
    }



});


app.controller('dashboardController', function ($scope, UserService, $sce) {

    UserService.dashboard().then(function (response) {

        $scope.profile = response.profile;
        $scope.info = response.info;

        $scope.preferences = response.preferences;


        if ($scope.info.YoutubeLink == 1) {
            var videoHtml = ' <iframe width="100%" height="100%" src="' + $scope.info.video_url + '"></iframe>';
        } else {
            var videoHtml = '<video width="100%" height="100%" controls>'
                    + '<source src="' + $scope.info.video_url + '" type="video/mp4">'
                    + '</video>';
        }

        $scope.videoDashboard = $sce.trustAsHtml(videoHtml);
        $scope.intro = $sce.trustAsHtml($scope.preferences.intro);

    });

});

