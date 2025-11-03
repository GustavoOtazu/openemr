<?php
/**
 * Formulario de Aplicaciones - save.php (CON EDICIÓN)
 * Ruta: interface/forms/aplicaciones/save.php
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
$medicamentos = (int)($_POST['medicamentos'] ?? 0);
$obs_medicamentos = $_POST['obs_medicamentos'] ?? '';

$sueros = (int)($_POST['sueros'] ?? 0);
$obs_sueros = $_POST['obs_sueros'] ?? '';

$vacunas = (int)($_POST['vacunas'] ?? 0);
$obs_vacunas = $_POST['obs_vacunas'] ?? '';

$expansiones = (int)($_POST['expansiones'] ?? 0);
$obs_expansiones = $_POST['obs_expansiones'] ?? '';

$sangre = (int)($_POST['sangre'] ?? 0);
$obs_sangre = $_POST['obs_sangre'] ?? '';

$hora_registro = $_POST['hora_registro'] ?? null;

// Determinar si es UPDATE o INSERT
$modo_edicion = !empty($id);

if ($modo_edicion) {
    // ============================================
    // MODO EDICIÓN: UPDATE
    // ============================================
    
    // Verificar que el registro existe y pertenece al paciente
    $check_sql = "SELECT id FROM form_aplicaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $check_row = sqlQuery($check_sql, array($id, $pid, $encounter));
    
    if (!$check_row) {
        die("Error: Registro no encontrado o no tiene permisos para editarlo.");
    }
    
    // Preparar SQL UPDATE
    $sql = "UPDATE form_aplicaciones SET
        date = NOW(),
        user = ?,
        groupname = ?,
        authorized = ?,
        medicamentos = ?,
        obs_medicamentos = ?,
        sueros = ?,
        obs_sueros = ?,
        vacunas = ?,
        obs_vacunas = ?,
        expansiones = ?,
        obs_expansiones = ?,
        sangre = ?,
        obs_sangre = ?,
        hora_registro = ?
    WHERE id = ? AND pid = ? AND encounter = ?";
    
    // Array de parámetros (17 valores)
    $params = array(
        $user,                   // 1
        $groupname,              // 2
        $authorized,             // 3
        $medicamentos,           // 4
        $obs_medicamentos,       // 5
        $sueros,                 // 6
        $obs_sueros,             // 7
        $vacunas,                // 8
        $obs_vacunas,            // 9
        $expansiones,            // 10
        $obs_expansiones,        // 11
        $sangre,                 // 12
        $obs_sangre,             // 13
        $hora_registro,          // 14
        $id,                     // 15 WHERE
        $pid,                    // 16 WHERE
        $encounter               // 17 WHERE
    );
    
    // Ejecutar UPDATE
    $result = sqlStatement($sql, $params);
    
    if ($result !== false) {
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que creación para que aparezca el mensaje
        $_SESSION['aplicacion_guardada'] = true;
        
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
    $sql = "INSERT INTO form_aplicaciones (
        date,
        pid,
        encounter,
        user,
        groupname,
        authorized,
        activity,
        medicamentos,
        obs_medicamentos,
        sueros,
        obs_sueros,
        vacunas,
        obs_vacunas,
        expansiones,
        obs_expansiones,
        sangre,
        obs_sangre,
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
        ?
    )";
    
    // Array de parámetros (16 valores)
    $params = array(
        $pid,                    // 1
        $encounter,              // 2
        $user,                   // 3
        $groupname,              // 4
        $authorized,             // 5
        $medicamentos,           // 6
        $obs_medicamentos,       // 7
        $sueros,                 // 8
        $obs_sueros,             // 9
        $vacunas,                // 10
        $obs_vacunas,            // 11
        $expansiones,            // 12
        $obs_expansiones,        // 13
        $sangre,                 // 14
        $obs_sangre,             // 15
        $hora_registro           // 16
    );
    
    // Ejecutar INSERT
    $newid = sqlInsert($sql, $params);
    
    if ($newid) {
        // Agregar el formulario al encuentro
        addForm($encounter, 'Aplicaciones', $newid, 'aplicaciones', $pid, $authorized);
        
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que edición para consistencia
        $_SESSION['aplicacion_guardada'] = true;
        
        // Redirigir a lista de internados
        header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
        exit;
    } else {
        die("Error al guardar los datos");
    }
}
?>