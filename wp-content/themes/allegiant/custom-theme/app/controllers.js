app.controller('albumController', function ($http, $scope, AppService, PhotoService, AlbumService) {

    $scope.backButton = false;

    $scope.pages = {
        album: true,
        photo: false,
        photoSingle: false,
    };

    $scope.showAlbum = function () {

        $scope.pages = AppService.showPage('album', $scope.pages);
        $scope.backButton = false;

        AlbumService.getItems().then(function (response) {
            $scope.albums = response;
            $scope.albums_count = $scope.albums.length;
        });
    }

    // Init ...
    $scope.showAlbum();

    $scope.showPhoto = function (data) {
        $scope.pages = AppService.showPage('photo', $scope.pages);
        $scope.backButton = true;
        $scope.photos = [];

        PhotoService.getItems(data).then(function (response) {
            $scope.photos = response;
        });
    }

    $scope.showPhotoSingle = function (model) {
        $scope.pages = AppService.showPage('photoSingle', $scope.pages);
        $scope.backButton = true;
        $scope.photo = [];

        PhotoService.getItem(model).then(function (response) {
            $scope.photo = response;
        });
    }


    // Title Edit
    $scope.showEditBox = false;

    $scope.editAlbumTitle = function (model, showInput) {

        if (showInput) {
            // show text box
            $scope.showEditBox = model.pf_album_id;
        } else {
            // hide textbox and save
            if (typeof model.caption != 'undefined') {
                $scope.showEditBox = false;
                AlbumService.update({
                    pf_album_id: model.pf_album_id,
                    caption: model.caption,
                }).then(function (response) {});
            }
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

