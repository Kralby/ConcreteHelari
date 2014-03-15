<?php  
/**
 * @author 		shahroq <shahroq \at\ yahoo.com>
 * @copyright  	Copyright (c) 2013 shahroq.
 * http://killerwhalesoft.com/c5/addons/
 */
defined('C5_EXECUTE') or die("Access Denied.");
$ah = Loader::helper('concrete/interface');
?>

<style type="text/css">
#ccm-slideshowBlock-itemRows a{cursor:pointer}
#ccm-slideshowBlock-itemRows .ccm-slideshowBlock-itemRow,
#ccm-slideshowBlock-fsRow {margin-bottom:10px;clear:both;padding:7px;background-color:#eee; border-radius: 4px; background-color: #F7F7F9; border: 1px solid #E1E1E8;}
#ccm-slideshowBlock-itemRows .ccm-slideshowBlock-itemRow a.moveUpLink{ display:block; background:url(<?php   echo ASSETS_URL_IMAGES ?>/icons/arrow_up.png) no-repeat center; height:10px; width:16px; }
#ccm-slideshowBlock-itemRows .ccm-slideshowBlock-itemRow a.moveDownLink{ display:block; background:url(<?php   echo ASSETS_URL_IMAGES ?>/icons/arrow_down.png) no-repeat center; height:10px; width:16px; }
#ccm-slideshowBlock-itemRows .ccm-slideshowBlock-itemRow a.moveUpLink:hover{background:url(<?php   echo ASSETS_URL_IMAGES ?>/icons/arrow_up_black.png) no-repeat center;}
#ccm-slideshowBlock-itemRows .ccm-slideshowBlock-itemRow a.moveDownLink:hover{background:url(<?php   echo ASSETS_URL_IMAGES ?>/icons/arrow_down_black.png) no-repeat center;}
#ccm-slideshowBlock-itemRows .cm-slideshowBlock-itemRowIcons{ float:right; width:35px; text-align:left; }
#ccm-slideshowBlock-chooseItem{}
</style>

<!-- Begin: Tabs -->
<ul id="ccm-slider-tabs" class="ccm-dialog-tabs">
	<li class="ccm-nav-active"><a id="ccm-slider-tab-items" href="javascript:void(0);"><?php  echo t('Images')?></a></li>
	<li class=""><a id="ccm-slider-tab-options"  href="javascript:void(0);"><?php  echo t('Options')?></a></li>
	<li class=""><a id="ccm-slider-tab-design"  href="javascript:void(0);"><?php  echo t('Design')?></a></li>
</ul>
<!-- End: Tabs -->
	
<div class="whale-nivo-slider-ui ccm-ui" style="">
    <!-- Begin: Items Tab -->
	<div class="ccm-sliderPane" id="ccm-sliderPane-items">
          <div class="clearfix" style="width:99%; margin-bottom:10px;">
	            <div id="ccm-slideshowBlock-chooseItem"><?php   echo $ah->button_js(t('Add Image'), 'SlideshowBlock.chooseItem()', 'right', $innerClass='btn-info');?></div>
	            <div id="ccm-slideshowBlock-fillTitles">
                    <?php   echo $ah->button_js(t('T'), 
											  'SlideshowBlock.fillData(this)', 
											  'left', 
											  $innerClass='btn-info launch-tooltip',
											  $args = array('title'=>t('Fetch selected image title attribute'), 'href'=>$action_ajax_fill_data, 'typ'=>'t')
											  );
					?>
                    <?php   echo $ah->button_js(t('D'), 
											  'SlideshowBlock.fillData(this)', 
											  'left', 
											  $innerClass='btn-info launch-tooltip',
											  $args = array('title'=>t('Fetch selected image description attribute'), 'href'=>$action_ajax_fill_data, 'typ'=>'d')
											  );
					?>
                </div>
          </div>
          <div class="clearfix" style="width:99%">
                <div id="ccm-slideshowBlock-itemRows">
	            <?php 
                foreach($items as $itemInfo){ 
                    $f = File::getByID($itemInfo['fID']);
                    $fp = new Permissions($f);
                    $itemInfo['thumbPath'] = $f->getThumbnailSRC(1);
                    $itemInfo['fileName'] = $f->getTitle();
                    if ($fp->canRead()) { 
                        $this->inc('elements/item_row.php', array('itemInfo' => $itemInfo));
                    }
                }
            	?>
		        </div>
          </div>      
    </div>
    <!-- End: Items Tab -->

    <!-- Begin: Options Tab -->
	<div class="ccm-sliderPane ccm-options-pane" id="ccm-sliderPane-options" style="clear: both;display: none">
	    <div class="clearfix" style="width:99%">
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Effect')?></h4>
                    <p class="muted"><?php  echo t('')?></p>
					<?php  echo $form->select('effect', $effects, $effect, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left; width:100px;')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Slices')?></h4>
                    <p class="muted"><?php  echo t('For slice animations')?></p>
					<?php  echo $form->text('slices', $slices, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left;', 'maxlength'=>'5')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Box Cols')?></h4>
                    <p class="muted"><?php  echo t('For box animations')?></p>
					<?php  echo $form->text('boxCols', $boxCols, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left;', 'maxlength'=>'5')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Box Rows')?></h4>
                    <p class="muted"><?php  echo t('For box animations')?></p>
					<?php  echo $form->text('boxRows', $boxRows, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left;', 'maxlength'=>'5')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Animation Speed')?></h4>
                    <p class="muted"><?php  echo t('Slide transition speed')?></p>
					<?php  echo $form->text('animSpeed', $animSpeed, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left;', 'maxlength'=>'5')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Pause Time')?></h4>
                    <p class="muted"><?php  echo t('How long each slide will show')?></p>
					<?php  echo $form->text('pauseTime', $pauseTime, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left;', 'maxlength'=>'5')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Start Slide')?></h4>
                    <p class="muted"><?php  echo t('Set starting Slide (0 index)')?></p>
					<?php  echo $form->text('startSlide', $startSlide, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left;', 'maxlength'=>'5')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Direction Nav')?></h4>
                    <p class="muted"><?php  echo t('Next & Prev navigation')?></p>
					<?php  echo $form->checkbox('directionNav', 1, $directionNav, array()); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Control Nav')?></h4>
                    <p class="muted"><?php  echo t('1,2,3... navigation')?></p>
					<?php  echo $form->checkbox('controlNav', 1, $controlNav, array()); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Control Nav Thumbs')?></h4>
                    <p class="muted"><?php  echo t('Use thumbnails for Control Nav')?></p>
					<?php  echo $form->checkbox('controlNavThumbs', 1, $controlNavThumbs, array()); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Pause On Hover')?></h4>
                    <p class="muted"><?php  echo t('Stop animation while hovering')?></p>
					<?php  echo $form->checkbox('pauseOnHover', 1, $pauseOnHover, array()); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Manual Advance')?></h4>
                    <p class="muted"><?php  echo t('Force manual transitions. It will set slider to only transition when the nav is clicked.')?></p>
					<?php  echo $form->checkbox('manualAdvance', 1, $manualAdvance, array()); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Prev Text')?></h4>
                    <p class="muted"><?php  echo t('Prev directionNav text')?></p>
					<?php  echo $form->text('prevText', $prevText, array('class'=>'span1', 'style'=>'', 'maxlength'=>'25')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Next Text')?></h4>
                    <p class="muted"><?php  echo t('Next directionNav text')?></p>
					<?php  echo $form->text('nextText', $nextText, array('class'=>'span1', 'style'=>'', 'maxlength'=>'25')); ?>
    	        </div>
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Random Start')?></h4>
                    <p class="muted"><?php  echo t('Start on a random slide')?></p>
					<?php  echo $form->checkbox('randomStart', 1, $randomStart, array()); ?>
    	        </div>
        </div>
    </div>
    <!-- End: Options Tab -->


    <!-- Begin: Design Tab -->
	<div class="ccm-sliderPane ccm-design-pane" id="ccm-sliderPane-design" style="clear: both;display: none">
	    <div class="clearfix" style="width:99%">
                <div class="ccm-block-field-group">
	                <h4><?php  echo t('Theme')?></h4>
                    <p class="muted"><?php  echo t('Slider Theme')?></p>
					<?php  echo $form->select('theme', $themes, $theme, array('class'=>'span1', 'style'=>'direction:ltr; text-align:left; width:100px;')); ?>
    	        </div>
        </div>
    </div>
    <!-- End: Design Tab -->
        
</div>        


<div id="itemRowTemplateWrap" style="display:none">
<?php   
$itemInfo['itemId']='tempItemId';
$itemInfo['fID']='tempFID';
$itemInfo['fileName']='tempFilename';
$itemInfo['origfileName']='tempOrigFilename';
$itemInfo['thumbPath']='tempThumbPath';
$itemInfo['itemTitle']='';
$itemInfo['itemDesc']='';
$itemInfo['itemUrl']='';
$itemInfo['class']='ccm-slideshowBlock-itemRow';
?>
<?php  $this->inc('elements/item_row.php', array('itemInfo' => $itemInfo)); ?> 
</div>
<script type="text/javascript">
$(document).ready(function() {
	$(".launch-tooltip").tooltip();
});
</script>