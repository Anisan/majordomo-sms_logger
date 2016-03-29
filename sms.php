<?php
/**
* Main project script
*
* @author Eraser <eraser1981@gmail.com>
* @version 1.0
*/

include_once("./config.php");
include_once("./lib/loader.php");

// start calculation of execution time
startMeasure('SMS'); 

include_once(DIR_MODULES."application.class.php");

$session = new session("prj");

// connecting to database
$db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); 

include_once("./load_settings.php");


$deviceid=$_REQUEST['id'];
$device = SQLSelectOne("SELECT * FROM sms_devices WHERE DEVICEID='" . DBSafe($_REQUEST['id']) . "'");
if (!$device['ID']) 
{
    $device = array();
    $device['DEVICEID'] = $_REQUEST['id'];
    $device['TITLE']    = 'New SMS Device';
    $device['ADDED'] = date('Y-m-d H:i:s');
    $device['ID']       = SQLInsert('sms_devices', $device);
}

$time = date('Y-m-d H:i:s');
if ($_REQUEST['time']) 
   $time = $_REQUEST['time'];

$rec=array();
$rec['ADDED']    = $time;
$rec['PHONE']    = $_REQUEST['phone'];
$rec['TEXT']     = $_REQUEST['text'];
$rec['DEVICEID'] = $_REQUEST['id'];
$rec['DEVICE_ID']= $device['ID'];
$rec['ID']=SQLInsert('sms_log', $rec);

if (!headers_sent()) 
{
   header("HTTP/1.0: 200 OK\n");
   header('Content-Type: text/html; charset=utf-8');
}
echo "OK";

// closing database connection
$db->Disconnect(); 

endMeasure('SMS'); // end calculation of execution time

?>
