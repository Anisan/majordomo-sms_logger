<?php
/*
* @version 0.1 (wizard)
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='sms_actions';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if ($this->mode=='update') {
   $ok=1;
  //updating 'PHONE_ID' (select)
   if (IsSet($this->phone_id)) {
    $rec['PHONE_ID']=$this->user_id;
   } else {
   global $phone_id;
   $rec['PHONE_ID']=$phone_id;
   }

   global $title;
   $rec['TITLE']=$title;
  //updating 'CODE' (text)
   global $code;
   $rec['CODE']=$code;
/*
  //updating 'LOG' (text)
   global $log;
   $rec['LOG']=$log;
  //updating 'EXECUTED' (datetime)
   global $executed_date;
   global $executed_minutes;
   global $executed_hours;
   $rec['EXECUTED']=toDBDate($executed_date)." $executed_hours:$executed_minutes:00";
*/
  //UPDATING RECORD
   if ($ok) {
    if ($rec['ID']) {
     SQLUpdate($table_name, $rec); // update
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
    }
    $out['OK']=1;
   } else {
    $out['ERR']=1;
   }
  }
  //options for 'PHONE_ID' (select)
  $tmp=SQLSelect("SELECT ID, TITLE FROM sms_phones ORDER BY TITLE");
  $users_total=count($tmp);
  for($users_i=0;$users_i<$users_total;$users_i++) {
   $user_id_opt[$tmp[$users_i]['ID']]=$tmp[$users_i]['TITLE'];
  }
  for($i=0;$i<$users_total;$i++) {
   if ($rec['PHONE_ID']==$tmp[$i]['ID']) $tmp[$i]['SELECTED']=1;
  }
  $out['PHONE_ID_OPTIONS']=$tmp;
  
  if ($rec['EXECUTED']!='') {
   $tmp=explode(' ', $rec['EXECUTED']);
   $out['EXECUTED_DATE']=fromDBDate($tmp[0]);
   $tmp2=explode(':', $tmp[1]);
   $executed_hours=$tmp2[0];
   $executed_minutes=$tmp2[1];
  }
  for($i=0;$i<60;$i++) {
   $title=$i;
   if ($i<10) $title="0$i";
   if ($title==$executed_minutes) {
    $out['EXECUTED_MINUTES'][]=array('TITLE'=>$title, 'SELECTED'=>1);
   } else {
    $out['EXECUTED_MINUTES'][]=array('TITLE'=>$title);
   }
  }
  for($i=0;$i<24;$i++) {
   $title=$i;
   if ($i<10) $title="0$i";
   if ($title==$executed_hours) {
    $out['EXECUTED_HOURS'][]=array('TITLE'=>$title, 'SELECTED'=>1);
   } else {
    $out['EXECUTED_HOURS'][]=array('TITLE'=>$title);
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