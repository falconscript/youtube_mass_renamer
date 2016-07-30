var express = require('express');
var router = express.Router();

// ROUTES AND CODE

router.get('/', function (req, res) {
  res.send('Do authenticate');
});

router.post('/yt_thumbnails_receive', function (req, res) {
  var videoJson = req.body;
  console.log("[D] Received thumbnails data. Key count:", Object.keys(req.body).length);


  var json = {};

  for(var i in videoJson) {
    json[i] = videoJson[i];//videoJson[i] ...
  }




  res.json(json);
});

router.post('/changenames', function (req, res) {
  res.send('Got a PUT request at /user');
});





module.exports = router;
