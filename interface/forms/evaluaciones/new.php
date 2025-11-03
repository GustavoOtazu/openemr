<?php
/**
 * Formulario de Evaluaciones - new.php (CON EDICIÓN)
 * Ruta: interface/forms/evaluaciones/new.php
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
$titulo = $modo_edicion ? "EDITAR EVALUACIONES" : "NUEVAS EVALUACIONES";

// Variables para pre-llenar el formulario
$conciencia = '';
$obs_conciencia = '';
$tono = '';
$obs_tono = '';
$pupilas = '';
$obs_pupilas = '';
$mucosas = '';
$obs_mucosas = '';
$glasgow_ojos = '';
$obs_glasgow_ojos = '';
$glasgow_motora = '';
$obs_glasgow_motora = '';
$glasgow_verbal = '';
$obs_glasgow_verbal = '';
$glasgow_total = 0;
$hora_evaluacion = '';

// Si es modo EDICIÓN, cargar datos existentes
if ($modo_edicion) {
    $sql = "SELECT * FROM form_evaluaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $row = sqlQuery($sql, array($id, $pid, $encounter));
    
    if ($row) {
        // Cargar valores existentes
        $conciencia = $row['conciencia'] ?? '';
        $obs_conciencia = $row['obs_conciencia'] ?? '';
        $tono = $row['tono'] ?? '';
        $obs_tono = $row['obs_tono'] ?? '';
        $pupilas = $row['pupilas'] ?? '';
        $obs_pupilas = $row['obs_pupilas'] ?? '';
        $mucosas = $row['mucosas'] ?? '';
        $obs_mucosas = $row['obs_mucosas'] ?? '';
        $glasgow_ojos = $row['glasgow_ojos'] ?? '';
        $obs_glasgow_ojos = $row['obs_glasgow_ojos'] ?? '';
        $glasgow_motora = $row['glasgow_motora'] ?? '';
        $obs_glasgow_motora = $row['obs_glasgow_motora'] ?? '';
        $glasgow_verbal = $row['glasgow_verbal'] ?? '';
        $obs_glasgow_verbal = $row['obs_glasgow_verbal'] ?? '';
        $glasgow_total = (int)($row['glasgow_total'] ?? 0);
        $hora_evaluacion = $row['hora_evaluacion'] ?? '';
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

        .form-group.glasgow-section {
            border-left: 4px solid #e74c3c;
            background: #fdf2f2;
        }

        .form-group h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        .radio-container label:hover {
            background-color: #f8f9fa;
        }

        .radio-container input[type="radio"] {
            width: 18px;
            height: 18px;
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

        .glasgow-info {
            background: #e8f4f8;
            border: 1px solid #bee5eb;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
        }

        .glasgow-info h5 {
            color: #0c5460;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .glasgow-info p {
            color: #0c5460;
            margin: 5px 0;
            font-size: 14px;
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
            <form method="POST" action="save.php" id="formEvaluaciones">
                <!-- Campos ocultos -->
                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid); ?>">
                <input type="hidden" name="encounter" value="<?php echo htmlspecialchars($encounter); ?>">
                <?php if ($modo_edicion): ?>
                <!-- Campo ID para indicar que es una edición -->
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <?php endif; ?>

                <!-- CONCIENCIA -->
                <div class="form-group">
                    <h3>🧠 Conciencia</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="conciencia" 
                                   value="VIGIL"
                                   <?php echo $conciencia == 'VIGIL' ? 'checked' : ''; ?>>
                            VIGIL
                        </label>
                        <label>
                            <input type="radio" 
                                   name="conciencia" 
                                   value="SOMNOLIENTO"
                                   <?php echo $conciencia == 'SOMNOLIENTO' ? 'checked' : ''; ?>>
                            SOMNOLIENTO
                        </label>
                        <label>
                            <input type="radio" 
                                   name="conciencia" 
                                   value="ESTUPOROSO"
                                   <?php echo $conciencia == 'ESTUPOROSO' ? 'checked' : ''; ?>>
                            ESTUPOROSO
                        </label>
                        <label>
                            <input type="radio" 
                                   name="conciencia" 
                                   value="COMATOSO"
                                   <?php echo $conciencia == 'COMATOSO' ? 'checked' : ''; ?>>
                            COMATOSO
                        </label>
                    </div>
                    <textarea name="obs_conciencia" 
                              class="observaciones" 
                              placeholder="Observaciones sobre conciencia..."><?php echo htmlspecialchars($obs_conciencia); ?></textarea>
                </div>

                <!-- TONO -->
                <div class="form-group">
                    <h3>💪 Tono</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="tono" 
                                   value="NORMAL"
                                   <?php echo $tono == 'NORMAL' ? 'checked' : ''; ?>>
                            NORMAL
                        </label>
                        <label>
                            <input type="radio" 
                                   name="tono" 
                                   value="FLACIDO"
                                   <?php echo $tono == 'FLACIDO' ? 'checked' : ''; ?>>
                            FLACIDO
                        </label>
                        <label>
                            <input type="radio" 
                                   name="tono" 
                                   value="ESPASTICO"
                                   <?php echo $tono == 'ESPASTICO' ? 'checked' : ''; ?>>
                            ESPASTICO
                        </label>
                    </div>
                    <textarea name="obs_tono" 
                              class="observaciones" 
                              placeholder="Observaciones sobre tono..."><?php echo htmlspecialchars($obs_tono); ?></textarea>
                </div>

                <!-- PUPILAS -->
                <div class="form-group">
                    <h3>👁️ Pupilas</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="pupilas" 
                                   value="NORMAL"
                                   <?php echo $pupilas == 'NORMAL' ? 'checked' : ''; ?>>
                            NORMAL
                        </label>
                        <label>
                            <input type="radio" 
                                   name="pupilas" 
                                   value="MIDRIASIS"
                                   <?php echo $pupilas == 'MIDRIASIS' ? 'checked' : ''; ?>>
                            MIDRIASIS
                        </label>
                        <label>
                            <input type="radio" 
                                   name="pupilas" 
                                   value="MIOSIS"
                                   <?php echo $pupilas == 'MIOSIS' ? 'checked' : ''; ?>>
                            MIOSIS
                        </label>
                    </div>
                    <textarea name="obs_pupilas" 
                              class="observaciones" 
                              placeholder="Observaciones sobre pupilas..."><?php echo htmlspecialchars($obs_pupilas); ?></textarea>
                </div>

                <!-- MUCOSAS -->
                <div class="form-group">
                    <h3>👄 Mucosas</h3>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="mucosas" 
                                   value="SECA"
                                   <?php echo $mucosas == 'SECA' ? 'checked' : ''; ?>>
                            SECA
                        </label>
                        <label>
                            <input type="radio" 
                                   name="mucosas" 
                                   value="HUMEDA"
                                   <?php echo $mucosas == 'HUMEDA' ? 'checked' : ''; ?>>
                            HUMEDA
                        </label>
                        <label>
                            <input type="radio" 
                                   name="mucosas" 
                                   value="PALIDA"
                                   <?php echo $mucosas == 'PALIDA' ? 'checked' : ''; ?>>
                            PALIDA
                        </label>
                        <label>
                            <input type="radio" 
                                   name="mucosas" 
                                   value="ICTERICA"
                                   <?php echo $mucosas == 'ICTERICA' ? 'checked' : ''; ?>>
                            ICTERICA
                        </label>
                        <label>
                            <input type="radio" 
                                   name="mucosas" 
                                   value="CIANOSIS"
                                   <?php echo $mucosas == 'CIANOSIS' ? 'checked' : ''; ?>>
                            CIANOSIS
                        </label>
                    </div>
                    <textarea name="obs_mucosas" 
                              class="observaciones" 
                              placeholder="Observaciones sobre mucosas..."><?php echo htmlspecialchars($obs_mucosas); ?></textarea>
                </div>

                <!-- ESCALA DE GLASGOW -->
                <div class="form-group glasgow-section">
                    <h3>🧠 Escala de Glasgow</h3>
                    
                    <h4>👁️ Ojos Abiertos</h4>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="glasgow_ojos" 
                                   value="ESPONTANEAMENTE"
                                   <?php echo $glasgow_ojos == 'ESPONTANEAMENTE' ? 'checked' : ''; ?>>
                            ESPONTANEAMENTE (4)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_ojos" 
                                   value="A ESTIMULOS AUDITIVOS"
                                   <?php echo $glasgow_ojos == 'A ESTIMULOS AUDITIVOS' ? 'checked' : ''; ?>>
                            A ESTIMULOS AUDITIVOS (3)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_ojos" 
                                   value="AL DOLOR"
                                   <?php echo $glasgow_ojos == 'AL DOLOR' ? 'checked' : ''; ?>>
                            AL DOLOR (2)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_ojos" 
                                   value="SIN RESPUESTA"
                                   <?php echo $glasgow_ojos == 'SIN RESPUESTA' ? 'checked' : ''; ?>>
                            SIN RESPUESTA (1)
                        </label>
                    </div>
                    <textarea name="obs_glasgow_ojos" 
                              class="observaciones" 
                              placeholder="Observaciones sobre respuesta ocular..."><?php echo htmlspecialchars($obs_glasgow_ojos); ?></textarea>

                    <h4>🤝 Respuesta Motora</h4>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="glasgow_motora" 
                                   value="OBEDECE ORDENES"
                                   <?php echo $glasgow_motora == 'OBEDECE ORDENES' ? 'checked' : ''; ?>>
                            OBEDECE ORDENES (6)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_motora" 
                                   value="LOCALIZA DOLOR"
                                   <?php echo $glasgow_motora == 'LOCALIZA DOLOR' ? 'checked' : ''; ?>>
                            LOCALIZA DOLOR (5)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_motora" 
                                   value="FLEXION DE DEFENSA"
                                   <?php echo $glasgow_motora == 'FLEXION DE DEFENSA' ? 'checked' : ''; ?>>
                            FLEXION DE DEFENSA (4)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_motora" 
                                   value="FLEXION ANORMAL"
                                   <?php echo $glasgow_motora == 'FLEXION ANORMAL' ? 'checked' : ''; ?>>
                            FLEXION ANORMAL (3)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_motora" 
                                   value="EXTENSION ANORMAL"
                                   <?php echo $glasgow_motora == 'EXTENSION ANORMAL' ? 'checked' : ''; ?>>
                            EXTENSION ANORMAL (2)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_motora" 
                                   value="NINGUNA"
                                   <?php echo $glasgow_motora == 'NINGUNA' ? 'checked' : ''; ?>>
                            NINGUNA (1)
                        </label>
                    </div>
                    <textarea name="obs_glasgow_motora" 
                              class="observaciones" 
                              placeholder="Observaciones sobre respuesta motora..."><?php echo htmlspecialchars($obs_glasgow_motora); ?></textarea>

                    <h4>🗣️ Respuesta Verbal</h4>
                    <div class="radio-container">
                        <label>
                            <input type="radio" 
                                   name="glasgow_verbal" 
                                   value="ORIENTADO Y CONVERSA"
                                   <?php echo $glasgow_verbal == 'ORIENTADO Y CONVERSA' ? 'checked' : ''; ?>>
                            ORIENTADO Y CONVERSA (5)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_verbal" 
                                   value="DESORIENTADO Y CONVERSA"
                                   <?php echo $glasgow_verbal == 'DESORIENTADO Y CONVERSA' ? 'checked' : ''; ?>>
                            DESORIENTADO Y CONVERSA (4)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_verbal" 
                                   value="LENGUAJE INADECUADO"
                                   <?php echo $glasgow_verbal == 'LENGUAJE INADECUADO' ? 'checked' : ''; ?>>
                            LENGUAJE INADECUADO (3)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_verbal" 
                                   value="SONIDOS INCOMPRENSIBLES"
                                   <?php echo $glasgow_verbal == 'SONIDOS INCOMPRENSIBLES' ? 'checked' : ''; ?>>
                            SONIDOS INCOMPRENSIBLES (2)
                        </label>
                        <label>
                            <input type="radio" 
                                   name="glasgow_verbal" 
                                   value="NINGUNA"
                                   <?php echo $glasgow_verbal == 'NINGUNA' ? 'checked' : ''; ?>>
                            NINGUNA (1)
                        </label>
                    </div>
                    <textarea name="obs_glasgow_verbal" 
                              class="observaciones" 
                              placeholder="Observaciones sobre respuesta verbal..."><?php echo htmlspecialchars($obs_glasgow_verbal); ?></textarea>

                    <!-- INFORMACIÓN DE GLASGOW -->
                    <div class="glasgow-info">
                        <h5>🔢 Puntaje Total de Glasgow: <span id="glasgowTotal"><?php echo $glasgow_total ?: '0'; ?></span>/15</h5>
                        <p><strong>13-15:</strong> Lesión leve</p>
                        <p><strong>9-12:</strong> Lesión moderada</p>
                        <p><strong>3-8:</strong> Lesión severa</p>
                    </div>
                </div>

                <!-- HORA DE EVALUACIÓN -->
                <div class="form-group">
                    <div class="hora-grupo">
                        <label for="hora_evaluacion">⏰ Hora de Evaluación:</label>
                        <input type="time" 
                               name="hora_evaluacion" 
                               id="hora_evaluacion" 
                               value="<?php echo htmlspecialchars($hora_evaluacion); ?>">
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
            const horaInput = document.getElementById('hora_evaluacion');
            
            if (!modoEdicion && horaInput.value === '') {
                // Solo en modo CREACIÓN y si el campo está vacío
                const ahora = new Date();
                const horas = String(ahora.getHours()).padStart(2, '0');
                const minutos = String(ahora.getMinutes()).padStart(2, '0');
                horaInput.value = horas + ':' + minutos;
            }

            // Calcular Glasgow en tiempo real
            calcularGlasgow();
            
            // Escuchar cambios en los radio buttons de Glasgow
            document.querySelectorAll('input[name^="glasgow_"]').forEach(function(radio) {
                radio.addEventListener('change', calcularGlasgow);
            });
        });

        function calcularGlasgow() {
            const puntajes = {
                glasgow_ojos: {
                    'ESPONTANEAMENTE': 4,
                    'A ESTIMULOS AUDITIVOS': 3,
                    'AL DOLOR': 2,
                    'SIN RESPUESTA': 1
                },
                glasgow_motora: {
                    'OBEDECE ORDENES': 6,
                    'LOCALIZA DOLOR': 5,
                    'FLEXION DE DEFENSA': 4,
                    'FLEXION ANORMAL': 3,
                    'EXTENSION ANORMAL': 2,
                    'NINGUNA': 1
                },
                glasgow_verbal: {
                    'ORIENTADO Y CONVERSA': 5,
                    'DESORIENTADO Y CONVERSA': 4,
                    'LENGUAJE INADECUADO': 3,
                    'SONIDOS INCOMPRENSIBLES': 2,
                    'NINGUNA': 1
                }
            };

            let total = 0;

            Object.keys(puntajes).forEach(function(categoria) {
                const radio = document.querySelector('input[name="' + categoria + '"]:checked');
                if (radio) {
                    total += puntajes[categoria][radio.value] || 0;
                }
            });

            document.getElementById('glasgowTotal').textContent = total;
        }

        // Validación simple del formulario
        document.getElementById('formEvaluaciones').addEventListener('submit', function(e) {
            console.log('Formulario enviado');
        });
    </script>
</body>
</html>