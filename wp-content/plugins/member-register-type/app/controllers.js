app.controller('albumController', function ($http, $window, $scope, AppService, PhotoService, AlbumService, WebService) {

    $scope.pages = {
        album: true,
        photo: false,
        photoSingle: false,
        download: false,
    };

    $scope.showAjaxLoader = false;
    $scope.setAjaxLoader = function () {
        $scope.showAjaxLoader = ($scope.showAjaxLoader) ? false : true;
    }

    $scope.resetAlbumSettings = function () {
        $scope.albumSettings = {
            htmlAddBox: false,
            htmlTitleInput: false,
            selectList: [],
            selectAllLabel: "SELECT ALL",
        };


    }
    $scope.resetAlbumSettings()

    $scope.showAlbum = function () {

        $scope.setAjaxLoader();
        $scope.pages = AppService.showPage('album', $scope.pages);
        $scope.backButton = false;

        AlbumService.getItems().then(function (response) {
            $scope.albums = response;
            $scope.setAlbumCount();
            $scope.resetAlbumSettings();
            $scope.resetPhotoSettings();
            $scope.setAjaxLoader();
        });
    }

    $scope.setAlbumCount = function () {
        var albumCount = $scope.albums.length;
        $scope.albumCount = (albumCount) ? albumCount : 'no';
        $scope.heading = "You have " + $scope.albumCount + " Albums";
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

    $scope.enterPressedAlbum = function ($event, type, album) {

        var keyCode = $event.which || $event.keyCode;
        if (keyCode === 13) {
            if (type == 'newalbum') {
                $scope.addAlbum();
            } else if (type == 'album') {
                $scope.editAlbumTitle(album, false)
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


    $scope.changeAlbumSelectList = function (model) {
        $scope.albumSettings.htmlTitleInput = false;
        var id = model.pf_album_id;
        if ($scope.albumSettings.selectList.indexOf(id) == -1) {
            $scope.albumSettings.selectList.push(id);
        } else {
            $scope.albumSettings.selectList.remove(id);
        }
        if ($scope.albums.length == $scope.albumSettings.selectList.length) {
            $scope.albumSettings.selectAllLabel = "DESELECT ALL";
        }

        if (Object.keys($scope.albums).length == $scope.albumSettings.selectList.length) {
            $scope.albumSettings.selectAllLabel = "DESELECT ALL";
        } else {
            $scope.albumSettings.selectAllLabel = "SELECT ALL";
        }
    }

    $scope.hasInAlbumSelectList = function (model) {
        var id = model.pf_album_id;
        return ($scope.albumSettings.selectList.indexOf(id) == -1) ? false : true;
    }

    $scope.bulkDeleteAlbum = function () {
        angular.forEach($scope.albumSettings.selectList, function (id, key) {
            AlbumService.delete(id).then(function (response) {});
            $scope.albums = AppService.collectiveRemove($scope.albums, 'pf_album_id', id);
        });
        $scope.setAlbumCount();
    }


    $scope.displayHtmlAddBox = function () {
        $scope.albumSettings.htmlAddBox = ($scope.albumSettings.htmlAddBox) ? false : true;
    }

    /**
     * 
     * Select & deselect items
     */

    $scope.selectAllAlbum = function () {
        if ($scope.albumSettings.selectAllLabel == "DESELECT ALL") {
            $scope.albumSettings.selectList = [];
            $scope.albumSettings.selectAllLabel = "SELECT ALL";
        } else {
            angular.forEach($scope.albums, function (album, key) {
                $scope.albumSettings.selectList.push(album.pf_album_id);
            });
            $scope.albumSettings.selectAllLabel = "DESELECT ALL";
        }
    }


    /**
     * 
     * Photos ==================================================================
     */
    $scope.resetPhotoSettings = function () {
        $scope.photoSettings = {
            htmlAddBox: false,
            htmlTitleInput: false,
            selectList: [],
            selectAllLabel: "SELECT ALL",
        };
    }
    $scope.resetPhotoSettings();

    $scope.showPhoto = function (data) {
        $scope.pages = AppService.showPage('photo', $scope.pages);
        $scope.backButton = 'photo';
        $scope.photos = [];
        $scope.heading = data.caption;
        $scope.lastModel = data;
        $scope.selectedAlbumId = data.pf_album_id;


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
            $scope.photoSettings.htmlTitleInput = model.pf_photo_id;
            $scope.photoSettings.selectList = [];
        } else {
            // hide textbox and save
            if (typeof model.Title != 'undefined') {
                $scope.photoSettings.htmlTitleInput = false;

                PhotoService.update({
                    pf_album_id: model.pf_album_id,
                    pf_photo_id: model.pf_photo_id,
                    Title: model.Title,
                }).then(function (response) {});
            }
        }
    }

    $scope.changePhotoSelectList = function (model) {
        $scope.photoSettings.htmlTitleInput = false;
        var id = model.pf_photo_id;
        if ($scope.photoSettings.selectList.indexOf(id) == -1) {
            $scope.photoSettings.selectList.push(id);
        } else {
            $scope.photoSettings.selectList.remove(id);
        }

        if (Object.keys($scope.photos).length == $scope.photoSettings.selectList.length) {
            $scope.photoSettings.selectAllLabel = "DESELECT ALL";
        } else {
            $scope.photoSettings.selectAllLabel = "SELECT ALL";
        }
    }

    $scope.hasInPhotoSelectList = function (model) {
        var id = model.pf_photo_id;
        return ($scope.photoSettings.selectList.indexOf(id) == -1) ? false : true;
    }

    $scope.selectAllPhotos = function () {
        if ($scope.photoSettings.selectAllLabel == "DESELECT ALL") {
            $scope.photoSettings.selectList = [];
            $scope.photoSettings.selectAllLabel = "SELECT ALL";
        } else {
            angular.forEach($scope.photos, function (photo, key) {
                $scope.photoSettings.selectList.push(photo.pf_photo_id);
            });
            $scope.photoSettings.selectAllLabel = "DESELECT ALL";
        }
    }

    $scope.enterPressedPhoto = function ($event, type, album) {

        var keyCode = $event.which || $event.keyCode;
        if (keyCode === 13) {
            if (type == 'newphoto') {
                $scope.addPhoto();
            } else if (type == 'photo') {
                $scope.editPhotoTitle(album, false)
            }
        }
    }

    $scope.bulkDeletePhoto = function () {

        angular.forEach($scope.photoSettings.selectList, function (id, key) {

            $scope.photos = AppService.collectiveRemove($scope.photos, 'pf_photo_id', id);
            var data = {
                pf_album_id: $scope.selectedAlbumId,
                pf_photo_id: id,
            };
            PhotoService.delete(data).then(function (response) {});


        });
    }

    $scope.downloadPhotos = function () {

        if ($scope.photoSettings.selectList.length == 0) {
            return false;
        }

        var data = {
            pf_album_id: $scope.selectedAlbumId,
            ids: $scope.photoSettings.selectList,
        };

        $scope.setAjaxLoader();
        PhotoService.download(data).then(function (response) {
            $scope.setAjaxLoader();
            console.log(response);

            $window.open(response.zip_url, '_blank');
        });

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

