app.controller('albumController', function (
        $scope,
        $http,
        $window,
        $interval,
        UserService,
        AppService,
        PhotoService,
        AlbumService,
        WebService,
        PageService,
        PhotoCommentService,
        AvatarService,
        FilestackAlbumProcessingService
        ) {

    $scope.pages = {
        move: false,
        album: true,
        photo: false,
        photoSingle: false,
        download: false,
    };

    $scope.albums = [];
    $scope.photos = [];

    $scope.showAjaxLoader = false;
    $scope.setAjaxLoader = function (setval) {
        if (typeof setval == 'undefined') {
            $scope.showAjaxLoader = ($scope.showAjaxLoader) ? false : true;
        } else {
            $scope.showAjaxLoader = setval;
        }
    }

    UserService.current().then(function (response) {
        $scope.currentUser = response;
    });

    $scope.albumSettings = angular.copy(AlbumService.settings);
    $scope.photoSettings = angular.copy(PhotoService.settings);
    $scope.appSettings = angular.copy(AppService.settings);
    $scope.downloadSettings = angular.copy(AppService.download);
    $scope.photoCommentSettings = angular.copy(PhotoCommentService.settings);

    $scope.featherEditor = new Aviary.Feather({
        apiKey: 'fd418edeb42a8eca',
        apiVersion: 3,
        theme: 'light', // Check out our new 'light' and 'dark' themes!
        tools: 'all',
        appendTo: '',
        onSave: function (imageID, newURL) {
            var img = document.getElementById(imageID);
            img.src = newURL;

            PhotoService.update({
                pf_photo_id: imageID,
                pf_album_id: $scope.lastModel.pf_album_id,
                url: newURL,
                change_photo: true,
            }).then(function (response) {
                console.log(response);
            });

        },
        onError: function (errorObj) {
            alert(errorObj.message);
        }
    });

    $scope.showAlbum = function () {

        $scope.albumSettings.selectList = [];

        $scope.pages = PageService.showPage('album', $scope.pages);
        $scope.showBackButton = PageService.showBackButton;
//        $interval.cancel($scope.photoSettings.preloadIntervalHandle);


        if ($scope.albumSettings.refresh) {
            $scope.albumSettings.refresh = false;

            $scope.setAjaxLoader();
            AlbumService.getItems().then(function (response) {
                $scope.albums = response;
                $scope.setAlbumCount();
                $scope.setAjaxLoader();

            });
        }

        $scope.albumSettings.htmlAddBox = false;
        $scope.setAlbumCount();
        $scope.downloadSettings = angular.copy(AppService.download);
        if (!$scope.downloadSettings.isActive) {
            $scope.photoSettings = angular.copy(PhotoService.settings);
        }

    }

    $scope.setAlbumCount = function () {
        $scope.albumSettings.count = $scope.albums.length;
        $scope.albumCountText = ($scope.albumSettings.count) ? $scope.albumSettings.count : 'no';
        $scope.heading = "You have " + $scope.albumCountText + " Albums";

    }

    // Init ...
    $scope.showAlbum();

    // Title Edit
    $scope.editAlbumTitle = function (model, showInput) {

        if (showInput) {
            // show text box
            $scope.dupCaption = model.caption;
            $scope.albumSettings.htmlTitleInput = model.pf_album_id;
            $scope.albumSettings.selectList = [];

        } else {
            // hide textbox and save
            if (typeof model.caption != 'undefined' && model.caption.trim().length != 0 && model.caption.trim().length <= $scope.appSettings.maxCaptionLength) {
                $scope.albumSettings.htmlTitleInput = false;

                AlbumService.update({
                    pf_album_id: model.pf_album_id,
                    caption: model.caption,
                }).then(function (response) {});
            } else {
                model.caption = $scope.dupCaption;
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
                $scope.albumSettings.refresh = true;
                $scope.albumSettings.downloadRefresh = true;

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
        $scope.albumSettings.downloadRefresh = true;

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


    $scope.showPhoto = function (data) {
        $scope.pages = PageService.showPage('photo', $scope.pages);
        $scope.showBackButton = PageService.showBackButton;
        $scope.photos = [];
        $scope.heading = data.caption;
        $scope.lastModel = data;
        $scope.setAllowAlbumViewText($scope.lastModel.AllowAlbumView);
        $scope.selectedAlbumId = data.pf_album_id;
        console.log($scope.selectedAlbumId);
//        $interval.cancel($scope.photoSettings.preloadIntervalHandle);
        $scope.setAjaxLoader(true);
        $scope.loadPhotos(data);
    }


    $scope.loadPhotos = function (data) {

        data.processing = 1;

        PhotoService.getItems(data).then(function (response) {

            $scope.photoSettings.photoLoader = AppService.arrayFill(response.processing_img_count);
            $scope.photos = response.photos;



            $scope.setAjaxLoader(false);
        });
    }

    $scope.onClickNewPhoto = function () {

        var client = filestack.init(appConst.fileStackClient);

        client.pick({
            accept: 'image/*',
            // fromSources: ['local_file_system', 'facebook', 'flickr', 'instagram', 'picasa'],
            fromSources: ['local_file_system'],
            maxFiles: 50,
            uploadInBackground: false,
            disableTransformer: true,
            onFileUploadProgress: function (file, progressEvent) {
                // console.log(JSON.stringify(progressEvent.totalProgressPercent))
            },
            onFileUploadFailed: function (file, error) {
                // console.log(error);
            }
        }).then(function (result) {

            var i = result.filesUploaded.length;

            $scope.photoSettings.photoLoader = AppService.arrayFill(i);

            angular.forEach(result.filesUploaded, function (value, index) {

                value.pf_album_id = $scope.selectedAlbumId;
                value.mode = 'album';

                PhotoService.create(value).then(function (response) {
                    var newPhoto = {
                        pf_album_id: value.pf_album_id,
                        pf_photo_id: response.thumb.pf_photo_id,
                        Title: "",
                        thumb: response.thumb.cloud_path,
                    };

                    $scope.photos = $scope.photos.mrtprepend(newPhoto);
                    $scope.photoSettings.photoLoader = AppService.arrayFill(--i);
                });
            });

        });

//        $scope.photoSettings.preloadIntervalHandle = $interval(function () {
//            $scope.loadPhotos($scope.lastModel);
//        }, 10000);

    }

    $scope.showPhotoSingle = function (model) {

        $scope.pages = PageService.showPage('photoSingle', $scope.pages);
        $scope.showBackButton = PageService.showBackButton;
//        $interval.cancel($scope.photoSettings.preloadIntervalHandle);

        $scope.photo = [];
        $scope.heading = model.Title;

        PhotoService.getItem(model).then(function (response) {
            $scope.photo = response;
        });

        $scope.photoCommentSettings.selectedPhoto = model.pf_photo_id;
        var data = {
            pf_photo_id: model.pf_photo_id
        };

        PhotoCommentService.getItems(data).then(function (response) {
            $scope.photoCommentSettings.comments = response;

        });
    }

    $scope.executeBackButton = function () {
        var previousPage = PageService.getPreviousPage();
        PageService.backHref = true;

        if (previousPage == 'photo') {
            $scope.showPhoto($scope.lastModel);
        } else if (previousPage == 'album') {
            $scope.showAlbum();
        } else if (previousPage == 'download') {
            $scope.showDownload();
        }
    }


    $scope.editPhotoTitle = function (model, showInput) {

        if (showInput) {
            // show text box
            $scope.dupCaption = model.Title;
            $scope.photoSettings.htmlTitleInput = model.pf_photo_id;
            $scope.photoSettings.selectList = [];
        } else {
            // hide textbox and save

            if (typeof model.Title != 'undefined' && model.Title.trim().length != 0 && model.Title.trim().length <= $scope.appSettings.maxCaptionLength) {
                $scope.photoSettings.htmlTitleInput = false;

                PhotoService.update({
                    pf_album_id: model.pf_album_id,
                    pf_photo_id: model.pf_photo_id,
                    Title: model.Title,
                }).then(function (response) {});
            } else {
                model.Title = $scope.dupCaption;
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

        $scope.photoSettings.selectListCount = $scope.photoSettings.selectList.length;
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
                if (!$scope.hasInPhotoSelectList(photo)) {
                    $scope.photoSettings.selectList.push(photo.pf_photo_id);
                }

            });
            $scope.photoSettings.selectAllLabel = "DESELECT ALL";
        }

        $scope.photoSettings.selectListCount = $scope.photoSettings.selectList.length;
    }

    $scope.enterPressedPhoto = function ($event, type, model) {

        var keyCode = $event.which || $event.keyCode;
        if (keyCode === 13) {
            if (type == 'newphoto') {
                $scope.addPhoto();
            } else if (type == 'photo') {
                $scope.editPhotoTitle(model, false);
            } else if (type == 'photoComment') {
                $scope.addPhotoComment(model);
            }
        }
    }

    $scope.bulkDeletePhoto = function () {

        var list = angular.copy($scope.photoSettings.selectList);
        angular.forEach(list, function (id, key) {

            $scope.photos = AppService.collectiveRemove($scope.photos, 'pf_photo_id', id);

            var data = {
                pf_album_id: $scope.selectedAlbumId,
                pf_photo_id: id,
            };

            PhotoService.delete(data).then(function (response) {});
            $scope.photoSettings.selectList.remove(id);

        });

        $scope.albumSettings.downloadRefresh = true;
        $scope.photoSettings.selectListCount = $scope.photoSettings.selectList.length;
    }

    $scope.downloadPhotos = function () {

        if ($scope.photoSettings.selectList.length == 0) {
            return false;
        }

        var data = {
            ids: $scope.photoSettings.selectList,
        };

        $scope.setAjaxLoader();
        AlbumService.download(data).then(function (response) {
            $scope.photoSettings.selectList = [];
            $scope.photoSettings.selectListCount = 0;
            $scope.setAjaxLoader();
            $window.open(response.zip_url, '_blank');
        });

    }

    $scope.showDownload = function () {
        $scope.setAlbumCount();
        $scope.pages = PageService.showPage('download', $scope.pages);
        $scope.showBackButton = PageService.showBackButton;
//        $interval.cancel($scope.photoSettings.preloadIntervalHandle);

        $scope.downloadSettings.isActive = true;
        $scope.downloadSettings.showAlert = true;
        $scope.albumSettings.selectList = [];

        if ($scope.albumSettings.downloadRefresh) {
            $scope.setAjaxLoader();
            AlbumService.getItems().then(function (response) {

                $scope.setAjaxLoader();
                $scope.downloadAlbums = [];

                angular.forEach(response, function (item, key) {

                    if ($scope.albumSettings.count == key + 1) {
                        $scope.albumSettings.downloadRefresh = false;
                    }

                    var obj = {};
                    obj.caption = item.caption;

                    var data = {
                        pf_album_id: item.pf_album_id,
                        pf_photo_id: item.pf_photo_id
                    };

                    PhotoService.getItems(data).then(function (response) {
                        obj.photos = response;
                        if (Object.keys(obj.photos).length > 0) {
                            $scope.downloadAlbums.push(obj);
                        }
                    });

                });

            });
        }


    }

    $scope.addPhotoComment = function (model) {

        var data = {
            content: model.content,
            pf_photo_id: $scope.photoCommentSettings.selectedPhoto,
            user_id: $scope.currentUser.user_id,
            display_name: $scope.currentUser.profile.display_name,
            avatar: $scope.currentUser.profile.avatar,
        };

        $scope.photoCommentSettings.comments = $scope.photoCommentSettings.comments.mrtprepend(data);
        model.content = "";
        PhotoCommentService.create(data).then(function (response) {
//            console.log(response);
        });
    }

    $scope.showCommentTime = function (time) {
        console.log(time);
    }

    $scope.showMovePhotoAlbum = function () {
        if ($scope.photoSettings.selectListCount == 0) {
            return;
        }
        $scope.heading = "Move photos"
        $scope.pages = PageService.showPage('move', $scope.pages);
        $scope.showBackButton = PageService.showBackButton;
        $scope.movePhotoAlbum = angular.copy($scope.albums);
        $scope.movePhotoAlbum = $scope.movePhotoAlbum.collectiveRemove('pf_album_id', $scope.selectedAlbumId);
        $scope.albumSettings.moveAlbum = (typeof $scope.movePhotoAlbum[0]['pf_album_id'] != 'undefined') ? $scope.movePhotoAlbum[0]['pf_album_id'] : 0;
    }

    $scope.changeMoveAlbum = function (album) {
        $scope.albumSettings.moveAlbum = album.pf_album_id;
    }

    $scope.isMoveAlbum = function (album) {
        return $scope.albumSettings.moveAlbum == album.pf_album_id;
    }

    $scope.toMoveAlbum = function () {

        var i = 0;
        $scope.setAjaxLoader();

        angular.forEach($scope.photoSettings.selectList, function (id, key) {

            var data = {
                pf_album_id: $scope.albumSettings.moveAlbum,
                pf_photo_id: id,
            };

            PhotoService.update(data).then(function (response) {
                ++i;
                if (i == $scope.photoSettings.selectListCount) {
                    $scope.photoSettings.selectList = [];
                    $scope.photoSettings.selectListCount = 0;
                    $scope.albumSettings.refresh = true;
                    $scope.executeBackButton();
                }
            });


        });
    }

    $scope.changeWhoCanView = function () {
        console.log($scope.lastModel.AllowAlbumView);
        if ($scope.lastModel.AllowAlbumView == $scope.albumSettings.statusIdEveryone) {
            $scope.lastModel.AllowAlbumView = $scope.albumSettings.statusIdMember;
        } else {
            $scope.lastModel.AllowAlbumView = $scope.albumSettings.statusIdEveryone;
        }

        $scope.setAllowAlbumViewText($scope.lastModel.AllowAlbumView);
        var data = {AllowAlbumView: $scope.lastModel.AllowAlbumView, pf_album_id: $scope.lastModel.pf_album_id};
        AlbumService.update(data).then(function (response) {
            console.log(response);
        });
    }

    $scope.setAllowAlbumViewText = function (status) {
        $scope.albumSettings.allowAlbumViewText = (status == $scope.albumSettings.statusIdEveryone) ? "EVERYONE (ON)" : "EVERYONE (OFF)";
    }

    $scope.editPhoto = function (id, url) {

        $scope.featherEditor.launch({
            image: id,
            url: url
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




