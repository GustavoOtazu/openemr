<?php
/**
 * Exportar Reporte de Curaciones a PDF con TCPDF
 * Compatible con OpenEMR 5.0.2
 * Ruta: interface/forms/curaciones/export_pdf_tcpdf.php
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

// Obtener datos del usuario
$sql_user = "SELECT fname, lname FROM users WHERE username = ?";
$res_user = sqlStatement($sql_user, array($curacion['user']));
$usuario = sqlFetchArray($res_user);

// Calcular edad
$edad = '';
if ($paciente && $paciente['dob']) {
    $birthdate = new DateTime($paciente['dob']);
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

// Incluir TCPDF
require_once($GLOBALS['webroot'] . '/../vendor/tcpdf/tcpdf.php');

// Extender clase TCPDF
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, 'REPORTE DE CURACIONES', 0, false, 'C', 0, '', 0, false);
        $this->Ln(15);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false);
    }
}

// Crear PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_PAGE_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 30, 15);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// INFORMACIÓN DEL PACIENTE
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(0, 123, 255);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(0, 8, 'INFORMACION DEL PACIENTE', 0, 1, 'L', TRUE);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240);

$pdf->Cell(50, 6, 'Nombre:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, ($paciente ? $paciente['fname'] . ' ' . $paciente['lname'] : 'N/A'), 0, 1, 'L', FALSE);

$pdf->Cell(50, 6, 'Identificacion (CI):', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, ($paciente ? $paciente['pubpid'] : 'N/A'), 0, 1, 'L', FALSE);

$pdf->Cell(50, 6, 'Edad:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, ($edad ? $edad . ' años' : 'N/A'), 0, 1, 'L', FALSE);

$pdf->Cell(50, 6, 'PID:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, $pid, 0, 1, 'L', FALSE);

$pdf->Cell(50, 6, 'Encuentro:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, $curacion['encounter'], 0, 1, 'L', FALSE);

$pdf->Ln(5);

// INFORMACIÓN DE LA CURACIÓN
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(0, 123, 255);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(0, 8, 'INFORMACION DE LA CURACION', 0, 1, 'L', TRUE);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240);

$pdf->Cell(50, 6, 'Fecha de Registro:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, date('d/m/Y', strtotime($curacion['date'])), 0, 1, 'L', FALSE);

$pdf->Cell(50, 6, 'Hora de Registro:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, date('H:i:s', strtotime($curacion['date'])), 0, 1, 'L', FALSE);

$pdf->Cell(50, 6, 'Hora de Operacion:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, ($curacion['hora_operacion'] ? date('H:i', strtotime($curacion['hora_operacion'])) : 'No registrada'), 0, 1, 'L', FALSE);

$pdf->Cell(50, 6, 'Profesional:', 0, 0, 'L', TRUE);
$pdf->Cell(0, 6, ($usuario ? $usuario['fname'] . ' ' . $usuario['lname'] : $curacion['user']), 0, 1, 'L', FALSE);

$pdf->Ln(5);

// TABLA DE CURACIONES
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(0, 123, 255);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(60, 7, 'Tipo de Curacion', 1, 0, 'C', TRUE);
$pdf->Cell(20, 7, 'Estado', 1, 0, 'C', TRUE);
$pdf->Cell(0, 7, 'Observacion', 1, 1, 'C', TRUE);

// Datos de curaciones
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(0, 0, 0);

$totales_si = 0;
$totales_no = 0;

foreach ($tipos_curaciones as $campo => $titulo) {
    $valor = $curacion[$campo] ?? 0;
    $obs = $curacion['obs_' . $campo] ?? '';
    $estado = $valor == 1 ? 'SI' : 'NO';
    
    if ($valor == 1) {
        $totales_si++;
        $pdf->SetFillColor(212, 237, 218);
    } else {
        $totales_no++;
        $pdf->SetFillColor(248, 215, 218);
    }
    
    $pdf->Cell(60, 6, substr($titulo, 0, 28), 1, 0, 'L', TRUE);
    $pdf->Cell(20, 6, $estado, 1, 0, 'C', TRUE);
    $pdf->MultiCell(0, 6, substr($obs, 0, 40), 1, 'L', TRUE);
}

// RESUMEN
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(224, 239, 255);
$pdf->Ln(5);
$pdf->Cell(0, 6, 'RESUMEN: Curaciones Realizadas (SI): ' . $totales_si . ' | No Realizadas (NO): ' . $totales_no, 1, 1, 'L', TRUE);

// PIE DE PÁGINA
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'Generado: ' . date('d/m/Y H:i:s') . ' | ID: ' . $id, 0, 1, 'C', FALSE);

// Salida del PDF
$pdf->Output('Reporte_Curaciones_' . date('d-m-Y_H-i-s') . '.pdf', 'D');

exit;
?>