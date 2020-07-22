<?php


namespace Mi2\Messages\Controllers;

require_once(__DIR__."/../../../../../../library/pid.inc");
require_once(__DIR__."/../../../../../../library/formatting.inc.php");
require_once(__DIR__."/../../../../../../library/patient.inc");

use Mi2\Framework\AbstractController;

class PatientController extends AbstractController
{
    public function _action_setpid()
    {
        // All functionality for setting active patient and encounter via ajax
        $pid = $this->request->getParam( 'pid' );
        $encounter = $this->request->getParam( 'encounter' );

        setpid($pid);
        $sql = "SELECT CONCAT( fname, ' ', lname ) AS patient_name, pubpid, pid, DATE_FORMAT( DOB,'%Y-%m-%d' ) as DOB_YMD
           FROM patient_data
           WHERE pid = ?
           LIMIT 1";
        $patientRow = sqlQuery( $sql, array( $pid ) );

        $patientName = $patientRow['patient_name'];
        $pubpid = $patientRow['pubpid'];
        $str_dob = xl('DOB') . ": " . oeFormatShortDate( $patientRow['DOB_YMD'] ) . " " . xl('Age') . ": " . getPatientAge( $patientRow['DOB_YMD'] );

        // Find all encounters for this patient and sort by date
        $sql = "SELECT fe.encounter, fe.date, opc.pc_catname
          FROM form_encounter AS fe
          LEFT JOIN openemr_postcalendar_categories opc on fe.pc_catid = opc.pc_catid
          WHERE fe.pid = ? order by fe.date desc";
        $result = sqlStatement( $sql, array( $pid ) );

        $encounterIdArray = array();
        $encounterDateArray = array();
        $encounterCategoryArray = array();
        while ( $row = sqlFetchArray( $result ) ) {
            $encounterIdArray []= $row['encounter'];
            $encounterDateArray []= oeFormatShortDate( date( "Y-m-d", strtotime( $row['date'] ) ) );
            $encounterCategoryArray []= $row['pc_catname'];
        }

        // Build an array of encounter data and use json_encode to conver php
        // array to json
        $data = array(
            'patientname' => $patientName,
            'pid' => $pid,
            'pubpid' => $pubpid,
            'frname' => 'RTop',
            'str_dob' => $str_dob,
            'encounter' => $encounter,
            'encounterIdArray' => $encounterIdArray,
            'encounterDateArray' => $encounterDateArray,
            'calendarCategoryArray' => $encounterCategoryArray,

        );
        echo json_encode($data);
    }
}
