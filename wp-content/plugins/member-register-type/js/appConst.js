
var appConst = {};

if (location.host == 'localhost') {
    appConst.base_url = location.port + '//' + location.host + '/parentfinder';
} else {
    appConst.base_url = location.port + '//' + location.host;
}

appConst.apiRequest = appConst.base_url + "/wp-json/mrt/v1";
// appConst.fileStackClient = 'Aym4Su0dJRFaqnWPrLu0Az'; // Test account
appConst.fileStackClient = 'A9Ul90L7XRqWxNswfaGOGz'; 
appConst.s3Domain = 'wppf';
appConst.mrtToken = mrt_readCookie('MrtToken');

function mrt_readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0)
            return c.substring(nameEQ.length, c.length);
    }
    return null;
}
