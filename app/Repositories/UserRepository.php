<?php
namespace App\Repositories;

use RedBeanPHP\R;

class UserRepository extends BaseController {

	public function __construct() {

	}

	public function getUsers()
	{
		$users = R::findAll( 'user' );
		return $users;
	}

	public function getUserById( $id )
	{
		return R::load( 'user', $id );
	}

	public function newUser( $formData )
	{
		try {
			$user = R::dispense( 'user' );
			$user->name = $formData[ 'name' ];
			$user->email = $formData[ 'email' ];
			$user->website = $formData[ 'website' ];
			
			return R::store( $user );
		} catch (\RedBeanPHP\RedException $e) {
			return false;
		}
	}

	public function editUser( $formData )
	{
		try {
			$user = R::load( 'user', $formData['id'] );
			$user->name = $formData['name'];
			$user->email = $formData['email'];
			$user->website = $formData['website'];

			return R::store( $user );
		} catch (\RedBeanPHP\RedException $e) {
			return false;
		}
	}

	public function deleteUser( $id )
	{
		// Delete user
			$user = R::load( 'user', $id );
			return R::trash( $user );

		// Ensure that it succeeds
			$user = R::load( 'user', $id );
			return !$user->id;
	}
}