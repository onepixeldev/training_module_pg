<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ext_training_model extends MY_Model
{
    private $staff_id;
    private $username;

    public function __construct()
    {
        $this->load->database();
        $this->staff_id = $this->lib->userid();
        $this->username = $this->lib->username();
    }

    // GET ADMIN DEPT -postgres
    public function getTrainingAdminDeptCode() {
		$sID = $this->staff_id;
		
        $this->db->select('hp_parm_desc as PARM_DESC');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->join('ims_hris.staff_main','sm_dept_code = upper(trim(hp_parm_desc))');
        $this->db->where('hp_parm_code', 'TRAINING_ADM_DEPT_CODE');
        $this->db->where('sm_staff_id', $sID);
        $query = $this->db->get();
		
        if ($query->num_rows() > 0) {
            if ($query->row()->PARM_DESC == '' or $query->row()->PARM_DESC == null){
                return '';
            }else{
                return $query->row()->PARM_DESC;
            }
        }
		
        return '';
    }

    // get current date -postgres
    public function getCurDate() {		
        $this->db->select("TO_CHAR(current_date, 'MM') AS sysdate_mm, TO_CHAR(current_date, 'YYYY') AS sysdate_yyyy");
        //$this->db->from("DUAL");
        $q = $this->db->get();
                
        return $q->row_case('UPPER');
    } 

    // GET YEAR DROPDOWN -postgres
    public function getYearList() 
    {		
        $this->db->select("to_char(cm_date, 'yyyy') as cm_year");
        $this->db->from("ims_hris.calendar_main");
        $this->db->where("to_char(cm_date, 'yyyy')::numeric >= to_char(current_date, 'yyyy')::numeric - 15");
        $this->db->group_by("to_char(cm_date, 'YYYY')");
        $this->db->order_by("to_char(cm_date, 'YYYY') desc");
        $q = $this->db->get();
                
        return $q->result_case('UPPER');
    } 

    // GET MONTH DROPDOWN -postgres
    public function getMonthList() 
    {		
        $this->db->select("to_char(cm_date, 'mm') as cm_mm, to_char(cm_date, 'month') as cm_month");
        $this->db->from("ims_hris.calendar_main");
        $this->db->group_by("to_char(cm_date,'mm'), to_char(cm_date, 'month')");
        $this->db->order_by("to_char(cm_date, 'mm')");
        $q = $this->db->get();
		        
        return $q->result_case('UPPER');
    } 

    // CURREMT USER DEPT -postgres
    public function currentUsrDept()
    {  
        $curr_usr = $this->username;

        $this->db->select("sm_dept_code");
        $this->db->from("ims_hris.staff_main");
        $this->db->where("upper(sm_apps_username)", $curr_usr);
        $q = $this->db->get();
    
        return $q->row_case('UPPER');
    }

    // ALL DEPARTMENT -postgres
    public function getDeptAll()
    {  
        $this->db->select("dm_dept_code, dm_dept_code||' - '||dm_dept_desc as dp_code_desc");
        $this->db->from("ims_hris.department_main");
        $this->db->where("COALESCE(dm_status,'INACTIVE') = 'ACTIVE'");
        $this->db->where("dm_level IN (1,2)");
        $this->db->order_by("dm_dept_code");
        $q = $this->db->get();
    
        return $q->result_case('UPPER');
    }

    // ALL DEPARTMENT 2 -postgres
    public function getPopulateDept($deptCode)
    {  
        $this->db->select("dm_dept_code, dm_dept_code||' - '||dm_dept_desc as dp_code_desc");
        $this->db->from("ims_hris.department_main");
        if(!empty($deptCode)) {
            $this->db->where('dm_dept_code', $deptCode);
        }
        $this->db->order_by("dm_dept_code");
        $q = $this->db->get();
    
        return $q->result_case('UPPER');
    }

    // NOT ALL DEPARTMENT -postgres
    public function getDeptBased()
    {  
        $curr_usr = $this->username;

        $this->db->select("dm_dept_code, dm_dept_code||' - '||dm_dept_desc as dp_code_desc");
        $this->db->from("ims_hris.department_main");
        $this->db->where("COALESCE(dm_status,'INACTIVE') = 'ACTIVE'");
        $this->db->where("dm_level IN (1,2)");
        $this->db->where("dm_dept_code = (select sm_dept_code from ims_hris.staff_main where upper(sm_apps_username) = '$curr_usr')");
        $this->db->order_by("dm_dept_code");
        $q = $this->db->get();
    
        return $q->result_case('UPPER');
    }
    
    /*===========================================================
       Organizer Info for External Agency Setup - ASF132
    =============================================================*/

    // ORGANIZER INFO -postgres
    public function getOrgInfoList($state = null)
    {
        $this->db->select("toh_org_code,
        toh_org_desc,
        toh_address,
        toh_postcode,
        toh_city,
        toh_state,
        toh_country,
        toh_external_agency,
        toh_state||' - '||sm_state_desc toh_state_desc,
        toh_country||' - '||cm_country_desc toh_country_desc,
        sm_state_desc,
        cm_country_desc");
        $this->db->from("ims_hris.training_organizer_head");
        $this->db->join("ims_hris.state_main", "toh_state = sm_state_code", "LEFT");
        $this->db->join("ims_hris.country_main", "toh_country = cm_country_code", "LEFT");
        $this->db->where("COALESCE(toh_external_agency,'N') = 'Y'");

        if(!empty($state)) 
        {
            $this->db->where("toh_state", $state);
        }

        $this->db->order_by("toh_state");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // STATE DD -postgres
    public function getStateDD()
    {
        $this->db->select("sm_state_code, sm_state_code||' - '||sm_state_desc sm_state_cd");
        $this->db->from("ims_hris.state_main");
        $this->db->where("(sm_country_code = 'MYS'
        OR sm_country_code IS NULL)");
        $this->db->order_by("sm_state_desc");
    
        $q = $this->db->get();
    
        return $q->result_case('UPPER');
    }

    // CONTRY DD -postgres
    public function getCountryDD()
    {
        $this->db->select("cm_country_code, cm_country_code||' - '||cm_country_desc cm_country_cd");
        $this->db->from("ims_hris.country_main");
        $this->db->order_by("cm_country_desc");
    
        $q = $this->db->get();
    
        return $q->result_case('UPPER');
    }

    // ORGANIZER INFO DETL -postgres
    public function getOrgInfoDetl($code)
    {
        $this->db->select("toh_org_code,
        toh_org_desc,
        toh_address,
        toh_postcode,
        toh_city,
        toh_state,
        toh_country,
        toh_external_agency");
        $this->db->from("ims_hris.training_organizer_head");
        $this->db->where("toh_org_code", $code);

        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // SAVE ADD ORGANIZER INFO -postgres
    public function saveOrgInfo($form) 
    {
        $data = array(
            "toh_org_code" => strtoupper($form['code']),
            "toh_org_desc" => $form['description'],
            "toh_address" => $form['address'],
            "toh_postcode" => $form['postcode'],
            "toh_city" => $form['city'],
            "toh_state" => $form['state'],
            "toh_country" => $form['country'],
            "toh_external_agency" => 'Y',
        );

        return $this->db->insert("ims_hris.training_organizer_head", $data);
    }

    // SAVE UPDATE ORGANIZER INFO -postgres
    public function saveUpdOrgInfo($form, $code) 
    {
        $data = array(
            "toh_org_desc" => $form['description'],
            "toh_address" => $form['address'],
            "toh_postcode" => $form['postcode'],
            "toh_city" => $form['city'],
            "toh_state" => $form['state'],
            "toh_country" => $form['country'],
        );

        $this->db->where("UPPER(toh_org_code) = UPPER('$code')");

        return $this->db->update("ims_hris.training_organizer_head", $data);
    }

    // DELETE ORGANIZER INFO -postgres
    public function delOrgInfo($code) 
    {
        $this->db->where("UPPER(toh_org_code) = UPPER('$code')");
        return $this->db->delete('ims_hris.training_organizer_head');
    }

    /*===========================================================
       TRAINING SETUP FOR EXTERNAL AGENCY - ATF138
    =============================================================*/

    // GET TRAINING LIST -postgres
    public function getTrainingList()
    {
        $umg = $this->username;

        $this->db->select("th_ref_id,
        th_training_title,
        to_char(th_date_from, 'dd/mm/yyyy') th_date_from2,
        to_char(th_date_to, 'dd/mm/yyyy') th_date_to2
        ");
        $this->db->from("ims_hris.training_head");
        
        $this->db->where("th_status = 'ENTRY'");
        $this->db->where("th_dept_code = (select sm_dept_code from ims_hris.staff_main where upper(sm_apps_username) = UPPER('$umg'))");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->order_by("th_date_from, th_date_to, th_training_title, th_ref_id");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // GET TRAINING LIST NEW -postgres
    public function getTrainingListNew($deptCode)
    {
        $this->db->select("th_ref_id,
        th_training_title,
        to_char(th_date_from, 'dd/mm/yyyy') th_date_from2,
        to_char(th_date_to, 'dd/mm/yyyy') th_date_to2
        ");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_status = 'ENTRY'");
        if(!empty($deptCode)) {
            $this->db->where("th_dept_code", $deptCode);
        }
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->order_by("th_date_from, th_date_to, th_training_title, th_ref_id");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // DROPDOWN TYPE LIST -postgres
    public function getTypeList()
    {
        $this->db->select("tt_code, tt_code ||' - '|| tt_desc as tt_code_desc");
        $this->db->from("ims_hris.training_type");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // DROPDOWN CATEGORY LIST -postgres
    public function getCategoryList()
    {
        $this->db->select("tc_category");
        $this->db->from("ims_hris.training_category");
        $this->db->where("COALESCE(tc_status,'N') = 'Y'");
        $this->db->order_by("1");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // DROPDOWN LEVEL LIST -postgres
    public function getLevelList()
    {
        $this->db->select("tl_code, tl_code ||' - '|| tl_desc as tl_code_desc");
        $this->db->from("ims_hris.training_level");
        $this->db->order_by("tl_code");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // DROPDOWN COMPETENCY LEVEL LIST -postgres
    public function getCompetencyLevel() 
    {
        $this->db->select("tcl_competency_code, tcl_competency_code ||' - '|| tcl_competency_desc as tcl_competency_code_desc, tcl_service_year_from, tcl_service_year_to,tcl_ordering");
        $this->db->from('ims_hris.training_competency_level');
		$this->db->where("tcl_status = 'Y'");
        $this->db->order_by('tcl_ordering');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // DROPDOWN STAFF LIST -postgres
    public function getCoordinator() 
    {
        $this->db->select("sm_staff_id, sm_staff_id ||' - '|| sm_staff_name as sm_staff_id_name");
        $this->db->from('ims_hris.staff_main, ims_hris.staff_status');
        $this->db->where("sm_staff_status = ss_status_code");
        $this->db->where("ss_status_sts = 'ACTIVE'");
        $this->db->where("sm_staff_type = 'STAFF'");
        $this->db->order_by('sm_staff_name');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // DROPDOWN SECTOR LEVEL LIST -postgres
    public function getCoordinatorSec() 
    {
        $this->db->select("tsl_code, tsl_code ||' - '|| tsl_desc as tsl_code_desc");
        $this->db->from('ims_hris.training_sector_level');
		$this->db->where("COALESCE(tsl_status,'N') = 'Y'");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // DROPDOWN ORGANIZER LEVEL LIST -postgres
    public function getOrganizerLevel() 
    {
        $this->db->select("tol_code, tol_code ||' - '|| tol_desc as tol_code_desc");
        $this->db->from('ims_hris.training_organizer_level');
        $this->db->order_by('tol_code');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // GET ORGANIZER DETAILS -postgres
    public function getOrganizerName($organizerCode = null) 
    {
        $this->db->select("toh_org_code, toh_org_desc, toh_org_code ||' - '|| toh_org_desc as toh_org_code_desc, toh_address, toh_postcode, toh_city, sm_state_desc, cm_country_desc");
        $this->db->from('ims_hris.training_organizer_head, ims_hris.state_main, ims_hris.country_main');
        $this->db->where("toh_state=sm_state_code");
        $this->db->where("toh_country=cm_country_code");
        $this->db->where("COALESCE(toh_external_agency,'N') = 'Y'");

        if(!empty($organizerCode)) {
            $this->db->where("toh_org_code", $organizerCode);
            $q = $this->db->get();
        
            return $q->row_case('UPPER');
        } 
        else {
            $this->db->order_by("2");
            $q = $this->db->get();
        
            return $q->result_case('UPPER');
        }  
    }

    // DROPDOWN STATE LIST -postgres
    public function getCountryStateList($countCode) 
    {
        $this->db->select('sm_state_code, sm_state_desc, sm_country_code');
        $this->db->from('ims_hris.state_main');
		$this->db->where('sm_country_code', $countCode);
        $this->db->order_by('sm_state_code');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // GET STAFF LIST DROPDOWN -postgres
    public function getStaffList($staffID = null)
    {
        $this->db->select("sm_staff_id, sm_staff_name, sm_staff_id ||' - '||sm_staff_name as sm_staff_id_name, to_char(current_date, 'dd/mm/yyyy') as curr_date, sm_dept_code");
        $this->db->from("ims_hris.staff_main, ims_hris.staff_status");
        $this->db->where("ss_status_code = sm_staff_status");
        $this->db->where("sm_staff_type = 'STAFF'");

        if(!empty($staffID)) {
            $this->db->where("UPPER(sm_staff_id) = UPPER('$staffID')");
            $q = $this->db->get();
            return $q->row_case('UPPER');
        } else {
            $this->db->where("ss_status_sts = 'ACTIVE'");
            $this->db->order_by("2");

            $q = $this->db->get();
            return $q->result_case('UPPER');
        }
    }

    // SEARCH STAFF -postgres
    public function getStaffSearch($staffID)
    {
        $this->db->select("sm_staff_id, sm_staff_name, sm_staff_id ||' - '||sm_staff_name as sm_staff_id_name");
        $this->db->from("ims_hris.staff_main, ims_hris.staff_status");
        $this->db->where("ss_status_code = sm_staff_status");
        $this->db->where("sm_staff_type = 'STAFF'");
        $this->db->where("ss_status_sts = 'ACTIVE'");

        $this->db->where("(upper(sm_staff_id) like upper('%$staffID%') or upper(sm_staff_name) like upper('%$staffID%'))");
        $this->db->order_by("2");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET REFID -postgres
    public function getRefID() 
    {
        //$this->db->select("to_char(sysdate,'yyyy')||'-E'||ltrim(to_char(training_head_seq.nextval,'000000')) AS REF_ID");
        $this->db->select('TO_CHAR(CURRENT_DATE,\'YYYY\')||\'-E\'||LTRIM(TO_CHAR(nextval(\'ims_hris.training_head_seq\'),\'000000\')) AS "REF_ID"');
        // $this->db->from("DUAL");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // INSERT TRAINING HEAD -postgres
    public function saveNewTraining($form, $refid)
    {
        $umg = $this->staff_id;
        $staff_dept_code = "(select sm_dept_code from ims_hris.staff_main where sm_staff_id = '$umg')";
        $enter_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_ref_id" => $refid,
            "th_type" => $form['type'],
            "th_category" => $form['category'],
            "th_level" => $form['level'],
            "th_training_title" => $form['training_title'],
            "th_training_desc" => $form['training_description'],
            "th_training_venue" => $form['venue'],
            "th_training_country" => $form['country'],
            "th_training_state" => $form['state'],
            "th_total_hours" => $form['total_hours'],
            "th_training_fee" => $form['fee'],
            "th_internal_external" => $form['internal_external'],
            "th_sponsor" => $form['sponsor'],
            "th_max_participant" => $form['participants'],
            "th_open" => $form['online_application'],
            // "TH_COMPETENCY_CODE" => $form['competency_code'],

            // organizer info
            "th_organizer_level" => $form['organizer_level'],
            "th_organizer_name" => $form['organizer_name'],

            // completion info
            "th_evaluation_compulsory" => $form['evaluation_compulsary'],
            "th_attendance_type" => $form['attendance_type'],
            "th_print_certificate" => $form['print_certificate'],

            "th_enter_by" => $umg,
            "th_status" => 'ENTRY'
        );

        //$this->db->set("TH_REF_ID", $refID, false);
        $this->db->set("th_dept_code", $staff_dept_code, false);
        $this->db->set("th_enter_date", $enter_date, false);

        if(!empty($form['date_from'])){
            $date_from = "to_date('".$form['date_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_from", $date_from, false);
        }

        if(!empty($form['date_to'])){
            $date_to = "to_date('".$form['date_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_to", $date_to, false);
        }

        if(!empty($form['closing_date'])){
            $closing_date = "to_date('".$form['closing_date']."', 'DD/MM/YYYY')";
            $this->db->set("th_apply_closing_date", $closing_date, false);
        }

        if(!empty($form['evaluation_period_from'])){
            $evaluation_period_from = "to_date('".$form['evaluation_period_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_from", $evaluation_period_from, false);
        }

        if(!empty($form['evaluation_period_to'])){
            $evaluation_period_to = "to_date('".$form['evaluation_period_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_to", $evaluation_period_to, false);
        }

        return $this->db->insert("ims_hris.training_head", $data);
    }

    // INSERT TRAINING HEAD DETL -postgres
    public function saveTrainingDetl($refid, $coor, $coorSeq, $coorContact, $evaluationTHD, $attention)
    {
        $data = array(
            "thd_ref_id" => $refid,
            "thd_coordinator" => $coor,
            "thd_coordinator_sector" => $coorSeq,
            "thd_coordinator_telno" => $coorContact,
            "thd_evaluation" => $evaluationTHD,
            "thd_for_attention" => $attention
        );

        return $this->db->insert("ims_hris.training_head_detl", $data);
    }

    // GET TRAINING HEAD -postgres
    public function getTrainingHead($refid)
    {
        $this->db->select("th_ref_id,
        th_type,
        th_category,
        th_level,
        th_training_title,
        th_training_desc,
        th_training_venue,
        th_training_country,
        th_training_state,
        to_char(th_date_from, 'dd/mm/yyyy') th_date_from2,
        to_char(th_date_to, 'dd/mm/yyyy') th_date_to2,
        th_total_hours,
        th_training_fee,
        th_internal_external,
        th_sponsor,
        th_max_participant,
        th_open,
        to_char(th_apply_closing_date, 'dd/mm/yyyy') th_apply_closing_date2,
        th_competency_code,
        to_char(th_evaluation_date_from, 'dd/mm/yyyy') th_evaluation_date_from2,
        to_char(th_evaluation_date_to, 'dd/mm/yyyy') th_evaluation_date_to2,
        th_organizer_level,
        th_organizer_name,
        th_evaluation_compulsory,
        th_attendance_type,
        th_print_certificate,
        th_status
        ");

        $this->db->from("ims_hris.training_head");
        $this->db->where("th_ref_id", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // GET TRAINING HEAD DETL -postgres
    public function getTrainingHeadDetl($refid)
    {
        $this->db->select("thd_ref_id,
        thd_coordinator,
        thd_coordinator_sector,
        thd_coordinator_telno,
        thd_for_attention,
        thd_evaluation,
        sm_staff_name
        ");

        $this->db->from("ims_hris.training_head_detl");
        $this->db->join("ims_hris.staff_main", "thd_coordinator = sm_staff_id", "left");
        $this->db->where("thd_ref_id", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // UPDATE TRAINING HEAD -postgres
    public function saveEditTraining($form, $refid)
    {
        $umg = $this->staff_id;
        $staff_dept_code = "(select sm_dept_code from ims_hris.staff_main where sm_staff_id = '$umg')";
        $enter_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_type" => $form['type'],
            "th_category" => $form['category'],
            "th_level" => $form['level'],
            "th_training_title" => $form['training_title'],
            "th_training_desc" => $form['training_description'],
            "th_training_venue" => $form['venue'],
            "th_training_country" => $form['country'],
            "th_training_state" => $form['state'],
            "th_total_hours" => $form['total_hours'],
            "th_training_fee" => $form['fee'],
            "th_internal_external" => $form['internal_external'],
            "th_sponsor" => $form['sponsor'],
            "th_max_participant" => $form['participants'],
            "th_open" => $form['online_application'],
            // "TH_COMPETENCY_CODE" => $form['competency_code'],

            // organizer info
            "th_organizer_level" => $form['organizer_level'],
            "th_organizer_name" => $form['organizer_name'],

            // completion info
            "th_evaluation_compulsory" => $form['evaluation_compulsary'],
            "th_attendance_type" => $form['attendance_type'],
            "th_print_certificate" => $form['print_certificate'],

            "th_enter_by" => $umg,
            "th_status" => 'ENTRY'
        );

        $this->db->set("th_dept_code", $staff_dept_code, false);
        $this->db->set("th_enter_date", $enter_date, false);

        if(!empty($form['date_from'])){
            $date_from = "to_date('".$form['date_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_from", $date_from, false);
        }

        if(!empty($form['date_to'])){
            $date_to = "to_date('".$form['date_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_to", $date_to, false);
        }

        if(!empty($form['closing_date'])){
            $closing_date = "to_date('".$form['closing_date']."', 'DD/MM/YYYY')";
            $this->db->set("th_apply_closing_date", $closing_date, false);
        }

        if(!empty($form['evaluation_period_from'])){
            $evaluation_period_from = "to_date('".$form['evaluation_period_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_from", $evaluation_period_from, false);
        } else {
            $this->db->set("th_evaluation_date_from", '', true);
        }

        if(!empty($form['evaluation_period_to'])){
            $evaluation_period_to = "to_date('".$form['evaluation_period_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_to", $evaluation_period_to, false);
        } else {
            $this->db->set("th_evaluation_date_to", '', true);
        }

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    }

    // UPDATE TRAINING HEAD DETL -postgres
    public function saveUpdTrainingDetl($refid, $coor, $coorSeq, $coorContact, $evaluationTHD, $attention)
    {
        $data = array(
            "thd_coordinator" => $coor,
            "thd_coordinator_sector" => $coorSeq,
            "thd_coordinator_telno" => $coorContact,
            "thd_evaluation" => $evaluationTHD,
            "thd_for_attention" => $attention
        );

        $this->db->where("thd_ref_id", $refid);

        return $this->db->update("ims_hris.training_head_detl", $data);
    }

    // ORGANIZER INFO DETL EDIT -postgres
    public function getOrgInfoDetlEdit($org_name)
    {
        $this->db->select("toh_org_code,
        toh_org_desc,
        toh_address,
        toh_postcode,
        toh_city,
        toh_state,
        toh_country,
        toh_external_agency,
        sm_state_desc,
        cm_country_desc");
        $this->db->from("ims_hris.training_organizer_head");
        $this->db->join("ims_hris.state_main", "toh_state = sm_state_code", "left");
        $this->db->join("ims_hris.country_main", "toh_country = cm_country_code", "left");
        $this->db->where("toh_org_code", $org_name);

        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // TRAINING COST -postgres
    public function getTrainingCost($refid)
    {
        $this->db->select("tc_training_refid,
        tc_cost_code,
        tc_amount,
        tc_remark,
        tct_desc
        ");
        $this->db->from("ims_hris.training_cost");
        $this->db->join("training_cost_type", "tct_code = tc_cost_code", "left");
        $this->db->where("tc_training_refid", $refid);

        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // GET ECOMM URL -postgres
    public function getEcommUrl()
    {
        $this->db->select("hp_parm_desc");
        $this->db->from("ims_hris.hradmin_parms");
        $this->db->where("hp_parm_code = 'ECOMMUNITY_STAFF_URL'");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    } 

    // TRAINING COST -postgres
    public function getCostCodeDd()
    {
        $this->db->select("tct_code,
        tct_desc, 
        tct_code||' - '||tct_desc tct_code_desc
        ");
        $this->db->from("ims_hris.training_cost_type");
        $this->db->where("COALESCE(tct_status,'N') = 'Y'");
        $this->db->order_by("tct_code");

        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // TRAINING COST DETL -postgres
    public function getTrainingCostDetl($refid, $cost_code)
    {
        $this->db->select("tc_training_refid,
        tc_cost_code,
        tc_amount,
        tc_remark
        ");
        $this->db->from("ims_hris.training_cost");
        $this->db->where("tc_training_refid", $refid);
        $this->db->where("tc_cost_code", $cost_code);

        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // INSERT TRAINING_COST -postgres
    public function saveNewTrCost($form)
    {
        $data = array(
            "tc_training_refid" => $form['refid'],
            "tc_cost_code" => $form['cost_code'],
            "tc_amount" => $form['amount'],
            "tc_remark" => $form['remark']
        );

        return $this->db->insert("ims_hris.training_cost", $data);
    }

    // GET SUM TRAINING COST -postgres
    public function getSumTrCost($refid)
    {
        $this->db->select("sum(tc_amount) as sum_tr_cost");
        $this->db->from("ims_hris.training_cost");
        $this->db->where("tc_training_refid", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // UPDATE TRAINING FEE -postgres
    public function updTrainingFee($refid, $tr_cost)
    {
        $data = array(
            "th_training_fee" => $tr_cost,
        );

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    }

    // UPDATE TRAINING_COST -postgres
    public function saveUpdTrCost($form)
    {
        $data = array(
            "tc_amount" => $form['amount'],
            "tc_remark" => $form['remark']
        );

        $this->db->where("tc_training_refid", $form['refid']);
        $this->db->where("tc_cost_code", $form['cost_code']);

        return $this->db->update("ims_hris.training_cost", $data);
    }

    // DELETE TRAINING COST -postgres
    public function deleteTrainingCost($refid, $code) 
    {
        $this->db->where("tc_training_refid", $refid);
        $this->db->where("tc_cost_code", $code);
        return $this->db->delete('ims_hris.training_cost');
    }

    // check training head detl -postgres
    public function delVerify1($refid) 
    {
        $this->db->select("1");
        $this->db->from("ims_hris.training_head_detl");
        $this->db->where("thd_ref_id", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // check cpd head -postgres
    public function delVerify2($refid) 
    {
        $this->db->select("1");
        $this->db->from("ims_hris.cpd_head");
        $this->db->where("ch_training_refid", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // check training target group -postgres
    public function delVerify3($refid) 
    {
        $this->db->select("1");
        $this->db->from("ims_hris.training_target_group");
        $this->db->where("ttg_training_refid", $refid);

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // check training cost -postgres
    public function delVerify4($refid) 
    {
        $this->db->select("1");
        $this->db->from("ims_hris.training_cost");
        $this->db->where("tc_training_refid", $refid);

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // check training attachment -postgres
    public function delVerify5($refid) 
    {
        $this->db->select("1");
        $this->db->from("ims_hris.training_doc_attach");
        $this->db->where("tda_refid", $refid);

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // DELETE TRAINING HEAD -postgres
    public function delTrainingInfo($refid) 
    {
        $this->db->where('th_ref_id', $refid);
        return $this->db->delete('ims_hris.training_head');
    }

    /*===========================================================
       APPROVE TRAINING SETUP FOR EXTERNAL AGENCY - ATF139
    =============================================================*/

    // GET YEAR DROPDOWN -postgres
    public function getYearListAppTr() 
    {		
        $this->db->select("to_char(th_date_from,'yyyy') as cm_year");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->group_by("TO_CHAR(th_date_from,'YYYY') ");
        $this->db->order_by("TO_CHAR(th_date_from,'YYYY') desc");
        $q = $this->db->get();
                
        return $q->result_case('UPPER');
    } 

    // GET TRAINING LIST -postgres
    public function getTrainingList2($dept, $month, $year, $status)
    {
        $this->db->select("th_ref_id,
        th_training_title,
        to_char(th_date_from, 'dd/mm/yyyy') th_date_from2,
        to_char(th_date_to, 'dd/mm/yyyy') th_date_to2,
        th_training_fee
        ");
        $this->db->from("ims_hris.training_head");

        if(!empty($dept)) {
            $this->db->where("th_dept_code", $dept);
        }

        if(!empty($month)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'MM'),'') = '$month'");
        }

        if(!empty($year)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'YYYY'),'') = '$year'");
        }

        // if(!empty($year) && !empty($month)) {
        //     $this->db->where("COALESCE(TO_CHAR(TH_DATE_FROM,'MM/YYYY'),'') = '$month/$year'");
        // }

        if(!empty($status)) {
            $this->db->where("COALESCE(th_status, 'ENTRY') = '$status'");
        }
        
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->order_by("th_date_from, th_date_to, th_training_title, th_ref_id");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // APPROVE/POSTPONE/AMEND/REJECT TRAINING -postgres
    public function updStsExtTrainingSetup($refid, $upd_status)
    {
        $currentUsr = $this->staff_id;
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_status" =>  $upd_status,
            "th_approve_by" => $currentUsr
        );

        $this->db->set("th_approve_date", $curDate, false);

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    } 

    // COUNT STAFF TRAINING -postgres
    public function getTrainingStaffDetl($refid)
    {
        $this->db->select("count(1) as c_staff");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->where("sth_training_refid", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // UPDATE STH TRAINING -postgres
    public function updSthTrainingSetup($refid, $upd_status)
    {
        $data = array(
            "sth_status" =>  $upd_status,
        );

        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    } 
    
    /*===========================================================
       APPROVE TRAINING SETUP FOR EXTERNAL AGENCY - ATF139
    =============================================================*/

    // GET YEAR DROPDOWN -postgres
    public function getYearListEdtAppTr() 
    {		
        $this->db->select("to_char(th_date_from,'yyyy') as cm_year");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->group_by("TO_CHAR(th_date_from,'YYYY') ");
        $this->db->order_by("TO_CHAR(th_date_from,'YYYY') desc");
        $q = $this->db->get();
                
        return $q->result_case('UPPER');
    } 

    // UPDATE TRAINING HEAD -postgres
    public function saveEditTraining2($form, $refid)
    {
        $umg = $this->staff_id;
        $staff_dept_code = "(select sm_dept_code from ims_hris.staff_main where sm_staff_id = '$umg')";
        $enter_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_type" => $form['type'],
            "th_category" => $form['category'],
            "th_level" => $form['level'],
            "th_training_title" => $form['training_title'],
            "th_training_desc" => $form['training_description'],
            "th_training_venue" => $form['venue'],
            //"TH_TRAINING_COUNTRY" => $form['country'],
            //"TH_TRAINING_STATE" => $form['state'],
            "th_total_hours" => $form['total_hours'],
            "th_training_fee" => $form['fee'],
            "th_internal_external" => $form['internal_external'],
            "th_sponsor" => $form['sponsor'],
            "th_max_participant" => $form['participants'],
            "th_open" => $form['online_application'],
            // "TH_COMPETENCY_CODE" => $form['competency_code'],

            // organizer info
            "th_organizer_level" => $form['organizer_level'],
            "th_organizer_name" => $form['organizer_name'],

            // completion info
            "th_evaluation_compulsory" => $form['evaluation_compulsary'],
            "th_attendance_type" => $form['attendance_type'],
            "th_print_certificate" => $form['print_certificate'],

            "th_enter_by" => $umg,
        );

        if(empty($form['country'])) {
            $this->db->set("th_training_country", "", true);
        } else {
            $this->db->set("th_training_country", $form['country'], true);
        }

        if(empty($form['state'])) {
            $this->db->set("th_training_state", "", true);
        } else {
            $this->db->set("th_training_state", $form['state'], true);
        }

        $this->db->set("th_dept_code", $staff_dept_code, false);
        $this->db->set("th_enter_date", $enter_date, false);

        if(!empty($form['date_from'])){
            $date_from = "to_date('".$form['date_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_from", $date_from, false);
        }

        if(!empty($form['date_to'])){
            $date_to = "to_date('".$form['date_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_to", $date_to, false);
        }

        if(!empty($form['closing_date'])){
            $closing_date = "to_date('".$form['closing_date']."', 'DD/MM/YYYY')";
            $this->db->set("th_apply_closing_date", $closing_date, false);
        }

        // if(!empty($form['evaluation_period_from'])){
        //     $evaluation_period_from = "to_date('".$form['evaluation_period_from']."', 'DD/MM/YYYY')";
        //     $this->db->set("TH_EVALUATION_DATE_FROM", $evaluation_period_from, false);
        // }

        // if(!empty($form['evaluation_period_to'])){
        //     $evaluation_period_to = "to_date('".$form['evaluation_period_to']."', 'DD/MM/YYYY')";
        //     $this->db->set("TH_EVALUATION_DATE_TO", $evaluation_period_to, false);
        // }

        if(!empty($form['evaluation_period_from'])){
            $evaluation_period_from = "to_date('".$form['evaluation_period_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_from", $evaluation_period_from, false);
        } else {
            $this->db->set("th_evaluation_date_from", '', true);
        }

        if(!empty($form['evaluation_period_to'])){
            $evaluation_period_to = "to_date('".$form['evaluation_period_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_to", $evaluation_period_to, false);
        } else {
            $this->db->set("th_evaluation_date_to", '', true);
        }

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    }

    /*===========================================================
       APPROVE TRAINING APPLICATIONS FOR EXTERNAL AGENCY - ATF141
    =============================================================*/

    // GET TRAINING LIST -postgres
    public function getTrainingList4($dept, $month, $year)
    {
        $this->db->select("th_ref_id,
        th_training_title,
        to_char(th_date_from, 'dd/mm/yyyy') th_date_from2,
        to_char(th_date_to, 'dd/mm/yyyy') th_date_to2,
        th_training_fee
        ");
        $this->db->from("ims_hris.training_head");

        if(!empty($dept)) {
            $this->db->where("th_dept_code", $dept);
        }

        if(!empty($month)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'MM'),'') = '$month'");
        }

        if (!empty($year)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'YYYY'),'') = '$year'");
        }
        
        $this->db->where("COALESCE(th_status, 'ENTRY') = 'APPROVE'");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->order_by("th_date_from, th_date_to, th_training_title, th_ref_id");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // STAFF LIST -postgres
    public function getTrStaffList($refid)
    {
        $this->db->select("sth_staff_id,
        sm_staff_name,
        sth_status,
        ss_job_status,
        sjs_status_desc,
        sth_apply_date,
        to_char(sth_apply_date, 'dd/mm/yyyy') sth_apply_date2,
        sth_fee_code||' - '||tfs_desc as fee_code_desc,
        tc_amount,
        sth_fee_code,
        sth_recommender_reason
        ");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->join("ims_hris.staff_main", "sm_staff_id = sth_staff_id", "left");
        $this->db->join("ims_hris.staff_service", "ss_staff_id = sth_staff_id", "left");
        $this->db->join("ims_hris.staff_job_status", "sjs_status_code = ss_job_status", "left");
        $this->db->join("ims_hris.training_fee_setup", "tfs_code = sth_fee_code", "left");
        $this->db->join("ims_hris.training_cost", "tc_cost_code = 'T001' and tc_training_refid = '$refid'", "left");
        $this->db->where("sth_status = 'RECOMMEND'");
        $this->db->where("sth_training_refid", $refid);
        $this->db->order_by("get_staff_name(sth_staff_id)");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // GET KTP -postgres
    public function getKtp()
    {
        $this->db->select("dm_director");
        $this->db->from("ims_hris.department_main, ims_hris.training_fee_setup");
        $this->db->where("dm_dept_code = tfs_dcr_approve");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // GET REGISTRAR -postgres
    public function getRegistrar()
    {
        $this->db->select("dm_director");
        $this->db->from("ims_hris.department_main, ims_hris.training_fee_setup");
        $this->db->where("dm_dept_code = tfs_registrar_approve");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // STH DETL -postgres
    public function getSTHDetl($refid, $staff_id)
    {
        $this->db->select("sth_staff_id,
        sth_training_refid,
        sth_fee_code,
        sth_status
        ");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->where("sth_staff_id", $staff_id);
        $this->db->where("sth_training_refid", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // EVALUATOR DETL -postgres
    public function getEvaDetl($refid, $staff_id)
    {
        $this->db->select("sth_verify_by");
        $this->db->from("ims_hris.staff_training_head, ims_hris.training_head_detl, ims_hris.training_head");
        $this->db->where("sth_staff_id", $staff_id);
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("COALESCE(thd_evaluation,'N') = 'Y'");
        $this->db->where("thd_ref_id = sth_training_refid");
        $this->db->where("th_ref_id = sth_training_refid");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // UPDATE TRAINING HEAD (APPROVE) -postgres
    public function updateApprove($refid, $sid, $fee_code, $cur_usr_id, $rem, $eva_id)
    {
        $curr_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_fee_code" => $fee_code,
            "sth_status" => 'RECOMMEND_BSM',
            "sth_recommend_by" => $cur_usr_id,
            "sth_recommender_reason" => $rem,
            "sth_evaluator_id" => $eva_id
        );

        $this->db->set("sth_recommend_date", $curr_date, false);

        $this->db->where("sth_staff_id", $sid);
        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // UPDATE TRAINING HEAD KTP / REG -postgres
    public function updateApproveKtpReg($refid, $sid, $fee_code, $cur_usr_id, $rem, $eva_id)
    {
        $curr_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_fee_code" => $fee_code,
            "sth_status" => 'APPROVE',
            "sth_approve_by" => $cur_usr_id,
            "sth_approve_reason" => $rem,
            "sth_recommender_reason" => $rem,
            "sth_evaluator_id" => $eva_id
        );

        $this->db->set("sth_approve_date", $curr_date, false);

        $this->db->where("sth_staff_id", $sid);
        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // UPDATE TRAINING HEAD (REJECT) -postgres
    public function updateReject($refid, $sid, $fee_code, $cur_usr_id, $rem)
    {
        $curr_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_fee_code" => $fee_code,
            "sth_status" => 'REJECT',
            "sth_recommend_by" => $cur_usr_id,
            "sth_recommender_reason" => $rem
        );

        $this->db->set("sth_recommend_date", $curr_date, false);

        $this->db->where("sth_staff_id", $sid);
        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // UPDATE TRAINING HEAD (REJECT - KTP / REG) -postgres
    public function updateRejectKtpReg($refid, $sid, $fee_code, $cur_usr_id, $rem)
    {
        $curr_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_fee_code" => $fee_code,
            "sth_status" => 'REJECT',
            "sth_approve_by" => $cur_usr_id,
            "sth_approve_reason" => $rem,
            "sth_recommender_reason" => $rem
        );

        $this->db->set("sth_approve_date", $curr_date, false);

        $this->db->where("sth_staff_id", $sid);
        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // GET KTP / SUPERIOR -postgres
    public function getKtpSup()
    {
        $this->db->select("dm_director");
        $this->db->from("ims_hris.department_main, ims_hris.training_fee_setup");
        $this->db->where("dm_dept_code = tfs_dcr_approve");
        $this->db->where("tfs_code = '001'");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // GET REGISTRAR / SUPERIOR -postgres
    public function getRegSup()
    {
        $this->db->select("dm_director");
        $this->db->from("ims_hris.department_main, ims_hris.training_fee_setup");
        $this->db->where("dm_dept_code = tfs_registrar_approve");
        $this->db->where("tfs_code = '002'");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // SEND APPROVE MEMO 001 -hold postgres
    public function SendApproveMemo001($cur_usr_id, $refid, $ktp_sup, $cc_user) 
    {
        $sts = null;

		$sql = oci_parse($this->db->conn_id, "begin :bindOutput1 := EXTERNAL_TRAINING.SendApproveMemo001(:bind1,:bind2,:bind3,:bind4); end;");
		oci_bind_by_name($sql, ":bind1", $cur_usr_id);	//IN
		oci_bind_by_name($sql, ":bind2", $refid);		//IN
        oci_bind_by_name($sql, ":bind3", $ktp_sup);		//IN
        oci_bind_by_name($sql, ":bind4", $cc_user);		//IN
        oci_bind_by_name($sql, ":bindOutput1", $sts);	//OUT
		$q = oci_execute($sql, OCI_DEFAULT); 
		
        if ($q === FALSE) {
			return 0;
		}
		
        return 1;
        // var_dump($q); 	
    } 

    // SEND APPROVE MEMO 002 -hold postgres
    public function SendApproveMemo002($cur_usr_id, $refid, $reg_sup, $cc_user) 
    {
        $sts = null;

		$sql = oci_parse($this->db->conn_id, "begin :bindOutput1 := EXTERNAL_TRAINING.SendApproveMemo002(:bind1,:bind2,:bind3,:bind4); end;");
		oci_bind_by_name($sql, ":bind1", $cur_usr_id);	//IN
		oci_bind_by_name($sql, ":bind2", $refid);		//IN
        oci_bind_by_name($sql, ":bind3", $reg_sup);		//IN
        oci_bind_by_name($sql, ":bind4", $cc_user);		//IN
        oci_bind_by_name($sql, ":bindOutput1", $sts);	//OUT
		$q = oci_execute($sql, OCI_DEFAULT); 
		
        if ($q === FALSE) {
			return 0;
		}
		
        return 1;
        // var_dump($q); 	
    } 

    // SEND APPROVE MEMO 003 -hold postgres
    public function SendApproveMemo003($cur_usr_id, $refid, $sid) 
    {
        $sts = null;

		$sql = oci_parse($this->db->conn_id, "begin :bindOutput1 := EXTERNAL_TRAINING.SendApproveMemo003(:bind1,:bind2,:bind3); end;");
		oci_bind_by_name($sql, ":bind1", $cur_usr_id);	//IN
		oci_bind_by_name($sql, ":bind2", $refid);		//IN
        oci_bind_by_name($sql, ":bind3", $sid);		//IN
        oci_bind_by_name($sql, ":bindOutput1", $sts);	//OUT
		$q = oci_execute($sql, OCI_DEFAULT); 
		
        if ($q === FALSE) {
			return 0;
		}
		
        return 1;
        // var_dump($q); 	
    } 

    // SEND APPROVE MEMO 004 -hold postgres
    public function SendApproveMemo004($cur_usr_id, $refid, $sid) 
    {
        $sts = null;

		$sql = oci_parse($this->db->conn_id, "begin :bindOutput1 := EXTERNAL_TRAINING.SendApproveMemo004(:bind1,:bind2,:bind3); end;");
		oci_bind_by_name($sql, ":bind1", $cur_usr_id);	//IN
		oci_bind_by_name($sql, ":bind2", $refid);		//IN
        oci_bind_by_name($sql, ":bind3", $sid);		//IN
        oci_bind_by_name($sql, ":bindOutput1", $sts);	//OUT
		$q = oci_execute($sql, OCI_DEFAULT); 
		
        if ($q === FALSE) {
			return 0;
		}
		
        return 1;
        // var_dump($q); 	
    } 

    // GET REJECT MEMO DETL -postgres
    public function getRejMemoDetl($refid, $sid)
    {
        $this->db->select("sth_staff_id, 
        th_training_title, 
        coalesce(th_training_venue,'') sth_venue,
        to_char(th_date_from,'dd/mm/yyyy') th_date_from,
        to_char(th_date_to,'dd/mm/yyyy') th_date_to, 
        sth_status,
		to_char(th_date_from-7,'dd/mm/yyyy') th_due_date");
        $this->db->from("ims_hris.training_head");
        // $this->db->where("TH_REF_ID(+) = STH_TRAINING_REFID");
        $this->db->join("ims_hris.staff_training_head", "th_ref_id = sth_training_refid", "left");
        $this->db->where("(th_ref_id = '$refid'
		and sth_staff_id = '$sid')");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // SEND MEMO -hold postgres
    public function createMemo($cur_usr_id, $sid, $cc = null, $memoTitle, $memoContent) 
    {
		$sql = oci_parse($this->db->conn_id, "begin create_memo(:bind1,:bind2,null,:bind3,:bind4); end;");
		oci_bind_by_name($sql, ":bind1", $cur_usr_id);		    //IN
		oci_bind_by_name($sql, ":bind2", $sid);				    //IN
		oci_bind_by_name($sql, ":bind3", $memoTitle, 255);		//IN
		oci_bind_by_name($sql, ":bind4", $memoContent, 4000);	//IN
		$q = oci_execute($sql, OCI_DEFAULT); 
		
        if ($q === FALSE) {
			return 0;
		}
		
		return 1;	
    } 

    /*===========================================================
       ASSIGN TRAINING COST TO STAFF - ATF029
    =============================================================*/

    // GET YEAR DROPDOWN -postgres
    public function getYearListTrCost() 
    {		
        $this->db->select("to_char(th_date_from,'yyyy') as cm_year");
        $this->db->from("training_head");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->group_by("TO_CHAR(th_date_from,'YYYY') ");
        $this->db->order_by("TO_CHAR(th_date_from,'YYYY') desc");
        $q = $this->db->get();
                
        return $q->result_case('UPPER');
    } 

    // STAFF LIST -postgres
    public function getTrStaffCostList($refid)
    {
        $this->db->select("stcm_staff_id,
        stcm_training_refid,
        stcm_total_amount,
        sm_staff_name
        ");
        $this->db->from("ims_hris.staff_training_cost_main");
        $this->db->join("ims_hris.staff_main", "sm_staff_id = stcm_staff_id", "left");
        $this->db->where("stcm_training_refid", $refid);
        $this->db->order_by("UPPER(get_staff_name(stcm_staff_id))");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // PAYMENT DETAILS -postgres
    public function getPaymentDetl($staff_id, $refid)
    {
        $this->db->select("stcd_staff_id,
        stcd_training_refid,
        stcd_cost_code,
        stcd_amount,
        tct_desc
        ");
        $this->db->from("ims_hris.staff_training_cost_detl");
        $this->db->join("ims_hris.training_cost_type", "tct_code = stcd_cost_code", "left");
        $this->db->where("stcd_staff_id", $staff_id);
        $this->db->where("stcd_training_refid", $refid);
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // APPLICANT DETL -postgres
    public function getApplicantDetl($staff_id)
    {
        $this->db->select("sm_staff_name,
        sm_staff_id
        ");
        $this->db->from("ims_hris.staff_main");
        $this->db->where("sm_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // STAFF TRAINING COST DETL -postgres
    public function getTrStaffCostDetl($refid, $staff_id)
    {
        $this->db->select("stcm_staff_id,
        stcm_training_refid,
        stcm_total_amount
        ");
        $this->db->from("ims_hris.staff_training_cost_main");
        $this->db->where("stcm_training_refid", $refid);
        $this->db->where("stcm_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // UPDATE STAFF_TRAINING_COST_MAIN -postgres
    public function saveUpdAssignStaffCost($form) 
    {
        $data = array(
            "stcm_total_amount" => $form['amount']
        );

        $this->db->where("stcm_training_refid", $form['refid']);
        $this->db->where("stcm_staff_id", $form['staff_id']);

        return $this->db->update("ims_hris.staff_training_cost_main", $data);
    }

    // COST CODE DD -postgres
    public function getCostCodeDD2($refid, $staff_id)
    {
        $this->db->select("tc_cost_code,
        tct_desc, 
        tc_amount,
        tc_cost_code||' - '||tct_desc tc_cost_code_desc
        ");
        $this->db->from("ims_hris.training_cost, ims_hris.training_cost_type");
        $this->db->where("tc_cost_code = tct_code");
        $this->db->where("tc_training_refid", $refid);
        $this->db->where("tc_cost_code not in (select stcd_cost_code
        from ims_hris.staff_training_cost_detl
        where stcd_training_refid = '$refid'
        and stcd_staff_id = '$staff_id')");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // COST CODE DETL -postgres
    public function getCostCodeDetl($refid, $staff_id, $cost_code)
    {
        $this->db->select("tc_cost_code,
        tct_desc, 
        tc_amount
        ");
        $this->db->from("ims_hris.training_cost, ims_hris.training_cost_type");
        $this->db->where("tc_cost_code = tct_code");
        $this->db->where("tc_training_refid", $refid);
        $this->db->where("tc_cost_code not in (select stcd_cost_code
        from ims_hris.staff_training_cost_detl
        where stcd_training_refid = '$refid'
        and stcd_staff_id = '$staff_id')");
        $this->db->where("tc_cost_code", $cost_code);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // INSERT STAFF_TRAINING_COST_DETL -postgres
    public function savePayDetl($form) 
    {
        $data = array(
            "stcd_staff_id" => $form['staff_id'],
            "stcd_training_refid" => $form['refid'],
            "stcd_cost_code" => $form['cost_code'],
            "stcd_amount" => $form['amount']
        );

        return $this->db->insert("ims_hris.staff_training_cost_detl", $data);
    }

    // SUM STCD -postgres
    public function getSumSTCD($refid, $staff_id)
    {
        $this->db->select("coalesce(sum(stcd_amount),0) sum_stcd");
        $this->db->from("ims_hris.staff_training_cost_detl");
        $this->db->where("stcd_staff_id", $staff_id);
        $this->db->where("stcd_training_refid", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // UPDATE STAFF_TRAINING_COST_MAIN 2 -postgres
    public function saveUpdateStfTrCostMain($refid, $staff_id, $amt) 
    {
        $data = array(
            "stcm_total_amount" => $amt
        );

        $this->db->where("stcm_training_refid", $refid);
        $this->db->where("stcm_staff_id", $staff_id);

        return $this->db->update("ims_hris.staff_training_cost_main", $data);
    }

    // CHECK STCD -postgres
    public function checkSTCD($refid, $staff_id)
    {
        $this->db->select("1");
        $this->db->from("ims_hris.staff_training_cost_detl");
        $this->db->where("stcd_staff_id", $staff_id);
        $this->db->where("stcd_training_refid", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // DELETE STCM -postgres
    public function delStfTrCost($refid, $staff_id) 
    {
        $this->db->where("stcm_training_refid", $refid);
        $this->db->where("stcm_staff_id", $staff_id);
        return $this->db->delete('ims_hris.staff_training_cost_main');
    }

    // DELETE STCD -postgres
    public function delPayDetl($refid, $staff_id, $code_code) 
    {
        $this->db->where("stcd_training_refid", $refid);
        $this->db->where("stcd_staff_id", $staff_id);
        $this->db->where("stcd_cost_code", $code_code);
        return $this->db->delete('ims_hris.staff_training_cost_detl');
    }

    /*===========================================================
       APPROVE TRAINING BY MPE FOR EXTERNAL AGENCY - ATF143
    =============================================================*/

    // GET YEAR DROPDOWN -postgres
    public function getYearListAppTr2() 
    {		
        $this->db->select("to_char(th_date_from,'yyyy') as cm_year");
        $this->db->from("training_head");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->group_by("TO_CHAR(th_date_from,'YYYY')");
        $this->db->order_by("TO_CHAR(th_date_from,'YYYY') desc");
        $q = $this->db->get();
                
        return $q->result_case('UPPER');
    } 

    // GET TRAINING LIST -postgres
    public function getTrainingList5($dept, $month, $year)
    {
        $this->db->select("th_ref_id,
        th_training_title,
        to_char(th_date_from, 'dd/mm/yyyy') th_date_from2,
        to_char(th_date_to, 'dd/mm/yyyy') th_date_to2,
        th_training_fee
        ");
        $this->db->from("ims_hris.training_head");

        if(!empty($dept)) {
            $this->db->where("th_dept_code", $dept);
        }

        if(!empty($month)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'MM'),'') = '$month'");
        }

        if (!empty($year)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'YYYY'),'') = '$year'");
        }
        
        $this->db->where("COALESCE(th_status, 'ENTRY') = 'APPROVE'");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->where("th_training_fee >= '5000.01'");
        $this->db->order_by("th_date_from, th_date_to, th_training_title");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // STAFF LIST -postgres
    public function getTrStaffList3($refid)
    {
        $this->db->select("sth_staff_id,
        sm_staff_name,
        sth_status,
        ss_job_status,
        sjs_status_desc,
        sth_apply_date,
        to_char(sth_apply_date, 'dd/mm/yyyy') sth_apply_date2,
        sth_fee_code||' - '||tfs_desc as fee_code_desc,
        tc_amount,
        sth_fee_code,
        sth_recommender_reason,
        sth_approve_reason
        ");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->join("ims_hris.staff_main", "sm_staff_id = sth_staff_id", "LEFT");
        $this->db->join("ims_hris.staff_service", "ss_staff_id = sth_staff_id", "LEFT");
        $this->db->join("ims_hris.staff_job_status", "sjs_status_code = ss_job_status", "LEFT");
        $this->db->join("ims_hris.training_fee_setup", "tfs_code = sth_fee_code", "LEFT");
        $this->db->join("ims_hris.training_cost", "tc_cost_code = 'T001' AND tc_training_refid = '$refid'", "LEFT");
        $this->db->where("sth_status = 'RECOMMEND_BSM'");
        $this->db->where("sth_fee_code in (select tfs_code from ims_hris.training_fee_setup where coalesce(tfs_mpe_approve,'N') = 'Y')");
        $this->db->where("sth_training_refid", $refid);
        $this->db->order_by("get_staff_name(sth_staff_id)");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // UPDATE STH -postgres
    public function updateSTH($refid, $sid, $cur_usr_id, $rem)
    {
        $curr_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_status" => 'APPROVE',
            "sth_approve_by" => $cur_usr_id,
            "sth_approve_reason" => $rem
        );

        $this->db->set("sth_approve_date", $curr_date, false);

        $this->db->where("sth_staff_id", $sid);
        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // UPDATE STD -postgres
    public function updateSTD($refid, $sid, $mpe_date)
    {
        if(!empty($mpe_date)) {
            $date = "TO_DATE('".$mpe_date."', 'DD/MM/YYYY')";
            $this->db->set("std_mpe_date", $date, false);
        } 

        $this->db->where("std_training_refid", $refid);
        $this->db->where("std_staff_id", $sid);

        return $this->db->update("ims_hris.staff_training_detl");
    }

    // GET DM_DIRECTOR -postgres
    public function getDeptDirector($sid)
    {
        $this->db->select("dm_director");
        $this->db->from("ims_hris.department_main, ims_hris.staff_main");
        $this->db->where("sm_dept_code = dm_dept_code");
        $this->db->where("sm_staff_id", $sid);
        $q = $this->db->get();
        
        return $q->row();
    }

    // SEND MEMO 2 -hold postgres
    public function createMemo2($cur_usr_id, $sid, $cc_hod, $memoTitle, $memoContent) 
    {
		$sql = oci_parse($this->db->conn_id, "begin create_memo(:bind1,:bind2,:bind3,:bind4,:bind5); end;");
		oci_bind_by_name($sql, ":bind1", $cur_usr_id);		    //IN
        oci_bind_by_name($sql, ":bind2", $sid);				    //IN
        oci_bind_by_name($sql, ":bind3", $cc_hod);				//IN
		oci_bind_by_name($sql, ":bind4", $memoTitle, 255);		//IN
		oci_bind_by_name($sql, ":bind5", $memoContent, 4000);	//IN
		$q = oci_execute($sql, OCI_DEFAULT); 
		
        if ($q === FALSE) {
			return 0;
		}
		
		return 1;	
    } 

    // UPDATE STH 2 -postgres
    public function updateSTH2($refid, $sid, $cur_usr_id, $rem)
    {
        $curr_date = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_status" => 'REJECT',
            "sth_approve_by" => $cur_usr_id,
            "sth_approve_reason" => $rem
        );

        $this->db->set("sth_approve_date", $curr_date, false);

        $this->db->where("sth_staff_id", $sid);
        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    /*===========================================================
       ASSIGN TRAINING FOR EXTERNAL AGENCY - ATF142
    =============================================================*/

    // GET TRAINING LIST 6 -postgres
    public function getTrainingList6($dept, $month, $year)
    {
        $this->db->select("th_ref_id,
        th_training_title,
        TO_CHAR(th_date_from, 'DD/MM/YYYY') th_date_from2,
        TO_CHAR(th_date_to, 'DD/MM/YYYY') th_date_to2,
        th_training_fee
        ");
        $this->db->from("ims_hris.training_head");

        if(!empty($dept)) {
            $this->db->where("th_dept_code", $dept);
        }

        if(!empty($month)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'MM'),'') = '$month'");
        }

        if (!empty($year)) {
            $this->db->where("COALESCE(TO_CHAR(th_date_from,'YYYY'),'') = '$year'");
        }
        
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->order_by("th_date_from, th_date_to, th_training_title, th_ref_id");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // STAFF LIST -postgres
    public function getTrStaffList4($refid)
    {
        $this->db->select("sth_staff_id,
        sm_staff_name,
        sth_status,
        sth_apply_date,
        to_char(sth_apply_date, 'dd/mm/yyyy') sth_apply_date2,
        sth_fee_code||' - '||tfs_desc as fee_code_desc,
        tc_amount,
        sth_fee_code,
        sth_recommender_reason,
        sth_approve_reason,
        sm_dept_code staff_dept,
        tpr_desc,
        sth_staff_training_benefit,
        sth_dept_training_benefit,
        case sth_hod_evaluation
        WHEN 'Y' THEN 'Yes'
        WHEN 'N' THEN 'No'
        END sth_hod_evaluation2,
        sth_hod_evaluation
        ");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->join("ims_hris.staff_main", "sm_staff_id = sth_staff_id", "left");
        $this->db->join("ims_hris.training_participant_role", "tpr_code = sth_participant_role", "left");
        // $this->db->join("STAFF_SERVICE", "SS_STAFF_ID = STH_STAFF_ID", "LEFT");
        // $this->db->join("STAFF_JOB_STATUS", "SJS_STATUS_CODE = SS_JOB_STATUS", "LEFT");
        $this->db->join("ims_hris.training_fee_setup", "tfs_code = sth_fee_code", "left");
        $this->db->join("ims_hris.training_cost", "tc_cost_code = 'T001' and tc_training_refid = '$refid'", "LEFT");
        $this->db->where("sth_training_refid", $refid);
        $this->db->order_by("sth_staff_id, upper(get_staff_dept(sth_staff_id)), sth_status, upper(get_staff_name(sth_staff_id))");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // ROLE DD -postgres
    public function getRoleDD()
    {
        $this->db->select("tpr_code,
        tpr_desc,
        tpr_code||' - '||tpr_desc tpr_code_desc
        ");
        $this->db->from("ims_hris.training_participant_role");
        $this->db->order_by("tpr_code");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // FEE DD -postgres
    public function getFeeDD()
    {
        $this->db->select("tfs_code,
        tfs_desc,
        tfs_code||' - '||tfs_desc tfs_code_desc
        ");
        $this->db->from("ims_hris.training_fee_setup");
        $this->db->order_by("tfs_code");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // STAFF DEPT -postgres
    public function getStaffDept($staff_id)
    {
        $this->db->select("sm_dept_code");
        $this->db->from("ims_hris.staff_main");
        $this->db->where("sm_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // TRAINING FEE -postgres
    public function getTrainingFee($refid)
    {
        $this->db->select("tc_amount");
        $this->db->from("ims_hris.training_cost");
        $this->db->where("tc_cost_code = 'T001'");
        $this->db->where("tc_training_refid", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // ASSIGN STAFF DETL -postgres
    public function assignStaffDetl($refid, $staff_id)
    {
        $this->db->select("sth_staff_id, sth_training_refid");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // TRAINING HEAD DETL -postgres
    public function getTrDetl($refid)
    {
        $this->db->select("th_attendance_type, coalesce(th_evaluation_compulsory,'N') th_evaluation_compulsory");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_ref_id", $refid);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // ASSIGN NEW STAFF -postgres
    public function saveAssignStaff($form, $sth_complete, $eva_id)
    {
        $curr_usr = $this->staff_id;
        $curr_date = "date_trunc('second', NOW()::timestamp)";
        $status = $form['status'];
        $role = $form['role'];

        $sth_approve_by = '';
        $sth_approve_date = '';
        $sth_verify_by = '';
        $sth_verify_date = '';
        $sth_recommend_by = '';
        $sth_recommend_date = '';

        // AUTO ASSIGN ROLE PESERTA
        if(empty($form['role'])) {
            $role = 'D';
        }

        if($status == 'APPROVE' || $status == 'REJECT') {
            $sth_approve_by = $curr_usr;
            $sth_approve_date = $curr_date;
        } elseif($status == 'RECOMMEND') {
            $sth_verify_by = $curr_usr;
            $sth_verify_date = $curr_date;
        } elseif($status == 'RECOMMEND_BSM') {
            $sth_recommend_by = $curr_usr;
            $sth_recommend_date = $curr_date;
        }

        $data = array(
            "sth_training_refid" => $form['refid'],
            "sth_staff_id" => $form['staff_id_form'],
            "sth_participant_role" =>  $role,
            "sth_status" =>  $form['status'],
            "sth_fee_code" =>  $form['fee_category'],
            "sth_staff_training_benefit" =>  $form['tr_benefit_stf'],
            "sth_dept_training_benefit" =>  $form['tr_benefit_dept'],
            "sth_recommender_reason" =>  $form['remark'],
            "sth_approve_reason" =>  $form['remark_other'],
            "sth_hod_evaluation" =>  $form['eva_status'],
            "sth_enter_by" => $curr_usr,
        );

        $this->db->set("sth_enter_date", $curr_date, false);

        // APPROVE & REJECT
        if(!empty($sth_approve_by)) {
            $this->db->set("sth_approve_by", $sth_approve_by);
        }

        if(!empty($sth_approve_date)) {
            $this->db->set("sth_approve_date", $sth_approve_date, false);
        }

        // RECOMMEND
        if(!empty($sth_verify_by)) {
            $this->db->set("sth_verify_by", $sth_verify_by);
        }

        if(!empty($sth_verify_date)) {
            $this->db->set("sth_verify_date", $sth_verify_date, false);
        }

        // RECOMMEND_BSM
        if(!empty($sth_recommend_by)) {
            $this->db->set("sth_recommend_by", $sth_verify_by);
        }

        if(!empty($sth_recommend_date)) {
            $this->db->set("sth_recommend_date", $sth_recommend_date, false);
        } 

        // STH_COMPLETE
        if(!empty($sth_complete)) {
            $this->db->set("sth_complete", $sth_complete);
        }

        // STH_EVALUATOR_ID
        if(!empty($eva_id)) {
            $this->db->set("sth_evaluator_id", $eva_id);
        }

        return $this->db->insert("ims_hris.staff_training_head", $data);
    }

    // ASSIGN STAFF DETL 2 -postgres
    public function getAssignStfDetl($refid, $staff_id)
    {
        $this->db->select("sth_staff_id,
        sm_staff_name,
        sth_status,
        sth_apply_date,
        to_char(sth_apply_date, 'dd/mm/yyyy') sth_apply_date2,
        sth_fee_code||' - '||tfs_desc as fee_code_desc,
        tc_amount,
        sth_fee_code,
        sth_recommender_reason,
        sth_approve_reason,
        sm_dept_code staff_dept,
        tpr_desc,
        sth_staff_training_benefit,
        sth_dept_training_benefit,
        case sth_hod_evaluation
        WHEN 'Y' THEN 'Yes'
        WHEN 'N' THEN 'No'
        end sth_hod_evaluation2,
        sth_hod_evaluation,
        sth_participant_role
        ");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->join("ims_hris.staff_main", "sm_staff_id = sth_staff_id", "LEFT");
        $this->db->join("ims_hris.training_participant_role", "tpr_code = sth_participant_role", "LEFT");
        $this->db->join("ims_hris.training_fee_setup", "tfs_code = sth_fee_code", "LEFT");
        $this->db->join("ims_hris.training_cost", "TC_COST_CODE = 'T001' and tc_training_refid = '$refid'", "LEFT");
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // GET EVALUATOR DETL -postgres
    public function getEvaluatorDetl($staff_id)
    {
        $this->db->select("dm_director");
        $this->db->from("ims_hris.department_main, ims_hris.staff_main");
        $this->db->where("sm_dept_code = dm_dept_code");
        $this->db->where("sm_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // SAVE EDIT ASSIGN STAFF -postgres
    public function saveEditAssignStaff($form, $sth_complete)
    {
        $curr_usr = $this->staff_id;
        $curr_date = "date_trunc('second', NOW()::timestamp)";
        $status = $form['status'];
        $role = $form['role'];

        $sth_approve_by = '';
        $sth_approve_date = '';
        $sth_verify_by = '';
        $sth_verify_date = '';
        $sth_recommend_by = '';
        $sth_recommend_date = '';

        // AUTO ASSIGN ROLE PESERTA
        if(empty($form['role'])) {
            $role = 'D';
        }

        if($status == 'APPROVE' || $status == 'REJECT') {
            $sth_approve_by = $curr_usr;
            $sth_approve_date = $curr_date;
        } elseif($status == 'RECOMMEND') {
            $sth_verify_by = $curr_usr;
            $sth_verify_date = $curr_date;
        } elseif($status == 'RECOMMEND_BSM') {
            $sth_recommend_by = $curr_usr;
            $sth_recommend_date = $curr_date;
        }

        $data = array(
            "sth_participant_role" =>  $role,
            "sth_status" =>  $form['status'],
            "sth_fee_code" =>  $form['fee_category'],
            "sth_staff_training_benefit" =>  $form['tr_benefit_stf'],
            "sth_dept_training_benefit" =>  $form['tr_benefit_dept'],
            "sth_recommender_reason" =>  $form['remark'],
            "sth_approve_reason" =>  $form['remark_other'],
            "sth_hod_evaluation" =>  $form['eva_status'],
            "sth_enter_by" => $curr_usr,
        );

        $this->db->set("sth_enter_date", $curr_date, false);

        // APPROVE & REJECT
        if(!empty($sth_approve_by)) {
            $this->db->set("sth_approve_by", $sth_approve_by);
        }

        if(!empty($sth_approve_date)) {
            $this->db->set("sth_approve_date", $sth_approve_date, false);
        }

        // RECOMMEND
        if(!empty($sth_verify_by)) {
            $this->db->set("sth_verify_by", $sth_verify_by);
        }

        if(!empty($sth_verify_date)) {
            $this->db->set("sth_verify_date", $sth_verify_date, false);
        }

        // RECOMMEND_BSM
        if(!empty($sth_recommend_by)) {
            $this->db->set("sth_recommend_by", $sth_recommend_by);
        }

        if(!empty($sth_recommend_date)) {
            $this->db->set("sth_recommend_date", $sth_recommend_date, false);
        }

        // STH_COMPLETE
        if(!empty($sth_complete)) {
            $this->db->set("sth_complete", $sth_complete);
        }

        // // STH_EVALUATOR_ID
        // if(!empty($eva_id)) {
        //     $this->db->set("STH_EVALUATOR_ID", $eva_id);
        // }
        
        $this->db->where("sth_training_refid", $form['refid']);
        $this->db->where("sth_staff_id", $form['staff_id_form']);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // GET SUM CNT -postgres
    public function getSumCNT($staff_id)
    {
        // $query = $this->db->query("select sum(cnt) sum_cnt from
        // (SELECT sum(sth_cpd_mark)cnt
        // FROM ims_hris.staff_training_head, ims_hris.training_head
        // WHERE sth_training_refid=th_ref_id
        // AND sth_staff_id='$staff_id'
        // AND sth_status='APPROVE'
        // AND sth_cpd_competency='UMUM'
        // AND TO_CHAR(th_date_from,'YYYY')=TO_CHAR(current_date,'YYYY')
        // UNION
        // select sum(scm_cpd_mark)cnt
        // FROM ims_hris.staff_conference_main,ims_hris.conference_main,ims_hris.staff_conference_rep, ims_hris.cpd_head
        // outer join 
        // WHERE scm_refid=cm_refid
        // AND scm_staff_id = SCR_STAFF_ID(+)
        // AND SCM_REFID = SCR_REFID(+)
        // AND ch_training_refid=cm_refid
        // AND scm_staff_id='$staff_id'
        // AND TO_CHAR(cm_date_to,'yyyy')=TO_CHAR(current_date,'YYYY')
        // AND scm_status = 'APPROVE'
        // AND ((ch_report_submission = 'N')
        // OR (ch_report_submission='Y' AND scr_status = 'VERIFY_TNCA'))
        // AND scm_cpd_competency ='UMUM')");

        $query = $this->db->query("select sum(cnt) sum_cnt from
        (SELECT sum(sth_cpd_mark)cnt
        FROM ims_hris.staff_training_head, ims_hris.training_head
        WHERE sth_training_refid=th_ref_id
        AND sth_staff_id='$staff_id'
        AND sth_status='APPROVE'
        AND sth_cpd_competency='UMUM'
        AND TO_CHAR(th_date_from,'YYYY')=TO_CHAR(current_date,'YYYY')
        UNION
        select sum(scm_cpd_mark)cnt
        FROM ims_hris.staff_conference_main
        full outer join ims_hris.conference_main on scm_refid=cm_refid
        full outer join ims_hris.staff_conference_rep on scm_staff_id = scr_staff_id and scm_refid = scr_refid
        full outer join ims_hris.cpd_head on ch_training_refid=cm_refid
        where scm_staff_id='$staff_id'
        and to_char(cm_date_to,'yyyy')=to_char(current_date,'YYYY')
        and scm_status = 'APPROVE'
        and ((ch_report_submission = 'N')
        or (ch_report_submission='Y' and scr_status = 'VERIFY_TNCA'))
        and scm_cpd_competency ='UMUM') as a");

        $row = $query->row_case('UPPER');
        
        return $row;
    }

    // GET SUM CNT 2 -postgres
    public function getSumCNT2($staff_id)
    {
        // $query = $this->db->query("SELECT SUM(CNT) SUM_CNT2 FROM
        // (SELECT SUM(STH_CPD_MARK)CNT
        // FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        // WHERE STH_TRAINING_REFID=TH_REF_ID
        // AND STH_STAFF_ID='$staff_id'
        // AND STH_STATUS='APPROVE'
        // AND STH_CPD_COMPETENCY='KHUSUS'
        // AND TO_CHAR(TH_DATE_FROM,'YYYY')=TO_CHAR(SYSDATE,'YYYY')
        // UNION
        // SELECT SUM(SCM_CPD_MARK)CNT
        // FROM STAFF_CONFERENCE_MAIN,CONFERENCE_MAIN,STAFF_CONFERENCE_REP, CPD_HEAD
        // WHERE SCM_REFID=CM_REFID
        // AND SCM_STAFF_ID = SCR_STAFF_ID(+)
        // AND SCM_REFID = SCR_REFID(+)
        // AND CH_TRAINING_REFID=CM_REFID
        // AND SCM_STAFF_ID='$staff_id'
        // AND TO_CHAR(CM_DATE_TO,'yyyy')=TO_CHAR(SYSDATE,'YYYY')
        // AND SCM_STATUS = 'APPROVE'
        // AND ((CH_REPORT_SUBMISSION = 'N')
        // OR (CH_REPORT_SUBMISSION='Y' AND SCR_STATUS = 'VERIFY_TNCA'))
        // AND SCM_CPD_COMPETENCY ='KHUSUS')");

        $query = $this->db->query("select sum(cnt) sum_cnt2 from
        (select sum(sth_cpd_mark)cnt
        from ims_hris.staff_training_head, ims_hris.training_head
        where sth_training_refid=th_ref_id
        AND sth_staff_id='$staff_id'
        AND sth_status='APPROVE'
        AND sth_cpd_competency='KHUSUS'
        AND to_char(th_date_from,'YYYY')=to_char(current_date,'YYYY')
        UNION
        SELECT sum(scm_cpd_mark)cnt
        FROM ims_hris.staff_conference_main
        full outer join ims_hris.conference_main on scm_refid=cm_refid
        full outer join ims_hris.staff_conference_rep on scm_staff_id = scr_staff_id and scm_refid = scr_refid
        full outer join ims_hris.cpd_head on ch_training_refid=cm_refid
        where scm_staff_id='$staff_id'
        and to_char(cm_date_to,'yyyy')=to_char(current_date,'YYYY')
        and scm_status = 'APPROVE'
        and ((ch_report_submission = 'N')
        or (ch_report_submission='Y' AND scr_status = 'VERIFY_TNCA'))
        and scm_cpd_competency ='KHUSUS')");

        $row = $query->row_case('UPPER');
        
        return $row;
    }

    // GET SUM CNT 3 -postgres
    public function getSumCNT3($staff_id)
    {
        // $query = $this->db->query("SELECT SUM(CNT) SUM_CNT3 FROM
        // (SELECT SUM(STH_CPD_MARK) CNT
        // FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        // WHERE STH_TRAINING_REFID=TH_REF_ID
        // AND STH_STAFF_ID='$staff_id'
        // AND STH_CPD_COMPETENCY IN ('KHUSUS','UMUM')
        // AND TO_CHAR(TH_DATE_FROM,'YYYY')=TO_CHAR(SYSDATE,'YYYY')
        // AND STH_STATUS='APPROVE'
        // UNION
        // SELECT SUM(SCM_CPD_MARK) CNT
        // FROM STAFF_CONFERENCE_MAIN,CONFERENCE_MAIN,STAFF_CONFERENCE_REP, CPD_HEAD
        // --WHERE SCM_REFID=CM_REFID
        // --AND SCM_STAFF_ID = SCR_STAFF_ID(+)
        // --AND SCM_REFID = SCR_REFID(+)
        // --AND CH_TRAINING_REFID=CM_REFID
        // AND SCM_STAFF_ID='$staff_id'
        // AND TO_CHAR(CM_DATE_TO,'yyyy')=TO_CHAR(SYSDATE,'YYYY')
        // AND SCM_STATUS = 'APPROVE'
        // AND ((CH_REPORT_SUBMISSION = 'N')
        // OR (CH_REPORT_SUBMISSION='Y' AND SCR_STATUS = 'VERIFY_TNCA'))
        // AND SCM_CPD_COMPETENCY IN ('KHUSUS','UMUM'))");


        $query = $this->db->query("select sum(cnt) sum_cnt3 from
        (select sum(sth_cpd_mark) cnt
        from ims_hris.staff_training_head, ims_hris.training_head
        where sth_training_refid=th_ref_id
        and sth_staff_id='$staff_id'
        and sth_cpd_competency in ('KHUSUS','UMUM')
        and to_char(th_date_from,'YYYY')=to_char(current_date,'YYYY')
        and sth_status='APPROVE'
        union
        select sum(scm_cpd_mark) cnt
        from ims_hris.staff_conference_main
        full outer join ims_hris.conference_main on scm_refid=cm_refid
        full outer join ims_hris.staff_conference_rep on scm_staff_id = scr_staff_id and scm_refid = scr_refid
        full outer join ims_hris.cpd_head on ch_training_refid=cm_refid
        and scm_staff_id='$staff_id'
        and to_char(cm_date_to,'yyyy')=to_char(current_date,'YYYY')
        and scm_status = 'APPROVE'
        and ((ch_report_submission = 'N')
        or (ch_report_submission='Y' AND scr_status = 'VERIFY_TNCA'))
        and scm_cpd_competency in ('KHUSUS','UMUM'))");

        $row = $query->row_case('UPPER');
        
        return $row;
    }

    // GET CPD DETL -postgres
    public function getCPDDetl($staff_id)
    {
        $this->db->select("sch_jum_cpd, sch_cpd_layak, cp_lnpt_weightage");
        $this->db->from("ims_hris.staff_cpd_head, ims_hris.cpd_point");
        $this->db->where("sch_tahun = cp_year");
        $this->db->where("sch_kump = cp_scheme");
        $this->db->where("sch_tahun = to_char(current_date,'YYYY')");
        $this->db->where("sch_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // UPDATE STAFF_CPD_HEAD -postgres
    public function updateSCH($staff_id, $jumum, $jkhu, $jum_cpd)
    {
        $data = array(
            "sch_jum_khusus" =>  $jkhu,
            "sch_jum_umum" =>  $jumum,
            "sch_jum_cpd" =>  $jum_cpd
        );
        
        $this->db->where("sch_staff_id", $staff_id);
        $this->db->where("sch_tahun = to_char(current_date,'YYYY')");

        return $this->db->update("ims_hris.staff_cpd_head", $data);
    }

    // UPDATE STAFF_CPD_HEAD 2 -postgres
    public function updateSCH2($staff_id, $res)
    {
        $data = array(
            "sch_peratus_lpp" =>  $res
        );
        
        $this->db->where("sch_staff_id", $staff_id);
        $this->db->where("sch_tahun = to_char(current_date,'YYYY')");

        return $this->db->update("ims_hris.staff_cpd_head", $data);
    }

    // GET TRAINING DETL -postgres
    public function getTrainingDetl($refid, $staff_id)
    {
        $this->db->select("std_cancel_reason, to_char(std_mpe_date, 'dd/mm/yyyy') std_mpe_date2, std_training_calendar, std_work_related");
        $this->db->from("ims_hris.staff_training_detl");
        $this->db->where("std_training_refid", $refid);
        $this->db->where("std_staff_id", $staff_id);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // UPDATE STAFF_TRAINING_DETL -postgres
    public function saveEditTrDetl($form)
    {
        $curr_usr = $this->staff_id;
        $curr_date = "date_trunc('second', NOW()::timestamp)";
        $status = $form['status'];
        $mpe_date = $form['mpe_date'];

        $data = array(
            "std_cancel_reason" =>  $form['cancel_reason'],
            "std_training_calendar" =>  $form['nitc'],
            "std_work_related" =>  $form['wrelated'],
        );

        if($status == 'CANCEL') {
            
            $this->db->set("std_cancel_by", $curr_usr);

            $this->db->set("std_cancel_date", $curr_date, false);
        } 

        if(!empty($mpe_date)) {
            $date = "TO_DATE('$mpe_date', 'DD/MM/YYYY')";
            $this->db->set("std_mpe_date", $date, false);
        } else {
            $this->db->set("std_mpe_date", '', true);
        }
        
        $this->db->where("std_training_refid", $form['refid']);
        $this->db->where("std_staff_id", $form['staff_id_form']);

        return $this->db->update("ims_hris.staff_training_detl", $data);
    }

    // INSERT STAFF_TRAINING_DETL -postgres
    public function saveInsTrDetl($form)
    {
        $curr_usr = $this->staff_id;
        $curr_date = "date_trunc('second', NOW()::timestamp)";
        $status = $form['status'];
        $mpe_date = $form['mpe_date'];

        $data = array(
            "std_training_refid" =>  $form['refid'],
            "std_staff_id" =>  $form['staff_id_form'],
            "std_cancel_reason" =>  $form['cancel_reason'],
            "std_training_calendar" =>  $form['nitc'],
            "std_work_related" =>  $form['wrelated'],
        );

        if($status == 'CANCEL') {
            
            $this->db->set("std_cancel_by", $curr_usr);

            $this->db->set("std_cancel_date", $curr_date, false);
        } 

        if(!empty($mpe_date)) {
            $date = "TO_DATE('$mpe_date', 'DD/MM/YYYY')";
            $this->db->set("std_mpe_date", $date, false);
        }

        return $this->db->insert("ims_hris.staff_training_detl", $data);
    }

    /*===========================================================
       REPORTS FOR EXTERNAL AGENCY - ATF144
    =============================================================*/

    // TRAINING LIST -postgres
    public function getExtTrainList()
    {
        $this->db->select("th_ref_id, th_training_title, to_char(th_date_from,'DD/MM/YYYY') th_date_from, to_char(th_date_to,'DD/MM/YYYY') th_date_to");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_internal_external = 'EXTERNAL_AGENCY'");
        $this->db->where("th_status = 'APPROVE'");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // SEARCH STAFF -postgres
    public function searchStaffMdExt($refid)
    {
        $this->db->select("sth_staff_id,
        sm_staff_name,
        sm_dept_code");
        $this->db->from("ims_hris.staff_training_head, ims_hris.staff_main, ims_hris.staff_service, ims_hris.staff_job_status");
        $this->db->where("sth_staff_id = sm_staff_id");
        $this->db->where("ss_staff_id = sm_staff_id");
        $this->db->where("ss_job_status = sjs_status_code");
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_status = 'APPROVE'");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }
}
