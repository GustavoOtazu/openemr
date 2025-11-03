<?php
/**
 * Formulario de Cuidados - report.php
 * Ruta: interface/forms/cuidados/report.php
 */

include_once("../../globals.php");

function cuidados_report($pid, $encounter, $cols, $id) {
    $count = 0;
    
    // Obtener datos del formulario
    $sql = "SELECT * FROM form_cuidados WHERE id = ? AND pid = ?";
    $result = sqlQuery($sql, array($id, $pid));
    
    if (!$result) {
        echo "<p>No se encontraron datos para este registro.</p>";
        return;
    }
    
    // Array de cuidados (excepto posición)
    $cuidados = array(
        'enjuague_bucal' => 'ENJUAGUE BUCAL',
        'higiene_manos' => 'HIGIENE DE MANOS PRE Y POST ASPIRADO',
        'aspirado_secreciones' => 'ASPIRADO DE SECRECIONES CON GUANTES Y AYUDANTE CON GUANTES',
        'suspension_sedacion' => 'SUSPENSION DIARIA DE SEDACION Y EVALUACION DE EXTUBACION',
        'medicion_cuff' => 'MEDICION DE PRESION DE CUFF'
    );
    
    // Subitems de posición
    $posiciones = array(
        'dli' => 'DLI',
        'dld' => 'DLD',
        'ds' => 'DS',
        'dv' => 'DV',
        'cabecera_30' => 'CABECERA 30°'
    );
    ?>
    
    <style>
        /* Forzar ajuste del contenedor padre de OpenEMR */
        #divid_2, .tab {
            max-width: none !important;
            width: 100% !important;
            overflow: visible !important;
        }
        
        .reporte-cuidados * { box-sizing: border-box; }
        .reporte-cuidados { 
            font-family: Arial, sans-serif; 
            max-width: 100%;
            overflow-x: auto;
            padding: 10px;
        }
        .reporte-cuidados .btn-reporte { 
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
        .reporte-cuidados .btn-reporte:hover { 
            background-color: #0056b3; 
        }
        .reporte-cuidados table { 
            width: 100%; 
            border-collapse: collapse;
            table-layout: fixed;
        }
        .reporte-cuidados table th { 
            background-color: #007bff; 
            color: white; 
            padding: 10px; 
            text-align: left; 
            font-weight: bold; 
            font-size: 12px;
            word-wrap: break-word;
        }
        .reporte-cuidados table td { 
            padding: 8px; 
            border: 1px solid #ddd; 
            font-size: 11px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .reporte-cuidados .item-nombre { font-weight: bold; }
        .reporte-cuidados .item-principal { 
            font-weight: bold; 
            color: #007bff; 
        }
        .reporte-cuidados .subitem { 
            padding-left: 20px; 
            font-style: normal; 
            font-size: 11px;
            font-weight: normal;
        }
        .reporte-cuidados .seleccionado { 
            background-color: #28a745; 
            color: white; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-cuidados .no-seleccionado {
            background-color: #f8f9fa;
            color: #6c757d;
            text-align: center;
        }
        .reporte-cuidados .si { 
            background-color: #d4edda; 
            color: #155724; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-cuidados .no { 
            background-color: #f8d7da; 
            color: #721c24; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-cuidados .info-box { 
            margin-top: 15px; 
            padding: 12px; 
            background-color: #e7f3ff; 
            border-left: 4px solid #007bff;
            font-size: 12px;
        }
        
        @media print {
            .reporte-cuidados .btn-reporte { display: none; }
            .reporte-cuidados { padding: 0; }
        }
    </style>
    
    <div class="reporte-cuidados">
        
        <table>
            <tr>
                <th width="40%">Item</th>
                <th width="15%">Estado</th>
                <th width="45%">Observación</th>
            </tr>
            
            <!-- POSICION DEL PACIENTE -->
            <tr>
                <td class="item-principal">POSICION DEL PACIENTE</td>
                <td style="text-align: center;">-</td>
                <td>-</td>
            </tr>
            <?php
            // Obtener la posición seleccionada de la base de datos (convertir a minúsculas para comparar)
            $posicion_seleccionada = strtolower($result['posicion_paciente'] ?? '');
            $observacion_posicion = $result['obs_posicion_paciente'] ?? '';
            
            // Mostrar todos los subitems
            foreach ($posiciones as $valor => $titulo) {
                $es_seleccionado = (strtolower($valor) === $posicion_seleccionada);
                ?>
                <tr>
                    <td class="subitem">└─ <?php echo $titulo; ?></td>
                    <?php if ($es_seleccionado) { ?>
                        <td class="seleccionado">✓ SELECCIONADO</td>
                        <td><?php echo !empty($observacion_posicion) ? htmlspecialchars($observacion_posicion) : '-'; ?></td>
                    <?php } else { ?>
                        <td class="no-seleccionado">-</td>
                        <td>-</td>
                    <?php } ?>
                </tr>
                <?php
            }
            
            // Resto de cuidados
            foreach ($cuidados as $campo => $titulo) {
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
            Hora de Cuidado: <?php echo $result['hora_cuidado'] ? date('H:i', strtotime($result['hora_cuidado'])) : 'No especificada'; ?><br>
            Registrado: <?php echo date('d/m/Y H:i', strtotime($result['date'])); ?>
        </div>
    </div>
    
    <?php
}
?>