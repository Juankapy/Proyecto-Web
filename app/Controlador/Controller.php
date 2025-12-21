<?php

class Controller {
    public function index() {
        require_once '../app/Vista/layouts/header.php';
        require_once '../app/Vista/home/index.php';
        require_once '../app/Vista/layouts/footer.php';
    }
}
