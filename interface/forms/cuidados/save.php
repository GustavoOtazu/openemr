<?php
/**
 * Formulario de Cuidados - save.php (CON EDICIÓN)
 * Ruta: interface/forms/cuidados/save.php
 * Soporta tanto INSERT (crear) como UPDATE (editar)
 * CORREGIDO: Mensaje de éxito aparece en AMBOS modos (crear y editar)
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
$posicion_paciente = $_POST['posicion_paciente'] ?? '';
$obs_posicion_paciente = $_POST['obs_posicion_paciente'] ?? '';

$enjuague_bucal = (int)($_POST['enjuague_bucal'] ?? 0);
$obs_enjuague_bucal = $_POST['obs_enjuague_bucal'] ?? '';

$higiene_manos = (int)($_POST['higiene_manos'] ?? 0);
$obs_higiene_manos = $_POST['obs_higiene_manos'] ?? '';

$aspirado_secreciones = (int)($_POST['aspirado_secreciones'] ?? 0);
$obs_aspirado_secreciones = $_POST['obs_aspirado_secreciones'] ?? '';

$suspension_sedacion = (int)($_POST['suspension_sedacion'] ?? 0);
$obs_suspension_sedacion = $_POST['obs_suspension_sedacion'] ?? '';

$medicion_cuff = (int)($_POST['medicion_cuff'] ?? 0);
$obs_medicion_cuff = $_POST['obs_medicion_cuff'] ?? '';

$hora_cuidado = $_POST['hora_cuidado'] ?? null;

// Determinar si es UPDATE o INSERT
$modo_edicion = !empty($id);

if ($modo_edicion) {
    // ============================================
    // MODO EDICIÓN: UPDATE
    // ============================================
    
    // Verificar que el registro existe y pertenece al paciente
    $check_sql = "SELECT id FROM form_cuidados WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $check_row = sqlQuery($check_sql, array($id, $pid, $encounter));
    
    if (!$check_row) {
        die("Error: Registro no encontrado o no tiene permisos para editarlo.");
    }
    
    // Preparar SQL UPDATE
    $sql = "UPDATE form_cuidados SET
        date = NOW(),
        user = ?,
        groupname = ?,
        authorized = ?,
        posicion_paciente = ?,
        obs_posicion_paciente = ?,
        enjuague_bucal = ?,
        obs_enjuague_bucal = ?,
        higiene_manos = ?,
        obs_higiene_manos = ?,
        aspirado_secreciones = ?,
        obs_aspirado_secreciones = ?,
        suspension_sedacion = ?,
        obs_suspension_sedacion = ?,
        medicion_cuff = ?,
        obs_medicion_cuff = ?,
        hora_cuidado = ?
    WHERE id = ? AND pid = ? AND encounter = ?";
    
    // Array de parámetros (19 valores)
    $params = array(
        $user,                          // 1
        $groupname,                     // 2
        $authorized,                    // 3
        $posicion_paciente,             // 4
        $obs_posicion_paciente,         // 5
        $enjuague_bucal,                // 6
        $obs_enjuague_bucal,            // 7
        $higiene_manos,                 // 8
        $obs_higiene_manos,             // 9
        $aspirado_secreciones,          // 10
        $obs_aspirado_secreciones,      // 11
        $suspension_sedacion,           // 12
        $obs_suspension_sedacion,       // 13
        $medicion_cuff,                 // 14
        $obs_medicion_cuff,             // 15
        $hora_cuidado,                  // 16
        $id,                            // 17 WHERE
        $pid,                           // 18 WHERE
        $encounter                      // 19 WHERE
    );
    
    // Ejecutar UPDATE
    $result = sqlStatement($sql, $params);
    
    if ($result !== false) {
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que creación para que aparezca el mensaje
        $_SESSION['cuidado_guardado'] = true;
        
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
    $sql = "INSERT INTO form_cuidados (
        date,
        pid,
        encounter,
        user,
        groupname,
        authorized,
        activity,
        posicion_paciente,
        obs_posicion_paciente,
        enjuague_bucal,
        obs_enjuague_bucal,
        higiene_manos,
        obs_higiene_manos,
        aspirado_secreciones,
        obs_aspirado_secreciones,
        suspension_sedacion,
        obs_suspension_sedacion,
        medicion_cuff,
        obs_medicion_cuff,
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
    
    // Array de parámetros (18 valores)
    $params = array(
        $pid,                           // 1
        $encounter,                     // 2
        $user,                          // 3
        $groupname,                     // 4
        $authorized,                    // 5
        $posicion_paciente,             // 6
        $obs_posicion_paciente,         // 7
        $enjuague_bucal,                // 8
        $obs_enjuague_bucal,            // 9
        $higiene_manos,                 // 10
        $obs_higiene_manos,             // 11
        $aspirado_secreciones,          // 12
        $obs_aspirado_secreciones,      // 13
        $suspension_sedacion,           // 14
        $obs_suspension_sedacion,       // 15
        $medicion_cuff,                 // 16
        $obs_medicion_cuff,             // 17
        $hora_cuidado                   // 18
    );
    
    // Ejecutar INSERT
    $newid = sqlInsert($sql, $params);
    
    if ($newid) {
        // Agregar el formulario al encuentro
        addForm($encounter, 'Cuidados', $newid, 'cuidados', $pid, $authorized);
        
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que edición para consistencia
        $_SESSION['cuidado_guardado'] = true;
        
        // Redirigir a lista de internados
        header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
        exit;
    } else {
        die("Error al guardar los datos");
    }
}
?>