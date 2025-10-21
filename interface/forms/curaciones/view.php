<?php
include_once("../../globals.php");

$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;

if (!$pid || !$encounter) {
    echo "<div class='alert alert-danger'>No se pudo obtener PID o Encounter.</div>";
    exit;
}

$sql = "SELECT * FROM form_curaciones WHERE pid = ? AND encounter = ? ORDER BY date DESC LIMIT 1";
$res = sqlQuery($sql, array($pid, $encounter));

if (!$res) {
    echo "<div class='alert alert-info'>No hay registros de Curaciones para este paciente.</div>";
    exit;
}

function siNo($val) {
    return $val ? 'Sí' : 'No';
}
?>

<link rel="stylesheet" href="../../library/css/bootstrap.min.css">
<style>
body { font-family: Verdana, Arial, sans-serif; font-size: 13px; color: #333; }
.reportTable { width: 100%; border-collapse: collapse; margin-top: 10px; }
.reportTable th { background-color: #f8f9fa; color: #333; padding: 8px; text-align: left; }
.reportTable td { padding: 8px; border-bottom: 1px solid #ddd; vertical-align: top; }
.reportTable tr:nth-child(even) { background-color: #fdfdfe; }
.reportTable tr:hover { background-color: #e6f2ff; }
.obs { background-color: #e9ecef; padding: 2px 6px; border-radius: 4px; margin-left:5px; display:inline-block; font-size: 12px; }
.edit-btn { float: right; margin-bottom: 10px; }
.header-title { font-weight: bold; font-size: 16px; text-align: center; padding: 10px; margin-bottom: 10px; }
</style>

<div>
    <button id="edit-curaciones-btn" class="btn btn-primary edit-btn">Editar Registro</button>
</div>

<div class="header-title">Curaciones - Último Registro</div>

<table class="reportTable">
    <tbody>
        <tr><th>Fecha</th><td><?php echo $res['date']; ?></td></tr>
        <tr><th>Herida Operatoría</th><td><?php echo siNo($res['herida_operatoria']); ?> <?php if($res['obs_herida_operatoria']) echo "<span class='obs'>".$res['obs_herida_operatoria']."</span>"; ?></td></tr>
        <tr><th>Traqueostomía</th><td><?php echo siNo($res['traqueostomia']); ?> <?php if($res['obs_traqueostomia']) echo "<span class='obs'>".$res['obs_traqueostomia']."</span>"; ?></td></tr>
        <tr><th>Ostomías</th><td><?php echo siNo($res['ostomias']); ?> <?php if($res['obs_ostomias']) echo "<span class='obs'>".$res['obs_ostomias']."</span>"; ?></td></tr>
        <tr><th>Escaras</th><td><?php echo siNo($res['escaras']); ?> <?php if($res['obs_escaras']) echo "<span class='obs'>".$res['obs_escaras']."</span>"; ?></td></tr>
        <tr><th>Vía Venosa Central</th><td><?php echo siNo($res['via_venosa_central']); ?> <?php if($res['obs_via_venosa_central']) echo "<span class='obs'>".$res['obs_via_venosa_central']."</span>"; ?></td></tr>
        <tr><th>Vía Venosa</th><td><?php echo siNo($res['via_venosa']); ?> <?php if($res['obs_via_venosa']) echo "<span class='obs'>".$res['obs_via_venosa']."</span>"; ?></td></tr>
        <tr><th>Hora de Curaciones</th><td><?php echo $res['hora_operacion']; ?></td></tr>
    </tbody>
</table>

<script src="../../library/js/jquery-min-3-1-1/index.js"></script>
<script>
$(document).ready(function() {
    $('#edit-curaciones-btn').click(function() {
        var pid_paciente = <?php echo json_encode($pid); ?>;
        var encounter = <?php echo json_encode($encounter); ?>;
        var form_id = <?php echo json_encode($res['id']); ?>;

        if (!pid_paciente || !encounter) {
            alert('Error: No se pudo obtener los datos del paciente');
            return;
        }

        var url = "<?php echo $GLOBALS['webroot']; ?>/interface/forms/curaciones/new.php?mode=update&id=" 
                    + encodeURIComponent(form_id) 
                    + "&pid=" + encodeURIComponent(pid_paciente) 
                    + "&encounter=" + encodeURIComponent(encounter);

        // Redirección confiable
        if (window.top && window.top.RTop) {
            window.top.RTop.location.href = url;
        } else if (window.top) {
            window.top.location.href = url;
        } else {
            window.location.href = url;
        }
    });
});
</script>
