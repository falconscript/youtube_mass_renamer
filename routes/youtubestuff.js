var express = require('express');
var router = express.Router();

// ROUTES AND CODE

router.get('/', function (req, res) {
  res.send('Do authenticate');
});

router.get('/videos', function (req, res) {
  res.send('Got a POST request');
});

router.post('/changenames', function (req, res) {
  res.send('Got a PUT request at /user');
});





module.exports = router;
