app.service("AppService", function () {

    this.showPage = function (show, pages) {
        angular.forEach(pages, function (value, key) {
            pages[key] = false
        });
        pages[show] = true;
        return pages;
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
});


app.service("PhotoService", function (WebService) {

    this.getItems = function (data) {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/' + data.pf_album_id + '/photos',
        });
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
});

app.service("UserService", function (WebService) {


    this.dashboard = function () {
        return WebService.request({
            method: 'GET',
            url: appConst.apiRequest + '/users/dashboard',
        });

    }
});

