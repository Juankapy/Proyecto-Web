<?php
/**
 * Archivo principal.
 * Segun el parametro 'action' llama al controlador correspondiente y los enruta.
 */

require_once '../app/Controlador/Controller.php';
require_once '../app/Controlador/AuthController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    case 'login':
        $controller = new AuthController();
        $controller->showLogin();
        break;
    case 'register':
        $controller = new AuthController();
        $controller->showRegister();
        break;
    case 'auth_login':
        $controller = new AuthController();
        $controller->processLogin();
        break;
    case 'auth_register':
        $controller = new AuthController();
        $controller->processRegister();
        break;
    case 'registro_artista':
        $controller = new AuthController();
        $controller->showRegisterArtist();
        break;
    case 'auth_register_artist':
        $controller = new AuthController();
        $controller->processRegisterArtist();
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'home':
    default:
        $controller = new Controller();
        $controller->index();
        break;
}
