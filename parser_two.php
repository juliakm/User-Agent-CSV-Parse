<?php
//Load UA Parser Library Installed with Composer
//GitHub: https://github.com/tobie/ua-parser 
require_once 'vendor/autoload.php';
use UAParser\Parser;


//Take a User Agent string and return the type - Desktop, Spider, Mobile, Tablet
function detectAgent($ua) {
 
  //List of mobile browsers, tablets, and mobile devices
  //Browser list source UA Classifier project: https://bitbucket.org/Jarnar/ua-classifier
  $mobileOSs     = array('windows phone 6.5','windows ce','symbian os');
  $mobileBrowsers  = array('firefox mobile','opera mobile','opera mini','mobile safari','webos','ie mobile','playstation portable',
               'nokia','blackberry','palm','silk','android','maemo','obigo','netfront','avantgo','teleca','semc-browser',
               'bolt','iris','up.browser','symphony','minimo','bunjaloo','jasmine','dolfin','polaris','brew','chrome mobile',
               'uc browser','tizen browser');
  $tablets     = array('kindle','ipad','playbook','touchpad','dell streak','galaxy tab','xoom');
  $mobileDevices   = array('iphone','ipod','ipad','htc','kindle','lumia','amoi','asus','bird','dell','docomo','huawei','i-mate','kyocera',
               'lenovo','lg','kin','motorola','philips','samsung','softbank','palm','hp ','generic feature phone','generic smartphone');


  unset($parser); //unsetting if previously set, TODO: Identify in this is needed
	
  //Instantiate the parser object and build result
  $parser = Parser::create();
	$result = $parser->parse($ua);

    //Identify devices with the other device family
    if(strtolower($result->device->family) == "other") { 
      $deviceType = 'Desktop';      
    }

    //Identify devices with the spider device family
    if(strtolower($result->device->family) == 'spider')
    {
      $deviceType = 'Spider';
    }

    //Identify devices with the Mobile device family    
    if((in_array(strtolower($result->ua->family), $mobileBrowsers)) || in_array(strtolower($result->os->family), $mobileOSs) || in_array(strtolower($result->device->family), $mobileDevices))
    {
      $deviceType = 'Mobile';
    }
   
    //Identify devices with the Tablet device family
    if(in_array(strtolower($result->device->family), $tablets))
    {
      $deviceType = 'Tablet';
    }

	return $deviceType;
}


//Set input and output files
$input_file = "test.csv";
$output_file = "output.csv";

//Grab column 2, row by row. 
//Run column 2 through detectAgent function and write to column 3
//Reference: http://stackoverflow.com/questions/25910987/modify-a-csv-file-line-by-line
if (($handle1 = fopen($input_file, "r")) !== FALSE) {
    if (($handle2 = fopen($output_file, "w")) !== FALSE) {
        while (($data = fgetcsv($handle1, 5000000, ",")) !== FALSE) {
           $data[2] = detectAgent($data[1]); //identify browser family
                // print_r($data[2]) . "<br />"; //test code

           // Write back to CSV format
           fputcsv($handle2, $data);
        }
        fclose($handle2);
    }
    fclose($handle1);
}
