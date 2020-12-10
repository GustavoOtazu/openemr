//obtener el pid
var pid = msg['PID']['PID.3']['PID.3.1'];

//Se crea un array de segmentos, en este caso de OBX's
var obxSegments= msg['OBX'];
var today = new Date();
var dd = today.getDate();
var mm = String(today.getMonth() + 1); //January is 0!
var yyyy = today.getFullYear();
var h = today.getHours();
var min= today.getMinutes();
var mili = today.getSeconds();
today =   yyyy + '-0' +mm  + '-0' +dd + ' '+h+':'+min+':'+mili;
logger.info('pid que manda: '+pid );
channelMap.put('Fecha',today);
channelMap.put('patientId',pid);
//Iteramos en el array sobre los segmentos
for each (var obx in obxSegments){
//Extraemos los campos que nos interesan mapear
    var signo_vital= obx['OBX.3']['OBX.3.2'].toString();
    var valor= obx['OBX.5']['OBX.5.1'];
    //logger.info('signo_vital: '+signo_vital+' valor: '+valor );
    if (signo_vital =='VITAL HR'){
        channelMap.put('hr',valor);
    }
    if (signo_vital=='VITAL VPC'){
        channelMap.put('vpc',valor);
    }

    if (signo_vital=='VITAL LVP(S)'){
        channelMap.put('lvp_s',valor);
    }
    if (signo_vital=='VITAL LVP(D)'){
        channelMap.put('lvp_d',valor);
    }
    if (signo_vital=='VITAL SpO2'){
        channelMap.put('sat_oxy',valor);
    }
    if (signo_vital=='VITAL TSKIN' || signo_vital==='VITAL TEMP2' || signo_vital==='VITAL TEMP' ){
        channelMap.put('temperatura',valor);
    }
    if (signo_vital=='VITAL RESP' || signo_vital==='VITAL APSEC(RESP)'){
        channelMap.put('respiracion',valor);
    }
    if (signo_vital=='VITAL ART(S)'){
        channelMap.put('bps',valor);
    }
    if (signo_vital=='VITAL ART(D)'){
        channelMap.put('bpd',valor);
    }
    if (signo_vital=='VITAL ST1'){
        channelMap.put('st1',valor);
    }
    if (signo_vital=='VITAL ST2'){
        channelMap.put('st2',valor);
    }
    if (signo_vital=='VITAL ST3'){
        channelMap.put('st3',valor);
    }
    if (signo_vital=='NIBP SYS'){
        channelMap.put('nibps_sys',valor);
    }
    if (signo_vital=='NIBP DIAS'){
        channelMap.put('nibps_dys',valor);
    }
    if (signo_vital=='VITAL PR(spo2)'){
        channelMap.put('pr_spo2',valor);
    }
}
