<?php
/**
 * Formulario de Curaciones - save.php
 * Compatible con OpenEMR 5.0.2
 * Ruta: interface/forms/curaciones/save.php
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");

// Obtener el modo
$mode = $_GET['mode'] ?? 'new';

// Obtener PID y Encounter
$pid = $_POST['pid'] ?? $_SESSION['pid'] ?? null;
$encounter = $_POST['encounter'] ?? $_SESSION['encounter'] ?? null;

// Validar datos
if (!$pid || !$encounter) {
    die("Error: No se pudo obtener el PID o Encounter del paciente.");
}

// Obtener datos del formulario - IMPORTANTE: Los radio buttons siempre envían un valor
$herida_operatoria = (int)($_POST['herida_operatoria'] ?? 0);
$traqueostomia = (int)($_POST['traqueostomia'] ?? 0);
$ostomias = (int)($_POST['ostomias'] ?? 0);
$escaras = (int)($_POST['escaras'] ?? 0);
$via_venosa_central = (int)($_POST['via_venosa_central'] ?? 0);
$via_venosa = (int)($_POST['via_venosa'] ?? 0);
$hora_operacion = $_POST['hora_operacion'] ?? null;

// Obtener observaciones
$obs_herida_operatoria = $_POST['obs_herida_operatoria'] ?? '';
$obs_traqueostomia = $_POST['obs_traqueostomia'] ?? '';
$obs_ostomias = $_POST['obs_ostomias'] ?? '';
$obs_escaras = $_POST['obs_escaras'] ?? '';
$obs_via_venosa_central = $_POST['obs_via_venosa_central'] ?? '';
$obs_via_venosa = $_POST['obs_via_venosa'] ?? '';

// Usuario actual
$user = $_SESSION['authUser'] ?? 'admin';
$groupname = $_SESSION['authProvider'] ?? '';
$authorized = $_SESSION['userauthorized'] ?? 0;

if ($mode == "new") {
    // Insertar nuevo registro
    $sql = "INSERT INTO form_curaciones (
        date,
        pid,
        encounter,
        user,
        groupname,
        authorized,
        activity,
        herida_operatoria,
        traqueostomia,
        ostomias,
        escaras,
        via_venosa_central,
        via_venosa,
        hora_operacion,
        obs_herida_operatoria,
        obs_traqueostomia,
        obs_ostomias,
        obs_escaras,
        obs_via_venosa_central,
        obs_via_venosa
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
    
    // Array con EXACTAMENTE 19 valores en el mismo orden que los placeholders
    $params = array(
        $pid,                       // 1
        $encounter,                 // 2
        $user,                      // 3
        $groupname,                 // 4
        $authorized,                // 5
        $herida_operatoria,         // 6
        $traqueostomia,             // 7
        $ostomias,                  // 8
        $escaras,                   // 9
        $via_venosa_central,        // 10
        $via_venosa,                // 11
        $hora_operacion,            // 12
        $obs_herida_operatoria,     // 13
        $obs_traqueostomia,         // 14
        $obs_ostomias,              // 15
        $obs_escaras,               // 16
        $obs_via_venosa_central,    // 17
        $obs_via_venosa             // 18
    );
    
    sqlInsert($sql, $params);
    
    // Obtener el ID del último registro insertado
    $result = sqlQuery("SELECT MAX(id) as newid FROM form_curaciones WHERE pid = ? ORDER BY id DESC LIMIT 1", array($pid));
    $newid = $result['newid'] ?? 0;
    
    // Registrar el formulario en el encuentro
    addForm($encounter, "Curaciones", $newid, "curaciones", $pid, $authorized);
    
} elseif ($mode == "update") {
    // Actualizar registro existente
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        die("Error: No se especificó el ID del registro a actualizar.");
    }
    
    $sql = "UPDATE form_curaciones SET
        herida_operatoria = ?,
        traqueostomia = ?,
        ostomias = ?,
        escaras = ?,
        via_venosa_central = ?,
        via_venosa = ?,
        hora_operacion = ?,
        obs_herida_operatoria = ?,
        obs_traqueostomia = ?,
        obs_ostomias = ?,
        obs_escaras = ?,
        obs_via_venosa_central = ?,
        obs_via_venosa = ?
        WHERE id = ?";
    
    sqlStatement($sql, array(
        $herida_operatoria,
        $traqueostomia,
        $ostomias,
        $escaras,
        $via_venosa_central,
        $via_venosa,
        $hora_operacion,
        $obs_herida_operatoria,
        $obs_traqueostomia,
        $obs_ostomias,
        $obs_escaras,
        $obs_via_venosa_central,
        $obs_via_venosa,
        $id
    ));
}

// Redirigir a lista_internados.php con parámetro de éxito
$_SESSION['curacion_guardada'] = true;
$redirect_url = $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php";
header("Location: " . $redirect_url);
exit;
?>