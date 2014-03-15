<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$rssUrl = $showRss ? $controller->getRssUrl($b) : '';
$th = Loader::helper('text');
$imgHelper = Loader::helper('image'); //<--uncomment this line if displaying image attributes (see below)
//$dh = Loader::helper('date'); //<--uncomment this line if displaying dates (see below)
//Note that $nh (navigation helper) is already loaded for us by the controller (for legacy reasons)
?>

<div class="ccm-page-list">

	<?php  foreach ($pages as $page):

		// Prepare data for each page being listed...
		$title = $th->entities($page->getCollectionName());
		$url = $nh->getLinkToCollection($page);
		$target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
		$target = empty($target) ? '_self' : $target;
		$description = $page->getAttribute('news_description');

		
		/* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>
		<div class="col-md-12">
		<?php
		  $newsThumbnailAttribute = $page->getAttribute('news_thumbnail');
  			if (is_object($newsThumbnailAttribute)) {
		 	 echo "<div class='col-md-1'>";
  		 	 $imgHelper->outputThumbnail($newsThumbnailAttribute, 50, 50, $title);
		 	 echo "</div>";
		  }?>
		<h5 class="orange col-md-offset-3">
			<a href="<?php  echo $url ?>" target="<?php  echo $target ?>"><?php  echo $title ?></a>
		</h5>
		<div class="col-md-12">
		<?php 
			 $description = (strlen($description) > 43) ? substr($description,0,40).'...' : $string;
			 echo $description;?>
		</div>
		</div></div>
		</hr>
		
	<?php  endforeach; ?>
 

	<?php  if ($showRss): ?>
		<div class="ccm-page-list-rss-icon">
			<a href="<?php  echo $rssUrl ?>" target="_blank"><img src="<?php  echo $rssIconSrc ?>" width="14" height="14" alt="<?php  echo t('RSS Icon') ?>" title="<?php  echo t('RSS Feed') ?>" /></a>
		</div>
		<link href="<?php  echo BASE_URL.$rssUrl ?>" rel="alternate" type="application/rss+xml" title="<?php  echo $rssTitle; ?>" />
	<?php  endif; ?>
 
</div><!-- end .ccm-page-list -->


<?php  if ($showPagination): ?>
	<div id="pagination">
		<div class="ccm-spacer"></div>
		<div class="ccm-pagination">
			<span class="ccm-page-left"><?php  echo $paginator->getPrevious('&laquo; ' . t('Previous')) ?></span>
			<?php  echo $paginator->getPages() ?>
			<span class="ccm-page-right"><?php  echo $paginator->getNext(t('Next') . ' &raquo;') ?></span>
		</div>
	</div>
<?php  endif; ?>
