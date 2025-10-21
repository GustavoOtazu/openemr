<?php
/**
 * Formulario de Evaluaciones - report.php
 * Ruta: interface/forms/evaluaciones/report.php
 */

function evaluaciones_report($pid, $encounter, $cols, $id) {
    $count = 0;
    
    // Obtener datos del formulario
    $sql = "SELECT * FROM form_evaluaciones WHERE id = ? AND pid = ? LIMIT 1";
    $result = sqlQuery($sql, array($id, $pid));
    
    if (!$result) {
        echo "<div style='padding: 20px; color: red;'>No se encontraron datos</div>";
        return;
    }
    
    ?>
    <style>
        .reporte-evaluaciones * { margin: 0; padding: 0; box-sizing: border-box; }
        .reporte-evaluaciones { font-family: Arial, sans-serif; padding: 20px; background-color: white; }
        .reporte-evaluaciones .btn { 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 12px; 
            margin-bottom: 15px; 
        }
        .reporte-evaluaciones .btn-imprimir { 
            background-color: #007bff; 
            color: white; 
        }
        .reporte-evaluaciones .btn-imprimir:hover { 
            background-color: #0056b3; 
        }
        .reporte-evaluaciones table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }
        .reporte-evaluaciones table th { 
            background-color: #007bff; 
            color: white; 
            padding: 12px; 
            text-align: left; 
            font-weight: bold; 
            font-size: 12px; 
        }
        .reporte-evaluaciones table td { 
            padding: 10px; 
            border: 1px solid #ddd; 
            font-size: 11px; 
        }
        .reporte-evaluaciones .item-principal {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .reporte-evaluaciones .item-glasgow {
            background-color: #fff3cd;
            font-weight: bold;
            color: #856404;
        }
        .reporte-evaluaciones .subitem {
            padding-left: 30px;
            font-style: italic;
            color: #666;
        }
        .reporte-evaluaciones .seleccionado {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
        }
        .reporte-evaluaciones .info-adicional {
            margin-top: 20px;
            padding: 15px;
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
        }
        .reporte-evaluaciones .glasgow-score {
            margin-top: 15px;
            padding: 15px;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            font-size: 14px;
        }
        .reporte-evaluaciones .glasgow-score strong {
            font-size: 18px;
            color: #856404;
        }

        @media print {
            .reporte-evaluaciones body { background-color: white; }
            .reporte-evaluaciones .btn { display: none; }
        }
    </style>

    <div class="reporte-evaluaciones">
        <button class="btn btn-imprimir" onclick="window.print()">Imprimir</button>

        <table>
            <tr>
                <th width="40%">Item</th>
                <th width="30%">Respuesta</th>
                <th width="30%">Observación</th>
            </tr>
            
            <!-- CONCIENCIA -->
            <tr>
                <td class="item-principal"> CONCIENCIA</td>
                <td><?php echo htmlspecialchars($result['conciencia'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($result['obs_conciencia'] ?? '-'); ?></td>
            </tr>
            
            <!-- TONO -->
            <tr>
                <td class="item-principal"> TONO</td>
                <td><?php echo htmlspecialchars($result['tono'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($result['obs_tono'] ?? '-'); ?></td>
            </tr>
            
            <!-- PUPILAS -->
            <tr>
                <td class="item-principal"> PUPILAS</td>
                <td><?php echo htmlspecialchars($result['pupilas'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($result['obs_pupilas'] ?? '-'); ?></td>
            </tr>
            
            <!-- MUCOSAS -->
            <tr>
                <td class="item-principal"> MUCOSAS</td>
                <td><?php echo htmlspecialchars($result['mucosas'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($result['obs_mucosas'] ?? '-'); ?></td>
            </tr>
            
            <!-- ESCALA DE GLASGOW -->
            <tr>
                <td colspan="3" class="item-glasgow">ESCALA DE GLASGOW</td>
            </tr>
            
            <!-- OJOS ABIERTOS -->
            <tr>
                <td class="subitem">└─ OJOS ABIERTOS</td>
                <td class="seleccionado"><?php echo htmlspecialchars($result['glasgow_ojos'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($result['obs_glasgow_ojos'] ?? '-'); ?></td>
            </tr>
            
            <!-- RESPUESTA MOTORA -->
            <tr>
                <td class="subitem">└─ RESPUESTA MOTORA</td>
                <td class="seleccionado"><?php echo htmlspecialchars($result['glasgow_motora'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($result['obs_glasgow_motora'] ?? '-'); ?></td>
            </tr>
            
            <!-- RESPUESTA VERBAL -->
            <tr>
                <td class="subitem">└─ RESPUESTA VERBAL</td>
                <td class="seleccionado"><?php echo htmlspecialchars($result['glasgow_verbal'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($result['obs_glasgow_verbal'] ?? '-'); ?></td>
            </tr>
        </table>

        <!-- PUNTAJE TOTAL GLASGOW -->
        <div class="glasgow-score">
            <strong>Puntaje Total Glasgow: <?php echo $result['glasgow_total'] ?? 0; ?>/15</strong>
            <?php
            $puntaje = $result['glasgow_total'] ?? 0;
            if ($puntaje >= 13) {
                echo " - <span style='color: green;'>Leve</span>";
            } elseif ($puntaje >= 9) {
                echo " - <span style='color: orange;'>Moderado</span>";
            } else {
                echo " - <span style='color: red;'>Severo</span>";
            }
            ?>
        </div>

        <!-- INFORMACIÓN ADICIONAL -->
        <div class="info-adicional">
            <strong>Información del Registro:</strong><br>
            Hora de Evaluación: <?php echo htmlspecialchars($result['hora_evaluacion'] ?? '-'); ?><br>
            Registrado: <?php echo date('d/m/Y H:i', strtotime($result['date'])); ?>
        </div>
    </div>
    <?php
}
?>