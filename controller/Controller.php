<?php

$data = NULL;

class Controller {

	public function __construct() {

	}

	public function render($view, $d=null) {
		global $data;
		include_once "view/layout/header.php";

		include_once "view/layout/navbarre.php";

		$controller = get_class($this);
		$model = substr($controller, 0, strpos($controller, "Controller"));
		$data = $d;
		include_once "view/".strtolower($model)."/".$view.".php";

		include_once "view/layout/footer.php";
	}

}

