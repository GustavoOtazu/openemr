<?php

/**
 * Nursing Evaluations Form - new.php
 * Neurological assessment form for inpatients (Glasgow Scale, consciousness, pupils, etc.)
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    OpenEMR Contributors
 * @copyright Copyright (c) 2026 OpenEMR Contributors
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");
use OpenEMR\Common\Csrf\CsrfUtils;
// Get parameters
$pid       = isset($_GET['pid'])       ? (int)$_GET['pid']       : (int)($_SESSION['pid'] ?? 0);
$encounter = isset($_GET['encounter']) ? (int)$_GET['encounter'] : (int)($_SESSION['encounter'] ?? 0);
$id        = isset($_GET['id'])        ? (int)$_GET['id']        : 0;
if (!$pid || !$encounter) {
    die(xlt("Error: Missing required parameters (PID or Encounter)"));
}

$is_edit = ($id > 0);
// Initialize field variables
$conciencia        = '';
$obs_conciencia    = '';
$tono              = '';
$obs_tono          = '';
$pupilas           = '';
$obs_pupilas       = '';
$mucosas           = '';
$obs_mucosas       = '';
$glasgow_ojos      = '';
$obs_glasgow_ojos  = '';
$glasgow_motora    = '';
$obs_glasgow_motora = '';
$glasgow_verbal    = '';
$obs_glasgow_verbal = '';
$glasgow_total     = 0;
$hora_evaluacion   = '';
// Load existing data in edit mode
if ($is_edit) {
    $row = sqlQuery("SELECT * FROM form_evaluaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1", array($id, $pid, $encounter));
    if ($row) {
        $conciencia         = $row['conciencia']         ?? '';
        $obs_conciencia     = $row['obs_conciencia']     ?? '';
        $tono               = $row['tono']               ?? '';
        $obs_tono           = $row['obs_tono']           ?? '';
        $pupilas            = $row['pupilas']            ?? '';
        $obs_pupilas        = $row['obs_pupilas']        ?? '';
        $mucosas            = $row['mucosas']            ?? '';
        $obs_mucosas        = $row['obs_mucosas']        ?? '';
        $glasgow_ojos       = $row['glasgow_ojos']       ?? '';
        $obs_glasgow_ojos   = $row['obs_glasgow_ojos']   ?? '';
        $glasgow_motora     = $row['glasgow_motora']     ?? '';
        $obs_glasgow_motora = $row['obs_glasgow_motora'] ?? '';
        $glasgow_verbal     = $row['glasgow_verbal']     ?? '';
        $obs_glasgow_verbal = $row['obs_glasgow_verbal'] ?? '';
        $glasgow_total      = (int)($row['glasgow_total'] ?? 0);
        $hora_evaluacion    = $row['hora_evaluacion']    ?? '';
    } else {
        die(xlt("Error: Record not found or insufficient permissions."));
    }
}

$page_title = $is_edit ? xlt('Edit Nursing Evaluation') : xlt('New Nursing Evaluation');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo text($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
    <style>
        .evaluaciones-form * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .header .subtitle { font-size: 14px; opacity: 0.9; }

        .form-content { padding: 40px; }

        .form-group {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group.glasgow-section {
            border-left: 4px solid #e74c3c;
            background: #fdf2f2;
        }

        .form-group h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .form-group h4 {
            color: #34495e;
            margin: 20px 0 15px 0;
            font-size: 16px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 8px;
        }

        .radio-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }

        .radio-container label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            min-width: 180px;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .radio-container label:hover { background-color: #f8f9fa; }
        .radio-container input[type="radio"] { width: 18px; height: 18px; cursor: pointer; }

        .observaciones {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
            transition: border-color 0.3s;
        }

        .observaciones:focus { outline: none; border-color: #3498db; }

        .hora-grupo { margin-top: 20px; }
        .hora-grupo label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        .hora-grupo input[type="time"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .hora-grupo input[type="time"]:focus { outline: none; border-color: #3498db; }

        .form-actions { display: flex; gap: 15px; margin-top: 30px; }

        .btn {
            flex: 1;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary { background: #3498db; color: white; }
        .btn-primary:hover { background: #2980b9; transform: translateY(-2px); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; transform: translateY(-2px); }

        .mode-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .mode-create { background: #28a745; color: white; }
        .mode-edit   { background: #ffc107; color: #000; }

        .glasgow-info {
            background: #e8f4f8;
            border: 1px solid #bee5eb;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
        }

        .glasgow-info h5 { color: #0c5460; margin-bottom: 10px; font-size: 16px; }
        .glasgow-info p  { color: #0c5460; margin: 5px 0; font-size: 14px; }
    </style>
</head>
<body>
<div class="evaluaciones-form container">
    <div class="header">
        <h1>
            <?php echo text($page_title); ?>
            <span class="mode-badge <?php echo attr($is_edit ? 'mode-edit' : 'mode-create'); ?>">
                <?php echo $is_edit ? xlt('Edit Mode') : xlt('Create Mode'); ?>
            </span>
        </h1>
        <div class="subtitle">
            <?php echo xlt('Encounter'); ?>: <?php echo text($encounter); ?>
        </div>
    </div>

    <div class="form-content">
        <form method="POST" action="save.php" id="formEvaluaciones" onsubmit="top.restoreSession();">
            <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>">
            <input type="hidden" name="pid"       value="<?php echo attr($pid); ?>">
            <input type="hidden" name="encounter" value="<?php echo attr($encounter); ?>">
            <?php if ($is_edit) :
                ?>
            <input type="hidden" name="id" value="<?php echo attr($id); ?>">
                <?php
            endif; ?>

            <!-- CONSCIOUSNESS -->
            <div class="form-group">
                <h3><?php echo xlt('Consciousness'); ?></h3>
                <div class="radio-container">
                    <?php
                    $consciousness_options = ['VIGIL', 'SOMNOLIENTO', 'ESTUPOROSO', 'COMATOSO'];
                    foreach ($consciousness_options as $opt) :
                        ?>
                    <label>
                        <input type="radio" name="conciencia" value="<?php echo attr($opt); ?>"
                               <?php echo ($conciencia === $opt) ? 'checked' : ''; ?>>
                        <?php echo xlt($opt); ?>
                    </label>
                        <?php
                    endforeach; ?>
                </div>
                <textarea name="obs_conciencia" class="observaciones"
                          placeholder="<?php echo attr(xlt('Observations about consciousness...')); ?>"><?php echo text($obs_conciencia); ?></textarea>
            </div>

            <!-- MUSCLE TONE -->
            <div class="form-group">
                <h3><?php echo xlt('Muscle Tone'); ?></h3>
                <div class="radio-container">
                    <?php
                    $tone_options = ['NORMAL', 'FLACIDO', 'ESPASTICO'];
                    foreach ($tone_options as $opt) :
                        ?>
                    <label>
                        <input type="radio" name="tono" value="<?php echo attr($opt); ?>"
                               <?php echo ($tono === $opt) ? 'checked' : ''; ?>>
                        <?php echo xlt($opt); ?>
                    </label>
                        <?php
                    endforeach; ?>
                </div>
                <textarea name="obs_tono" class="observaciones"
                          placeholder="<?php echo attr(xlt('Observations about muscle tone...')); ?>"><?php echo text($obs_tono); ?></textarea>
            </div>

            <!-- PUPILS -->
            <div class="form-group">
                <h3><?php echo xlt('Pupils'); ?></h3>
                <div class="radio-container">
                    <?php
                    $pupil_options = ['NORMAL', 'MIDRIASIS', 'MIOSIS'];
                    foreach ($pupil_options as $opt) :
                        ?>
                    <label>
                        <input type="radio" name="pupilas" value="<?php echo attr($opt); ?>"
                               <?php echo ($pupilas === $opt) ? 'checked' : ''; ?>>
                        <?php echo xlt($opt); ?>
                    </label>
                        <?php
                    endforeach; ?>
                </div>
                <textarea name="obs_pupilas" class="observaciones"
                          placeholder="<?php echo attr(xlt('Observations about pupils...')); ?>"><?php echo text($obs_pupilas); ?></textarea>
            </div>

            <!-- MUCOUS MEMBRANES -->
            <div class="form-group">
                <h3><?php echo xlt('Mucous Membranes'); ?></h3>
                <div class="radio-container">
                    <?php
                    $mucosa_options = ['SECA', 'HUMEDA', 'PALIDA', 'ICTERICA', 'CIANOSIS'];
                    foreach ($mucosa_options as $opt) :
                        ?>
                    <label>
                        <input type="radio" name="mucosas" value="<?php echo attr($opt); ?>"
                               <?php echo ($mucosas === $opt) ? 'checked' : ''; ?>>
                        <?php echo xlt($opt); ?>
                    </label>
                        <?php
                    endforeach; ?>
                </div>
                <textarea name="obs_mucosas" class="observaciones"
                          placeholder="<?php echo attr(xlt('Observations about mucous membranes...')); ?>"><?php echo text($obs_mucosas); ?></textarea>
            </div>

            <!-- GLASGOW COMA SCALE -->
            <div class="form-group glasgow-section">
                <h3><?php echo xlt('Glasgow Coma Scale'); ?></h3>

                <h4><?php echo xlt('Eye Opening'); ?></h4>
                <div class="radio-container">
                    <?php
                    $eye_options = [
                        'ESPONTANEAMENTE'       => xlt('Spontaneously') . ' (4)',
                        'A ESTIMULOS AUDITIVOS' => xlt('To auditory stimuli') . ' (3)',
                        'AL DOLOR'              => xlt('To pain') . ' (2)',
                        'SIN RESPUESTA'         => xlt('No response') . ' (1)',
                    ];
                    foreach ($eye_options as $val => $label) :
                        ?>
                    <label>
                        <input type="radio" name="glasgow_ojos" value="<?php echo attr($val); ?>"
                               <?php echo ($glasgow_ojos === $val) ? 'checked' : ''; ?>>
                        <?php echo text($label); ?>
                    </label>
                        <?php
                    endforeach; ?>
                </div>
                <textarea name="obs_glasgow_ojos" class="observaciones"
                          placeholder="<?php echo attr(xlt('Observations about eye opening...')); ?>"><?php echo text($obs_glasgow_ojos); ?></textarea>

                <h4><?php echo xlt('Motor Response'); ?></h4>
                <div class="radio-container">
                    <?php
                    $motor_options = [
                        'OBEDECE ORDENES'    => xlt('Obeys commands') . ' (6)',
                        'LOCALIZA DOLOR'     => xlt('Localizes pain') . ' (5)',
                        'FLEXION DE DEFENSA' => xlt('Withdrawal') . ' (4)',
                        'FLEXION ANORMAL'    => xlt('Abnormal flexion') . ' (3)',
                        'EXTENSION ANORMAL'  => xlt('Abnormal extension') . ' (2)',
                        'NINGUNA'            => xlt('No response') . ' (1)',
                    ];
                    foreach ($motor_options as $val => $label) :
                        ?>
                    <label>
                        <input type="radio" name="glasgow_motora" value="<?php echo attr($val); ?>"
                               <?php echo ($glasgow_motora === $val) ? 'checked' : ''; ?>>
                        <?php echo text($label); ?>
                    </label>
                        <?php
                    endforeach; ?>
                </div>
                <textarea name="obs_glasgow_motora" class="observaciones"
                          placeholder="<?php echo attr(xlt('Observations about motor response...')); ?>"><?php echo text($obs_glasgow_motora); ?></textarea>

                <h4><?php echo xlt('Verbal Response'); ?></h4>
                <div class="radio-container">
                    <?php
                    $verbal_options = [
                        'ORIENTADO Y CONVERSA'    => xlt('Oriented and conversing') . ' (5)',
                        'DESORIENTADO Y CONVERSA' => xlt('Disoriented and conversing') . ' (4)',
                        'LENGUAJE INADECUADO'     => xlt('Inappropriate words') . ' (3)',
                        'SONIDOS INCOMPRENSIBLES' => xlt('Incomprehensible sounds') . ' (2)',
                        'NINGUNA'                 => xlt('No response') . ' (1)',
                    ];
                    foreach ($verbal_options as $val => $label) :
                        ?>
                    <label>
                        <input type="radio" name="glasgow_verbal" value="<?php echo attr($val); ?>"
                               <?php echo ($glasgow_verbal === $val) ? 'checked' : ''; ?>>
                        <?php echo text($label); ?>
                    </label>
                        <?php
                    endforeach; ?>
                </div>
                <textarea name="obs_glasgow_verbal" class="observaciones"
                          placeholder="<?php echo attr(xlt('Observations about verbal response...')); ?>"><?php echo text($obs_glasgow_verbal); ?></textarea>

                <div class="glasgow-info">
                    <h5><?php echo xlt('Glasgow Total Score'); ?>: <span id="glasgowTotal"><?php echo text($glasgow_total ?: '0'); ?></span>/15</h5>
                    <p><strong>13-15:</strong> <?php echo xlt('Mild injury'); ?></p>
                    <p><strong>9-12:</strong>  <?php echo xlt('Moderate injury'); ?></p>
                    <p><strong>3-8:</strong>   <?php echo xlt('Severe injury'); ?></p>
                </div>
            </div>

            <!-- EVALUATION TIME -->
            <div class="form-group">
                <div class="hora-grupo">
                    <label for="hora_evaluacion"><?php echo xlt('Evaluation Time'); ?>:</label>
                    <input type="time" name="hora_evaluacion" id="hora_evaluacion"
                           value="<?php echo attr($hora_evaluacion); ?>">
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?php echo $is_edit ? xlt('Save Changes') : xlt('Save'); ?>
                </button>
                <a href="<?php echo attr($GLOBALS['webroot'] . '/interface/tableros/lista_internados.php'); ?>"
                   class="btn btn-secondary">
                    <?php echo xlt('Cancel'); ?>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var isEdit      = <?php echo ($is_edit ? 'true' : 'false'); ?>;
        var horaInput   = document.getElementById('hora_evaluacion');

        // Auto-fill current time only in create mode
        if (!isEdit && horaInput.value === '') {
            var now = new Date();
            horaInput.value =
                String(now.getHours()).padStart(2, '0') + ':' +
                String(now.getMinutes()).padStart(2, '0');
        }

        calcularGlasgow();

        document.querySelectorAll('input[name^="glasgow_"]').forEach(function (radio) {
            radio.addEventListener('change', calcularGlasgow);
        });
    });

    function calcularGlasgow() {
        var scores = {
            glasgow_ojos: {
                'ESPONTANEAMENTE': 4, 'A ESTIMULOS AUDITIVOS': 3,
                'AL DOLOR': 2, 'SIN RESPUESTA': 1
            },
            glasgow_motora: {
                'OBEDECE ORDENES': 6, 'LOCALIZA DOLOR': 5,
                'FLEXION DE DEFENSA': 4, 'FLEXION ANORMAL': 3,
                'EXTENSION ANORMAL': 2, 'NINGUNA': 1
            },
            glasgow_verbal: {
                'ORIENTADO Y CONVERSA': 5, 'DESORIENTADO Y CONVERSA': 4,
                'LENGUAJE INADECUADO': 3, 'SONIDOS INCOMPRENSIBLES': 2,
                'NINGUNA': 1
            }
        };

        var total = 0;
        Object.keys(scores).forEach(function (field) {
            var checked = document.querySelector('input[name="' + field + '"]:checked');
            if (checked) {
                total += scores[field][checked.value] || 0;
            }
        });

        document.getElementById('glasgowTotal').textContent = total;
    }
</script>
</body>
</html>
