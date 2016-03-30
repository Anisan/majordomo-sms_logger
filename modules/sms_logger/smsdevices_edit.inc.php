<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='sms_devices';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if ($this->mode=='update') {
   $ok=1;
  //updating 'TITLE' (varchar, required)
   global $title;
   $rec['TITLE']=$title;
   if ($rec['TITLE']=='') {
    $out['ERR_TITLE']=1;
    $ok=0;
   }

  //updating 'DEVICEID' (varchar)
   global $deviceid;
   $rec['DEVICEID']=$deviceid;
   if ($rec['DEVICEID']=='') {
    $out['ERR_Device']=1;
    $ok=0;
   }

  //UPDATING RECORD
   if ($ok) {
    if ($rec['ID']) {
     SQLUpdate($table_name, $rec); // update
    } else {
     $rec['ADDED'] = date('Y-m-d H:i:s');
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
    }

    $out['OK']=1;

   } else {
    $out['ERR']=1;
   }
  }

  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
?>