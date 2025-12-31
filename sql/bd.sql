-- ====================================================================================
-- Script Maestro de Creación para la Base de Datos "Genius App"
-- Versión: 3.0 (Consolidada con Perfiles, Géneros y Créditos)
-- 
-- ESTE SCRIPT REEMPLAZA A TODOS LOS ANTERIORES.
-- Ejecútalo una sola vez para tener la base de datos completa y actualizada.
-- ====================================================================================

-- 1. Preparación del Entorno
DROP DATABASE IF EXISTS genius_db;
CREATE DATABASE genius_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE genius_db;


-- -----------------------------------------------------
-- Tabla: usuario
-- Incluye datos de login, perfil extendido, dirección y avatar.
-- -----------------------------------------------------
CREATE TABLE usuario (
  usuario_id INT AUTO_INCREMENT PRIMARY KEY,
  -- Datos de Acceso
  nombre_usuario VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE, -- ¡ESTO EVITA DUPLICADOS!
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('usuario', 'editor', 'admin') NOT NULL DEFAULT 'usuario',
  
  -- Datos de Perfil y Personales
  avatar VARCHAR(255) DEFAULT NULL, -- URL o nombre de archivo
  nombre_real VARCHAR(100) DEFAULT NULL,
  apellidos VARCHAR(100) DEFAULT NULL,
  
  -- Datos de Dirección
  calle VARCHAR(255) DEFAULT NULL,
  codigo_postal VARCHAR(20) DEFAULT NULL,
  ciudad VARCHAR(100) DEFAULT NULL,
  pais VARCHAR(100) DEFAULT NULL,
  
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: artista
-- -----------------------------------------------------
CREATE TABLE artista (
  artista_id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_artistico VARCHAR(255) NOT NULL UNIQUE,
  biografia TEXT NULL,
  foto_url VARCHAR(255) NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: genero
-- Tabla independiente para los géneros musicales.
-- -----------------------------------------------------
CREATE TABLE genero (
  genero_id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: artista_genero
-- Tabla pivote (Muchos a Muchos) entre artistas y géneros.
-- -----------------------------------------------------
CREATE TABLE artista_genero (
  artista_id INT NOT NULL,
  genero_id INT NOT NULL,
  PRIMARY KEY (artista_id, genero_id),
  CONSTRAINT fk_ag_artista FOREIGN KEY (artista_id) REFERENCES artista(artista_id) ON DELETE CASCADE,
  CONSTRAINT fk_ag_genero FOREIGN KEY (genero_id) REFERENCES genero(genero_id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: artista_editor
-- Relaciona usuarios (rol editor) con artistas.
-- -----------------------------------------------------
CREATE TABLE artista_editor (
  usuario_id INT NOT NULL,
  artista_id INT NOT NULL,
  fecha_asignacion DATE NOT NULL,
  PRIMARY KEY (usuario_id, artista_id),
  CONSTRAINT fk_editor_usuario FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id) ON DELETE CASCADE,
  CONSTRAINT fk_editor_artista FOREIGN KEY (artista_id) REFERENCES artista(artista_id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: album
-- -----------------------------------------------------
CREATE TABLE album (
  album_id INT AUTO_INCREMENT PRIMARY KEY,
  artista_id INT NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  fecha_lanzamiento DATE NULL,
  contexto TEXT NULL,
  portada_url VARCHAR(255) NULL,
  estado ENUM('publico', 'privado', 'oculto') NOT NULL DEFAULT 'publico',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_album_artista FOREIGN KEY (artista_id) REFERENCES artista(artista_id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: cancion
-- Incluye la columna de créditos.
-- -----------------------------------------------------
CREATE TABLE cancion (
  cancion_id INT AUTO_INCREMENT PRIMARY KEY,
  album_id INT NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  duracion INT NULL, -- en segundos
  contexto TEXT NULL,
  creditos TEXT NULL, -- Columna añadida
  estado ENUM('publico', 'privado', 'oculto') NOT NULL DEFAULT 'publico',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cancion_album FOREIGN KEY (album_id) REFERENCES album(album_id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: letra
-- -----------------------------------------------------
CREATE TABLE letra (
  letra_id INT AUTO_INCREMENT PRIMARY KEY,
  cancion_id INT NOT NULL UNIQUE,
  contenido LONGTEXT NOT NULL,
  CONSTRAINT fk_letra_cancion FOREIGN KEY (cancion_id) REFERENCES cancion(cancion_id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- -----------------------------------------------------
-- Tabla: anotacion
-- -----------------------------------------------------
CREATE TABLE anotacion (
  anotacion_id INT AUTO_INCREMENT PRIMARY KEY,
  letra_id INT NOT NULL,
  usuario_id INT NOT NULL,
  explicacion TEXT NOT NULL,
  estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_anotacion_letra FOREIGN KEY (letra_id) REFERENCES letra(letra_id) ON DELETE CASCADE,
  CONSTRAINT fk_anotacion_usuario FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Confirmación Final
SELECT 'Base de datos genius_db RECREADA COMPLETAMENTE con la estructura final.' AS 'Estado';