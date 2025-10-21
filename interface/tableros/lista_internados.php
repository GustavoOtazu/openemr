<?php

/**
 * Main info frame.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once("../globals.php");
require_once "$srcdir/user.inc";
require_once "$srcdir/options.inc.php";

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;
use OpenEMR\OeUI\OemrUI;

/*Extraer todos los internados actuales, tabla: form_encounter, con tipo Internación pc_catid = 16 (referencia tabla: openemr_postcalendar_categories)*/

$id_encounter = $_GET['id_encounter'] ?? null;
$nombre_paciente = $_GET['paciente'] ?? null;
$death_date = $_GET['death_date'] ?? null;
$update = $_GET['update'] ?? null;

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Variable para almacenar mensajes
$mensaje_exito = '';

// Mensaje de éxito para CURACIONES
if (isset($_SESSION['curacion_guardada']) && $_SESSION['curacion_guardada']) {
    $mensaje_exito = '¡Éxito! La curación se guardó correctamente.';
    unset($_SESSION['curacion_guardada']);
}

// Mensaje de éxito para CUIDADOS
if (isset($_SESSION['cuidado_guardado']) && $_SESSION['cuidado_guardado']) {
    $mensaje_exito = '¡Éxito! El cuidado se guardó correctamente.';
    unset($_SESSION['cuidado_guardado']);
}

// Mensaje de éxito para EVALUACIONES
if (isset($_SESSION['evaluacion_guardada']) && $_SESSION['evaluacion_guardada']) {
    $mensaje_exito = '¡Éxito! La evaluación se guardó correctamente.';
    unset($_SESSION['evaluacion_guardada']);
}

if ($death_date) {
    sqlStatement("UPDATE form_encounter set out_date= ? , death_date= ? where id = ?", array($death_date, $death_date, $id_encounter));
    $id_encounter = null;
} elseif ($id_encounter) {
    sqlStatement("UPDATE form_encounter set out_date= DATE(NOW()) where id = ?", array($id_encounter));
}

$internados_actuales_consult = "SELECT f.*, CONCAT(CONCAT(p.fname, ' '),p.lname) as paciente, p.pubpid as pubpid, f.nro_registro as nro_registro, f.encounter as encounter from form_encounter as f join patient_data as p on p.pid = f.pid where f.pc_catid = 16 and f.out_date is null";
$res = sqlStatement($internados_actuales_consult);
$inpatient = [];
for ($iter = 0; $row = sqlFetchArray($res); $iter++) {
    $inpatient[$iter] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php Header::setupHeader(['no_bootstrap']); ?>
    <title><?php echo xlt('Internados'); ?></title>
    <link rel="stylesheet" href="../../public/themes/style_light.css">
    <link rel="stylesheet" href="../../public/assets/bootstrap/dist/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../../public/assets/jquery-ui/jquery-ui.css" type="text/css">
    <script type="text/javascript" src="../../public/assets/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="../../public/assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../public/assets/font-awesome/css/font-awesome.min.css" type="text/css">
    <link rel="shortcut icon" href="../../public/images/favicon.ico" />
    <script type="text/javascript" src="../../public/assets/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="../../public/assets/datatable-last/jquery.dataTables.min.css" type="text/css">
    <script type="text/javascript" src="../../public/assets/datatable-last/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../../public/assets/datatable-last/dataTables.select.min.js"></script>
    <script type="text/javascript" src="../../public/assets/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="../../public/assets/select2/dist/css/select2.min.css" type="text/css">
    
    <style type="text/css">
        /* Finder Processing style */
        div.dataTables_wrapper div.dataTables_processing {
            top: -20px;
            width: auto;
            margin: 0;
            color: red;
            transform: translateX(-50%);
        }

        @media screen and (max-width: 640px) {
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: inherit;
                text-align: justify;
            }
        }

        thead input {
            width: 100%;
        }

        .inner {
            display: inline-block;
        }

        .outer {
            width: 100%;
            text-align: center;
        }

        /* Estilos para alerta de éxito */
        .alert-success-custom {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            min-width: 320px;
            font-weight: bold;
            animation: slideIn 0.5s ease-out, fadeOut 0.5s ease-in 4.5s;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-success-custom .icon-success {
            font-size: 24px;
            color: #28a745;
        }
        
        .alert-success-custom .close-btn {
            cursor: pointer;
            font-size: 20px;
            line-height: 20px;
            margin-left: auto;
            color: #155724;
            background: none;
            border: none;
            padding: 0;
        }
        
        .alert-success-custom .close-btn:hover {
            color: #0d3d1a;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        /* Estilos para los botones de enfermería */
        .btn-enf-card {
            width: 100%;
            height: 140px;
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn-enf-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-enf-card:active {
            transform: translateY(-2px);
        }

        .btn-enf-card .enf-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .btn-enf-card:hover .enf-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .btn-enf-card .enf-icon i {
            color: white;
        }

        .btn-enf-card .enf-label {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .btn-enf-card {
                height: 120px;
            }
            
            .btn-enf-card .enf-icon {
                width: 50px;
                height: 50px;
            }
            
            .btn-enf-card .enf-icon i {
                font-size: 24px;
            }
            
            .alert-success-custom {
                top: 10px;
                right: 10px;
                left: 10px;
                min-width: auto;
            }
        }
    </style>
    
    <?php
    $arrOeUiSettings = array(
        'heading_title' => xl('Patient Finder'),
        'include_patient_name' => false,
        'expandable' => true,
        'expandable_files' => array('dynamic_finder_xpd'),
        'action' => "search",
        'action_title' => "",
        'action_href' => "",
        'show_help_icon' => false,
        'help_file_name' => ""
    );
    $oemr_ui = new OemrUI($arrOeUiSettings);
    ?>
</head>

<body class="body_top">

    <?php if ($mensaje_exito): ?>
    <div class="alert-success-custom" id="alertaExito">
        <i class="fa fa-check-circle icon-success"></i>
        <span><?php echo text($mensaje_exito); ?></span>
        <button class="close-btn" onclick="cerrarAlerta()" aria-label="Cerrar">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <script>
        // Cerrar alerta automáticamente después de 5 segundos
        setTimeout(function() {
            var alerta = document.getElementById('alertaExito');
            if (alerta) {
                alerta.style.display = 'none';
            }
        }, 5000);
        
        // Función para cerrar manualmente
        function cerrarAlerta() {
            var alerta = document.getElementById('alertaExito');
            if (alerta) {
                alerta.style.display = 'none';
            }
        }
    </script>
    <?php endif; ?>

    <div id="container" class="<?php echo attr($oemr_ui->oeContainer()); ?>" style="width: 95%;">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header clearfix">
                    <h2>Lista de Internados</h2>
                    <br />
                    <?php if ($id_encounter != null) { ?>
                        <div class="alert alert-success alert-dismissible show" role="alert">
                            Se dió de alta al paciente <strong><?php echo text($nombre_paciente); ?></strong> con éxito!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar" style="color: black !important;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    <?php if ($update != null) { ?>
                        <div class="alert alert-success alert-dismissible show" role="alert">
                            Se actualizó al paciente con éxito!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar" style="color: black !important;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    <?php
                    $id_encounter = null;
                    $death_date = null;
                    ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <div id="dynamic">
                    <table border="0" cellpadding="0" cellspacing="0" class="display" id="inp_table" style="width:100%">
                        <thead>
                            <tr>
                                <th class="head" style="width: 5%;">Nro Prontuario</th>
                                <th class="head">Paciente</th>
                                <th class="head">Ingreso</th>
                                <th class="head">CI (RG Paciente)</th>
                                <th class="head" style="width: 4%;">NRO Registro</th>
                                <th class="head" style="width: 10%;">Servicio</th>
                                <th class="head" style="width: 10%;">Sala</th>
                                <th class="head" style="width: 5%;">Cama</th>
                                <th class="head" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($inpatient)) {
                                foreach ($inpatient as $index => $result) {
                                    echo '<tr>' .
                                        '<td class="btn-pacienteData btn-link" data-pid="' . attr($result['pid']) . '" data-encounter="' . attr($result['encounter']) . '">' . text($result['pid']) . '</td>' .
                                        '<td>' . text($result['paciente']) . '</td>' .
                                        '<td>' . date('d/m/Y', strtotime($result['date'])) . '</td>' .
                                        '<td>' . text($result['pubpid']) . '</td>' .
                                        '<td>' . text($result['nro_registro']) . '</td>' .
                                        '<td>' . text(strtoupper($result['servicio'])) . '</td>' .
                                        '<td>' . text(strtoupper($result['cuarto'])) . '</td>' .
                                        '<td>' . text($result['cama']) . '</td>' .
                                        '<td><div class="outer">' .
                                        '<div class="inner"><button class="btn btn-info btn-editar btn-xs" type="button" data-id="' . attr($result['id']) . '">EDIT</button></div>' .
                                        '<div class="inner"><button class="btn btn-default btn-alta btn-xs" type="button" id="' . attr($result['id']) . '" data-title="Dar de alta al paciente: ' . attr($result['paciente']) . '" data-paciente="' . attr($result['paciente']) . '">ALTA</button></div>' .
                                        '<div class="inner"><button class="btn btn-danger btn-death btn-xs" type="button" data-id="' . attr($result['id']) . '" data-title="Registrar muerte del paciente: ' . attr($result['paciente']) . '" data-paciente="' . attr($result['paciente']) . '">OBITO</button></div>' .
                                        '<div class="inner"><button class="btn btn-primary btn-Enferm btn-xs" type="button" data-id="' . attr($result['id']) . '" data-title="Opciones de Enfermeria: ' . attr($result['paciente']) . '" data-paciente="' . attr($result['paciente']) . '" data-pid="' . attr($result['pid']) . '" data-encounter="' . attr($result['encounter']) . '">Enfermeria</button></div>' .
                                        '</div></td>' .
                                        '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="head">Identificador</th>
                                <th class="head">Paciente</th>
                                <th class="head">Fecha de Ingreso</th>
                                <th class="head">CI (RG Paciente)</th>
                                <th class="head">NRO Prontuario</th>
                                <th class="head">Servicio</th>
                                <th class="head">Sala</th>
                                <th class="head">Cama</th>
                                <th class="head">Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal ALTA/OBITO -->
        <div class="modal" tabindex="-1" role="dialog" id="modal_alta">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: black !important;"><i class="fa fa-times"></i></span>
                        </button>
                    </div>
                    <form method="get" name="form" action="lista_internados.php">
                        <div class="modal-body">
                            <p id="body_alta">¿Está seguro que desea dar el alta al paciente: <b id="paciente_name"></b>?</p>
                            <div class="row" id="body_death" style="display: none">
                                <div class="col-md-6">
                                    <label for="death_date">Registrar fecha de muerte</label>
                                    <input id="death_date" name="death_date" class="form-control" type="date" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_encounter" id="id_encounter" />
                            <input type="hidden" name="paciente" id="nombre_paciente" />
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                            <input type="submit" value="Confirmar" class="btn btn-success pull-right">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal ENFERMERÍA MEJORADO -->
        <div class="modal fade" tabindex="-1" role="dialog" id="modal_Enf">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 850px;">
                <div class="modal-content" style="border: none; border-radius: 15px; overflow: hidden;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px 30px;">
                        <h4 class="modal-title" style="font-weight: 600; margin: 0;">
                            <i class="fa fa-user-md"></i> Opciones de Enfermería
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.9; font-size: 28px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body" style="padding: 30px 25px; background-color: #f8f9fa;">
                        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <p style="margin: 0; color: #555; font-size: 15px;">
                                <strong style="color: #667eea;">Paciente:</strong> 
                                <span id="paciente_nombre_enf" style="color: #333;"></span>
                            </p>
                        </div>

                        <div class="row" style="margin: 0 -10px;">
                            <!-- Botón Curaciones -->
                            <div class="col-md-4 col-sm-6" style="padding: 10px;">
                                <button class="btn-enf-card btn-enfRedired1" type="button">
                                    <div class="enf-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);">
                                        <i class="fa fa-heartbeat fa-2x"></i>
                                    </div>
                                    <div class="enf-label">Curaciones</div>
                                </button>
                            </div>
                            
                            <!-- Botón Aplicaciones -->
                            <div class="col-md-4 col-sm-6" style="padding: 10px;">
                                <button class="btn-enf-card btn-enfRedired2" type="button">
                                    <div class="enf-icon" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);">
                                        <i class="fa fa-tint fa-2x"></i>
                                    </div>
                                    <div class="enf-label">Aplicaciones</div>
                                </button>
                            </div>
                            
                            <!-- Botón Cuidados -->
                            <div class="col-md-4 col-sm-6" style="padding: 10px;">
                                <button class="btn-enf-card btn-enfRedired3" type="button">
                                    <div class="enf-icon" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);">
                                        <i class="fa fa-heart fa-2x"></i>
                                    </div>
                                    <div class="enf-label">Cuidados</div>
                                </button>
                            </div>
                            
                            <!-- Botón Evaluaciones -->
                            <div class="col-md-4 col-sm-6" style="padding: 10px;">
                                <button class="btn-enf-card btn-enfRedired4" type="button">
                                    <div class="enf-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                                        <i class="fa fa-clipboard fa-2x"></i>
                                    </div>
                                    <div class="enf-label">Evaluaciones</div>
                                </button>
                            </div>
                            
                            <!-- Botón Registro VM -->
                            <div class="col-md-4 col-sm-6" style="padding: 10px;">
                                <button class="btn-enf-card btn-enfRedired5" type="button">
                                    <div class="enf-icon" style="background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);">
                                        <i class="fa fa-stethoscope fa-2x"></i>
                                    </div>
                                    <div class="enf-label">Registro VM</div>
                                </button>
                            </div>
                            
                            <!-- Botón Próximamente -->
                            <div class="col-md-4 col-sm-6" style="padding: 10px;">
                                <button class="btn-enf-card" type="button" disabled style="opacity: 0.3; cursor: not-allowed;">
                                    <div class="enf-icon" style="background: #ccc;">
                                        <i class="fa fa-plus fa-2x"></i>
                                    </div>
                                    <div class="enf-label">Próximamente</div>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="background-color: #fff; border-top: 1px solid #dee2e6; padding: 15px 30px;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="padding: 10px 25px; border-radius: 6px;">
                            <i class="fa fa-times"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php $oemr_ui->oeBelowContainerDiv(); ?>
    
    <script language="JavaScript" type="text/javascript">
        var webroot_url = <?php echo js_escape($web_root); ?>;
        var encounter;
        var pid_paciente;
        
        var xl_strings_tabs_view_model = <?php echo json_encode(array(
                                                'encounter_locked' => xla('This encounter is locked. No new forms can be added.'),
                                                'must_select_patient'  => $GLOBALS['enable_group_therapy'] ? xla('You must first select or add a patient or therapy group.') : xla('You must first select or add a patient.'),
                                                'must_select_encounter'    => xla('You must first select or create an encounter.'),
                                                'new' => xla('New')
                                            ));
                                            ?>;
        var csrf_token_js = <?php echo js_escape(CsrfUtils::collectCsrfToken()); ?>;
        
        $(function() {
            // Botón ALTA
            $(document).on('click', '.btn-alta', function() {
                let id_encounter = this.id;
                let nombre = $(this).data('title');
                $('#body_alta').show();
                $('#body_death').hide();
                $('#modal_title').html("<b>" + nombre + '</b>');
                $('#paciente_name').html("<b>" + $(this).data('paciente') + '</b>');
                $('#id_encounter').val(id_encounter);
                $('#nombre_paciente').val($(this).data('paciente'));
                $('#modal_alta').modal('toggle');
            });
            
            // Botón ENFERMERÍA
            $(document).on('click', '.btn-Enferm', function() {
                encounter = $(this).data('encounter');
                pid_paciente = $(this).data('pid');
                let nombre_paciente = $(this).data('paciente');
                
                console.log('Abriendo modal enfermería...'); 
                console.log('Encounter:', encounter, 'PID:', pid_paciente);
                
                $('#paciente_nombre_enf').text(nombre_paciente);
                $('#modal_Enf').modal('show');
            });
            
            // Botón ÓBITO
            $(document).on('click', '.btn-death', function() {
                let id_encounter = $(this).data('id');
                let nombre = $(this).data('title');
                $('#death_date').attr('required', true);
                $('#body_alta').hide();
                $('#body_death').show();
                $('#modal_title').html("<b>" + nombre + '</b>');
                $('#paciente_name').html("<b>" + $(this).data('paciente') + '</b>');
                $('#id_encounter').val(id_encounter);
                $('#nombre_paciente').val($(this).data('paciente'));
                $('#modal_alta').modal('toggle');
            });
            
            // Botón EDITAR
            $(document).on('click', '.btn-editar', function() {
                let id_encounter = $(this).data('id');
                top.RTop.location = webroot_url + "/interface/tableros/editar_internado.php?id=" + id_encounter;
            });

            // ========== BOTONES DEL MODAL DE ENFERMERÍA ==========
            
            // Botón 1: CURACIONES
            $(document).on('click', '.btn-enfRedired1', function() {
                console.log('Click en Curaciones, encounter:', encounter, 'pid:', pid_paciente);
                if (!encounter || !pid_paciente) {
                    alert('Error: No se pudo obtener los datos del paciente');
                    return;
                }
                
                $('#modal_Enf').modal('hide');
                
                setTimeout(function() {
                    top.RTop.location = webroot_url + "/interface/forms/curaciones/new.php?mode=new&id=0&pid=" + pid_paciente + "&encounter=" + encounter;
                }, 300);
            });

            // Botón 2: APLICACIONES
            $(document).on('click', '.btn-enfRedired2', function() {
                console.log('Click en Aplicaciones, encounter:', encounter);
                if (!encounter) {
                    alert('Error: No se pudo obtener el ID del encuentro');
                    return;
                }
                $('#modal_Enf').modal('hide');
                setTimeout(function() {
                    top.RTop.location = webroot_url + "/interface/forms/LBF/new.php?formname=LBF_APLICACIONES&visitid=" + encounter + "&inter=1";
                }, 300);
            });

            // Botón 3: CUIDADOS
            $(document).on('click', '.btn-enfRedired3', function() {
                console.log('Click en Cuidados, encounter:', encounter, 'pid:', pid_paciente);
                if (!encounter || !pid_paciente) {
                    alert('Error: No se pudo obtener los datos del paciente');
                    return;
                }
                
                $('#modal_Enf').modal('hide');
                
                setTimeout(function() {
                    top.RTop.location = webroot_url + "/interface/forms/cuidados/new.php?mode=new&id=0&pid=" + pid_paciente + "&encounter=" + encounter;
                }, 300);
            });

            // Botón: EVALUACIONES
            $(document).on('click', '.btn-enfRedired4', function() {
                console.log('Click en Evaluaciones, encounter:', encounter, 'pid:', pid_paciente);
                if (!encounter || !pid_paciente) {
                    alert('Error: No se pudo obtener los datos del paciente');
                    return;
                }
                
                $('#modal_Enf').modal('hide');
                
                setTimeout(function() {
                    top.RTop.location = webroot_url + "/interface/forms/evaluaciones/new.php?mode=new&id=0&pid=" + pid_paciente + "&encounter=" + encounter;
                }, 300);
            });
            // Botón 5: REGISTRO VM
            $(document).on('click', '.btn-enfRedired5', function() {
                console.log('Click en Registro VM, encounter:', encounter);
                if (!encounter) {
                    alert('Error: No se pudo obtener el ID del encuentro');
                    return;
                }
                $('#modal_Enf').modal('hide');
                setTimeout(function() {
                    top.RTop.location = webroot_url + "/interface/forms/LBF/new.php?formname=LBF_REGISTRO_VM&visitid=" + encounter + "&inter=1";
                }, 300);
            });

            // Click en datos del paciente
            $(document).on('click', '.btn-pacienteData', function() {
                let encounter_click = $(this).data('encounter');
                let pid_click = $(this).data('pid');
                top.RTop.location = webroot_url + "/interface/patient_file/encounter/encounter_top.php?set_encounter=" + encounter_click + "&pid=" + pid_click;
            });

            // DataTable
            $(document).ready(function() {
                $('#inp_table thead tr').clone(true).appendTo('#inp_table thead');
                $('#inp_table thead tr:eq(1) th').each(function(i) {
                    var title = $(this).text();
                    if (title.trim() !== 'Acciones') {
                        $(this).html('<input type="text" placeholder="Buscar ' + title + '" title="Ingrese aquí lo que desea buscar"/>');
                    } else {
                        $(this).html('');
                    }

                    $('input', this).on('keyup change', function() {
                        if (datatable.column(i).search() !== this.value) {
                            datatable
                                .column(i)
                                .search(this.value)
                                .draw();
                        }
                    });
                });
                
                const datatable = $('#inp_table').DataTable({
                    order: [
                        [6, "asc"],
                        [7, "asc"],
                    ],
                    responsive: true,
                    orderCellsTop: true,
                    fixedHeader: true
                });
            });
        });
    </script>
    <script>
        document.addEventListener('touchstart', {});
    </script>
</body>
</html>