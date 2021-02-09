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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>

<HEAD>
    <TITLE><?php echo xlt('Tablero'); ?></TITLE>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js"></script>
    <link rel=stylesheet href="../../public/themes/style_light.css">
    <link rel="stylesheet" href="../../public/assets/bootstrap/dist/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../../public/assets/jquery-ui/jquery-ui.css" type="text/css">
    <script type="text/javascript" src="../../public/assets/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="../../public/assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../public/assets/font-awesome/css/font-awesome.min.css" type="text/css">
    <link rel="shortcut icon" href="../../public/images/favicon.ico" />
    <script type="text/javascript" src="../../public/assets/jquery-ui/jquery-ui.js"></script>
    <script type="text/javascript" src="../../public/assets/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="../../public/assets/select2/dist/css/select2.min.css" type="text/css">
    <script src="../../public/assets/canvas/canvasjs.min.js"></script>
    <script src="../../public/assets/moment/moment.js"></script>
    <script src="../../public/assets/moment/locale/es.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style type="text/css>">
        .canvasjs-chart-credit {
            display: none !important;
        }
    </style>
</HEAD>

<body class="body_top">
    <div class="row" style="margin: 10px">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <div class="box-body">
                    <div class="row" style="margin-bottom: 5%;">
                        <div class="col-md-12">
                            <h4 class="text-center">Filtros</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2 oe-text-to-right" for="sala">Sala</label>
                                        <div class="col-sm-8">
                                            <select class="form-control col-sm-9" name="sala" id="sala" multiple="">
                                                <option value="A">Sala A</option>
                                                <option value="B">Sala B</option>
                                                <option value="C">Sala C</option>
                                                <option value="D">Sala D</option>
                                                <option value="E">Sala E</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2 oe-text-to-right" for="cama">Camas</label>
                                        <div class="col-sm-8">
                                            <select class="form-control col-sm-9" name="cama" id="cama" multiple="">
                                                <option value="1">Cama 1</option>
                                                <option value="2">Cama 2</option>
                                                <option value="3">Cama 3</option>
                                                <option value="4">Cama 4</option>
                                                <option value="5">Cama 5</option>
                                                <option value="6">Cama 6</option>
                                                <option value="7">Cama 7</option>
                                                <option value="8">Cama 8</option>
                                                <option value="9">Cama 9</option>
                                                <option value="10">Cama 10</option>
                                                <option value="11">Cama 11</option>
                                                <option value="12">Cama 12</option>
                                                <option value="13">Cama 13</option>
                                                <option value="14">Cama 14</option>
                                                <option value="15">Cama 15</option>
                                                <option value="16">Cama 16</option>
                                                <option value="17">Cama 17</option>
                                                <option value="18">Cama 18</option>
                                                <option value="19">Cama 19</option>
                                                <option value="20">Cama 1</option>
                                                <option value="21">Cama 21</option>
                                                <option value="22">Cama 22</option>
                                                <option value="23">Cama 23</option>
                                                <option value="24">Cama 24</option>
                                                <option value="25">Cama 25</option>
                                                <option value="26">Cama 26</option>
                                                <option value="27">Cama 27</option>
                                                <option value="28">Cama 28</option>
                                                <option value="29">Cama 29</option>
                                                <option value="30">Cama 30</option>
                                                <option value="31">Cama 31</option>
                                                <option value="32">Cama 32</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top: 2%;">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2 oe-text-to-right">Rango de fechas</label>
                                        <div class="col-sm-8">
                                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                <i class="fa fa-calendar"></i>&nbsp;
                                                <span></span> <i class="fa fa-caret-down"></i>
                                                <input type="hidden" name="inicio" id="inicio">
                                                <input type="hidden" name="fin" id="fin">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px" id="body-vitals-tab">
                        <div class="alert alert-warning text-center col-md-12" role="alert">
                            <p>- No se encontraron datos -</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

<!-- (CHEMED) -->


<script>
    let salas = [];
    let camas = [];

    function showVitals() {

        var xmlhttp = new XMLHttpRequest();
        let result = [];
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                result = $.parseJSON(this.responseText)
                $('#body-vitals-tab').html('');
                let existen_datos = false;
                $.each(result, function(pubpid, index) {
                    let mostrar = false;
                    if (camas.length === 0 && salas.length === 0) {
                        mostrar = true;
                    }
                    if (salas.length > 0) {
                        if (salas.includes(result[pubpid]['sala'].toUpperCase())) {
                            if (camas.length > 0) {
                                if (camas.includes(result[pubpid]['cama'])) {
                                    mostrar = true;
                                } else {
                                    mostrar = false;
                                }
                            } else {
                                mostrar = true;
                            }
                        }
                    } else {
                        if (camas.length > 0) {
                            if (camas.includes(result[pubpid]['cama'])) {
                                mostrar = true;
                            } else {
                                mostrar = false;
                            }
                        }
                    }

                    if (mostrar == true) {
                        existen_datos = true;
                        $('#body-vitals-tab').append('<div class="col-md-6"><div id="chartContainer' + pubpid + '" style="height: 200px; width: 100%;"></div></div>');
                        new CanvasJS.Chart("chartContainer" + pubpid, {
                            animationEnabled: true,
                            exportEnabled: true,
                            title: {
                                fontFamily: "tahoma",
                                text: 'Sala: ' + result[pubpid]['sala'] + ' Cama: ' + result[pubpid]['cama'] + ' Nro Registro: ' + pubpid + '   ' + result[pubpid]['paciente']
                            },
                            axisY: {
                                title: "Signo vitales"
                            },
                            legend: {
                                cursor: "pointer",
                                dockInsidePlotArea: false,
                                itemclick: toggleDataSeries
                            },
                            data: [{
                                    type: "spline",
                                    name: "BPS",
                                    showInLegend: true,
                                    dataPoints: JSON.parse(result[pubpid]['bps'])
                                },
                                {
                                    type: "spline",
                                    name: "BPD",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['bpd'])
                                },
                                {
                                    type: "spline",
                                    name: "Sat. Ox",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['oxy'])
                                },
                                {
                                    type: "spline",
                                    name: "Pulso",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['pulso'])
                                },
                                {
                                    type: "spline",
                                    name: "Temperatura",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['temp'])
                                },

                                {
                                    type: "spline",
                                    name: "HR",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['hr'])
                                },
                                {
                                    type: "spline",
                                    name: "VPC",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['vpc'])
                                },
                                {
                                    type: "spline",
                                    name: "HR",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['hr'])
                                },
                                {
                                    type: "spline",
                                    name: "lvp(s)",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['lvp_s'])
                                },
                                {
                                    type: "spline",
                                    name: "lvp(d)",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['lvp_s'])
                                },
                                {
                                    type: "spline",
                                    name: "PR(Sp02)",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['pr_spo2'])
                                },
                                {
                                    type: "spline",
                                    name: "ST1",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['st1'])
                                },
                                {
                                    type: "spline",
                                    name: "ST2",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['st2'])
                                },
                                {
                                    type: "spline",
                                    name: "ST3",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['st3'])
                                },
                                {
                                    type: "spline",
                                    name: "Nibp(S)",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['nibps_sys'])
                                },
                                {
                                    type: "spline",
                                    name: "Nibps(D)",
                                    showInLegend: true,
                                    xValueType: "dateTime",
                                    dataPoints: JSON.parse(result[pubpid]['nibps_dys'])
                                }
                            ]
                        }).render();
                    }
                });
                if (!existen_datos) {
                    $('#body-vitals-tab').html('');
                    $('#body-vitals-tab').html('<div class="alert alert-warning text-center" role="alert">\n' +
                        '                <p>- No se encontraron datos -</p>\n' +
                        '            </div>');

                }


                function toggleDataSeries(e) {
                    if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                        e.dataSeries.visible = false;
                    } else {
                        e.dataSeries.visible = true;
                    }
                    e.chart.render();
                }
            }
        };
        let inicio = $('#inicio').val();
        let fin = $('#fin').val()
        xmlhttp.open("GET", "list_tablero2.php?inicio="+inicio+"&fin="+fin, true);
        xmlhttp.send();


    }

    $(document).on('click', '.vitalsbtn', function() {
        top.RTop.location = "../../patient_file/summary/demographics.php?set_pid=" + $(this).data('pid') + '&goto-vitals=vitals';
    });
    $(document).ready(function() {
        moment.locale('es');
        var start = moment().locale('es');
        var end = moment().locale('es');
        cb(start, end)

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#inicio').val(start.format('YYYY-MM-DD'));
            $('#fin').val(end.add(1, 'days').format('YYYY-MM-DD'));
            showVitals()
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                "daysOfWeek": [
                    "Dom",
                    "Lun",
                    "Ma",
                    "Mie",
                    "Jue",
                    "Vie"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                applyLabel: "Aceptar",
                cancelLabel: "Cancelar",
                weekLabel: "w",
                customRangeLabel: "Seleccione un Rango"
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        showVitals()
        setInterval(showVitals, 60000);
        $('#sala').select2({
            placeholder: 'Seleccione una o más opciones'
        }).on("select2:select select2:unselect", function(e) {
            //this returns all the selected item
            var items = $(this).val();
            salas = items;
            showVitals()

        });
        $('#cama').select2({
            placeholder: 'Seleccione una o más opciones'
        }).on("select2:select select2:unselect", function(e) {

            var items = $(this).val();
            camas = items;
            showVitals()

        });
    })
</script>
<script>

</script>
<!-- END (CHEMED) -->


<noframes>

    <body bgcolor="#FFFFFF">
        <?php echo xlt('Frame support required'); ?>
    </body>
</noframes>

</HTML>