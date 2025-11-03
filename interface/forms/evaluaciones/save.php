<?php
/**
 * Formulario de Evaluaciones - save.php (CON EDICIÓN)
 * Ruta: interface/forms/evaluaciones/save.php
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
$conciencia = $_POST['conciencia'] ?? '';
$obs_conciencia = $_POST['obs_conciencia'] ?? '';

$tono = $_POST['tono'] ?? '';
$obs_tono = $_POST['obs_tono'] ?? '';

$pupilas = $_POST['pupilas'] ?? '';
$obs_pupilas = $_POST['obs_pupilas'] ?? '';

$mucosas = $_POST['mucosas'] ?? '';
$obs_mucosas = $_POST['obs_mucosas'] ?? '';

$glasgow_ojos = $_POST['glasgow_ojos'] ?? '';
$obs_glasgow_ojos = $_POST['obs_glasgow_ojos'] ?? '';

$glasgow_motora = $_POST['glasgow_motora'] ?? '';
$obs_glasgow_motora = $_POST['obs_glasgow_motora'] ?? '';

$glasgow_verbal = $_POST['glasgow_verbal'] ?? '';
$obs_glasgow_verbal = $_POST['obs_glasgow_verbal'] ?? '';

$hora_evaluacion = $_POST['hora_evaluacion'] ?? null;

// Calcular puntaje de Glasgow
$glasgow_total = 0;

// Puntaje ojos
$puntajes_ojos = [
    'ESPONTANEAMENTE' => 4,
    'A ESTIMULOS AUDITIVOS' => 3,
    'AL DOLOR' => 2,
    'SIN RESPUESTA' => 1
];
$glasgow_total += $puntajes_ojos[$glasgow_ojos] ?? 0;

// Puntaje motor
$puntajes_motora = [
    'OBEDECE ORDENES' => 6,
    'LOCALIZA DOLOR' => 5,
    'FLEXION DE DEFENSA' => 4,
    'FLEXION ANORMAL' => 3,
    'EXTENSION ANORMAL' => 2,
    'NINGUNA' => 1
];
$glasgow_total += $puntajes_motora[$glasgow_motora] ?? 0;

// Puntaje verbal
$puntajes_verbal = [
    'ORIENTADO Y CONVERSA' => 5,
    'DESORIENTADO Y CONVERSA' => 4,
    'LENGUAJE INADECUADO' => 3,
    'SONIDOS INCOMPRENSIBLES' => 2,
    'NINGUNA' => 1
];
$glasgow_total += $puntajes_verbal[$glasgow_verbal] ?? 0;

// Determinar si es UPDATE o INSERT
$modo_edicion = !empty($id);

if ($modo_edicion) {
    // ============================================
    // MODO EDICIÓN: UPDATE
    // ============================================
    
    // Verificar que el registro existe y pertenece al paciente
    $check_sql = "SELECT id FROM form_evaluaciones WHERE id = ? AND pid = ? AND encounter = ? LIMIT 1";
    $check_row = sqlQuery($check_sql, array($id, $pid, $encounter));
    
    if (!$check_row) {
        die("Error: Registro no encontrado o no tiene permisos para editarlo.");
    }
    
    // Preparar SQL UPDATE
    $sql = "UPDATE form_evaluaciones SET
        date = NOW(),
        user = ?,
        groupname = ?,
        authorized = ?,
        conciencia = ?,
        obs_conciencia = ?,
        tono = ?,
        obs_tono = ?,
        pupilas = ?,
        obs_pupilas = ?,
        mucosas = ?,
        obs_mucosas = ?,
        glasgow_ojos = ?,
        obs_glasgow_ojos = ?,
        glasgow_motora = ?,
        obs_glasgow_motora = ?,
        glasgow_verbal = ?,
        obs_glasgow_verbal = ?,
        glasgow_total = ?,
        hora_evaluacion = ?
    WHERE id = ? AND pid = ? AND encounter = ?";
    
    // Array de parámetros (22 valores)
    $params = array(
        $user,                      // 1
        $groupname,                 // 2
        $authorized,                // 3
        $conciencia,                // 4
        $obs_conciencia,            // 5
        $tono,                      // 6
        $obs_tono,                  // 7
        $pupilas,                   // 8
        $obs_pupilas,               // 9
        $mucosas,                   // 10
        $obs_mucosas,               // 11
        $glasgow_ojos,              // 12
        $obs_glasgow_ojos,          // 13
        $glasgow_motora,            // 14
        $obs_glasgow_motora,        // 15
        $glasgow_verbal,            // 16
        $obs_glasgow_verbal,        // 17
        $glasgow_total,             // 18
        $hora_evaluacion,           // 19
        $id,                        // 20 WHERE
        $pid,                       // 21 WHERE
        $encounter                  // 22 WHERE
    );
    
    // Ejecutar UPDATE
    $result = sqlStatement($sql, $params);
    
    if ($result !== false) {
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que creación para que aparezca el mensaje
        $_SESSION['evaluacion_guardada'] = true;
        
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
    $sql = "INSERT INTO form_evaluaciones (
        date,
        pid,
        encounter,
        user,
        groupname,
        authorized,
        activity,
        conciencia,
        obs_conciencia,
        tono,
        obs_tono,
        pupilas,
        obs_pupilas,
        mucosas,
        obs_mucosas,
        glasgow_ojos,
        obs_glasgow_ojos,
        glasgow_motora,
        obs_glasgow_motora,
        glasgow_verbal,
        obs_glasgow_verbal,
        glasgow_total,
        hora_evaluacion
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
        ?
    )";
    
    // Array de parámetros (21 valores)
    $params = array(
        $pid,                       // 1
        $encounter,                 // 2
        $user,                      // 3
        $groupname,                 // 4
        $authorized,                // 5
        $conciencia,                // 6
        $obs_conciencia,            // 7
        $tono,                      // 8
        $obs_tono,                  // 9
        $pupilas,                   // 10
        $obs_pupilas,               // 11
        $mucosas,                   // 12
        $obs_mucosas,               // 13
        $glasgow_ojos,              // 14
        $obs_glasgow_ojos,          // 15
        $glasgow_motora,            // 16
        $obs_glasgow_motora,        // 17
        $glasgow_verbal,            // 18
        $obs_glasgow_verbal,        // 19
        $glasgow_total,             // 20
        $hora_evaluacion            // 21
    );
    
    // Ejecutar INSERT
    $newid = sqlInsert($sql, $params);
    
    if ($newid) {
        // Agregar el formulario al encuentro
        addForm($encounter, 'Evaluaciones', $newid, 'evaluaciones', $pid, $authorized);
        
        // ✅ IMPORTANTE: Usar MISMA variable de sesión que edición para consistencia
        $_SESSION['evaluacion_guardada'] = true;
        
        // Redirigir a lista de internados
        header("Location: " . $GLOBALS['webroot'] . "/interface/tableros/lista_internados.php");
        exit;
    } else {
        die("Error al guardar los datos");
    }
}
?>