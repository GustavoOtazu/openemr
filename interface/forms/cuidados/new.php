<?php
/**
 * Formulario de Cuidados - new.php
 * Ruta: interface/forms/cuidados/new.php
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");

formHeader("Nuevo Registro de Cuidados");

$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;

date_default_timezone_set('America/Asuncion');
?>

<html>
<head>
    <title>Formulario de Cuidados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <style>
        body { background-color: #f8f9fa; }
        .form-container { max-width: 900px; margin: 30px auto; padding: 25px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .form-title { text-align: center; color: #2c3e50; margin-bottom: 25px; font-size: 28px; font-weight: bold; }
        .cuidado-card { padding: 15px; margin-bottom: 20px; border-radius: 10px; background-color: #fdfdfe; border: 1px solid #dee2e6; }
        .cuidado-card h3 { font-size: 18px; color: #34495e; margin-bottom: 12px; }
        .obs-input { flex: 1; }
        .time-group label { font-weight: bold; min-width: 150px; }
        .posicion-group { display: flex; gap: 15px; flex-wrap: wrap; }
        .posicion-item { display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body class="body_top">

<div class="form-container">
    <h2 class="form-title">FORMULARIO DE CUIDADOS</h2>
    
    <form method="post" action="<?php echo $rootdir;?>/forms/cuidados/save.php?mode=new" name="cuidados_form">
        <input type="hidden" name="pid" value="<?php echo attr($pid); ?>" />
        <input type="hidden" name="encounter" value="<?php echo attr($encounter); ?>" />

        <!-- POSICION DEL PACIENTE (Radio Buttons) -->
        <div class="cuidado-card">
            <h3>POSICION DEL PACIENTE</h3>
            <div class="posicion-group">
                <div class="posicion-item">
                    <input type="radio" name="posicion_paciente" value="DLI" id="dli">
                    <label for="dli">DLI</label>
                </div>
                <div class="posicion-item">
                    <input type="radio" name="posicion_paciente" value="DLD" id="dld">
                    <label for="dld">DLD</label>
                </div>
                <div class="posicion-item">
                    <input type="radio" name="posicion_paciente" value="DS" id="ds">
                    <label for="ds">DS</label>
                </div>
                <div class="posicion-item">
                    <input type="radio" name="posicion_paciente" value="DV" id="dv">
                    <label for="dv">DV</label>
                </div>
                <div class="posicion-item">
                    <input type="radio" name="posicion_paciente" value="CABECERA 30°" id="cabecera">
                    <label for="cabecera">CABECERA 30°</label>
                </div>
            </div>
            <div style="margin-top: 15px;">
                <input type="text" name="obs_posicion_paciente" placeholder="Observación" class="form-control">
            </div>
        </div>

        <!-- ENJUAGUE BUCAL -->
        <div class="cuidado-card d-flex flex-column">
            <h3>ENJUAGUE BUCAL</h3>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="enjuague_bucal" value="1" id="enjuague_si">
                    <label class="form-check-label" for="enjuague_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="enjuague_bucal" value="0" id="enjuague_no" checked>
                    <label class="form-check-label" for="enjuague_no">No</label>
                </div>
                <input type="text" name="obs_enjuague_bucal" placeholder="Observación" class="form-control obs-input">
            </div>
        </div>

        <!-- HIGIENE DE MANOS PRE Y POST ASPIRADO -->
        <div class="cuidado-card d-flex flex-column">
            <h3>HIGIENE DE MANOS PRE Y POST ASPIRADO</h3>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="higiene_manos" value="1" id="higiene_si">
                    <label class="form-check-label" for="higiene_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="higiene_manos" value="0" id="higiene_no" checked>
                    <label class="form-check-label" for="higiene_no">No</label>
                </div>
                <input type="text" name="obs_higiene_manos" placeholder="Observación" class="form-control obs-input">
            </div>
        </div>

        <!-- ASPIRADO DE SECRECIONES CON GUANTES Y AYUDANTE CON GUANTES -->
        <div class="cuidado-card d-flex flex-column">
            <h3>ASPIRADO DE SECRECIONES CON GUANTES Y AYUDANTE CON GUANTES</h3>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aspirado_secreciones" value="1" id="aspirado_si">
                    <label class="form-check-label" for="aspirado_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aspirado_secreciones" value="0" id="aspirado_no" checked>
                    <label class="form-check-label" for="aspirado_no">No</label>
                </div>
                <input type="text" name="obs_aspirado_secreciones" placeholder="Observación" class="form-control obs-input">
            </div>
        </div>

        <!-- SUSPENSION DIARIA DE SEDACION Y EVALUACION DE EXTUBACION -->
        <div class="cuidado-card d-flex flex-column">
            <h3>SUSPENSION DIARIA DE SEDACION Y EVALUACION DE EXTUBACION</h3>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="suspension_sedacion" value="1" id="suspension_si">
                    <label class="form-check-label" for="suspension_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="suspension_sedacion" value="0" id="suspension_no" checked>
                    <label class="form-check-label" for="suspension_no">No</label>
                </div>
                <input type="text" name="obs_suspension_sedacion" placeholder="Observación" class="form-control obs-input">
            </div>
        </div>

        <!-- MEDICION DE PRESION DE CUFF -->
        <div class="cuidado-card d-flex flex-column">
            <h3>MEDICION DE PRESION DE CUFF</h3>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medicion_cuff" value="1" id="medicion_si">
                    <label class="form-check-label" for="medicion_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="medicion_cuff" value="0" id="medicion_no" checked>
                    <label class="form-check-label" for="medicion_no">No</label>
                </div>
                <input type="text" name="obs_medicion_cuff" placeholder="Observación" class="form-control obs-input">
            </div>
        </div>

        <!-- HORA DE CUIDADO -->
        <div class="mb-3 d-flex align-items-center time-group">
            <label for="hora_cuidado">Hora de Cuidado:</label>
            <input type="time" id="hora_cuidado" name="hora_cuidado" class="form-control w-auto" required value="<?php echo date('H:i'); ?>">
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-secondary"
                onclick="if(confirm('¿Seguro que deseas cancelar? Se perderán los datos no guardados.')) {
                    top.RTop.location = '<?php echo $GLOBALS['webroot']; ?>/interface/tableros/lista_internados.php';
                }">
                Cancelar
            </button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>