<?php   
defined('C5_EXECUTE') or die("Access Denied.");

$fp = FilePermissions::getGlobal();
if (!$fp->canAccessFileManager()) {
	die(t("Access Denied."));
}

	$fIds = isset($_GET['fIds'])? explode ( ',' , $_GET['fIds'] ):array();
	$infos = array();
	foreach($fIds as $value){
		$f = File::getByID($value);
		if($f){
			$obj = new stdClass;
			$obj->imageTitle = $f->getApprovedVersion()->getTitle();
			$obj->imageDesc = $f->getApprovedVersion()->getDescription();
			$infos[] = $obj;
		}
	}
	$js = Loader::helper('json');
	print $js->encode($infos);
	exit;	
