<?php

        /*

           Sample project for OCRWebService.com (REST API).
           Extract text from scanned images and convert into editable formats.
           Please create new account with ocrwebservice.com via http://www.ocrwebservice.com/account/signup and get license code

        */

        // Provide your user name and license code
        $license_code = "1CDDF196-6FE1-4A8A-BA5C-190708AFB7D8";
        $username =  "freshfalcon";

        /*

           You should specify OCR settings. See full description http://www.ocrwebservice.com/service/restguide

           Input parameters:

	   [language]     - Specifies the recognition language.
	   		    This parameter can contain several language names separated with commas.
                            For example "language=english,german,spanish".
			    Optional parameter. By default:english

	   [pagerange]    - Enter page numbers and/or page ranges separated by commas.
			    For example "pagerange=1,3,5-12" or "pagerange=allpages".
                            Optional parameter. By default:allpages

           [tobw]	  - Convert image to black and white (recommend for color image and photo).
			    For example "tobw=false"
                            Optional parameter. By default:false

           [zone]         - Specifies the region on the image for zonal OCR.
			    The coordinates in pixels relative to the left top corner in the following format: top:left:height:width.
			    This parameter can contain several zones separated with commas.
		            For example "zone=0:0:100:100,50:50:50:50"
                            Optional parameter.

           [outputformat] - Specifies the output file format.
                            Can be specified up to two output formats, separated with commas.
			    For example "outputformat=pdf,txt"
                            Optional parameter. By default:doc

           [gettext]	  - Specifies that extracted text will be returned.
			    For example "tobw=true"
                            Optional parameter. By default:false

           [description]  - Specifies your task description. Will be returned in response.
                            Optional parameter.


	   !!!!  For getting result you must specify "gettext" or "outputformat" !!!!

	*/


        // Build your OCR:

        // Extraction text with English language
        $url = 'http://www.ocrwebservice.com/restservices/processDocument?gettext=true';

        // Extraction text with English and german language using zonal OCR
        // $url = 'http://www.ocrwebservice.com/restservices/processDocument?language=english,german&zone=0:0:600:400,500:1000:150:400';

        // Convert first 5 pages of multipage document into doc and txt
        // $url = 'http://www.ocrwebservice.com/restservices/processDocument?language=english&pagerange=1-5&outputformat=doc,txt';


        // Full path to uploaded document
        $filePath = '/Users/jeff/Desktop/hqdefault.jpg';

        $fp = fopen($filePath, 'r');
        $session = curl_init();

        curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_USERPWD, "$username:$license_code");

        curl_setopt($session, CURLOPT_UPLOAD, true);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($session, CURLOPT_TIMEOUT, 200);
        curl_setopt($session, CURLOPT_HEADER, false);


        // For SSL using
        //curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);

        // Specify Response format to JSON or XML (application/json or application/xml)
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        curl_setopt($session, CURLOPT_INFILE, $fp);
        curl_setopt($session, CURLOPT_INFILESIZE, filesize($filePath));

        $result = curl_exec($session);

  	$httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        fclose($fp);

        if($httpCode == 401)
	{
           // Please provide valid username and license code
           die('Unauthorized request');
        }

        // Output response
	$data = json_decode($result);

        if($httpCode != 200)
	{
	   // OCR error
           die($data->ErrorMessage);
        }

        // Task description
	echo 'TaskDescription:'.$data->TaskDescription."\r\n";

        // Available pages
	echo 'AvailablePages:'.$data->AvailablePages."\r\n";

        // Extracted text
        echo 'OCRText='.$data->OCRText[0][0]."\r\n";

        // For zonal OCR: OCRText[z][p]    z - zone, p - pages

        // Get First zone from each page
        //echo 'OCRText[0][0]='.$data->OCRText[0][0]."\r\n";
        //echo 'OCRText[0][1]='.$data->OCRText[0][1]."\r\n";


        // Get second zone from each page
        //echo 'OCRText[1][0]='.$data->OCRText[1][0]."\r\n";
        //echo 'OCRText[1][1]='.$data->OCRText[1][1]."\r\n";


        // Download output file (if outputformat was specified)

        //$url = $data->OutputFileUrl;
        //$content = file_get_contents($url);
        //file_put_contents('converted_document.doc', $content);

        // End recognition

?>
