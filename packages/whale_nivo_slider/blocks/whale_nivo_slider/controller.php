<?php   
/**
 * @author 		shahroq <shahroq \at\ yahoo.com>
 * @copyright  	Copyright (c) 2013 shahroq.
 * http://killerwhalesoft.com/c5/addons/
 */
defined('C5_EXECUTE') or die("Access Denied.");
class WhaleNivoSliderBlockController extends BlockController {
	
	protected $btTable = 'btWhaleNivoSlider';
	protected $btInterfaceWidth = "650";
	protected $btInterfaceHeight = "400";
	
	// Allow full caching
	// DEVNOTE: The cache may need to be cleared or the block resaved if
	// file titles/descriptions are changed.
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = true;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
	
	public $themes = array(0=>'default',1=>'dark',2=>'light',3=>'bar');  
	public $defaultTheme = 0;  // Slider Theme
	public $effects = array('random','fold','fade',
							'sliceDown','sliceDownLeft','sliceUp','sliceUpLeft','sliceUpDown',
							'sliceUpDownLeft','slideInRight','slideInLeft',
							'boxRandom','boxRain','boxRainReverse','boxRainGrow','boxRainGrowReverse');  
	public $defaultEffect = 0;  // Specify sets like: 'fold,fade,sliceDown'
	public $defaultSlices = 15; // For slice animations
	public $defaultBoxCols = 8; // For box animations
	public $defaultBoxRows = 4; // For box animations
	public $defaultAnimSpeed = 500; // Slide transition speed
	public $defaultPauseTime = 3000; // How long each slide will show
	public $defaultStartSlide = 0; // Set starting Slide (0 index)
	public $defaultDirectionNav = 1;//true; // Next & Prev navigation
    public $defaultControlNav = 1;//true; // 1,2,3... navigation
    public $defaultControlNavThumbs = 0;//false; // Use thumbnails for Control Nav
    public $defaultPauseOnHover = 1;//true; // Stop animation while hovering
    public $defaultManualAdvance = 0;//false; // Force manual transitions. 
    public $defaultPrevText = 'Prev'; // Prev directionNav text
    public $defaultNextText = 'Next'; // Next directionNav text
    public $defaultRandomStart = 0;//false; // Start on a random slide	

	public function getBlockTypeDescription() {
		return t("Whale Nivo Slider");
	}
	
	public function getBlockTypeName() {
		return t("Whale Nivo Slider");
	}
		
	public function getJavaScriptStrings() {
		return array(
			'choose-min-2' => t('Please choose at least two images.'),
			'no-image' => t('No images selected yet.')
		);
	}
	
	function __construct($obj = null) {		
		parent::__construct($obj);
	    
		$defaultPrevText = t('Prev'); // Prev directionNav text
	    $defaultNextText = t('Next'); // Next directionNav text
		
		//it read themes folder if there are any themes, other than default themes added to folder, comment it out if you don't want extra themes
		$this->getExtraThemes();
	}	

	function on_page_view(){
		$this->loadResources();
	}

    //load resource (js, css)
    private function loadResources(){
        $hh = Loader::helper('html');
        $pkg = BlockType::getByHandle($this->btHandle)->getPackageHandle();

        $theme_path = (isset($this->themes[$this->theme])) ? $this->themes[$this->theme].'/'.$this->themes[$this->theme].'.css' : 'default/default.css';
        $this->addHeaderItem($hh->css('nivo_themes/'.$theme_path, $pkg));
    }
	
	function view() {
		$this->set('pageID', $this->pageID);
		$this->setVariables();
		$this->getItems();
	}

	function add() {
		$this->includeUIElements();
		$this->setVariables();
		$this->getItems();
		$this->set('pageID', $this->pageID);
		$this->set('bID', $this->bID);		
		
	}
	
	function edit() {
		$this->includeUIElements();
		$this->setVariables();
		$this->getItems();
		$this->set('pageID', $this->pageID);
		$this->set('bID', $this->bID);		
	}
	
	function delete(){
		$db = Loader::db();
		$item_data = array( 
						  (int)$this->bID
						  );
		$db->query("DELETE FROM `btWhaleNivoSliderItems` WHERE bID=? ", $item_data);
		parent::delete();
	}
	
	function duplicate($nbID) {
       parent::duplicate($nbID);
       $this->getItems();
       $db = Loader::db();
       foreach($this->items as $item) {
         $db->Execute('INSERT INTO `btWhaleNivoSliderItems` (`bID`, `fID`, `itemTitle`, `itemDesc`, `itemUrl`, `position`) VALUES 
                  (?,?,?,?,?,?)', 
            array($nbID, $item['fID'], $item['itemTitle'], $item['itemDesc'], $item['itemURL'], $item['position'])
         );      
       }
    }	
	
	function save($data) {
		//safe inputs:
		$data['effect'] = (int)$data['effect'];
		$data['slices'] = (int)$data['slices'];
		$data['boxCols'] = (int)$data['boxCols'];
		$data['boxRows'] = (int)$data['boxRows'];
		$data['animSpeed'] = (int)$data['animSpeed'];
		$data['pauseTime'] = (int)$data['pauseTime'];
		$data['directionNav'] = $data['directionNav'] ? 1 : 0;
		$data['controlNav'] = $data['controlNav'] ? 1 : 0;
		$data['controlNavThumbs'] = $data['controlNavThumbs'] ? 1 : 0;
		$data['pauseOnHover'] = $data['pauseOnHover'] ? 1 : 0;
		$data['manualAdvance'] = $data['manualAdvance'] ? 1 : 0;
		$data['prevText'] = (string)substr(trim($data['prevText']), 0, 25);
		$data['nextText'] = (string)substr(trim($data['nextText']), 0, 25);
		$data['randomStart'] = $data['randomStart'] ? 1 : 0;
		$data['theme'] = (int)$data['theme'];

		parent::save($data);
			
		//save selected images at the child table ( btWhaleNivoSliderImg )
		$db = Loader::db();
		//delete existing images
		$item_data = array( 
						  (int)$this->bID
						  );
		$db->query("DELETE FROM `btWhaleNivoSliderItems` WHERE bID=?", $item_data);
		
		//loop through and add the images
		$pos=0;
		foreach($data['itemFIDs'] as $itemFID){ 
			if(intval($itemFID)==0 || $data['fileNames'][$pos]=='tempFilename') continue; //do not conside temp one
			//make inputs safe[in terms of length], befor insert to db
			$item_title = (string)substr(trim($data['itemTitle'][$pos]), 0, 255);
			$item_desc = (string)trim($data['itemDesc'][$pos]);
			$item_url = (string)substr(trim($data['itemUrl'][$pos]), 0, 255);
			$item_data = array( 
							  (int)$this->bID,
							  (int)$itemFID, 
							  $item_title,
							  $item_desc,
							  $item_url,
							  $pos
							  );
			$db->query("INSERT INTO `btWhaleNivoSliderItems` 
					   (`bID`, `fID`, `itemTitle`, `itemDesc`, `itemUrl`, `position`) VALUES 
					   (?,?,?,?,?,?)"
					   ,$item_data
					   );
			$pos++;
		}
	}

	private function getItems(){
		if(intval($this->bID) == 0) {
			$this->items = array();
			$this->set('items', $this->items);
			return;
		}
		$sql = sprintf("SELECT * FROM btWhaleNivoSliderItems WHERE bID=%d ORDER BY position", $this->bID);
		$db = Loader::db();
		$this->items = $db->getAll($sql); 
		
        //prepare images:
        foreach ($this->items as $key => $value) {
            //image:
            $f = File::getByID($value['fID']);
            $this->items[$key]['itemImageSrc'] = $f->getRelativePath();
            $this->items[$key]['itemImageAlt'] = (strlen($value['itemTitle'])>0)?$value['itemTitle']:$f->getTitle(); //image alt tag
            $this->items[$key]['itemImageTitle'] = (strlen($value['itemDesc'])>0)?$value['itemDesc']:$f->getDescription(); //image title tag
            //if you always want file title/desc attribute as image alt/title attribute, uncomment this two line 
            //$this->items[$key]['itemImageAlt'] = $f->getTitle(); //image alt tag
            //$this->items[$key]['itemImageTitle'] = $f->getDescription(); //image title tag
        }
		
		$this->set('items', $this->items);
		return;
	}
	/*
	* read the theme directory & check if any new themes added (other than default themes).
	*/
	private function getExtraThemes(){
        
        $hh = Loader::helper('html');
        
        $dir = DIR_PACKAGES.'/whale_nivo_slider/css/nivo_themes/';

		if (is_dir($dir)){
        	$contents = scandir($dir);
	        $bad = array(".", "..", ".DS_Store", "_notes", "Thumbs.db");
	        $files = array_diff($contents, $bad);
			foreach($files as $value){
				if(is_dir($dir.$value) && !in_array($value, $this->themes) ) $this->themes[] = $value;
			}
    	}
	}

	private function setVariables(){
		$this->set('themes', $this->themes);	
		$this->set('effects', $this->effects);
		
		$this->set('theme', isset($this->theme)?$this->theme:$this->defaultTheme);	
		$this->set('theme_title', isset($this->themes[$this->theme])?$this->themes[$this->theme]:'default');
		
		$this->set('effect', isset($this->effect)?$this->effect:$this->defaultEffect);	
		$this->set('slices', isset($this->slices)?$this->slices:$this->defaultSlices);	
		$this->set('boxCols', isset($this->boxCols)?$this->boxCols:$this->defaultBoxCols);	
		$this->set('boxRows', isset($this->boxRows)?$this->boxRows:$this->defaultBoxRows);	
		$this->set('animSpeed', isset($this->animSpeed)?$this->animSpeed:$this->defaultAnimSpeed);	
		$this->set('pauseTime', isset($this->pauseTime)?$this->pauseTime:$this->defaultPauseTime);	
		$this->set('startSlide', isset($this->startSlide)?$this->startSlide:$this->defaultStartSlide);	

		$this->set('directionNav', isset($this->directionNav)?$this->directionNav:$this->defaultDirectionNav);	
		$this->set('controlNav', isset($this->controlNav)?$this->controlNav:$this->defaultControlNav);	
		$this->set('controlNavThumbs', isset($this->controlNavThumbs)?$this->controlNavThumbs:$this->defaultControlNavThumbs);	
		$this->set('pauseOnHover', isset($this->pauseOnHover)?$this->pauseOnHover:$this->defaultPauseOnHover);	
		$this->set('manualAdvance', isset($this->manualAdvance)?$this->manualAdvance:$this->defaultManualAdvance);	
		$this->set('prevText', isset($this->prevText)?$this->prevText:$this->defaultPrevText);	
		$this->set('nextText', isset($this->nextText)?$this->nextText:$this->defaultNextText);	
		$this->set('randomStart', isset($this->randomStart)?$this->randomStart:$this->defaultRandomStart);

		//$action_ajax_fill_data
		$tool_helper = Loader::helper('concrete/urls');
		$bt = BlockType::getByHandle($this->btHandle);
		$this->set ('action_ajax_fill_data', $tool_helper->getBlockTypeToolsURL($bt).'/action_ajax_fill_data');
	}
	/**
	 * Loads required assets and variables when in edit or add mode.
	 * Called by edit() and add()
	 */
	private function includeUIElements() {
		
	}
	
}

?>
