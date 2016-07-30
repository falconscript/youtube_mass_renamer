var express = require('express');
var router = express.Router();

var http = require('http');
var https = require('https');
var fs = require('fs');

// ROUTES AND CODE

router.get('/', function (req, res) {
  res.send('Do authenticate');
});


var downloadFile = function(url, dest, cb) {
  var file = fs.createWriteStream(dest);
  var request = (url.substr(0, 8) == 'https://' ? https : http).get(url, function(response) {
    response.pipe(file);
    file.on('finish', function() {
      file.close(cb);  // close() is async, call cb after close completes.
    });
  }).on('error', function(err) { // Handle errors
    fs.unlink(dest); // Delete the file async. (But we don't check the result)
    if (cb) cb(err.message);
  });
};

function getThumbnailIfNotGotten(videoId, callback) {
  var thumbnailPath = './public/yt_data/' + videoId + '.jpg';

  // if thumbnail file DOESN'T exist -> DOWNLOAD
  if (!fs.existsSync(thumbnailPath)) {
    var url = 'https://i.ytimg.com/vi/' + videoId + '/hqdefault.jpg?custom=true&w=360&h=202&stc=true&jpg444=true&jpgq=90&sp=68';
    downloadFile(url, thumbnailPath, function (err) {
      // Thumbnail download failed! That's bad!
      // More advanced confirmation would use "calipers" and verify image width/height.
      if (err) {
        console.log("[!] ERROR! Thumbnail download failed! Err:", err);
        throw err;
      }

      return callback();
    });
  } else {
    // Thumbnail previously downloaded. Let's not spam youtube servers
    return callback();
  }
}

function getOpticalDataIfNotGotten(videoId, callback) {
  var txtFilePath = './public/yt_data/' + videoId + '.txt';

  // if text was not saved for this FETCH USING API
  if (!fs.existsSync(txtFilePath)) {
    // USE API...
    var foundText = 'test found text';
      fs.writeFileSync(foundText);
      return callback(foundText);
  } else {
    return callback(fs.readFileSync(txtFilePath));
  }

}

router.post('/yt_thumbnails_receive', function (req, res) {

  var videoJson = req.body;
  var idArr = Object.keys(req.body);
  var idCount = idArr.length;

  var retJson = {};

  console.log("[D] Received thumbnails data. Key count:", idCount);

// this.use('/public', express.static('public'));

  var countDone = 0;
  for (var i in videoJson) {
    getThumbnailIfNotGotten(i, function () {
      getOpticalDataIfNotGotten(i, function(foundText) {

        // set in json object and send back
        retJson[i] = {
          id: videoJson,
          textFound: foundText
        };

        if (++countDone == idCount) {
          return res.json(retJson);
        }

      });
    });
  }


});


module.exports = router;
