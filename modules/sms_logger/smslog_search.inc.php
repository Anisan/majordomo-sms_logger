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
  if (IsSet($this->device_id)) {
   $device_id=$this->device_id;
   $qry.=" AND DEVICE_ID='".$this->device_id."'";
  } else {
   global $device_id;
  }
  if (IsSet($this->location_id)) {
   $location_id=$this->location_id;
   $qry.=" AND LOCATION_ID='".$this->location_id."'";
  } else {
   global $location_id;
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
  $res=SQLSelect("SELECT sms_log.*, sms_devices.TITLE as DEVICE_TITLE FROM sms_log LEFT JOIN sms_devices ON sms_devices.ID=sms_log.DEVICE_ID WHERE  $qry ORDER BY ".$sortby_sms_log);
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
?>