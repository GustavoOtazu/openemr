<?php
/**
 * Formulario de Curaciones - report.php
 * Ruta: interface/forms/curaciones/report.php
 */

include_once("../../globals.php");
include_once("$srcdir/api.inc.php");

function curaciones_report($pid, $encounter, $cols, $id) {
    $count = 0;
    
    // Obtener datos del formulario
    $sql = "SELECT * FROM form_curaciones WHERE id = ? AND pid = ?";
    $res = sqlStatement($sql, array($id, $pid));
    
    if ($res) {
        $data = sqlFetchArray($res);
        
        if ($data) {
            // Definir los tipos de curaciones
            $curaciones = [
                'herida_operatoria' => 'HERIDA OPERATORIA',
                'traqueostomia' => 'TRAQUEOSTOMIA',
                'ostomias' => 'OSTOMIAS',
                'escaras' => 'ESCARAS',
                'via_venosa_central' => 'VÍA VENOSA CENTRAL',
                'via_venosa' => 'VÍA VENOSA'
            ];
            
            ?>
            <style>
                .reporte-container {
                    width: 100%;
                    margin: 0;
                    padding: 30px;
                    background-color: #f0f2f5;
                    box-sizing: border-box;
                    overflow-x: auto;
                }
                
                .reporte-content {
                    background-color: white;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    padding: 30px;
                }
                
                .reporte-titulo {
                    color: #1a3a52;
                    border-bottom: 4px solid #007bff;
                    padding-bottom: 20px;
                    font-size: 28px;
                    text-align: center;
                    margin-bottom: 30px;
                    font-weight: bold;
                }
                
                .reporte-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 15px;
                }
                
                .reporte-table td {
                    padding: 15px;
                    border: 1px solid #ddd;
                }
                
                .label-celda {
                    background-color: #e9ecef;
                    font-weight: bold;
                    width: 30%;
                }
                
                .valor-celda {
                    background-color: #ffffff;
                }
                
                .curacion-nombre {
                    background-color: #e3f2fd;
                    font-weight: bold;
                    width: 35%;
                }
                
                .curacion-estado {
                    background-color: #e3f2fd;
                    font-weight: bold;
                    width: 12%;
                    text-align: center;
                }
                
                .curacion-obs {
                    background-color: #e3f2fd;
                    font-style: italic;
                }
                
                .estado-si {
                    background-color: #d4edda;
                    color: #155724;
                    font-weight: bold;
                }
                
                .estado-no {
                    background-color: #f8d7da;
                    color: #721c24;
                    font-weight: bold;
                }
            </style>
            
            <div class="reporte-container">
                <div class="reporte-content">
                    <div class="reporte-titulo">REPORTE DE CURACIONES</div>
                    
                    <table class="reporte-table">
                        <tr>
                            <td class="label-celda">Fecha y Hora de Registro:</td>
                            <td class="valor-celda"><?php echo date('d/m/Y H:i:s', strtotime($data['date'])); ?></td>
                        </tr>
                        
                        <tr>
                            <td class="label-celda">Hora de Operación:</td>
                            <td class="valor-celda"><?php echo $data['hora_operacion'] ? date('H:i', strtotime($data['hora_operacion'])) : 'No registrada'; ?></td>
                        </tr>
                    </table>
                    
                    <div style="margin-top: 30px; margin-bottom: 20px;">
                        <h4 style="background-color: #007bff; color: white; padding: 15px; font-size: 18px; margin: 0; border-radius: 5px;">
                            DETALLES DE CURACIONES REALIZADAS
                        </h4>
                    </div>
                    
                    <table class="reporte-table">
                        <tr style="background-color: #007bff; color: white;">
                            <td style="padding: 15px; font-weight: bold; width: 35%;">Tipo de Curación</td>
                            <td style="padding: 15px; font-weight: bold; width: 12%; text-align: center;">Estado</td>
                            <td style="padding: 15px; font-weight: bold;">Observación</td>
                        </tr>
                        
                        <?php 
                        // Mostrar todas las curaciones
                        foreach ($curaciones as $campo => $titulo) {
                            $valor = $data[$campo] ?? 0;
                            $observacion = $data['obs_' . $campo] ?? '';
                            $estado_clase = $valor == 1 ? 'estado-si' : 'estado-no';
                            $estado_texto = $valor == 1 ? '✓ SÍ' : '✗ NO';
                            
                            echo '<tr>';
                            echo '<td class="curacion-nombre">' . $titulo . '</td>';
                            echo '<td class="curacion-estado ' . $estado_clase . '">' . $estado_texto . '</td>';
                            echo '<td class="curacion-obs">';
                            if (!empty($observacion)) {
                                echo htmlspecialchars($observacion);
                            } else {
                                echo '-';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
            <?php
        }
    }
}
?>