<?php
namespace App\Controllers;

use Flight;
use App\Repositories\UserRepository;

class UserController extends BaseController {
	public function index()
	{
		$userRepo = new UserRepository;
		$users = $userRepo->getUsers();
		$this->render( 'User/list.twig', compact( 'users' ));
	}

	public function new()
	{
		$this->render( 'User/new.twig' );
	}

	public function new_process()
	{
		$formData = $this->request->data;
		$userRepo = new UserRepository;
		$userRepo->newUser( $formData );

		echo "post method here";
	}
}