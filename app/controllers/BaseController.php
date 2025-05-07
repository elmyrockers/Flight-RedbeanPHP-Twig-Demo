<?php
namespace App\Controllers;

use Flight;

class BaseController {
	protected $twig;
	protected $request;

	public function __construct() {
		$this->twig = Flight::twig();
		$this->request = Flight::request();
	}

	protected function render($template, $data = []) {
		echo $this->twig->render($template, $data);
	}
}
