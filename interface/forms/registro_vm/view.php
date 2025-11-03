<?php
/**
 * Formulario de Registro VM - view.php SIMPLE
 * Siguiendo EXACTAMENTE el formato del REGISTRO DE APLICACIÓN
 */

include_once("../../globals.php");

$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;
$id = $_GET['id'] ?? null;

if (!$pid || !$encounter) {
    echo "<div class='alert alert-danger'>No se pudo obtener PID o Encounter.</div>";
    exit;
}

// Obtener información del paciente
$sql_paciente = "SELECT CONCAT(fname, ' ', lname) as nombre_completo, pubpid, DOB, sex FROM patient_data WHERE pid = ?";
$paciente = sqlQuery($sql_paciente, array($pid));

// Calcular edad
$edad = '';
if (!empty($paciente['DOB'])) {
    $fecha_nac = new DateTime($paciente['DOB']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha_nac)->y . ' años';
}

// Consultar registros
if ($id) {
    $sql = "SELECT * FROM form_registro_vm WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $result = sqlStatement($sql, array($id, $pid, $encounter));
} else {
    $sql = "SELECT * FROM form_registro_vm WHERE pid = ? AND encounter = ? ORDER BY date DESC";
    $result = sqlStatement($sql, array($pid, $encounter));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Registros VM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
            font-size: 24px;
        }
        
        .info-paciente {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .info-paciente h3 {
            color: #495057;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .info-paciente .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .info-paciente .info-item {
            background-color: white;
            padding: 10px 15px;
            border-radius: 3px;
            border: 1px solid #e0e0e0;
        }
        .info-paciente .info-item strong {
            color: #495057;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
        }
        .info-paciente .info-item span {
            color: #333;
            font-size: 15px;
            font-weight: 500;
        }
        
        .registro-card {
            background-color: white;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .registro-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .registro-fecha {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .fecha-principal {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        .hora-principal {
            font-size: 14px;
            color: #6c757d;
        }
        
        .registro-acciones {
            display: flex;
            gap: 12px;
        }
        
        .btn-accion {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-editar {
            background-color: #007bff;
            color: white;
        }
        .btn-editar:hover {
            background-color: #0056b3;
        }
        
        .btn-imprimir {
            background-color: #28a745;
            color: white;
        }
        .btn-imprimir:hover {
            background-color: #218838;
        }
        
        .registro-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }
        .info-box-small {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-radius: 3px;
            border: 1px solid #dee2e6;
        }
        .info-box-small strong {
            color: #495057;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .info-box-small span {
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }
        
        .seccion-titulo {
            font-size: 15px;
            font-weight: 700;
            color: #495057;
            margin: 20px 0 15px 0;
            padding: 10px 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 5px solid #667eea;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .vm-detalle {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        
        .vm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background-color: #f8f9fa;
        }
        .vm-header.si {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
        }
        .vm-header.no {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .vm-header.modo {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
        }
        
        .vm-nombre {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .estado-badge {
            padding: 6px 14px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        .estado-badge.si {
            background-color: #28a745;
            color: white;
        }
        .estado-badge.no {
            background-color: #dc3545;
            color: white;
        }
        .estado-badge.modo {
            background-color: #2196F3;
            color: white;
        }
        
        .vm-obs {
            padding: 18px 20px;
            background-color: #f8f9fa;
        }
        .vm-obs.con-contenido {
            background: linear-gradient(135deg, #e7f3ff 0%, #cfe2ff 100%);
            border-top: 3px solid #0d6efd;
        }
        .vm-obs h5 {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .vm-obs p {
            font-size: 14px;
            color: #212529;
            line-height: 1.7;
            margin: 0;
            white-space: pre-wrap;
        }
        
        .no-registros {
            text-align: center;
            padding: 80px 40px;
            color: #999;
            background-color: #f8f9fa;
            border-radius: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🫁 <?php echo $id ? 'DETALLE DE REGISTRO VM' : 'LISTA DE REGISTROS VM'; ?></h2>
        
        <div class="info-paciente">
            <h3>👤 INFORMACIÓN DEL PACIENTE</h3>
            <div class="info-row">
                <div class="info-item">
                    <strong>📝 Paciente:</strong>
                    <span><?php echo htmlspecialchars($paciente['nombre_completo'] ?? 'No disponible'); ?></span>
                </div>
                <div class="info-item">
                    <strong>🆔 CI/RG:</strong>
                    <span><?php echo htmlspecialchars($paciente['pubpid'] ?? 'No disponible'); ?></span>
                </div>
                <?php if (!empty($edad)): ?>
                <div class="info-item">
                    <strong>🎂 Edad:</strong>
                    <span><?php echo $edad; ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php
        if (sqlNumRows($result) == 0) {
            echo "<div class='no-registros'>";
            echo "<h3>No hay registros VM registrados</h3>";
            echo "</div>";
        }
        
        while ($row = sqlFetchArray($result)) {
            ?>
            
            <div class="registro-card" id="registro-<?php echo $row['id']; ?>">
                <div class="registro-header">
                    <div class="registro-fecha">
                        <span class="fecha-principal">📅 <?php echo date('d/m/Y', strtotime($row['date'])); ?></span>
                        <span class="hora-principal">🕐 <?php echo date('H:i', strtotime($row['date'])); ?></span>
                    </div>
                    <div class="registro-acciones">
                        <a href="<?php echo $GLOBALS['webroot']; ?>/interface/forms/registro_vm/new.php?pid=<?php echo $pid; ?>&encounter=<?php echo $encounter; ?>&id=<?php echo $row['id']; ?>" class="btn-accion btn-editar">
                            ✏️ EDITAR
                        </a>
                        <button onclick="imprimirRegistro(<?php echo $row['id']; ?>)" class="btn-accion btn-imprimir">
                            🖨️ IMPRIMIR
                        </button>
                    </div>
                </div>
                
                <div class="registro-info">
                    <div class="info-box-small">
                        <strong>⏰ Hora de Registro:</strong>
                        <span><?php echo htmlspecialchars($row['hora_registro'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-box-small">
                        <strong>👨‍⚕️ Usuario:</strong>
                        <span><?php echo htmlspecialchars($row['user'] ?? 'N/A'); ?></span>
                    </div>
                </div>
                
                <div class="seccion-titulo">🫁 DETALLE DE REGISTRO VM</div>
                
                <!-- Modo de Ventilación (Especial) -->
                <div class="vm-detalle">
                    <div class="vm-header modo">
                        <div class="vm-nombre">
                            <span>🫁 MODO DE VENTILACIÓN</span>
                        </div>
                        <span class="estado-badge modo">
                            <?php echo !empty($row['modo_ventilacion']) ? htmlspecialchars($row['modo_ventilacion']) : 'NO ESPECIFICADO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_modo']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_modo']) ? nl2br(htmlspecialchars($row['obs_modo'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Presión -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['presion'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>📊 PRESIÓN</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['presion'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['presion'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_presion']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_presion']) ? nl2br(htmlspecialchars($row['obs_presion'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Volumen -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['volumen'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>📏 VOLUMEN</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['volumen'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['volumen'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_volumen']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_volumen']) ? nl2br(htmlspecialchars($row['obs_volumen'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- SIMV -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['simv'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>🔄 SIMV</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['simv'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['simv'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_simv']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_simv']) ? nl2br(htmlspecialchars($row['obs_simv'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- PSV -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['psv'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>🔧 PSV</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['psv'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['psv'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_psv']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_psv']) ? nl2br(htmlspecialchars($row['obs_psv'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Otros -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['otros'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>⚙️ OTROS</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['otros'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['otros'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_otros']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_otros']) ? nl2br(htmlspecialchars($row['obs_otros'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Frecuencia Respiratoria -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['frecuencia_respiratoria'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>💨 FRECUENCIA RESPIRATORIA</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['frecuencia_respiratoria'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['frecuencia_respiratoria'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_frecuencia_respiratoria']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_frecuencia_respiratoria']) ? nl2br(htmlspecialchars($row['obs_frecuencia_respiratoria'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- P.Inspiratorio / T.Inspiratorio -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['p_inspiratorio'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>📈 P.INSPIRATORIO / T.INSPIRATORIO</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['p_inspiratorio'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['p_inspiratorio'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_p_inspiratorio']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_p_inspiratorio']) ? nl2br(htmlspecialchars($row['obs_p_inspiratorio'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- P.Media / PEEP -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['p_media'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>📊 P.MEDIA / PEEP</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['p_media'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['p_media'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_p_media']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_p_media']) ? nl2br(htmlspecialchars($row['obs_p_media'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- P.Max / P.Plateau -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['p_max'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>⬆️ P.MAX / P.PLATEAU</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['p_max'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['p_max'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_p_max']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_p_max']) ? nl2br(htmlspecialchars($row['obs_p_max'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- CHST / CDIN -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['chst'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>🔍 CHST / CDIN</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['chst'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['chst'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_chst']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_chst']) ? nl2br(htmlspecialchars($row['obs_chst'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Disparo por F/P -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['disparo'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>🎯 DISPARO POR F/P</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['disparo'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['disparo'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_disparo']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_disparo']) ? nl2br(htmlspecialchars($row['obs_disparo'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- F / VT -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['fvt'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>📈 F / VT</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['fvt'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['fvt'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_fvt']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_fvt']) ? nl2br(htmlspecialchars($row['obs_fvt'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Vol.Tidal / Flujo -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['vol_tidal'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>💧 VOL.TIDAL / FLUJO</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['vol_tidal'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['vol_tidal'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_vol_tidal']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_vol_tidal']) ? nl2br(htmlspecialchars($row['obs_vol_tidal'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- VM Programado / Medido -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['vm_programado'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>⚙️ VM PROGRAMADO / MEDIDO</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['vm_programado'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['vm_programado'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_vm_programado']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_vm_programado']) ? nl2br(htmlspecialchars($row['obs_vm_programado'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- PETCO2 -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['petco2'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>🌬️ PETCO2</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['petco2'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['petco2'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_petco2']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_petco2']) ? nl2br(htmlspecialchars($row['obs_petco2'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- VD / VT -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['vdvt'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>📊 VD / VT</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['vdvt'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['vdvt'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_vdvt']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_vdvt']) ? nl2br(htmlspecialchars($row['obs_vdvt'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- KO2 -->
                <div class="vm-detalle">
                    <div class="vm-header <?php echo ($row['ko2'] == 1) ? 'si' : 'no'; ?>">
                        <div class="vm-nombre">
                            <span>💨 KO2</span>
                        </div>
                        <span class="estado-badge <?php echo ($row['ko2'] == 1) ? 'si' : 'no'; ?>">
                            <?php echo ($row['ko2'] == 1) ? '✓ SÍ' : '✗ NO'; ?>
                        </span>
                    </div>
                    <div class="vm-obs <?php echo !empty($row['obs_ko2']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_ko2']) ? nl2br(htmlspecialchars($row['obs_ko2'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
            </div>
            
        <?php } ?>
    </div>
    
    <!-- Librerías para generar PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <script>
        async function imprimirRegistro(id) {
            var registro = document.getElementById('registro-' + id);
            if (!registro) {
                alert('No se encontró el registro');
                return;
            }
            
            // Mostrar mensaje de carga
            var loadingMsg = document.createElement('div');
            loadingMsg.style.position = 'fixed';
            loadingMsg.style.top = '50%';
            loadingMsg.style.left = '50%';
            loadingMsg.style.transform = 'translate(-50%, -50%)';
            loadingMsg.style.background = 'rgba(0,0,0,0.8)';
            loadingMsg.style.color = 'white';
            loadingMsg.style.padding = '20px 40px';
            loadingMsg.style.borderRadius = '10px';
            loadingMsg.style.zIndex = '99999';
            loadingMsg.style.fontSize = '18px';
            loadingMsg.textContent = 'Generando PDF...';
            document.body.appendChild(loadingMsg);
            
            // Función para crear header que se repetirá en cada página
            function crearHeader() {
                return `
                    <div style="padding: 0 10px; margin-bottom: 25px;">
                        <div style="background: white; padding: 20px 15px; margin-bottom: 0; border-bottom: 1px solid #000; text-align: center;">
                            <img src="<?php echo $GLOBALS["web_root"]; ?>/public/images/logo_ips.png" 
                                 style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain; display: inline-block;"
                                 onerror="this.style.display='none'">
                        </div>
                    
                        <div style="text-align: center; font-size: 16px; font-weight: bold; color: #000; margin: 12px 0 0 0; padding: 10px 0; background: white; letter-spacing: 2.5px; text-transform: uppercase;">
                            REGISTRO DE VENTILACIÓN MECÁNICA
                        </div>
                        
                        <div style="height: 2px; background: #000; margin: 0 0 18px 0;"></div>
                    </div>
                `;
            }
            
            // Obtener todos los parámetros
            var parametros = registro.querySelectorAll('.vm-detalle');
            var parametrosArray = Array.from(parametros);
            
            // Dividir parámetros en páginas (7 por página)
            var parametrosPorPagina = 7;
            var paginas = [];
            
            for (var i = 0; i < parametrosArray.length; i += parametrosPorPagina) {
                paginas.push(parametrosArray.slice(i, i + parametrosPorPagina));
            }
            
            // Crear array de contenedores de página
            var printContainers = [];
            
            paginas.forEach(function(paramsGrupo, indexPagina) {
                var esPrimeraPagina = (indexPagina === 0);
                var esUltimaPagina = (indexPagina === paginas.length - 1);
                
                // Crear contenedor para esta página
                var printContainer = document.createElement('div');
                printContainer.style.position = 'absolute';
                printContainer.style.left = '-9999px';
                printContainer.style.width = '900px';
                printContainer.style.fontFamily = 'Arial, sans-serif';
                printContainer.style.backgroundColor = 'white';
                printContainer.style.padding = '20px 30px 20px 40px';
                
                // Construir HTML de esta página
                var htmlContent = crearHeader();
                
                // Información del paciente (solo en primera página)
                if (esPrimeraPagina) {
                    htmlContent += `
                        <div style="background: white; padding: 0; margin: 15px 0; border: 1px solid #000;">
                            <div style="font-weight: bold; font-size: 11px; margin: 0; color: #000; border-bottom: 1px solid #000; padding: 8px 10px; background: #f5f5f5; text-transform: uppercase; letter-spacing: 0.5px;">
                                INFORMACIÓN DEL PACIENTE
                            </div>
                            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                                <tr>
                                    <td style="padding: 12px 15px; background: white; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 33%; vertical-align: top;">
                                        <div style="color: #666; font-size: 10px; margin-bottom: 5px; font-weight: bold;">PACIENTE:</div>
                                        <div style="color: #000; font-weight: 600; font-size: 12px;"><?php echo htmlspecialchars($paciente['nombre_completo'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td style="padding: 12px 15px; background: white; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 33%; vertical-align: top;">
                                        <div style="color: #666; font-size: 10px; margin-bottom: 5px; font-weight: bold;">CI/RG:</div>
                                        <div style="color: #000; font-weight: 600; font-size: 12px;"><?php echo htmlspecialchars($paciente['pubpid'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td style="padding: 12px 15px; background: white; border-bottom: 1px solid #000; width: 34%; vertical-align: top;">
                                        <div style="color: #666; font-size: 10px; margin-bottom: 5px; font-weight: bold;">EDAD:</div>
                                        <div style="color: #000; font-weight: 600; font-size: 12px;"><?php echo !empty($edad) ? $edad : 'N/A'; ?></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    `;
                }
                
                // Título de sección
                htmlContent += `
                    <div style="font-weight: bold; font-size: 12px; color: #000; margin: 20px 0 15px 0; padding: 10px 15px; border: 1px solid #000; border-left: 4px solid #000; background: #f5f5f5; text-transform: uppercase; letter-spacing: 0.5px;">
                        DETALLE DE REGISTRO VM ${!esPrimeraPagina ? '(CONTINUACIÓN)' : ''}
                    </div>
                `;
                
                // Parámetros de esta página
                paramsGrupo.forEach(function(param) {
                    var nombre = param.querySelector('.vm-nombre').textContent.trim();
                    var badge = param.querySelector('.estado-badge');
                    var esAfirmativo = badge.classList.contains('si') || badge.textContent.includes('SÍ');
                    var obsText = param.querySelector('.vm-obs p').textContent.trim();
                    
                    var estadoTexto = esAfirmativo ? 'SÍ' : 'NO';
                    
                    htmlContent += `
                        <div style="margin-bottom: 12px; border: 1px solid #000; overflow: hidden; background: white;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background: white; border-bottom: 1px solid #000;">
                                <span style="font-weight: 600; font-size: 11px; color: #000;">${nombre}</span>
                                <span style="border: 1px solid #000; padding: 6px 15px; font-size: 10px; font-weight: bold; color: #000; min-width: 40px; text-align: center;">
                                    ${estadoTexto}
                                </span>
                            </div>
                            <div style="padding: 12px 15px; background: white; font-size: 10px; min-height: 50px;">
                                <div style="color: #666; font-size: 9px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">OBSERVACIONES:</div>
                                <div style="color: ${obsText === 'Sin observaciones registradas' ? '#999' : '#000'}; line-height: 1.5; ${obsText === 'Sin observaciones registradas' ? 'font-style: italic;' : ''}">${obsText}</div>
                            </div>
                        </div>
                    `;
                });
                
                // Firmas (solo en última página)
                if (esUltimaPagina) {
                    htmlContent += `
                        <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #000;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 50%; text-align: center; vertical-align: bottom; padding: 0 30px;">
                                        <div style="border-top: 2px solid #000; margin: 60px 20px 8px 20px;"></div>
                                        <div style="font-size: 11px; font-weight: bold; color: #000; margin-top: 8px;">FIRMA DEL RESPONSABLE</div>
                                        <div style="font-size: 9px; color: #666; margin-top: 5px;">Nombre y Apellido</div>
                                    </td>
                                    <td style="width: 50%; text-align: center; vertical-align: bottom; padding: 0 30px;">
                                        <div style="border-top: 2px solid #000; margin: 60px 20px 8px 20px;"></div>
                                        <div style="font-size: 11px; font-weight: bold; color: #000; margin-top: 8px;">ACLARACIÓN</div>
                                        <div style="font-size: 9px; color: #666; margin-top: 5px;">C.I. Nº</div>
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: center; margin-top: 20px; font-size: 9px; color: #666;">
                                Fecha: _____/_____/________
                            </div>
                        </div>
                    `;
                }
                
                printContainer.innerHTML = htmlContent;
                document.body.appendChild(printContainer);
                printContainers.push(printContainer);
            });
            
            // Inicializar jsPDF
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'letter');
            
            // Capturar cada página secuencialmente
            try {
                for (var i = 0; i < printContainers.length; i++) {
                    loadingMsg.textContent = 'Generando PDF... Página ' + (i + 1) + ' de ' + printContainers.length;
                    
                    // Esperar un momento para renderizado
                    await new Promise(resolve => setTimeout(resolve, 100));
                    
                    // Capturar esta página
                    var canvas = await html2canvas(printContainers[i], {
                        scale: 2,
                        useCORS: true,
                        logging: false,
                        backgroundColor: '#ffffff',
                        windowWidth: 900
                    });
                    
                    // Agregar nueva página si no es la primera
                    if (i > 0) {
                        pdf.addPage();
                    }
                    
                    // Calcular dimensiones
                    const imgWidth = 210;
                    const pageHeight = 279;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    // Ajustar si excede altura de página
                    if (imgHeight > pageHeight) {
                        const scaleFactor = pageHeight / imgHeight;
                        const scaledWidth = imgWidth * scaleFactor;
                        const scaledHeight = pageHeight;
                        const xOffset = (imgWidth - scaledWidth) / 2;
                        
                        pdf.addImage(canvas.toDataURL('image/jpeg', 0.95), 'JPEG', xOffset, 0, scaledWidth, scaledHeight);
                    } else {
                        pdf.addImage(canvas.toDataURL('image/jpeg', 0.95), 'JPEG', 0, 0, imgWidth, imgHeight);
                    }
                }
                
                // Limpiar contenedores
                printContainers.forEach(function(container) {
                    document.body.removeChild(container);
                });
                document.body.removeChild(loadingMsg);
                
                // Guardar PDF
                pdf.save('RegistroVM_<?php echo htmlspecialchars($paciente['nombre_completo'] ?? 'Paciente'); ?>_' + new Date().getTime() + '.pdf');
                
            } catch (error) {
                // Limpiar en caso de error
                printContainers.forEach(function(container) {
                    if (document.body.contains(container)) {
                        document.body.removeChild(container);
                    }
                });
                if (document.body.contains(loadingMsg)) {
                    document.body.removeChild(loadingMsg);
                }
                console.error('Error:', error);
                alert('Error al generar el PDF. Por favor intente nuevamente.');
            }
        }
    </script>
</body>
</html>