<?php
/**
 * Formulario de Registro VM - save.php
 * Ruta: interface/forms/registro_vm/save.php
 */

require_once("../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener datos del formulario
$pid = (int)($_POST['pid'] ?? 0);
$encounter = (int)($_POST['encounter'] ?? 0);

// Validación básica
if (!$pid || !$encounter) {
    die("Error: PID o Encounter no válidos");
}

// Obtener datos del usuario
$user = $_SESSION['authUser'] ?? 'desconocido';
$groupname = $_SESSION['authProvider'] ?? 'default';

// Capturar datos del formulario
$modo_ventilacion = $_POST['modo_ventilacion'] ?? '';
$hora_registro = $_POST['hora_registro'] ?? null;

// Items con Sí/No
$presion = (int)($_POST['presion'] ?? 0);
$volumen = (int)($_POST['volumen'] ?? 0);
$simv = (int)($_POST['simv'] ?? 0);
$psv = (int)($_POST['psv'] ?? 0);
$otros = (int)($_POST['otros'] ?? 0);
$frecuencia_respiratoria = (int)($_POST['frecuencia_respiratoria'] ?? 0);
$p_inspiratorio = (int)($_POST['p_inspiratorio'] ?? 0);
$p_media = (int)($_POST['p_media'] ?? 0);
$p_max = (int)($_POST['p_max'] ?? 0);
$chst = (int)($_POST['chst'] ?? 0);
$disparo = (int)($_POST['disparo'] ?? 0);
$fvt = (int)($_POST['fvt'] ?? 0);
$vol_tidal = (int)($_POST['vol_tidal'] ?? 0);
$vm_programado = (int)($_POST['vm_programado'] ?? 0);
$petco2 = (int)($_POST['petco2'] ?? 0);
$vdvt = (int)($_POST['vdvt'] ?? 0);
$ko2 = (int)($_POST['ko2'] ?? 0);

// Observaciones
$obs_presion = $_POST['obs_presion'] ?? '';
$obs_volumen = $_POST['obs_volumen'] ?? '';
$obs_simv = $_POST['obs_simv'] ?? '';
$obs_psv = $_POST['obs_psv'] ?? '';
$obs_otros = $_POST['obs_otros'] ?? '';
$obs_frecuencia_respiratoria = $_POST['obs_frecuencia_respiratoria'] ?? '';
$obs_p_inspiratorio = $_POST['obs_p_inspiratorio'] ?? '';
$obs_p_media = $_POST['obs_p_media'] ?? '';
$obs_p_max = $_POST['obs_p_max'] ?? '';
$obs_chst = $_POST['obs_chst'] ?? '';
$obs_disparo = $_POST['obs_disparo'] ?? '';
$obs_fvt = $_POST['obs_fvt'] ?? '';
$obs_vol_tidal = $_POST['obs_vol_tidal'] ?? '';
$obs_vm_programado = $_POST['obs_vm_programado'] ?? '';
$obs_petco2 = $_POST['obs_petco2'] ?? '';
$obs_vdvt = $_POST['obs_vdvt'] ?? '';
$obs_ko2 = $_POST['obs_ko2'] ?? '';

// Preparar SQL
$sql = "INSERT INTO form_registro_vm (
    date,
    pid,
    encounter,
    user,
    groupname,
    authorized,
    activity,
    modo_ventilacion,
    hora_registro,
    presion,
    volumen,
    simv,
    psv,
    otros,
    frecuencia_respiratoria,
    p_inspiratorio,
    p_media,
    p_max,
    chst,
    disparo,
    fvt,
    vol_tidal,
    vm_programado,
    petco2,
    vdvt,
    ko2,
    obs_presion,
    obs_volumen,
    obs_simv,
    obs_psv,
    obs_otros,
    obs_frecuencia_respiratoria,
    obs_p_inspiratorio,
    obs_p_media,
    obs_p_max,
    obs_chst,
    obs_disparo,
    obs_fvt,
    obs_vol_tidal,
    obs_vm_programado,
    obs_petco2,
    obs_vdvt,
    obs_ko2
) VALUES (
    NOW(),
    ?,
    ?,
    ?,
    ?,
    1,
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

// Array de parámetros
$params = array(
    $pid,
    $encounter,
    $user,
    $groupname,
    $modo_ventilacion,
    $hora_registro,
    $presion,
    $volumen,
    $simv,
    $psv,
    $otros,
    $frecuencia_respiratoria,
    $p_inspiratorio,
    $p_media,
    $p_max,
    $chst,
    $disparo,
    $fvt,
    $vol_tidal,
    $vm_programado,
    $petco2,
    $vdvt,
    $ko2,
    $obs_presion,
    $obs_volumen,
    $obs_simv,
    $obs_psv,
    $obs_otros,
    $obs_frecuencia_respiratoria,
    $obs_p_inspiratorio,
    $obs_p_media,
    $obs_p_max,
    $obs_chst,
    $obs_disparo,
    $obs_fvt,
    $obs_vol_tidal,
    $obs_vm_programado,
    $obs_petco2,
    $obs_vdvt,
    $obs_ko2
);

// Ejecutar inserción
$newid = sqlInsert($sql, $params);

if ($newid) {
    // Agregar el formulario al encuentro
    addForm($encounter, "Registro VM", $newid, "registro_vm", $pid, 1);
    
    // Guardar mensaje de éxito en sesión
    $_SESSION['registro_vm_guardado'] = true;
    
    // Redireccionar a lista de internados
    header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
    exit();
} else {
    echo "Error al guardar el registro VM.";
}
?>