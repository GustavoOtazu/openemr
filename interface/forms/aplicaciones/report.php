<?php
/**
 * Formulario de Aplicaciones - report.php
 * Ruta: interface/forms/aplicaciones/report.php
 */

include_once("../../globals.php");

function aplicaciones_report($pid, $encounter, $cols, $id) {
    $count = 0;
    
    // Obtener datos del formulario
    $sql = "SELECT * FROM form_aplicaciones WHERE id = ? AND pid = ?";
    $result = sqlQuery($sql, array($id, $pid));
    
    if (!$result) {
        echo "<p>No se encontraron datos para este registro.</p>";
        return;
    }
    
    // Array de aplicaciones
    $aplicaciones = array(
        'medicamentos' => 'MEDICAMENTOS',
        'sueros' => 'SUEROS',
        'vacunas' => 'VACUNAS',
        'expansiones' => 'EXPANSIONES',
        'sangre' => 'SANGRE'
    );
    ?>
    
    <style>
        /* Forzar ajuste del contenedor padre de OpenEMR */
        #divid_2, .tab {
            max-width: none !important;
            width: 100% !important;
            overflow: visible !important;
        }
        
        .reporte-aplicaciones * { box-sizing: border-box; }
        .reporte-aplicaciones { 
            font-family: Arial, sans-serif; 
            max-width: 100%;
            overflow-x: auto;
            padding: 10px;
        }
        .reporte-aplicaciones h3 {
            color: #2196F3;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #2196F3;
        }
        .reporte-aplicaciones .btn-reporte { 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 13px; 
            margin-bottom: 15px;
            background-color: #2196F3;
            color: white;
            transition: all 0.3s;
        }
        .reporte-aplicaciones .btn-reporte:hover { 
            background-color: #1976D2;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
        }
        
        /* Información del paciente */
        .reporte-aplicaciones .info-paciente {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2196F3;
        }
        .reporte-aplicaciones .info-paciente strong {
            color: #1976D2;
            font-size: 14px;
        }
        .reporte-aplicaciones .info-paciente p {
            margin: 5px 0;
            font-size: 13px;
            color: #333;
        }
        
        .reporte-aplicaciones table { 
            width: 100%; 
            border-collapse: collapse;
            table-layout: fixed;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .reporte-aplicaciones table th { 
            background-color: #2196F3; 
            color: white; 
            padding: 12px; 
            text-align: left; 
            font-weight: bold; 
            font-size: 13px;
            word-wrap: break-word;
        }
        .reporte-aplicaciones table td { 
            padding: 10px; 
            border: 1px solid #e0e0e0; 
            font-size: 12px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            background-color: white;
        }
        .reporte-aplicaciones .item-nombre { 
            font-weight: bold;
            color: #333;
        }
        .reporte-aplicaciones .si { 
            background-color: #d4edda; 
            color: #155724; 
            font-weight: bold; 
            text-align: center; 
        }
        .reporte-aplicaciones .no { 
            background-color: #f8d7da; 
            color: #721c24; 
            font-weight: bold; 
            text-align: center; 
        }
        
        /* Resumen */
        .reporte-aplicaciones .resumen-box {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
        }
        .reporte-aplicaciones .resumen-box strong {
            color: #856404;
            font-size: 14px;
            display: block;
            margin-bottom: 10px;
        }
        .reporte-aplicaciones .resumen-item {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            margin: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .reporte-aplicaciones .info-box { 
            margin-top: 20px; 
            padding: 15px; 
            background-color: #e3f2fd; 
            border-left: 4px solid #2196F3;
            border-radius: 5px;
            font-size: 13px;
            color: #333;
        }
        .reporte-aplicaciones .info-box strong {
            color: #1976D2;
            font-size: 14px;
            display: block;
            margin-bottom: 8px;
        }
        .reporte-aplicaciones .info-box p {
            margin: 5px 0;
        }
        
        @media print {
            .reporte-aplicaciones .btn-reporte { display: none; }
            .reporte-aplicaciones { padding: 0; }
            .reporte-aplicaciones table {
                box-shadow: none;
            }
        }
    </style>
    
    <div class="reporte-aplicaciones">
        <h3>REPORTE DE APLICACIONES</h3>
        
        <!-- INFORMACIÓN DEL PACIENTE -->
        <?php
        $sql_paciente = "SELECT CONCAT(fname, ' ', lname) as nombre_completo, pubpid FROM patient_data WHERE pid = ?";
        $paciente = sqlQuery($sql_paciente, array($pid));
        ?>
        <div class="info-paciente">
            <strong>Información del Paciente:</strong>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($paciente['nombre_completo'] ?? 'No disponible'); ?></p>
            <p><strong>CI/RG:</strong> <?php echo htmlspecialchars($paciente['pubpid'] ?? 'No disponible'); ?></p>
            <p><strong>Encounter:</strong> <?php echo htmlspecialchars($encounter); ?></p>
        </div>
        
        <!-- RESUMEN DE APLICACIONES REALIZADAS -->
        <?php
        $aplicaciones_realizadas = array();
        foreach ($aplicaciones as $campo => $titulo) {
            if (($result[$campo] ?? 0) == 1) {
                $aplicaciones_realizadas[] = $titulo;
            }
        }
        ?>
        <div class="resumen-box">
            <strong>Resumen de Aplicaciones Realizadas:</strong>
            <?php if (!empty($aplicaciones_realizadas)): ?>
                <?php foreach ($aplicaciones_realizadas as $app): ?>
                    <span class="resumen-item">✓ <?php echo $app; ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #856404; margin-top: 5px;">No se realizaron aplicaciones en este registro.</p>
            <?php endif; ?>
        </div>
        
        <!-- TABLA DETALLADA -->
        <table>
            <tr>
                <th width="40%">Item</th>
                <th width="15%">Estado</th>
                <th width="45%">Observación</th>
            </tr>
            <?php
            foreach ($aplicaciones as $campo => $titulo) {
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
        
        <!-- INFORMACIÓN DEL REGISTRO -->
        <div class="info-box">
            <strong>Información del Registro:</strong>
            <p><strong>Hora de Aplicación:</strong> <?php echo $result['hora_registro'] ? date('H:i', strtotime($result['hora_registro'])) : 'No especificada'; ?></p>
            <p><strong>Fecha de Registro:</strong> <?php echo date('d/m/Y', strtotime($result['date'])); ?></p>
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($result['user'] ?? 'No especificado'); ?></p>
            
        </div>
    </div>
    
    <?php
}
?>