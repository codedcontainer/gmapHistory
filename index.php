<?php 
// Today's time is needed to get the bounding for the request
$currentTime = time();
$currentDate = date('m_d_y'); //01_02_15.xml
echo $currentDate;
// Beginning and end of the day
$startTime = strtotime("midnight", $currentTime);
$endTime = strtotime("tomorrow", $startTime);
 
// The Google Location history URL - gets the KML.
$locationURL = "https://maps.google.com/locationhistory/b/1/kml?startTime=" .
     $startTime . "000&endTime=" .
     $endTime . "000";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $locationURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
 
$kml = curl_exec($ch);

$location = "data/";

//write the data to an xml file on the server
$xmlFile = fopen($location.$currentDate.'.xml', 'w');
//get all of the information from the file and add into the database
fwrite($xmlFile, $kml);
curl_close($ch);

$hostname = "";
$username = "";
$dbname = "";
$password = "";

mysql_connect($hostname, $username, $password); 
mysql_select_db($dbname); 

$simpleXml = simplexml_load_file($location.$currentDate.'.xml');
$latLong = $simpleXml->Document->Placemark->xpath('//gx:coord');
$times = $simpleXml->Document->Placemark->xpath('//gx:Track');
$latLongs = array();
$dates = array(); 
 //push all dates into new above array 
foreach ($times[0]->when as $when)
{
	array_push($dates, $when);
}
// push all latlongs in new array 
foreach ($latLong as $lat)
{
	//echo $lat."<br/>";
	$parts = preg_split('/\s+/', $lat);
	$parts[0]; //long
	$parts[1]; //lat 
	$fullCord = $parts[1].' '.$parts[0];
	array_push($latLongs, $fullCord);
}

//connect to newly created database for google location data
//close the curl

$TableDetails;
$table = 'CREATE TABLE IF NOT EXISTS '.$currentDate.'(
id INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
time text,
coords text )';
mysql_query($table) or die(mysql_error() );
//foreach dates and foreach times into new database for storages

foreach( $dates as $key => $date)
{
	$insert = "INSERT INTO `".$currentDate."` VALUES('','".$date."',"."'".$latLongs[$key]."')";
	mysql_query($insert) or die(mysql_error() );
}

$numberofItems = count($dates);
if ($numberofItems > 0) { $working = 'Success'; }
else { $working = 'Failure'; }
mail ( );


 ?>
