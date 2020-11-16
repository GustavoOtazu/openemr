<?php
/**
 * Encounter form save script.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Roberto Vasquez <robertogagliotta@gmail.com>
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2015 Roberto Vasquez <robertogagliotta@gmail.com>
 * @copyright Copyright (c) 2019 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */


require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/acl.inc");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Services\FacilityService;

if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

$facilityService = new FacilityService();

$date             = (isset($_POST['form_date']))            ? DateToYYYYMMDD($_POST['form_date']) : '';
$onset_date       = (isset($_POST['form_onset_date']))      ? DateToYYYYMMDD($_POST['form_onset_date']) : '';
$sensitivity      = (isset($_POST['form_sensitivity']))     ? $_POST['form_sensitivity'] : '';
$pc_catid         = (isset($_POST['pc_catid']))             ? $_POST['pc_catid'] : '';
$facility_id      = (isset($_POST['facility_id']))          ? $_POST['facility_id'] : '';
$billing_facility = (isset($_POST['billing_facility']))     ? $_POST['billing_facility'] : '';
$reason           = (isset($_POST['reason']))               ? $_POST['reason'] : '';
$mode             = (isset($_POST['mode']))                 ? $_POST['mode'] : '';
$referral_source  = (isset($_POST['form_referral_source'])) ? $_POST['form_referral_source'] : '';
$pos_code         = (isset($_POST['pos_code']))              ? $_POST['pos_code'] : '';
//save therapy group if exist in external_id column
$external_id         = isset($_POST['form_gid']) ? $_POST['form_gid'] : '';
$departamento         = isset($_POST['departamento']) ? $_POST['departamento'] : '';
$servicio         = isset($_POST['servicio']) ? $_POST['servicio'] : '';
$cuarto         = isset($_POST['cuarto']) ? $_POST['cuarto'] : '';
$cama         = isset($_POST['cama']) ? $_POST['cama'] : '';
//alter TABLE `form_encounter` ADD COLUMN departamento VARCHAR(55) AFTER reason, ADD COLUMN servicio VARCHAR(55) AFTER departamento,ADD COLUMN cama VARCHAR(55) AFTER servicio,ADD COLUMN cuarto VARCHAR(55) AFTER cama,ADD COLUMN out_date date AFTER cama;
$facilityresult = $facilityService->getById($facility_id);
$facility = $facilityresult['name'];



$id = $_POST["id"];
$result = sqlQuery("SELECT encounter, sensitivity FROM form_encounter WHERE id = ?", array($id));


$encounter = $result['encounter'];
// See view.php to allow or disallow updates of the encounter date.
$datepart = "";
$sqlBindArray = array();
if (acl_check('encounters', 'date_a')) {
    $datepart = "date = ?, ";
    $sqlBindArray[] = $date;
}
array_push(
    $sqlBindArray,
    $onset_date,
    $departamento,
    $servicio,
    $cama,
    $cuarto,
    $id
);
sqlStatement(
    "UPDATE form_encounter SET
            $datepart
            onset_date = ?,
            departamento=?,
            servicio=?,
            cama=?,
            cuarto=? WHERE id = ?",
    $sqlBindArray
);

?>
<html>
<body>
<script language='JavaScript'>
    top.RTop.location = "../../tableros/lista_internados.php?update=success";
</script>

</body>
</html>
