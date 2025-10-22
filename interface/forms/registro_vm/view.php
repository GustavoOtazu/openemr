<?php
/**
 * Formulario de Registro VM - view.php
 * Ruta: interface/forms/registro_vm/view.php
 */

include_once("../../globals.php");

$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;

if (!$pid || !$encounter) {
    echo "<div class='alert alert-danger'>No se pudo obtener PID o Encounter.</div>";
    exit;
}

$sql = "SELECT * FROM form_registro_vm WHERE pid = ? AND encounter = ? AND activity = 1 ORDER BY date DESC";
$result = sqlStatement($sql, array($pid, $encounter));

$registros = array();
while ($row = sqlFetchArray($result)) {
    $registros[] = $row;
}
?>

<style>
    .vm-view-container { padding: 20px; font-family: Arial, sans-serif; }
    .vm-view-container h3 { color: #007bff; margin-bottom: 20px; }
    .vm-view-container table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .vm-view-container table th {
        background-color: #007bff;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: bold;
    }
    .vm-view-container table td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    .vm-view-container .btn-ver {
        background-color: #28a745;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .vm-view-container .btn-ver:hover { background-color: #218838; }
    .vm-view-container .sin-registros {
        padding: 20px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        color: #721c24;
        margin-top: 20px;
    }
</style>

<div class="vm-view-container">
    <h3>Registros de VM</h3>
    
    <?php if (count($registros) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Fecha/Hora</th>
                    <th>Modo Ventilación</th>
                    <th>Hora Registro</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($registro['date'])); ?></td>
                        <td><?php echo strtoupper($registro['modo_ventilacion']); ?></td>
                        <td><?php echo date('H:i', strtotime($registro['hora_registro'])); ?></td>
                        <td><?php echo $registro['user']; ?></td>
                        <td>
                            <a href="<?php echo $GLOBALS['webroot']; ?>/interface/forms/registro_vm/report.php?id=<?php echo $registro['id']; ?>&pid=<?php echo $pid; ?>" 
                               class="btn-ver" target="_blank">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="sin-registros">
            <strong>No hay registros de VM para este encuentro.</strong>
        </div>
    <?php endif; ?>
</div>