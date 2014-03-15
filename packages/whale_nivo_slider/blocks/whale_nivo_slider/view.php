<?php   
/**
 * @author 		shahroq <shahroq \at\ yahoo.com>
 * @copyright  	Copyright (c) 2013 shahroq.
 * http://killerwhalesoft.com/c5/addons/
 */
defined('C5_EXECUTE') or die("Access Denied."); 
?>

	<div class="slider-wrapper <?php  echo "theme-".$theme_title;?>" id="<?php  echo "slider-wrapper-".$bID; ?>">
	    <div id="<?php  echo "nivo-slider-".$bID; ?>" class="nivoSlider" style="width: 100% !important;">
		<?php   
		foreach($items as $item) { 
			echo (strlen($item['itemUrl'])>1) ? '<a href="'.$item['itemUrl'].'">' : '';
	        echo '<img src="'.$item['itemImageSrc'].'" alt="'.$item['itemImageAlt'].'" title="'.$item['itemImageTitle'].'" data-thumb="'.$item['itemImageSrc'].'" >';
			echo (strlen($item['itemUrl'])>1) ? '</a>' : '';
		} 
		?>
        </div>
	</div>

<?php  if(count($items)>0) { ?>
<script type="text/javascript">
$(document).ready(function() {
	$('<?php  echo "#nivo-slider-".$bID; ?>').nivoSlider({
		effect: '<?php  echo isset($effects[$effect])? $effects[$effect]: $effects[0]?>', <?php  //Specify sets like: 'random,fold,fade,sliceDown' ?>
        slices: <?php  echo $slices ?>, <?php  //For slice animations ?>
        boxCols: <?php  echo $boxCols ?>, <?php  //For box animations ?>
        boxRows: <?php  echo $boxRows ?>, <?php  //For box animations ?>
        animSpeed: <?php  echo $animSpeed ?>, <?php  //Slide transition speed ?>
        pauseTime: <?php  echo $pauseTime ?>, <?php  //How long each slide will show ?>
        startSlide: <?php  echo $startSlide ?>, <?php  //Set starting Slide (0 index) ?>
        directionNav: <?php  echo $directionNav ?>, <?php  //Next & Prev navigation ?>
        controlNav: <?php  echo $controlNav ?>, <?php  //1,2,3... navigation ?>
        controlNavThumbs: <?php  echo $controlNavThumbs ?>, <?php  //Use thumbnails for Control Nav ?>
        pauseOnHover: <?php  echo $pauseOnHover ?>, <?php  //Stop animation while hovering ?>
        manualAdvance: <?php  echo $manualAdvance ?>, <?php  //Force manual transitions ?>
        prevText: '<?php  echo $prevText ?>', <?php  //Prev directionNav text ?>
        nextText: '<?php  echo $nextText ?>', <?php  //Next directionNav text ?>
        randomStart: <?php  echo $randomStart ?> <?php  //Start on a random slide ?>
        <?php  //beforeChange: function(){}, // Triggers before a slide transition ?>
        <?php  //afterChange: function(){}, // Triggers after a slide transition ?>
        <?php  //slideshowEnd: function(){}, // Triggers after all slides have been shown ?>
        <?php  //lastSlide: function(){}, // Triggers when last slide is shown ?>
        <?php  //afterLoad: function(){} // Triggers when slider has loaded ?>
	});
	
});
</script>
<?php  } ?>