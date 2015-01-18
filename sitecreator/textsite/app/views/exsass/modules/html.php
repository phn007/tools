<?php
/**
* HTML
*/
class Html {
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

trait Components {
	function header() {
		return array(
			'dir' => 'main',
			'id' => array( 
				1 => 'header1', 
				2 => 'header2' 
			)
		);
	}

	function sbtn() {
		return array(
			'dir' => 'home',
			'id' => array( 1 => 'sbtn' )
		);
	}

	function footer() {
		return array(
			'dir' => 'main',
			'id' => array( 
				1 => 'footer1', 
				2 => 'footer2' 
			)
		);
	}

	function jumbotron() {
		return array(
			'dir' => 'home',
			'id' => array( 1 => 'jumbotron1' )
		);
	}

	function feature() {
		return array(
			'dir' => 'home',
			'id' => array( 1 => 'feature' )
		);
	}

	function impact() {
		return array(
			'dir' => 'home',
			'id' => array( 1 => 'impact' )
		);
	}

	function cycle() {
		return array(
			'dir' => 'home',
			'id' => array( 1 => 'cycle' )
		);
	}

	function masonry() {
		return array(
			'dir' => 'home',
			'id' => array( 1 => 'masonry' )
		);
	}

	function echojs() {
		return array(
			'dir' => 'home',
			'id' => array( 1 => 'echojs' )
		);
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