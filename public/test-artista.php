<?php
/**
 * Archivo de prueba para visualizar la vista del artista usando el nuevo Controlador
 * Accede a: http://localhost/Proyecto/public/test-artista.php
 */

require_once '../app/Controlador/ArtistaControlador.php';

$controller = new ArtistaControlador();

// Obtener ID o Nombre de la URL, por defecto 'Bad Bunny' para demo
$id = $_GET['id'] ?? '4q3ewBCX7sLwd24euuV69X'; 

$controller->mostrar($id);
