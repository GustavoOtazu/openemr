<?php

/**
 * Nursing Evaluations Form - view.php
 * Displays evaluation records for a patient encounter, with PDF export.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    OpenEMR Contributors
 * @copyright Copyright (c) 2026 OpenEMR Contributors
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
$pid       = isset($_GET['pid'])       ? (int)$_GET['pid']       : (int)($_SESSION['pid'] ?? 0);
$encounter = isset($_GET['encounter']) ? (int)$_GET['encounter'] : (int)($_SESSION['encounter'] ?? 0);
$id        = isset($_GET['id'])        ? (int)$_GET['id']        : 0;
if (!$pid || !$encounter) {
    echo "<div style='padding:20px;color:red;'>" . xlt("Could not retrieve PID or Encounter.") . "</div>";
    exit;
}

// Get patient info
$paciente = sqlQuery("SELECT CONCAT(fname, ' ', lname) AS full_name, pubpid, DOB FROM patient_data WHERE pid = ?", array($pid));
// Calculate age
$age = '';
if (!empty($paciente['DOB'])) {
    $dob = new DateTime($paciente['DOB']);
    $age  = (new DateTime())->diff($dob)->y . ' ' . xlt('years');
}

if ($id > 0) {
    $result = sqlStatement("SELECT * FROM form_evaluaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1", array($id, $pid, $encounter));
} else {
    $result = sqlStatement("SELECT * FROM form_evaluaciones WHERE pid = ? AND encounter = ? ORDER BY date DESC", array($pid, $encounter));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo xlt('Nursing Evaluations'); ?></title>
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
    <style>
        .evaluaciones-view * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
            font-size: 24px;
        }
        .info-paciente {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .info-paciente h3 { color: #495057; font-size: 16px; margin-bottom: 15px; font-weight: 600; }
        .info-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-top: 15px; }
        .info-item { background-color: white; padding: 10px 15px; border-radius: 3px; border: 1px solid #e0e0e0; }
        .info-item strong { color: #495057; font-size: 12px; display: block; margin-bottom: 5px; }
        .info-item span   { color: #333; font-size: 15px; font-weight: 500; }

        .registro-card {
            background-color: white;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .registro-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
        .registro-fecha { display: flex; align-items: center; gap: 15px; }
        .fecha-principal { font-size: 18px; font-weight: 600; color: #333; }
        .hora-principal  { font-size: 14px; color: #6c757d; }
        .registro-acciones { display: flex; gap: 12px; }

        .btn-accion {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-editar   { background-color: #007bff; color: white; }
        .btn-editar:hover   { background-color: #0056b3; }
        .btn-imprimir { background-color: #28a745; color: white; }
        .btn-imprimir:hover { background-color: #218838; }

        .registro-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }
        .info-box-small { background-color: #f8f9fa; padding: 12px 15px; border-radius: 3px; border: 1px solid #dee2e6; }
        .info-box-small strong { color: #495057; font-size: 12px; display: block; margin-bottom: 5px; }
        .info-box-small span   { color: #333; font-size: 16px; font-weight: 500; }

        .seccion-titulo {
            font-size: 15px;
            font-weight: 700;
            color: #495057;
            margin: 20px 0 15px 0;
            padding: 10px 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 5px solid #667eea;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .evaluacion-detalle { background-color: white; border: 1px solid #dee2e6; border-radius: 3px; margin-bottom: 12px; overflow: hidden; }
        .evaluacion-header  { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background-color: #f8f9fa; border-left: 4px solid #6c757d; }
        .evaluacion-nombre  { font-size: 14px; font-weight: 600; color: #333; }
        .valor-badge        { padding: 6px 14px; border-radius: 3px; font-size: 12px; font-weight: 600; background-color: #007bff; color: white; }
        .evaluacion-obs     { padding: 18px 20px; background-color: #f8f9fa; }
        .evaluacion-obs.con-contenido { background: linear-gradient(135deg, #e7f3ff 0%, #cfe2ff 100%); border-top: 3px solid #0d6efd; }
        .evaluacion-obs h5  { font-size: 12px; color: #6c757d; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .evaluacion-obs p   { font-size: 14px; color: #212529; line-height: 1.7; white-space: pre-wrap; }

        .glasgow-section {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            padding: 20px 25px;
            border-radius: 12px;
            margin-top: 25px;
            border-left: 5px solid #ffc107;
        }
        .glasgow-section h4 { color: #856404; font-size: 16px; margin-bottom: 12px; font-weight: 700; text-transform: uppercase; }
        .glasgow-score {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border-left: 5px solid #28a745;
            text-align: center;
        }
        .glasgow-score h3 { color: #155724; font-size: 24px; margin-bottom: 10px; }
        .glasgow-score p  { color: #155724; font-size: 16px; font-weight: 600; }

        .no-registros { text-align: center; padding: 80px 40px; color: #999; background-color: #f8f9fa; border-radius: 15px; margin-top: 20px; }

        @media print {
            body { background: white; padding: 0; }
            .evaluaciones-view { box-shadow: none; border-radius: 0; padding: 20px; }
            .btn-accion { display: none !important; }
        }
    </style>
</head>
<body>
<div class="evaluaciones-view container">
    <h2><?php echo $id > 0 ? xlt('Evaluation Detail') : xlt('Nursing Evaluations List'); ?></h2>

    <div class="info-paciente">
        <h3><?php echo xlt('Patient Information'); ?></h3>
        <div class="info-row">
            <div class="info-item">
                <strong><?php echo xlt('Patient'); ?>:</strong>
                <span><?php echo text($paciente['full_name'] ?? xlt('Not available')); ?></span>
            </div>
            <div class="info-item">
                <strong><?php echo xlt('ID'); ?>:</strong>
                <span><?php echo text($paciente['pubpid'] ?? xlt('Not available')); ?></span>
            </div>
            <?php if (!empty($age)) :
                ?>
            <div class="info-item">
                <strong><?php echo xlt('Age'); ?>:</strong>
                <span><?php echo text($age); ?></span>
            </div>
                <?php
            endif; ?>
        </div>
    </div>

    <?php
    if (sqlNumRows($result) == 0) {
        echo "<div class='no-registros'><h3>" . xlt('No evaluations recorded') . "</h3></div>";
    }

    while ($row = sqlFetchArray($result)) :
        $glasgow      = (int)($row['glasgow_total'] ?? 0);
        $glasgow_color = '#28a745';
        $glasgow_level = xlt('Mild');
        if ($glasgow < 9) {
            $glasgow_color = '#dc3545';
            $glasgow_level = xlt('Severe');
        } elseif ($glasgow < 13) {
            $glasgow_color = '#ffc107';
            $glasgow_level = xlt('Moderate');
        }
        ?>

    <div class="registro-card" id="registro-<?php echo attr($row['id']); ?>">
        <div class="registro-header">
            <div class="registro-fecha">
                <span class="fecha-principal"><?php echo text(date('d/m/Y', strtotime($row['date']))); ?></span>
                <span class="hora-principal"><?php echo text(date('H:i', strtotime($row['date']))); ?></span>
            </div>
            <div class="registro-acciones">
                <a href="<?php echo attr($GLOBALS['webroot'] . '/interface/forms/evaluaciones/new.php?pid=' . $pid . '&encounter=' . $encounter . '&id=' . $row['id']); ?>"
                   class="btn-accion btn-editar">
                    <?php echo xlt('Edit'); ?>
                </a>
                <a href="<?php echo attr($GLOBALS['webroot'] . '/interface/forms/evaluaciones/print.php?pid=' . $pid . '&encounter=' . $encounter . '&id=' . $row['id']); ?>"
                   target="_blank" class="btn-accion btn-imprimir">
                    <?php echo xlt('Print'); ?>
                </a>
            </div>
        </div>

        <div class="registro-info">
            <div class="info-box-small">
                <strong><?php echo xlt('Evaluation Time'); ?>:</strong>
                <span><?php echo text($row['hora_evaluacion'] ?? 'N/A'); ?></span>
            </div>
            <div class="info-box-small">
                <strong><?php echo xlt('User'); ?>:</strong>
                <span><?php echo text($row['user'] ?? 'N/A'); ?></span>
            </div>
        </div>

        <div class="glasgow-score">
            <h3 style="color:<?php echo attr($glasgow_color); ?>">
                <?php echo xlt('Glasgow'); ?>: <?php echo text($glasgow); ?>/15
            </h3>
            <p style="color:<?php echo attr($glasgow_color); ?>">
                <?php echo xlt('Injury'); ?>: <?php echo text($glasgow_level); ?>
            </p>
        </div>

        <div class="seccion-titulo"><?php echo xlt('Basic Assessments'); ?></div>

        <?php
        $basic_fields = [
            'conciencia' => ['label' => xlt('Consciousness'), 'obs' => 'obs_conciencia'],
            'tono'       => ['label' => xlt('Muscle Tone'),   'obs' => 'obs_tono'],
            'pupilas'    => ['label' => xlt('Pupils'),        'obs' => 'obs_pupilas'],
            'mucosas'    => ['label' => xlt('Mucous Membranes'), 'obs' => 'obs_mucosas'],
        ];
        foreach ($basic_fields as $field => $meta) :
            $val = $row[$field] ?? 'N/A';
            $obs = $row[$meta['obs']] ?? '';
            ?>
        <div class="evaluacion-detalle">
            <div class="evaluacion-header">
                <div class="evaluacion-nombre"><?php echo text($meta['label']); ?></div>
                <span class="valor-badge"><?php echo text($val); ?></span>
            </div>
            <div class="evaluacion-obs <?php echo !empty($obs) ? 'con-contenido' : ''; ?>">
                <h5><?php echo xlt('Observations'); ?></h5>
                <p><?php echo !empty($obs) ? nl2br(text($obs)) : xlt('No observations recorded'); ?></p>
            </div>
        </div>
                <?php
        endforeach; ?>

        <div class="glasgow-section">
            <h4><?php echo xlt('Glasgow Coma Scale - Detailed'); ?></h4>

            <?php
            $glasgow_fields = [
                'glasgow_ojos'   => ['label' => xlt('Eye Opening'),     'obs' => 'obs_glasgow_ojos'],
                'glasgow_motora' => ['label' => xlt('Motor Response'),  'obs' => 'obs_glasgow_motora'],
                'glasgow_verbal' => ['label' => xlt('Verbal Response'), 'obs' => 'obs_glasgow_verbal'],
            ];
            foreach ($glasgow_fields as $field => $meta) :
                $val = $row[$field] ?? 'N/A';
                $obs = $row[$meta['obs']] ?? '';
                ?>
            <div class="evaluacion-detalle">
                <div class="evaluacion-header">
                    <div class="evaluacion-nombre"><?php echo text($meta['label']); ?></div>
                    <span class="valor-badge"><?php echo text($val); ?></span>
                </div>
                <div class="evaluacion-obs <?php echo !empty($obs) ? 'con-contenido' : ''; ?>">
                    <h5><?php echo xlt('Observations'); ?></h5>
                    <p><?php echo !empty($obs) ? nl2br(text($obs)) : xlt('No observations recorded'); ?></p>
                </div>
            </div>
                        <?php
            endforeach; ?>
        </div>
    </div>

        <?php
    endwhile; ?>
</div>

</body>
</html>
