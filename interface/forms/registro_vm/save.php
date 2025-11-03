<?php
/**
 * Formulario de Registro VM - save.php (CON EDICIÓN)
 * Ruta: interface/forms/registro_vm/save.php
 * Soporta tanto INSERT (crear) como UPDATE (editar)
 * CORREGIDO: 100% consistente con patrón de Curaciones
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
$id = isset($_POST['id']) ? (int)$_POST['id'] : null; // ← NUEVO: Detectar modo edición

if (!$pid || !$encounter) {
    die("Error: Faltan datos requeridos (PID o Encounter)");
}

// Obtener usuario actual
$user = $_SESSION['authUser'] ?? 'admin';
$groupname = $_SESSION['authProvider'] ?? 'Default';
$authorized = $_SESSION['userauthorized'] ?? 1;

// Capturar datos del formulario
$modo_ventilacion = $_POST['modo_ventilacion'] ?? '';
$obs_modo = $_POST['obs_modo'] ?? '';

$presion = (int)($_POST['presion'] ?? 0);
$obs_presion = $_POST['obs_presion'] ?? '';

$volumen = (int)($_POST['volumen'] ?? 0);
$obs_volumen = $_POST['obs_volumen'] ?? '';

$simv = (int)($_POST['simv'] ?? 0);
$obs_simv = $_POST['obs_simv'] ?? '';

$psv = (int)($_POST['psv'] ?? 0);
$obs_psv = $_POST['obs_psv'] ?? '';

$otros = (int)($_POST['otros'] ?? 0);
$obs_otros = $_POST['obs_otros'] ?? '';

$frecuencia_respiratoria = (int)($_POST['frecuencia_respiratoria'] ?? 0);
$obs_frecuencia_respiratoria = $_POST['obs_frecuencia_respiratoria'] ?? '';

$p_inspiratorio = (int)($_POST['p_inspiratorio'] ?? 0);
$obs_p_inspiratorio = $_POST['obs_p_inspiratorio'] ?? '';

$p_media = (int)($_POST['p_media'] ?? 0);
$obs_p_media = $_POST['obs_p_media'] ?? '';

$p_max = (int)($_POST['p_max'] ?? 0);
$obs_p_max = $_POST['obs_p_max'] ?? '';

$chst = (int)($_POST['chst'] ?? 0);
$obs_chst = $_POST['obs_chst'] ?? '';

$disparo = (int)($_POST['disparo'] ?? 0);
$obs_disparo = $_POST['obs_disparo'] ?? '';

$fvt = (int)($_POST['fvt'] ?? 0);
$obs_fvt = $_POST['obs_fvt'] ?? '';

$vol_tidal = (int)($_POST['vol_tidal'] ?? 0);
$obs_vol_tidal = $_POST['obs_vol_tidal'] ?? '';

$vm_programado = (int)($_POST['vm_programado'] ?? 0);
$obs_vm_programado = $_POST['obs_vm_programado'] ?? '';

$petco2 = (int)($_POST['petco2'] ?? 0);
$obs_petco2 = $_POST['obs_petco2'] ?? '';

$vdvt = (int)($_POST['vdvt'] ?? 0);
$obs_vdvt = $_POST['obs_vdvt'] ?? '';

$ko2 = (int)($_POST['ko2'] ?? 0);
$obs_ko2 = $_POST['obs_ko2'] ?? '';

$hora_registro = $_POST['hora_registro'] ?? null;

// Determinar si es UPDATE o INSERT
$modo_edicion = !empty($id);

if ($modo_edicion) {
    // ============================================
    // MODO EDICIÓN: UPDATE
    // ============================================
    
    // Verificar que el registro existe y pertenece al paciente
    $check_sql = "SELECT id FROM form_registro_vm WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $check_row = sqlQuery($check_sql, array($id, $pid, $encounter));
    
    if (!$check_row) {
        die("Error: Registro no encontrado o no tiene permisos para editarlo.");
    }
    
    // Preparar SQL UPDATE
    $sql = "UPDATE form_registro_vm SET
        date = NOW(),
        user = ?,
        groupname = ?,
        authorized = ?,
        modo_ventilacion = ?,
        obs_modo = ?,
        presion = ?,
        obs_presion = ?,
        volumen = ?,
        obs_volumen = ?,
        simv = ?,
        obs_simv = ?,
        psv = ?,
        obs_psv = ?,
        otros = ?,
        obs_otros = ?,
        frecuencia_respiratoria = ?,
        obs_frecuencia_respiratoria = ?,
        p_inspiratorio = ?,
        obs_p_inspiratorio = ?,
        p_media = ?,
        obs_p_media = ?,
        p_max = ?,
        obs_p_max = ?,
        chst = ?,
        obs_chst = ?,
        disparo = ?,
        obs_disparo = ?,
        fvt = ?,
        obs_fvt = ?,
        vol_tidal = ?,
        obs_vol_tidal = ?,
        vm_programado = ?,
        obs_vm_programado = ?,
        petco2 = ?,
        obs_petco2 = ?,
        vdvt = ?,
        obs_vdvt = ?,
        ko2 = ?,
        obs_ko2 = ?,
        hora_registro = ?
    WHERE id = ? AND pid = ? AND encounter = ?";
    
    // Array de parámetros (43 valores)
    $params = array(
        $user,                              // 1
        $groupname,                         // 2
        $authorized,                        // 3
        $modo_ventilacion,                  // 4
        $obs_modo,                          // 5
        $presion,                           // 6
        $obs_presion,                       // 7
        $volumen,                           // 8
        $obs_volumen,                       // 9
        $simv,                              // 10
        $obs_simv,                          // 11
        $psv,                               // 12
        $obs_psv,                           // 13
        $otros,                             // 14
        $obs_otros,                         // 15
        $frecuencia_respiratoria,           // 16
        $obs_frecuencia_respiratoria,       // 17
        $p_inspiratorio,                    // 18
        $obs_p_inspiratorio,                // 19
        $p_media,                           // 20
        $obs_p_media,                       // 21
        $p_max,                             // 22
        $obs_p_max,                         // 23
        $chst,                              // 24
        $obs_chst,                          // 25
        $disparo,                           // 26
        $obs_disparo,                       // 27
        $fvt,                               // 28
        $obs_fvt,                           // 29
        $vol_tidal,                         // 30
        $obs_vol_tidal,                     // 31
        $vm_programado,                     // 32
        $obs_vm_programado,                 // 33
        $petco2,                            // 34
        $obs_petco2,                        // 35
        $vdvt,                              // 36
        $obs_vdvt,                          // 37
        $ko2,                               // 38
        $obs_ko2,                           // 39
        $hora_registro,                     // 40
        $id,                                // 41 WHERE
        $pid,                               // 42 WHERE
        $encounter                          // 43 WHERE
    );
    
    // Ejecutar UPDATE
    $result = sqlStatement($sql, $params);
    
    if ($result !== false) {
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que creación para que aparezca el mensaje
        $_SESSION['registro_vm_guardado'] = true;
        
        // Redirigir a lista de internados
        header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
        exit;
    } else {
        die("Error al actualizar los datos");
    }
    
} else {
    // ============================================
    // MODO CREACIÓN: INSERT
    // ============================================
    
    // Preparar SQL INSERT
    $sql = "INSERT INTO form_registro_vm (
        date,
        pid,
        encounter,
        user,
        groupname,
        authorized,
        activity,
        modo_ventilacion,
        obs_modo,
        presion,
        obs_presion,
        volumen,
        obs_volumen,
        simv,
        obs_simv,
        psv,
        obs_psv,
        otros,
        obs_otros,
        frecuencia_respiratoria,
        obs_frecuencia_respiratoria,
        p_inspiratorio,
        obs_p_inspiratorio,
        p_media,
        obs_p_media,
        p_max,
        obs_p_max,
        chst,
        obs_chst,
        disparo,
        obs_disparo,
        fvt,
        obs_fvt,
        vol_tidal,
        obs_vol_tidal,
        vm_programado,
        obs_vm_programado,
        petco2,
        obs_petco2,
        vdvt,
        obs_vdvt,
        ko2,
        obs_ko2,
        hora_registro
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
    
    // Array de parámetros (42 valores)
    $params = array(
        $pid,                               // 1
        $encounter,                         // 2
        $user,                              // 3
        $groupname,                         // 4
        $authorized,                        // 5
        $modo_ventilacion,                  // 6
        $obs_modo,                          // 7
        $presion,                           // 8
        $obs_presion,                       // 9
        $volumen,                           // 10
        $obs_volumen,                       // 11
        $simv,                              // 12
        $obs_simv,                          // 13
        $psv,                               // 14
        $obs_psv,                           // 15
        $otros,                             // 16
        $obs_otros,                         // 17
        $frecuencia_respiratoria,           // 18
        $obs_frecuencia_respiratoria,       // 19
        $p_inspiratorio,                    // 20
        $obs_p_inspiratorio,                // 21
        $p_media,                           // 22
        $obs_p_media,                       // 23
        $p_max,                             // 24
        $obs_p_max,                         // 25
        $chst,                              // 26
        $obs_chst,                          // 27
        $disparo,                           // 28
        $obs_disparo,                       // 29
        $fvt,                               // 30
        $obs_fvt,                           // 31
        $vol_tidal,                         // 32
        $obs_vol_tidal,                     // 33
        $vm_programado,                     // 34
        $obs_vm_programado,                 // 35
        $petco2,                            // 36
        $obs_petco2,                        // 37
        $vdvt,                              // 38
        $obs_vdvt,                          // 39
        $ko2,                               // 40
        $obs_ko2,                           // 41
        $hora_registro                      // 42
    );
    
    // Ejecutar INSERT
    $newid = sqlInsert($sql, $params);
    
    if ($newid) {
        // Agregar el formulario al encuentro
        addForm($encounter, 'Registro VM', $newid, 'registro_vm', $pid, $authorized);
        
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que edición para consistencia
        $_SESSION['registro_vm_guardado'] = true;
        
        // Redirigir a lista de internados
        header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
        exit;
    } else {
        die("Error al guardar los datos");
    }
}
?>