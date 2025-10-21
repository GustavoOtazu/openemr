<?php
/**
 * Formulario de Curaciones - new.php
 * Ruta: interface/forms/curaciones/new.php
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");

formHeader("Nuevo Registro de Curación");

// Obtener parámetros de la URL o sesión
$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;

// Imprimir en consola del navegador
echo "<script>
    console.log('PID obtenido:', " . json_encode($pid) . ");
    console.log('Encounter obtenido:', " . json_encode($encounter) . ");
</script>";

date_default_timezone_set('America/Asuncion'); // Ajusta según tu zona horaria
?>

<html>
<head>
    <title>Formulario de Curaciones</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <style>
        body { background-color: #f8f9fa; }
        .form-container { max-width: 900px; margin: 30px auto; padding: 25px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .form-title { text-align: center; color: #2c3e50; margin-bottom: 25px; font-size: 28px; font-weight: bold; }
        .curacion-card { padding: 15px; margin-bottom: 20px; border-radius: 10px; background-color: #fdfdfe; border: 1px solid #dee2e6; }
        .curacion-card h3 { font-size: 20px; color: #34495e; margin-bottom: 12px; }
        .obs-input { flex: 1; }
        .time-group label { font-weight: bold; min-width: 150px; }
        .checkbox-wrapper { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
    </style>
</head>
<body class="body_top">

<div class="form-container">
    <h2 class="form-title">FORMULARIO DE CURACIONES</h2>
    
    <form method="post" action="<?php echo $rootdir;?>/forms/curaciones/save.php?mode=new" name="curaciones_form">
        <input type="hidden" name="pid" value="<?php echo attr($pid); ?>" />
        <input type="hidden" name="encounter" value="<?php echo attr($encounter); ?>" />

        <?php
        $curaciones = [
            "herida_operatoria" => "HERIDA OPERATORIA",
            "traqueostomia" => "TRAQUEOSTOMIA",
            "ostomias" => "OSTOMIAS",
            "escaras" => "ESCARAS",
            "via_venosa_central" => "VÍA VENOSA CENTRAL",
            "via_venosa" => "VÍA VENOSA"
        ];

        foreach ($curaciones as $campo => $titulo) {
            echo '<div class="curacion-card">';
            echo "<h3>$titulo</h3>";
            echo '<div class="checkbox-wrapper">';
            
            // Radio button SÍ
            echo '<div class="form-check form-check-inline">';
            echo '<input class="form-check-input" type="radio" name="'.$campo.'" value="1" id="'.$campo.'_si">';
            echo '<label class="form-check-label" for="'.$campo.'_si">Sí</label>';
            echo '</div>';
            
            // Radio button NO (seleccionado por defecto)
            echo '<div class="form-check form-check-inline">';
            echo '<input class="form-check-input" type="radio" name="'.$campo.'" value="0" id="'.$campo.'_no" checked>';
            echo '<label class="form-check-label" for="'.$campo.'_no">No</label>';
            echo '</div>';
            
            // Campo de observación
            echo '<input type="text" name="obs_'.$campo.'" placeholder="Observación" class="form-control obs-input">';
            
            echo '</div></div>';
        }
        ?>

        <div class="mb-3 d-flex align-items-center time-group">
            <label for="hora_operacion">Hora de Curaciones:</label>
            <input type="time" id="hora_operacion" name="hora_operacion" class="form-control w-auto" required value="<?php echo date('H:i'); ?>">
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>