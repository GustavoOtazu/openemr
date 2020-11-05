<?php



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
    <script src="../../public/assets/knob/jquery.knob.min.js"></script>
    <script src="../../public/assets/canvas/canvasjs.min.js"></script>
</HEAD>
<body class="body_top">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header clearfix">
                <div class="row" id="body-vitals-tab">
                    <div class="alert alert-warning text-center" role="alert">
                        <p>- No se encontraron datos -</p>
                    </div>
                </div>
            </div>
        </div>
</div>
</body>

<!-- (CHEMED) -->
<script type='text/javascript' language='JavaScript'>
    function showVitals() {

        var xmlhttp = new XMLHttpRequest();
        let vitals_show={};
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let resp = JSON.parse(this.responseText);
                if (resp.length > 0){
                    $('#body-vitals-tab').html('');
                }
                for ( element in resp) {
                    let paciente=resp[element]["paciente"];
                    let bps= resp[element]["bps"];
                    let bpd=resp[element]["bpd"];
                    let temperatura=resp[element]["temperatura"];
                    let respiracion=resp[element]["respiracion"];
                    let hora=resp[element]["date"];
                    let pid=resp[element]["pid"];
                    let pulse=resp[element]["pulse"];
                    let oxygen_saturation=resp[element]["oxygen_saturation"];
                    let bmi=resp[element]["BMI"];
                    let display_bbps = localStorage.getItem('bps-check'+pid) !== null ? localStorage.getItem('bps-check'+pid) : true;
                    let display_bbpd = localStorage.getItem('bpd-check'+pid) !== null ? localStorage.getItem('bpd-check'+pid) : true;
                    let display_temperature = localStorage.getItem('temperature-check'+pid) !== null ? localStorage.getItem('temperature-check'+pid) : true;
                    let display_respiracion = localStorage.getItem('respiracion-check'+pid) !== null ? localStorage.getItem('respiracion-check'+pid) : true;
                    let display_pulse = localStorage.getItem('pulse-check'+pid) !== null ? localStorage.getItem('pulse-check'+pid) : true;
                    let display_oxygen_saturation = localStorage.getItem('oxygen_saturation-check'+pid) !== null ? localStorage.getItem('oxygen_saturation-check'+pid) : true;
                    $('#body-vitals-tab').append('<div class="col-md-12">' +
                        '<div class="row"><h4 class="text-left">Paciente: '+paciente+" - Último signo recibido a las: "+hora+'</h4>' +
                        '<a class="btn btn-primary vitalsbtn" href="javascript:void(0)" title="Ir al Paciente" data-pid="'+pid+'"><i class="fa fa-eye"></i></a>' +
                        '<button title="Seleccionar signos vitales a visualizar" data-id="list_sv'+pid+'" class="btn show-list btn-success"><i class="fa fa-caret-down" aria-hidden="true"></i></button></div>\n' +
                        '                <div class="row">\n' +

                        '<div class="col-xs-6 col-md-3" style="display: none" id="list_sv'+pid+'">\n' +
                        '<ul style="list-style-type:none">' +
                        '<li><input type="checkbox" class="sv-item" id="bps-check'+pid+'"  name="bps-check'+pid+'" data-value="bps'+pid+'"  '+((display_bbps===true || display_bbps==='true')? "checked":"")   +'  >\n' +
                        '<label for="bps-check'+pid+'"> Presión arterial sistólica</label><br></li>' +
                        '<li><input type="checkbox" class="sv-item" '+( (display_bbpd===true || display_bbpd==='true') ? "checked":"")  +' id="bpd-check'+pid+'"  name="bpd-check'+pid+'" data-value="bpd'+pid+'">\n' +
                        '<label for="bpd-check'+pid+'"> Presión arterial diastólica</label><br></li>' +
                        '<li><input type="checkbox" class="sv-item" '+( (display_temperature===true || display_temperature==='true')? "checked": "") +' id="temperature-check'+pid+'"  name="temperature-check'+pid+'" data-value="temperatura'+pid+'">\n' +
                        '<label for="temperature-check'+pid+'"> Temperatura</label><br></li>' +
                        '<li><input type="checkbox" class="sv-item" '+((display_respiracion===true || display_respiracion==='true')? "checked":"")+' id="respiracion-check'+pid+'"  name="respiracion-check'+pid+'" data-value="respiracion'+pid+'">\n' +
                        '<label for="respiracion-check'+pid+'"> Respiración</label><br></li>' +
                        '<li><input type="checkbox" class="sv-item" '+((display_pulse===true || display_pulse==='true')?"checked":"")+' id="pulse-check'+pid+'"  name="pulse-check'+pid+'" data-value="pulse'+pid+'">\n' +
                        '<label for="pulse-check'+pid+'"> Pulso</label><br></li>' +
                        '<li><input type="checkbox" class="sv-item"  '+((display_oxygen_saturation===true || display_oxygen_saturation==='true')? "checked":"")+' id="oxygen_saturation-check'+pid+'"  name="oxygen_saturation-check'+pid+'" data-value="oxygen_saturation'+pid+'">\n' +
                        '<label for="oxygen_saturation-check'+pid+'"> Sat. de Oxígeno</label><br></li>' +
                        '</ul>'+
                        '                    </div>\n' +


                        '                    <div class="col-xs-6 col-md-2 text-center '+((display_bbps===true || display_bbps==='true')? "":"hidden")+'"  id="bps'+pid+'">\n' +
                        '                        <input type="text" class="knob" value="'+bps+'"  data-width="100" data-height="100"  data-max="1000" data-fgColor="#3c8dbc" readonly>\n' +
                        '\n' +
                        '                        <div class="knob-label">Presión arterial sistólica</div>\n' +
                        '                    </div>\n' +
                        '                    <!-- ./col -->\n' +
                        '                    <div class="col-xs-6 col-md-2 text-center '+((display_bbpd===true || display_bbpd==='true')? "":"hidden")+'" id="bpd'+pid+'">\n' +
                        '                        <input type="text" class="knob" value="'+bpd+'" data-width="100" data-height="100"  data-max="1000" data-fgColor="#f56954" readonly>\n' +
                        '\n' +
                        '                        <div class="knob-label">Presión arterial diastólica</div>\n' +
                        '                    </div>\n' +
                        '                    <!-- ./col -->\n' +
                        '                    <div class="col-xs-6 col-md-2 text-center '+((display_temperature===true || display_temperature==='true')? "":"hidden")+'" id="temperatura'+pid+'">\n' +
                        '                        <input type="text" class="knob" value="'+temperatura+'"  data-width="100" data-height="100" data-max="1000" readonly data-fgColor="#00a65a">\n' +
                        '\n' +
                        '                        <div class="knob-label">Temperatura</div>\n' +
                        '                    </div>\n' +
                        '                    <!-- ./col -->\n' +
                        '                    <div class="col-xs-6 col-md-2 text-center '+((display_respiracion===true || display_respiracion==='true')? "":"hidden")+' " id="respiracion'+pid+'">\n' +
                        '                        <input type="text" class="knob" value="'+respiracion+'" data-width="100" data-height="100" data-max="1000" data-fgColor="#00c0ef" readonly>\n' +
                        '\n' +
                        '                        <div class="knob-label">Respiración</div>\n' +
                        '                    </div>\n' +
                        '                    <div class="col-xs-6 col-md-2 text-center  '+((display_pulse===true || display_pulse==='true')? "":"hidden")+'" id="pulse'+pid+'">\n' +
                        '                        <input type="text" class="knob" value="'+pulse+'" data-width="100" data-height="100" data-max="1000" data-fgColor="#1CEB05" readonly>\n' +
                        '\n' +
                        '                        <div class="knob-label">Pulso</div>\n' +
                        '                    </div>\n' +
                        '                    <div class="col-xs-6 col-md-2 text-center '+((display_oxygen_saturation===true|| display_oxygen_saturation==='true')? "":"hidden")+' " id="oxygen_saturation'+pid+'">\n' +
                        '                        <input type="text" class="knob" value="'+oxygen_saturation+'" data-width="100" data-height="100" data-max="1000" data-fgColor="#DEC112" readonly>\n' +
                        '\n' +
                        '                        <div class="knob-label">Saturación de oxígeno</div>\n' +
                        '                    </div>\n' +
                        '                </div></div><hr style="border-top: 1px solid black;">');
                }
                $('.knob').knob();
            }
        }
        xmlhttp.open("GET", "listar_vitales.php?", true);
        xmlhttp.send();
    }
    showVitals()
    setInterval(showVitals, 60000);
    $(document).on('click','.vitalsbtn', function () {
        top.RTop.location = "<?php echo $GLOBALS['webroot'] ?>"+"/interface/patient_file/summary/demographics.php?set_pid=" + $(this).data('pid') +'&goto-vitals=vitals';
    });
    $(document).on('click','.show-list',function () {
        showVitalsList('#'+$(this).data('id'))
    });
    function showVitalsList(id) {
        if ($(id).is(":visible")){
            $(id).hide();
        }else{
            $(id).css('display','block');
        }
    }
    $(document).on('click','.sv-item',function () {
        let check = $(this).prop('checked')
        showVitalItem('#'+$(this).data('value'), check,$(this).attr('id'))
    });
    function showVitalItem(id,check, input) {
        if (check){
            if (localStorage.getItem(input) != null){
                localStorage.removeItem(input);
            }
            localStorage.setItem(input, check);
            $(id).removeClass('hidden');
            $(id).css('display','block');
        }else{
            if (localStorage.getItem(input) != null){
                localStorage.removeItem(input);
            }
            localStorage.setItem(input, false);
            $(id).hide();
        }
    }
</script>
<script >

</script>
<!-- END (CHEMED) -->


<noframes><body bgcolor="#FFFFFF">
    <?php echo xlt('Frame support required'); ?>
    </body></noframes>

</HTML>
