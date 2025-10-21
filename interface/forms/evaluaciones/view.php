<?php
/**
 * Formulario de Evaluaciones - view.php
 * Muestra el registro en modo lectura con opción a editar.
 */

require_once(__DIR__ . '/../../../globals.php');
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");

// Obtener parámetros
$id = $_GET['id'] ?? 0;
$pid = $_GET['pid'] ?? 0;
$encounter = $_GET['encounter'] ?? 0;

// Obtener datos del formulario
$sql = "SELECT * FROM form_evaluaciones WHERE id = ?";
$res = sqlQuery($sql, [$id]);

if (!$res) {
    echo "<div style='padding: 20px; color: red;'>No se encontraron datos para esta evaluación.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación del Paciente</title>
    <link rel="stylesheet" href="<?php echo $webroot; ?>/public/assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fb;
            margin: 0;
            padding: 0;
        }
        .container-eval {
            max-width: 900px;
            background: #fff;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
        }
        .info-block {
            margin-bottom: 25px;
        }
        .info-title {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            font-size: 13px;
        }
        th {
            background-color: #f1f4f8;
            text-align: left;
            color: #333;
        }
        td {
            color: #555;
        }
        .glasgow {
            background-color: #fff3cd;
            font-weight: bold;
            color: #856404;
        }
        .glasgow-score {
            text-align: center;
            font-size: 16px;
            padding: 12px;
            margin-top: 20px;
            border-left: 4px solid #ffc107;
            background-color: #fff8e1;
            border-radius: 5px;
        }
        .glasgow-score strong {
            font-size: 18px;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease-in-out;
        }
        .btn-edit {
            background-color: #007bff;
            color: #fff;
        }
        .btn-edit:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-back:hover {
            background-color: #545b62;
        }
        .info-adicional {
            margin-top: 20px;
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            border-radius: 5px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container-eval">
    <h2>Evaluación del Paciente</h2>

    <div class="info-block">
        <table>
            <tr><th width="35%">Conciencia</th><td><?php echo htmlspecialchars($res['conciencia'] ?? '-'); ?></td></tr>
            <tr><th>Observación</th><td><?php echo htmlspecialchars($res['obs_conciencia'] ?? '-'); ?></td></tr>
            <tr><th>Tono</th><td><?php echo htmlspecialchars($res['tono'] ?? '-'); ?></td></tr>
            <tr><th>Observación</th><td><?php echo htmlspecialchars($res['obs_tono'] ?? '-'); ?></td></tr>
            <tr><th>Pupilas</th><td><?php echo htmlspecialchars($res['pupilas'] ?? '-'); ?></td></tr>
            <tr><th>Observación</th><td><?php echo htmlspecialchars($res['obs_pupilas'] ?? '-'); ?></td></tr>
            <tr><th>Mucosas</th><td><?php echo htmlspecialchars($res['mucosas'] ?? '-'); ?></td></tr>
            <tr><th>Observación</th><td><?php echo htmlspecialchars($res['obs_mucosas'] ?? '-'); ?></td></tr>
        </table>
    </div>

    <div class="info-block">
        <div class="info-title">Escala de Glasgow</div>
        <table>
            <tr class="glasgow">
                <th>Ojos Abiertos</th><td><?php echo htmlspecialchars($res['glasgow_ojos'] ?? '-'); ?></td>
            </tr>
            <tr class="glasgow">
                <th>Respuesta Motora</th><td><?php echo htmlspecialchars($res['glasgow_motora'] ?? '-'); ?></td>
            </tr>
            <tr class="glasgow">
                <th>Respuesta Verbal</th><td><?php echo htmlspecialchars($res['glasgow_verbal'] ?? '-'); ?></td>
            </tr>
        </table>

        <div class="glasgow-score">
            <strong>Puntaje Total: <?php echo htmlspecialchars($res['glasgow_total'] ?? 0); ?>/15</strong><br>
            <?php
            $puntaje = $res['glasgow_total'] ?? 0;
            if ($puntaje >= 13) {
                echo "<span style='color:green;'>Leve</span>";
            } elseif ($puntaje >= 9) {
                echo "<span style='color:orange;'>Moderado</span>";
            } else {
                echo "<span style='color:red;'>Severo</span>";
            }
            ?>
        </div>
    </div>

    <div class="info-adicional">
        <strong>Información del Registro:</strong><br>
        Hora de Evaluación: <?php echo htmlspecialchars($res['hora_evaluacion'] ?? '-'); ?><br>
        Fecha: <?php echo date('d/m/Y H:i', strtotime($res['date'])); ?>
    </div>

    <div class="buttons">
        <button class="btn btn-edit" id="btnEditar">Editar</button>
        <button class="btn btn-back" onclick="top.restoreSession(); top.RTop.location = '<?php echo $webroot; ?>/interface/patient_file/encounter/encounter_top.php?set_encounter=<?php echo attr($encounter); ?>&pid=<?php echo attr($pid); ?>';">Volver</button>
    </div>
</div>

<script>
document.getElementById('btnEditar').addEventListener('click', function() {
    top.restoreSession();
    const pid = "<?php echo $pid; ?>";
    const encounter = "<?php echo $encounter; ?>";
    const id = "<?php echo $id; ?>";
    const url = "<?php echo $webroot; ?>/interface/forms/evaluaciones/new.php?mode=edit&id=" + id + "&pid=" + pid + "&encounter=" + encounter;
    top.RTop.location = url;
});
</script>

</body>
</html>
