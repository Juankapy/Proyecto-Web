<?php
require_once __DIR__ . '/Controller.php';

class UsuarioControlador extends Controller {

    public function perfil() {
        // Verificar si el usuario está logueado
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit();
        }

        // Obtener datos reales de la BD
        require_once __DIR__ . '/../Modelo/Database.php';
        $database = new Database();
        $db = $database->conectar();
        
        try {
            $query = "SELECT * FROM usuario WHERE usuario_id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $_SESSION['usuario_id']);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData) {
                // Mapear a objeto para la vista perfile.php
                $usuario = (object) [
                    'nombre' => $userData['nombre_usuario'], // O nombre_real si prefieres mostrar ese
                    'email' => $userData['email'],
                    'avatar' => $userData['avatar'] ?? 'multimedia/img/default-avatar.png',
                    'nombre_real' => $userData['nombre_real'],
                    'apellidos' => $userData['apellidos'],
                    'ciudad' => $userData['ciudad'],
                    'pais' => $userData['pais']
                ];
            } else {
                 // Usuario no encontrado (posiblemente eliminado por script BD)
                 header('Location: index.php?action=logout');
                 exit();
            }

        } catch (Exception $e) {
            // Error general
             header('Location: index.php?action=logout');
             exit();
        }

        // Anotaciones Mock (Arrays de ejemplo)
        // Anotaciones Mock (Guardado para futuro implementation)
        /*
        $anotaciones = [
             (object) [
            'cancion_titulo' => 'Canción Demo',
            'artista_nombre' => 'Artista Demo',
            'texto' => 'Esta es una anotación de prueba para verificar el diseño.'
            ]
        ];
        */
        
        // Estado actual: Sin anotaciones
        $anotaciones = [];
        
        // Si quisieras probar el estado vacío:
        // $anotaciones = [];

        // Cargar la vista
        require_once __DIR__ . '/../Vista/usuario/perfil.php';
    }

    public function editarPerfil() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit();
        }

        // Obtener datos frescos de la BD
        require_once __DIR__ . '/../Modelo/Database.php';
        $database = new Database();
        $db = $database->conectar();
        
        $usuario = [];
        // Intentamos obtener todos los campos, asumiendo que existen.
        // Si no existen, PDO podría lanzar error, así que usamos un SELECT * genérico o manejamos la excepción
        try {
            $query = "SELECT * FROM usuario WHERE usuario_id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $_SESSION['usuario_id']);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
               // Si el usuario no existe en BD (ej. tras reset de DB), cerramos sesión
               header('Location: index.php?action=logout');
               exit();
            }
        } catch (Exception $e) {
            // Si hay error de conexión, etc., redirigimos con error
            header('Location: index.php?action=home&error=db_connection');
            exit();
        }

        require_once __DIR__ . '/../Vista/usuario/editar_perfil.php';
    }

    public function actualizarPerfil() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
            require_once __DIR__ . '/../Modelo/Database.php';
            $database = new Database();
            $db = $database->conectar();

            $id = $_SESSION['usuario_id'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $nombre_real = $_POST['nombre_real'] ?? ''; // Asumiendo que el campo se llama así o lo añado
            $apellidos = $_POST['apellidos'] ?? '';
            $email = $_POST['email'];
            $calle = $_POST['calle'] ?? '';
            $cp = $_POST['codigo_postal'] ?? '';
            $ciudad = $_POST['ciudad'] ?? '';
            $pais = $_POST['pais'] ?? '';
            
            // Manejo de Avatar
            $avatarPath = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                // Directorio de destino
                $target_dir = __DIR__ . "/../../public/multimedia/img/avatars/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
                $new_filename = "user_" . $id . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Ruta relativa para guardar en BD
                $relative_path = "multimedia/img/avatars/" . $new_filename;

                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                    $avatarPath = $relative_path;
                    // Actualizar sesión
                    $_SESSION['avatar'] = $avatarPath; 
                }
            }

            // Construir Query Dinámica
            // Se asume que se ha ejecutado el script sql/migration_v2.sql
            try {
                $sql = "UPDATE usuario SET nombre_usuario = :nombre, email = :email, nombre_real = :nombre_real, apellidos = :apellidos, calle = :calle, codigo_postal = :cp, ciudad = :ciudad, pais = :pais";
                
                if ($avatarPath) {
                    $sql .= ", avatar = :avatar";
                }
                
                // Manejo de contraseña (campo 'contrasena' en BD)
                if (!empty($_POST['password'])) {
                    $sql .= ", contrasena = :password";
                }

                $sql .= " WHERE usuario_id = :id";

                $stmt = $db->prepare($sql);
                $stmt->bindParam(':nombre', $nombre_usuario);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':nombre_real', $nombre_real);
                $stmt->bindParam(':apellidos', $apellidos);
                $stmt->bindParam(':calle', $calle);
                $stmt->bindParam(':cp', $cp);
                $stmt->bindParam(':ciudad', $ciudad);
                $stmt->bindParam(':pais', $pais);
                $stmt->bindParam(':id', $id);
                
                if ($avatarPath) {
                    $stmt->bindParam(':avatar', $avatarPath);
                }
                if (!empty($_POST['password'])) {
                    $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt->bindParam(':password', $hashed);
                }

                $stmt->execute();
                
                // Actualizar sesión con los datos principales
                $_SESSION['nombre_usuario'] = $nombre_usuario;
                if (!empty($_POST['email'])) $_SESSION['email'] = $_POST['email'];

                header("Location: index.php?action=perfil&success=updated");
                exit();

            } catch (PDOException $e) {
                // Si hay error (posiblemente falta de columnas si no corrieron la migración), redirigir con error
                header("Location: index.php?action=editar_perfil&error=db_error&message=" . urlencode($e->getMessage()));
                exit();
            }
        }
    }
}
