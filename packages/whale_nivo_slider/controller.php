<?php     
/**
 * @author 		shahroq <shahroq \at\ yahoo.com>
 * @copyright  	Copyright (c) 2013 shahroq.
 * http://killerwhalesoft.com/c5/addons/
 */
defined('C5_EXECUTE') or die(_("Access Denied."));

class WhaleNivoSliderPackage extends Package {

	protected $pkgHandle = 'whale_nivo_slider';
    protected $appVersionRequired = '5.5.0';
    protected $pkgVersion = '1.2';
    
	public function getPackageDescription() {
    	return t("Whale Image Slider based on jQuery Nivo Slider");
    }

    public function getPackageName() {
    	return t("Whale Nivo Image Slider");
    }

    public function install() {
    	$pkg = parent::install();
	    // install block
        BlockType::installBlockTypeFromPackage('whale_nivo_slider', $pkg);
    }   

	public function uninstall() {
		parent::uninstall();
		
		//drop tables
		$db = Loader::db();
		$db->Execute('DROP TABLE IF EXISTS `btWhaleNivoSlider`');
		$db->Execute('DROP TABLE IF EXISTS `btWhaleNivoSliderItems`');

	} 
	
}
?>
