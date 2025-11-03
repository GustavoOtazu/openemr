<?php
/**
 * Formulario de Aplicaciones - new.php (CON EDICIÓN)
 * Ruta: interface/forms/aplicaciones/new.php
 * Soporta tanto CREAR como EDITAR registros
 * MODIFICADO: Auto-completa hora actual en modo creación
 * MODIFICADO: Radio buttons Sí/No como en curaciones
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");

// Obtener parámetros
$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;
$id = $_GET['id'] ?? null; // ← NUEVO: Detectar si es modo edición

// Determinar si es modo CREAR o EDITAR
$modo_edicion = !empty($id);
$titulo = $modo_edicion ? "EDITAR APLICACIÓN" : "NUEVA APLICACIÓN";

// Variables para pre-llenar el formulario
$medicamentos = 0;
$obs_medicamentos = '';
$sueros = 0;
$obs_sueros = '';
$vacunas = 0;
$obs_vacunas = '';
$expansiones = 0;
$obs_expansiones = '';
$sangre = 0;
$obs_sangre = '';
$hora_registro = '';

// Si es modo EDICIÓN, cargar datos existentes
if ($modo_edicion) {
    $sql = "SELECT * FROM form_aplicaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $row = sqlQuery($sql, array($id, $pid, $encounter));
    
    if ($row) {
        // Cargar valores existentes
        $medicamentos = (int)$row['medicamentos'];
        $obs_medicamentos = $row['obs_medicamentos'] ?? '';
        $sueros = (int)$row['sueros'];
        $obs_sueros = $row['obs_sueros'] ?? '';
        $vacunas = (int)$row['vacunas'];
        $obs_vacunas = $row['obs_vacunas'] ?? '';
        $expansiones = (int)$row['expansiones'];
        $obs_expansiones = $row['obs_expansiones'] ?? '';
        $sangre = (int)$row['sangre'];
        $obs_sangre = $row['obs_sangre'] ?? '';
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
            <form method="POST" action="save.php" id="formAplicaciones">
                <!-- Campos ocultos -->
                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid); ?>">
                <input type="hidden" name="encounter" value="<?php echo htmlspecialchars($encounter); ?>">
                <?php if ($modo_edicion): ?>
                <!-- Campo ID para indicar que es una edición -->
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php endif; ?>

                <!-- MEDICAMENTOS -->
                <div class="form-group">
                    <h3>💊 Medicamentos</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="medicamentos" 
                                   value="1"
                                   <?php echo $medicamentos ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="medicamentos" 
                                   value="0"
                                   <?php echo !$medicamentos ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_medicamentos" 
                              class="observaciones" 
                              placeholder="Observaciones sobre medicamentos..."><?php echo htmlspecialchars($obs_medicamentos); ?></textarea>
                </div>

                <!-- SUEROS -->
                <div class="form-group">
                    <h3>💧 Sueros</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="sueros" 
                                   value="1"
                                   <?php echo $sueros ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="sueros" 
                                   value="0"
                                   <?php echo !$sueros ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_sueros" 
                              class="observaciones" 
                              placeholder="Observaciones sobre sueros..."><?php echo htmlspecialchars($obs_sueros); ?></textarea>
                </div>

                <!-- VACUNAS -->
                <div class="form-group">
                    <h3>💉 Vacunas</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="vacunas" 
                                   value="1"
                                   <?php echo $vacunas ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="vacunas" 
                                   value="0"
                                   <?php echo !$vacunas ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_vacunas" 
                              class="observaciones" 
                              placeholder="Observaciones sobre vacunas..."><?php echo htmlspecialchars($obs_vacunas); ?></textarea>
                </div>

                <!-- EXPANSIONES -->
                <div class="form-group">
                    <h3>🔬 Expansiones Plasmáticas</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="expansiones" 
                                   value="1"
                                   <?php echo $expansiones ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="expansiones" 
                                   value="0"
                                   <?php echo !$expansiones ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_expansiones" 
                              class="observaciones" 
                              placeholder="Observaciones sobre expansiones..."><?php echo htmlspecialchars($obs_expansiones); ?></textarea>
                </div>

                <!-- SANGRE -->
                <div class="form-group">
                    <h3>🩸 Sangre y Hemoderivados</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="sangre" 
                                   value="1"
                                   <?php echo $sangre ? 'checked' : ''; ?>>
                            Sí
                        </label>
                        <label>
                            <input type="radio" 
                                   name="sangre" 
                                   value="0"
                                   <?php echo !$sangre ? 'checked' : ''; ?>>
                            No
                        </label>
                    </div>
                    <textarea name="obs_sangre" 
                              class="observaciones" 
                              placeholder="Observaciones sobre sangre..."><?php echo htmlspecialchars($obs_sangre); ?></textarea>
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
        document.getElementById('formAplicaciones').addEventListener('submit', function(e) {
            console.log('Formulario enviado');
        });
    </script>
</body>
</html>