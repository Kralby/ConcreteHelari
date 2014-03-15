<?php    defined('C5_EXECUTE') or die(_("Access Denied."));
 
$im = Loader::helper('image');
 
$f = File::getByID($fID); 
$fv = $f->getApprovedVersion();
$path = $fv->getURL(); 
$linkURL = $controller->getLinkURL();
$title = $fv->getTitle();
$width= $maxWidth;
$height = $maxHeight;
 ?>  
 
   <div class="cadre">
   <img class="radius" src="<?php echo $path;?>" title="<?php    echo $title;?>"/>
   </div>

