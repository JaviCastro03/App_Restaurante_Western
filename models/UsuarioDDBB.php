<?php
require_once __DIR__ . "/ConexionDB.php";

/**
 * UsuarioDDBB - Modelo de Usuarios
 * Gestiona todas las operaciones CRUD de la tabla usuarios.
 */
class UsuarioDDBB {

    private PDO $conexion;

    public function __construct() {
        $this->conexion = ConexionDB::getConexion();
    }

    // ── Verificación ────────────────────────────────────────────

    public function emailExiste(string $email): bool {
        $sql = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function comprobarPassword(string $password, string $cpassword): bool {
        return $password === $cpassword;
    }

    // ── Inserción ────────────────────────────────────────────────

    public function insertarUsuario(
        string $dni, string $nombre, string $apellidos,
        string $email, string $password, int $rol,
        string $telefono = '', string $provincia = '',
        string $municipio = '', string $via = '', string $numero = ''
    ): bool {
        $sql = "INSERT INTO usuarios 
            (dni, nombre, apellidos, email, password, rol, telefono, provincia, municipio, via, numero)
        VALUES 
            (:dni, :nombre, :apellidos, :email, :password, :rol, :telefono, :provincia, :municipio, :via, :numero)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':dni',       $dni);
        $stmt->bindParam(':nombre',    $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email',     $email);
        $stmt->bindParam(':password',  $password);
        $stmt->bindParam(':rol',       $rol, PDO::PARAM_INT);
        $stmt->bindParam(':telefono',  $telefono);
        $stmt->bindParam(':provincia', $provincia);
        $stmt->bindParam(':municipio', $municipio);
        $stmt->bindParam(':via',       $via);
        $stmt->bindParam(':numero',    $numero);
        return $stmt->execute();
    }

    public function insertarUsuarioGoogle(
        string $googleId, string $nombre, string $apellidos,
        string $email, string $avatar
    ): bool {
        $sql = "INSERT INTO usuarios (dni, nombre, apellidos, email, password, rol, google_id, avatar)
                VALUES ('GOOGLE', :nombre, :apellidos, :email, '', :rol, :google_id, :avatar)
                ON DUPLICATE KEY UPDATE google_id = :google_id2, avatar = :avatar2";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre',    $nombre);
        $stmt->bindValue(':apellidos', $apellidos);
        $stmt->bindValue(':email',     $email);
        $stmt->bindValue(':rol',       ROL_CLIENTE, PDO::PARAM_INT);
        $stmt->bindValue(':google_id', $googleId);
        $stmt->bindValue(':avatar',    $avatar);
        $stmt->bindValue(':google_id2',$googleId);
        $stmt->bindValue(':avatar2',   $avatar);
        return $stmt->execute();
    }

    public function setTokenVerificacion(string $email, string $token): bool {
        $sql = "UPDATE usuarios SET token_verificacion = :token WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function verificarTokenRegistro(string $token): bool {
        $sql = "UPDATE usuarios SET rol = :rol, token_verificacion = NULL 
                WHERE token_verificacion = :token AND rol = :sinver";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':rol',   ROL_CLIENTE, PDO::PARAM_INT);
        $stmt->bindParam(':token', $token);
        $stmt->bindValue(':sinver', ROL_SIN_VERIFICAR, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // ── Getters ─────────────────────────────────────────────────

    public function getRol(string $email): ?int {
        $sql  = "SELECT rol FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ? (int)$fila['rol'] : null;
    }

    public function getPassword(string $email): ?string {
        $sql  = "SELECT password FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ? $fila['password'] : null;
    }

    public function getIdUsuario(string $email): ?int {
        $sql  = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ? (int)$fila['id'] : null;
    }

    public function getNombreUsuario(string $email): ?string {
        $sql  = "SELECT nombre FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() ?: null;
    }

    public function getUsuarioPorId(int $id): ?array {
        $sql  = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function getUsuarioPorGoogleId(string $googleId): ?array {
        $sql  = "SELECT * FROM usuarios WHERE google_id = :gid LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':gid', $googleId);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    // ── Setters / Update ─────────────────────────────────────────

    public function setRol(string $email, int $rol): bool {
        $sql  = "UPDATE usuarios SET rol = :rol WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':rol',   $rol, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function setActivo(string $email, int $activo): bool {
        $sql  = "UPDATE usuarios SET activo = :activo WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':email',  $email);
        return $stmt->execute();
    }

    public function actualizarPerfil(int $id, array $datos): bool {
        $sql  = "UPDATE usuarios SET nombre=:nombre, apellidos=:apellidos, 
            telefono=:telefono, provincia=:provincia, municipio=:municipio, 
            via=:via, numero=:numero WHERE id=:id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre',    $datos['nombre']);
        $stmt->bindParam(':apellidos', $datos['apellidos']);
        $stmt->bindParam(':telefono',  $datos['telefono']);
        $stmt->bindParam(':provincia', $datos['provincia']);
        $stmt->bindParam(':municipio', $datos['municipio']);
        $stmt->bindParam(':via',       $datos['via']);
        $stmt->bindParam(':numero',    $datos['numero']);
        $stmt->bindParam(':id',        $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ── Listados Admin ───────────────────────────────────────────

    public function getTodosUsuarios(): array {
        $sql  = "SELECT id, dni, nombre, apellidos, email, rol, activo, fecha_registro 
                 FROM usuarios ORDER BY fecha_registro DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUsuariosPorRol(int $rol): array {
        $sql  = "SELECT * FROM usuarios WHERE rol = :rol AND activo = 1 ORDER BY nombre";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function eliminarUsuario(int $id): bool {
        $sql  = "UPDATE usuarios SET activo = 0 WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function reactivarUsuario(string $email): void {
        $stmt = $this->conexion->prepare(
            "UPDATE usuarios SET activo = 1 WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
    }
}
