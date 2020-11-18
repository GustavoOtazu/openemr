<?php
require_once("../globals.php");
/*Extraer todos los internados actuales, tabla: form_encounter, con tipo Internación pc_catid = 16 (referencia tabla: openemr_postcalendar_categories	)*/
$internados_actuales_consult = "SELECT f.pid, CONCAT(CONCAT(p.fname, ' '),p.lname) as paciente from form_encounter as f join patient_data as p on p.pid = f.pid where f.pc_catid = 16 and f.out_date is null order by f.id ASC";
$res = sqlStatement($internados_actuales_consult);
$inpatient=[];
$result=[];
for ($iter=0; $row=sqlFetchArray($res); $iter++) {
    /*$sql="INSERT INTO `form_vitals` ( `date`, `pid`, `user`, `groupname`, `authorized`, `activity`, `bps`, `bpd`, `weight`, `height`, `temperature`, `temp_method`, `pulse`, `respiration`, `note`, `BMI`, `BMI_status`, `waist_circ`, `head_circ`, `oxygen_saturation`, `external_id`) VALUES";
    $sql.=       "(now(), 4722837, 'Administrador', 'Default', 0, 1, '".round(rand(1,10))."', '".rand(10,568)."', '".round(rand(1,90))."', '".rand(0,10)."', '".rand(1,100)."', 'Oral', '".rand(0,60)."', '".rand(0,10)."', 'holis', '".rand(30,400)."', 'Obesity III', 5.00, 5.00, 50.00, NULL)";
    $sql.=       ",(now(), 4171144, 'Administrador', 'Default', 0, 1, '".round(rand(1,10))."', '".rand(10,568)."', '".round(rand(1,90))."', '".rand(0,10)."', '".rand(1,100)."', 'Oral', '".rand(0,60)."', '".rand(0,10)."', 'holis', '".rand(30,400)."', 'Obesity III', 5.00, 5.00, 50.00, NULL)";
    sqlStatement($sql);*/
    //encontrar el ultimo form_vitals insertado para este pid y mostrar
    $vital_sql = "SELECT * from form_vitals where  pid = ? order by DATE desc limit 1";
    $vitals = sqlQuery($vital_sql, array($row['pid']));
    if ($vitals) {
        $result[] = [
            "paciente"=>$row['paciente'],
            "bps"=> $vitals["bps"], //blood pressure systolic
            "bpd"=>$vitals["bpd"], //blood pressure diastolic
            "temperatura"=>$vitals["temperature"],
            "respiracion"=>$vitals["respiration"],
            "pulse"=>$vitals["pulse"],
            "BMI"=>$vitals["BMI"], //Índice de masa corporal
            "oxygen_saturation"=>$vitals["oxygen_saturation"],
            "date"=>date('H:i:s',strtotime($vitals["date"])),
            "pid"=>$vitals["pid"],
            "hr"=>$vitals["hr"],
            "vpc"=>$vitals["vpc"],
            "lvp_s"=>$vitals["lvp_s"],
            "lvp_d"=>$vitals["lvp_d"],
            "pr_spo2"=>$vitals["pr_spo2"],
            "st1"=>$vitals["st1"],
            "st2"=>$vitals["st2"],
            "st3"=>$vitals["st3"],
            "nibps_sys"=>$vitals["nibps_sys"],
            "nibps_dys"=>$vitals["nibps_dys"],
        ];
    }


}
echo json_encode($result);
?>
