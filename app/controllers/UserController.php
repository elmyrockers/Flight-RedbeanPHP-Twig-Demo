<?php
namespace App\Controllers;

class UserController extends BaseController {
	public function index() {
		$this->render( 'home.twig', []);
	}
}