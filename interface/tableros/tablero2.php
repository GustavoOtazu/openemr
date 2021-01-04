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
    <script src="../../public/assets/canvas/canvasjs.min.js"></script>
    <style type="text/css>">
        .canvasjs-chart-credit{
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
    function showVitals() {
        <?php

        $dataPoints = array();
        $y = 5;
        for($i = 0; $i < 10; $i++){
            $y += rand(-1, 1) * 0.1;
            array_push($dataPoints, array("x" => $i, "y" => $y));
        }
        //array_push($dataPoints, array("x" => $i, "y" => $y));
        $internados_actuales_consult = "SELECT f.pid, CONCAT(CONCAT(p.fname, ' '),p.lname) as paciente, f.cuarto as sala, f.cama as cama from form_encounter as f join patient_data as p on p.pid = f.pid where f.pc_catid = 16 and f.out_date is null order by f.id ASC";
        $res = sqlStatement($internados_actuales_consult);
        $inpatient=[];
        $result=[];
        for ($iter=0; $encounter=sqlFetchArray($res); $iter++)  {
            $sql_vitals = "SELECT p.fname, v.* FROM form_vitals as v JOIN patient_data as p on p.pubpid = v.pid  WHERE v.pid =?  ORDER by v.date ASC";
            $results = sqlStatement($sql_vitals, array($encounter['pid']));
            if ($results) {
                $i = 0;
                $paciente = "";
                $datos_bps =[];
                $datos_bpd =[];
                $datos_temp=[];
                $datos_resp=[];
                $datos_pulso=[];
                $datos_bmi=[];
                $datos_oxy=[];

                $datos_hr=[];
                $datos_vpc=[];
                $datos_lvp_d=[];
                $datos_lvp_s=[];
                $datos_pr_spo2=[];
                $datos_st1=[];
                $datos_st2=[];
                $datos_st3=[];
                $datos_nibps_sys=[];
                $datos_nibps_dys=[];
                $datos =[];
                while($row = sqlFetchArray($results)) {
                    $datos_bps[]=["x"=> (strtotime($row["date"]) *1000), "y"=> $row["bps"]];
                    $datos_bpd[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["bpd"]];
                    $datos_bmi[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["bmi"]];
                    $datos_pulso[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["pulse"]];
                    $datos_resp[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["respiration"]];
                    $datos_temp[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["temperature"]];
                    $datos_oxy[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["oxygen_saturation"]];


                    $datos_hr[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["hr"]];
                    $datos_vpc[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["vpc"]];
                    $datos_lvp_d[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["lvp_d"]];
                    $datos_lvp_s[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["lvp_s"]];
                    $datos_pr_spo2[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["pr_spo2"]];
                    $datos_st1[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["st1"]];
                    $datos_st2[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["st2"]];
                    $datos_st3[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["st3"]];
                    $datos_nibps_sys[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["nibps_sys"]];
                    $datos_nibps_dys[] = ["x"=> (strtotime($row["date"]) *1000), "y"=> $row["nibps_dys"]];
                    $i++;
                }
                if ($i>0) {
                    $result[$encounter['pid']]=[
                        "paciente"=>$encounter['paciente'],
                        "cama"=>$encounter['cama'],
                        "sala"=>$encounter['sala'],
                        "bps"=>json_encode($datos_bps, JSON_NUMERIC_CHECK),
                        "bpd"=>json_encode($datos_bpd, JSON_NUMERIC_CHECK),
                        "pulso"=>json_encode($datos_pulso, JSON_NUMERIC_CHECK),
                        "resp"=>json_encode($datos_resp, JSON_NUMERIC_CHECK),
                        "temp"=>json_encode($datos_temp, JSON_NUMERIC_CHECK),
                        "oxy"=>json_encode($datos_oxy, JSON_NUMERIC_CHECK),


                        "hr"=>json_encode($datos_hr, JSON_NUMERIC_CHECK),
                        "vpc"=>json_encode($datos_vpc, JSON_NUMERIC_CHECK),
                        "lvp_s"=>json_encode($datos_lvp_s, JSON_NUMERIC_CHECK),
                        "lvp_d"=>json_encode($datos_lvp_d, JSON_NUMERIC_CHECK),
                        "pr_spo2"=>json_encode($datos_pr_spo2, JSON_NUMERIC_CHECK),
                        "st1"=>json_encode($datos_st1, JSON_NUMERIC_CHECK),
                        "st2"=>json_encode($datos_st2, JSON_NUMERIC_CHECK),
                        "st3"=>json_encode($datos_st3, JSON_NUMERIC_CHECK),
                        "nibps_sys"=>json_encode($datos_nibps_sys, JSON_NUMERIC_CHECK),
                        "nibps_dys"=>json_encode($datos_nibps_dys, JSON_NUMERIC_CHECK),

                    ];
                }

            }

        }
        ?>
        var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
        let result = <?php echo json_encode($result); ?>;
        let existen_datos=false;
        $('#body-vitals-tab').html('');
        $.each(result, function (pubpid, index) {
            existen_datos = true;
            $('#body-vitals-tab').append('<div class="col-md-6"><div id="chartContainer'+pubpid+'" style="height: 200px; width: 100%;"></div></div>');
            new CanvasJS.Chart("chartContainer"+pubpid,
                {
                    animationEnabled: true,
                    exportEnabled: true,
                    title: {
                        text: 'Sala: '+result[pubpid]['sala']+' Cama: '+result[pubpid]['cama']+' Nro Registro: '+pubpid+'   '+result[pubpid]['paciente']
                    },
                    axisY: {
                        title: "Signo vitales"
                    },
                    legend:{
                        cursor: "pointer",
                        dockInsidePlotArea: false,
                        itemclick: toggleDataSeries
                    },
                    data: [{
                        type: "spline",
                        name:"BPS",
                        showInLegend: true,
                        dataPoints: JSON.parse(result[pubpid]['bps'])
                    },
                        {
                            type: "spline",
                            name:"BPD",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['bpd'])
                        },
                        {
                            type: "spline",
                            name:"Sat. Ox",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['oxy'])
                        },
                        {
                            type: "spline",
                            name:"Pulso",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['pulso'])
                        },
                        {
                            type: "spline",
                            name:"Temperatura",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['temp'])
                        },

                        {
                            type: "spline",
                            name:"HR",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['hr'])
                        },
                        {
                            type: "spline",
                            name:"VPC",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['vpc'])
                        },
                        {
                            type: "spline",
                            name:"HR",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['hr'])
                        },
                        {
                            type: "spline",
                            name:"lvp(s)",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['lvp_s'])
                        },
                        {
                            type: "spline",
                            name:"lvp(d)",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['lvp_s'])
                        },
                        {
                            type: "spline",
                            name:"PR(Sp02)",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['pr_spo2'])
                        },
                        {
                            type: "spline",
                            name:"ST1",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['st1'])
                        },
                        {
                            type: "spline",
                            name:"ST2",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['st2'])
                        },
                        {
                            type: "spline",
                            name:"ST3",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['st3'])
                        },
                        {
                            type: "spline",
                            name:"Nibp(S)",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['nibps_sys'])
                        },
                        {
                            type: "spline",
                            name:"Nibps(D)",
                            showInLegend: true,
                            xValueType: "dateTime",
                            dataPoints: JSON.parse(result[pubpid]['nibps_dys'])
                        }
                    ]
                }
            ).render();
        });
        if (!existen_datos){
            $('#body-vitals-tab').html('');
            $('#body-vitals-tab').html('<div class="alert alert-warning text-center" role="alert">\n' +
                '                <p>- No se encontraron datos -</p>\n' +
                '            </div>');

        }


        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }
    }
    showVitals()
    setInterval(showVitals, 60000);
    $(document).on('click','.vitalsbtn', function () {
        top.RTop.location = "../../patient_file/summary/demographics.php?set_pid=" + $(this).data('pid') +'&goto-vitals=vitals';
    });


</script>
<script >

</script>
<!-- END (CHEMED) -->


<noframes><body bgcolor="#FFFFFF">
    <?php echo xlt('Frame support required'); ?>
    </body></noframes>

</HTML>
