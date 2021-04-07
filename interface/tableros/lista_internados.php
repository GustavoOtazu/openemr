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
/*Extraer todos los internados actuales, tabla: form_encounter, con tipo Internación pc_catid = 16 (referencia tabla: openemr_postcalendar_categories	)*/

$id_encounter = $_GET['id_encounter'];
$nombre_paciente = $_GET['paciente'];
$death_date = $_GET['death_date'];
$update = $_GET['update'];
if ($death_date) {
    sqlStatement("UPDATE form_encounter set out_date= ? , death_date= ? where id = ?", array($death_date, $death_date, $id_encounter));
    $id_encounter = null;
} elseif ($id_encounter) {
    sqlStatement("UPDATE form_encounter set out_date= DATE(NOW()) where id = ?", array($id_encounter));
}
$internados_actuales_consult = "SELECT f.*, CONCAT(CONCAT(p.fname, ' '),p.lname) as paciente, p.pubpid as pubpid from form_encounter as f join patient_data as p on p.pid = f.pid where f.pc_catid = 16 and f.out_date is null";
$res = sqlStatement($internados_actuales_consult);
$inpatient = [];
for ($iter = 0; $row = sqlFetchArray($res); $iter++) {
    $inpatient[$iter] = $row;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>

<HEAD>
    <?php Header::setupHeader(['no_bootstrap']); ?>
    <TITLE><?php echo xlt('Internados'); ?></TITLE>
    <link rel=stylesheet href="../../public/themes/style_light.css">
    <link rel="stylesheet" href="../../public/assets/bootstrap/dist/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../../public/assets/jquery-ui/jquery-ui.css" type="text/css">
    <script type="text/javascript" src="../../public/assets/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="../../public/assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../public/assets/font-awesome/css/font-awesome.min.css" type="text/css">
    <link rel="shortcut icon" href="../../public/images/favicon.ico" />
    <script type="text/javascript" src="../../public/assets/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="../../public/assets/datatable-last/jquery.dataTables.min.css" type="text/css">
    <!-- <link rel="stylesheet" href="../../public/assets/datatable-last/searchPanes.dataTables.min.css" type="text/css"> -->
    <script type="text/javascript" src="../../public/assets/datatable-last/jquery.dataTables.min.js"></script>
    <!-- <script type="text/javascript" src="../../public/assets/datatable-last/dataTables.searchPanes.min.js"></script> -->
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
    </style>
    <?php
    $arrOeUiSettings = array(
        'heading_title' => xl('Patient Finder'),
        'include_patient_name' => false,
        'expandable' => true,
        'expandable_files' => array('dynamic_finder_xpd'), //all file names need suffix _xpd
        'action' => "search", //conceal, reveal, search, reset, link or back
        'action_title' => "", //only for action link, leave empty for conceal, reveal, search
        'action_href' => "", //only for actions - reset, link or back
        'show_help_icon' => false,
        'help_file_name' => ""
    );
    $oemr_ui = new OemrUI($arrOeUiSettings);
    ?>
</HEAD>

<body class="body_top">
    <div id="container" class="<?php echo attr($oemr_ui->oeContainer()); ?>" style="width: 90%;">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header clearfix">
                    <h2>
                        Lista de Internados
                    </h2>
                    <br />
                    <?php if ($id_encounter != null) { ?>
                        <div class="alert alert-success alert-dismissible show" role="alert">
                            Se dió de alta al paciente <strong> <?php echo $nombre_paciente ?></strong> con éxito!
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
                    $_GET['id_encounter'] = null;
                    $_GET['death_date'] = null;
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
                                <th class="head">
                                    Identificador
                                </th>
                                <th class="head">
                                    Paciente
                                </th>
                                <th class="head">
                                    Fecha de Ingreso
                                </th>
                                <th class="head">
                                    CI (RG Paciente)
                                </th>
                                <th class="head">
                                    NRO Prontuario
                                </th>
                                <th class="head">
                                    Servicio
                                </th>
                                <th class="head">
                                    Sala
                                </th>
                                <th class="head">
                                    Cama
                                </th>
                                <th class="head">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($inpatient)) {
                                foreach ($inpatient as $index => $result) {
                                    echo '<tr>' .
                                        '<td>' . text($result['pid']) . '</td>' .
                                        '<td>' . text($result['paciente']) . '</td>' .
                                        '<td>' . date('d/m/Y', strtotime($result['date'])) . '</td>' .
                                        '<td>' . text($result['pubpid']) . '</td>' .
                                        '<td>' . text('-') . '</td>' .
                                        '<td>' . text(strtoupper($result['servicio'])) . '</td>' .
                                        '<td>' . text(strtoupper($result['cuarto'])) . '</td>' .
                                        '<td>' . text($result['cama']) . '</td>' .
                                        '<td>
<button class="btn btn-info btn-editar" type="button" data-id="' . attr($result['id']) . '">EDIT</button>
<button class="btn btn-default btn-alta" type="button" id="' . attr($result['id']) . '" data-title="Dar de alta al paciente: ' . $result['paciente'] . '" data-paciente="' . $result['paciente'] . '">ALTA</button>
<button class="btn btn-danger btn-death" type="button" data-id="' . attr($result['id']) . '" data-title="Registrar muerte del paciente: ' . $result['paciente'] . '" data-paciente="' . $result['paciente'] . '">OBITO</button>
</td>' .
                                        '</tr>';
                                }
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="head">
                                    Identificador
                                </th>
                                <th class="head">
                                    Paciente
                                </th>
                                <th class="head">
                                    Fecha de Ingreso
                                </th>
                                <th class="head">
                                    CI (RG Paciente)
                                </th>
                                <th class="head">
                                    NRO Prontuario
                                </th>
                                <th class="head">
                                    Servicio
                                </th>
                                <th class="head">
                                    Sala
                                </th>
                                <th class="head">
                                    Cama
                                </th>
                                <th class="head">
                                    Acciones
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
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
    </div>
    <?php $oemr_ui->oeBelowContainerDiv(); ?>
    <script language="JavaScript" type="text/javascript">
        var webroot_url = <?php echo js_escape($web_root); ?>;
        var xl_strings_tabs_view_model = <?php echo json_encode(array(
                                                'encounter_locked' => xla('This encounter is locked. No new forms can be added.'),
                                                'must_select_patient'  => $GLOBALS['enable_group_therapy'] ? xla('You must first select or add a patient or therapy group.') : xla('You must first select or add a patient.'),
                                                'must_select_encounter'    => xla('You must first select or create an encounter.'),
                                                'new' => xla('New')
                                            ));
                                            ?>;
        // Set the csrf_token_js token that is used in the below js/tabs_view_model.js script
        var csrf_token_js = <?php echo js_escape(CsrfUtils::collectCsrfToken()); ?>;
        $(function() {

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
            $(document).on('click', '.btn-editar', function() {
                let id_encounter = $(this).data('id');
                top.RTop.location = "<?php echo $GLOBALS['webroot'] ?>" + "/interface/tableros/editar_internado.php?id=" + id_encounter;
            });
            $(document).ready(function() {
                $('#inp_table thead tr').clone(true).appendTo('#inp_table thead');
                $('#inp_table thead tr:eq(1) th').each(function(i) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Buscar ' + title + '"  title="Ingrese aquí lo que desea buscar"/>');

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
                        [1, "asc"],

                    ],
                    responsive: true,
                    orderCellsTop: true,
                    fixedHeader: true
                });
                


            })
        });
    </script>
    <script>
        document.addEventListener('touchstart', {});
    </script>
</body>