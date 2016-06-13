<?php
/**
* SMS Logger
*
* sms_logger
*
* @package MajorDoMo
* @author Eraser <eraser1981@.by> http://smartliving.ru/
* @version 1.0
*/
//
//
class sms_logger extends module {
/**
* Module class constructor
*
* @access private
*/
function sms_logger() {
  $this->name="sms_logger";
  $this->title="SMS Logger";
  $this->module_category="<#LANG_SECTION_APPLICATIONS#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->data_source)) {
  $p["data_source"]=$this->data_source;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $data_source;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($data_source)) {
   $this->data_source=$data_source;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['DATA_SOURCE']=$this->data_source;
  $out['TAB']=$this->tab;
  if ($this->single_rec) {
   $out['SINGLE_REC']=1;
  }
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='smslog' || $this->data_source=='') {
  if ($this->view_mode=='' || $this->view_mode=='search_smslog') {
   $this->search_smslog($out);
  }
  if ($this->view_mode=='edit_smslog') {
   $this->edit_smslog($out, $this->id);
  }
  if ($this->view_mode=='delete_smslog') {
   $this->delete_smslog($this->id);
   $this->redirect("?data_source=smslog");
  }
 }
 if ($this->data_source=='smsphones') {
  if ($this->view_mode=='' || $this->view_mode=='search_smsphones') {
   $this->search_smsphones($out);
  }
  if ($this->view_mode=='edit_smsphones') {
   $this->edit_smsphones($out, $this->id);
  }
  if ($this->view_mode=='delete_smsphones') {
   $this->delete_smsphones($this->id);
   $this->redirect("?data_source=smsphones");
  }
 }
 if ($this->data_source=='smsdevices') {
  if ($this->view_mode=='' || $this->view_mode=='search_smsdevices') {
   $this->search_smsdevices($out);
  }
  if ($this->view_mode=='edit_smsdevices') {
   $this->edit_smsdevices($out, $this->id);
  }
  if ($this->view_mode=='delete_smsdevices') {
   $this->delete_smsdevices($this->id);
   $this->redirect("?data_source=smsdevices");
  }
 }
 if ($this->data_source=='smsactions') {
  if ($this->view_mode=='' || $this->view_mode=='search_smsactions') {
   $this->search_smsactions($out);
  }
  if ($this->view_mode=='edit_smsactions') {
   $this->edit_smsactions($out, $this->id);
  }
  if ($this->view_mode=='delete_smsactions') {
   $this->delete_smsactions($this->id);
   $this->redirect("?data_source=smsactions");
  }
 }
}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 require(DIR_MODULES.$this->name.'/usual.inc.php');
}
/**
* smslog search
*
* @access public
*/
 function search_smslog(&$out) {
  require(DIR_MODULES.$this->name.'/smslog_search.inc.php');
 }
/**
* smslog edit/add
*
* @access public
*/
 function edit_smslog(&$out, $id) {
  require(DIR_MODULES.$this->name.'/smslog_edit.inc.php');
 }
/**
* smslog delete record
*
* @access public
*/
 function delete_smslog($id) {
  $rec=SQLSelectOne("SELECT * FROM sms_log WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM sms_log WHERE ID='".$rec['ID']."'");
 }
/**
* smsphones search
*
* @access public
*/
 function search_smsphones(&$out) {
  require(DIR_MODULES.$this->name.'/smsphones_search.inc.php');
 }
/**
* smsphones edit/add
*
* @access public
*/
 function edit_smsphones(&$out, $id) {
  require(DIR_MODULES.$this->name.'/smsphones_edit.inc.php');
 }
/**
* smsphones delete record
*
* @access public
*/
 function delete_smsphones($id) {
  $rec=SQLSelectOne("SELECT * FROM sms_phones WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM sms_actions WHERE PHONE_ID='".$rec['ID']."'");
  SQLExec("DELETE FROM sms_phones WHERE ID='".$rec['ID']."'");
  SQLExec("DELETE FROM sms_log WHERE PHONE_ID='".$rec['ID']."'");
 }
/**
* smsdevices search
*
* @access public
*/
 function search_smsdevices(&$out) {
  require(DIR_MODULES.$this->name.'/smsdevices_search.inc.php');
 }
/**
* smsdevices edit/add
*
* @access public
*/
 function edit_smsdevices(&$out, $id) {
  require(DIR_MODULES.$this->name.'/smsdevices_edit.inc.php');
 }
/**
* smsdevices delete record
*
* @access public
*/
 function delete_smsdevices($id) {
  $rec=SQLSelectOne("SELECT * FROM sms_devices WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM sms_log WHERE DEVICE_ID='".$rec['ID']."'");
  SQLExec("DELETE FROM sms_devices WHERE ID='".$rec['ID']."'");
 }
/**
* smsactions search
*
* @access public
*/
 function search_smsactions(&$out) {
  require(DIR_MODULES.$this->name.'/smsactions_search.inc.php');
 }
/**
* smsactions edit/add
*
* @access public
*/
 function edit_smsactions(&$out, $id) {
  require(DIR_MODULES.$this->name.'/smsactions_edit.inc.php');
 }
/**
* smsactions delete record
*
* @access public
*/
 function delete_smsactions($id) {
  $rec=SQLSelectOne("SELECT * FROM sms_actions WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM sms_actions WHERE ID='".$rec['ID']."'");
 }
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
  SQLExec('DROP TABLE IF EXISTS sms_log');
  SQLExec('DROP TABLE IF EXISTS sms_devices');
  SQLExec('DROP TABLE IF EXISTS sms_phones');
  SQLExec('DROP TABLE IF EXISTS sms_actions');
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data) {
/*
sms_log - Log
sms_devices - Devices
*/
  $data = <<<EOD
 sms_log: ID int(10) unsigned NOT NULL auto_increment
 sms_log: DEVICE_ID int(10) unsigned NOT NULL
 sms_log: PHONE_ID int(10) unsigned NOT NULL
 sms_log: ADDED datetime
 sms_log: TEXT varchar(255) NOT NULL DEFAULT ''
 sms_log: INDEX (DEVICE_ID)
 
 sms_devices: ID int(10) unsigned NOT NULL auto_increment
 sms_devices: TITLE varchar(255) NOT NULL DEFAULT ''
 sms_devices: ADDED datetime
 sms_devices: DEVICEID varchar(255) NOT NULL DEFAULT ''
 sms_devices: INDEX (DEVICEID)
 
 sms_phones: ID int(10) unsigned NOT NULL auto_increment
 sms_phones: TITLE varchar(255) NOT NULL DEFAULT ''
 sms_phones: ADDED datetime
 sms_phones: NUMBER varchar(255) NOT NULL DEFAULT ''
 sms_phones: INDEX (NUMBER)
 
 sms_actions: ID int(10) unsigned NOT NULL auto_increment
 sms_actions: TITLE varchar(255) NOT NULL DEFAULT ''
 sms_actions: ADDED datetime
 sms_actions: PHONE_ID int(10) unsigned NOT NULL
 sms_actions: NUMBER varchar(255) NOT NULL DEFAULT ''
 sms_actions: CODE text
 
EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgSnVsIDI1LCAyMDExIHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
?>