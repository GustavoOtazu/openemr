var dbConn;
try {
    dbConn = DatabaseConnectionFactory.createDatabaseConnection('com.mysql.jdbc.Driver','jdbc:mysql://localhost:3306/openemr','root','');
    logger.info("patient id que recibe: "+$('patientId'))
    var pid= $('patientId');
    var query= "SELECT id FROM form_vitals order by id desc LIMIT 1";
    var result= dbConn.executeCachedQuery(query);
    var last_id=0;
    while (result.next()){
        last_id=result.getInt(1);}
//Próxima fila que se insertará en la tabla "form_vitals" será:
    var next_id= last_id+1;
    //Se insertan los valores procedentes del monitor de constantes
    var hr =$('hr') ;
    if (!hr) {
      hr=0;
    }
    var vpc=$('vpc');
    if (!vpc) {
      vpc=0;
    }
    var lvp_s =$('lvp_s');
    if (!lvp_s) {
      lvp_s=0;
    }
    var lvp_d=$('lvp_d');
    if (!lvp_d) {
      lvp_d=0;
    }
    var sat_oxy = $('sat_oxy');
    if (!sat_oxy) {
      sat_oxy=0;
    }
    var temperatura= $('temperatura');
    if (!temperatura) {
      temperatura=0;
    }
    var respiracion=$('respiracion');
    if (!respiracion) {
      respiracion=0;
    }
    var bps=$('bps');
    if (!bps) {
      bps=0;
    }
    var bpd=$('bpd');
    if (!bpd) {
      bpd=0;
    }
    var st1=$('st1');
    if (!st1) {
      st1=0;
    }
    var st2=$('st2');
    if (!st2) {
      st2=0;
    }
    var st3=$('st3');
    if (!st3) {
      st3=0;
    }
    var nibps_sys=$('nibps_sys');
    if (!nibps_sys) {
      nibps_sys= 0;
    }
    var nibps_dys=$('nibps_dys');
    if (!nibps_dys) {
      nibps_dys=0;
    }
    var pr_spo2=$('pr_spo2');
    if (!pr_spo2) {
      pr_spo2=0;
    }
    var pulso=0;
    if (!pulso) {
      pulso=0;
    }
    // logger.info("hr: "+hr);
    // logger.info("vpc: "+vpc);
    // logger.info("lvp_s: "+lvp_s);
    // logger.info("lvp_d: "+lvp_d);
    // logger.info("sat_oxy: "+sat_oxy);
    // logger.info("temperatura: "+temperatura);
    // logger.info("respiracion: "+respiracion);
    // logger.info("bps: "+bps);
    // logger.info("bpd: "+bpd);
    // logger.info("st1: "+st1);
    // logger.info("st2: "+st2);
    // logger.info("st3: "+st3);
    // logger.info("nibps_sys: "+nibps_sys);
    // logger.info("nibps_dys: "+nibps_dys);
    var sql1= "INSERT INTO form_vitals (id, date, pid,bps, nibps_dys, nibps_sys, st3, st2, st1, pr_spo2, lvp_d, lvp_s, vpc, hr, bpd, temperature, pulse, respiration, oxygen_saturation) VALUES("+next_id+",'"+$('Fecha')+"',"+pid+","+bps+","+nibps_dys+","+nibps_sys+","+st3+","+st2+","+st1+","+pr_spo2+","+lvp_d+","+lvp_s+","+vpc+","+hr+","+bpd+","+temperatura+","+pulso+","+respiracion+","+sat_oxy+")";
    logger.info(sql1);

    var result = dbConn.executeUpdate(sql1);
    var query="SELECT encounter FROM form_encounter WHERE pid="+pid+"";
    var result= dbConn.executeCachedQuery(query);
    result.next();
    try {
      if (result.getString(1)!=0) {
          var encounter= result.getInt(1);
          if (encounter) {
            var sql1= "INSERT INTO forms (encounter, form_name,form_id, pid, user, formdir) VALUES ("+encounter+",'Vitals',"+next_id+","+pid+", 'administrador','vitals')";
            var result = dbConn.executeUpdate(sql1);
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
