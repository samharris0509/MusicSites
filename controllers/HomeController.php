<?php
require_once __DIR__ . '/../auth/LoginMiddleware.php';

class HomeController {
    private $loginMiddleware;

    public function __construct() {
        $this->loginMiddleware = new LoginMiddleware();
        $this->loginMiddleware->handle();  // Ensures the user is authenticated
    }

    public function index() {
        // Home page logic
    }
}
