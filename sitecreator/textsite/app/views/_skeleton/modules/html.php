<?php
/**
* HTML
*/
Class Html {
	public static function get( $name, $id, $data=null ) {
		$html = new CreateHtml( $name, $id );
		return $html->get( $data );
	}
}

class CreateHtml {
	use ClassFile;
	use Components;

	private $name;
	private $id;
	private $classname;
	private $params;

	function __construct( $name, $id ) {
		$this->name = $name;
		$this->id = $id;
		$this->checkMethodExist();
		$this->checkClassFile(); //see ClassFile Trait
		$this->loadClass();
	}

	function checkMethodExist() {
		if ( ! method_exists( $this, $this->name ) ) die( $name . ' method not found!!!' );
	}

	function loadClass() {
		include $this->file;
	}

	function get( $params = null ) {
		$this->params = $params;
		$obj = new $this->classname();
		return $this->setHtml( $obj );
	}

	function setHtml( $obj ) {
		ob_start();
		$obj->createHtml( $this->params );
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}

trait ClassFile {
	function checkClassFile() {
		$method = $this->name;
		$list = $this->$method();
		$dir = $list['dir'];
		$class = $list['id'];
		$this->checkClassNameExist( $class );
		$this->setClassname( $class );
		$this->setClassFile( $dir );

		if ( ! file_exists( $this->file ) ) 
			die( 'File ' . $this->file . ' not found !!!' );
	}

	function checkClassNameExist( $class ) {
		if ( ! isset( $class[$this->id] ) ) 
			die( $this->name . $this->id . ' does not exists' );
	}

	function setClassname( $class ) {
		$this->classname = $class[$this->id];
	}

	function setClassFile( $dir ) {
		$this->file = dirname( __FILE__ ) . '/' . $dir . '/' . $this->classname . '.php';
	}
}

trait Components {
	function main() {
		return array('dir' => 'main',
		'id' => array(
				'header' => 'header',
				'footer' => 'footer',
			)
		);
	}

	function homepage() {
		return array(
			'dir' => 'home',
			'id' => array(
				'topProductList' => 'topProductList',
				'productList' => 'productList',
				'categoryLinkList' => 'categoryLinkList'
			)
		);
	}

	function productpage() {
		return array(
			'dir' => 'product',
			'id' => array(
				'breadcrumb' => 'breadcrumb',
				'productDetail' => 'productItemDetail',
				'relatedProducts' => 'relatedProductItems',
				'navmenu' => 'navmenu'
			)
		);
	}

	function categorypage() {
		return array(
			'dir' => 'category',
			'id' => array(
				'categoryItems' => 'categoryItemList',
				'menu' => 'menu'
			)
		);
	}

	function categoriespage() {
		return array(
			'dir' => 'categories',
			'id' => array(
				'categories' => 'categoryLinks',
				'menu' => 'menu'
			)
		);
	}
}