<?php
/**
 * Exportar Reporte Completo de Curaciones a PDF con mPDF
 * Ruta: interface/forms/curaciones/export_pdf_mpdf.php
 */

require_once("../../globals.php");

$id = $_GET['id'] ?? null;
$pid = $_GET['pid'] ?? null;

if (!$id || !$pid) {
    die("Error: ID o PID no especificado");
}

// Obtener datos del formulario de curaciones
$sql_curaciones = "SELECT * FROM form_curaciones WHERE id = ? AND pid = ?";
$res = sqlStatement($sql_curaciones, array($id, $pid));
$curacion = sqlFetchArray($res);

if (!$curacion) {
    die("Error: Registro de curación no encontrado");
}

// Obtener datos del paciente
$sql_paciente = "SELECT pid, fname, lname, dob, pubpid FROM patient_data WHERE pid = ?";
$res_paciente = sqlStatement($sql_paciente, array($pid));
$paciente = sqlFetchArray($res_paciente);

// Obtener datos del usuario que registró
$sql_user = "SELECT fname, lname FROM users WHERE username = ?";
$res_user = sqlStatement($sql_user, array($curacion['user']));
$usuario = sqlFetchArray($res_user);

// Calcular edad
$fecha_nac = $paciente['dob'] ?? null;
$edad = '';
if ($fecha_nac) {
    $birthdate = new DateTime($fecha_nac);
    $today = new DateTime();
    $edad = $today->diff($birthdate)->y;
}

// Definir tipos de curaciones
$tipos_curaciones = array(
    'herida_operatoria' => 'HERIDA OPERATORIA',
    'traqueostomia' => 'TRAQUEOSTOMIA',
    'ostomias' => 'OSTOMIAS',
    'escaras' => 'ESCARAS',
    'via_venosa_central' => 'VÍA VENOSA CENTRAL',
    'via_venosa' => 'VÍA VENOSA'
);

// Generar HTML para el PDF
$html = '
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        .titulo { font-size: 16px; font-weight: bold; color: #1a3a52; }
        .subtitulo { font-size: 12px; color: #666; margin-top: 5px; }
        .seccion { margin-bottom: 20px; }
        .seccion-titulo { background-color: #007bff; color: white; padding: 8px; font-weight: bold; font-size: 11px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        .label { font-weight: bold; width: 25%; background-color: #f0f0f0; }
        .valor { width: 75%; }
        .curaciones-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .curaciones-table th { background-color: #0056b3; color: white; padding: 8px; text-align: left; font-weight: bold; }
        .curaciones-table td { padding: 8px; border: 1px solid #ddd; }
        .si { background-color: #d4edda; color: #155724; font-weight: bold; text-align: center; }
        .no { background-color: #f8d7da; color: #721c24; font-weight: bold; text-align: center; }
        .obs-celda { font-size: 9px; }
        .resumen { background-color: #e7f3ff; padding: 10px; margin-top: 15px; border-left: 4px solid #007bff; }
        .pie { margin-top: 30px; text-align: center; font-size: 8px; color: #999; }
        .firma-area { margin-top: 40px; display: flex; justify-content: space-between; }
        .firma-linea { width: 40%; text-align: center; }
        .linea { border-top: 1px solid #000; margin-bottom: 5px; }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <div class="header">
        <div class="titulo">REPORTE DETALLADO DE CURACIONES</div>
        <div class="subtitulo">Registro Médico de Procedimientos de Curación</div>
    </div>

    <!-- DATOS DEL PACIENTE -->
    <div class="seccion">
        <div class="seccion-titulo">INFORMACIÓN DEL PACIENTE</div>
        <table class="info-table">
            <tr>
                <td class="label">Nombre Completo:</td>
                <td class="valor">' . ($paciente ? $paciente['fname'] . ' ' . $paciente['lname'] : 'N/A') . '</td>
            </tr>
            <tr>
                <td class="label">Identificación (CI/RG):</td>
                <td class="valor">' . ($paciente ? $paciente['pubpid'] : 'N/A') . '</td>
            </tr>
            <tr>
                <td class="label">Edad:</td>
                <td class="valor">' . ($edad ? $edad . ' años' : 'N/A') . '</td>
            </tr>
            <tr>
                <td class="label">PID (Registro):</td>
                <td class="valor">' . $pid . '</td>
            </tr>
            <tr>
                <td class="label">Encuentro (Internación):</td>
                <td class="valor">' . $curacion['encounter'] . '</td>
            </tr>
        </table>
    </div>

    <!-- INFORMACIÓN DE LA CURACIÓN -->
    <div class="seccion">
        <div class="seccion-titulo">INFORMACIÓN DE LA CURACIÓN</div>
        <table class="info-table">
            <tr>
                <td class="label">Fecha de Registro:</td>
                <td class="valor">' . date('d/m/Y', strtotime($curacion['date'])) . '</td>
            </tr>
            <tr>
                <td class="label">Hora de Registro:</td>
                <td class="valor">' . date('H:i:s', strtotime($curacion['date'])) . '</td>
            </tr>
            <tr>
                <td class="label">Hora de Operación:</td>
                <td class="valor">' . ($curacion['hora_operacion'] ? date('H:i', strtotime($curacion['hora_operacion'])) : 'No registrada') . '</td>
            </tr>
            <tr>
                <td class="label">Profesional a Cargo:</td>
                <td class="valor">' . ($usuario ? $usuario['fname'] . ' ' . $usuario['lname'] : $curacion['user']) . '</td>
            </tr>
            <tr>
                <td class="label">Grupo/Departamento:</td>
                <td class="valor">' . ($curacion['groupname'] ? $curacion['groupname'] : 'N/A') . '</td>
            </tr>
        </table>
    </div>

    <!-- DETALLES DE CURACIONES -->
    <div class="seccion">
        <div class="seccion-titulo">DETALLES DE CURACIONES REALIZADAS</div>
        <table class="curaciones-table">
            <tr>
                <th width="25%">Tipo de Curación</th>
                <th width="10%">Estado</th>
                <th width="65%">Observación</th>
            </tr>';

$totales_si = 0;
$totales_no = 0;
$detalles = array();

foreach ($tipos_curaciones as $campo => $titulo) {
    $valor = $curacion[$campo] ?? 0;
    $obs = $curacion['obs_' . $campo] ?? '';
    $estado = $valor == 1 ? 'SÍ' : 'NO';
    $clase = $valor == 1 ? 'si' : 'no';
    
    if ($valor == 1) {
        $totales_si++;
    } else {
        $totales_no++;
    }
    
    $detalles[] = array(
        'titulo' => $titulo,
        'estado' => $valor,
        'obs' => $obs
    );
    
    $html .= '
            <tr>
                <td>' . $titulo . '</td>
                <td class="' . $clase . '">' . $estado . '</td>
                <td class="obs-celda">' . (empty($obs) ? '-' : $obs) . '</td>
            </tr>';
}

$html .= '
        </table>
    </div>

    <!-- RESUMEN -->
    <div class="resumen">
        <strong>RESUMEN:</strong><br>
        ✓ Curaciones Realizadas (Sí): <strong>' . $totales_si . '</strong><br>
        ✗ Curaciones No Realizadas (No): <strong>' . $totales_no . '</strong><br>
        Total de Tipos de Curación Evaluados: <strong>' . ($totales_si + $totales_no) . '</strong>
    </div>

    <!-- OBSERVACIONES GENERALES -->
    <div class="seccion" style="margin-top: 20px;">
        <div class="seccion-titulo">OBSERVACIONES ADICIONALES</div>
        <div style="padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; min-height: 60px;">
            _________________________________________________________________<br>
            _________________________________________________________________<br>
            _________________________________________________________________
        </div>
    </div>

    <!-- FIRMA -->
    <div class="firma-area">
        <div class="firma-linea">
            <div class="linea"></div>
            <strong style="font-size: 9px;">Profesional que Regist