<?php

$cat = new HomeCategoryList( $categoryList );
$categoryListHtml = $cat->getHtml();

$brand = new HomeCategoryList( $brandList );
$brandListHtml = $brand->getHtml();

class HomeCategoryList {
	
	private $categoryList;
	
	function __construct( $categoryList ) {
		$this->categoryList = $categoryList;
	}
	
	function getHtml() {
		ob_start();
		$this->setHtml();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function setHtml() { 
		foreach ( $this->categoryList as $list ) :?>
<div class="col-md-3">
	<a title="<?php echo $list['categoryName'] ?>" href="<?php echo $list['categorylink'] ?>">
		<?php echo $list['categoryName'] ?>
	</a>
</div>		
	<?php endforeach;
	}
}