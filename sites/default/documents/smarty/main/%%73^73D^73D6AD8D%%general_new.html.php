<?php /* Smarty version 2.6.31, created on 2021-02-09 21:59:29
         compiled from C:%5Cxampp%5Chtdocs%5Copenemr_empty%5Cinterface%5Cforms%5Cvitals/templates/vitals/general_new.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'headerTemplate', 'C:\\xampp\\htdocs\\openemr_empty\\interface\\forms\\vitals/templates/vitals/general_new.html', 12, false),array('function', 'xla', 'C:\\xampp\\htdocs\\openemr_empty\\interface\\forms\\vitals/templates/vitals/general_new.html', 23, false),array('function', 'xlj', 'C:\\xampp\\htdocs\\openemr_empty\\interface\\forms\\vitals/templates/vitals/general_new.html', 93, false),array('modifier', 'date_format', 'C:\\xampp\\htdocs\\openemr_empty\\interface\\forms\\vitals/templates/vitals/general_new.html', 77, false),array('modifier', 'js_escape', 'C:\\xampp\\htdocs\\openemr_empty\\interface\\forms\\vitals/templates/vitals/general_new.html', 77, false),)), $this); ?>
<html>
<head>
    <?php echo smarty_function_headerTemplate(array('assets' => 'datetime-picker'), $this);?>



    <title>Signos vitales   </title>
</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
">

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-header">
                <h2>Signos vitales de&nbsp;&nbsp;<strong id="patient-name"></strong> <a  href="../summary/demographics.php" onclick="top.restoreSession()" title="<?php echo smarty_function_xla(array('t' => 'Back to patient dashboard'), $this);?>
"><i id="advanced-tooltip" class="readonly fa fa-arrow-circle-o-left fa-2x small" aria-hidden="true"></i> </a></h2>
            </div>
        </div>
        <div class="col-md-6">
            <label>Rango de fechas</label>
            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
                <input type="hidden" name="inicio" id="inicio">
                <input type="hidden" name="fin" id="fin">
            </div>
        </div>
        <div class="col-md-6">
            <label for="unidades">Unidades</label>
            <select id="unidades" class="form-control">
                <option value="hr">Ritmo cardiaco</option>
                <option value="vpc">Contracciones ventriculares prematuras</option>
                <option value="lvp_s">Pr. ventricular izq sis</option>
                <option value="lvp_d"> Pr. ventricular izq diast</option>
                <option value="pr_spo2">Frec. del pulso por sat. de oxígeno</option>
                <option value="nibps_sys"> Presión No Invasiva sis</option>
                <option value="nibps_dys"> Presión No Invasiva Dis</option>


                <option value="height">Altura	(cm)</option>
                <option value="bps">BP Sistólica	(mmHg)</option>
                <option value="bpd">BP Diastólica	(mmHg)</option>
                <option value="pulse">Pulso	(per min)</option>
                <option value="respiration">
                    Respiración	(per min)</option>
                <option value="temperature">Temperatura	C</option>
                <option value="oxygen_saturation">Saturación de oxígeno	%</option>
                <option value="BMI"> BMI	kg/m^2	</option>
            </select>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-sm-12 col-sm-offset-3 col-md-12">
            <div id="graphdiv2" style="width:800px; height:700px;">

            </div>
        </div>
    </div>
</div>
</body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dygraph/2.1.0/dygraph.css" integrity="sha512-QG68tUGWKc1ItPqaThfgSFbubTc+hBv4OW/4W1pGi0HHO5KmijzXzLEOlEbbdfDtVT7t7mOohcOrRC5mxKuaHA==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dygraph/2.1.0/dygraph.min.js" integrity="sha512-opAQpVko4oSCRtt9X4IgpmRkINW9JFIV3An2bZWeFwbsVvDxEkl4TEDiQ2vyhO2TDWfk/lC+0L1dzC5FxKFeJw==" crossorigin="anonymous"></script>
<script src="../../../public/assets/moment/moment.js"></script>
<script src="../../../public/assets/moment/locale/es.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script language="javascript">
    var formdate = <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['vitals']->get_date())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y%m%d") : smarty_modifier_date_format($_tmp, "%Y%m%d")))) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
    var patientdata = <?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_patient())) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
    // vitals array elements are in the format:
    //   date-height-weight-head_circumference
    var vitals = new Array();
    // get values from the current form elements
    vitals[0] = formdate + '-' + <?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_height())) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
 + '-' + <?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_weight())) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
 + '-' + <?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_head_circ())) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
    // historic values
    <?php $_from = $this->_tpl_vars['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    vitals[vitals.length] = <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['result']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y%m%d") : smarty_modifier_date_format($_tmp, "%Y%m%d")))) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
 + '-' + <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['height'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
 + '-' + <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['weight'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
 + '-' + <?php echo ((is_array($_tmp=$this->_tpl_vars['result']['head_circ'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
    <?php endforeach; endif; unset($_from); ?>
        var patientAge= <?php echo ((is_array($_tmp=$this->_tpl_vars['patient_age'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
        var patient_dob= <?php echo ((is_array($_tmp=$this->_tpl_vars['patient_dob'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
        var webroot = <?php echo ((is_array($_tmp=$this->_tpl_vars['FORM_ACTION'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
        var pid = <?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_pid())) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
        var cancellink = <?php echo ((is_array($_tmp=$this->_tpl_vars['DONT_SAVE_LINK'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
        var birth_xl= <?php echo smarty_function_xlj(array('t' => "Birth-24 months"), $this);?>
;
        var older_xl= <?php echo smarty_function_xlj(array('t' => "2-20 years"), $this);?>
;

        <?php echo '
        $(\'#patient-name\').html(patientdata);
        $(function() {

            var start = moment();
            var end = moment();
            cb(start, end)
            var refreshIntervalId = setInterval(graficar, 60000)
            function cb(start, end) {
                $(\'#reportrange span\').html(start.format(\'MMMM D, YYYY\') + \' - \' + end.format(\'MMMM D, YYYY\'));
                $(\'#inicio\').val(start.format(\'YYYY-MM-DD\'));
                $(\'#fin\').val(end.add(1, \'days\').format(\'YYYY-MM-DD\'));
                graficar()

            }
            function graficar() {
                var xmlhttp = new XMLHttpRequest();
                var inicio = $(\'#inicio\').val();
                var fin = $(\'#fin\').val();
                var unidad = $(\'#unidades\').children(\'option:selected\').val();
                var values = [];
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        let resp = JSON.parse(this.responseText);
                        for (element in resp) {
                            values.push(
                                [new Date(resp[element]["date"]), parseFloat(resp[element][unidad])]
                            );
                        }
                        if (values.length>0){
                            new Dygraph(document.getElementById("graphdiv2"),

                                values
                                ,
                                {
                                    labels: ["A", "B"],
                                });
                        }else{ $(\'#graphdiv2\').html(\'<p>No cuenta con datos para la fecha ingresada</p>\') }
                    }
                }
                xmlhttp.open("GET", "vitales.php?pid=" + pid + "&inicio=" + inicio + "&fin=" + fin + "&unidad=" + unidad, true);
                xmlhttp.send();
            }


            $(\'#reportrange\').daterangepicker({
                startDate: start,
                endDate: end,
                locale:{
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
                    applyLabel:"Aceptar",
                    cancelLabel:"Cancelar",
                    weekLabel:"w",
                    customRangeLabel:"Seleccione un Rango"
                },
                ranges: {
                    \'Hoy\': [moment(), moment()],
                    \'Ayer\': [moment().subtract(1, \'days\'), moment().subtract(1, \'days\')],
                    \'Últimos 7 días\': [moment().subtract(6, \'days\'), moment()],
                    \'Últimos 30 días\': [moment().subtract(29, \'days\'), moment()],
                    \'Este mes\': [moment().startOf(\'month\'), moment().endOf(\'month\')],
                    \'El mes pasado\': [moment().subtract(1, \'month\').startOf(\'month\'), moment().subtract(1, \'month\').endOf(\'month\')]
                }
            }, cb);
            $(\'#unidades\').change(function () {
                graficar();
            });


        });
</script>
'; ?>


</html>
