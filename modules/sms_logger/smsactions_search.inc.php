<?php
/*
* @version 0.1 (wizard)
*/
 global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $qry="1";
  if (IsSet($this->phone_id)) {
   $user_id=$this->phone_id;
   $qry.=" AND PHONE_ID='".$this->phone_id."'";
  } else {
   global $phone_id;
  }
  // QUERY READY
  global $save_qry;
  if ($save_qry) {
   $qry=$session->data['smsactions_qry'];
  } else {
   $session->data['smsactions_qry']=$qry;
  }
  if (!$qry) $qry="1";
  // FIELDS ORDER
  global $sortby_smsactions;
  if (!$sortby_smsactions) {
   $sortby_smsactions=$session->data['smsactions_sort'];
  } else {
   if ($session->data['smsactions_sort']==$sortby_smsactions) {
    if (Is_Integer(strpos($sortby_smsactions, ' DESC'))) {
     $sortby_smsactions=str_replace(' DESC', '', $sortby_smsactions);
    } else {
     $sortby_smsactions=$sortby_smsactions." DESC";
    }
   }
   $session->data['smsactions_sort']=$sortby_smsactions;
  }
  $sortby_smsactions=" sms_actions.TITLE";
  $out['SORTBY']=$sortby_smsactions;
  // SEARCH RESULTS
  $res=SQLSelect("SELECT sms_actions.*, sms_phones.TITLE as PHONE_TITLE FROM sms_actions LEFT JOIN sms_phones ON sms_phones.ID=sms_actions.PHONE_ID WHERE $qry ORDER BY ".$sortby_smsactions);
  if ($res[0]['ID']) {
   colorizeArray($res);
   $total=count($res);
   for($i=0;$i<$total;$i++) {
    // some action for every record if required
    $tmp=explode(' ', $res[$i]['EXECUTED']);
    $res[$i]['EXECUTED']=fromDBDate($tmp[0])." ".$tmp[1];
   }
   $out['RESULT']=$res;
  }
?>