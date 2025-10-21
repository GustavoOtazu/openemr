<?php
/**
 * Formulario de Evaluaciones - new.php
 * Ruta: interface/forms/evaluaciones/new.php
 */

require_once("../../globals.php");

// Obtener parámetros
$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;

if (!$pid || !$encounter) {
    echo "<div style='color: red; padding: 20px;'>Error: No se pudo obtener PID o Encounter</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Evaluaciones</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #007bff;
        }
        h3 {
            color: #007bff;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 20px;
        }
        h4 {
            color: #495057;
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }
        .evaluacion-item {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        .item-label {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
            display: block;
        }
        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }
        .radio-option {
            display: flex;
            align-items: center;
            min-width: 180px;
        }
        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }
        .radio-option label {
            cursor: pointer;
            font-size: 14px;
            color: #495057;
        }
        .obs-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 10px;
        }
        .obs-input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .hora-grupo {
            margin: 25px 0;
            padding: 20px;
            background-color: #e7f3ff;
            border-radius: 6px;
        }
        .hora-grupo label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        .hora-grupo input[type="time"] {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        .glasgow-section {
            background-color: #fff3cd;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-top: 30px;
        }
        .botones {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-guardar {
            background-color: #28a745;
            color: white;
        }
        .btn-guardar:hover {
            background-color: #218838;
        }
        .btn-cancelar {
            background-color: #6c757d;
            color: white;
        }
        .btn-cancelar:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>FORMULARIO DE EVALUACIONES</h2>
        
        <form action="save.php" method="POST">
            <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid); ?>">
            <input type="hidden" name="encounter" value="<?php echo htmlspecialchars($encounter); ?>">
            
            <!-- SECCIÓN 1: EVALUACIONES BÁSICAS -->
            <div class="evaluacion-item">
                <span class="item-label"> CONCIENCIA</span>
                <div class="radio-group">
                    <div class="radio-option">
                        <input type="radio" name="conciencia" value="VIGIL" id="conciencia_1">
                        <label for="conciencia_1">VIGIL</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="conciencia" value="SOMNOLIENTO" id="conciencia_2">
                        <label for="conciencia_2">SOMNOLIENTO</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="conciencia" value="ESTUPOROSO" id="conciencia_3">
                        <label for="conciencia_3">ESTUPOROSO</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="conciencia" value="COMATOSO" id="conciencia_4">
                        <label for="conciencia_4">COMATOSO</label>
                    </div>
                </div>
                <input type="text" name="obs_conciencia" placeholder="Observaciones" class="obs-input">
            </div>

            <div class="evaluacion-item">
                <span class="item-label"> TONO</span>
                <div class="radio-group">
                    <div class="radio-option">
                        <input type="radio" name="tono" value="NORMAL" id="tono_1">
                        <label for="tono_1">NORMAL</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="tono" value="FLACIDO" id="tono_2">
                        <label for="tono_2">FLACIDO</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="tono" value="ESPASTICO" id="tono_3">
                        <label for="tono_3">ESPASTICO</label>
                    </div>
                </div>
                <input type="text" name="obs_tono" placeholder="Observaciones" class="obs-input">
            </div>

            <div class="evaluacion-item">
                <span class="item-label"> PUPILAS</span>
                <div class="radio-group">
                    <div class="radio-option">
                        <input type="radio" name="pupilas" value="NORMAL" id="pupilas_1">
                        <label for="pupilas_1">NORMAL</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="pupilas" value="MIDRIASIS" id="pupilas_2">
                        <label for="pupilas_2">MIDRIASIS</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="pupilas" value="MIOSIS" id="pupilas_3">
                        <label for="pupilas_3">MIOSIS</label>
                    </div>
                </div>
                <input type="text" name="obs_pupilas" placeholder="Observaciones" class="obs-input">
            </div>

            <div class="evaluacion-item">
                <span class="item-label"> MUCOSAS</span>
                <div class="radio-group">
                    <div class="radio-option">
                        <input type="radio" name="mucosas" value="SECA" id="mucosas_1">
                        <label for="mucosas_1">SECA</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="mucosas" value="HUMEDA" id="mucosas_2">
                        <label for="mucosas_2">HUMEDA</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="mucosas" value="PALIDA" id="mucosas_3">
                        <label for="mucosas_3">PALIDA</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="mucosas" value="ICTERICA" id="mucosas_4">
                        <label for="mucosas_4">ICTERICA</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="mucosas" value="CIANOSIS" id="mucosas_5">
                        <label for="mucosas_5">CIANOSIS</label>
                    </div>
                </div>
                <input type="text" name="obs_mucosas" placeholder="Observaciones" class="obs-input">
            </div>

            <!-- SECCIÓN 2: ESCALA DE GLASGOW -->
            <div class="glasgow-section">
                <h3>ESCALA DE GLASGOW</h3>
                
                <h4>OJOS ABIERTOS</h4>
                <div class="evaluacion-item">
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="glasgow_ojos" value="ESPONTANEAMENTE" id="glasgow_ojos_1">
                            <label for="glasgow_ojos_1">ESPONTANEAMENTE</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_ojos" value="A ESTIMULOS AUDITIVOS" id="glasgow_ojos_2">
                            <label for="glasgow_ojos_2">A ESTIMULOS AUDITIVOS</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_ojos" value="AL DOLOR" id="glasgow_ojos_3">
                            <label for="glasgow_ojos_3">AL DOLOR</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_ojos" value="SIN RESPUESTA" id="glasgow_ojos_4">
                            <label for="glasgow_ojos_4">SIN RESPUESTA</label>
                        </div>
                    </div>
                    <input type="text" name="obs_glasgow_ojos" placeholder="Observaciones" class="obs-input">
                </div>

                <h4>RESPUESTA MOTORA</h4>
                <div class="evaluacion-item">
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="glasgow_motora" value="OBEDECE ORDENES" id="glasgow_motora_1">
                            <label for="glasgow_motora_1">OBEDECE ORDENES</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_motora" value="LOCALIZA DOLOR" id="glasgow_motora_2">
                            <label for="glasgow_motora_2">LOCALIZA DOLOR</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_motora" value="FLEXION DE DEFENSA" id="glasgow_motora_3">
                            <label for="glasgow_motora_3">FLEXION DE DEFENSA</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_motora" value="FLEXION ANORMAL" id="glasgow_motora_4">
                            <label for="glasgow_motora_4">FLEXION ANORMAL</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_motora" value="EXTENSION ANORMAL" id="glasgow_motora_5">
                            <label for="glasgow_motora_5">EXTENSION ANORMAL</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_motora" value="NINGUNA" id="glasgow_motora_6">
                            <label for="glasgow_motora_6">NINGUNA</label>
                        </div>
                    </div>
                    <input type="text" name="obs_glasgow_motora" placeholder="Observaciones" class="obs-input">
                </div>

                <h4>RESPUESTA VERBAL</h4>
                <div class="evaluacion-item">
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="glasgow_verbal" value="ORIENTADO Y CONVERSA" id="glasgow_verbal_1">
                            <label for="glasgow_verbal_1">ORIENTADO Y CONVERSA</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_verbal" value="DESORIENTADO Y CONVERSA" id="glasgow_verbal_2">
                            <label for="glasgow_verbal_2">DESORIENTADO Y CONVERSA</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_verbal" value="LENGUAJE INADECUADO" id="glasgow_verbal_3">
                            <label for="glasgow_verbal_3">LENGUAJE INADECUADO</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_verbal" value="SONIDOS INCOMPRENSIBLES" id="glasgow_verbal_4">
                            <label for="glasgow_verbal_4">SONIDOS INCOMPRENSIBLES</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="glasgow_verbal" value="NINGUNA" id="glasgow_verbal_5">
                            <label for="glasgow_verbal_5">NINGUNA</label>
                        </div>
                    </div>
                    <input type="text" name="obs_glasgow_verbal" placeholder="Observaciones" class="obs-input">
                </div>
            </div>

            <!-- HORA DE EVALUACIÓN -->
            <div class="hora-grupo">
                <label for="hora_evaluacion">Hora de Evaluación:</label>

                <input type="time" name="hora_evaluacion" id="hora_evaluacion" class="form-control w-auto" required value="<?php echo date('H:i'); ?>">
            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>

                <button type="button" class="btn btn-cancelar" 
                    onclick="if(confirm('¿Seguro que deseas cancelar? Se perderán los datos no guardados.')) {
                        top.RTop.location = '<?php echo $GLOBALS['webroot']; ?>/interface/tableros/lista_internados.php';
                    }">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</body>
</html>