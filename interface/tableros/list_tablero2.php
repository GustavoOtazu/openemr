<?php
require_once("../globals.php");
$inicio = $_REQUEST["inicio"];
$fin = $_REQUEST["fin"];
$internados_actuales_consult = "SELECT f.pid, CONCAT(CONCAT(p.fname, ' '),p.lname) as paciente, f.cuarto as sala, f.cama as cama from form_encounter as f join patient_data as p on p.pid = f.pid where f.pc_catid = 16 and f.out_date is null order by sala ASC, cama ASC";
$res = sqlStatement($internados_actuales_consult);
$inpatient = [];
$result = [];
for ($iter = 0; $encounter = sqlFetchArray($res); $iter++) {
    $sql_vitals = "SELECT p.fname, v.* FROM form_vitals as v JOIN patient_data as p on p.pid = v.pid  WHERE v.pid =? and v.date between '".$inicio."' and '".$fin."'  ORDER by v.date ASC";
    $results = sqlStatement($sql_vitals, array($encounter['pid']));
    if ($results) {
        $i = 0;
        $paciente = "";
        $datos_bps = [];
        $datos_bpd = [];
        $datos_temp = [];
        $datos_resp = [];
        $datos_pulso = [];
        $datos_bmi = [];
        $datos_oxy = [];

        $datos_hr = [];
        $datos_vpc = [];
        $datos_lvp_d = [];
        $datos_lvp_s = [];
        $datos_pr_spo2 = [];
        $datos_st1 = [];
        $datos_st2 = [];
        $datos_st3 = [];
        $datos_nibps_sys = [];
        $datos_nibps_dys = [];
        $datos = [];
        while ($row = sqlFetchArray($results)) {
            $datos_bps[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["bps"]];
            $datos_bpd[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["bpd"]];
            $datos_bmi[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["bmi"]];
            $datos_pulso[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["pulse"]];
            $datos_resp[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["respiration"]];
            $datos_temp[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["temperature"]];
            $datos_oxy[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["oxygen_saturation"]];


            $datos_hr[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["hr"]];
            $datos_vpc[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["vpc"]];
            $datos_lvp_d[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["lvp_d"]];
            $datos_lvp_s[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["lvp_s"]];
            $datos_pr_spo2[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["pr_spo2"]];
            $datos_st1[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["st1"]];
            $datos_st2[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["st2"]];
            $datos_st3[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["st3"]];
            $datos_nibps_sys[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["nibps_sys"]];
            $datos_nibps_dys[] = ["x" => (strtotime($row["date"]) * 1000), "y" => $row["nibps_dys"]];
            $i++;
        }
        if ($i > 0) {
            $result[$encounter['pid']] = [
                "paciente" => $encounter['paciente'],
                "cama" => $encounter['cama'],
                "sala" => $encounter['sala'],
                "bps" => json_encode($datos_bps, JSON_NUMERIC_CHECK),
                "bpd" => json_encode($datos_bpd, JSON_NUMERIC_CHECK),
                "pulso" => json_encode($datos_pulso, JSON_NUMERIC_CHECK),
                "resp" => json_encode($datos_resp, JSON_NUMERIC_CHECK),
                "temp" => json_encode($datos_temp, JSON_NUMERIC_CHECK),
                "oxy" => json_encode($datos_oxy, JSON_NUMERIC_CHECK),


                "hr" => json_encode($datos_hr, JSON_NUMERIC_CHECK),
                "vpc" => json_encode($datos_vpc, JSON_NUMERIC_CHECK),
                "lvp_s" => json_encode($datos_lvp_s, JSON_NUMERIC_CHECK),
                "lvp_d" => json_encode($datos_lvp_d, JSON_NUMERIC_CHECK),
                "pr_spo2" => json_encode($datos_pr_spo2, JSON_NUMERIC_CHECK),
                "st1" => json_encode($datos_st1, JSON_NUMERIC_CHECK),
                "st2" => json_encode($datos_st2, JSON_NUMERIC_CHECK),
                "st3" => json_encode($datos_st3, JSON_NUMERIC_CHECK),
                "nibps_sys" => json_encode($datos_nibps_sys, JSON_NUMERIC_CHECK),
                "nibps_dys" => json_encode($datos_nibps_dys, JSON_NUMERIC_CHECK),

            ];
        }
    }
}
echo json_encode($result)
?>