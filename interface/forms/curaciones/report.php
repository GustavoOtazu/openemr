<?php
/**
 * Formulario de Curaciones - report.php
 * Ruta: interface/forms/curaciones/report.php
 */

include_once("../../globals.php");

function curaciones_report($pid, $encounter, $cols, $id) {
    $count = 0;
    
    // Obtener datos del formulario
    $sql = "SELECT * FROM form_curaciones WHERE id = ? AND pid = ?";
    $result = sqlQuery($sql, array($id, $pid));
    
    if (!$result) {
        echo "<p>No se encontraron datos para este registro.</p>";
        return;
    }
    
    // Array de curaciones
    $curaciones = array(
        'herida_operatoria' => 'HERIDA OPERATORIA',
        'traqueostomia' => 'TRAQUEOSTOMIA',
        'ostomias' => 'OSTOMIAS',
        'escaras' => 'ESCARAS',
        'via_venosa_central' => 'VÍA VENOSA CENTRAL',
        'via_venosa' => 'VÍA VENOSA'
    );
    ?>
    
    <style>
        /* Forzar ajuste del contenedor padre de OpenEMR */
        #divid_2, .tab {
            max-width: none !important;
            width: 100% !important;
            overflow: visible !important;
        }
        
        .reporte-curaciones * { box-sizing: border-box; }
        .reporte-curaciones { 
            font-family: Arial, sans-serif; 
            max-width: 100%;
            overflow-x: auto;
            padding: 10px;
        }
        .reporte-curaciones .btn-reporte { 
            padding: 8px 16px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 12px; 
            margin-bottom: 10px;
            background-color: #007bff;
            color: white;
        }
        .reporte-curaciones .btn-reporte:hover { 
            background-color: #0056b3; 
        }
        .reporte-curaciones table { 
            width: 100%; 
            border-collapse: collapse;
            table-layout: fixed;
        }
        .reporte-curaciones table th { 
            background-color: #007bff; 
            color: white; 
            padding: 10px; 
            text-align: left; 
            font-weight: bold; 
            font-size: 12px;
            word-wrap: break-word;
        }
        .reporte-curaciones table td { 
            padding: 8px; 
            border: 1px solid #ddd; 
            font-size: 11px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .reporte-curaciones .item-nombre { font-weight: bold; }
        .reporte-curaciones .si { 
            background-color: #d4edda; 
            color: #155724; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-curaciones .no { 
            background-color: #f8d7da; 
            color: #721c24; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-curaciones .info-box { 
            margin-top: 15px; 
            padding: 12px; 
            background-color: #e7f3ff; 
            border-left: 4px solid #007bff;
            font-size: 12px;
        }
        
        @media print {
            .reporte-curaciones .btn-reporte { display: none; }
            .reporte-curaciones { padding: 0; }
        }
    </style>
    
    <div class="reporte-curaciones">
        <button class="btn-reporte" onclick="window.print()">🖨️ Imprimir</button>
        
        <table>
            <tr>
                <th width="40%">Item</th>
                <th width="15%">Estado</th>
                <th width="45%">Observación</th>
            </tr>
            <?php
            foreach ($curaciones as $campo => $titulo) {
                $valor = $result[$campo] ?? 0;
                $observacion = $result['obs_' . $campo] ?? '';
                $estado_clase = $valor == 1 ? 'si' : 'no';
                $estado_texto = $valor == 1 ? 'SÍ' : 'NO';
                ?>
                <tr>
                    <td class="item-nombre"><?php echo $titulo; ?></td>
                    <td class="<?php echo $estado_clase; ?>"><?php echo $estado_texto; ?></td>
                    <td><?php echo !empty($observacion) ? htmlspecialchars($observacion) : '-'; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        
        <div class="info-box">
            <strong>Información del Registro:</strong><br>
            Hora de Curaciones: <?php echo $result['hora_operacion'] ? date('H:i', strtotime($result['hora_operacion'])) : 'No especificada'; ?><br>
            Registrado: <?php echo date('d/m/Y H:i', strtotime($result['date'])); ?>
        </div>
    </div>
    
    <?php
}
?>