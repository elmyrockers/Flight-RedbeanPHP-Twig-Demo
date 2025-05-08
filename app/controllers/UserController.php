<?php
namespace App\Controllers;

use Flight;
use App\Repositories\UserRepository;

class UserController extends BaseController {
	public function index()
	{
		// Get list of users
			$userRepo = new UserRepository;
			$users = $userRepo->getUsers();

		// Display flash once
			$flash = $_SESSION[ 'flash_message' ] ?? '';
			unset( $_SESSION[ 'flash_message' ] );
		
		$this->render( 'User/list.twig', compact( 'users','flash' ));
	}

	public function new()
	{
		$this->render( 'User/new.twig' );
	}

	public function new_process()
	{
		// Save into database
			$formData = $this->request->data;
			$userRepo = new UserRepository;
			$id = $userRepo->newUser( $formData );

		// Set flash message to be displayed later
			if ( !$id ) $_SESSION[ 'flash_message' ] = '<div class="alert alert-danger">Failed to create the user. Please try again.</div>';
			else $_SESSION[ 'flash_message' ] = '<div class="alert alert-success">New user has been created!</div>';

		// Redirect to list of users table
			Flight::redirect( '/users' );
	}
}