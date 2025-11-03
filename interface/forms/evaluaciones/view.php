<?php
/**
 * Formulario de Evaluaciones - view.php FINAL
 * Ruta: interface/forms/evaluaciones/view.php
 * Con Logo IPS centralizado en /public/images/
 * MODIFICADO: Muestra solo el registro específico cuando viene id
 */

include_once("../../globals.php");

$pid = $_GET['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_GET['encounter'] ?? $_SESSION['encounter'] ?? null;
$id = $_GET['id'] ?? null; // ← NUEVO: Capturar ID del registro específico

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

// ← MODIFICADO: Consultar solo el registro específico si viene ID
if ($id) {
    // Mostrar solo el registro con el ID específico
    $sql = "SELECT * FROM form_evaluaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $result = sqlStatement($sql, array($id, $pid, $encounter));
} else {
    // Mostrar todos los registros del encounter (comportamiento original)
    $sql = "SELECT * FROM form_evaluaciones WHERE pid = ? AND encounter = ? ORDER BY date DESC";
    $result = sqlStatement($sql, array($pid, $encounter));
}
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
        
        .btn-nuevo {
            background-color: #467ac2;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        .btn-nuevo:hover {
            background-color: #3a6bb0;
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
        
        .evaluacion-detalle {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        
        .evaluacion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
        }
        
        .evaluacion-nombre {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .valor-badge {
            padding: 6px 14px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            background-color: #007bff;
            color: white;
        }
        
        .evaluacion-obs {
            padding: 18px 20px;
            background-color: #f8f9fa;
        }
        .evaluacion-obs.con-contenido {
            background: linear-gradient(135deg, #e7f3ff 0%, #cfe2ff 100%);
            border-top: 3px solid #0d6efd;
        }
        .evaluacion-obs h5 {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .evaluacion-obs p {
            font-size: 14px;
            color: #212529;
            line-height: 1.7;
            margin: 0;
            white-space: pre-wrap;
        }
        
        .glasgow-section {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            padding: 20px 25px;
            border-radius: 12px;
            margin-top: 25px;
            border-left: 5px solid #ffc107;
        }
        .glasgow-section h4 {
            color: #856404;
            font-size: 16px;
            margin-bottom: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .glasgow-score {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border-left: 5px solid #28a745;
            text-align: center;
        }
        .glasgow-score h3 {
            color: #155724;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .glasgow-score p {
            color: #155724;
            font-size: 16px;
            font-weight: 600;
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
        <h2>🧠 <?php echo $id ? 'DETALLE DE EVALUACIONES' : 'LISTA DE EVALUACIONES'; ?></h2>
        
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
            echo "<h3>No hay evaluaciones registradas</h3>";
            echo "</div>";
        }
        
        while ($row = sqlFetchArray($result)) {
            // Determinar nivel de Glasgow
            $glasgow = $row['glasgow_total'] ?? 0;
            if ($glasgow >= 13) {
                $glasgow_clase = "success";
                $glasgow_nivel = "Leve";
                $glasgow_color = "#28a745";
            } elseif ($glasgow >= 9) {
                $glasgow_clase = "warning";
                $glasgow_nivel = "Moderado";
                $glasgow_color = "#ffc107";
            } else {
                $glasgow_clase = "danger";
                $glasgow_nivel = "Severo";
                $glasgow_color = "#dc3545";
            }
            ?>
            
            <div class="registro-card" id="registro-<?php echo $row['id']; ?>">
                <div class="registro-header">
                    <div class="registro-fecha">
                        <span class="fecha-principal">📅 <?php echo date('d/m/Y', strtotime($row['date'])); ?></span>
                        <span class="hora-principal">🕐 <?php echo date('H:i', strtotime($row['date'])); ?></span>
                    </div>
                    <div class="registro-acciones">
                        <a href="<?php echo $GLOBALS['webroot']; ?>/interface/forms/evaluaciones/new.php?pid=<?php echo $pid; ?>&encounter=<?php echo $encounter; ?>&id=<?php echo $row['id']; ?>" class="btn-accion btn-editar">
                            ✏️ EDITAR
                        </a>
                        <button onclick="imprimirRegistro(<?php echo $row['id']; ?>)" class="btn-accion btn-imprimir">
                            🖨️ IMPRIMIR
                        </button>
                    </div>
                </div>
                
                <div class="registro-info">
                    <div class="info-box-small">
                        <strong>⏰ Hora de Evaluación:</strong>
                        <span><?php echo htmlspecialchars($row['hora_evaluacion'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-box-small">
                        <strong>👨‍⚕️ Usuario:</strong>
                        <span><?php echo htmlspecialchars($row['user'] ?? 'N/A'); ?></span>
                    </div>
                </div>

                <!-- GLASGOW SCORE DESTACADO -->
                <div class="glasgow-score">
                    <h3 style="color: <?php echo $glasgow_color; ?>">🧠 GLASGOW: <?php echo $glasgow; ?>/15</h3>
                    <p style="color: <?php echo $glasgow_color; ?>">LESIÓN <?php echo strtoupper($glasgow_nivel); ?></p>
                </div>
                
                <div class="seccion-titulo">🧠 EVALUACIONES BÁSICAS</div>
                
                <!-- Conciencia -->
                <div class="evaluacion-detalle">
                    <div class="evaluacion-header">
                        <div class="evaluacion-nombre">
                            <span>🧠 CONCIENCIA</span>
                        </div>
                        <span class="valor-badge">
                            <?php echo htmlspecialchars($row['conciencia'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <div class="evaluacion-obs <?php echo !empty($row['obs_conciencia']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_conciencia']) ? nl2br(htmlspecialchars($row['obs_conciencia'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Tono -->
                <div class="evaluacion-detalle">
                    <div class="evaluacion-header">
                        <div class="evaluacion-nombre">
                            <span>💪 TONO</span>
                        </div>
                        <span class="valor-badge">
                            <?php echo htmlspecialchars($row['tono'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <div class="evaluacion-obs <?php echo !empty($row['obs_tono']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_tono']) ? nl2br(htmlspecialchars($row['obs_tono'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Pupilas -->
                <div class="evaluacion-detalle">
                    <div class="evaluacion-header">
                        <div class="evaluacion-nombre">
                            <span>👁️ PUPILAS</span>
                        </div>
                        <span class="valor-badge">
                            <?php echo htmlspecialchars($row['pupilas'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <div class="evaluacion-obs <?php echo !empty($row['obs_pupilas']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_pupilas']) ? nl2br(htmlspecialchars($row['obs_pupilas'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>
                
                <!-- Mucosas -->
                <div class="evaluacion-detalle">
                    <div class="evaluacion-header">
                        <div class="evaluacion-nombre">
                            <span>👄 MUCOSAS</span>
                        </div>
                        <span class="valor-badge">
                            <?php echo htmlspecialchars($row['mucosas'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <div class="evaluacion-obs <?php echo !empty($row['obs_mucosas']) ? 'con-contenido' : ''; ?>">
                        <h5>📝 OBSERVACIONES</h5>
                        <p><?php echo !empty($row['obs_mucosas']) ? nl2br(htmlspecialchars($row['obs_mucosas'])) : 'Sin observaciones registradas'; ?></p>
                    </div>
                </div>

                <!-- SECCIÓN GLASGOW -->
                <div class="glasgow-section">
                    <h4>🧠 ESCALA DE GLASGOW DETALLADA</h4>
                    
                    <!-- Ojos -->
                    <div class="evaluacion-detalle">
                        <div class="evaluacion-header">
                            <div class="evaluacion-nombre">
                                <span>👁️ OJOS ABIERTOS</span>
                            </div>
                            <span class="valor-badge">
                                <?php echo htmlspecialchars($row['glasgow_ojos'] ?? 'N/A'); ?>
                            </span>
                        </div>
                        <div class="evaluacion-obs <?php echo !empty($row['obs_glasgow_ojos']) ? 'con-contenido' : ''; ?>">
                            <h5>📝 OBSERVACIONES</h5>
                            <p><?php echo !empty($row['obs_glasgow_ojos']) ? nl2br(htmlspecialchars($row['obs_glasgow_ojos'])) : 'Sin observaciones registradas'; ?></p>
                        </div>
                    </div>
                    
                    <!-- Motora -->
                    <div class="evaluacion-detalle">
                        <div class="evaluacion-header">
                            <div class="evaluacion-nombre">
                                <span>🤝 RESPUESTA MOTORA</span>
                            </div>
                            <span class="valor-badge">
                                <?php echo htmlspecialchars($row['glasgow_motora'] ?? 'N/A'); ?>
                            </span>
                        </div>
                        <div class="evaluacion-obs <?php echo !empty($row['obs_glasgow_motora']) ? 'con-contenido' : ''; ?>">
                            <h5>📝 OBSERVACIONES</h5>
                            <p><?php echo !empty($row['obs_glasgow_motora']) ? nl2br(htmlspecialchars($row['obs_glasgow_motora'])) : 'Sin observaciones registradas'; ?></p>
                        </div>
                    </div>
                    
                    <!-- Verbal -->
                    <div class="evaluacion-detalle">
                        <div class="evaluacion-header">
                            <div class="evaluacion-nombre">
                                <span>🗣️ RESPUESTA VERBAL</span>
                            </div>
                            <span class="valor-badge">
                                <?php echo htmlspecialchars($row['glasgow_verbal'] ?? 'N/A'); ?>
                            </span>
                        </div>
                        <div class="evaluacion-obs <?php echo !empty($row['obs_glasgow_verbal']) ? 'con-contenido' : ''; ?>">
                            <h5>📝 OBSERVACIONES</h5>
                            <p><?php echo !empty($row['obs_glasgow_verbal']) ? nl2br(htmlspecialchars($row['obs_glasgow_verbal'])) : 'Sin observaciones registradas'; ?></p>
                        </div>
                    </div>
                </div>
                
            </div>
            
        <?php } ?>
    </div>
    
    <!-- Librerías para generar PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <script>
        function imprimirRegistro(id) {
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
            
            // Crear contenedor temporal con margen izquierdo
            var printContainer = document.createElement('div');
            printContainer.style.position = 'absolute';
            printContainer.style.left = '-9999px';
            printContainer.style.width = '750px';
            printContainer.style.fontFamily = 'Arial, sans-serif';
            printContainer.style.backgroundColor = 'white';
            printContainer.style.padding = '15px 15px 15px 25px'; // Margen izquierdo 25px
            
            // Construir HTML estilo JasperReports - PROFESIONAL
            var htmlContent = `
                <div style="padding: 0 10px;">
                    <div style="background: white; padding: 20px 15px; margin-bottom: 0; border-bottom: 1px solid #000; text-align: center;">
                        <img src="<?php echo $GLOBALS["web_root"]; ?>/public/images/logo_ips.png" 
                             style="max-width: 100%; height: auto; max-height: 100px; object-fit: contain; display: inline-block;"
                             onerror="this.style.display='none'">
                    </div>
                
                    <div style="text-align: center; font-size: 16px; font-weight: bold; color: #000; margin: 12px 0 0 0; padding: 10px 0; background: white; letter-spacing: 2.5px; text-transform: uppercase;">
                        REGISTRO DE EVALUACIONES
                    </div>
                    
                    <div style="height: 2px; background: #000; margin: 0 0 18px 0;"></div>
                </div>
                
                <div style="background: white; padding: 0; margin: 15px 0; border: 1px solid #000;">
                    <div style="font-weight: bold; font-size: 11px; margin: 0; color: #000; border-bottom: 1px solid #000; padding: 8px 10px; background: #f5f5f5; text-transform: uppercase; letter-spacing: 0.5px;">
                        INFORMACIÓN DEL PACIENTE
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                        <tr>
                            <td style="padding: 8px 10px; background: white; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 33%; vertical-align: top;">
                                <div style="color: #666; font-size: 9px; margin-bottom: 3px;">PACIENTE:</div>
                                <div style="color: #000; font-weight: 600; font-size: 10px;"><?php echo htmlspecialchars($paciente['nombre_completo'] ?? 'N/A'); ?></div>
                            </td>
                            <td style="padding: 8px 10px; background: white; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 33%; vertical-align: top;">
                                <div style="color: #666; font-size: 9px; margin-bottom: 3px;">CI/RG:</div>
                                <div style="color: #000; font-weight: 600; font-size: 10px;"><?php echo htmlspecialchars($paciente['pubpid'] ?? 'N/A'); ?></div>
                            </td>
                            <td style="padding: 8px 10px; background: white; border-bottom: 1px solid #000; width: 33%; vertical-align: top;">
                                <div style="color: #666; font-size: 9px; margin-bottom: 3px;">EDAD:</div>
                                <div style="color: #000; font-weight: 600; font-size: 10px;"><?php echo !empty($edad) ? $edad : 'N/A'; ?></div>
                            </td>
                        </tr>
                    </table>
                </div>
            `;
            
            // PUNTAJE GLASGOW DESTACADO
            var glasgowData = registro.querySelector('.glasgow-score');
            if (glasgowData) {
                var glasgowText = glasgowData.textContent.trim();
                htmlContent += `
                    <div style="background: white; padding: 15px; margin: 15px 0; border: 2px solid #000; text-align: center;">
                        <div style="font-weight: bold; font-size: 14px; color: #000; margin-bottom: 5px;">ESCALA DE GLASGOW</div>
                        <div style="font-size: 18px; font-weight: bold; color: #000;">${glasgowText}</div>
                    </div>
                `;
            }
            
            // Agregar título de sección estilo JasperReports
            htmlContent += `
                <div style="font-weight: bold; font-size: 11px; color: #000; margin: 15px 0 10px 0; padding: 8px 10px; border: 1px solid #000; border-left: 4px solid #000; background: #f5f5f5; text-transform: uppercase; letter-spacing: 0.5px;">
                    EVALUACIONES DETALLADAS
                </div>
            `;
            
            // Agregar evaluaciones básicas - ESTILO JASPERREPORTS
            var evaluacionesBasicas = registro.querySelectorAll('.evaluacion-detalle');
            // Filtrar solo las evaluaciones básicas (no las de Glasgow)
            var evaluacionesBasicasFiltradas = Array.from(evaluacionesBasicas).filter(function(eval) {
                var nombre = eval.querySelector('.evaluacion-nombre').textContent.trim();
                return !nombre.includes('OJOS ABIERTOS') && 
                       !nombre.includes('RESPUESTA MOTORA') && 
                       !nombre.includes('RESPUESTA VERBAL');
            });
            
            evaluacionesBasicasFiltradas.forEach(function(eval) {
                var nombre = eval.querySelector('.evaluacion-nombre').textContent.trim();
                var badge = eval.querySelector('.valor-badge');
                var valor = badge ? badge.textContent.trim() : 'N/A';
                var obsText = eval.querySelector('.evaluacion-obs p').textContent.trim();
                
                htmlContent += `
                    <div style="margin-bottom: 8px; border: 1px solid #000; overflow: hidden; background: white;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: white; border-bottom: 1px solid #000;">
                            <span style="font-weight: 600; font-size: 10px; color: #000;">${nombre}</span>
                            <span style="border: 1px solid #000; padding: 4px 10px; font-size: 9px; font-weight: bold; color: #000; min-width: 60px; text-align: center;">
                                ${valor}
                            </span>
                        </div>
                        <div style="padding: 8px 10px; background: white; font-size: 9px;">
                            <div style="color: #666; font-size: 8px; font-weight: 600; margin-bottom: 3px;">OBSERVACIONES:</div>
                            <div style="color: ${obsText === 'Sin observaciones registradas' ? '#999' : '#000'}; line-height: 1.4; ${obsText === 'Sin observaciones registradas' ? 'font-style: italic;' : ''}">${obsText}</div>
                        </div>
                    </div>
                `;
            });

            // ========================================
            // NUEVA SECCIÓN: ESCALA DE GLASGOW DETALLADA
            // ========================================
            htmlContent += `
                <div style="font-weight: bold; font-size: 11px; color: #000; margin: 20px 0 10px 0; padding: 8px 10px; border: 1px solid #000; border-left: 4px solid #000; background: #f5f5f5; text-transform: uppercase; letter-spacing: 0.5px;">
                    ESCALA DE GLASGOW DETALLADA
                </div>
            `;

            // Obtener los datos específicos de Glasgow del registro actual
            var glasgowEvaluaciones = Array.from(evaluacionesBasicas).filter(function(eval) {
                var nombre = eval.querySelector('.evaluacion-nombre').textContent.trim();
                return nombre.includes('OJOS ABIERTOS') || 
                       nombre.includes('RESPUESTA MOTORA') || 
                       nombre.includes('RESPUESTA VERBAL');
            });

            // Función para obtener puntaje de Glasgow
            function obtenerPuntajeGlasgow(valor, tipo) {
                const puntajes = {
                    'ojos': {
                        'ESPONTANEAMENTE': '4',
                        'A ESTIMULOS AUDITIVOS': '3',
                        'AL DOLOR': '2',
                        'SIN RESPUESTA': '1'
                    },
                    'motora': {
                        'OBEDECE ORDENES': '6',
                        'LOCALIZA DOLOR': '5',
                        'FLEXION DE DEFENSA': '4',
                        'FLEXION ANORMAL': '3',
                        'EXTENSION ANORMAL': '2',
                        'NINGUNA': '1'
                    },
                    'verbal': {
                        'ORIENTADO Y CONVERSA': '5',
                        'DESORIENTADO Y CONVERSA': '4',
                        'LENGUAJE INADECUADO': '3',
                        'SONIDOS INCOMPRENSIBLES': '2',
                        'NINGUNA': '1'
                    }
                };
                return puntajes[tipo] && puntajes[tipo][valor] ? puntajes[tipo][valor] : '0';
            }

            glasgowEvaluaciones.forEach(function(eval) {
                var nombre = eval.querySelector('.evaluacion-nombre').textContent.trim();
                var badge = eval.querySelector('.valor-badge');
                var valor = badge ? badge.textContent.trim() : 'N/A';
                var obsText = eval.querySelector('.evaluacion-obs p').textContent.trim();
                
                // Determinar el tipo y puntaje
                var tipo = '';
                var puntaje = '0';
                if (nombre.includes('OJOS')) {
                    tipo = 'ojos';
                    puntaje = obtenerPuntajeGlasgow(valor, 'ojos');
                } else if (nombre.includes('MOTORA')) {
                    tipo = 'motora';
                    puntaje = obtenerPuntajeGlasgow(valor, 'motora');
                } else if (nombre.includes('VERBAL')) {
                    tipo = 'verbal';
                    puntaje = obtenerPuntajeGlasgow(valor, 'verbal');
                }
                
                htmlContent += `
                    <div style="margin-bottom: 8px; border: 1px solid #000; overflow: hidden; background: white;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: white; border-bottom: 1px solid #000;">
                            <span style="font-weight: 600; font-size: 10px; color: #000;">${nombre}</span>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <span style="border: 1px solid #000; padding: 4px 8px; font-size: 8px; font-weight: bold; color: #000; background: #f5f5f5;">
                                    PTOS: ${puntaje}
                                </span>
                                <span style="border: 1px solid #000; padding: 4px 10px; font-size: 9px; font-weight: bold; color: #000; min-width: 60px; text-align: center; background: #f5f5f5;">
                                    ${valor}
                                </span>
                            </div>
                        </div>
                        <div style="padding: 8px 10px; background: white; font-size: 9px;">
                            <div style="color: #666; font-size: 8px; font-weight: 600; margin-bottom: 3px;">OBSERVACIONES:</div>
                            <div style="color: ${obsText === 'Sin observaciones registradas' ? '#999' : '#000'}; line-height: 1.4; ${obsText === 'Sin observaciones registradas' ? 'font-style: italic;' : ''}">${obsText}</div>
                        </div>
                    </div>
                `;
            });
            
            // PIE DE PÁGINA - ESPACIO PARA FIRMA
            htmlContent += `
                <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #000;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 50%; text-align: center; vertical-align: bottom; padding: 0 20px;">
                                <div style="border-top: 2px solid #000; margin: 50px 20px 5px 20px;"></div>
                                <div style="font-size: 9px; font-weight: bold; color: #000; margin-top: 5px;">FIRMA DEL RESPONSABLE</div>
                                <div style="font-size: 8px; color: #666; margin-top: 3px;">Nombre y Apellido</div>
                            </td>
                            <td style="width: 50%; text-align: center; vertical-align: bottom; padding: 0 20px;">
                                <div style="border-top: 2px solid #000; margin: 50px 20px 5px 20px;"></div>
                                <div style="font-size: 9px; font-weight: bold; color: #000; margin-top: 5px;">ACLARACIÓN</div>
                                <div style="font-size: 8px; color: #666; margin-top: 3px;">C.I. Nº</div>
                            </td>
                        </tr>
                    </table>
                    <div style="text-align: center; margin-top: 15px; font-size: 8px; color: #666;">
                        Fecha: _____/_____/________
                    </div>
                </div>
            `;
            
            htmlContent += '</div>'; // Cerrar div del padding interno
            
            printContainer.innerHTML = htmlContent;
            document.body.appendChild(printContainer);
            
            // Usar html2canvas + jsPDF
            html2canvas(printContainer, {
                scale: 2,
                useCORS: true,
                logging: false,
                backgroundColor: '#ffffff',
                windowWidth: 750
            }).then(function(canvas) {
                document.body.removeChild(printContainer);
                document.body.removeChild(loadingMsg);
                
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'letter');
                
                const imgWidth = 210;
                const pageHeight = 279;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                // Si el contenido es más alto que una página, escalarlo
                if (imgHeight > pageHeight) {
                    const scaleFactor = pageHeight / imgHeight;
                    const scaledWidth = imgWidth * scaleFactor;
                    const scaledHeight = pageHeight;
                    const xOffset = (imgWidth - scaledWidth) / 2;
                    
                    pdf.addImage(canvas.toDataURL('image/jpeg', 0.95), 'JPEG', xOffset, 0, scaledWidth, scaledHeight);
                } else {
                    pdf.addImage(canvas.toDataURL('image/jpeg', 0.95), 'JPEG', 0, 0, imgWidth, imgHeight);
                }
                
                pdf.save('Evaluaciones_<?php echo htmlspecialchars($paciente['nombre_completo'] ?? 'Paciente'); ?>_' + new Date().getTime() + '.pdf');
            }).catch(function(error) {
                document.body.removeChild(printContainer);
                document.body.removeChild(loadingMsg);
                console.error('Error:', error);
                alert('Error al generar el PDF. Por favor intente nuevamente.');
            });
        }
    </script>
</body>
</html>