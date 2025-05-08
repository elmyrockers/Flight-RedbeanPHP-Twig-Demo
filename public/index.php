<?php
require '../vendor/autoload.php';


// Error Handling -------------------------------------------------------------------------------------------------------------
	use Symfony\Component\ErrorHandler\Debug;
	use Symfony\Component\ErrorHandler\ErrorHandler;
	use Symfony\Component\ErrorHandler\DebugClassLoader;
	Debug::enable();
//-----------------------------------------------------------------------------------------------------------------------------
use RedBeanPHP\R as R;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;








// Setup Twig
	Flight::register( 'twig', Environment::class, [new FilesystemLoader(__DIR__ . '/../app/Views')] );




// Setup RedBeanPHP
	R::setup('sqlite:../db/sqlite.db');



// users route
	Flight::route( '/', function(){
		Flight::redirect( '/users' );
	});

	Flight::route('GET /users', ['App\\Controllers\\UserController', 'index']);
	Flight::route( 'GET /users/new', ['App\\Controllers\\UserController', 'new'] );
	Flight::route( 'POST /users/new', ['App\\Controllers\\UserController', 'new_process'] );





	Flight::start();