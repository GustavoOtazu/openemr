<?php
/**
 * Formulario de Evaluaciones - view.php
 * Ruta: interface/forms/evaluaciones/view.php
 */

include_once("../../globals.php");

$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;

if (!$pid || !$encounter) {
    echo "<div class='alert alert-danger'>No se pudo obtener PID o Encounter.</div>";
    exit;
}

$sql = "SELECT * FROM form_evaluaciones WHERE pid = ? AND encounter = ? ORDER BY date DESC";
$result = sqlStatement($sql, array($pid, $encounter));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Evaluaciones</title>
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
            max-width: 1400px;
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
        .btn-nuevo {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-nuevo:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        table tr:hover {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .btn-ver {
            background-color: #007bff;
            color: white;
            padding: 6px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
        }
        .btn-ver:hover {
            background-color: #0056b3;
        }
        .no-registros {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>LISTA DE EVALUACIONES</h2>
        
        <a href="new.php?pid=<?php echo $pid; ?>&encounter=<?php echo $encounter; ?>" class="btn-nuevo">
            + Nueva Evaluación
        </a>
        
        <?php
        $count = 0;
        while ($row = sqlFetchArray($result)) {
            if ($count == 0) {
                echo "<table>";
                echo "<tr>";
                echo "<th>Fecha/Hora</th>";
                echo "<th>Conciencia</th>";
                echo "<th>Tono</th>";
                echo "<th>Pupilas</th>";
                echo "<th>Glasgow</th>";
                echo "<th>Hora Evaluación</th>";
                echo "<th>Acciones</th>";
                echo "</tr>";
            }
            $count++;
            
            // Determinar nivel de Glasgow
            $glasgow = $row['glasgow_total'] ?? 0;
            if ($glasgow >= 13) {
                $glasgow_badge = "badge-success";
                $glasgow_nivel = "Leve";
            } elseif ($glasgow >= 9) {
                $glasgow_badge = "badge-warning";
                $glasgow_nivel = "Moderado";
            } else {
                $glasgow_badge = "badge-danger";
                $glasgow_nivel = "Severo";
            }
            
            echo "<tr>";
            echo "<td>" . date('d/m/Y H:i', strtotime($row['date'])) . "</td>";
            echo "<td>" . htmlspecialchars($row['conciencia'] ?? '-') . "</td>";
            echo "<td>" . htmlspecialchars($row['tono'] ?? '-') . "</td>";
            echo "<td>" . htmlspecialchars($row['pupilas'] ?? '-') . "</td>";
            echo "<td><span class='badge $glasgow_badge'>$glasgow/15 - $glasgow_nivel</span></td>";
            echo "<td>" . htmlspecialchars($row['hora_evaluacion'] ?? '-') . "</td>";
            echo "<td>";
            echo "<a href='#' onclick='verDetalle(" . $row['id'] . ")' class='btn-ver'>Ver Detalle</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        if ($count == 0) {
            echo "<div class='no-registros'>";
            echo "No hay evaluaciones registradas para este encuentro.";
            echo "</div>";
        } else {
            echo "</table>";
        }
        ?>
    </div>
    
    <!-- Modal para ver detalle -->
    <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #007bff; color: white;">
                    <h4 class="modal-title">Detalle de Evaluación</h4>
                    <button type="button" class="close" data-dismiss="modal" style="color: white;">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contenidoDetalle">
                    <!-- El contenido se carga aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="imprimirDetalle()">
                        <i class="fa fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <script>
        function verDetalle(id) {
            // Cargar el detalle mediante AJAX
            $.ajax({
                url: 'report.php',
                type: 'GET',
                data: {
                    id: id,
                    pid: <?php echo $pid; ?>
                },
                success: function(response) {
                    // Extraer solo el contenido del reporte
                    var contenido = $(response).find('.reporte-evaluaciones').html();
                    if (!contenido) {
                        contenido = response;
                    }
                    $('#contenidoDetalle').html(contenido);
                    $('#modalDetalle').modal('show');
                },
                error: function() {
                    alert('Error al cargar el detalle de la evaluación');
                }
            });
        }
        
        function imprimirDetalle() {
            var contenido = document.getElementById('contenidoDetalle').innerHTML;
            var ventana = window.open('', '_blank');
            ventana.document.write('<html><head><title>Evaluación</title>');
            ventana.document.write('<style>');
            ventana.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
            ventana.document.write('table { width: 100%; border-collapse: collapse; margin-top: 15px; }');
            ventana.document.write('table th { background-color: #007bff; color: white; padding: 12px; text-align: left; }');
            ventana.document.write('table td { padding: 10px; border: 1px solid #ddd; }');
            ventana.document.write('.item-principal { font-weight: bold; background-color: #f8f9fa; }');
            ventana.document.write('.item-glasgow { background-color: #fff3cd; font-weight: bold; color: #856404; }');
            ventana.document.write('.subitem { padding-left: 30px; font-style: italic; color: #666; }');
            ventana.document.write('.seleccionado { background-color: #d4edda; color: #155724; font-weight: bold; }');
            ventana.document.write('.info-adicional { margin-top: 20px; padding: 15px; background-color: #e7f3ff; border-left: 4px solid #007bff; }');
            ventana.document.write('.glasgow-score { margin-top: 15px; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffc107; }');
            ventana.document.write('</style>');
            ventana.document.write('</head><body>');
            ventana.document.write(contenido);
            ventana.document.write('</body></html>');
            ventana.document.close();
            ventana.print();
        }
    </script>
</body>
</html>