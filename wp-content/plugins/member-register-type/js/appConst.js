
var appConst = {};

if (location.host == 'localhost') {
    appConst.base_url = location.port + '//' + location.host + '/parentfinder';    
} else {
    appConst.base_url = location.port + '//' + location.host;   
}

appConst.apiRequest = appConst.base_url + "/wp-json/mrt/v1";