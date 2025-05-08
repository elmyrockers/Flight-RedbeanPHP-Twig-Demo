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

	public function edit( $id )
	{
		// Get a user
			$userRepo = new UserRepository;
			$user = $userRepo->getUserById( $id );
		$this->render( 'User/edit.twig', compact( 'user' ) );
	}

	public function delete( $id )
	{
		// Get a user
			$userRepo = new UserRepository;
			$user = $userRepo->getUserById( $id );
		$this->render( 'User/delete.twig', compact( 'user' ) );
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

	public function edit_process( $id )
	{
		// Save into database
			$formData = $this->request->data;
			$userRepo = new UserRepository;
			$ok = $userRepo->editUser( $formData );

		// Set flash message to be displayed later
			if ( !$ok ) $_SESSION[ 'flash_message' ] = '<div class="alert alert-danger">Failed to save the user data. Please try again.</div>';
			else $_SESSION[ 'flash_message' ] = '<div class="alert alert-success">The user has been updated!</div>';

		// Redirect to list of users table
			Flight::redirect( '/users' );
	}

	public function delete_process( $id )
	{
		// Save into database
			$userRepo = new UserRepository;
			$ok = $userRepo->deleteUser( $id );

		// Set flash message to be displayed later
			if ( !$ok ) $_SESSION[ 'flash_message' ] = '<div class="alert alert-danger">Failed to delete the user record. Please try again.</div>';
			else $_SESSION[ 'flash_message' ] = '<div class="alert alert-success">The user record has been deleted!</div>';

		// Redirect to list of users table
			Flight::redirect( '/users' );
	}
}