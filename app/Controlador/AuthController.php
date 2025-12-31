<?php

require_once '../app/Modelo/Database.php';

class AuthController {

    public function showLogin() {
        $extraCss = 'css/styleLogin.css?v=' . time();
        require_once '../app/Vista/layouts/header.php';
        require_once '../app/Vista/auth/login.php';
        require_once '../app/Vista/layouts/footer.php';
    }

    public function showRegister() {
        $extraCss = 'css/styleLogin.css?v=' . time();
        require_once '../app/Vista/layouts/header.php';
        require_once '../app/Vista/auth/registro.php';
        require_once '../app/Vista/layouts/footer.php';
    }

    public function processRegister() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $tipo_usuario = $_POST['tipo_usuario'];

            if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password) || empty($tipo_usuario)) {
                header("Location: index.php?action=register&error=empty_fields");
                exit();
            }

            if ($password !== $confirm_password) {
                header("Location: index.php?action=register&error=password_mismatch");
                exit();
            }

            $database = new Database();
            $db = $database->conectar();

            if ($db) {
                $query = "SELECT usuario_id FROM usuario WHERE email = :email";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    header("Location: index.php?action=register&error=email_exists");
                    exit();
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO usuario (nombre_usuario, email, contrasena, rol) VALUES (:nombre, :email, :password, :rol)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':nombre', $nombre);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':rol', $tipo_usuario);

                    if ($stmt->execute()) {
                        header("Location: index.php?action=login&success=registered");
                        exit();
                    } else {
                        header("Location: index.php?action=register&error=stmtfailed");
                        exit();
                    }
                }
            } else {
                header("Location: index.php?action=register&error=db_connection");
                exit();
            }
        } else {
            header("Location: index.php?action=register");
            exit();
        }
    }

    public function processLogin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                header("Location: index.php?action=login&error=empty_fields");
                exit();
            }

            $database = new Database();
            $db = $database->conectar();

            if ($db) {
                $query = "SELECT usuario_id, nombre_usuario, contrasena, rol FROM usuario WHERE email = :email";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $hashed_password = $row['contrasena'];

                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['usuario_id'] = $row['usuario_id'];
                        $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
                        $_SESSION['rol'] = $row['rol'];

                        header("Location: index.php");
                        exit();
                    } else {
                        header("Location: index.php?action=login&error=wrong_password");
                        exit();
                    }
                } else {
                    header("Location: index.php?action=login&error=user_not_found");
                    exit();
                }
            } else {
                header("Location: index.php?action=login&error=db_connection");
                exit();
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function showRegisterArtist() {
        $extraCss = 'css/styleLogin.css?v=' . time();
        require_once '../app/Vista/layouts/header.php';
        require_once '../app/Vista/auth/registro-artista.php';
        require_once '../app/Vista/layouts/footer.php';
    }

    public function processRegisterArtist() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = trim($_POST['nombre']);
            $nombre_artistico = trim($_POST['nombre_artistico']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $tipo_usuario = 'artista'; // Forzado
            $genero = $_POST['genero_musical'];

            if (empty($nombre) || empty($nombre_artistico) || empty($email) || empty($password) || empty($confirm_password) || empty($genero)) {
                header("Location: index.php?action=registro_artista&error=empty_fields");
                exit();
            }

            if ($password !== $confirm_password) {
                header("Location: index.php?action=registro_artista&error=password_mismatch");
                exit();
            }

            $database = new Database();
            $db = $database->conectar();

            if ($db) {
                try {
                    // Verificar si el email ya existe
                    $query = "SELECT usuario_id FROM usuario WHERE email = :email";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                         header("Location: index.php?action=registro_artista&error=email_exists");
                         exit();
                    }

                    // Iniciar Transacción
                    $db->beginTransaction();

                    // 1. Insertar Usuario (Rol 'editor' para artistas)
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $rolArtista = 'editor'; 
                    
                    $queryUser = "INSERT INTO usuario (nombre_usuario, email, contrasena, rol) VALUES (:nombre, :email, :password, :rol)";
                    $stmtUser = $db->prepare($queryUser);
                    $stmtUser->bindParam(':nombre', $nombre);
                    $stmtUser->bindParam(':email', $email);
                    $stmtUser->bindParam(':password', $hashed_password);
                    $stmtUser->bindParam(':rol', $rolArtista);
                    $stmtUser->execute();
                    $usuarioId = $db->lastInsertId();

                    // 2. Insertar Artista
                    $queryArtista = "INSERT INTO artista (nombre_artistico) VALUES (:nombre_artistico)";
                    $stmtArtista = $db->prepare($queryArtista);
                    $stmtArtista->bindParam(':nombre_artistico', $nombre_artistico);
                    $stmtArtista->execute();
                    $artistaId = $db->lastInsertId();

                    // 3. Manejar Género
                    $queryGeneroCheck = "SELECT genero_id FROM genero WHERE nombre = :nombre";
                    $stmtGeneroCheck = $db->prepare($queryGeneroCheck);
                    $stmtGeneroCheck->bindParam(':nombre', $genero);
                    $stmtGeneroCheck->execute();
                    
                    if ($stmtGeneroCheck->rowCount() > 0) {
                        $generoId = $stmtGeneroCheck->fetchColumn();
                    } else {
                        $queryGeneroInsert = "INSERT INTO genero (nombre) VALUES (:nombre)";
                        $stmtGeneroInsert = $db->prepare($queryGeneroInsert);
                        $stmtGeneroInsert->bindParam(':nombre', $genero);
                        $stmtGeneroInsert->execute();
                        $generoId = $db->lastInsertId();
                    }

                    // 4. Vincular Artista - Género
                    $queryAg = "INSERT INTO artista_genero (artista_id, genero_id) VALUES (:artista_id, :genero_id)";
                    $stmtAg = $db->prepare($queryAg);
                    $stmtAg->bindParam(':artista_id', $artistaId);
                    $stmtAg->bindParam(':genero_id', $generoId);
                    $stmtAg->execute();

                    // 5. Vincular Usuario (Editor) - Artista
                    $fecha = date('Y-m-d');
                    $queryAe = "INSERT INTO artista_editor (usuario_id, artista_id, fecha_asignacion) VALUES (:usuario_id, :artista_id, :fecha)";
                    $stmtAe = $db->prepare($queryAe);
                    $stmtAe->bindParam(':usuario_id', $usuarioId);
                    $stmtAe->bindParam(':artista_id', $artistaId);
                    $stmtAe->bindParam(':fecha', $fecha);
                    $stmtAe->execute();

                    // Confirmar Transacción
                    $db->commit();

                    header("Location: index.php?action=login&success=artist_registered");
                    exit();

                } catch (Exception $e) {
                    $db->rollBack();
                    header("Location: index.php?action=registro_artista&error=stmtfailed&message=" . urlencode($e->getMessage()));
                    exit();
                }
            } else {
                header("Location: index.php?action=registro_artista&error=db_connection");
                exit();
            }
        } else {
            header("Location: index.php?action=registro_artista");
            exit();
        }
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        // Redirigir al login con mensaje de éxito
        header("Location: index.php?action=login&success=logout");
        exit();
    }
}
