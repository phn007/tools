<?php
class Breadcrumb {

	function createHtml( $productDetail ) {
		extract( $productDetail );

		echo $this->home();
		echo " / ";
		echo $this->category( $category, $categoryLink );
		echo " / ";
		echo $this->product( $keyword, $permalink );
	}

	function product( $keyword, $permalink ) {
		echo '<a title="' . $keyword . '" href="' . $permalink. '">' . $keyword . '</a>';
	}

	function category( $category, $categoryLink ) {
		echo '<a title="' . $category . '" href="' . $categoryLink . FORMAT . '">' . $category . '</a>';
	}

	function home() {
		echo '<a title="' . HOME_URL . '" href="' . HOME_URL . '">Home</a>';
	}
}