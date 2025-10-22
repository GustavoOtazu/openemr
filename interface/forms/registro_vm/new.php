<?php
/**
 * Formulario de Registro VM - new.php
 * Ruta: interface/forms/registro_vm/new.php
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");

// Obtener parámetros
$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;

if (!$pid || !$encounter) {
    echo "<div class='alert alert-danger'>Error: No se pudo obtener PID o Encounter</div>";
    exit;
}

// Definir items con Sí/No
$items_sino = array(
    'presion' => 'PRESION',
    'volumen' => 'VOLUMEN',
    'simv' => 'SIMV',
    'psv' => 'PSV',
    'otros' => 'OTROS',
    'frecuencia_respiratoria' => 'FRECUENCIA RESPIRATORIA',
    'p_inspiratorio' => 'P.INSPIRATORIO/T.INSPIRATORIO',
    'p_media' => 'P.MEDIA/PEEP',
    'p_max' => 'P.MAX/P.PLATEAU',
    'chst' => 'CHST/CDIN',
    'disparo' => 'DISPARO POR F/P',
    'fvt' => 'F/VT',
    'vol_tidal' => 'VOL.TIDAL/FLUJO',
    'vm_programado' => 'VM PROGRAMADO / MEDIDO',
    'petco2' => 'PETCO2',
    'vdvt' => 'VD/VT',
    'ko2' => 'Ko2'
);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro VM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px; }
        .form-container { max-width: 900px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-title { text-align: center; color: #007bff; font-size: 28px; margin-bottom: 30px; font-weight: bold; }
        
        .modo-section {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #007bff;
        }
        .modo-section h3 {
            color: #007bff;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .modo-options {
            display: flex;
            gap: 30px;
            margin-top: 10px;
        }
        .modo-options label {
            display: flex;
            align-items: center;
            font-size: 16px;
            cursor: pointer;
        }
        .modo-options input[type="radio"] {
            margin-right: 8px;
            width: 18px;
            height: 18px;
        }
        
        .item-card {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .item-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .item-options {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .radio-group {
            display: flex;
            gap: 20px;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .radio-group input[type="radio"] {
            margin-right: 5px;
            width: 16px;
            height: 16px;
        }
        .obs-input {
            flex: 1;
            min-width: 250px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .hora-section {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
        }
        .hora-section label {
            font-weight: bold;
            color: #856404;
            display: block;
            margin-bottom: 8px;
        }
        .hora-section input[type="time"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 200px;
        }
        
        .form-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
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
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">FORMULARIO DE REGISTRO VM</h1>
        
        <form method="POST" action="<?php echo $GLOBALS['webroot']; ?>/interface/forms/registro_vm/save.php">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>">
            <input type="hidden" name="encounter" value="<?php echo $encounter; ?>">
            
            <!-- MODO DE VENTILACION -->
            <div class="modo-section">
                <h3>MODO DE VENTILACION</h3>
                <div class="modo-options">
                    <label>
                        <input type="radio" name="modo_ventilacion" value="ESPONTANEA" required>
                        ESPONTANEA
                    </label>
                    <label>
                        <input type="radio" name="modo_ventilacion" value="VENTILACION MECANICA">
                        VENTILACION MECANICA
                    </label>
                </div>
            </div>
            
           
            
            <!-- ITEMS CON SÍ/NO Y OBSERVACIONES -->
            <?php foreach ($items_sino as $campo => $titulo): ?>
                <div class="item-card">
                    <div class="item-title"><?php echo $titulo; ?></div>
                    <div class="item-options">
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="<?php echo $campo; ?>" value="1" id="<?php echo $campo; ?>_si">
                                Sí
                            </label>
                            <label>
                                <input type="radio" name="<?php echo $campo; ?>" value="0" id="<?php echo $campo; ?>_no" checked>
                                No
                            </label>
                        </div>
                        <input type="text" 
                               name="obs_<?php echo $campo; ?>" 
                               placeholder="Ingrese observación" 
                               class="obs-input">
                    </div>
                </div>
            <?php endforeach; ?>

             <!-- HORA DE REGISTRO -->
            <div class="hora-section">
                <label for="hora_registro">Hora de Registro:</label>
                <input type="time" id="hora_registro" name="hora_registro" class="form-control w-auto" required value="<?php echo date('H:i'); ?>">
            </div>
            
            <!-- BOTONES -->
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">💾 Guardar Registro</button>
                <button type="button" class="btn btn-secondary"
                    onclick="if(confirm('¿Seguro que deseas cancelar? Se perderán los datos no guardados.')) {
                        top.RTop.location = '<?php echo $GLOBALS['webroot']; ?>/interface/tableros/lista_internados.php';
                    }">
                    ❌ Cancelar
                </button>
            </div>
        </form>
    </div>
</body>
</html>