<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Training_application_model extends MY_Model
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
		
        $this->db->select('hp_parm_desc AS PARM_DESC');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->join('ims_hris.staff_main','sm_dept_code = UPPER(TRIM(BOTH HP_PARM_DESC))');
        $this->db->where('hp_parm_code', 'TRAINING_ADM_DEPT_CODE');
        $this->db->where('sm_staff_id', $sID);
        $query = $this->db->get();
		
        if ($query->num_rows() > 0) {
            if ($query->row()->PARM_DESC == '' or $query->row()->PARM_DESC == null or $query->row()->PARM_DESC == 0){
                return '';
            }else{
                return $query->row()->PARM_DESC;
            }
        }
		
        return '';
    }
    
    /*===========================================================
       TRAINING APPLICATION [TRAINING SETUP]
    =============================================================*/

   /*_____________________
        GET BASIC INFO
    _______________________*/

    // TRAINING HEAD -postgres
    public function getTrainingInfo()
    {
        $umg = $this->username;

        $this->db->select('th_ref_id TH_REF_ID,
        th_training_title TH_TRAINING_TITLE,
        th_training_desc TH_TRAINING_DESC,
        th_type TH_TYPE,
        th_status TH_STATUS,
        th_internal_external TH_INTERNAL_EXTERNAL,
        th_level TH_LEVEL,
        th_training_venue TH_TRAINING_VENUE,
        th_training_country TH_TRAINING_COUNTRY,
        th_organizer_name TH_ORGANIZER_NAME,
        th_organizer_level TH_ORGANIZER_LEVEL,
        th_organizer_address TH_ORGANIZER_ADDRESS,
        th_organizer_postcode TH_ORGANIZER_POSTCODE,
        th_organizer_city TH_ORGANIZER_CITY,
        th_organizer_state TH_ORGANIZER_STATE,
        th_organizer_country TH_ORGANIZER_COUNTRY,
        th_sponsor TH_SPONSOR,
        th_date_from TH_DATE_FROM,
        th_date_to TH_DATE_TO,
        th_total_hours TH_TOTAL_HOURS,
        th_training_fee TH_TRAINING_FEE,
        th_apply_closing_date TH_APPLY_CLOSING_DATE,
        th_current_participant TH_CURRENT_PARTICIPANT,
        th_max_participant TH_MAX_PARTICIPANT,
        th_open TH_OPEN,
        th_dept_code TH_DEPT_CODE,
        th_enter_by TH_ENTER_BY,
        th_enter_date TH_ENTER_DATE,
        th_approve_by TH_APPROVE_BY,
        th_approve_date TH_APPROVE_DATE,
        th_training_state TH_TRAINING_STATE,
        th_attendance_type TH_ATTENDANCE_TYPE,
        th_print_certificate TH_PRINT_CERTIFICATE,
        th_evaluation_compulsory TH_EVALUATION_COMPULSORY,
        th_service_group TH_SERVICE_GROUP,
        th_category TH_CATEGORY,
        th_evaluation_date_from TH_EVALUATION_DATE_FROM,
        th_evaluation_date_to TH_EVALUATION_DATE_TO,
        th_training_history TH_TRAINING_HISTORY,
        th_competency_code TH_COMPETENCY_CODE,
        th_training_code TH_TRAINING_CODE,
        th_offer TH_OFFER,
        th_generate_cpd TH_GENERATE_CPD,
        th_time_from TH_TIME_FROM,
        th_time_to TH_TIME_TO,
        th_confirm_date_from TH_CONFIRM_DATE_FROM,
        th_confirm_date_to TH_CONFIRM_DATE_TO,
        th_field TH_FIELD');
        $this->db->from('ims_hris.training_head');
        
        $this->db->where("th_status = 'ENTRY'");
        $this->db->where("th_dept_code = (SELECT sm_dept_code FROM ims_hris.staff_main where UPPER(sm_apps_username) = UPPER('$umg'))");
        $this->db->where("th_internal_external NOT IN ('EXTERNAL_AGENCY')");
        $this->db->order_by("th_date_from, th_date_to, th_training_title, th_ref_id");
        $q = $this->db->get();
        
        return $q->result();
    }

    // TRAINING HEAD 2 -postgres
    public function getTrainingInfo2($deptCode)
    {
        $this->db->select('th_ref_id TH_REF_ID,
        th_training_title TH_TRAINING_TITLE,
        th_training_desc TH_TRAINING_DESC,
        th_type TH_TYPE,
        th_status TH_STATUS,
        th_internal_external TH_INTERNAL_EXTERNAL,
        th_level TH_LEVEL,
        th_training_venue TH_TRAINING_VENUE,
        th_training_country TH_TRAINING_COUNTRY,
        th_organizer_name TH_ORGANIZER_NAME,
        th_organizer_level TH_ORGANIZER_LEVEL,
        th_organizer_address TH_ORGANIZER_ADDRESS,
        th_organizer_postcode TH_ORGANIZER_POSTCODE,
        th_organizer_city TH_ORGANIZER_CITY,
        th_organizer_state TH_ORGANIZER_STATE,
        th_organizer_country TH_ORGANIZER_COUNTRY,
        th_sponsor TH_SPONSOR,
        th_date_from TH_DATE_FROM,
        th_date_to TH_DATE_TO,
        th_total_hours TH_TOTAL_HOURS,
        th_training_fee TH_TRAINING_FEE,
        th_apply_closing_date TH_APPLY_CLOSING_DATE,
        th_current_participant TH_CURRENT_PARTICIPANT,
        th_max_participant TH_MAX_PARTICIPANT,
        th_open TH_OPEN,
        th_dept_code TH_DEPT_CODE,
        th_enter_by TH_ENTER_BY,
        th_enter_date TH_ENTER_DATE,
        th_approve_by TH_APPROVE_BY,
        th_approve_date TH_APPROVE_DATE,
        th_training_state TH_TRAINING_STATE,
        th_attendance_type TH_ATTENDANCE_TYPE,
        th_print_certificate TH_PRINT_CERTIFICATE,
        th_evaluation_compulsory TH_EVALUATION_COMPULSORY,
        th_service_group TH_SERVICE_GROUP,
        th_category TH_CATEGORY,
        th_evaluation_date_from TH_EVALUATION_DATE_FROM,
        th_evaluation_date_to TH_EVALUATION_DATE_TO,
        th_training_history TH_TRAINING_HISTORY,
        th_competency_code TH_COMPETENCY_CODE,
        th_training_code TH_TRAINING_CODE,
        th_offer TH_OFFER,
        th_generate_cpd TH_GENERATE_CPD,
        th_time_from TH_TIME_FROM,
        th_time_to TH_TIME_TO,
        th_confirm_date_from TH_CONFIRM_DATE_FROM,
        th_confirm_date_to TH_CONFIRM_DATE_TO,
        th_field TH_FIELD');
        $this->db->from('ims_hris.training_head');
        
        $this->db->where("th_status = 'ENTRY'");
        if(!empty($deptCode)) {
            $this->db->where("th_dept_code", $deptCode);
        }
        // $this->db->where("TH_DEPT_CODE = (SELECT SM_DEPT_CODE FROM STAFF_MAIN WHERE UPPER(SM_APPS_USERNAME) = UPPER('$umg'))");
        $this->db->where("th_internal_external NOT IN ('EXTERNAL_AGENCY')");
        $this->db->order_by("th_date_from, th_date_to, th_training_title, th_ref_id");
        $q = $this->db->get();
        
        return $q->result();
    }

    // TRAINING HEAD BASED ON REFID -postgres
    public function getTrainingInfoDetail($refID)
    {
        $umg = $this->username;

        $this->db->select('th_ref_id TH_REF_ID,
        th_training_title TH_TRAINING_TITLE,
        th_training_desc TH_TRAINING_DESC,
        th_type TH_TYPE,
        th_status TH_STATUS,
        th_internal_external TH_INTERNAL_EXTERNAL,
        th_level TH_LEVEL,
        th_training_venue TH_TRAINING_VENUE,
        th_training_country TH_TRAINING_COUNTRY,
        th_organizer_name TH_ORGANIZER_NAME,
        th_organizer_level TH_ORGANIZER_LEVEL,
        th_organizer_address TH_ORGANIZER_ADDRESS,
        th_organizer_postcode TH_ORGANIZER_POSTCODE,
        th_organizer_city TH_ORGANIZER_CITY,
        th_organizer_state TH_ORGANIZER_STATE,
        th_organizer_country TH_ORGANIZER_COUNTRY,
        th_sponsor TH_SPONSOR,
        to_char(th_date_from, \'DD-MM-YYYY\') AS "TH_DATEFR",
        to_char(th_date_to, \'DD-MM-YYYY\') AS "TH_DATETO", 
        th_total_hours TH_TOTAL_HOURS,
        th_training_fee TH_TRAINING_FEE,
        to_char(th_apply_closing_date, \'DD-MM-YYYY\') AS "TH_APP_CLOSING_DATE", 
        th_current_participant TH_CURRENT_PARTICIPANT,
        th_max_participant TH_MAX_PARTICIPANT,
        th_open TH_OPEN,
        th_dept_code TH_DEPT_CODE,
        th_enter_by TH_ENTER_BY,
        th_enter_date TH_ENTER_DATE,
        th_approve_by TH_APPROVE_BY,
        th_approve_date TH_APPROVE_DATE,
        th_training_state TH_TRAINING_STATE,
        th_attendance_type TH_ATTENDANCE_TYPE,
        th_print_certificate TH_PRINT_CERTIFICATE,
        th_evaluation_compulsory TH_EVALUATION_COMPULSORY,
        th_service_group TH_SERVICE_GROUP,
        th_category TH_CATEGORY,
        to_char(th_evaluation_date_from, \'DD-MM-YYYY\') AS "TH_EVA_DATE_FROM",
        to_char(th_evaluation_date_to, \'DD-MM-YYYY\') AS "TH_EVA_DATE_TO", 
        th_training_history TH_TRAINING_HISTORY,
        th_competency_code TH_COMPETENCY_CODE,
        th_training_code TH_TRAINING_CODE,
        th_offer TH_OFFER,
        th_generate_cpd TH_GENERATE_CPD,
        to_char(th_time_from, \'HH:MI AM\') AS "TIME_FR", 
        to_char(th_time_to, \'HH:MI AM\') AS "TIME_T", 
        to_char(th_confirm_date_from, \'DD-MM-YYYY\') AS "TH_CON_DATE_FROM",
        to_char(th_confirm_date_to, \'DD-MM-YYYY\') AS "TH_CON_DATE_TO", 
        th_field TH_FIELD,
        th_approve_by TH_APPROVE_BY'); //start @update 06032020
        $this->db->from('ims_hris.training_head');
        
        // if(empty($scCode)) {
        //     $this->db->where("TH_STATUS = 'ENTRY'");
        //     $this->db->where("TH_DEPT_CODE = (SELECT SM_DEPT_CODE FROM STAFF_MAIN WHERE UPPER(SM_APPS_USERNAME) = UPPER('$umg'))");
        //     $this->db->where("TH_INTERNAL_EXTERNAL NOT IN ('EXTERNAL_AGENCY')");
        // }
    
        $this->db->where("th_ref_id", $refID);
        $q = $this->db->get();
        
        return $q->row();
    }

    // SPEAKER INFO EXTERNAL -postgres
    public function getSpeakerInfoExternal($tsrefID, $spID = null)
    {
        // $this->db->select("TRAINING_SPEAKER.ROWID AS SPRD, ts_training_refid TS_TRAINING_REFID, ts_speaker_id TS_SPEAKER_ID, ts_type TS_TYPE, ts_contact TS_CONTACT, es_speaker_name ES_SPEAKER_NAME, es_dept ES_DEPT");
        $this->db->select("ts_training_refid TS_TRAINING_REFID, ts_speaker_id TS_SPEAKER_ID, ts_type TS_TYPE, ts_contact TS_CONTACT, es_speaker_name ES_SPEAKER_NAME, es_dept ES_DEPT");
        $this->db->from("ims_hris.external_speaker, ims_hris.training_speaker");
        $this->db->where("es_speaker_id = ts_speaker_id");
        $this->db->where("ts_training_refid", $tsrefID);
        
        if(!empty($spID)) {
            $this->db->where("ts_speaker_id", $spID);

            $q = $this->db->get();
            
            return $q->row();
        } else {
            $q = $this->db->get();
        
            return $q->result();
        }
    }

    // SPEAKER INFO STAFF -postgres
    public function getSpeakerInfoStaff($tsrefID, $spID = null)
    {
        $this->db->select("ts_type TS_TYPE, ts_speaker_id TS_SPEAKER_ID, sm_staff_name SM_STAFF_NAME, sm_dept_code SM_DEPT_CODE, ts_contact TS_CONTACT");
        $this->db->from("ims_hris.staff_main, ims_hris.training_speaker");
        $this->db->where("sm_staff_id = ts_speaker_id");
        $this->db->where("ts_training_refid", $tsrefID);

        if(!empty($spID)) {
            $this->db->where("ts_speaker_id", $spID);

            $q = $this->db->get();
            
            return $q->row();
        } else {
            $q = $this->db->get();
        
            return $q->result();
        }
    }

    // FACILITATOR INFO EXTERNAL -postgres
    public function getFacilitatorInfoExternal($tsrefID, $fiID = null)
    {
        $this->db->select("tf_type TF_TYPE, ef_facilitator_name EF_FACILITATOR_NAME, tf_facilitator_id TF_FACILITATOR_ID, tf_facilitator_label TF_FACILITATOR_LABEL");
        $this->db->from("ims_hris.training_facilitator, ims_hris.external_facilitator");
        $this->db->where("ef_facilitator_id = tf_facilitator_id");
        $this->db->where("tf_training_refid", $tsrefID);
        
        if(!empty($fiID)) {
            $this->db->where("tf_facilitator_id", $fiID);

            $q = $this->db->get();
            
            return $q->row();
        } else {
			$this->db->order_by("tf_facilitator_label");
            $q = $this->db->get();
        
            return $q->result();
        }
    }

    // FACILITATOR INFO STAFF -postgres
    public function getFacilitatorInfoStaff($tsrefID, $fiID = null)
    {
        $this->db->select("tf_type TF_TYPE, sm_staff_name SM_STAFF_NAME, tf_facilitator_id TF_FACILITATOR_ID, tf_facilitator_label TF_FACILITATOR_LABEL");
        $this->db->from("ims_hris.training_facilitator, ims_hris.staff_main");
        $this->db->where("sm_staff_id = tf_facilitator_id");
        $this->db->where("tf_training_refid", $tsrefID);
        
        if(!empty($fiID)) {
            $this->db->where("tf_facilitator_id", $fiID);

            $q = $this->db->get();
            
            return $q->row();
        } else {
			$this->db->order_by("tf_facilitator_label");
            $q = $this->db->get();
        
            return $q->result();
        }
    }

    // DROPDOWN TYPE LIST -postgres
    public function getTypeList()
    {
        $this->db->select('tt_code as TT_CODE, tt_code ||\' - \'|| tt_desc as "TT_CODE_DESC"');
        $this->db->from('ims_hris.training_type');
        $q = $this->db->get();
        return $q->result();
    }

    // SELECT STRUCTURED TRAINING -postgres
    public function getStructuredTraining($strTrCode = null)
    {
        $this->db->select('tth_ref_id TTH_REF_ID, tth_ref_id ||\' - \'|| tth_training_title "TTH_REF_TITLE", 
        tth_training_title TTH_TRAINING_TITLE, tth_category TTH_CATEGORY, 
        tth_field ||\' - \'|| tf_field_desc "TTH_TF_FIELD_DESC", tth_type ||\' - \'|| tt_desc "TTH_TT_TYPE_DESC", tth_competency TTH_COMPETENCY');
        $this->db->from("ims_hris.tna_training_head, ims_hris.training_type, ims_hris.training_field");
        $this->db->where("tth_type = tt_code");
        $this->db->where("coalesce(tth_status,'INACTIVE') = 'ACTIVE'");
        $this->db->where("tth_field = tf_code");

        if(!empty($strTrCode)){
            $this->db->where("tth_ref_id", $strTrCode);
            $q = $this->db->get();
        
            return $q->row();
        } else {
            $this->db->order_by("tth_ref_id");
            $q = $this->db->get();
            
            return $q->result();
        }
    }

    // DROPDOWN CATEGORY LIST -postgres
    public function getCategoryList()
    {
        $this->db->select("tc_category TC_CATEGORY");
        $this->db->from("ims_hris.training_category");
        $this->db->where("coalesce(tc_status,'N') = 'Y'");
        $this->db->order_by("1");
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN LEVEL LIST -postgres
    public function getLevelList()
    {
        $this->db->select('tl_code TL_CODE, tl_code ||\' - \'|| tl_desc "TL_CODE_DESC"');
        $this->db->from('ims_hris.training_level');
        $this->db->order_by("TL_CODE");
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN AREA LIST -postgres
    public function getAreaList()
    {
        $this->db->select('tf_code TF_CODE, tf_code ||\' - \'|| tf_field_desc "TF_CODE_DESC"');
        $this->db->from('ims_hris.training_field');
        $this->db->where("coalesce(tf_status,'N') = 'Y'");
        $this->db->order_by('tf_ranking');
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN GROUP LIST -postgres
    public function getSgroupList()
    {
        //select SG_GROUP_CODE,SG_GROUP_DESC from service_group order by 1
        $this->db->select('sg_group_code SG_GROUP_CODE, sg_group_code ||\' - \'|| sg_group_desc "SG_CODE_DESC"');
        $this->db->from('ims_hris.service_group');
        $this->db->order_by("1");
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN COUNTRY LIST -postgres
    public function getCountryList() {
        $this->db->select('cm_country_code CM_COUNTRY_CODE, cm_country_desc CM_COUNTRY_DESC');
        $this->db->from('ims_hris.country_main');
        $this->db->order_by("CM_COUNTRY_DESC");
        $q = $this->db->get();
		        
        return $q->result();
    }

    // DEFAULT COUNTRY -postgres
    public function getCountryDef() {
        $this->db->select('cm_country_code CM_COUNTRY_CODE, cm_country_desc CM_COUNTRY_DESC');
        $this->db->from('ims_hris.country_main');
        $this->db->where("cm_country_code = 'MYS'");
        $q = $this->db->get();
		        
        return $q->row();
    }

    // DROPDOWN STATE LIST -postgres
    public function getCountryStateList($countCode) {
        $this->db->select('sm_state_code SM_STATE_CODE, sm_state_desc SM_STATE_DESC, sm_country_code SM_COUNTRY_CODE');
        $this->db->from('ims_hris.state_main');
		$this->db->where('sm_country_code', $countCode);
        $this->db->order_by('sm_state_code');
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN COMPETENCY LEVEL LIST -postgres
    public function getCompetencyLevel() {
        $this->db->select('tcl_competency_code TCL_COMPETENCY_CODE, tcl_competency_code ||\' - \'|| tcl_competency_desc "TCL_COMPETENCY_CODE_DESC", tcl_service_year_from TCL_SERVICE_YEAR_FROM, tcl_service_year_to TCL_SERVICE_YEAR_TO,tcl_ordering TCL_ORDERING');
        $this->db->from('ims_hris.training_competency_level');
		$this->db->where("tcl_status = 'Y'");
        $this->db->order_by("TCL_ORDERING");
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN STAFF LIST -postgres
    public function getCoordinator() {
        $this->db->select('sm_staff_id SM_STAFF_ID, sm_staff_id ||\' - \'|| sm_staff_name "SM_STAFF_ID_NAME"');
        $this->db->from('ims_hris.staff_main, ims_hris.staff_status');
        $this->db->where("sm_staff_status = ss_status_code");
        $this->db->where("ss_status_sts = 'ACTIVE'");
        $this->db->where("sm_staff_type = 'STAFF'");
        $this->db->order_by('sm_staff_name');
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN SECTOR LEVEL LIST -postgres
    public function getCoordinatorSec() {
        $this->db->select('tsl_code TSL_CODE, tsl_code ||\' - \'|| tsl_desc "TSL_CODE_DESC"');
        $this->db->from('ims_hris.training_sector_level');
		$this->db->where("coalesce(tsl_status,'N') = 'Y'");
        $q = $this->db->get();
        
        return $q->result();
    }

    // DROPDOWN ORGANIZER LEVEL LIST -postgres
    public function getOrganizerLevel() {
        $this->db->select('tol_code TOL_CODE, tol_code ||\' - \'|| tol_desc "TOL_CODE_DESC"');
        $this->db->from('ims_hris.training_organizer_level');
        $this->db->order_by("TOL_CODE");
        $q = $this->db->get();
        
        return $q->result();
    }

    // GET ORGANIZER DETAILS -postgres
    public function getOrganizerName($organizerCode = null) {
        $this->db->select('toh_org_code TOH_ORG_CODE, toh_org_desc TOH_ORG_DESC, toh_org_code ||\' - \'|| toh_org_desc "TOH_ORG_CODE_DESC", toh_address TOH_ADDRESS, toh_postcode TOH_POSTCODE, toh_city TOH_CITY, sm_state_desc SM_STATE_DESC, cm_country_desc CM_COUNTRY_DESC');
        $this->db->from('ims_hris.training_organizer_head, ims_hris.state_main, ims_hris.country_main');
        $this->db->where('toh_state=sm_state_code');
        $this->db->where('toh_country=cm_country_code');
        $this->db->where("coalesce(toh_external_agency,'N') <> 'Y'");

        if(!empty($organizerCode)) {
            $this->db->where("toh_org_code", $organizerCode);
            $q = $this->db->get();
        
            return $q->row();
        } 
        else {
            $this->db->order_by("2");
            $q = $this->db->get();
        
            return $q->result();
        }  
    }

    // GET TARGET GROUP LIST -postgres
    public function getTargetGroup($tsrefID, $gpCode = null) {
        $this->db->select('ttg_training_refid TTG_TRAINING_REFID, ttg_group_code TTG_GROUP_CODE, tg_group_desc TG_GROUP_DESC, tg_scheme TG_SCHEME, tg_grade_from TG_GRADE_FROM, 
        tg_grade_to TG_GRADE_TO, tg_service_year_from TG_SERVICE_YEAR_FROM, tg_service_year_to TG_SERVICE_YEAR_TO, tg_service_group TG_SERVICE_GROUP,
                            CASE tg_academic
                                WHEN \'Y\' THEN \'YES\'
                                WHEN \'N\' THEN \'NO\'
                            END
                            AS "TGACADEMIC",
                            CASE TG_NEW_STAFF
                                WHEN \'Y\' THEN \'YES\'
                                WHEN \'N\' THEN \'NO\'
                            END
                            AS "TGNEWSTAFF",
                            CASE TG_COMPULSORY
                                WHEN \'Y\' THEN \'YES\'
                                WHEN \'N\' THEN \'NO\'
                            END
                            AS "TGCOMPULSORY", 
                            tg_academic TG_ACADEMIC, tg_new_staff TG_NEW_STAFF, tg_compulsory TG_COMPULSORY');
        $this->db->from("ims_hris.training_target_group, ims_hris.tna_group");
        $this->db->where("ttg_training_refid", $tsrefID);
        $this->db->where("ttg_group_code = tg_group_code");

        if(!empty($gpCode)) {
            $this->db->where("ttg_group_code", $gpCode);

            $this->db->order_by('ims_hris.training_gettargetgroupdesc(ttg_group_code::text)');
            $q = $this->db->get();
            
            return $q->row();
        } 
        else 
        {
            $this->db->order_by("ims_hris.training_gettargetgroupdesc(ttg_group_code::text)");
            $q = $this->db->get();
            return $q->result();
        }
    }

    // SELECT TRAINING HEAD DETL -postgres
    public function getmoduleSetup($tsrefID) {
        $this->db->select('thd_training_objective2 THD_TRAINING_OBJECTIVE2, thd_training_content THD_TRAINING_CONTENT, thd_module_category THD_MODULE_CATEGORY, thd_module_category ||\' - \'|| tmc_component_desc AS "TMCDESC",
        thd_evaluation THD_EVALUATION, thd_coordinator THD_COORDINATOR, thd_coordinator_telno THD_COORDINATOR_TELNO, thd_coordinator_sector THD_COORDINATOR_SECTOR');
        $this->db->from("ims_hris.training_head_detl");
        $this->db->join("ims_hris.training_module_component", "tmc_component_code = thd_module_category", "left");
        $this->db->where("thd_ref_id", $tsrefID);
        $q = $this->db->get();
        
        return $q->row();
    }

    // SELECT CPD HEAD -postgres
    public function getCpdSetup($tsrefID) {
        $this->db->select('ch_competency CH_COMPETENCY, ch_category CH_CATEGORY, ch_mark CH_MARK, 
                           CASE WHEN ch_report_submission = \'Y\' THEN \'YES\' ELSE \'NO\' END AS "REP_SUB", 
                           CASE WHEN ch_compulsory = \'Y\' THEN \'YES\' ELSE \'NO\' END AS "CHCOMPULSORY"');

        // $this->db->select("CH_COMPETENCY, CH_CATEGORY, CH_MARK, 
        // CASE WHEN CH_REPORT_SUBMISSION = 'Y' THEN 'YES' ELSE 'NO' END AS REP_SUB, 
        // CASE WHEN CH_COMPULSORY = 'Y' THEN 'YES' ELSE 'NO' END AS CHCOMPULSORY,
        // CH_AUTO");
        $this->db->from("ims_hris.cpd_head");
        $this->db->where("ch_training_refid", $tsrefID);
        //$this->db->where("CC_CATEGORY_CODE = CH_CATEGORY");
        $q = $this->db->get();
        
        return $q->row();
    }

    // SELECT CPD CATEGORY -postgres
    public function getCpdSetupCategory($cCode) {
        $this->db->select('cc_category_code ||\' - \'|| cc_category_desc AS "CH_CC_CATEGORY_DESC"');
        $this->db->from("ims_hris.cpd_category");
        $this->db->where("cc_category_code", $cCode);
        $q = $this->db->get();
        
        return $q->row();
    }

    // COUNT TARGET GROUP -postgres
    public function getCountTargetGroup($tsrefID) {
        $this->db->select('COUNT(1) AS "COUNT_TG"');
        $this->db->from("ims_hris.training_target_group");
        $this->db->where("ttg_training_refid", $tsrefID);
        $q = $this->db->get();
        
        return $q->row();
    }

    // TARGET GROUP STRUCTURED TRAINING -postgres
    public function getValueStrTrTargetGroup($strRefID) {
        $this->db->select("ttg_group_code TTG_GROUP_CODE");
        $this->db->from("ims_hris.tna_target_group, ims_hris.tna_group");
        $this->db->where("ttg_ref_id", $strRefID);
        $this->db->where("tg_group_code = ttg_group_code");
        $q = $this->db->get();
        
        return $q->result();
    }

    // GET REFID -postgres
    public function getRefID() 
    {
        // $this->db->select("to_char(current_date,'yyyy')||'-'||ltrim(to_char(training_head_seq.nextval,'000000')) AS REF_ID");
        $this->db->select('to_char(current_date,\'yyyy\')||\'-\'||ltrim(to_char(nextval(\'ims_hris.training_head_seq\'),\'000000\')) AS "REF_ID"');
        // $this->db->from("DUAL");
        $q = $this->db->get();
        
        return $q->row();
    }

    // GET ALL TNA_TARGET_GROUP BASED ON STRUCTURED TRAINING CODE -postgres
    public function getResultTTG($trCode) {
        $this->db->select("ttg_group_code TTG_GROUP_CODE");
        $this->db->from("ims_hris.tna_target_group, ims_hris.tna_group");
        $this->db->where("ttg_ref_id",$trCode);
        $this->db->where("tg_group_code = ttg_group_code");
        $q = $this->db->get();
        
        return $q->result();
    }

    // GET ALL TRAINING GROUP SERVICE BASED ON STRUCTURED TRAINING CODE -postgres
    public function getResultTGS($trCode) {
        $this->db->select("ttg_group_code TTG_GROUP_CODE, tgs_seq TGS_SEQ, tgs_service_code TGS_SERVICE_CODE");
        $this->db->from("ims_hris.tna_group_service, ims_hris.tna_target_group");
        $this->db->where("tgs_grpserv_code = ttg_group_code");
        $this->db->where("ttg_ref_id", $trCode);
        $q = $this->db->get();
        
        return $q->result();
    }

    // SELECT TRAINING GROUP SERVICE -postgres
    public function checkTGS($gpCode, $tgsSeq) {
        $this->db->select("tgs_grpserv_code TGS_GRPSERV_CODE, tgs_seq TGS_SEQ");
        $this->db->from("ims_hris.training_group_service");
        $this->db->where("tgs_grpserv_code", $gpCode);
        $this->db->where("tgs_seq", $tgsSeq);
        $q = $this->db->get();
        
        return $q->result();
    }

    // check tgs add position -postgres
    public function checkTGS2($gpCode, $svcCode) {
        $this->db->select("tgs_grpserv_code TGS_GRPSERV_CODE,
        tgs_seq TGS_SEQ,
        tgs_service_code TGS_SERVICE_CODE");
        $this->db->from("ims_hris.training_group_service");
        $this->db->where("tgs_grpserv_code", $gpCode);
        $this->db->where("tgs_service_code", $svcCode);
        $q = $this->db->get();
        
        return $q->row();
    }

    // get max seq add position -postgres
    public function getMaxTGSSeq($gpCode) {
        $this->db->select('(MAX(tgs_seq)::numeric+1) AS "MAX_SEQ"');
        $this->db->from("ims_hris.training_group_service");
        $this->db->where("tgs_grpserv_code", $gpCode);
        $q = $this->db->get();
        
        return $q->row();
    }

    // SELECT TRAINING HEAD DETAIL -postgres
    public function getTrHeadDetl($refID) {
        $this->db->select("thd_ref_id THD_REF_ID,
        thd_training_objective1 THD_TRAINING_OBJECTIVE1,
        thd_training_objective2 THD_TRAINING_OBJECTIVE2,
        thd_training_content THD_TRAINING_CONTENT,
        thd_module_category THD_MODULE_CATEGORY,
        thd_evaluation THD_EVALUATION,
        thd_coordinator THD_COORDINATOR,
        thd_coordinator_telno THD_COORDINATOR_TELNO,
        thd_coordinator_sector THD_COORDINATOR_SECTOR,
        thd_for_attention THD_FOR_ATTENTION");
        $this->db->from("ims_hris.training_head_detl");
        $this->db->where("thd_ref_id", $refID);
        
        $q = $this->db->get();
        return $q->row();
    }

    // GET SPEAKER LIST AND SELECT SPEAKER -postgres
    public function getSpeakerList($tpSpeaker, $trSpeakerCode = null) {
        if(empty($trSpeakerCode)) {
            if($tpSpeaker == 'STAFF') {
                $this->db->select('sm_staff_id SM_STAFF_ID, sm_staff_name SM_STAFF_NAME, sm_dept_code SM_DEPT_CODE, sm_staff_id ||\' - \'|| sm_staff_name AS "STAFF_ID_NAME"');
                $this->db->from("ims_hris.staff_main, ims_hris.staff_status, ims_hris.department_main");
                $this->db->where("ss_status_code = sm_staff_status");
                $this->db->where("ss_status_sts = 'ACTIVE'");
                $this->db->where("sm_dept_code = dm_dept_code");
                $this->db->order_by("2,1");
            } 
            elseif($tpSpeaker == 'EXTERNAL') {
                $this->db->select('es_speaker_id ES_SPEAKER_ID, es_speaker_name ES_SPEAKER_NAME, es_dept ES_DEPT, es_telno_work ES_TELNO_WORK, es_speaker_id ||\' - \'|| es_speaker_name AS "ES_SPEAKER_ID_NAME"');
                $this->db->from("ims_hris.external_speaker");
                $this->db->where("es_status = 'ACTIVE'");
                $this->db->order_by("2");
            }
    
            $q = $this->db->get();
            return $q->result();
        } 
        elseif(!empty($trSpeakerCode)) {
            if($tpSpeaker == 'STAFF') {
                $this->db->select('sm_staff_id SM_STAFF_ID, sm_staff_name SM_STAFF_NAME, sm_dept_code SM_DEPT_CODE, sm_telno_work SM_TELNO_WORK, sm_staff_id ||\' - \'|| sm_staff_name AS "STAFF_ID_NAME"');
                $this->db->from("ims_hris.staff_main, ims_hris.staff_status, ims_hris.department_main");
                $this->db->where("ss_status_code = sm_staff_status");
                $this->db->where("ss_status_sts = 'ACTIVE'");
                $this->db->where("sm_dept_code = dm_dept_code");
                $this->db->where("sm_staff_id", $trSpeakerCode);
            } 
            elseif($tpSpeaker == 'EXTERNAL') {
                $this->db->select('es_speaker_id ES_SPEAKER_ID, es_speaker_name ES_SPEAKER_NAME, es_dept ES_DEPT, es_telno_work ES_TELNO_WORK, es_speaker_id ||\' - \'|| es_speaker_name AS ES_SPEAKER_ID_NAME');
                $this->db->from("ims_hris.external_speaker");
                $this->db->where("es_status = 'ACTIVE'");
                $this->db->where("es_speaker_id", $trSpeakerCode);
            }
    
            $q = $this->db->get();
            return $q->row();        
        }
    }

    // GET FACILITATOR LIST AND SELECT FACILITATOR -postgres
    public function getFacilitatorList($tpFacilitator, $trSpeakerCode = null) {

        if(!empty($tpFacilitator)) {
            if($tpFacilitator == 'STAFF') {
                $this->db->select('sm_staff_id SM_STAFF_ID, sm_staff_name SM_STAFF_NAME, sm_staff_id ||\' - \'|| sm_staff_name AS "STAFF_ID_NAME"');
                $this->db->from("ims_hris.staff_main, ims_hris.staff_status");
                $this->db->where("ss_status_code = sm_staff_status");
                $this->db->where("ss_status_sts = 'ACTIVE'");
                $this->db->order_by("2,1");
            } 
            elseif($tpFacilitator == 'EXTERNAL') {
                $this->db->select('ef_facilitator_id EF_FACILITATOR_ID, ef_facilitator_name EF_FACILITATOR_NAME, ef_facilitator_id ||\' - \'|| ef_facilitator_name AS "ES_FACILITATOR_ID_NAME"');
                $this->db->from("ims_hris.external_facilitator");
                $this->db->order_by("2");
            }
    
            $q = $this->db->get();
            return $q->result();
        }
    }

    // SELECT TRAINING SPEAKER -postgres
    public function checkTrainingSpeaker($refID, $spID) {
        $this->db->select("ts_training_refid TS_TRAINING_REFID, ts_speaker_id TS_SPEAKER_ID, ts_type TS_TYPE, ts_contact TS_CONTACT");
        $this->db->from("ims_hris.training_speaker");
        $this->db->where("ts_speaker_id", $spID);
        $this->db->where("ts_training_refid", $refID);
        $q = $this->db->get();
        
        return $q->row();
    }

    // SELECT TRAINING FACILITATOR -postgres
    public function checkTrainingFacilitator($refID, $fiID, $fiLabel) {
        $this->db->select("tf_training_refid TF_TRAINING_REFID,
        tf_facilitator_id TF_FACILITATOR_ID,
        tf_type TF_TYPE,
        tf_facilitator_label TF_FACILITATOR_LABEL");
        $this->db->from("ims_hris.training_facilitator");
        $this->db->where("tf_facilitator_id", $fiID);
        $this->db->where("tf_training_refid", $refID);
        $this->db->where("tf_facilitator_label", $fiLabel);
        $q = $this->db->get();
        
        return $q->row();
    }

    // SELECT TRAINING NEED ANALYSIS GROUP -postgres
    public function getTargetGroupList($groupCode = null) {

        if(!empty($groupCode)) {
            $this->db->select('tg_group_desc TG_GROUP_DESC, tg_scheme TG_SCHEME, tg_grade_from TG_GRADE_FROM, 
            tg_grade_to TG_GRADE_TO, tg_service_year_from TG_SERVICE_YEAR_FROM, tg_service_year_to TG_SERVICE_YEAR_TO, 
            tg_service_group TG_SERVICE_GROUP,
                               CASE tg_academic
                                WHEN \'Y\' THEN \'YES\'
                                WHEN \'N\' THEN \'NO\'
                                END
                               AS "TGACADEMIC",
                               CASE tg_new_staff
                                    WHEN \'Y\' THEN \'YES\'
                                    WHEN \'N\' THEN \'NO\'
                                END
                               AS "TGNEWSTAFF",
                               CASE tg_compulsory
                                    WHEN \'Y\' THEN \'YES\'
                                    WHEN \'N\' THEN \'NO\'
                                END
                               AS "TGCOMPULSORY",
                               tg_academic TG_ACADEMIC, tg_new_staff TG_NEW_STAFF, tg_compulsory TG_COMPULSORY');
            $this->db->from("ims_hris.tna_group");
            $this->db->where("tg_group_code", $groupCode);

            $q = $this->db->get();
            return $q->row();
        } 
        
        if(empty($groupCode)) {
            $this->db->select('tg_group_code TG_GROUP_CODE, tg_group_desc TG_GROUP_DESC, tg_group_code ||\' - \'|| tg_group_desc AS "TG_GROUP_CODE_DESC", 
            tg_scheme TG_SCHEME, tg_service_group TG_SERVICE_GROUP, 
            tg_grade_from TG_GRADE_FROM, tg_grade_to TG_GRADE_TO, tg_academic TG_ACADEMIC, tg_compulsory TG_COMPULSORY,
            tg_new_staff TG_NEW_STAFF, tg_service_year_from TG_SERVICE_YEAR_FROM, tg_service_year_to TG_SERVICE_YEAR_TO,
            tg_option TG_OPTION, tg_status TG_STATUS');
            $this->db->from("ims_hris.tna_group");
            $this->db->where("tg_status = 'ACTIVE'");
            $this->db->order_by("tg_group_desc");

            $q = $this->db->get();
            return $q->result();
        }
    }

    // SELECT TRAINING TARGET GROUP BASED ON REFID & GROUP CODE -postgres
    public function getTargetGroupDetail($refid, $gpCode) {
        $this->db->select("ttg_training_refid TTG_TRAINING_REFID,
        ttg_group_code TTG_GROUP_CODE,
        ttg_enter_by TTG_ENTER_BY,
        ttg_enter_date TTG_ENTER_DATE,
        ttg_update_by TTG_UPDATE_BY,
        ttg_update_date TTG_UPDATE_DATE,
        ttg_structured TTG_STRUCTURED");
        $this->db->from("ims_hris.training_target_group");
        $this->db->where("ttg_training_refid", $refid);
        $this->db->where("ttg_group_code", $gpCode);

        $q = $this->db->get();
        return $q->row();
    }

    // SELECT TRAINING TARGET GROUP BASED ON REFID -postgres
    public function delTargetGroupVerify($gpCode) {
        $this->db->select("tgs_grpserv_code TGS_GRPSERV_CODE");
        $this->db->from("ims_hris.training_group_service");
        $this->db->where("tgs_grpserv_code", $gpCode);

        $q = $this->db->get();
        return $q->result();
    }

    // GET SERVICE SCHEME BASED ON GROUP CODE -postgres
    public function getListEgPosition($groupCode, $svcCode = null) {
        $this->db->select("tgs_grpserv_code TGS_GRPSERV_CODE, tgs_seq TGS_SEQ, tgs_service_code TGS_SERVICE_CODE, ss_service_desc SS_SERVICE_DESC");
        $this->db->from("ims_hris.training_group_service");
        $this->db->join('ims_hris.service_scheme', 'ims_hris.training_group_service.tgs_service_code = ims_hris.service_scheme.ss_service_code', 'LEFT');
        $this->db->where("tgs_grpserv_code", $groupCode);
        if(!empty($svcCode)) {
            $this->db->where("tgs_service_code", $svcCode);

            $q = $this->db->get();
            return $q->row();
        } else {
            $q = $this->db->get();
            return $q->result();
        }
    }

    // save insert eg position -postgres
    public function saveInsertEgPos($form, $gpCode) {
        $tgs_seq = "(SELECT CASE 
        WHEN max_seq IS NULL THEN 1
        WHEN max_seq IS NOT NULL THEN max_seq
        END AS MAX_SEQ
        FROM(SELECT (MAX(tgs_seq)::NUMERIC+1) AS max_seq
        FROM ims_hris.training_group_service
        WHERE tgs_grpserv_code = '$gpCode') AS A)";

        $data = array(
            "tgs_grpserv_code" => $gpCode,
            "tgs_service_code" => $form['service']
        );

        $this->db->set("tgs_seq", $tgs_seq, false);

        return $this->db->insert("ims_hris.training_group_service", $data);
    }

    // -postgres
    public function getServiceList($schemeCode, $gradeTo, $svcGrp, $aca) {

        $this->db->select('ss_service_code SS_SERVICE_CODE, ss_service_desc SS_SERVICE_DESC, ss_service_code||\' - \'||ss_service_desc AS "SS_SERVICE_DESC"');
        $this->db->from("ims_hris.service_scheme");
        if(!empty($aca)){
            $this->db->where("ss_academic", $aca);
        }

        if(!empty($schemeCode)){
            $this->db->where("ss_class_code", $schemeCode);
        }

        if(!empty($svcGrp)){
            $this->db->where("ss_service_group", $svcGrp);
        }
        $this->db->where("ltrim(ss_salary_grade, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ') <= '$gradeTo'");

        $q = $this->db->get();
        return $q->result();
    }

    // GET TRAINING MODULE COMPONENT -postgres
    public function getCompList() {
        $this->db->select('tmc_component_code TMC_COMPONENT_CODE, tmc_component_code ||\' - \'|| tmc_component_desc "TMC_CODE_DESC"');
        $this->db->from("ims_hris.training_module_component");
        $this->db->order_by("tmc_component_code");

        $q = $this->db->get();
        return $q->result();
    }

    // GET CPD CATEGORY LIST -postgres
    public function getCpdCategoryList() {
        $this->db->select('"cc_category_code CC_CATEGORY_CODE, cc_category_code ||\' - \'|| cc_category_desc AS "CC_CODE_DESC"');
        $this->db->from("ims_hris.cpd_category");

        $q = $this->db->get();
        return $q->result();
    }

    // VERIFY DELETE TRAINING SPEAKER -postgres
    public function delVerifyTrSP($refid) 
    {
        $this->db->select("ts_training_refid TS_TRAINING_REFID");
        $this->db->from("ims_hris.training_speaker");
        $this->db->where("ts_training_refid", $refid);

        $q = $this->db->get();
        return $q->result();
    }

    // VERIFY DELETE TRAINING FACILITATOR -postgres
    public function delVerifyTrFi($refid) {
        $this->db->select("tf_training_refid TF_TRAINING_REFID");
        $this->db->from("ims_hris.training_facilitator");
        $this->db->where("tf_training_refid", $refid);

        $q = $this->db->get();
        return $q->result();
    }

    // VERIFY DELETE TRAINING TARGET GROUP -postgres
    public function delVerifyTrGrp($refid) {
        $this->db->select("ttg_training_refid TTG_TRAINING_REFID");
        $this->db->from("ims_hris.training_target_group");
        $this->db->where("ttg_training_refid", $refid);

        $q = $this->db->get();
        return $q->result();
    }

    // VERIFY DELETE TRAINING MODULE SETUP -postgres
    public function delVerifyModSet($refid) {
        $this->db->select("thd_ref_id THD_REF_ID");
        $this->db->from("ims_hris.training_head_detl");
        $this->db->where("thd_ref_id", $refid);

        $q = $this->db->get();
        return $q->result();
    }

    // VERIFY DELETE TRAINING CPD SETUP -postgres
    public function delVerifyCpdSet($refid) {
        $this->db->select("ch_training_refid CH_TRAINING_REFID");
        $this->db->from("ims_hris.cpd_head");
        $this->db->where("ch_training_refid", $refid);

        $q = $this->db->get();
        return $q->result();
    }

    /*_____________________
        ADD PROCESS
    _______________________*/

    // INSERT TRAINING HEAD -postgres
    public function insertTrainingHead($form, $refid)
    {
        $umg = $this->staff_id;
        $staff_dept_code = "(select sm_dept_code from ims_hris.staff_main where sm_staff_id = '$umg')";
        $enter_date = "date_trunc('second', NOW()::timestamp)";

        $refID = $refid;

        $data = array(
            "th_ref_id" => $refID,
            "th_type" => $form['type'],
            "th_category" => $form['category'],
            "th_training_code" => $form['structured_training'],
            "th_level" => $form['level'],
            "th_field" => $form['area'],
            "th_service_group" => $form['service_group'],
            "th_training_title" => $form['training_title'],
            "th_training_desc" => $form['training_description'],
            "th_training_venue" => $form['venue'],
            "th_training_country" => $form['country'],
            // "th_training_state" => $form['state'],
            "th_total_hours" => $form['total_hours'],
            "th_internal_external" => $form['internal_external'],
            "th_sponsor" => $form['sponsor'],
            "th_offer" => $form['offer'],
            // "th_max_participant" => $form['participants'],
            "th_open" => $form['online_application'],
            "th_competency_code" => $form['competency_code'],

            // organizer info
            // "th_organizer_level" => $form['organizer_level'],
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

        if(!empty($form['organizer_level'])){
            $this->db->set("th_organizer_level", $form['organizer_level'], false);
        } else {
            $this->db->set("th_organizer_level", NULL, true);
        }

        if(!empty($form['th_training_state'])){
            $this->db->set("th_training_state", $form['state'], false);
        } else {
            $this->db->set("th_training_state", NULL, true);
        }

        if(!empty($form['th_max_participant'])){
            $this->db->set("th_max_participant", $form['th_max_participant'], false);
        } else {
            $this->db->set("th_max_participant", NULL, true);
        }

        if(!empty($form['date_from'])){
            $date_from = "to_date('".$form['date_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_from", $date_from, false);

            if(!empty($form['time_from'])){
                $time_from = "to_date('".$form['date_from']." ".$form['time_from']."', 'DD/MM/YYYY HH12:MI PM')";
                $this->db->set("th_time_from", $time_from, false);
            }

            if(!empty($form['time_to'])){
                $time_to = "to_date('".$form['date_from']." ".$form['time_to']."', 'DD/MM/YYYY HH12:MI PM')";
                $this->db->set("th_time_to", $time_to, false);
            }
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
        // else {
            //$this->db->set("th_evaluation_date_from", '', true);
        // }

        if(!empty($form['evaluation_period_to'])){
            $evaluation_period_to = "to_date('".$form['evaluation_period_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_to", $evaluation_period_to, false);
        } 
        // else {
            //$this->db->set("th_evaluation_date_to", '', true);
        // }

        if(!empty($form['confirmation_due_date_from'])){
            $confirmation_due_date_from = "to_date('".$form['confirmation_due_date_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_confirm_date_from", $confirmation_due_date_from, false);
        }

        if(!empty($form['confirmation_due_date_to'])){
            $confirmation_due_date_to = "to_date('".$form['confirmation_due_date_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_confirm_date_to", $confirmation_due_date_to, false);
        }

        return $this->db->insert("ims_hris.training_head", $data);
    }

    // INSERT TRAINING TARGET GROUP -postgres
    public function insertTrainingTargetGroup($refid, $gpCode)
    {
        $insertDate = "date_trunc('second',NOW()::timestamp)";
        $enterBy = $this->staff_id;

        $data = array(
            "ttg_training_refid" => $refid,
            "ttg_group_code" => $gpCode,
            "ttg_structured" => 'Y',
            "ttg_enter_by" => $enterBy,
        );

        $this->db->set("ttg_enter_date", $insertDate, false);

        return $this->db->insert("ims_hris.training_target_group", $data);
    }

    // INSERT TRAINING GROUP SERVICE -postgres
    public function insertTrainingGroupService($gpCode, $tgsSeq, $tgsSvcCode)
    {
        $data = array(
            "tgs_grpserv_code" => $gpCode,
            "tgs_seq" => $tgsSeq,
            "tgs_service_code" => $tgsSvcCode
        );

        return $this->db->insert("ims_hris.training_group_service", $data);
    }

    // INSERT CPD HEAD FROM INSERT TRAINING INFO -postgres
    public function insertCPDHead($refid, $competency)
    {
        $data = array(
            "ch_training_refid" => $refid,
            "ch_competency" => $competency,
            "ch_report_submission" => 'N'
        );

        return $this->db->insert("ims_hris.cpd_head", $data);
    }

    // INSERT TRAINING HEAD DETAIL FROM INSERT TRAINING INFO -postgres
    public function insertTrainingHeadDetl($refid, $coor, $coorSeq, $coorContact, $evaluationTHD)
    {
        $data = array(
            "thd_ref_id" => $refid,
            "thd_coordinator" => $coor,
            "thd_coordinator_sector" => $coorSeq,
            "thd_coordinator_telno" => $coorContact,
            "thd_evaluation" => $evaluationTHD,
        );

        return $this->db->insert("ims_hris.training_head_detl", $data);
    }
    
    // INSERT TRAINING SPEAKER -postgres
    public function insertTrainingSpeaker($form, $refid)
    {
        $data = array(
            "ts_training_refid" => $refid,
            "ts_speaker_id" => $form['speaker'],
            "ts_type" => $form['type'],
            "ts_contact" => $form['contact_phone_no'],
        );

        return $this->db->insert("ims_hris.training_speaker", $data);
    }

    // INSERT TRAINING FACILITATOR -postgres
    public function insertTrainingFacilitator($form, $refid)
    {
        $data = array(
            "tf_training_refid" => $refid,
            "tf_facilitator_id" => $form['facilitator'],
            "tf_type" => $form['type'],
            "tf_facilitator_label" => strtoupper($form['label'])
        );

        return $this->db->insert("ims_hris.training_facilitator", $data);
    }

    // INSERT TRAINING TARGET GROUP -postgres
    public function insertTrainingTG($form, $refid)
    {
        $umg = $this->staff_id;
        $eDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "ttg_training_refid" => $refid,
            "ttg_group_code" => $form['group_code'],
            "ttg_enter_by" => $umg,
        );
        
        //$this->db->set("TTG_ENTER_BY", $umg, false);
        $this->db->set("ttg_enter_date", $eDate, false);

        return $this->db->insert("ims_hris.training_target_group", $data);
    }

    // INSERT TRAINING HEAD DETAIL -postgres
    public function insertModuleSetup($form, $refid)
    {
        $data = array(
            "thd_ref_id" => $refid,
            "thd_training_objective2" => $form['specific_objectives'],
            "thd_training_content" => $form['contents'],
            "thd_module_category" => $form['component_category'],
        );

        return $this->db->insert("ims_hris.training_head_detl", $data);
    }

    // INSERT TRAINING HEAD -postgres
    public function insertCpdSetup($form, $refid)
    {
        $data = array(
            "ch_training_refid" => $refid,
            "ch_competency" => $form['competency'],
            "ch_category" => $form['category'],
            // "ch_mark" => $form['mark'],
            "ch_report_submission" => $form['report_submission'],
            "ch_compulsory" => $form['compulsory']
        );

        if(!empty($form['ch_mark'])){
            $this->db->set("ch_mark", $form['ch_mark'], false);
        } else {
            $this->db->set("ch_mark", NULL, true);
        }

        return $this->db->insert("ims_hris.cpd_head", $data);
    }

    /*_____________________
        UPDATE PROCESS
    _______________________*/
    
    // UPDATE TRAINING HEAD -postgres
    public function updateTrainingHead($form, $refid)
    {
        $umg = $this->staff_id;
        $staff_dept_code = "(SELECT sm_dept_code FROM ims_hris.staff_main WHERE sm_staff_id = '$umg')";
        $enter_date = "date_trunc('second',NOW()::timestamp)";

        $defScCode = $form['sc_code'];
        $thHistory = $form['training_setup_history']; 
        $sts = '';
        $apprBy = '';

        if($defScCode == 'ATF044') {
            $sts = 'APPROVE';
        } else {
            $sts = 'ENTRY';
        }
        
        if($thHistory == 'Y'){
            $apprBy = 'HRA_ADMIN';
        }else {
            $apprBy = $this->staff_id;
        } 
        
        //$refID = $refid;

        $data = array(
            "th_type" => $form['type'],
            "th_category" => $form['category'],
            "th_training_code" => $form['structured_training'],
            "th_level" => $form['level'],
            "th_field" => $form['area'],
            "th_service_group" => $form['service_group'],
            "th_training_title" => $form['training_title'],
            "th_training_desc" => $form['training_description'],
            "th_training_venue" => $form['venue'],
            "th_training_country" => $form['country'],
            //"th_training_state" => $form['state'],
            "th_total_hours" => $form['total_hours'],
            "th_internal_external" => $form['internal_external'],
            "th_sponsor" => $form['sponsor'],
            "th_offer" => $form['offer'],
            // "th_max_participant" => $form['participants'],
            "th_open" => $form['online_application'],
            "th_competency_code" => $form['competency_code'],

            // organizer info
            // "th_organizer_level" => $form['organizer_level'],
            "th_organizer_name" => $form['organizer_name'],

            // completion info
            "th_evaluation_compulsory" => $form['evaluation_compulsary'],
            "th_attendance_type" => $form['attendance_type'],
            "th_print_certificate" => $form['print_certificate'],

            "th_enter_by" => $umg,
            "th_status" => $sts,
                
            "th_approve_by" => $apprBy
        );

        //$this->db->set("TH_REF_ID", $refID, false);
        $this->db->set("th_dept_code", $staff_dept_code, false);
        $this->db->set("th_enter_date", $enter_date, false);

        if(!empty($form['organizer_level'])){
            $this->db->set("th_organizer_level", $form['organizer_level'], false);
        } else {
            $this->db->set("th_organizer_level", NULL, true);
        }

        if(!empty($form['th_training_state'])){
            $this->db->set("th_training_state", $form['state'], false);
        } else {
            $this->db->set("th_training_state", NULL, true);
        }

        if(!empty($form['th_max_participant'])){
            $this->db->set("th_max_participant", $form['th_max_participant'], false);
        } else {
            $this->db->set("th_max_participant", NULL, true);
        }

        if(!empty($form['date_from'])){
            $date_from = "to_date('".$form['date_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_date_from", $date_from, false);

            if(!empty($form['time_from'])){
                $time_from = "to_date('".$form['date_from']." ".$form['time_from']."', 'DD/MM/YYYY HH12:MI PM')";
                $this->db->set("th_time_from", $time_from, false);
            }

            if(!empty($form['time_to'])){
                $time_to = "to_date('".$form['date_from']." ".$form['time_to']."', 'DD/MM/YYYY HH12:MI PM')";
                $this->db->set("th_time_to", $time_to, false);
            }
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

        if(!empty($form['confirmation_due_date_from'])){
            $confirmation_due_date_from = "to_date('".$form['confirmation_due_date_from']."', 'DD/MM/YYYY')";
            $this->db->set("th_confirm_date_from", $confirmation_due_date_from, false);
        } else {
            $this->db->set("th_confirm_date_from", '', true);
        }

        if(!empty($form['confirmation_due_date_to'])){
            $confirmation_due_date_to = "to_date('".$form['confirmation_due_date_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_confirm_date_to", $confirmation_due_date_to, false);
        } else {
            $this->db->set("th_confirm_date_to", '', true);
        }

        $this->db->where('th_ref_id',$refid);

        return $this->db->update('ims_hris.training_head', $data);
    }

    // UPDATE CPD HEAD FROM TRAINING INFO FORM -postgres
    public function updateCPDHead($refid, $competency)
    {
        $data = array(
            //"CH_TRAINING_REFID" => $refid,
            "ch_competency" => $competency,
            "ch_report_submission" => 'N'
        );

        $this->db->where("ch_training_refid", $refid);

        return $this->db->update("ims_hris.cpd_head", $data);
    }

    // UPDATE TRAINING HEAD DETAIL -postgres
    public function updateTrainingHeadDetl($refid, $coor, $coorSeq, $coorContact, $evaluationTHD)
    {
        $data = array(
            //"THD_REF_ID" => $refid,
            "thd_coordinator" => $coor,
            "thd_coordinator_sector" => $coorSeq,
            "thd_coordinator_telno" => $coorContact,
            "thd_evaluation" => $evaluationTHD,
        );

        $this->db->where("thd_ref_id", $refid);

        return $this->db->update("ims_hris.training_head_detl", $data);
    }

    // UPDATE TRAINING SPEAKER -postgres
    public function updateTrainingSpeaker($form, $refid, $spID)
    {
        $data = array(
            "ts_contact" => $form['contact_phone_no']
        );

        $this->db->where("ts_training_refid", $refid);
        $this->db->where("ts_speaker_id", $spID);

        return $this->db->update("ims_hris.training_speaker", $data);
    }

    // UPDATE TRAINING HEAD DETAIL 1 -postgres
    public function updateMs1($form, $refid)
    {
        $data = array(
            "thd_training_objective2" => $form['specific_objectives']
        );

        $this->db->where("thd_ref_id", $refid);

        return $this->db->update("ims_hris.training_head_detl", $data);
    }

    // UPDATE TRAINING HEAD DETAIL 2 -postgres
    public function updateMs2($form, $refid)
    {
        $data = array(
            "thd_training_content" => $form['contents']
        );

        $this->db->where("thd_ref_id", $refid);

        return $this->db->update("ims_hris.training_head_detl", $data);
    }

    // UPDATE TRAINING HEAD DETAIL 3 -postgres
    public function updateMs3($form, $refid)
    {
        $data = array(
            "thd_module_category" => $form['component_category']
        );

        $this->db->where("thd_ref_id", $refid);

        return $this->db->update("ims_hris.training_head_detl", $data);
    }

    // UPDATE CPD HEAD 1 -postgres
    public function updateCpd1($form, $refid)
    {
        $data = array(
            "ch_competency" => $form['competency']
        );

        $this->db->where("ch_training_refid", $refid);

        return $this->db->update("ims_hris.cpd_head", $data);
    }

    // UPDATE CPD HEAD 2 -postgres
    public function updateCpd2($form, $refid)
    {
        $data = array(
            "ch_category" => $form['category']
        );

        $this->db->where("ch_training_refid", $refid);

        return $this->db->update("ims_hris.cpd_head", $data);
    }

    // UPDATE CPD HEAD 3 -postgres
    public function updateCpd3($form, $refid)
    {
        $data = array(
            "ch_mark" => $form['mark']
        );

        $this->db->where("ch_training_refid", $refid);

        return $this->db->update("ims_hris.cpd_head", $data);
    }

    // UPDATE CPD HEAD 4 -postgres
    public function updateCpd4($form, $refid)
    {
        $data = array(
            "ch_report_submission" => $form['report_submission']
        );

        $this->db->where("ch_training_refid", $refid);

        return $this->db->update("ims_hris.cpd_head", $data);
    }

    // UPDATE CPD HEAD 5 -postgres
    public function updateCpd5($form, $refid)
    {
        $data = array(
            "ch_compulsory" => $form['compulsory']
        );

        $this->db->where("ch_training_refid", $refid);

        return $this->db->update("ims_hris.cpd_head", $data);
    }

    /*_____________________
        DELETE PROCESS
    _______________________*/

    // DELETE TRAINING HEAD -postgres
    public function delTrainingInfo($refid) {
        $this->db->where('th_ref_id', $refid);
        return $this->db->delete('ims_hris.training_head');
    }

    // DELETE TRAINING SPEAKER -postgres
    public function delTrainingSpeaker($refid, $spID) {
        $this->db->where('ts_training_refid', $refid);
        $this->db->where('ts_speaker_id', $spID);
        return $this->db->delete('ims_hris.training_speaker');
    }

    // DELETE TRAINING FACILITATOR -postgres
    public function delTrainingFacilitator($refid, $fiID) {
        $this->db->where('tf_training_refid', $refid);
        $this->db->where('tf_facilitator_id', $fiID);
        return $this->db->delete('ims_hris.training_facilitator');
    }

    // DELETE TRAINING TARGET GROUP -postgres
    public function delTargetGroup($refid, $gpCode) {
        $this->db->where('ttg_training_refid', $refid);
        $this->db->where('ttg_group_code', $gpCode);
        return $this->db->delete('ims_hris.training_target_group');
    }

    // DELETE TRAINING HEAD DETAIL -postgres
    public function delModuleSetup($refid) {
        $this->db->where('thd_ref_id', $refid);
        return $this->db->delete('ims_hris.training_head_detl');
    }

    // DELETE CPD HEAD -postgres
    public function delCpdSetup($refid) {
        $this->db->where('ch_training_refid', $refid);
        return $this->db->delete('ims_hris.cpd_head');
    }

    // DELETE TRAINING GROUP SERVICE -postgres
    public function delTrainingGpService($gpCode, $tgsSeq) {
        $this->db->where('tgs_grpserv_code', $gpCode);
        $this->db->where('tgs_seq', $tgsSeq);
        return $this->db->delete('ims_hris.training_group_service');
    }



    /*===========================================================
       TRAINING APPLICATION [APPROVE TRAINING APPLICATIONS]
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // GET CURRENT DEFAULT USER DEPARTMENT - STAFF MAIN -postgres
    public function getCurUserDept($staffID = null) {
        
        $curUsername = $this->username;

        $this->db->select("sm_staff_id AS SM_STAFF_ID, sm_staff_name AS SM_STAFF_NAME, sm_dept_code AS SM_DEPT_CODE, sm_email_addr AS SM_EMAIL_ADDR");
        $this->db->from("ims_hris.staff_main");

        if(empty($staffID)) {
            $this->db->where("sm_apps_username", $curUsername);
        } else {
            $this->db->where("sm_staff_id", $staffID);
        }
        
        $q = $this->db->get();
        return $q->row();
    }

    // GET CURRENT DEFAULT YEAR -postgres
    public function getCurYear() {
        $this->db->select('to_char(CURRENT_DATE, \'YYYY\') AS "CUR_YEAR"');
        
        // $this->db->from("DUAL");
        
        $q = $this->db->get();
        return $q->row();
    }

    // GET TRAINING DATE FROM -postgres
    public function getTrainingDateFrom($refID)
    {
        $this->db->select('to_char(CURRENT_DATE, \'YYYYMMDD\') AS "CUR_DATE", to_char(th_date_from, \'YYYYMMDD\') AS "TH_DATE_FROM",
        to_char(th_date_to, \'YYYYMMDD\') AS "TH_DATE_TO_FULL", to_char(th_date_to, \'YYYY\') AS "TH_DATE_TO_YEAR"');
        $this->db->from('ims_hris.training_head');
        $this->db->where("th_ref_id", $refID);
        $q = $this->db->get();
        
        return $q->row();
    }

    // GET TRAINING HEAD BASED ON FILTER -postgres
    public function getTrainingList($defIntExt = null, $curUsrDept = null, $defMonth = null, $curYear = null, $defTrSts = null, $evaluation = null, $screRpt = null)
    {
        if($screRpt == '1') {
            $this->db->select('th_ref_id TH_REF_ID, 
                                th_training_title TH_TRAINING_TITLE, 
                                th_training_desc TH_TRAINING_DESC, 
                                th_type TH_TYPE, 
                                th_status TH_STATUS, 
                                th_internal_external TH_INTERNAL_EXTERNAL,
                                th_level TH_LEVEL, 
                                th_training_venue TH_TRAINING_VENUE, 
                                th_training_country TH_TRAINING_COUNTRY, 
                                th_organizer_name TH_ORGANIZER_NAME, 
                                th_organizer_level TH_ORGANIZER_LEVEL, 
                                th_organizer_address TH_ORGANIZER_ADDRESS,
                                th_organizer_postcode TH_ORGANIZER_POSTCODE, 
                                th_organizer_city TH_ORGANIZER_CITY, 
                                th_organizer_state TH_ORGANIZER_STATE, 
                                th_organizer_country TH_ORGANIZER_COUNTRY, 
                                th_sponsor TH_SPONSOR, 
                                to_char(th_date_from, \'DD/MM/YYYY\') AS "TH_DATE_FROM",
                                to_char(th_date_to, \'DD/MM/YYYY\') AS "TH_DATE_TO", 
                                th_total_hours TH_TOTAL_HOURS, 
                                th_training_fee TH_TRAINING_FEE, 
                                to_char(th_apply_closing_date, \'DD-MM-YYYY\') AS "TH_APP_CLOSING_DATE", 
                                th_current_participant TH_CURRENT_PARTICIPANT, 
                                th_max_participant TH_MAX_PARTICIPANT,
                                th_open TH_OPEN, 
                                th_dept_code TH_DEPT_CODE, 
                                th_enter_by TH_ENTER_BY, 
                                th_enter_date TH_ENTER_DATE, 
                                th_approve_by TH_APPROVE_BY, 
                                th_approve_date TH_APPROVE_DATE, 
                                th_training_state TH_TRAINING_STATE, 
                                th_attendance_type TH_ATTENDANCE_TYPE,
                                th_print_certificate TH_PRINT_CERTIFICATE, 
                                th_evaluation_compulsory TH_EVALUATION_COMPULSORY, 
                                th_service_group TH_SERVICE_GROUP, 
                                th_category TH_CATEGORY, 
                                to_char(th_evaluation_date_from, \'DD-MM-YYYY\') AS "TH_EVA_DATE_FROM",
                                to_char(th_evaluation_date_to, \'DD-MM-YYYY\') AS "TH_EVA_DATE_TO", 
                                th_training_history TH_TRAINING_HISTORY, 
                                th_competency_code TH_COMPETENCY_CODE, 
                                th_training_code TH_TRAINING_CODE, 
                                th_offer TH_OFFER, 
                                th_generate_cpd TH_GENERATE_CPD,
                                to_char(th_time_from, \'HH:MI AM\') AS "TIME_FR", 
                                to_char(th_time_to, \'HH:MI AM\') AS "TIME_T", 
                                to_char(th_confirm_date_from, \'DD-MM-YYYY\') AS "TH_CON_DATE_FROM",
                                to_char(th_confirm_date_to, \'DD-MM-YYYY\') AS "TH_CON_DATE_TO", 
                                th_field TH_FIELD,
                                tsr_refid TSR_REFID
                            ');
            $this->db->from('ims_hris.training_head');
            $this->db->join("ims_hris.training_secretariat_report","th_ref_id = tsr_refid","LEFT");
        } else {
            $this->db->select('th_ref_id TH_REF_ID, 
                                th_training_title TH_TRAINING_TITLE, 
                                th_training_desc TH_TRAINING_DESC, 
                                th_type TH_TYPE, 
                                th_status TH_STATUS, 
                                th_internal_external TH_INTERNAL_EXTERNAL,
                                th_level TH_LEVEL, 
                                th_training_venue TH_TRAINING_VENUE, 
                                th_training_country TH_TRAINING_COUNTRY, 
                                th_organizer_name TH_ORGANIZER_NAME, 
                                th_organizer_level TH_ORGANIZER_LEVEL, 
                                th_organizer_address TH_ORGANIZER_ADDRESS,
                                th_organizer_postcode TH_ORGANIZER_POSTCODE, 
                                th_organizer_city TH_ORGANIZER_CITY, 
                                th_organizer_state TH_ORGANIZER_STATE, 
                                th_organizer_country TH_ORGANIZER_COUNTRY, 
                                th_sponsor TH_SPONSOR,
                                to_char(th_date_from, \'DD/MM/YYYY\') AS "TH_DATE_FROM",
                                to_char(th_date_to, \'DD/MM/YYYY\') AS "TH_DATE_TO", 
                                th_total_hours TH_TOTAL_HOURS, 
                                th_training_fee TH_TRAINING_FEE, 
                                to_char(th_apply_closing_date, \'DD-MM-YYYY\') AS "TH_APP_CLOSING_DATE",
                                th_current_participant TH_CURRENT_PARTICIPANT, 
                                th_max_participant TH_MAX_PARTICIPANT,
                                th_open TH_OPEN, 
                                th_dept_code TH_DEPT_CODE, 
                                th_enter_by TH_ENTER_BY, 
                                th_enter_date TH_ENTER_DATE, 
                                th_approve_by TH_APPROVE_BY, 
                                th_approve_date TH_APPROVE_DATE, 
                                th_training_state TH_TRAINING_STATE, 
                                th_attendance_type TH_ATTENDANCE_TYPE,
                                th_print_certificate TH_PRINT_CERTIFICATE, 
                                th_evaluation_compulsory TH_EVALUATION_COMPULSORY, 
                                th_service_group TH_SERVICE_GROUP, 
                                th_category TH_CATEGORY,
                                to_char(th_evaluation_date_from, \'DD-MM-YYYY\') AS "TH_EVA_DATE_FROM",
                                to_char(th_evaluation_date_to, \'DD-MM-YYYY\') AS "TH_EVA_DATE_TO",
                                th_training_history TH_TRAINING_HISTORY, 
                                th_competency_code TH_COMPETENCY_CODE, 
                                th_training_code TH_TRAINING_CODE, 
                                th_offer TH_OFFER, 
                                th_generate_cpd TH_GENERATE_CPD, 
                                to_char(th_time_from, \'HH:MI AM\') AS "TIME_FR", 
                                to_char(th_time_to, \'HH:MI AM\') AS "TIME_T",
                                to_char(th_confirm_date_from, \'DD-MM-YYYY\') AS "TH_CON_DATE_FROM",
                                to_char(th_confirm_date_to, \'DD-MM-YYYY\') AS "TH_CON_DATE_TO", 
                                th_field TH_FIELD
                            ');
            $this->db->from('ims_hris.training_head');
        }

        if(!empty($curUsrDept)) {
            $this->db->where("th_dept_code = '$curUsrDept'");
        }

        if(!empty($defMonth) && !empty($curYear)) {
            $this->db->where("((coalesce(to_char(th_date_from,'MM/YYYY'),'') = '$defMonth'||'/'||'$curYear'))");
        } elseif(!empty($defMonth)) {
            $this->db->where("((coalesce(to_char(th_date_from,'MM'),'') = '$defMonth'))");
        } elseif(!empty($curYear)) {
            $this->db->where("((coalesce(to_char(th_date_from,'YYYY'),'') = '$curYear'))");
        }
        
        if($defIntExt == 'INTERNAL' || $defIntExt == 'EXTERNAL' || $defIntExt == 'EXTERNAL_AGENCY' ) {
            $this->db->where("th_internal_external", $defIntExt);
        } elseif($defIntExt == '1') {
            $this->db->where("th_internal_external NOT IN ('EXTERNAL_AGENCY')");
        }

        if($defTrSts == 'POSTPONE' || $defTrSts == 'REJECT' || $defTrSts == 'APPROVE' || $defTrSts == 'ENTRY') {
            $this->db->where("th_status", $defTrSts);
        } elseif(empty($defTrSts)) {
            $this->db->where("coalesce(th_status,'ENTRY') = 'APPROVE'");
        }
        
        if($evaluation == '1') {
            $this->db->where("th_ref_id IN (SELECT thd_ref_id
                                FROM ims_hris.training_head_detl
                                WHERE coalesce(thd_evaluation,'N') = 'Y')");
        }

        if($screRpt == '1') {
            // $this->db->where("to_char(to_date(th_date_from, 'DD/MM/YYYY'), 'YYYYMMDD')::numeric < to_char(to_date(current_date, 'DD/MM/YYYY'), 'YYYYMMDD')::numeric");
            $this->db->where("to_char(th_date_from, 'YYYYMMDD')::numeric < to_char(current_date, 'YYYYMMDD')::numeric");
            $this->db->where("th_organizer_name = 'ULAT'");
        }
        
        $this->db->order_by("th_date_from, th_date_to, th_training_title");

        $q = $this->db->get();
        return $q->result();
    }

    // GET TRAINING DETAIL -postgres
    public function getTrDetl($refid)
    {
        $this->db->select('th_ref_id TH_REF_ID, 
                            th_training_title TH_TRAINING_TITLE,
                            th_training_venue TH_TRAINING_VENUE,
                            to_char(th_date_from, \'DD/MM/YYYY\') AS "TH_DATEFR",
                            to_char(th_date_to, \'DD/MM/YYYY\') AS "TH_DATETO",  
                            to_char(th_time_from, \'HH:MI AM\') AS "TIME_FR", 
                            to_char(th_time_to, \'HH:MI AM\') AS "TIME_T", 
                            to_char(th_confirm_date_to, \'DD/MM/YYYY\') AS "TH_CON_DATE_TO"');
        $this->db->from('ims_hris.training_head');
        $this->db->where("th_ref_id", $refid);
        $this->db->where("th_status = 'APPROVE'");

        $q = $this->db->get();
        return $q->row();
    }

    // GET DEPARTMENT LIST -postgres
    public function getDeptList() {
        $this->db->select('dm_dept_code DM_DEPT_CODE, dm_dept_desc DM_DEPT_DESC, dm_dept_code ||\' - \'|| dm_dept_desc AS "DEPT_CODE_DESC"');
        $this->db->from('ims_hris.department_main');
		$this->db->where('coalesce(dm_status,\'INACTIVE\')', 'ACTIVE');
		$this->db->where('dm_level <= 2');
        $this->db->order_by('dm_dept_code');
        $q = $this->db->get();
		        
        return $q->result();
    }

    // POPULATE DEPARTMENT LIST -postgres
    public function populateDept($deptCode) {
        $this->db->select('dm_dept_code DM_DEPT_CODE, dm_dept_desc DM_DEPT_DESC, dm_dept_code ||\' - \'|| dm_dept_desc AS "DEPT_CODE_DESC"');
        $this->db->from('ims_hris.department_main');
		$this->db->where('COALESCE(dm_status,\'INACTIVE\')', 'ACTIVE');
        $this->db->where('dm_level IN (1,2)');
        if(!empty($deptCode)) {
            $this->db->where('dm_dept_code', $deptCode);
        }
        $this->db->order_by('dm_dept_code');
        $q = $this->db->get();
		        
        return $q->result();
    }

    // POPULATE DEPARTMENT LIST 2 -postgres
    public function populateDept2($deptCode) {
        $this->db->select('dm_dept_code DM_DEPT_CODE, dm_dept_desc DM_DEPT_DESC, dm_dept_code ||\' - \'|| dm_dept_desc AS "DEPT_CODE_DESC"');
        $this->db->from('ims_hris.department_main');
		$this->db->where('COALESCE(dm_status,\'INACTIVE\')', 'ACTIVE');
        $this->db->where('dm_level IN (1,2)');
        if(!empty($deptCode)) {
            $this->db->where('dm_dept_code', $deptCode);
        }
        $this->db->order_by('dm_dept_code');
        $q = $this->db->get();
		        
        return $q->result();
    }

    // GET YEAR DROPDOWN -postgres
    public function getYearList() {		
        $this->db->select('to_char(cm_date, \'YYYY\') AS "CM_YEAR"');
        $this->db->from("ims_hris.calendar_main");
		$this->db->where("to_char(cm_date, 'YYYY')::numeric >= to_char(current_date, 'YYYY')::numeric - 15");
        $this->db->group_by("to_char(cm_date, 'YYYY')");
        $this->db->order_by("to_char(cm_date, 'YYYY') DESC");
        $q = $this->db->get();
		        
        return $q->result();
    } 

    // GET MONTH DROPDOWN -postgres
    public function getMonthList() 
    {		
        $this->db->select('to_char(cm_date, \'MM\') AS "CM_MM", to_char(cm_date, \'MONTH\') AS "CM_MONTH"');
        $this->db->from("ims_hris.calendar_main");
        $this->db->group_by("to_char(cm_date,'MM'), to_char(cm_date, 'MONTH')");
        $this->db->order_by("to_char(cm_date, 'MM')");
        $q = $this->db->get();
		        
        return $q->result();
    } 

    // GET STAFF LIST BASED FROM TRAINING
    // public function getStaffTrainingApplication($refid, $staffID = null)
    // {
    //     $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_DEPT_CODE, 
    //                        SJS_STATUS_DESC, STH_STATUS, SM_EMAIL_ADDR, 
    //                        to_char(STH_APPLY_DATE, 'DD/MM/YYYY') AS STHAPPDATE,
    //                        STH_DEPT_TRAINING_BENEFIT");
    //     $this->db->from('STAFF_TRAINING_HEAD');
    //     $this->db->join("STAFF_MAIN", "STH_STAFF_ID = SM_STAFF_ID");
    //     $this->db->join("STAFF_SERVICE", "STH_STAFF_ID = SS_STAFF_ID");
    //     $this->db->join("STAFF_JOB_STATUS", "SS_JOB_STATUS = SJS_STATUS_CODE");
    //     $this->db->where("STH_TRAINING_REFID", $refid);
    //     $this->db->where("STH_STATUS = 'RECOMMEND'");

    //     if(!empty($staffID)) {
    //         $this->db->where("SM_STAFF_ID", $staffID);

    //         $q = $this->db->get();
    //         return $q->row();
    //     } else {
    //         $this->db->order_by("SM_STAFF_NAME");

    //         $q = $this->db->get();
    //         return $q->result();
    //     }   
    // }

    // GET STAFF LIST BASED FROM TRAINING -postgres
    public function getStaffTrainingApplication($refid)
    {

        $this->db->select('sth_staff_id STH_STAFF_ID, sm_staff_id SM_STAFF_ID, sm_staff_name SM_STAFF_NAME,  sm_dept_code SM_DEPT_CODE, sjs_status_desc SJS_STATUS_DESC, sth_status STH_STATUS, sm_email_addr SM_EMAIL_ADDR, TO_CHAR(sth_apply_date, \'DD/MM/YYYY\') AS "STHAPPDATE", sth_dept_training_benefit STH_DEPT_TRAINING_BENEFIT');
        $this->db->from("ims_hris.staff_training_head");
        $this->db->join("ims_hris.staff_main", "sth_staff_id = sm_staff_id", "LEFT");
        $this->db->join("ims_hris.staff_service", "sth_staff_id = ss_staff_id", "LEFT");
        $this->db->join("ims_hris.staff_job_status", "ss_job_status = sjs_status_code", "LEFT");
        $this->db->where("sth_status = 'RECOMMEND'");
        $this->db->where("sth_training_refid", $refid);
        $this->db->order_by("ims_hris.get_staff_dept(sth_staff_id::text)");
        $q = $this->db->get();
		        
        return $q->result();
    }

    // GET STAFF EVA ID -postgres
    public function getStaffTrainingApplicationEvaID($refid, $staff_id)
    {
        /*$query = "SELECT SM_STAFF_ID||' '||SM_STAFF_NAME||' ('||SM_EMAIL_ADDR||')' STAFF
		FROM STAFF_TRAINING_HEAD,STAFF_MAIN
		WHERE STH_TRAINING_REFID = '$refid'
        AND STH_STATUS = 'RECOMMEND'
        AND NVL(STH_VERIFY_BY,STH_RECOMMEND_BY) = SM_STAFF_ID
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SM_STAFF_ID||' '||SM_STAFF_NAME||' ('||SM_EMAIL_ADDR||')' STAFF
        FROM LEAVE_STAFF_HIERARCHY,STAFF_MAIN,STAFF_TRAINING_HEAD
        WHERE LEAVE_STAFF_HIERARCHY.LSH_STAFF_ID = STH_STAFF_ID
        AND STH_TRAINING_REFID = '$refid'
        AND STH_STATUS = 'RECOMMEND'
        AND STH_VERIFY_BY IS NULL 
        AND STH_RECOMMEND_BY IS NULL
        AND NVL(LEAVE_STAFF_HIERARCHY.LSH_RECOMMEND_BY,LSH_APPROVE_BY) = SM_STAFF_ID
        AND STH_STAFF_ID = '$staff_id'";*/

        $query = 'SELECT sm_staff_id||\' \'||sm_staff_name||\' (\'||sm_email_addr||\')\' STAFF
        FROM ims_hris.staff_training_head,ims_hris.staff_main
        WHERE STH_TRAINING_REFID = \'$refid\'
        AND sth_status = \'RECOMMEND\'
        AND COALESCE(sth_verify_by,sth_recommend_by) = sm_staff_id
        AND sth_staff_id = \'$staff_id\'
        UNION
        SELECT sm_staff_id||\' \'||sm_staff_name||\' (\'||sm_email_addr||\')\' "STAFF"
        FROM ims_hris.leave_staff_hierarchy,ims_hris.staff_main,ims_hris.staff_training_head
        WHERE ims_hris.leave_staff_hierarchy.lsh_staff_id = sth_staff_id
        AND sth_training_refid = \'$refid\'
        AND sth_status = \'RECOMMEND\'
        AND sth_verify_by IS NULL 
        AND sth_recommend_by IS NULL
        AND COALESCE(ims_hris.leave_staff_hierarchy.lsh_recommend_by,lsh_approve_by) = sm_staff_id
        AND sth_staff_id = \'$staff_id\'';

        $q = $this->db->query($query);
        return $q->row();
    }

    // GET EVALUATOR INFO -postgres
    public function getEvaluatorInfo($refid, $staffID)
    {
        /*$query = "SELECT SM_STAFF_ID||' - '||SM_STAFF_NAME||' ('||SM_EMAIL_ADDR||')' AS STAFF
        FROM STAFF_TRAINING_HEAD, STAFF_MAIN
        WHERE STH_TRAINING_REFID = '$refid'
        AND STH_STATUS = 'RECOMMEND'
        AND NVL(STH_VERIFY_BY,STH_RECOMMEND_BY) = SM_STAFF_ID
        AND STH_STAFF_ID = '$staffID'
        UNION
        SELECT SM_STAFF_ID||' - '||SM_STAFF_NAME||' ('||SM_EMAIL_ADDR||')' AS STAFF
        FROM LEAVE_STAFF_HIERARCHY,STAFF_MAIN,STAFF_TRAINING_HEAD
        WHERE LEAVE_STAFF_HIERARCHY.LSH_STAFF_ID = STH_STAFF_ID
        AND STH_TRAINING_REFID = '$refid'
        AND STH_STATUS = 'RECOMMEND'
        AND STH_VERIFY_BY IS NULL 
        AND STH_RECOMMEND_BY IS NULL
        AND NVL(LEAVE_STAFF_HIERARCHY.LSH_RECOMMEND_BY,LSH_APPROVE_BY) = SM_STAFF_ID
        AND STH_STAFF_ID = '$staffID'";*/



        $query = 'SELECT sm_staff_id||\' - \'||sm_staff_name||\' (\'||sm_email_addr||\')\' AS "STAFF"
        FROM ims_hris.STAFF_TRAINING_HEAD, ims_hris.STAFF_MAIN
        WHERE sth_training_refid = \'$refid\'
        AND sth_status = \'RECOMMEND\'
        AND coalesce(sth_verify_by,sth_recommend_by) = sm_staff_id
        AND sth_staff_id = \'$staffID\'
        UNION
        SELECT sm_staff_id||\' - \'||sm_staff_name||\' (\'||sm_email_addr||\')\' AS "STAFF"
        FROM ims_hris.leave_staff_hierarchy,ims_hris.staff_main,ims_hris.staff_training_head
        WHERE ims_hris.leave_staff_hierarchy.lsh_staff_id = sth_staff_id
        AND sth_training_refid = \'$refid\'
        AND sth_status = \'RECOMMEND\'
        AND sth_verify_by IS NULL 
        AND sth_recommend_by IS NULL
        AND coalesce(ims_hris.leave_staff_hierarchy.lsh_recommend_by,lsh_approve_by) = sm_staff_id
        AND sth_staff_id = \'$staffID\'';

        $q = $this->db->query($query);
        return $q->row();
    }

    // GET EVALUATOR ID -postgres
    public function getEvaluatorID($refid, $staffID)
    {
        /*$query = "SELECT NVL(STH_VERIFY_BY, NVL(STH_RECOMMEND_BY, NVL(LSH_RECOMMEND_BY, LSH_APPROVE_BY))) AS EVAID
        FROM STAFF_TRAINING_HEAD,LEAVE_STAFF_HIERARCHY,TRAINING_HEAD_DETL
        WHERE STH_STAFF_ID = '$staffID'
        AND STH_TRAINING_REFID = '$refid'
        AND NVL(THD_EVALUATION,'N') = 'Y' 
        AND THD_REF_ID = STH_TRAINING_REFID
        AND LSH_STAFF_ID = STH_STAFF_ID";*/

        $query = 'SELECT coalesce(sth_verify_by, coalesce(sth_recommend_by, coalesce(lsh_recommend_by, lsh_approve_by)))AS "EVAID"
        FROM ims_hris.staff_training_head,ims_hris.leave_staff_hierarchy,ims_hris.training_head_detl
        WHERE sth_staff_id = \'$staffID\'
        AND sth_training_refid = \'$refid\'
        AND coalesce(thd_evaluation,\'N\') = \'Y\' 
        AND thd_ref_id = sth_training_refid
        AND lsh_staff_id = sth_staff_id';

        $q = $this->db->query($query);
        return $q->row();
    }

    // GET STAFF EMAIL DISTINCT -postgres
    public function getStaffMainDis($refid, $staffID, $resend = null)
    {
        if($resend == 1) {
            $query = "select distinct sm_email_addr, coalesce(sth_verify_by,sth_recommend_by) staff, sm_staff_name
            from ims_hris.staff_training_head, ims_hris.staff_main
            where sth_training_refid = '$refid'
            and sth_status = 'APPROVE'
            and coalesce(sth_verify_by, sth_recommend_by) = sm_staff_id
            and sth_staff_id = '$staffID'
            union
            select distinct sm_email_addr, coalesce(leave_staff_hierarchy.lsh_recommend_by, lsh_approve_by) staff, sm_staff_name
            from ims_hris.leave_staff_hierarchy, ims_hris.staff_main, ims_hris.staff_training_head
            where leave_staff_hierarchy.lsh_staff_id = sth_staff_id
            and sth_training_refid = '$refid'
            and sth_status = 'APPROVE'
            and sth_verify_by IS NULL 
            and sth_recommend_by IS NULL
            and coalesce(leave_staff_hierarchy.lsh_recommend_by, lsh_approve_by) = sm_staff_id
            and sth_staff_id = '$staffID'";
        } else {
            $query = "select distinct sm_email_addr, coalesce(sth_verify_by,sth_recommend_by) staff, sm_staff_name
            from ims_hris.staff_training_head, ims_hris.staff_main
            where sth_training_refid = '$refid'
            and sth_status = 'RECOMMEND'
            and coalesce(sth_verify_by, sth_recommend_by) = sm_staff_id
            and sth_staff_id = '$staffID'
            union
            select distinct sm_email_addr, coalesce(leave_staff_hierarchy.lsh_recommend_by, lsh_approve_by) staff, sm_staff_name
            From ims_hris.leave_staff_hierarchy, ims_hris.staff_main, ims_hris.staff_training_head
            where leave_staff_hierarchy.lsh_staff_id = sth_staff_id
            and sth_training_refid = '$refid'
            and sth_status = 'RECOMMEND'
            and sth_verify_by is null 
            and sth_recommend_by is null
            and coalesce(leave_staff_hierarchy.lsh_recommend_by, lsh_approve_by) = sm_staff_id
            and sth_staff_id = '$staffID'";
        }
        

        $q = $this->db->query($query);
        return $q->row_case('UPPER');
    }

    // GET TRAINING COORDINATOR -postgres
    public function getTrCoor($refid)
    {
        /*$query = "select tm_title_desc||' '||sm_staff_name as staff_name, thd_coordinator_telno
        from training_head, training_head_detl, staff_main, title_main
        where th_ref_id = '$refid'
        and th_status = 'APPROVE'
        and th_ref_id = thd_ref_id
        and thd_coordinator = sm_staff_id
        and sm_stas_title = tm_title_code(+)";*/

        $query = "select tm_title_desc||' '||sm_staff_name as staff_name, thd_coordinator_telno
        from ims_hris.training_head
        full outer join ims_hris.training_head_detl on th_ref_id = thd_ref_id
        full outer join ims_hris.staff_main on thd_coordinator = sm_staff_id
        full outer join ims_hris.title_main on sm_stas_title = tm_title_code
        where th_ref_id = '$refid'
        and th_status = 'APPROVE'";

        $q = $this->db->query($query);
        return $q->row_case('UPPPER');
    }

    // VERIFY TRAINING -postgres
    public function verifyTraining($refid, $staffID)
    {
        $this->db->select("*");
        $this->db->from('ims_hris.staff_training_detl');
        $this->db->where("std_training_refid", $refid);
        $this->db->where("std_staff_id", $staffID);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    /*_____________________
        INSERT PROCESS
    _______________________*/

    // INSERT SENT EMAIL STATUS -postgres
    public function insertEmailSts($refid, $staffID)
    {
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "std_training_refid" => $refid,
            "std_staff_id" => $staffID,
            "std_sendmemo" => 'Y'
        );

        $this->db->set("std_sendmemo_date", $curDate, false);

        return $this->db->insert("ims_hris.staff_training_detl", $data);
    }

    /*_____________________
        UPDATE PROCESS
    _______________________*/

    // UPDATE STAFF TRAINING HEAD - APPROVE APPLICANT -postgres
    public function apprOrReApp($refid, $staffID, $eveluatorID, $remark, $sts)
    {
        if($sts == 1) {
            $sthSts = 'APPROVE';
        } elseif($sts == 0) {
            $sthSts = 'REJECT';
        } 

        $curUsr = $this->staff_id;
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_status" => $sthSts,
            "sth_approve_by" => $curUsr,
            "sth_evaluator_id" => $eveluatorID,
            "sth_remark" => $remark
        );

        $this->db->set("sth_approve_date", $curDate, false);

        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $staffID);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // UPDATE SENT EMAIL STATUS -postgres
    public function updateEmailSts($refid, $staffID)
    {
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "std_sendmemo" => 'Y'
        );

        $this->db->set("std_sendmemo_date", $curDate, false);

        $this->db->where("std_training_refid", $refid);
        $this->db->where("std_staff_id", $staffID);

        return $this->db->update("ims_hris.staff_training_detl", $data);
    }


    /*_____________________
        SEND EMAIL
    _______________________*/
    // -hold postgres

    public function sendEmail($memo_from, $staff_app_email, $email_cc, $msg_title, $msg_content) {
        // var_dump($memo_from);
        // exit();
        // $memo_from_name = 'BSM';
		// if (empty($memo_from)) {
        //     $memo_from = 'bsm.latihan@upsi.edu.my';
		// }
		// if (empty($email_cc)) {
		// 	$email_cc = null;
		// }
		
		// // execute create_memo procedure
		// $sql = 'begin utl_mail.send(
		// 			sender=>?,
		// 			recipients=>?,
		// 			cc=>?,
		// 			subject=>?,
		// 			message=>?,
		// 			mime_type=>\'text/html\'		
		// 		); end;';
        // $q = $this->db->query($sql, array($memo_from, $staff_app_email, $email_cc, $msg_title, $msg_content));

		// if ($q === FALSE) {
		// 	// return 0 if fail to execute create_memo
		// 	return 0;
		// }
		
        // return 1;

        $memo_from_name = 'BSM';
		if (empty($memo_from)) {
            $memo_from = 'bsm.latihan@upsi.edu.my';
		}
        
        // load library
        $this->load->library('email');
        $this->email->clear();
        $this->email->set_mailtype('html');

        $this->email->from($memo_from, $memo_from_name);
        $this->email->to($staff_app_email);

        if (!empty($email_cc)) {
            $this->email->cc($email_cc);
        }

        $this->email->subject($msg_title);
        $this->email->message($msg_content);

        return $this->email->send();
        // var_dump($this->email->send());
        // exit();

    }


    /*===========================================================
       ASSIGN TRAINING TO STAFF
    =============================================================*/

    // GET ALL STAFF FROM TRAINING -postgres
    public function getAssignStaff($refid, $staffId = null)
    {
        $this->db->select("sm_staff_id, sm_staff_name, sm_dept_code, sth_participant_role, 
                            tpr_desc, sth_status, sth_staff_training_benefit, 
                            sth_dept_training_benefit, sth_remark, sm_staff_id||' - '||sm_staff_name as sm_id_name");
        $this->db->from('ims_hris.staff_training_head');
        $this->db->join("ims_hris.staff_main", "sth_staff_id = sm_staff_id");
        $this->db->join("ims_hris.training_participant_role", "tpr_code = sth_participant_role", "LEFT");
        $this->db->where("sth_training_refid", $refid);
        if(empty($staffId)) {
            $this->db->order_by("sth_staff_id, upper(ims_hris.get_staff_dept(sth_staff_id)), sth_status, upper(ims_hris.get_staff_name(sth_staff_id))");

            $q = $this->db->get();
            return $q->result_case('UPPER');
        } 
        elseif(!empty($staffId)) {
            $this->db->where("sth_staff_id", $staffId);

            $q = $this->db->get();
            return $q->row_case('UPPER');
        }
    } 

    // FILTER STAFF DROPDOWN LIST -postgres
    public function getStaffList($refid, $deptCode = null)
    {
        $this->db->select("sm_staff_id, sm_staff_name, sm_staff_id||' - '||sm_staff_name as staff_id_name, sm_dept_code");
        $this->db->from('ims_hris.staff_main, ims_hris.staff_service, ims_hris.staff_status');
        $this->db->where("ss_staff_id = sm_staff_id");
        $this->db->where("sm_staff_status = ss_status_code");
        $this->db->where("ss_job_status in ('01','03','08','09','10','02','11')");
        $this->db->where("ss_status_sts = 'ACTIVE'");
        if(!empty($deptCode)) {
            $this->db->where("sm_dept_code", $deptCode);
        }
        $this->db->where("sm_staff_id not in
        (select sth_staff_id from ims_hris.staff_training_head where sth_training_refid = '$refid')");
        $this->db->order_by("sm_staff_name");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    } 

    // GET PARTICIPANT ROLE -postgres
    public function getRoleList()
    {
        $this->db->select("*");
        $this->db->from('ims_hris.training_participant_role');
        $this->db->order_by("tpr_code");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET PARTICIPANT STATUS -postgres
    public function getPstatusList()
    {
        $this->db->select("*");
        $this->db->from('ims_hris.training_participant_status');
        $this->db->order_by("tps_code");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // CHECK STAFF IN STAFF_TRAINING_COST_MAIN -postgres
    public function checkStaffTr($refid, $staffId)
    {
        $this->db->select("*");
        $this->db->from('ims_hris.staff_training_cost_main');
        $this->db->where("stcm_training_refid", $refid);
        $this->db->where("stcm_staff_id", $staffId);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }
    
    /*_____________________
        INSERT PROCESS
    _______________________*/

    // INSERT ASSIGNED STAFF -postgres
    public function saveAssignedStaff($form, $refid)
    {
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_staff_id" => $form['staff_id'],
            "sth_training_refid" => $refid,
            "sth_participant_role" => $form['role'],
            "sth_staff_training_benefit" => $form['training_benefit_staff'],
            "sth_dept_training_benefit" => $form['training_benefit_department'],
            "sth_status" => $form['status'],
            "sth_remark" => $form['remark'],
        );

        $this->db->set("sth_apply_date", $curDate, false);

        return $this->db->insert("ims_hris.staff_training_head", $data);
    }

    // -postgres
    public function saveAssignedStaffBatch($refid, $sid, $role, $sts)
    {
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "sth_staff_id" => $sid,
            "sth_training_refid" => $refid,
            "sth_participant_role" => $role,
            "sth_status" => $sts
        );

        $this->db->set("sth_apply_date", $curDate, false);

        return $this->db->insert("ims_hris.staff_training_head", $data);
    }

    /*_____________________
        UPDATE PROCESS
    _______________________*/

    // SAVE UPDATE ASSIGNED STAFF -postgres
    public function saveUpdAssigned($form, $refid, $staffid)
    {
        $data = array(
            "sth_participant_role" => $form['role'],
            "sth_staff_training_benefit" => $form['training_benefit_staff'],
            "sth_dept_training_benefit" => $form['training_benefit_department'],
            "sth_status" => $form['status'],
            "sth_remark" => $form['remark'],
        );

        $this->db->where('sth_training_refid', $refid);
        $this->db->where('sth_staff_id', $staffid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    /*_____________________
        DELETE PROCESS
    _______________________*/

    // DELETE ASSIGNED STAFF -postgres
    public function deleteAssignedStaff($refid, $staffId) {
        $this->db->where('sth_staff_id', $staffId);
        $this->db->where('sth_training_refid', $refid);

        return $this->db->delete('ims_hris.staff_training_head');
    }


    /*===========================================================
       TRAINING QURIES - ATF008
    =============================================================*/

    // GET TRAINING STATUS LIST -postgres
    public function getTrainingStsList()
    {
        $this->db->select("th_status");
        $this->db->from('ims_hris.training_head');
        $this->db->group_by("th_status");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET TRAINING COST -postgres
    public function getTrainingCost($tsRefID)
    {
        $this->db->select("tc_cost_code, tct_desc, tc_amount, tc_remark");
        $this->db->from("ims_hris.training_cost, ims_hris.training_cost_type");
        $this->db->where("tc_cost_code = tct_code");
        $this->db->where("tc_training_refid", $tsRefID);

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    /*===========================================================
       APPROVE TRAINING SETUP - ATF027
    =============================================================*/

    // STAFF TRAINING RECORDS -postgres
    public function getStaffTrainingRecords($refid)
    {
        $this->db->select("count(1) as cc");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->where("sth_training_refid", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // APPROVE TRAINING -postgres
    public function approveTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_status" => 'APPROVE',
            "th_approve_by" => $currentUsr
        );

        $this->db->set("th_approve_date", $curDate, false);

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    } 
    
    // POSTPONE TRAINING -postgres
    public function postponeTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_status" => 'POSTPONE',
            "th_approve_by" => $currentUsr
        );

        $this->db->set("th_approve_date", $curDate, false);

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    }  
    
    // REJECT TRAINING -postgres
    public function rejectTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_status" => 'REJECT',
            "th_approve_by" => $currentUsr
        );

        $this->db->set("th_approve_date", $curDate, false);

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    }

    // -postgres
    public function rejectStaffTraining($refid)
    {
        // $currentUsr = $this->staff_id;
        // $curDate = 'SYSDATE';

        $data = array(
            "sth_status" => 'REJECT'
        );

        //$this->db->set("TH_APPROVE_DATE", $curDate, false);

        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // AMEND TRAINING -postgres
    public function amendTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "th_status" => 'ENTRY',
            "th_approve_by" => $currentUsr
        );

        $this->db->set("th_approve_date", $curDate, false);

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    }

    /*===========================================================
       EDIT APPROVE TRAINING SETUP - ATF044
    =============================================================*/

    // GET URL -postgres
    public function getEcommUrl()
    {
        $this->db->select("hp_parm_desc");
        $this->db->from("ims_hris.hradmin_parms");
        $this->db->where("hp_parm_code = 'ECOMMUNITY_STAFF_URL'");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    /*===========================================================
       QUERY STAFF TRAINING - ATF041
    =============================================================*/

    // GET STAFF LIST -postgres
    public function getStaffTrainingList($curUsrDept = null, $stfID = null)
    {
        $this->db->select("sm_staff_id, sm_staff_name, dm_dept_desc, ss_service_desc");
        $this->db->from("ims_hris.staff_main");
        $this->db->join("ims_hris.service_scheme", "ss_service_code = sm_job_code", "LEFT");
        $this->db->join("ims_hris.department_main", "dm_dept_code = sm_dept_code", "LEFT");
        if(!empty($curUsrDept)) {
            $this->db->where("sm_dept_code = '$curUsrDept'");
        }
        if(!empty($stfID)) {
            $this->db->where("upper(sm_staff_id) = upper('$stfID') OR upper(sm_staff_name) like upper('%$stfID%')");
        }
        $this->db->where("sm_staff_status in (select ss_status_code from ims_hris.staff_status where ss_status_sts='ACTIVE')");
        $this->db->where("sm_staff_type <> 'SYSTEM'");
        $this->db->order_by("sm_staff_name");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET STAFF LIST -postgres
    public function trainingListStaff($stfID)
    {
        $this->db->select("sth_training_refid, th_training_title, tps_desc, tpr_desc, sth_status, sth_remark,
                            CASE
                            WHEN sth_complete = 'Y' THEN 'YES'
                            ELSE 'NO'
                            END AS sthcomplete");
        $this->db->from("ims_hris.staff_training_head sth");
        $this->db->join("ims_hris.training_head th", "sth.sth_training_refid = th.th_ref_id", "LEFT");
        $this->db->join("ims_hris.training_participant_status tps", "sth.sth_participant_status = tps.tps_code", "LEFT");
        $this->db->join("ims_hris.training_participant_role tpr", "sth.sth_participant_role = tpr.tpr_code", "LEFT");
        $this->db->where("sth_staff_id", $stfID);
        $this->db->order_by("sth_training_refid");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET STAFF LIST -postgres
    public function applicationDetail($refid, $stfID)
    {
        $this->db->select("sth_training_refid ||' - '|| th_training_title training_id, to_char(sth_apply_date, 'DD/MM/YYYY') AS appl_date, 
                            CASE
                            WHEN std_training_calendar = 'Y' THEN 'YES'
                            ELSE 'NO'
                            END AS training_calendar,
                            CASE
                            WHEN std_work_related = 'Y' THEN 'YES'
                            ELSE 'NO'
                            END AS work_related, 
                            sth_staff_training_benefit, sth_verify_by ||' - '|| sm1.sm_staff_name AS ver_by,
                            to_char(sth_verify_date, 'DD/MM/YYYY') AS ver_date, sth_dept_training_benefit, sth_recommend_by ||' - '|| sm2.sm_staff_name AS rec_by, 
                            to_char(sth_recommend_date, 'DD/MM/YYYY') AS rec_date, sth_recommender_reason, sth_remark, sth_approve_by ||' - '|| sm3.sm_staff_name AS appr_by,
                            to_char(sth_approve_date, 'DD/MM/YYYY') AS appr_date, to_char(std_mpe_date, 'DD/MM/YYYY') AS mpe_date, 
                            sth_approve_reason, std_cancel_by ||' - '|| sm4.sm_staff_name AS canc_by, to_char(std_cancel_date, 'DD/MM/YYYY') AS canc_date, 
                            std_cancel_reason");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->join("ims_hris.training_head", "sth_training_refid = th_ref_id", "LEFT");
        $this->db->join("ims_hris.staff_training_detl", "sth_training_refid = std_training_refid AND sth_staff_id = std_staff_id", "LEFT");
        $this->db->join("ims_hris.staff_main sm1", "sm1.sm_staff_id = sth_verify_by", "LEFT");
        $this->db->join("ims_hris.staff_main sm2", "sm2.sm_staff_id = sth_recommend_by", "LEFT");
        $this->db->join("ims_hris.staff_main sm3", "sm3.sm_staff_id = sth_approve_by", "LEFT");
        $this->db->join("ims_hris.staff_main sm4", "sm4.sm_staff_id = std_cancel_by", "LEFT");
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $stfID);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    /*===========================================================
       Confirmation Attend Training - ATF148
    =============================================================*/

    // GET STAFF LIST -postgres
    public function getStaffTrainingApplicationConf($refid, $stfID = null)
    {
        $this->db->select("sth_training_refid, sm_staff_id, sm_staff_name, sm_dept_code, 
                            tpr_desc, to_char(sth_apply_date, 'DD/MM/YYYY') AS sthappdate,
                            CASE
                            WHEN std_attend = 'A' THEN 'Yes (Auto)'
                            WHEN std_attend = 'Y' THEN 'Yes'
                            WHEN std_attend = 'N' THEN 'No'
                            END AS std_attend, 
                            CASE
                            WHEN std_sendmemo = 'Y' THEN 'Yes'
                            WHEN std_sendmemo = 'Y' THEN 'No'
                            END AS std_sendmemo,
                            CASE
                            WHEN sth_hod_evaluation = 'Y' THEN 'Yes'
                            WHEN sth_hod_evaluation = 'N' THEN 'No'
                            END AS sth_hod_evaluation,
                            CASE
                            WHEN std_transportation = 'OWN_SHARING' THEN 'Owned  / Shared Transport'
                            WHEN std_transportation = 'UPSI' THEN 'UPSI'
                            END AS std_transportation, 
                            to_char(std_attend_date, 'DD/MM/YYYY') 
                            std_attend_date, std_attend_remark, std_attend AS std_attend2,
                            std_transportation AS std_transportation2");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->join("ims_hris.staff_main", "sth_staff_id = sm_staff_id", "LEFT");
        $this->db->join("ims_hris.staff_service", "sth_staff_id = ss_staff_id", "LEFT");
        $this->db->join("ims_hris.training_participant_role", "sth_participant_role = tpr_code", "LEFT");
        $this->db->join("ims_hris.staff_training_detl", "sth_training_refid = std_training_refid AND sth_staff_id = std_staff_id", "LEFT");

        if(!empty($stfID)) {
            $this->db->where("sth_staff_id", $stfID);
            $this->db->where("sth_training_refid", $refid);
            $this->db->where("sth_status = 'APPROVE'"); 

            $q = $this->db->get();
            return $q->row_case('UPPER');
        } else {
            $this->db->where("sth_training_refid", $refid);
            $this->db->where("sth_status = 'APPROVE'");
            $this->db->order_by("sth_staff_id");

            $q = $this->db->get();
            return $q->result_case('UPPER');
        }
    }

    // CHECK TRAINING EXTERNAL -postgres
    public function getTrainingExternal($refid)
    {
        $this->db->select("th_internal_external, th_training_code");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_ref_id", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // AUTO ATTEND CONFIRMATION UPDATE -postgres
    public function autoAttendConfirmation($refid, $staffID, $transport)
    {
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "std_attend" => 'A',
            "std_transportation" => $transport
        );

        $this->db->set("std_attend_date", $curDate, false);

        $this->db->where("std_training_refid", $refid);
        $this->db->where("std_staff_id", $staffID);

        return $this->db->update("ims_hris.staff_training_detl", $data);
    }

    // AUTO ATTEND CONFIRMATION INSERT -postgres
    public function autoAttendConfirmationIns($refid, $staffID, $transport)
    {
        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "std_training_refid" => $refid,
            "std_staff_id" => $staffID,
            "std_attend" => 'A',
            "std_transportation" => $transport
        );

        $this->db->set("std_attend_date", $curDate, false);

        return $this->db->insert("ims_hris.staff_training_detl", $data);
    }

    // COUNT TRAINING REQUIREMENT -postgres
    public function getTrainingRequirement($trCode, $staffID)
    {
        $query = "SELECT COUNT(1) AS r_count
        FROM ims_hris.training_requirement_main,ims_hris.training_requirement_detl
        WHERE trm_code = trd_id
        AND trm_setup_code IN (SELECT trs_code
        FROM ims_hris.training_requirement_setup 
        WHERE trs_remark IS NOT NULL
        AND trs_date_to IS NULL)
        AND trm_staff_id = '$staffID'
        AND trd_training_refid = '$trCode'
        AND trd_status <> 'APPROVE'";

        $q = $this->db->query($query);
        return $q->row_case('UPPER');
    }

    // UPDATE TRAINING REQUIREMENT DETAIL -postgres
    public function updTrainingRequirementDetl($trCode, $staffID)
    {
        $currStaff = $this->staff_id;

        $query = "update ims_hris.training_requirement_detl
        set trd_status = 'APPROVE',
        trd_update_by = '$currStaff',
        trd_update_date = date_trunc('second', NOW()::timestamp)
        where exists  
        (select trm_code 
        from ims_hris.training_requirement_main
        where trm_code = trd_id
        and trm_staff_id = '$staffID'
        and trm_setup_code in 
            (select trs_code
            from ims_hris.training_requirement_setup 
            where trs_remark is not null
            and trs_date_to is null)
            ) 
        and trd_training_refid = '$trCode'
        and trd_status <> 'APPROVE'";

        $q = $this->db->query($query);
        $afftectedRows =  $this->db->affected_rows();

        if ($afftectedRows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

        // if ($q === FALSE) {
		// 	// return 0 if fail to execute create_memo
		// 	return 0;
		// } 
        // return 1;
    }

    // GET REMARK LIST -postgres
    public function getRemarkList()
    {
        $this->db->select("*");
        $this->db->from("ims_hris.training_remark_setup");
        $this->db->where("trs_module = 'APPLICATION'");
        $this->db->order_by("trs_seq");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // SAVE UPDATE APPLICANT DETAILS -postgres
    public function saveUpdateApplicantDetails($form, $refid, $stfID)
    {
        $data = array(
            "std_attend" => $form['attendance_confirmation'],
            "std_transportation" => $form['transportation'],
            "std_attend_remark" => $form['absent_remark']
        );

        if(!empty($form['confirm_date'])){
            $confirm_date = "to_date('".$form['confirm_date']."', 'DD/MM/YYYY')";
            $this->db->set("std_attend_date", $confirm_date, false);
        }

        $this->db->where("std_training_refid", $refid);
        $this->db->where("std_staff_id", $stfID);

        return $this->db->update("ims_hris.staff_training_detl", $data);
    }

    // SAVE INSERT APPLICANT DETAILS -postgres
    public function saveInsertApplicantDetails($form, $refid, $stfID)
    {
        $data = array(
            "std_training_refid" => $refid,
            "std_staff_id" => $stfID,
            "std_attend" => $form['attendance_confirmation'],
            "std_transportation" => $form['transportation'],
            "std_attend_remark" => $form['absent_remark']
        );

        if(!empty($form['confirm_date'])){
            $confirm_date = "to_date('".$form['confirm_date']."', 'DD/MM/YYYY')";
            $this->db->set("std_attend_date", $confirm_date, false);
        }

        return $this->db->insert("ims_hris.staff_training_detl", $data);
    }

    // APPLICANT ATTENDANCE SUMMARY -postgres
    public function getCountAttendSum($refid, $att)
    {

        $this->db->select("count(1) count_attend");
        $this->db->from("ims_hris.staff_training_head");
        if($att == 0 || $att == 1 || $att == 2) {
            $this->db->join("ims_hris.staff_training_detl", "sth_training_refid = std_training_refid and sth_staff_id = std_staff_id", "LEFT");
        } 
        if($att == 0) {
            $this->db->where("std_attend in ('Y','A')");
        }
        if($att == 1) {
            $this->db->where("std_attend = 'N'");
        }
        if($att == 2) {
            //$this->db->join("STAFF_TRAINING_DETL", "STH_TRAINING_REFID = STD_TRAINING_REFID AND STH_STAFF_ID = STD_STAFF_ID");
            $this->db->where("std_attend is null");
        }
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_participant_role = 'D'");
        $this->db->where("sth_status = 'APPROVE'");
        

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET SEND DATE LIST -postgres
    public function getSendDate($refid)
    {
        $this->db->select("distinct to_char(std_sendmemo_date,'DD/MM/YYYY') send_date");
        $this->db->from("ims_hris.staff_training_detl");
        $this->db->where("std_training_refid", $refid);
        $this->db->where("std_sendmemo_date is not null");
       
        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET STAFF LIST (SERVICE BOOK) -postgres
    public function getStaffListSvcBook($refid) {
        $query = "SELECT sth_staff_id, sm_staff_name, sm_dept_code, tpr_desc, sth_status, sth_service_book, sbh_ref_id FROM (SELECT *
        FROM ims_hris.staff_training_head 
        JOIN ims_hris.staff_main ON sth_staff_id = sm_staff_id
        JOIN ims_hris.training_participant_role ON sth_participant_role = tpr_code 
        WHERE sth_staff_id IN
        (
        SELECT sth_staff_id
        FROM ims_hris.STAFF_TRAINING_HEAD,ims_hris.TRAINING_HEAD
        WHERE sth_status = 'APPROVE'
        AND sth_training_refid = th_ref_id 
        AND sth_participant_role = 'D'
        AND sth_training_refid = '$refid'
        AND to_char(th_date_from,'YYYY') < '2016'
        AND th_internal_external <> 'EXTERNAL_AGENCY'
        AND (((sth_service_book IS NULL OR sth_service_book = 'N') AND NOT EXISTS (SELECT *
        FROM ims_hris.service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082')) 
        
        OR (sth_service_book = 'Y' 
        AND NOT EXISTS (select *
        FROM ims_hris.service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082')))
        
        UNION
        SELECT sth_staff_id
        FROM ims_hris.staff_training_head,ims_hris.training_head
        WHERE sth_status = 'APPROVE'
        AND sth_training_refid = th_ref_id 
        AND sth_participant_role = 'D'
        AND sth_training_refid = '$refid'
        AND th_internal_external = 'EXTERNAL_AGENCY'
        AND (((sth_service_book IS NULL OR sth_service_book = 'N') AND NOT EXISTS (SELECT *
        FROM service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082')) 
        
        OR (sth_service_book = 'Y' 
        AND NOT EXISTS (SELECT *
        FROM ims_hris.service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082')))
        
        UNION
        SELECT std_staff_id 
        FROM ims_hris.staff_training_detl,ims_hris.training_head,ims_hris.staff_training_head
        WHERE coalesce(std_attend,'N') IN ('Y','A')
        AND std_training_refid = '$refid'
        AND sth_status = 'APPROVE' 
        AND sth_participant_role = 'D'
        AND sth_training_refid = std_training_refid
        AND sth_training_refid = th_ref_id
        AND sth_staff_id = std_staff_id
        AND to_char(th_date_from,'YYYY') >= '2016'
        AND th_internal_external <> 'EXTERNAL_AGENCY'
        AND (((sth_service_book IS NULL OR sth_service_book = 'N') AND NOT EXISTS (SELECT *
        FROM ims_hris.service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082')) 
        
        OR (sth_service_book = 'Y' 
        AND NOT EXISTS (SELECT *
        FROM ims_hris.service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082')))
        
        UNION
        SELECT ste_staff_id
        FROM ims_hris.staff_training_exam,ims_hris.training_head,ims_hris.staff_training_head
        WHERE th_ref_id = ste_ref_id
        AND sth_training_refid = ste_ref_id
        AND sth_staff_id = ste_staff_id
        AND sth_status = 'APPROVE'
        AND ste_ref_id = '$refid'
        AND ste_status IN ('LULUS','PENGECUALIAN')
        AND (((sth_service_book IS NULL OR sth_service_book = 'N') AND NOT EXISTS (SELECT *
        FROM ims_hris.service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082')) 
        
        OR (sth_service_book = 'Y' 
        AND NOT EXISTS (SELECT *
        FROM ims_hris.service_book_head 
        WHERE sbh_subsystem_refid = sth_training_refid
        AND sbh_staff_id = sth_staff_id
        AND sbh_subsystem_id = 'COURSE'
        AND sbh_trans_group = 'SG0011'
        AND sbh_trans_code = 'S0082'))))
        
        AND sth_training_refid = '$refid'
        AND sth_participant_role = 'D'
        ) 
        LEFT JOIN ims_hris.service_book_head ON sbh_staff_id = sth_staff_id AND sbh_subsystem_refid = sth_training_refid";

        $q = $this->db->query($query);
        return $q->result_case('UPPER');
    }

    // VERIFY TRAINING SERVICE BOOK -postgres
    public function verifySvcBook($refid)
    {
        $this->db->select("tt_service_book");
        $this->db->from("ims_hris.training_type, ims_hris.training_head");
        $this->db->where("tt_code = th_type");
        $this->db->where("th_ref_id", $refid);
       
        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET TRAINING DETAIL FOR SERVICE BOOK -postgres
    public function getTrDetlSvcBook($refid)
    {
        $this->db->select("th_ref_id, 
                            th_training_title,
                            th_training_venue,
                            to_char(th_date_from, 'DD-mm-YYYY') AS th_datefr,
                            to_char(th_date_to, 'DD-mm-YYYY') AS th_dateto, 
                            th_organizer_name");
        $this->db->from('ims_hris.training_head');
        $this->db->where("th_ref_id", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET JOB CODE -postgres
    public function getJobCode($sid)
    {
        $this->db->select("sm_job_code");
        $this->db->from('ims_hris.staff_main');
        $this->db->where("sm_staff_id", $sid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET PENSION STATUS -postgres
    public function getPenisionSts($sid)
    {
        $this->db->select("ss_pension_status");
        $this->db->from('ims_hris.staff_service');
        $this->db->where("ss_staff_id", $sid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // ADD TO SERVICE BOOK -postgres
    public function addServiceBook($refid, $sid, $sb_remark, $jobCode, $pensionSts, $tr_date_from = null, $tr_date_to = null)
    {
        $curUser = $this->staff_id;
        $curDate = "date_trunc('second', NOW()::timestamp)";
        //$sbhRefid = "to_char(sysdate,'YYYY') || '-' || ltrim(to_char(SERVICE_DETL_SEQ.nextval,'00000000'))";
        $this->db->select('TO_CHAR(CURRENT_DATE,\'YYYY\')||\'-\'||LTRIM(TO_CHAR(nextval(\'ims_hris.service_detl_seq\'),\'00000000\')) AS "REF_ID"');

        

        $data = array(
            "sbh_staff_id" => $sid,
            "sbh_trans_group" => 'SG0011',
            "sbh_trans_code" => 'S0082',
            "sbh_subsystem_id" => 'COURSE',
            "sbh_subsystem_refid" => $refid,
            "sbh_service_code" => $jobCode,
            // "SBH_START_DATE" => $tr_date_from,
            // "SBH_END_DATE" => $tr_date_to,
            "sbh_remark" => $sb_remark,
            "sbh_status" => 'APPRV',
            "sbh_enter_by" => $curUser,
            //"SBH_ENTER_DATE" => $curDate,
            "sbh_display" => 'Y',
            "sbh_pension_status" => $pensionSts
        );

        if(!empty($tr_date_from)) {
            $date = "to_date('".$tr_date_from."','DD-mm-YYYY')";
            $this->db->set("sbh_start_date", $date, false);
        }

        if(!empty($tr_date_to)) {
            $date = "to_date('".$tr_date_to."','DD-mm-YYYY')";
            $this->db->set("sbh_end_date", $date, false);
        }

        $this->db->set("sbh_ref_id", $sbhRefid, false);
        $this->db->set("sbh_enter_date", $curDate, false);

        return $this->db->insert("ims_hris.service_book_head", $data);
    }

    // UPDATE STH_SERVICE_BOOK -postgres
    public function updSthSvcBook($refid, $sid)
    {
        $data = array(
            "sth_service_book" => 'Y'
        );

        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $sid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    /*===========================================================
       Staff Training Evaluation - ATF104
    =============================================================*/

    // GET STAFF LIST -postgres
    public function getStaffListEvaluation($refid, $staffID = null)
    {
        $this->db->select("sth.sth_staff_id stf_id, sm1.sm_staff_name stf_n1, 
                            sm1.sm_dept_code stf_dept1, to_char(sth.sth_submit_date, 'dd/mm/yyyy') sth_sb_dt, 
                            sth.sth_evaluator_id eva_id, sm2.sm_staff_name stf_n2, 
                            CASE 
                            WHEN sth.sth_hod_evaluation = 'Y' THEN 'Yes'
                            WHEN sth.sth_hod_evaluation = 'N' THEN 'No'
                            END
                            she_desc, sth.sth_hod_evaluation she,
                            to_char(sth.sth_evaluation_date, 'DD/MM/YYYY') sed,
                            sth.sth_evaluator_id ||' - '|| sm2.sm_staff_name eva_id_name");
        $this->db->from("ims_hris.staff_training_head sth");
        $this->db->join("ims_hris.staff_main sm1", "sth.sth_staff_id = sm1.sm_staff_id", "LEFT");
        $this->db->join("ims_hris.staff_main sm2", "sth.sth_evaluator_id = sm2.sm_staff_id", "LEFT");
        
        $this->db->where("sth_staff_id IN (SELECT 
                            sm_staff_id FROM ims_hris.staff_main,ims_hris.staff_status 
                            WHERE sm_staff_status = '01' AND 
                            sm_staff_status = ss_status_code AND ss_status_sts = 'ACTIVE')");
        $this->db->where("sth_staff_id IN 
                            (
                            SELECT sth_staff_id 
                            FROM ims_hris.staff_training_head,ims_hris.training_head 
                            WHERE sth_training_refid = th_ref_id 
                            AND sth_status = 'APPROVE' 
                            AND to_char(th_date_from,'yyyy') < '2016'
                            AND th_internal_external <> 'EXTERNAL_AGENCY'
                            AND sth_training_refid = '$refid'
                            UNION
                            select STH_STAFF_ID 
                            from ims_hris.staff_training_head,ims_hris.training_head 
                            where sth_training_refid = th_ref_id 
                            and sth_status = 'APPROVE' 
                            and th_internal_external = 'EXTERNAL_AGENCY'
                            and sth_training_refid = '$refid'
                            union
                            SELECT sth_staff_id 
                            FROM ims_hris.staff_training_head,ims_hris.training_head,ims_hris.staff_training_detl
                            WHERE sth_training_refid = th_ref_id 
                            AND sth_status = 'APPROVE' 
                            AND to_char(th_date_from,'yyyy') >= '2016'
                            AND th_internal_external <> 'EXTERNAL_AGENCY'
                            AND sth_training_refid = std_training_refid
                            AND sth_staff_id = std_staff_id
                            AND sth_training_refid = '$refid'
                            AND std_attend IN ('Y','A')
                            )");
        
        if(!empty($staffID)) {
            $this->db->where("sth.sth_training_refid", $refid);
            $this->db->where("sth.sth_staff_id", $staffID);

            $q = $this->db->get();
            return $q->row_case('UPPER');
        } else {
            $this->db->where("sth.sth_training_refid", $refid);
            $this->db->order_by("sth.sth_staff_id");

            $q = $this->db->get();
            return $q->result_case('UPPER');
        }
    }

    // GET EVALUATION START DATE -postgres
    public function getStartDate() {
        $this->db->select("to_char(to_date(hp_parm_desc,'DD/MM/YYYY'), 'YYYYMMDD') AS hp_parm_desc");
        $this->db->from("ims_hris.hradmin_parms");
        $this->db->where("hp_parm_code = 'TRAINING_EVALUATION_STARTED'");
        $this->db->where("hp_parm_no = 1");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET EVALUATOR LIST -postgres
    public function getEvaluatorList() {
        $this->db->select("sm_staff_id, sm_staff_name, sm_staff_id||' - '||sm_staff_name stf_id_name");
        $this->db->from("ims_hris.staff_main");
        $this->db->where("sm_staff_status in (select ss_status_code from staff_status where ss_status_sts='ACTIVE')");
        $this->db->where("sm_staff_id LIKE 'K%'");
        $this->db->order_by("sm_staff_name");


        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // SAVE UPDATE APPLICANT DETAILS -postgres
    public function saveUpdateStaffEvaDetails($form, $refid, $stfID)
    {
        $data = array(
            "sth_evaluator_id" => $form['evaluator_id'],
            "sth_hod_evaluation" => $form['evaluation_status'],
        );

        if(!empty($form['submit_date'])){
            $submit_date = "to_date('".$form['submit_date']."', 'DD/MM/YYYY')";
            $this->db->set("sth_submit_date", $submit_date, false);
        }

        if(!empty($form['evaluation_date'])){
            $eva_date = "to_date('".$form['evaluation_date']."', 'DD/MM/YYYY')";
            $this->db->set("sth_evaluation_date", $eva_date, false);
        }

        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $stfID);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // GET PROCESS EVALUATOR ID -postgres
    public function getProcessEvaluatorID($refid, $fid) {
        $this->db->select("coalesce(sth_verify_by,coalesce(sth_recommend_by,coalesce(lsh_recommend_by,lsh_approve_by))) AS proc_eva_id");
        $this->db->from("ims_hris.staff_training_head, ims_hris.leave_staff_hierarchy");
        $this->db->where("sth_staff_id", $fid);
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("lsh_staff_id = sth_staff_id");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // VERIFY STH_EVALUATOR_ID -postgres
    public function verifyEvaID($refid, $fid) {
        $this->db->select("sth_evaluator_id");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->where("sth_staff_id", $fid);
        $this->db->where("sth_training_refid", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // UPDATE EVA ID -postgres
    public function updateEvaID($refid, $fid, $procEvaID)
    {
        $data = array(
            "sth_evaluator_id" => $procEvaID,
        );

        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $fid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

     /*===========================================================
       Staff Evaluator Send Memo - ATF121
    =============================================================*/
    // GET STAFF LIST -hold postgres
    public function getStaffListSendMemo($refid, $staffID = null)
    {
        $this->db->distinct();
        $this->db->select("STH.STH_STAFF_ID STF_ID, SM1.SM_STAFF_NAME STF_N1, 
                            SM1.SM_DEPT_CODE STF_DEPT1, to_char(STH.STH_SUBMIT_DATE, 'DD/MM/YYYY') STH_SB_DT, 
                            STH.STH_EVALUATOR_ID EVA_ID, SM2.SM_STAFF_NAME STF_N2,
                            STH.STH_EVALUATOR_ID ||' - '|| SM2.SM_STAFF_NAME EVA_ID_NAME, 
                            MAX(TEH.TEH_SEQ) OVER (PARTITION BY STH.STH_STAFF_ID) SND_MEM");
        $this->db->from("STAFF_TRAINING_HEAD STH");
        $this->db->join("STAFF_MAIN SM1", "STH.STH_STAFF_ID = SM1.SM_STAFF_ID", "LEFT");
        $this->db->join("STAFF_MAIN SM2", "STH.STH_EVALUATOR_ID = SM2.SM_STAFF_ID", "LEFT");
        $this->db->join("TRAINING_EVALUATION_HIS TEH", "TEH.TEH_TRAINING_REFID = STH.STH_TRAINING_REFID AND TEH.TEH_SEND_TO = STH.STH_EVALUATOR_ID and TEH.TEH_CC = STH.STH_STAFF_ID", "LEFT");
        $this->db->where("STH_STAFF_ID IN 
                            (
                            SELECT STH_STAFF_ID
                            FROM STAFF_TRAINING_HEAD,TRAINING_HEAD 
                            WHERE STH_STATUS = 'APPROVE' 
                            AND STH_SUBMIT_DATE IS NOT NULL 
                            AND STH_EVALUATOR_ID IS NOT NULL
                            AND (STH_HOD_EVALUATION IS NULL OR STH_HOD_EVALUATION = 'N')
                            AND STH_EVALUATION_DATE IS NULL
                            AND STH_TRAINING_REFID = '$refid'
                            AND STH_TRAINING_REFID = TH_REF_ID 
                            AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
                            AND TO_CHAR(TH_DATE_FROM,'YYYY') < '2016'
                            UNION
                            SELECT STH_STAFF_ID
                            FROM STAFF_TRAINING_HEAD,TRAINING_HEAD 
                            WHERE STH_STATUS = 'APPROVE' 
                            AND STH_SUBMIT_DATE IS NOT NULL 
                            AND STH_EVALUATOR_ID IS NOT NULL
                            AND (STH_HOD_EVALUATION IS NULL OR STH_HOD_EVALUATION = 'N')
                            AND STH_EVALUATION_DATE IS NULL
                            AND STH_TRAINING_REFID = '$refid'
                            AND STH_TRAINING_REFID = TH_REF_ID 
                            AND TH_INTERNAL_EXTERNAL = 'EXTERNAL_AGENCY'
                            UNION
                            SELECT STH_STAFF_ID
                            FROM STAFF_TRAINING_HEAD,TRAINING_HEAD,STAFF_TRAINING_DETL 
                            WHERE STH_STATUS = 'APPROVE' 
                            AND STH_SUBMIT_DATE IS NOT NULL 
                            AND STH_EVALUATOR_ID IS NOT NULL
                            AND (STH_HOD_EVALUATION IS NULL OR STH_HOD_EVALUATION = 'N')
                            AND STH_EVALUATION_DATE IS NULL
                            AND STH_TRAINING_REFID = '$refid'
                            AND STH_TRAINING_REFID = TH_REF_ID 
                            AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
                            AND TO_CHAR(TH_DATE_FROM,'YYYY') >= '2016'
                            AND STH_TRAINING_REFID = STD_TRAINING_REFID
                            AND STH_STAFF_ID = STD_STAFF_ID
                            AND STD_ATTEND IN ('Y','A')
                            )");
        
        if(!empty($staffID)) {
            $this->db->where("STH.STH_TRAINING_REFID", $refid);
            $this->db->where("STH.STH_STAFF_ID", $staffID);

            $q = $this->db->get();
            return $q->row();
        } else {
            $this->db->where("STH.STH_TRAINING_REFID", $refid);
            $this->db->order_by("STH.STH_EVALUATOR_ID");

            $q = $this->db->get();
            return $q->result();
        }
    }

    // GET STAFF LIST -postgres
    public function getTrainEvaMemoDetl()
    {
        $this->db->select("tem_title, tem_content, tem_send_by");
        $this->db->from("ims_hris.training_evaluation_memo");
        $this->db->where("tem_module = 'TRAINING_EVALUATION'");
        $this->db->where("coalesce(tem_status,'N') = 'Y'");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET STAFF INFO -postgres
    public function getStaffInfo($staffID) {
        $this->db->select("*");
        $this->db->from("ims_hris.staff_main");
        $this->db->where("sm_staff_id", $staffID);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET MAX SEQ -postgres
    public function getMaxSeq() {
        $this->db->select("hp_parm_desc::numeric hp_parm_desc");
        $this->db->from("ims_hris.hradmin_parms");
        $this->db->where("hp_parm_code = 'TRAINING_EVALUATION_MEMO'");
        $this->db->where("hp_parm_no = 1");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // SEND MEMO -hold postgres
    public function createMemo($from, $sendTO, $memoTitle, $memoContent) 
    {
		$sql = oci_parse($this->db->conn_id, "begin create_memo(:bind1,:bind2,null,:bind3,:bind4); end;");
		oci_bind_by_name($sql, ":bind1", $from);				//IN
		oci_bind_by_name($sql, ":bind2", $sendTO);				//IN
		oci_bind_by_name($sql, ":bind3", $memoTitle, 255);		//IN
		oci_bind_by_name($sql, ":bind4", $memoContent, 4000);	//IN
		$q = oci_execute($sql, OCI_DEFAULT); 
		
        if ($q === FALSE) {
			return 0;
		}
		
		return 1;	
    } 

    // GET STAFF INFO -postgres
    public function getVenue($refid) {
        //$this->db->select("distinct DECODE(TH_TRAINING_VENUE,'',TH_TRAINING_VENUE||', ')||CM_COUNTRY_DESC TH_VENUE");
        $this->db->select("distinct 
        (CASE 
        WHEN th_training_venue = '' OR th_training_venue is null
        then th_training_venue||', '
        end)||cm_country_desc
        th_venue");
        $this->db->from("ims_hris.training_head, ims_hris.country_main");
        $this->db->where("th_training_country = cm_country_code");
        $this->db->where("th_ref_id", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET COUNT MEMO SEQ -postgres
    public function getCountMemoSeq($refid, $sendTO, $memoCC) {
        $this->db->select("count(1)::numeric+1 AS memoc");
        $this->db->from("ims_hris.training_evaluation_his");
        $this->db->where("teh_training_refid", $refid);
        $this->db->where("teh_send_to", $sendTO);
        $this->db->where("teh_cc", $memoCC);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // UPDATE EVA ID -postgres
    public function insertRefMemo($refid, $curMemoCount, $memoTitle, $defContent, $from, $sendTO, $memoCC)
    {
        $sendDate = "date_trunc('second', NOW()::timestamp)";
        $data = array(
            "teh_training_refid" => $refid,
            "teh_seq" => $curMemoCount,
            "teh_title" => $memoTitle,
            "teh_content" => $defContent,
            "teh_send_by" => $from,
            "teh_send_to" => $sendTO,
            "teh_cc" => $memoCC,
        );

        $this->db->set("teh_send_date", $sendDate, false);

        return $this->db->insert("ims_hris.training_evaluation_his", $data);
    }

    /*===========================================================
       Report for Training Evaluation - ATF166
    =============================================================*/

    // GET STAFF LIST DD -postgres
    public function getStaffListDD() {
        $this->db->select("sm_staff_id, sm_staff_name, sm_staff_id||' - '||sm_staff_name AS staff_id_name");
        $this->db->from("ims_hris.staff_main, ims_hris.staff_status");
        $this->db->where("sm_staff_status = ss_status_code");
        $this->db->where("sm_staff_type = 'STAFF'");
        $this->db->where("ss_status_sts = 'ACTIVE'");
        $this->db->order_by("sm_staff_name");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET COURSE DD EFF LIST -postgres
    public function getCourseListEff() {
        $this->db->select("th_ref_id, th_training_title, th_ref_id||' - '||th_training_title course_id_name,th_ref_id||' - '||th_training_title||'|'||to_char(th_date_from,'dd/mm/yyyy')||' - '||to_char(th_date_to,'dd/mm/yyyy') course_detl,
        to_char(th_date_from, 'dd/mm/yyyy')||' - '||to_char(th_date_to,'dd/mm/yyyy') th_date");
        $this->db->from("ims_hris.training_head, ims_hris.training_head_detl");
        $this->db->where("th_ref_id = thd_ref_id");
        $this->db->where("coalesce(thd_evaluation,'N') = 'Y'");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->order_by("th_ref_id");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET COURSE LIST REPORT TRAINING EVALUATION -postgres
    public function courseListRptTe($year) {
        $this->db->select("th_ref_id, th_training_title, th_ref_id||' - '||th_training_title th_id_name,
                            to_char(th_date_from, 'dd/mm/yyyy')||' - '||to_char(th_date_to,'dd/mm/yyyy') th_date, th_date_from");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("TO_CHAR(th_date_from,'YYYY') = coalesce($year,TO_CHAR(current_date,'YYYY'))");
        $this->db->where("th_ref_id IN (select tmh_training_refid from ims_hris.training_memo_history)");
        $this->db->order_by("th_date_from, th_training_title");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    /*===========================================================
       Training Secretariat Report - Manual Entry - ATF147
    =============================================================*/

    // GET COURSE LIST REPORT TRAINING EVALUATION -postgres
    public function getSubmittedReport($trCountRefid) {
        $this->db->select("*");
        $this->db->from("ims_hris.training_secretariat_report");
        $this->db->where("tsr_refid", $trCountRefid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET TOTAL ATTEND < 2016 -postgres
    public function getTotalAttend1($refid) {
        $this->db->select("th_max_participant as total_attend");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_internal_external <> 'EXTERNAL_AGENCY'");
        $this->db->where("TO_CHAR(th_date_from,'YYYY') < '2016'");
        $this->db->where("th_ref_id", $refid);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET TOTAL ATTEND >= 2016 -postgres
    public function getTotalAttend2($refid) {
        $this->db->select("count(sth_staff_id) total_attend");
        $this->db->from("ims_hris.staff_training_head, ims_hris.training_head");
        $this->db->where("sth_status = 'APPROVE'");
        $this->db->where("sth_training_refid = th_ref_id");
        $this->db->where("th_internal_external <> 'EXTERNAL_AGENCY'");
        $this->db->where("TO_CHAR(th_date_from,'YYYY') >= '2016'");
        $this->db->where("th_ref_id", $refid);
        $this->db->where("sth_participant_role = 'D'");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET ATTENDED PARTICIPANT -postgres
    public function getAttendParticipant($refid) {
        $this->db->select("count(sth_staff_id) attended");
        $this->db->from("(
                            select sth_staff_id
                            from ims_hris.staff_training_head,ims_hris.training_head 
                            WHERE sth_status = 'APPROVE' 
                            AND sth_training_refid = th_ref_id 
                            AND th_internal_external <> 'EXTERNAL_AGENCY'
                            AND TO_CHAR(th_date_from,'yyyy') < '2016'
                            AND sth_participant_role = 'D'
                            AND sth_training_refid = '$refid'
                            UNION
                            SELECT sth_staff_id
                            FROM ims_hris.staff_training_head,ims_hris.training_head,ims_hris.staff_training_detl 
                            WHERE sth_status = 'APPROVE' 
                            AND sth_training_refid = th_ref_id 
                            AND th_internal_external <> 'EXTERNAL_AGENCY'
                            AND TO_CHAR(th_date_from,'yyyy') >= '2016'
                            AND sth_training_refid = std_training_refid
                            AND sth_staff_id = std_staff_id
                            AND sth_participant_role = 'D'
                            AND std_attend IN ('Y','A')
                            AND sth_training_refid = '$refid'
                        ) AS A");

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // SECRETARIAT ON DUTY -postgres
    public function getScreOnDuty($refid) {
        $this->db->select("tsi_seq, tsi_incharge, sm_staff_name, to_char(tsi_incharge_date, 'dd/mm/yyyy') as incharge_date");
        $this->db->from("ims_hris.training_secret_incharge");
        $this->db->join("ims_hris.staff_main", "sm_staff_id = tsi_incharge");
        $this->db->where("tsi_refid", $refid);
        $this->db->order_by("tsi_seq");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // INSERT SECRET REPORT -postgres
    public function insertSecretReport($form, $refid)
    {
        $data = array(
            "tsr_refid" => $refid,
            "tsr_participant_discipline" => $form['discipline'],
            "tsr_participant_time" => $form['participant_time'],
            "tsr_participant_remark" => $form['participant_remark'],

            "tsr_cafe_name" => $form['cafe_name'],
            "tsr_cafe_time" => $form['food_drink_time'],
            "tsr_cafe_quality" => $form['food_drink_quality'],
            "tsr_cafe_remark" => $form['cafe_remark'],

            "tsr_room_chair" => $form['chair'],
            "tsr_room_desk" => $form['table'],
            "tsr_room_aircond" => $form['aircond'],
            "tsr_room_lamp" => $form['lamps'],
            "tsr_room_remark" => $form['training_room_remark'],

            "tsr_equipment_computer" => $form['laptop_desktop'],
            "tsr_equipment_laserpointer" => $form['laser_pointer'],
            "tsr_equipment_lcd" => $form['lcd_pointer'],
            "tsr_equipment_pasystem" => $form['pa_system'],
            "tsr_equipment_remark" => $form['equipment_remark'],

            "tsr_overall_report" => $form['overall_remark']
        );

        //$this->db->set("TH_REF_ID", $refID, false);

        return $this->db->insert("ims_hris.training_secretariat_report", $data);
    }

    // UPDATE SECRET REPORT -postgres
    public function updateSecretReport($form, $refid)
    {
        $data = array(
            "tsr_participant_discipline" => $form['discipline'],
            "tsr_participant_time" => $form['participant_time'],
            "tsr_participant_remark" => $form['participant_remark'],

            "tsr_cafe_name" => $form['cafe_name'],
            "tsr_cafe_time" => $form['food_drink_time'],
            "tsr_cafe_quality" => $form['food_drink_quality'],
            "tsr_cafe_remark" => $form['cafe_remark'],

            "tsr_room_chair" => $form['chair'],
            "tsr_room_desk" => $form['table'],
            "tsr_room_aircond" => $form['aircond'],
            "tsr_room_lamp" => $form['lamps'],
            "tsr_room_remark" => $form['training_room_remark'],

            "tsr_equipment_computer" => $form['laptop_desktop'],
            "tsr_equipment_laserpointer" => $form['laser_pointer'],
            "tsr_equipment_lcd" => $form['lcd_pointer'],
            "tsr_equipment_pasystem" => $form['pa_system'],
            "tsr_equipment_remark" => $form['equipment_remark'],

            "tsr_overall_report" => $form['overall_remark']
        );

        $this->db->where("tsr_refid", $refid);

        return $this->db->update("ims_hris.training_secretariat_report", $data);
    }

    // GET STAFF DEPT -postgres
    public function getStaffDept($staff_id)
    {
        $this->db->select("sm_dept_code");
        $this->db->from("ims_hris.staff_main");
        $this->db->where("sm_staff_id", $staff_id);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // CHECK SECRETARIAT ON DUTY -postgres
    public function checkSecretDuty($refid, $secretariat_id) {
        $this->db->select("tsi_seq, tsi_incharge, to_char(tsi_incharge_date, 'dd/mm/yyyy') as incharge_date");
        $this->db->from("ims_hris.training_secret_incharge");
        $this->db->where("tsi_refid", $refid);
        $this->db->where("tsi_incharge", $secretariat_id);

        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // INSERT SECRET REPORT -postgres
    public function insertSecretDuty($form, $refid)
    {
        $tsi_seq = "(select case 
        when tsi_seq is null then 1
        when tsi_seq is not null then tsi_seq
        end as tsi_seq
        FROM(
        select max(tsi_seq)::numeric+1 as tsi_seq
        from ims_hris.training_secret_incharge
        where tsi_refid = '$refid') as A)";

        $curDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "tsi_refid" => $refid,
            "tsi_incharge" => $form['secretariat_id']
        );

        $this->db->set("tsi_seq", $tsi_seq, false);

        if(!empty($form['date'])){
            $date = "to_date('".$form['date']."', 'DD/MM/YYYY')";
            $this->db->set("tsi_incharge_date", $date, false);
        } else {
            $this->db->set("tsi_incharge_date", $curDate, false);
        }

        return $this->db->insert("ims_hris.training_secret_incharge", $data);
    }

    // DELETE SECRETARIAT INCHARGE -postgres
    public function deleteScrIncharge($refid, $seq, $scrId) {
        $this->db->where('tsi_refid', $refid);
        $this->db->where('tsi_seq', $seq);
        $this->db->where('tsi_incharge', $scrId);
        return $this->db->delete('ims_hris.training_secret_incharge');
    }

    /*===========================================================
       Training Application Report - ATF081
    =============================================================*/

    // GET DEPARTMENT LIST -postgres
    public function getDeptListAppRpt() {
        $this->db->select("dm_dept_code, dm_dept_desc, dm_dept_code ||' - '|| dm_dept_desc as dept_code_desc");
        $this->db->from('ims_hris.department_main');
		$this->db->where("dm_status = 'ACTIVE'");
		$this->db->where('dm_level <= 2');
        $this->db->order_by('dm_dept_desc');
        $q = $this->db->get();
		        
        return $q->result_case('UPPER');
    }

    // GET COURSE LIST REPORT I -postgres
    public function courseTitlei($year) {
        $this->db->select("th_ref_id, th_training_title, to_char(th_date_from,'dd/mm/yyyy')||' - '||to_char(th_date_to,'dd/mm/yyyy') th_date,
                            th_date_from");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("TO_CHAR(th_date_from,'YYYY') = coalesce('$year',TO_CHAR(current_date,'YYYY'))");
        $this->db->where("th_ref_id IN (select tmh_training_refid from ims_hris.training_memo_history)");
        
        $this->db->order_by("th_date_from, th_training_title");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET ORGANIZER LIST REPORT II -postgres
    public function getOrganizer() {
        $this->db->select("toh_org_code, toh_org_desc, toh_org_code||' - '||toh_org_desc as toh_code_desc,
                            toh_address, toh_postcode, toh_city, 
                            sm_state_desc, cm_country_desc");
        $this->db->from("ims_hris.training_organizer_head, ims_hris.state_main, ims_hris.country_main");
        $this->db->where("toh_state = sm_state_code and toh_country = cm_country_code");
        $this->db->order_by("2");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET SECTOR LIST REPORT II -postgres
    public function getSector() {
        $this->db->select("tsl_code, tsl_desc, tsl_code||' - '||tsl_desc tsl_code_desc");
        $this->db->from("ims_hris.training_sector_level");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }

    // GET COURSE LIST REPORT III -postgres
    public function courseTitleiii($year) {
        $this->db->select("th_ref_id, th_training_title, th_ref_id||' - '||th_training_title th_id_title, 
                            to_char(th_date_from,'dd/mm/yyyy')||' - '||to_char(th_date_to,'dd/mm/yyyy') th_date,
                            th_date_from");
        $this->db->from("ims_hris.training_head");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("TO_CHAR(th_date_from,'YYYY') = coalesce('$year',TO_CHAR(current_date,'YYYY'))");
        
        $this->db->order_by("th_date_from, th_training_title");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }


    // GET UNIT LIST REPORT VII -postgres
    public function getUnitVii($deptCode) {
        $this->db->select("dm_dept_code, dm_dept_code||' - '||dm_dept_desc dm_dept_desc");
        $this->db->from("ims_hris.department_main");
        $this->db->where("dm_level >= '3'");
        $this->db->where("dm_status = 'ACTIVE'");
        $this->db->where("dm_parent_dept_code", $deptCode);
        
        $this->db->order_by("2");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }
    
     //start update @17/02/2020
    //--------------------------------------------------------------------------
    // -postgres
    public function getTerasTrainingList($year,$month) {
        $this->db->select("th_ref_id, th_training_title, to_char(th_date_from,'dd/mm/yyyy') as date_from, to_char(th_date_to,'dd/mm/yyyy') as date_to,
            th_date_from,thd_coordinator||' - '||sm_staff_name as coor");
        $this->db->from("ims_hris.training_head");
        $this->db->join("ims_hris.tna_training_head","tth_ref_id = th_training_code");
        $this->db->join("ims_hris.training_head_detl","th_ref_id = thd_ref_id","LEFT");
        $this->db->join("ims_hris.staff_main","sm_staff_id = thd_coordinator","LEFT");
        $this->db->where("th_status = 'APPROVE'");
        $this->db->where("coalesce(tth_structured,'N') = 'Y'");
        $this->db->where("coalesce(tth_status,'INACTIVE') = 'ACTIVE'");
        $this->db->where("tth_ref_id LIKE 'TRS%'");
        //$this->db->where("('$month' IS NULL OR ('$month' IS NOT NULL AND TO_CHAR(TH_DATE_FROM,'MM') = '$month'))");
        //$this->db->where("('$year' IS NULL OR ('$year' IS NOT NULL AND TO_CHAR(TH_DATE_FROM,'YYYY') = '$year'))");
        //$this->db->where("TO_CHAR(TH_DATE_FROM,'YYYY') = NVL('$year',TO_CHAR(SYSDATE,'YYYY'))"); 
        //$this->db->where("TO_CHAR(TH_DATE_FROM,'MM') = NVL('$month',TO_CHAR(SYSDATE,'MM'))"); 
        
        if(!empty($month) && !empty($year)) {
            $this->db->where("((coalesce(to_char(th_date_from,'MM/YYYY'),'') = '$month'||'/'||'$year'))");
        } elseif(!empty($month)) {
            $this->db->where("((coalesce(to_char(th_date_from,'MM'),'') = '$month'))");
        } elseif(!empty($year)) {
            $this->db->where("((coalesce(to_char(th_date_from,'YYYY'),'') = '$year'))");
        }
        
        $this->db->order_by("th_date_from");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }//getTerasTrainingList
    
    // -postgres
    public function getTerasList() {
        $this->db->select("tth_ref_id, tth_training_title");
        $this->db->from("ims_hris.tna_training_head");
        $this->db->where("COALESCE(tth_structured,'N') = 'Y'");
        $this->db->where("COALESCE(tth_status,'INACTIVE') = 'ACTIVE'");
        $this->db->where("tth_ref_id LIKE 'TRS%'");

        $this->db->order_by("tth_ref_id");

        $q = $this->db->get();
        return $q->result_case('UPPER');
    }//getTerasList
    
    //end update @17/02/2020 -----------------------------------------------------------
    
    // for ATF008Q -postgres
    public function getQueryDetailInfo($tableName, $fieldName, $fieldWhereName, $fieldWhereValue){
        $this->db->select($fieldName);
        $this->db->where($fieldWhereName, $fieldWhereValue);
        $query = $this->db->get($tableName);
        if ($query->num_rows() > 0) {
            if ($query->row()->$fieldName == '' or $query->row()->$fieldName == null){
                return '-';
            }else{
                return $query->row()->$fieldName;
            }
        }
		
        return '-';
    } // getQueryDetailInfo
    // for ATF008Q 
    
    // for ATF008Q
    // GET ORGANIZER DETAILS QUERY SCREEN -postgres
    public function getOrganizerName_Query($organizerCode = null) {
        $this->db->select("toh_org_code, toh_org_desc, toh_org_code ||' - '|| toh_org_desc as toh_org_code_desc, toh_address, toh_postcode, toh_city, sm_state_desc, cm_country_desc");
        $this->db->from('ims_hris.training_organizer_head, ims_hris.state_main, ims_hris.country_main');
        $this->db->where("toh_state=sm_state_code");
        $this->db->where("toh_country=cm_country_code");
        //$this->db->where("NVL(TOH_EXTERNAL_AGENCY,'N') <> 'Y'");

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
        
        
    }//getOrganizerName_Query
    // for ATF008Q
    
}
