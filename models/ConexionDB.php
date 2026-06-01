<?php
require_once "Config.php";

/**
 * ConexionDB - Patrón Singleton
 * Garantiza una única instancia de conexión PDO a la base de datos.
 * 
 * @author Sistema de Gestión Western
 */
class ConexionDB {

    /** @var PDO|null Instancia única de la conexión */
    private static ?PDO $instancia = null;

    /** Constructor privado → nadie puede instanciar desde fuera */
    private function __construct() {}

    /** Clonación prohibida */
    private function __clone() {}

    /**
     * Devuelve la única instancia PDO.
     * Si no existe, la crea (lazy initialization).
     *
     * @param string $nombreBD Nombre de la base de datos
     * @return PDO|null
     */
    public static function getConexion(string $nombreBD = NOMBRE_BD): ?PDO {
        if (self::$instancia === null) {
            try {
                $dsn = SGBD . ":host=" . SERVIDOR . ";dbname=" . $nombreBD . ";charset=utf8mb4";
                self::$instancia = new PDO($dsn, USERDB, PASSWORDDB, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS   => true,
                ]);
            } catch (PDOException $e) {
                // En producción, loguear el error y no exponerlo
                error_log("Error de conexión BD: " . $e->getMessage());
                self::$instancia = null;
            }
        }
        return self::$instancia;
    }

    /**
     * Cierra la conexión liberando la instancia.
     */
    public static function cerrarConexion(): void {
        self::$instancia = null;
    }
}
