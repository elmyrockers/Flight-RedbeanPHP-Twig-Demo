<?php
namespace App\Controllers;

use Flight;

class BaseController {
    protected $twig;

    public function __construct() {
        $this->twig = Flight::get('twig');
    }

    protected function render($template, $data = []) {
        echo $this->twig->render($template, $data);
    }
}
