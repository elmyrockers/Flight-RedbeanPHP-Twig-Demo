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
	$loader = new FilesystemLoader(__DIR__ . '/../app/views');
	$twig = new Environment($loader);
	Flight::set( 'twig', $twig ); // Share Twig with all routes

// Setup RedBeanPHP
	R::setup('sqlite:db/database.sqlite');

// users route
	Flight::route( '/', function(){
		Flight::redirect( '/users' );
	});

	use App\Controllers\UserController;
	Flight::route( '/users', [new UserController(), 'index'] );





	Flight::start();