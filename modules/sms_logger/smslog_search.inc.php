<?php
/*
* @version 0.2 (wizard)
*/

 global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $qry="1";
  // search filters
  global $device_id;
  if ($device_id) {
   $qry.=" AND DEVICE_ID='".(int)$device_id."'";
   $out['DEVICE_ID']=(int)$device_id;
  } 
  
  global $phone_id;
  if ($phone_id) {
   $qry.=" AND PHONE_ID='".(int)$phone_id."'";
   $out['PHONE_ID']=(int)$phone_id;
  } 
  // QUERY READY
  global $save_qry;
  if ($save_qry) {
   $qry=$session->data['sms_log_qry'];
  } else {
   $session->data['sms_log_qry']=$qry;
  }
  if (!$qry) $qry="1";
  // FIELDS ORDER
  global $sortby_sms_log;
  if (!$sortby_sms_log) {
   $sortby_sms_log=$session->data['sms_log_sort'];
  } else {
   if ($session->data['sms_log_sort']==$sortby_sms_log) {
    if (Is_Integer(strpos($sortby_sms_log, ' DESC'))) {
     $sortby_sms_log=str_replace(' DESC', '', $sortby_sms_log);
    } else {
     $sortby_sms_log=$sortby_sms_log." DESC";
    }
   }
   $session->data['sms_log_sort']=$sortby_sms_log;
  }
  if (!$sortby_sms_log) $sortby_sms_log="sms_log.ID DESC";
  $out['SORTBY']=$sortby_sms_log;
  // SEARCH RESULTS
  $res=SQLSelect("SELECT sms_log.*, sms_devices.TITLE as DEVICE_TITLE, sms_phones.TITLE as PHONE_TITLE FROM sms_log LEFT JOIN sms_devices ON sms_devices.ID=sms_log.DEVICE_ID LEFT JOIN sms_phones ON sms_phones.ID=sms_log.PHONE_ID WHERE  $qry ORDER BY ".$sortby_sms_log);
  if ($res[0]['ID']) {
   paging($res, 50, $out); // search result paging
   colorizeArray($res);
   $total=count($res);
   for($i=0;$i<$total;$i++) {
    // some action for every record if required
    $tmp=explode(' ', $res[$i]['ADDED']);
    $res[$i]['ADDED']=fromDBDate($tmp[0])." ".$tmp[1];
   }
   $out['RESULT']=$res;
  }
  $out['DEVICE_ID_OPTIONS']=SQLSelect("SELECT * FROM sms_devices ORDER BY TITLE");
  $out['PHONE_ID_OPTIONS']=SQLSelect("SELECT * FROM sms_phones ORDER BY TITLE");
?>