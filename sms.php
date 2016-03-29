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

$phone = SQLSelectOne("SELECT * FROM sms_phones WHERE NUMBER='" . DBSafe($_REQUEST['phone']) . "'");
if (!$phone['ID']) 
{
    $phone = array();
    $phone['NUMBER'] = $_REQUEST['phone'];
    $phone['TITLE']  = $_REQUEST['phone'];
    $phone['ADDED']  = date('Y-m-d H:i:s');
    $phone['ID']     = SQLInsert('sms_phones', $phone);
}

$time = date('Y-m-d H:i:s');
if ($_REQUEST['time']) 
   $time = $_REQUEST['time'];

$text = $_REQUEST['text'];

$rec=array();
$rec['ADDED']    = $time;
$rec['DEVICE_ID']= $device['ID'];
$rec['PHONE_ID'] = $phone['ID'];
$rec['TEXT']     = $text;
$rec['DEVICEID'] = $deviceid;
$rec['ID']=SQLInsert('sms_log', $rec);

// work action
$recs=SQLSelect("SELECT * FROM sms_actions where PHONE_ID=".$phone['ID']." order by ID;");  
 foreach($recs as $action) {      
    if ($action['CODE'])
    {
        try {
            $success = eval($action['CODE']);
            echo  date("Y-m-d H:i:s ")." Action:".$action['TITLE']." Result:".$success."\n";
        } catch (Exception $e) {
            registerError('sms_logger', sprintf('Exception in "%s" method '.$e->getMessage(), $text));
        }
        $action['EXECUTED'] = date('Y-m-d H:i:s');
        SQLUpdate('sms_actions', $action);
    }
}    


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
