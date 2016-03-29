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
  if (IsSet($this->device_id)) {
   $out['IS_SET_DEVICE_ID']=1;
  }
  if (IsSet($this->location_id)) {
   $out['IS_SET_LOCATION_ID']=1;
  }
  if (IsSet($this->user_id)) {
   $out['IS_SET_USER_ID']=1;
  }
  if (IsSet($this->location_id)) {
   $out['IS_SET_LOCATION_ID']=1;
  }
  if (IsSet($this->user_id)) {
   $out['IS_SET_USER_ID']=1;
  }
  if (IsSet($this->script_id)) {
   $out['IS_SET_SCRIPT_ID']=1;
  }
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
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='gpslocations') {
  if ($this->view_mode=='' || $this->view_mode=='search_gpslocations') {
   $this->search_gpslocations($out);
  }
  if ($this->view_mode=='edit_gpslocations') {
   $this->edit_gpslocations($out, $this->id);
  }
  if ($this->view_mode=='delete_gpslocations') {
   $this->delete_gpslocations($this->id);
   $this->redirect("?data_source=gpslocations");
  }
 }
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='gpsdevices') {
  if ($this->view_mode=='' || $this->view_mode=='search_gpsdevices') {
   $this->search_gpsdevices($out);
  }
  if ($this->view_mode=='edit_gpsdevices') {
   $this->edit_gpsdevices($out, $this->id);
  }
  if ($this->view_mode=='delete_gpsdevices') {
   $this->delete_gpsdevices($this->id);
   $this->redirect("?data_source=gpsdevices");
  }
 }
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='gpsactions') {
  if ($this->view_mode=='' || $this->view_mode=='search_gpsactions') {
   $this->search_gpsactions($out);
  }
  if ($this->view_mode=='edit_gpsactions') {
   $this->edit_gpsactions($out, $this->id);
  }
  if ($this->view_mode=='delete_gpsactions') {
   $this->delete_gpsactions($this->id);
   $this->redirect("?data_source=gpsactions");
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
* gpslocations search
*
* @access public
*/
 function search_gpslocations(&$out) {
  require(DIR_MODULES.$this->name.'/gpslocations_search.inc.php');
 }
/**
* gpslocations edit/add
*
* @access public
*/
 function edit_gpslocations(&$out, $id) {
  require(DIR_MODULES.$this->name.'/gpslocations_edit.inc.php');
 }
/**
* gpslocations delete record
*
* @access public
*/
 function delete_gpslocations($id) {
  $rec=SQLSelectOne("SELECT * FROM gpslocations WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM gpslocations WHERE ID='".$rec['ID']."'");
 }
/**
* gpsdevices search
*
* @access public
*/
 function search_gpsdevices(&$out) {
  require(DIR_MODULES.$this->name.'/gpsdevices_search.inc.php');
 }
/**
* gpsdevices edit/add
*
* @access public
*/
 function edit_gpsdevices(&$out, $id) {
  require(DIR_MODULES.$this->name.'/gpsdevices_edit.inc.php');
 }
/**
* gpsdevices delete record
*
* @access public
*/
 function delete_gpsdevices($id) {
  $rec=SQLSelectOne("SELECT * FROM gpsdevices WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM smslog WHERE DEVICE_ID='".$rec['ID']."'");
  SQLExec("DELETE FROM gpsdevices WHERE ID='".$rec['ID']."'");
 }
/**
* gpsactions search
*
* @access public
*/
 function search_gpsactions(&$out) {
  require(DIR_MODULES.$this->name.'/gpsactions_search.inc.php');
 }
/**
* gpsactions edit/add
*
* @access public
*/
 function edit_gpsactions(&$out, $id) {
  require(DIR_MODULES.$this->name.'/gpsactions_edit.inc.php');
 }
/**
* gpsactions delete record
*
* @access public
*/
 function delete_gpsactions($id) {
  $rec=SQLSelectOne("SELECT * FROM gpsactions WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM gpsactions WHERE ID='".$rec['ID']."'");
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
 sms_log: ADDED datetime
 sms_log: PHONE varchar(30) NOT NULL DEFAULT ''
 sms_log: TEXT varchar(255) NOT NULL DEFAULT ''
 sms_log: INDEX (DEVICE_ID)
 
 sms_devices: ID int(10) unsigned NOT NULL auto_increment
 sms_devices: TITLE varchar(255) NOT NULL DEFAULT ''
 sms_devices: ADDED datetime
 sms_devices: DEVICEID varchar(255) NOT NULL DEFAULT ''
 sms_devices: INDEX (DEVICEID)
 
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