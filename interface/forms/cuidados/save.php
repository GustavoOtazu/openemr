<?php
/**
 * Formulario de Cuidados - save.php
 * Ruta: interface/forms/cuidados/save.php
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");

// Validar que vengan los datos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido");
}

// Obtener datos del formulario
$pid = (int)($_POST['pid'] ?? 0);
$encounter = (int)($_POST['encounter'] ?? 0);

if (!$pid || !$encounter) {
    die("Error: Faltan datos requeridos (PID o Encounter)");
}

// Obtener usuario actual
$user = $_SESSION['authUser'] ?? 'admin';
$groupname = $_SESSION['authProvider'] ?? 'Default';
$authorized = $_SESSION['userauthorized'] ?? 1;

// Capturar POSICION DEL PACIENTE (radio button - una sola opción)
$posicion_paciente = $_POST['posicion_paciente'] ?? '';
$obs_posicion = $_POST['obs_posicion'] ?? '';

// Capturar otros items (Sí/No como radio buttons)
$enjuague_bucal = isset($_POST['enjuague_bucal']) ? (int)$_POST['enjuague_bucal'] : 0;
$obs_enjuague = $_POST['obs_enjuague'] ?? '';

$higiene_manos = isset($_POST['higiene_manos']) ? (int)$_POST['higiene_manos'] : 0;
$obs_higiene = $_POST['obs_higiene'] ?? '';

$aspirado_secreciones = isset($_POST['aspirado_secreciones']) ? (int)$_POST['aspirado_secreciones'] : 0;
$obs_aspirado = $_POST['obs_aspirado'] ?? '';

$suspension_sedacion = isset($_POST['suspension_sedacion']) ? (int)$_POST['suspension_sedacion'] : 0;
$obs_suspension = $_POST['obs_suspension'] ?? '';

$medicion_cuff = isset($_POST['medicion_cuff']) ? (int)$_POST['medicion_cuff'] : 0;
$obs_cuff = $_POST['obs_cuff'] ?? '';

$hora_cuidado = $_POST['hora_cuidado'] ?? null;

// Preparar SQL INSERT
$sql = "INSERT INTO form_cuidados (
    date,
    pid,
    encounter,
    user,
    groupname,
    authorized,
    activity,
    posicion_paciente,
    obs_posicion,
    enjuague_bucal,
    obs_enjuague,
    higiene_manos,
    obs_higiene,
    aspirado_secreciones,
    obs_aspirado,
    suspension_sedacion,
    obs_suspension,
    medicion_cuff,
    obs_cuff,
    hora_cuidado
) VALUES (
    NOW(),
    ?,
    ?,
    ?,
    ?,
    ?,
    1,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?
)";

// Array de parámetros (19 valores)
$params = array(
    $pid,                      // 1
    $encounter,                // 2
    $user,                     // 3
    $groupname,                // 4
    $authorized,               // 5
    $posicion_paciente,        // 6
    $obs_posicion,             // 7
    $enjuague_bucal,           // 8
    $obs_enjuague,             // 9
    $higiene_manos,            // 10
    $obs_higiene,              // 11
    $aspirado_secreciones,     // 12
    $obs_aspirado,             // 13
    $suspension_sedacion,      // 14
    $obs_suspension,           // 15
    $medicion_cuff,            // 16
    $obs_cuff,                 // 17
    $hora_cuidado              // 18
);

// Ejecutar INSERT
$newid = sqlInsert($sql, $params);

if ($newid) {
    // Agregar el formulario al encuentro
    addForm($encounter, 'Cuidados', $newid, 'cuidados', $pid, $authorized);
    
    // Marcar sesión para mostrar mensaje de éxito
    $_SESSION['cuidado_guardado'] = true;
    
    // Redirigir a lista de internados
    header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
    exit;
} else {
    die("Error al guardar los datos");
}
?>