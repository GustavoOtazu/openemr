<?php
/**
 * Formulario de Registro VM - report.php
 * Ruta: interface/forms/registro_vm/report.php
 */

include_once("../../globals.php");

function registro_vm_report($pid, $encounter, $cols, $id) {
    $count = 0;
    
    // Obtener datos del formulario
    $sql = "SELECT * FROM form_registro_vm WHERE id = ? AND pid = ?";
    $result = sqlQuery($sql, array($id, $pid));
    
    if (!$result) {
        echo "<div style='padding: 20px; color: red;'>No se encontraron datos del Registro VM.</div>";
        return;
    }
    
    // Definir items
    $items = array(
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
    
    <style>
        .reporte-vm { padding: 20px; font-family: Arial, sans-serif; }
        .reporte-vm .btn-imprimir { 
            background-color: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold; 
            margin-bottom: 15px; 
        }
        .reporte-vm .btn-imprimir:hover { background-color: #0056b3; }
        .reporte-vm table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .reporte-vm table th { 
            background-color: #007bff; 
            color: white; 
            padding: 12px; 
            text-align: left; 
            font-weight: bold; 
        }
        .reporte-vm table td { 
            padding: 10px; 
            border: 1px solid #ddd; 
        }
        .reporte-vm .item-principal { font-weight: bold; }
        .reporte-vm .si { 
            background-color: #d4edda; 
            color: #155724; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-vm .no { 
            background-color: #f8d7da; 
            color: #721c24; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-vm .modo-section {
            margin: 15px 0;
            padding: 15px;
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
        }
        .reporte-vm .info-adicional {
            margin-top: 20px;
            padding: 15px;
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
        }
        
        @media print {
            .reporte-vm .btn-imprimir { display: none; }
        }
    </style>
    
    <div class="reporte-vm">
        
        <div class="modo-section">
            <strong>MODO DE VENTILACION:</strong> 
            <span style="font-size: 16px; color: #007bff; font-weight: bold;">
                <?php echo strtoupper($result['modo_ventilacion']); ?>
            </span>
        </div>
        
        <table>
            <tr>
                <th width="40%">Item</th>
                <th width="20%">Estado</th>
                <th width="40%">Observación</th>
            </tr>
            <?php
            foreach ($items as $campo => $titulo) {
                $valor = (int)$result[$campo];
                $observacion = $result['obs_' . $campo] ?? '-';
                $estado_class = $valor == 1 ? 'si' : 'no';
                $estado_texto = $valor == 1 ? 'SÍ' : 'NO';
                
                echo '<tr>';
                echo '<td class="item-principal">' . $titulo . '</td>';
                echo '<td class="' . $estado_class . '">' . $estado_texto . '</td>';
                echo '<td>' . ($observacion ?: '-') . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
        
        <div class="info-adicional">
            <strong>Información del Registro:</strong><br>
            Hora de Registro: <?php echo date('H:i', strtotime($result['hora_registro'])); ?><br>
            Registrado: <?php echo date('d/m/Y H:i', strtotime($result['date'])); ?>
        </div>
    </div>
    
    <?php
}
?>