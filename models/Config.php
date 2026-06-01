<?php
/**
 * CONFIGURACIÓN GLOBAL DE LA APLICACIÓN
 * Restaurante El Último Corral
 * @author Sistema de Gestión Western
 */

// ── Base de Datos ────────────────────────────────────────────
define("SGBD",       "mysql");
define("SERVIDOR", getenv('DB_HOST') ?: 'localhost');
define("USERDB",     "root");
define("PASSWORDDB", "Ciclo2gs");
define("NOMBRE_BD",  "restaurante_western");

// ── Aplicación ───────────────────────────────────────────────
define("APP_NAME",   "El Último Corral");
define("APP_URL", "http://localhost/restaurant_western/restaurant_western");
define("APP_ROOT",   __DIR__ . "/..");

// ── Email (PHPMailer) ─────────────────────────────────────────
define("MAIL_HOST",     "smtp.gmail.com");
define("MAIL_USER",     "jcastro2gs@gmail.com");
define("MAIL_PASSWORD", "rons ukei mjrz atyj");
define("MAIL_PORT",     587);
define("MAIL_FROM",     "respuesta_automatica@gmail.com");
define("MAIL_NAME",     APP_NAME);

// ── Google OAuth ──────────────────────────────────────────────
define("GOOGLE_CLIENT_ID",     "425384455989-1u8lje33qfiqedeio3n62bdapcmagi96.apps.googleusercontent.com");
define("GOOGLE_CLIENT_SECRET", "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");
define("GOOGLE_REDIRECT_URI",  APP_URL . "/controllers/api/google_callback.php");

// ── Google Maps ───────────────────────────────────────────────
define("GOOGLE_MAPS_KEY", "AIzaSyDVY7ecjisONwD60ie5YnHTkVQ0xyWZf0o");

// ── Rutas ─────────────────────────────────────────────────────
define("DIR_UPLOADS", APP_ROOT . "/uploads/");
define("DIR_TICKETS", APP_ROOT . "/uploads/tickets/");
define("URL_UPLOADS", APP_URL  . "/uploads/");

// ── Roles ─────────────────────────────────────────────────────
define("ROL_ADMIN",         0);
define("ROL_CAMARERO",      1);
define("ROL_SIN_VERIFICAR", 2);
define("ROL_CLIENTE",       3);

// ── Zona horaria ──────────────────────────────────────────────
date_default_timezone_set("Europe/Madrid");
