<?php
/**
 * Formulario de Curaciones - save.php (CON EDICIÓN)
 * Ruta: interface/forms/curaciones/save.php
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
$herida_operatoria = (int)($_POST['herida_operatoria'] ?? 0);
$obs_herida_operatoria = $_POST['obs_herida_operatoria'] ?? '';

$traqueostomia = (int)($_POST['traqueostomia'] ?? 0);
$obs_traqueostomia = $_POST['obs_traqueostomia'] ?? '';

$ostomias = (int)($_POST['ostomias'] ?? 0);
$obs_ostomias = $_POST['obs_ostomias'] ?? '';

$escaras = (int)($_POST['escaras'] ?? 0);
$obs_escaras = $_POST['obs_escaras'] ?? '';

$via_venosa_central = (int)($_POST['via_venosa_central'] ?? 0);
$obs_via_venosa_central = $_POST['obs_via_venosa_central'] ?? '';

$via_venosa = (int)($_POST['via_venosa'] ?? 0);
$obs_via_venosa = $_POST['obs_via_venosa'] ?? '';

$hora_operacion = $_POST['hora_operacion'] ?? null;

// Determinar si es UPDATE o INSERT
$modo_edicion = !empty($id);

if ($modo_edicion) {
    // ============================================
    // MODO EDICIÓN: UPDATE
    // ============================================
    
    // Verificar que el registro existe y pertenece al paciente
    $check_sql = "SELECT id FROM form_curaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $check_row = sqlQuery($check_sql, array($id, $pid, $encounter));
    
    if (!$check_row) {
        die("Error: Registro no encontrado o no tiene permisos para editarlo.");
    }
    
    // Preparar SQL UPDATE
    $sql = "UPDATE form_curaciones SET
        date = NOW(),
        user = ?,
        groupname = ?,
        authorized = ?,
        herida_operatoria = ?,
        obs_herida_operatoria = ?,
        traqueostomia = ?,
        obs_traqueostomia = ?,
        ostomias = ?,
        obs_ostomias = ?,
        escaras = ?,
        obs_escaras = ?,
        via_venosa_central = ?,
        obs_via_venosa_central = ?,
        via_venosa = ?,
        obs_via_venosa = ?,
        hora_operacion = ?
    WHERE id = ? AND pid = ? AND encounter = ?";
    
    // Array de parámetros (19 valores)
    $params = array(
        $user,                      // 1
        $groupname,                 // 2
        $authorized,                // 3
        $herida_operatoria,         // 4
        $obs_herida_operatoria,     // 5
        $traqueostomia,             // 6
        $obs_traqueostomia,         // 7
        $ostomias,                  // 8
        $obs_ostomias,              // 9
        $escaras,                   // 10
        $obs_escaras,               // 11
        $via_venosa_central,        // 12
        $obs_via_venosa_central,    // 13
        $via_venosa,                // 14
        $obs_via_venosa,            // 15
        $hora_operacion,            // 16
        $id,                        // 17 WHERE
        $pid,                       // 18 WHERE
        $encounter                  // 19 WHERE
    );
    
    // Ejecutar UPDATE
    $result = sqlStatement($sql, $params);
    
    if ($result !== false) {
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que creación para que aparezca el mensaje
        $_SESSION['curacion_guardada'] = true;
        
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
    $sql = "INSERT INTO form_curaciones (
        date,
        pid,
        encounter,
        user,
        groupname,
        authorized,
        activity,
        herida_operatoria,
        obs_herida_operatoria,
        traqueostomia,
        obs_traqueostomia,
        ostomias,
        obs_ostomias,
        escaras,
        obs_escaras,
        via_venosa_central,
        obs_via_venosa_central,
        via_venosa,
        obs_via_venosa,
        hora_operacion
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
        $pid,                       // 1
        $encounter,                 // 2
        $user,                      // 3
        $groupname,                 // 4
        $authorized,                // 5
        $herida_operatoria,         // 6
        $obs_herida_operatoria,     // 7
        $traqueostomia,             // 8
        $obs_traqueostomia,         // 9
        $ostomias,                  // 10
        $obs_ostomias,              // 11
        $escaras,                   // 12
        $obs_escaras,               // 13
        $via_venosa_central,        // 14
        $obs_via_venosa_central,    // 15
        $via_venosa,                // 16
        $obs_via_venosa,            // 17
        $hora_operacion             // 18
    );
    
    // Ejecutar INSERT
    $newid = sqlInsert($sql, $params);
    
    if ($newid) {
        // Agregar el formulario al encuentro
        addForm($encounter, 'Curaciones', $newid, 'curaciones', $pid, $authorized);
        
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que edición para consistencia
        $_SESSION['curacion_guardada'] = true;
        
        // Redirigir a lista de internados
        header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
        exit;
    } else {
        die("Error al guardar los datos");
    }
}
?>