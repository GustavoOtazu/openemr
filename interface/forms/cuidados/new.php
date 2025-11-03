<?php
/**
 * Formulario de Cuidados - new.php (CON EDICIÓN)
 * Ruta: interface/forms/cuidados/new.php
 * Soporta tanto CREAR como EDITAR registros
 * MODIFICADO: Auto-completa hora actual en modo creación
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");

// Obtener parámetros
$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;
$id = $_GET['id'] ?? null; // ← NUEVO: Detectar si es modo edición

// Determinar si es modo CREAR o EDITAR
$modo_edicion = !empty($id);
$titulo = $modo_edicion ? "EDITAR CUIDADOS" : "NUEVOS CUIDADOS";

// Variables para pre-llenar el formulario
$posicion_paciente = '';
$obs_posicion_paciente = '';
$enjuague_bucal = 0;
$obs_enjuague_bucal = '';
$higiene_manos = 0;
$obs_higiene_manos = '';
$aspirado_secreciones = 0;
$obs_aspirado_secreciones = '';
$suspension_sedacion = 0;
$obs_suspension_sedacion = '';
$medicion_cuff = 0;
$obs_medicion_cuff = '';
$hora_cuidado = '';

// Si es modo EDICIÓN, cargar datos existentes
if ($modo_edicion) {
    $sql = "SELECT * FROM form_cuidados WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $row = sqlQuery($sql, array($id, $pid, $encounter));
    
    if ($row) {
        // Cargar valores existentes
        $posicion_paciente = $row['posicion_paciente'] ?? '';
        $obs_posicion_paciente = $row['obs_posicion_paciente'] ?? '';
        $enjuague_bucal = (int)$row['enjuague_bucal'];
        $obs_enjuague_bucal = $row['obs_enjuague_bucal'] ?? '';
        $higiene_manos = (int)$row['higiene_manos'];
        $obs_higiene_manos = $row['obs_higiene_manos'] ?? '';
        $aspirado_secreciones = (int)$row['aspirado_secreciones'];
        $obs_aspirado_secreciones = $row['obs_aspirado_secreciones'] ?? '';
        $suspension_sedacion = (int)$row['suspension_sedacion'];
        $obs_suspension_sedacion = $row['obs_suspension_sedacion'] ?? '';
        $medicion_cuff = (int)$row['medicion_cuff'];
        $obs_medicion_cuff = $row['obs_medicion_cuff'] ?? '';
        $hora_cuidado = $row['hora_cuidado'] ?? '';
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

        .form-group-posicion {
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
            <form method="POST" action="save.php" id="formCuidados">
                <!-- Campos ocultos -->
                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid); ?>">
                <input type="hidden" name="encounter" value="<?php echo htmlspecialchars($encounter); ?>">
                <?php if ($modo_edicion): ?>
                <!-- Campo ID para indicar que es una edición -->
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php endif; ?>

                <!-- POSICIÓN DEL PACIENTE (Especial) -->
                <div class="form-group-posicion">
                    <h3>🛏️ Posición del Paciente</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="posicion_paciente" 
                                   value="DLI"
                                   <?php echo ($posicion_paciente === 'DLI') ? 'checked' : ''; ?>>
                            DLI
                        </label>
                        <label>
                            <input type="radio" 
                                   name="posicion_paciente" 
                                   value="DLD"
                                   <?php echo ($posicion_paciente === 'DLD') ? 'checked' : ''; ?>>
                            DLD
                        </label>
                        <label>
                            <input type="radio" 
                                   name="posicion_paciente" 
                                   value="DS"
                                   <?php echo ($posicion_paciente === 'DS') ? 'checked' : ''; ?>>
                            DS
                        </label>
                        <label>
                            <input type="radio" 
                                   name="posicion_paciente" 
                                   value="DV"
                                   <?php echo ($posicion_paciente === 'DV') ? 'checked' : ''; ?>>
                            DV
                        </label>
                        <label>
                            <input type="radio" 
                                   name="posicion_paciente" 
                                   value="CABECERA 30°"
                                   <?php echo ($posicion_paciente === 'CABECERA 30°') ? 'checked' : ''; ?>>
                            CABECERA 30°
                        </label>
                    </div>
                    <textarea name="obs_posicion_paciente" 
                              class="observaciones" 
                              placeholder="Observaciones sobre posición del paciente..."><?php echo htmlspecialchars($obs_posicion_paciente); ?></textarea>
                </div>

                <!-- ENJUAGUE BUCAL -->
                <div class="form-group">
                    <h3>🦷 Enjuague Bucal</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="enjuague_bucal" 
                                   value="1"
                                   <?php echo $enjuague_bucal ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="enjuague_bucal" 
                                   value="0"
                                   <?php echo !$enjuague_bucal ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_enjuague_bucal" 
                              class="observaciones" 
                              placeholder="Observaciones sobre enjuague bucal..."><?php echo htmlspecialchars($obs_enjuague_bucal); ?></textarea>
                </div>

                <!-- HIGIENE DE MANOS -->
                <div class="form-group">
                    <h3>🧼 Higiene de Manos Pre y Post Aspirado</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="higiene_manos" 
                                   value="1"
                                   <?php echo $higiene_manos ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="higiene_manos" 
                                   value="0"
                                   <?php echo !$higiene_manos ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_higiene_manos" 
                              class="observaciones" 
                              placeholder="Observaciones sobre higiene de manos..."><?php echo htmlspecialchars($obs_higiene_manos); ?></textarea>
                </div>

                <!-- ASPIRADO DE SECRECIONES -->
                <div class="form-group">
                    <h3>🫁 Aspirado de Secreciones con Guantes y Ayudante</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="aspirado_secreciones" 
                                   value="1"
                                   <?php echo $aspirado_secreciones ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="aspirado_secreciones" 
                                   value="0"
                                   <?php echo !$aspirado_secreciones ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_aspirado_secreciones" 
                              class="observaciones" 
                              placeholder="Observaciones sobre aspirado de secreciones..."><?php echo htmlspecialchars($obs_aspirado_secreciones); ?></textarea>
                </div>

                <!-- SUSPENSIÓN SEDACIÓN -->
                <div class="form-group">
                    <h3>💊 Suspensión Diaria de Sedación y Evaluación de Extubación</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="suspension_sedacion" 
                                   value="1"
                                   <?php echo $suspension_sedacion ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="suspension_sedacion" 
                                   value="0"
                                   <?php echo !$suspension_sedacion ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_suspension_sedacion" 
                              class="observaciones" 
                              placeholder="Observaciones sobre suspensión de sedación..."><?php echo htmlspecialchars($obs_suspension_sedacion); ?></textarea>
                </div>

                <!-- MEDICIÓN CUFF -->
                <div class="form-group">
                    <h3>📏 Medición de Presión de Cuff</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="medicion_cuff" 
                                   value="1"
                                   <?php echo $medicion_cuff ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="medicion_cuff" 
                                   value="0"
                                   <?php echo !$medicion_cuff ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_medicion_cuff" 
                              class="observaciones" 
                              placeholder="Observaciones sobre medición de cuff..."><?php echo htmlspecialchars($obs_medicion_cuff); ?></textarea>
                </div>

                <!-- HORA DE REGISTRO -->
                <div class="form-group">
                    <div class="hora-grupo">
                        <label for="hora_cuidado">⏰ Hora de Registro:</label>
                        <input type="time" 
                               name="hora_cuidado" 
                               id="hora_cuidado" 
                               value="<?php echo htmlspecialchars($hora_cuidado); ?>">
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
            const horaInput = document.getElementById('hora_cuidado');
            
            if (!modoEdicion && horaInput.value === '') {
                // Solo en modo CREACIÓN y si el campo está vacío
                const ahora = new Date();
                const horas = String(ahora.getHours()).padStart(2, '0');
                const minutos = String(ahora.getMinutes()).padStart(2, '0');
                horaInput.value = horas + ':' + minutos;
            }
        });

        // Validación simple del formulario
        document.getElementById('formCuidados').addEventListener('submit', function(e) {
            console.log('Formulario enviado');
        });
    </script>
</body>
</html>