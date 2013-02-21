// Extend the Array Prototype with a 'foreach'
Array.prototype.forEach = function (action) {
    var i, len;
    for ( i = 0, len = this.length; i < len; ++i ) {
        action(i, this[i], len);
    }
};

/**
 * Render a given url to a given file
 * @param url URL to render
 * @param file File to render to
 * @param callback Callback function
 */
function renderUrlToFile(url, file, callback) {
    var page = require('webpage').create();
    page.viewportSize = { width: 480, height : 500 };
    page.settings.userAgent = "Phantom.js bot";

    page.open(url, function(status){
        if ( status !== "success") {
            console.log("Unable to render '"+url+"'");
        } else {
            page.render(file);
        }
        page.close();
        callback(url, file);
    });
}

// Read the passed args
var arrayOfUrls;
var fs = require('fs');
var path = 'Data/jscache.json';
if (fs.exists(path) && fs.isFile(path)) {
    var fileStr = fs.read(path);
    arrayOfUrls = fileStr.split('@');
}

// For each URL
arrayOfUrls.forEach(function(pos, url, total){
    var fileName = url.split('/');
    var indexOfUrl = fileName.length - 1;
    var tmp = fileName[indexOfUrl];
    var urlPath = tmp.split('.');
    var file_name = "Storage/image480/" + urlPath[0] + ".gif";

    // Render to a file
    renderUrlToFile(url, file_name, function(url, file){
        console.log("Rendered '"+url+"' at '"+file+"'");
        if ( pos === total-1 ) {
            // Close Phantom if it's the last URL
            phantom.exit();
        }
    });
});