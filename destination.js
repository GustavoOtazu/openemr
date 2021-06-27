var dbConn;
try {
  dbConn = DatabaseConnectionFactory.createDatabaseConnection('com.mysql.jdbc.Driver', 'jdbc:mysql://localhost:3306/openemr', 'root', '');
  logger.info("numero de registro que recibe desde el monitor: " + $('patientId'))
  var pid = null
  var nro_registro = $('patientId');
  var query_form_encounter = "SELECT pid FROM form_encounter where nro_registro = " + nro_registro + "LIMIT 1";
  var result_form_encounter = dbConn.executeCachedQuery(query_form_encounter);
  while (result_form_encounter.next()) {
    pid = result_form_encounter.getInt(1);
  }


  //Se insertan los valores procedentes del monitor de constantes
  var hr = $('hr');
  if (!hr) {
    hr = 0;
  }
  var vpc = $('vpc');
  if (!vpc) {
    vpc = 0;
  }
  var lvp_s = $('lvp_s');
  if (!lvp_s) {
    lvp_s = 0;
  }
  var lvp_d = $('lvp_d');
  if (!lvp_d) {
    lvp_d = 0;
  }
  var sat_oxy = $('sat_oxy');
  if (!sat_oxy) {
    sat_oxy = 0;
  }
  var temperatura = $('temperatura');
  if (!temperatura) {
    temperatura = 0;
  }
  var respiracion = $('respiracion');
  if (!respiracion) {
    respiracion = 0;
  }
  var bps = $('bps');
  if (!bps) {
    bps = 0;
  }
  var bpd = $('bpd');
  if (!bpd) {
    bpd = 0;
  }
  var st1 = $('st1');
  if (!st1) {
    st1 = 0;
  }
  var st2 = $('st2');
  if (!st2) {
    st2 = 0;
  }
  var st3 = $('st3');
  if (!st3) {
    st3 = 0;
  }
  var nibps_sys = $('nibps_sys');
  if (!nibps_sys) {
    nibps_sys = 0;
  }
  var nibps_dys = $('nibps_dys');
  if (!nibps_dys) {
    nibps_dys = 0;
  }
  var pr_spo2 = $('pr_spo2');
  if (!pr_spo2) {
    pr_spo2 = 0;
  }
  var pulso = 0;
  if (!pulso) {
    pulso = 0;
  }

  var insert_form_vitals = "INSERT INTO form_vitals (date, pid,bps, nibps_dys, nibps_sys, st3, st2, st1, pr_spo2, lvp_d, lvp_s, vpc, hr, bpd, temperature, pulse, respiration, oxygen_saturation) VALUES('" + $('Fecha') + "'," + pid + "," + bps + "," + nibps_dys + "," + nibps_sys + "," + st3 + "," + st2 + "," + st1 + "," + pr_spo2 + "," + lvp_d + "," + lvp_s + "," + vpc + "," + hr + "," + bpd + "," + temperatura + "," + pulso + "," + respiracion + "," + sat_oxy + ")";
  logger.info('insert_form_vitals', insert_form_vitals);

  dbConn.executeUpdate(insert_form_vitals);
  var query_encounter = "SELECT encounter FROM form_encounter WHERE pid=" + pid + "";
  var result_encounter = dbConn.executeCachedQuery(query_encounter);
  result_encounter.next();
  try {
    if (result_encounter.getString(1) != 0) {
      var encounter = result_encounter.getInt(1);
      if (encounter) {
        var insert_forms = "INSERT INTO forms (encounter, form_name,form_id, pid, user, formdir) VALUES (" + encounter + ",'Vitals'," + next_id + "," + pid + ", 'administrador','vitals')";
        dbConn.executeUpdate(insert_forms);
      }
    }
  } catch (e) {
    logger.error(e);
  }


} finally {
  if (dbConn) {
    dbConn.close();
  }
}
