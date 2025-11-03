<?php
/**
 * Formulario de Registro VM - new.php (CON EDICIÓN)
 * Ruta: interface/forms/registro_vm/new.php
 * Soporta tanto CREAR como EDITAR registros
 * MODIFICADO: 100% consistente con patrón de Curaciones
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");

// Obtener parámetros
$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;
$id = $_GET['id'] ?? null; // ← NUEVO: Detectar si es modo edición

// Determinar si es modo CREAR o EDITAR
$modo_edicion = !empty($id);
$titulo = $modo_edicion ? "EDITAR REGISTRO VM" : "NUEVO REGISTRO VM";

// Variables para pre-llenar el formulario
$modo_ventilacion = '';
$obs_modo = '';
$presion = 0;
$obs_presion = '';
$volumen = 0;
$obs_volumen = '';
$simv = 0;
$obs_simv = '';
$psv = 0;
$obs_psv = '';
$otros = 0;
$obs_otros = '';
$frecuencia_respiratoria = 0;
$obs_frecuencia_respiratoria = '';
$p_inspiratorio = 0;
$obs_p_inspiratorio = '';
$p_media = 0;
$obs_p_media = '';
$p_max = 0;
$obs_p_max = '';
$chst = 0;
$obs_chst = '';
$disparo = 0;
$obs_disparo = '';
$fvt = 0;
$obs_fvt = '';
$vol_tidal = 0;
$obs_vol_tidal = '';
$vm_programado = 0;
$obs_vm_programado = '';
$petco2 = 0;
$obs_petco2 = '';
$vdvt = 0;
$obs_vdvt = '';
$ko2 = 0;
$obs_ko2 = '';
$hora_registro = '';

// Si es modo EDICIÓN, cargar datos existentes
if ($modo_edicion) {
    $sql = "SELECT * FROM form_registro_vm WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $row = sqlQuery($sql, array($id, $pid, $encounter));
    
    if ($row) {
        // Cargar valores existentes
        $modo_ventilacion = $row['modo_ventilacion'] ?? '';
        $obs_modo = $row['obs_modo'] ?? '';
        $presion = (int)$row['presion'];
        $obs_presion = $row['obs_presion'] ?? '';
        $volumen = (int)$row['volumen'];
        $obs_volumen = $row['obs_volumen'] ?? '';
        $simv = (int)$row['simv'];
        $obs_simv = $row['obs_simv'] ?? '';
        $psv = (int)$row['psv'];
        $obs_psv = $row['obs_psv'] ?? '';
        $otros = (int)$row['otros'];
        $obs_otros = $row['obs_otros'] ?? '';
        $frecuencia_respiratoria = (int)$row['frecuencia_respiratoria'];
        $obs_frecuencia_respiratoria = $row['obs_frecuencia_respiratoria'] ?? '';
        $p_inspiratorio = (int)$row['p_inspiratorio'];
        $obs_p_inspiratorio = $row['obs_p_inspiratorio'] ?? '';
        $p_media = (int)$row['p_media'];
        $obs_p_media = $row['obs_p_media'] ?? '';
        $p_max = (int)$row['p_max'];
        $obs_p_max = $row['obs_p_max'] ?? '';
        $chst = (int)$row['chst'];
        $obs_chst = $row['obs_chst'] ?? '';
        $disparo = (int)$row['disparo'];
        $obs_disparo = $row['obs_disparo'] ?? '';
        $fvt = (int)$row['fvt'];
        $obs_fvt = $row['obs_fvt'] ?? '';
        $vol_tidal = (int)$row['vol_tidal'];
        $obs_vol_tidal = $row['obs_vol_tidal'] ?? '';
        $vm_programado = (int)$row['vm_programado'];
        $obs_vm_programado = $row['obs_vm_programado'] ?? '';
        $petco2 = (int)$row['petco2'];
        $obs_petco2 = $row['obs_petco2'] ?? '';
        $vdvt = (int)$row['vdvt'];
        $obs_vdvt = $row['obs_vdvt'] ?? '';
        $ko2 = (int)$row['ko2'];
        $obs_ko2 = $row['obs_ko2'] ?? '';
        $hora_registro = $row['hora_registro'] ?? '';
    } else {
        // Registro no encontrado
        die("Error: Registro no encontrado o no tiene permisos para editarlo.");
    }
}

// Validación básica
if (!$pid || !$encounter) {
    die("Error: Faltan parámetros requeridos (PID o Encounter)");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
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

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-content {
            padding: 40px;
        }

        .form-group {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group-modo {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #1976d2;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .radio-container {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .radio-container label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            color: #333;
            cursor: pointer;
        }

        .radio-container input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

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

        .observaciones:focus {
            outline: none;
            border-color: #3498db;
        }

        .hora-grupo {
            margin-top: 20px;
        }

        .hora-grupo label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .hora-grupo input[type="time"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .hora-grupo input[type="time"]:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

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

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .modo-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .modo-crear {
            background: #28a745;
            color: white;
        }

        .modo-editar {
            background: #ffc107;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <?php echo $titulo; ?>
                <span class="modo-badge <?php echo $modo_edicion ? 'modo-editar' : 'modo-crear'; ?>">
                    <?php echo $modo_edicion ? '✏️ MODO EDICIÓN' : '➕ MODO CREACIÓN'; ?>
                </span>
            </h1>
            <div class="subtitle">
                Encounter: <?php echo htmlspecialchars($encounter); ?>
            </div>
        </div>

        <div class="form-content">
            <form method="POST" action="save.php" id="formRegistroVM">
                <!-- Campos ocultos -->
                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid); ?>">
                <input type="hidden" name="encounter" value="<?php echo htmlspecialchars($encounter); ?>">
                <?php if ($modo_edicion): ?>
                <!-- Campo ID para indicar que es una edición -->
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php endif; ?>

                <!-- MODO DE VENTILACIÓN (Especial) -->
                <div class="form-group-modo">
                    <h3>🫁 Modo de Ventilación</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="modo_ventilacion" 
                                   value="ESPONTANEA"
                                   <?php echo ($modo_ventilacion === 'ESPONTANEA') ? 'checked' : ''; ?>>
                            ESPONTÁNEA
                        </label>
                        <label>
                            <input type="radio" 
                                   name="modo_ventilacion" 
                                   value="VENTILACION MECANICA"
                                   <?php echo ($modo_ventilacion === 'VENTILACION MECANICA') ? 'checked' : ''; ?>>
                            VENTILACIÓN MECÁNICA
                        </label>
                    </div>
                    <textarea name="obs_modo" 
                              class="observaciones" 
                              placeholder="Observaciones sobre modo de ventilación..."><?php echo htmlspecialchars($obs_modo); ?></textarea>
                </div>

                <!-- PRESIÓN -->
                <div class="form-group">
                    <h3>📊 Presión</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="presion" 
                                   value="1"
                                   <?php echo $presion ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="presion" 
                                   value="0"
                                   <?php echo !$presion ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_presion" 
                              class="observaciones" 
                              placeholder="Observaciones sobre presión..."><?php echo htmlspecialchars($obs_presion); ?></textarea>
                </div>

                <!-- VOLUMEN -->
                <div class="form-group">
                    <h3>📏 Volumen</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="volumen" 
                                   value="1"
                                   <?php echo $volumen ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="volumen" 
                                   value="0"
                                   <?php echo !$volumen ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_volumen" 
                              class="observaciones" 
                              placeholder="Observaciones sobre volumen..."><?php echo htmlspecialchars($obs_volumen); ?></textarea>
                </div>

                <!-- SIMV -->
                <div class="form-group">
                    <h3>🔄 SIMV</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="simv" 
                                   value="1"
                                   <?php echo $simv ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="simv" 
                                   value="0"
                                   <?php echo !$simv ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_simv" 
                              class="observaciones" 
                              placeholder="Observaciones sobre SIMV..."><?php echo htmlspecialchars($obs_simv); ?></textarea>
                </div>

                <!-- PSV -->
                <div class="form-group">
                    <h3>🔧 PSV</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="psv" 
                                   value="1"
                                   <?php echo $psv ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="psv" 
                                   value="0"
                                   <?php echo !$psv ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_psv" 
                              class="observaciones" 
                              placeholder="Observaciones sobre PSV..."><?php echo htmlspecialchars($obs_psv); ?></textarea>
                </div>

                <!-- OTROS -->
                <div class="form-group">
                    <h3>⚙️ Otros</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="otros" 
                                   value="1"
                                   <?php echo $otros ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="otros" 
                                   value="0"
                                   <?php echo !$otros ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_otros" 
                              class="observaciones" 
                              placeholder="Observaciones sobre otros..."><?php echo htmlspecialchars($obs_otros); ?></textarea>
                </div>

                <!-- FRECUENCIA RESPIRATORIA -->
                <div class="form-group">
                    <h3>💨 Frecuencia Respiratoria</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="frecuencia_respiratoria" 
                                   value="1"
                                   <?php echo $frecuencia_respiratoria ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="frecuencia_respiratoria" 
                                   value="0"
                                   <?php echo !$frecuencia_respiratoria ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_frecuencia_respiratoria" 
                              class="observaciones" 
                              placeholder="Observaciones sobre frecuencia respiratoria..."><?php echo htmlspecialchars($obs_frecuencia_respiratoria); ?></textarea>
                </div>

                <!-- P.INSPIRATORIO / T.INSPIRATORIO -->
                <div class="form-group">
                    <h3>📈 P.Inspiratorio / T.Inspiratorio</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="p_inspiratorio" 
                                   value="1"
                                   <?php echo $p_inspiratorio ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="p_inspiratorio" 
                                   value="0"
                                   <?php echo !$p_inspiratorio ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_p_inspiratorio" 
                              class="observaciones" 
                              placeholder="Observaciones sobre P.Inspiratorio / T.Inspiratorio..."><?php echo htmlspecialchars($obs_p_inspiratorio); ?></textarea>
                </div>

                <!-- P.MEDIA / PEEP -->
                <div class="form-group">
                    <h3>📊 P.Media / PEEP</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="p_media" 
                                   value="1"
                                   <?php echo $p_media ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="p_media" 
                                   value="0"
                                   <?php echo !$p_media ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_p_media" 
                              class="observaciones" 
                              placeholder="Observaciones sobre P.Media / PEEP..."><?php echo htmlspecialchars($obs_p_media); ?></textarea>
                </div>

                <!-- P.MAX / P.PLATEAU -->
                <div class="form-group">
                    <h3>⬆️ P.Max / P.Plateau</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="p_max" 
                                   value="1"
                                   <?php echo $p_max ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="p_max" 
                                   value="0"
                                   <?php echo !$p_max ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_p_max" 
                              class="observaciones" 
                              placeholder="Observaciones sobre P.Max / P.Plateau..."><?php echo htmlspecialchars($obs_p_max); ?></textarea>
                </div>

                <!-- CHST / CDIN -->
                <div class="form-group">
                    <h3>🔍 CHST / CDIN</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="chst" 
                                   value="1"
                                   <?php echo $chst ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="chst" 
                                   value="0"
                                   <?php echo !$chst ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_chst" 
                              class="observaciones" 
                              placeholder="Observaciones sobre CHST / CDIN..."><?php echo htmlspecialchars($obs_chst); ?></textarea>
                </div>

                <!-- DISPARO POR F/P -->
                <div class="form-group">
                    <h3>🎯 Disparo por F/P</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="disparo" 
                                   value="1"
                                   <?php echo $disparo ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="disparo" 
                                   value="0"
                                   <?php echo !$disparo ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_disparo" 
                              class="observaciones" 
                              placeholder="Observaciones sobre Disparo por F/P..."><?php echo htmlspecialchars($obs_disparo); ?></textarea>
                </div>

                <!-- F / VT -->
                <div class="form-group">
                    <h3>📈 F / VT</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="fvt" 
                                   value="1"
                                   <?php echo $fvt ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="fvt" 
                                   value="0"
                                   <?php echo !$fvt ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_fvt" 
                              class="observaciones" 
                              placeholder="Observaciones sobre F / VT..."><?php echo htmlspecialchars($obs_fvt); ?></textarea>
                </div>

                <!-- VOL.TIDAL / FLUJO -->
                <div class="form-group">
                    <h3>💧 Vol.Tidal / Flujo</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="vol_tidal" 
                                   value="1"
                                   <?php echo $vol_tidal ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="vol_tidal" 
                                   value="0"
                                   <?php echo !$vol_tidal ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_vol_tidal" 
                              class="observaciones" 
                              placeholder="Observaciones sobre Vol.Tidal / Flujo..."><?php echo htmlspecialchars($obs_vol_tidal); ?></textarea>
                </div>

                <!-- VM PROGRAMADO / MEDIDO -->
                <div class="form-group">
                    <h3>⚙️ VM Programado / Medido</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="vm_programado" 
                                   value="1"
                                   <?php echo $vm_programado ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="vm_programado" 
                                   value="0"
                                   <?php echo !$vm_programado ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_vm_programado" 
                              class="observaciones" 
                              placeholder="Observaciones sobre VM Programado / Medido..."><?php echo htmlspecialchars($obs_vm_programado); ?></textarea>
                </div>

                <!-- PETCO2 -->
                <div class="form-group">
                    <h3>🌬️ PETCO2</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="petco2" 
                                   value="1"
                                   <?php echo $petco2 ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="petco2" 
                                   value="0"
                                   <?php echo !$petco2 ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_petco2" 
                              class="observaciones" 
                              placeholder="Observaciones sobre PETCO2..."><?php echo htmlspecialchars($obs_petco2); ?></textarea>
                </div>

                <!-- VD / VT -->
                <div class="form-group">
                    <h3>📊 VD / VT</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="vdvt" 
                                   value="1"
                                   <?php echo $vdvt ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="vdvt" 
                                   value="0"
                                   <?php echo !$vdvt ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_vdvt" 
                              class="observaciones" 
                              placeholder="Observaciones sobre VD / VT..."><?php echo htmlspecialchars($obs_vdvt); ?></textarea>
                </div>

                <!-- KO2 -->
                <div class="form-group">
                    <h3>💨 KO2</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="ko2" 
                                   value="1"
                                   <?php echo $ko2 ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="ko2" 
                                   value="0"
                                   <?php echo !$ko2 ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_ko2" 
                              class="observaciones" 
                              placeholder="Observaciones sobre KO2..."><?php echo htmlspecialchars($obs_ko2); ?></textarea>
                </div>

                <!-- HORA DE REGISTRO -->
                <div class="form-group">
                    <div class="hora-grupo">
                        <label for="hora_registro">⏰ Hora de Registro:</label>
                        <input type="time" 
                               name="hora_registro" 
                               id="hora_registro" 
                               value="<?php echo htmlspecialchars($hora_registro); ?>">
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $modo_edicion ? '💾 GUARDAR CAMBIOS' : '💾 GUARDAR'; ?>
                    </button>
                    <a href="<?php echo $GLOBALS['webroot']; ?>/interface/tableros/lista_internados.php" 
                       class="btn btn-secondary">
                        ❌ CANCELAR
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-completar hora actual solo en modo CREACIÓN
        document.addEventListener('DOMContentLoaded', function() {
            const modoEdicion = <?php echo $modo_edicion ? 'true' : 'false'; ?>;
            const horaInput = document.getElementById('hora_registro');
            
            if (!modoEdicion && horaInput.value === '') {
                // Solo en modo CREACIÓN y si el campo está vacío
                const ahora = new Date();
                const horas = String(ahora.getHours()).padStart(2, '0');
                const minutos = String(ahora.getMinutes()).padStart(2, '0');
                horaInput.value = horas + ':' + minutos;
            }
        });

        // Validación simple del formulario
        document.getElementById('formRegistroVM').addEventListener('submit', function(e) {
            console.log('Formulario enviado');
        });
    </script>
</body>
</html>