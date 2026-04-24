<?php
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use RedBeanPHP\R as R;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


require 'vendor/autoload.php';






// GLOBAL SETUP (Run once on startup) --------------------------------------
// R::setup('sqlite:../db/sqlite.db');
	R::setup('sqlite:' . __DIR__ . '/db/sqlite.db');
	Flight::register('twig', Environment::class, [new FilesystemLoader(__DIR__ . '/app/Views')]);


// OVERRIDES
	Flight::map('redirect', function ($url, $code = 303) {
		$response = Flight::get('swoole.response');

		if (!$response) {
			throw new Exception("Swoole response is not available");
		}

		$response->status($code);
		$response->header('Location', $url);
		$response->end('');

		exit;
	});


// Define Routes
	Flight::route('/', function(){ Flight::redirect('/users'); });
	Flight::route('GET /users', ['App\\Controllers\\UserController', 'index']);
	Flight::route('GET /users/new', ['App\\Controllers\\UserController', 'new']);
	Flight::route('GET /users/edit/@id', ['App\\Controllers\\UserController', 'edit']);
	Flight::route('GET /users/delete/@id', ['App\\Controllers\\UserController', 'delete']);
	Flight::route('POST /users/new', ['App\\Controllers\\UserController', 'new_process']);
	Flight::route('POST /users/edit/@id', ['App\\Controllers\\UserController', 'edit_process']);
	Flight::route('POST /users/delete/@id', ['App\\Controllers\\UserController', 'delete_process']);



// SWOOLE SERVER -----------------------------------------------------------
	$server = new Server("0.0.0.0", 8080);
	$server->set([
		'worker_num'    => 1,      // Essential for 1GB RAM
		'max_request'   => 1000,   // Cleans memory automatically every 1k requests
	]);


	$server->on("Request", function (Request $request, Response $response) {
		// Inject Swoole response into Flight
			Flight::set('swoole.response', $response);

		// Static File Handler
			$uri = $request->server['request_uri'];
			$file = __DIR__ . '/public' . $uri;
			if (str_starts_with($uri, '/assets/') && file_exists($file)) {

				$ext = pathinfo($file, PATHINFO_EXTENSION);
				$mime = match ($ext) {
					'css' => 'text/css',
					'js' => 'application/javascript',
					'png' => 'image/png',
					'jpg', 'jpeg' => 'image/jpeg',
					default => 'application/octet-stream'
				};

				$response->header("Content-Type", $mime);
				$response->end(file_get_contents($file));
				return;
			}


		// PHP Superglobals
			$_GET    = $request->get ?? [];
			$_POST   = $request->post ?? [];
			$_FILES  = $request->files ?? [];
			$_COOKIE = $request->cookie ?? [];
			$_SERVER = array_change_key_case($request->server, CASE_UPPER);



		// Output buffer
			ob_start();
					try {
						Flight::start();
					} catch (\Throwable $e) {
						echo $e->getMessage();
					}
			$content = ob_get_clean();

		// Detect 404 from Flight output
			if (str_contains($content, '404')) {
				$response->status(404);
			} else {
				$response->status(200);
			}

		$response->end($content);
	});




	echo "Swoole http server started at http://127.0.0.1:8080\n";
	$server->start();