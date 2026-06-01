<?php
require_once __DIR__ . "/ConexionDB.php";

/**
 * RestauranteDDBB - Modelo Principal del Restaurante
 * Gestiona mesas, zonas, turnos, reservas, servicios, menú y estadísticas.
 */
class RestauranteDDBB {

    private PDO $conexion;

    public function __construct() {
        $this->conexion = ConexionDB::getConexion();
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                       ZONAS                              ║
    // ╚══════════════════════════════════════════════════════════╝

    public function getZonas(bool $soloActivas = true): array {
        $where = $soloActivas ? "WHERE activa = 1" : "";
        $sql = "SELECT * FROM zonas $where ORDER BY nombre";
        return $this->conexion->query($sql)->fetchAll();
    }

    public function crearZona(string $nombre, string $desc): bool {
        $sql  = "INSERT INTO zonas (nombre, descripcion) VALUES (:n, :d)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':d' => $desc]);
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                       MESAS                              ║
    // ╚══════════════════════════════════════════════════════════╝

    public function getMesas(bool $soloActivas = true): array {
        $where = $soloActivas ? "WHERE m.activa = 1" : "";
        $sql = "SELECT m.*, z.nombre AS zona_nombre FROM mesas m 
                JOIN zonas z ON m.zona_id = z.id $where ORDER BY z.nombre, m.numero";
        return $this->conexion->query($sql)->fetchAll();
    }

    public function getMesasPorZona(int $zonaId): array {
        $sql  = "SELECT * FROM mesas WHERE zona_id = :z AND activa = 1 ORDER BY numero";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':z' => $zonaId]);
        return $stmt->fetchAll();
    }

    public function getMesasLibres(int $turnoId, string $fecha): array {
        $sql = "SELECT m.*, z.nombre AS zona_nombre FROM mesas m
                JOIN zonas z ON m.zona_id = z.id
                WHERE m.activa = 1
                AND m.id NOT IN (
                    SELECT r.mesa_id FROM reservas r
                    WHERE r.turno_id = :t AND r.fecha = :f AND r.estado IN ('pendiente','confirmada')
                )
                AND m.id NOT IN (
                    SELECT s.mesa_id FROM servicios s
                    WHERE s.turno_id = :t2 AND s.fecha = :f2 AND s.estado = 'abierto'
                )
                ORDER BY z.nombre, m.numero";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':t' => $turnoId, ':f' => $fecha, ':t2' => $turnoId, ':f2' => $fecha]);
        return $stmt->fetchAll();
    }

    public function crearMesa(string $numero, int $capacidad, int $zonaId, string $desc): bool {
        $sql  = "INSERT INTO mesas (numero, capacidad, zona_id, descripcion) VALUES (:n,:c,:z,:d)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':n'=>$numero,':c'=>$capacidad,':z'=>$zonaId,':d'=>$desc]);
    }

    public function editarMesa(int $id, string $numero, int $capacidad, int $zonaId, string $desc): bool {
        $sql  = "UPDATE mesas SET numero=:n, capacidad=:c, zona_id=:z, descripcion=:d WHERE id=:id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':n'=>$numero,':c'=>$capacidad,':z'=>$zonaId,':d'=>$desc,':id'=>$id]);
    }
    public function getServicioAbiertoByMesa(int $mesaId): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT id FROM servicios WHERE mesa_id = ? AND estado = 'abierto' LIMIT 1"
        );
        $stmt->execute([$mesaId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    public function getMesasSinServicioAbierto(): array {
        $sql = "SELECT m.*, z.nombre AS zona_nombre 
            FROM mesas m
            JOIN zonas z ON m.zona_id = z.id
            WHERE m.activa = 1
            AND m.id NOT IN (
                SELECT mesa_id FROM servicios WHERE estado = 'abierto'
            )
            ORDER BY z.nombre, m.numero";
        return $this->conexion->query($sql)->fetchAll();
    }
    public function eliminarMesa(int $id): bool {
        $sql  = "UPDATE mesas SET activa = 0 WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function setEstadoMesa(int $id, string $estado): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE mesas SET estado = :estado WHERE id = :id"
        );
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                       TURNOS                             ║
    // ╚══════════════════════════════════════════════════════════╝

    public function getTurnos(bool $soloActivos = true): array {
        $where = $soloActivos ? "WHERE activo = 1" : "";
        $sql = "SELECT * FROM turnos $where ORDER BY hora_inicio";
        return $this->conexion->query($sql)->fetchAll();
    }

    public function getTurnoPorId(int $id): ?array {
        $sql  = "SELECT * FROM turnos WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function crearTurno(string $nombre, string $ini, string $fin, string $tipo): bool {
        $sql  = "INSERT INTO turnos (nombre, hora_inicio, hora_fin, tipo) VALUES (:n,:i,:f,:t)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':n'=>$nombre,':i'=>$ini,':f'=>$fin,':t'=>$tipo]);
    }

    public function editarTurno(int $id, string $nombre, string $ini, string $fin, string $tipo): bool {
        $sql  = "UPDATE turnos SET nombre=:n, hora_inicio=:i, hora_fin=:f, tipo=:t WHERE id=:id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':n'=>$nombre,':i'=>$ini,':f'=>$fin,':t'=>$tipo,':id'=>$id]);
    }

    public function eliminarTurno(int $id): bool {
        $sql  = "UPDATE turnos SET activo = 0 WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                  DÍAS APERTURA / DESCANSO                ║
    // ╚══════════════════════════════════════════════════════════╝

    public function getDiasApertura(): array {
        return $this->conexion->query("SELECT * FROM dias_apertura ORDER BY dia_semana")->fetchAll();
    }

    public function setDiaApertura(int $diaSemana, int $abierto): bool {
        $sql  = "UPDATE dias_apertura SET abierto = :a WHERE dia_semana = :d";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':a' => $abierto, ':d' => $diaSemana]);
    }

    public function getDiasDescanso(string $año): array {
        $sql  = "SELECT * FROM dias_descanso WHERE YEAR(fecha) = :a ORDER BY fecha";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':a' => $año]);
        return $stmt->fetchAll();
    }

    public function agregarDiaDescanso(string $fecha, string $motivo): bool {
        $sql  = "INSERT IGNORE INTO dias_descanso (fecha, motivo) VALUES (:f,:m)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':f' => $fecha, ':m' => $motivo]);
    }

    public function eliminarDiaDescanso(int $id): bool {
        $sql  = "DELETE FROM dias_descanso WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function esFechaDisponible(string $fecha): bool {
        // Comprobar día de descanso
        $sqlD = "SELECT id FROM dias_descanso WHERE fecha = :f LIMIT 1";
        $stmtD = $this->conexion->prepare($sqlD);
        $stmtD->execute([':f' => $fecha]);
        if ($stmtD->rowCount() > 0) return false;

        // Comprobar día de semana
        $diaSem = (int)date('N', strtotime($fecha)) - 1; // 0=Lunes
        $sqlA = "SELECT abierto FROM dias_apertura WHERE dia_semana = :d";
        $stmtA = $this->conexion->prepare($sqlA);
        $stmtA->execute([':d' => $diaSem]);
        $fila = $stmtA->fetch();
        return $fila && $fila['abierto'] == 1;
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                       RESERVAS                           ║
    // ╚══════════════════════════════════════════════════════════╝

    public function crearReserva(int $userId, int $mesaId, int $turnoId,
                                  string $fecha, int $comensales, string $obs): ?int {
        $sql = "INSERT INTO reservas (usuario_id, mesa_id, turno_id, fecha, num_comensales, observaciones, token_reserva)
                VALUES (:u,:m,:t,:f,:c,:o,:token)";
        $token = bin2hex(random_bytes(32));
        $stmt  = $this->conexion->prepare($sql);
        $ok = $stmt->execute([':u'=>$userId,':m'=>$mesaId,':t'=>$turnoId,
                               ':f'=>$fecha,':c'=>$comensales,':o'=>$obs,':token'=>$token]);
        return $ok ? (int)$this->conexion->lastInsertId() : null;
    }

    public function getReservasPorUsuario(int $userId): array {
        $sql = "SELECT r.*, m.numero AS mesa, z.nombre AS zona, t.nombre AS turno,
                       t.hora_inicio, t.hora_fin
                FROM reservas r
                JOIN mesas m ON r.mesa_id = m.id
                JOIN zonas z ON m.zona_id = z.id
                JOIN turnos t ON r.turno_id = t.id
                WHERE r.usuario_id = :u ORDER BY r.fecha DESC, t.hora_inicio";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':u' => $userId]);
        return $stmt->fetchAll();
    }

    public function getReservasPorFecha(string $fecha): array {
        $sql = "SELECT r.*, m.numero AS mesa, z.nombre AS zona, t.nombre AS turno,
                       t.hora_inicio, t.hora_fin,
                       CONCAT(u.nombre,' ',u.apellidos) AS cliente, u.email AS cliente_email, u.telefono
                FROM reservas r
                JOIN mesas m ON r.mesa_id = m.id
                JOIN zonas z ON m.zona_id = z.id
                JOIN turnos t ON r.turno_id = t.id
                JOIN usuarios u ON r.usuario_id = u.id
                WHERE r.fecha = :f ORDER BY t.hora_inicio, m.numero";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':f' => $fecha]);
        return $stmt->fetchAll();
    }

    public function getTodasReservas(string $desde = '', string $hasta = ''): array {
        $where = "";
        $params = [];
        if ($desde && $hasta) {
            $where = "WHERE r.fecha BETWEEN :desde AND :hasta";
            $params = [':desde' => $desde, ':hasta' => $hasta];
        }
        $sql = "SELECT r.*, m.numero AS mesa, z.nombre AS zona, t.nombre AS turno,
                   t.hora_inicio, t.hora_fin,
                   CONCAT(u.nombre,' ',u.apellidos) AS cliente, u.email AS cliente_email
            FROM reservas r
            JOIN mesas m ON r.mesa_id = m.id
            JOIN zonas z ON m.zona_id = z.id
            JOIN turnos t ON r.turno_id = t.id
            JOIN usuarios u ON r.usuario_id = u.id
            $where ORDER BY r.fecha DESC, t.hora_inicio";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getReservaPorId(int $id): ?array {
        $sql  = "SELECT r.*, m.numero AS mesa, z.nombre AS zona, t.nombre AS turno,
                        t.hora_inicio, t.hora_fin,
                        CONCAT(u.nombre,' ',u.apellidos) AS cliente, u.email AS cliente_email
                 FROM reservas r
                 JOIN mesas m ON r.mesa_id = m.id
                 JOIN zonas z ON m.zona_id = z.id
                 JOIN turnos t ON r.turno_id = t.id
                 JOIN usuarios u ON r.usuario_id = u.id
                 WHERE r.id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function actualizarReserva(int $id, int $mesaId, int $turnoId,
                                       string $fecha, int $comensales, string $obs): bool {
        $sql  = "UPDATE reservas SET mesa_id=:m, turno_id=:t, fecha=:f, 
                    num_comensales=:c, observaciones=:o WHERE id=:id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':m'=>$mesaId,':t'=>$turnoId,':f'=>$fecha,
                                ':c'=>$comensales,':o'=>$obs,':id'=>$id]);
    }

    public function cancelarReserva(int $id): bool {
        $sql  = "UPDATE reservas SET estado = 'cancelada' WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function confirmarReserva(int $id): bool {
        $sql  = "UPDATE reservas SET estado = 'confirmada' WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                       SERVICIOS                          ║
    // ╚══════════════════════════════════════════════════════════╝

    public function abrirServicio(int $mesaId, int $turnoId, int $camareroId,
                                   string $fecha, int $comensales, ?int $reservaId = null): ?int {
        $sql = "INSERT INTO servicios (mesa_id, turno_id, camarero_id, reserva_id, fecha, num_comensales)
                VALUES (:m,:t,:c,:r,:f,:com)";
        $stmt = $this->conexion->prepare($sql);
        $ok = $stmt->execute([':m'=>$mesaId,':t'=>$turnoId,':c'=>$camareroId,
                               ':r'=>$reservaId,':f'=>$fecha,':com'=>$comensales]);
        if ($ok) {
            $id = (int)$this->conexion->lastInsertId();
            $this->setEstadoMesa($mesaId, 'ocupada');
            return $id;
        }
        return null;
    }

    public function cerrarServicio(int $servicioId, string $ticketPath = ''): float {
        $sqlTotal = "SELECT SUM(subtotal) as t FROM pedidos WHERE servicio_id=:s AND estado!='cancelado'";
        $stmtT    = $this->conexion->prepare($sqlTotal);
        $stmtT->execute([':s' => $servicioId]);
        $total = (float)($stmtT->fetchColumn() ?? 0);

        $sql  = "UPDATE servicios SET estado='cerrado', total=:total, ticket_pdf=:pdf, closed_at=NOW() WHERE id=:id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':total'=>$total,':pdf'=>$ticketPath,':id'=>$servicioId]);

        $mesaId = $this->getMesaDeServicio($servicioId);
        if ($mesaId) $this->setEstadoMesa($mesaId, 'libre');

        return $total;
    }

    public function getServicioActivo(int $servicioId): ?array {
        $sql = "SELECT s.*, m.numero AS mesa, z.nombre AS zona, t.nombre AS turno,
                       t.hora_inicio, t.hora_fin
                FROM servicios s
                JOIN mesas m ON s.mesa_id = m.id
                JOIN zonas z ON m.zona_id = z.id
                JOIN turnos t ON s.turno_id = t.id
                WHERE s.id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $servicioId]);
        return $stmt->fetch() ?: null;
    }

    public function getServiciosPorCamarero(int $camareroId, string $fecha): array {
        $sql = "SELECT s.*, m.numero AS mesa, z.nombre AS zona, t.nombre AS turno
                FROM servicios s
                JOIN mesas m ON s.mesa_id = m.id
                JOIN zonas z ON m.zona_id = z.id
                JOIN turnos t ON s.turno_id = t.id
                WHERE s.camarero_id = :c AND s.fecha = :f
                ORDER BY s.created_at DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':c' => $camareroId, ':f' => $fecha]);
        return $stmt->fetchAll();
    }

    private function getMesaDeServicio(int $servicioId): ?int {
        $sql  = "SELECT mesa_id FROM servicios WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $servicioId]);
        return $stmt->fetchColumn() ?: null;
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                       PEDIDOS                            ║
    // ╚══════════════════════════════════════════════════════════╝

    public function agregarPedido(int $servicioId, int $itemId, int $cantidad,
                                   float $precio, string $notas = ''): bool {
        $sql  = "INSERT INTO pedidos (servicio_id, item_id, cantidad, precio_unitario, notas)
                 VALUES (:s,:i,:c,:p,:n)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':s'=>$servicioId,':i'=>$itemId,
                                ':c'=>$cantidad,':p'=>$precio,':n'=>$notas]);
    }

    public function getPedidosDeServicio(int $servicioId): array {
        $sql = "SELECT p.*, mi.nombre_es, mi.nombre_en, mi.precio AS precio_actual,
                       c.nombre_es AS categoria_es, c.tipo AS categoria_tipo
                FROM pedidos p
                JOIN menu_items mi ON p.item_id = mi.id
                JOIN categorias_menu c ON mi.categoria_id = c.id
                WHERE p.servicio_id = :s ORDER BY p.created_at";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':s' => $servicioId]);
        return $stmt->fetchAll();
    }

    public function cancelarPedido(int $pedidoId): bool {
        $sql  = "UPDATE pedidos SET estado = 'cancelado' WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $pedidoId]);
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                         MENÚ                             ║
    // ╚══════════════════════════════════════════════════════════╝

    public function getCategoriasMenu(): array {
        return $this->conexion
            ->query("SELECT * FROM categorias_menu WHERE activa=1 ORDER BY orden")
            ->fetchAll();
    }

    public function getItemsMenu(bool $soloDisponibles = true, ?int $categoriaId = null): array {
        $where = "WHERE 1=1";
        $params = [];
        if ($soloDisponibles) $where .= " AND mi.disponible = 1";
        if ($categoriaId) {
            $where .= " AND mi.categoria_id = :cat";
            $params[':cat'] = $categoriaId;
        }
        $sql = "SELECT mi.*, c.nombre_es AS cat_es, c.nombre_en AS cat_en, c.tipo, c.icono
                FROM menu_items mi
                JOIN categorias_menu c ON mi.categoria_id = c.id
                $where ORDER BY c.orden, mi.orden, mi.nombre_es";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getItemPorId(int $id): ?array {
        $sql  = "SELECT mi.*, c.nombre_es AS cat_es, c.tipo FROM menu_items mi
                 JOIN categorias_menu c ON mi.categoria_id = c.id WHERE mi.id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                     ESTADÍSTICAS                         ║
    // ╚══════════════════════════════════════════════════════════╝

    public function getEstadisticasPorPeriodo(string $periodo, string $año): array {
        switch ($periodo) {
            case 'dia':
                $sql = "SELECT DATE(closed_at) as etiqueta, 
                           COUNT(*) as servicios,
                           SUM(num_comensales) as comensales,
                           ROUND(SUM(total),2) as ingresos
                    FROM servicios WHERE estado='cerrado' AND YEAR(closed_at)=:a
                    GROUP BY DATE(closed_at) ORDER BY etiqueta DESC LIMIT 30";
                break;
            case 'semana':
                $sql = "SELECT YEARWEEK(closed_at,1) as etiqueta,
                           COUNT(*) as servicios,
                           SUM(num_comensales) as comensales,
                           ROUND(SUM(total),2) as ingresos
                    FROM servicios WHERE estado='cerrado' AND YEAR(closed_at)=:a
                    GROUP BY YEARWEEK(closed_at,1) ORDER BY etiqueta DESC LIMIT 20";
                break;
            case 'mes':
                $sql = "SELECT DATE_FORMAT(closed_at,'%Y-%m') as etiqueta,
                           COUNT(*) as servicios,
                           SUM(num_comensales) as comensales,
                           ROUND(SUM(total),2) as ingresos
                    FROM servicios WHERE estado='cerrado' AND YEAR(closed_at)=:a
                    GROUP BY DATE_FORMAT(closed_at,'%Y-%m') ORDER BY etiqueta DESC";
                break;
            default: // año — NO usa :a, ejecutar sin parámetros
                $stmt = $this->conexion->query(
                    "SELECT YEAR(closed_at) as etiqueta,
                        COUNT(*) as servicios,
                        SUM(num_comensales) as comensales,
                        ROUND(SUM(total),2) as ingresos
                 FROM servicios WHERE estado='cerrado'
                 GROUP BY YEAR(closed_at) ORDER BY etiqueta DESC"
                );
                return $stmt->fetchAll();
        }
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':a' => $año]);
        return $stmt->fetchAll();
    }

    public function getTopPlatos(int $limite = 10): array {
        $sql = "SELECT mi.nombre_es, mi.precio, c.nombre_es AS categoria, c.tipo,
                       SUM(p.cantidad) AS total_vendido,
                       SUM(p.subtotal) AS total_ingresos
                FROM pedidos p
                JOIN menu_items mi ON p.item_id = mi.id
                JOIN categorias_menu c ON mi.categoria_id = c.id
                WHERE p.estado != 'cancelado'
                GROUP BY p.item_id ORDER BY total_vendido DESC LIMIT :l";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':l', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getResumenHoy(): array {
        $hoy = date('Y-m-d');
        $sql = "SELECT 
                    COUNT(DISTINCT s.id) AS servicios_hoy,
                    SUM(s.num_comensales) AS comensales_hoy,
                    ROUND(SUM(CASE WHEN s.estado='cerrado' THEN s.total ELSE 0 END),2) AS ingresos_hoy,
                    COUNT(DISTINCT r.id) AS reservas_hoy
                FROM servicios s
                LEFT JOIN reservas r ON r.fecha = :f AND r.estado IN ('pendiente','confirmada')
                WHERE s.fecha = :f2";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':f' => $hoy, ':f2' => $hoy]);
        return $stmt->fetch() ?? [];
    }

    // ╔══════════════════════════════════════════════════════════╗
    // ║                   CONFIGURACIÓN                          ║
    // ╚══════════════════════════════════════════════════════════╝

    public function getConfiguracion(): ?array {
        return $this->conexion->query("SELECT * FROM configuracion WHERE id=1")->fetch() ?: null;
    }

    public function actualizarConfiguracion(array $datos): bool {
        $campos = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($datos)));
        $sql    = "UPDATE configuracion SET $campos WHERE id = 1";
        $stmt   = $this->conexion->prepare($sql);
        $params = [];
        foreach ($datos as $k => $v) $params[":$k"] = $v;
        return $stmt->execute($params);
    }
    public function getServicioPorId(int $id): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT s.*, m.numero AS mesa, z.nombre AS zona, t.nombre AS turno
         FROM servicios s
         JOIN mesas m ON s.mesa_id = m.id
         JOIN zonas z ON m.zona_id = z.id
         JOIN turnos t ON s.turno_id = t.id
         WHERE s.id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }
    public function reactivarUsuario(string $email): void {
        $stmt = $this->conexion->prepare("UPDATE usuarios SET activo = 1 WHERE email = :email");
        $stmt->execute([':email' => $email]);
    }

}
