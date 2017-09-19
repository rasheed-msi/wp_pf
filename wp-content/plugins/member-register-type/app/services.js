app.service("AppService", function () {
    
    this.collectiveRemove = function (Obj, key, value) {
        var keep = [];

        angular.forEach(Obj, function (item, index) {
            if (item[key] != value) {
                keep.push(item)
            }
        });
        return keep;
    }

    this.download = {
        alert: '',
        selectList: [],
        isActive: false,
        showAlert: false,
    }
    
    this.settings = {
        maxCaptionLength: 23,
    }
    
    this.arrayFill = function(count){
        var a = [];
        for (var i = 0; i < count; i++) a[i] = i;
        return a;
    }
});


app.service("WebService", function ($http, $q) {

    this.request = function (req) {

        var deferred = $q.defer();

        req.headers = {Token: appConst.mrtToken}

        $http(req).then(function (response) {
            deferred.resolve(response.data);
        }, function (response) {
            deferred.reject(response);
        });

        return deferred.promise;
    }
});


app.service("AlbumService", function (WebService) {

    this.settings = {
        htmlAddBox: false,
        htmlTitleInput: false,
        selectList: [],
        selectAllLabel: "SELECT ALL",
        count: 0,
        refresh: true,
        downloadRefresh: true,
        moveAlbum: 0,
    }

    this.getItems = function () {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/albums',
        });
    }

    this.getItem = function (id) {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/albums/' + id,
        });
    }

    this.create = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/albums',
            data: data
        });
    }

    this.update = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/albums/' + data.pf_album_id,
            data: data
        });
    }

    this.delete = function (id) {
        return WebService.request({
            method: 'DELETE',
            url: appConst.apiRequest + '/albums/' + id,
        });
    }
    
    this.download = function (data) {
        return WebService.request({
            method: 'POST',
            data: data,
            url: appConst.apiRequest + '/albums/download-photos',
        });
    }
});


app.service("PhotoService", function (WebService) {

    this.settings = {
        htmlAddBox: false,
        htmlTitleInput: false,
        selectList: [],
        selectAllLabel: "SELECT ALL",
        selectListCount: 0,
        photoLoader: 0,
        preloadIntervalHandle: 0,
    }

    this.getItems = function (data) {

        if (typeof data.processing == 'undefined') {
            return WebService.request({
                method: 'GET',
                url: appConst.apiRequest + '/' + data.pf_album_id + '/photos',
            });
        } else {
            return WebService.request({
                method: 'GET',
                url: appConst.apiRequest + '/' + data.pf_album_id + '/photos?processing=1',
            });
        }
    }

    this.getItem = function (data) {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/photos/' + data.pf_photo_id,
        });
    }

    this.create = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/photos',
            data: data
        });
    }

    this.update = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/photos/' + data.pf_photo_id,
            data: data
        });
    }

    this.delete = function (data) {
        return WebService.request({
            method: 'DELETE',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/photos/' + data.pf_photo_id,
        });
    }

    this.download = function (data) {
        return WebService.request({
            method: 'POST',
            data: data,
            url: appConst.apiRequest + '/' + data.pf_album_id + '/photos/download-photos',
        });
    }
    
});

app.service("UserService", function (WebService) {


    this.dashboard = function () {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/users/dashboard',
        });
    };
    
    this.current = function () {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/users/current',
        });
    }
});

app.service("PageService", function () {

    this.visit = [];
    this.backHref = false;
    this.previousPage = false;
    this.showBackButton = false;

    this.showPage = function (show, pages) {
        this.addVisit(show);
        this.showBackButton = (typeof this.getPreviousPage() == 'undefined') ? false : true;
        angular.forEach(pages, function (value, key) {
            pages[key] = false
        });
        pages[show] = true;
        return pages;
    }

    this.addVisit = function (page) {
        var index = this.visit.length - 1;
        if(typeof this.visit[index] != 'undefined' && this.visit[index] == page){
            // same page
        } else if(this.backHref){
            this.visit.pop();
            this.backHref = false;
        } else {
            this.visit.push(page);
        }
        
//        if (!this.backHref) {
//            this.visit.push(page);
//        } else {
//            // Visit the page by back button
//            this.visit.pop();
//            this.backHref = false;
//        }
    };
    this.getPreviousPage = function () {
        var index = this.visit.length - 2 // second last index 
        this.previousPage = this.visit[index];
        return this.previousPage;
    };



});

app.service("PhotoCommentService", function (WebService) {

    this.settings = {
        selectedPhoto: '',
        comments: [],
        htmlAddBox: false,
        htmlTitleInput: false,
        selectList: [],
        selectAllLabel: "SELECT ALL",
        selectListCount: 0,
    }

    this.getItems = function (data) {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/' + data.pf_photo_id + '/photo-comment',
        });
    }

    this.getItem = function (data) {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/' + data.pf_photo_id + '/photo-comment/' + data.id,
        });
    }

    this.create = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/' + data.pf_photo_id + '/photo-comment',
            data: data
        });
    }

    this.update = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/' + data.pf_photo_id + '/photo-comment/' + data.id,
            data: data
        });
    }

    this.delete = function (data) {
        return WebService.request({
            method: 'DELETE',
            url: appConst.apiRequest + '/' + data.pf_photo_id + '/photo-comment/' + data.id,
        });
    }

});

app.service("FilestackAlbumProcessingService", function (WebService) {

    this.settings = {}

    this.getItems = function (data) {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/filestack-album-processing',
        });
    }

    this.getItem = function (data) {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/filestack-album-processing/' + data.pf_photo_id,
        });
    }

    this.create = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/filestack-album-processing',
            data: data
        });
    }

    this.update = function (data) {
        return WebService.request({
            method: 'POST',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/filestack-album-processing/' + data.pf_photo_id,
            data: data
        });
    }

    this.delete = function (data) {
        return WebService.request({
            method: 'DELETE',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/filestack-album-processing/' + data.pf_photo_id,
        });
    }

});

app.filter('commentTime', function() {
    return function(x) {
        if(typeof x == "undefined"){
            return 'just now';
        }
        return x + " now";
    };
});