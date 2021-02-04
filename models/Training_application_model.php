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
        $this->db->where("ltrim(ss_salary_grade, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')::numeric <= '$gradeTo'");

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
            "th_training_state" => $form['state'],
            "th_total_hours" => $form['total_hours'],
            "th_internal_external" => $form['internal_external'],
            "th_sponsor" => $form['sponsor'],
            "th_offer" => $form['offer'],
            "th_max_participant" => $form['participants'],
            "th_open" => $form['online_application'],
            "th_competency_code" => $form['competency_code'],

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
        } else {
            $this->db->set("th_evaluation_date_from", '', true);
        }

        if(!empty($form['evaluation_period_to'])){
            $evaluation_period_to = "to_date('".$form['evaluation_period_to']."', 'DD/MM/YYYY')";
            $this->db->set("th_evaluation_date_to", $evaluation_period_to, false);
        } else {
            $this->db->set("th_evaluation_date_to", '', true);
        }

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
            "ch_mark" => $form['mark'],
            "ch_report_submission" => $form['report_submission'],
            "ch_compulsory" => $form['compulsory']
        );

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
            "th_training_state" => $form['state'],
            "th_total_hours" => $form['total_hours'],
            "th_internal_external" => $form['internal_external'],
            "th_sponsor" => $form['sponsor'],
            "th_offer" => $form['offer'],
            "th_max_participant" => $form['participants'],
            "th_open" => $form['online_application'],
            "th_competency_code" => $form['competency_code'],

            // organizer info
            "th_organizer_level" => $form['organizer_level'],
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
            $this->db->where("to_char(to_date(th_date_from, 'DD/MM/YYYY'), 'YYYYMMDD')::numeric < to_char(to_date(current_date, 'DD/MM/YYYY'), 'YYYYMMDD')::numeric");
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
		$this->db->where('DM_LEVEL:numeric <= 2');
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
        $this->db->order_by("get_staff_dept(sth_staff_id)");
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

    // GET STAFF EMAIL DISTINCT
    public function getStaffMainDis($refid, $staffID, $resend = null)
    {
        if($resend == 1) {
            $query = "SELECT DISTINCT SM_EMAIL_ADDR, NVL(STH_VERIFY_BY,STH_RECOMMEND_BY) STAFF, SM_STAFF_NAME
            FROM STAFF_TRAINING_HEAD, STAFF_MAIN
            WHERE STH_TRAINING_REFID = '$refid'
            AND STH_STATUS = 'APPROVE'
            AND NVL(STH_VERIFY_BY, STH_RECOMMEND_BY) = SM_STAFF_ID
            AND STH_STAFF_ID = '$staffID'
            UNION
            SELECT DISTINCT SM_EMAIL_ADDR, NVL(LEAVE_STAFF_HIERARCHY.LSH_RECOMMEND_BY, LSH_APPROVE_BY) STAFF, SM_STAFF_NAME
            FROM LEAVE_STAFF_HIERARCHY, STAFF_MAIN, STAFF_TRAINING_HEAD
            WHERE LEAVE_STAFF_HIERARCHY.LSH_STAFF_ID = STH_STAFF_ID
            AND STH_TRAINING_REFID = '$refid'
            AND STH_STATUS = 'APPROVE'
            AND STH_VERIFY_BY IS NULL 
            AND STH_RECOMMEND_BY IS NULL
            AND NVL(LEAVE_STAFF_HIERARCHY.LSH_RECOMMEND_BY, LSH_APPROVE_BY) = SM_STAFF_ID
            AND STH_STAFF_ID = '$staffID'";
        } else {
            $query = "SELECT DISTINCT SM_EMAIL_ADDR, NVL(STH_VERIFY_BY,STH_RECOMMEND_BY) STAFF, SM_STAFF_NAME
            FROM STAFF_TRAINING_HEAD, STAFF_MAIN
            WHERE STH_TRAINING_REFID = '$refid'
            AND STH_STATUS = 'RECOMMEND'
            AND NVL(STH_VERIFY_BY, STH_RECOMMEND_BY) = SM_STAFF_ID
            AND STH_STAFF_ID = '$staffID'
            UNION
            SELECT DISTINCT SM_EMAIL_ADDR, NVL(LEAVE_STAFF_HIERARCHY.LSH_RECOMMEND_BY, LSH_APPROVE_BY) STAFF, SM_STAFF_NAME
            FROM LEAVE_STAFF_HIERARCHY, STAFF_MAIN, STAFF_TRAINING_HEAD
            WHERE LEAVE_STAFF_HIERARCHY.LSH_STAFF_ID = STH_STAFF_ID
            AND STH_TRAINING_REFID = '$refid'
            AND STH_STATUS = 'RECOMMEND'
            AND STH_VERIFY_BY IS NULL 
            AND STH_RECOMMEND_BY IS NULL
            AND NVL(LEAVE_STAFF_HIERARCHY.LSH_RECOMMEND_BY, LSH_APPROVE_BY) = SM_STAFF_ID
            AND STH_STAFF_ID = '$staffID'";
        }
        

        $q = $this->db->query($query);
        return $q->row();
    }

    // GET TRAINING COORDINATOR
    public function getTrCoor($refid)
    {
        $query = "SELECT TM_TITLE_DESC||' '||SM_STAFF_NAME AS STAFF_NAME, THD_COORDINATOR_TELNO
        FROM TRAINING_HEAD, TRAINING_HEAD_DETL, STAFF_MAIN, TITLE_MAIN
        WHERE TH_REF_ID = '$refid'
        AND TH_STATUS = 'APPROVE'
        AND TH_REF_ID = THD_REF_ID
        AND THD_COORDINATOR = SM_STAFF_ID
        AND SM_STAS_TITLE = TM_TITLE_CODE(+)";

        $q = $this->db->query($query);
        return $q->row();
    }

    // VERIFY TRAINING
    public function verifyTraining($refid, $staffID)
    {
        $this->db->select("*");
        $this->db->from('STAFF_TRAINING_DETL');
        $this->db->where("STD_TRAINING_REFID", $refid);
        $this->db->where("STD_STAFF_ID", $staffID);

        $q = $this->db->get();
        return $q->row();
    }

    /*_____________________
        INSERT PROCESS
    _______________________*/

    // INSERT SENT EMAIL STATUS
    public function insertEmailSts($refid, $staffID)
    {
        $curDate = 'SYSDATE';

        $data = array(
            "STD_TRAINING_REFID" => $refid,
            "STD_STAFF_ID" => $staffID,
            "STD_SENDMEMO" => 'Y'
        );

        $this->db->set("STD_SENDMEMO_DATE", $curDate, false);

        return $this->db->insert("STAFF_TRAINING_DETL", $data);
    }

    /*_____________________
        UPDATE PROCESS
    _______________________*/

    // UPDATE STAFF TRAINING HEAD - APPROVE APPLICANT
    public function apprOrReApp($refid, $staffID, $eveluatorID, $remark, $sts)
    {
        if($sts == 1) {
            $sthSts = 'APPROVE';
        } elseif($sts == 0) {
            $sthSts = 'REJECT';
        } 

        $curUsr = $this->staff_id;
        $curDate = 'SYSDATE';

        $data = array(
            "STH_STATUS" => $sthSts,
            "STH_APPROVE_BY" => $curUsr,
            "STH_EVALUATOR_ID" => $eveluatorID,
            "STH_REMARK" => $remark
        );

        $this->db->set("STH_APPROVE_DATE", $curDate, false);

        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID", $staffID);

        return $this->db->update("STAFF_TRAINING_HEAD", $data);
    }

    // UPDATE SENT EMAIL STATUS
    public function updateEmailSts($refid, $staffID)
    {
        $curDate = 'SYSDATE';

        $data = array(
            "STD_SENDMEMO" => 'Y'
        );

        $this->db->set("STD_SENDMEMO_DATE", $curDate, false);

        $this->db->where("STD_TRAINING_REFID", $refid);
        $this->db->where("STD_STAFF_ID", $staffID);

        return $this->db->update("STAFF_TRAINING_DETL", $data);
    }


    /*_____________________
        SEND EMAIL
    _______________________*/

    public function sendEmail($memo_from, $staff_app_email, $email_cc, $msg_title, $msg_content) {
		if (empty($memo_from)) {
			$memo_from = 'bsm.latihan@upsi.edu.my';
		}
		if (empty($email_cc)) {
			$email_cc = null;
		}
		
		// execute create_memo procedure
		$sql = 'begin utl_mail.send(
					sender=>?,
					recipients=>?,
					cc=>?,
					subject=>?,
					message=>?,
					mime_type=>\'text/html\'		
				); end;';
        $q = $this->db->query($sql, array($memo_from, $staff_app_email, $email_cc, $msg_title, $msg_content));

		if ($q === FALSE) {
			// return 0 if fail to execute create_memo
			return 0;
		}
		
		return 1;
    }


    /*===========================================================
       ASSIGN TRAINING TO STAFF
    =============================================================*/

    // GET ALL STAFF FROM TRAINING
    public function getAssignStaff($refid, $staffId = null)
    {
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_DEPT_CODE, STH_PARTICIPANT_ROLE, 
                            TPR_DESC, STH_STATUS, STH_STAFF_TRAINING_BENEFIT, 
                            STH_DEPT_TRAINING_BENEFIT, STH_REMARK, SM_STAFF_ID||' - '||SM_STAFF_NAME AS SM_ID_NAME");
        $this->db->from('STAFF_TRAINING_HEAD');
        $this->db->join("STAFF_MAIN", "STH_STAFF_ID = SM_STAFF_ID");
        $this->db->join("TRAINING_PARTICIPANT_ROLE", "TPR_CODE = STH_PARTICIPANT_ROLE", "LEFT");
        $this->db->where("STH_TRAINING_REFID", $refid);
        if(empty($staffId)) {
            $this->db->order_by("STH_STAFF_ID, UPPER(GET_STAFF_DEPT(STH_STAFF_ID)), STH_STATUS, UPPER(GET_STAFF_NAME(STH_STAFF_ID))");

            $q = $this->db->get();
            return $q->result();
        } 
        elseif(!empty($staffId)) {
            $this->db->where("STH_STAFF_ID", $staffId);

            $q = $this->db->get();
            return $q->row();
        }
    } 

    // FILTER STAFF DROPDOWN LIST
    public function getStaffList($refid, $deptCode = null)
    {
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_STAFF_ID||' - '||SM_STAFF_NAME AS STAFF_ID_NAME, SM_DEPT_CODE");
        $this->db->from('STAFF_MAIN, STAFF_SERVICE, STAFF_STATUS');
        $this->db->where("SS_STAFF_ID = SM_STAFF_ID");
        $this->db->where("SM_STAFF_STATUS = SS_STATUS_CODE");
        $this->db->where("SS_JOB_STATUS IN ('01','03','08','09','10','02','11')");
        $this->db->where("SS_STATUS_STS = 'ACTIVE'");
        if(!empty($deptCode)) {
            $this->db->where("SM_DEPT_CODE", $deptCode);
        }
        $this->db->where("SM_STAFF_ID NOT IN
        (SELECT STH_STAFF_ID FROM STAFF_TRAINING_HEAD WHERE STH_TRAINING_REFID = '$refid')");
        $this->db->order_by("SM_STAFF_NAME");

        $q = $this->db->get();
        return $q->result();
    } 

    // GET PARTICIPANT ROLE
    public function getRoleList()
    {
        $this->db->select("*");
        $this->db->from('TRAINING_PARTICIPANT_ROLE');
        $this->db->order_by("TPR_CODE");

        $q = $this->db->get();
        return $q->result();
    }

    // GET PARTICIPANT STATUS
    public function getPstatusList()
    {
        $this->db->select("*");
        $this->db->from('TRAINING_PARTICIPANT_STATUS');
        $this->db->order_by("TPS_CODE");

        $q = $this->db->get();
        return $q->result();
    }

    // CHECK STAFF IN STAFF_TRAINING_COST_MAIN
    public function checkStaffTr($refid, $staffId)
    {
        $this->db->select("*");
        $this->db->from('STAFF_TRAINING_COST_MAIN');
        $this->db->where("STCM_TRAINING_REFID", $refid);
        $this->db->where("STCM_STAFF_ID", $staffId);

        $q = $this->db->get();
        return $q->row();
    }
    
    /*_____________________
        INSERT PROCESS
    _______________________*/

    // INSERT ASSIGNED STAFF
    public function saveAssignedStaff($form, $refid)
    {
        $curDate = 'SYSDATE';

        $data = array(
            "STH_STAFF_ID" => $form['staff_id'],
            "STH_TRAINING_REFID" => $refid,
            "STH_PARTICIPANT_ROLE" => $form['role'],
            "STH_STAFF_TRAINING_BENEFIT" => $form['training_benefit_staff'],
            "STH_DEPT_TRAINING_BENEFIT" => $form['training_benefit_department'],
            "STH_STATUS" => $form['status'],
            "STH_REMARK" => $form['remark'],
        );

        $this->db->set("STH_APPLY_DATE", $curDate, false);

        return $this->db->insert("STAFF_TRAINING_HEAD", $data);
    }

    public function saveAssignedStaffBatch($refid, $sid, $role, $sts)
    {
        $curDate = 'SYSDATE';

        $data = array(
            "STH_STAFF_ID" => $sid,
            "STH_TRAINING_REFID" => $refid,
            "STH_PARTICIPANT_ROLE" => $role,
            "STH_STATUS" => $sts
        );

        $this->db->set("STH_APPLY_DATE", $curDate, false);

        return $this->db->insert("STAFF_TRAINING_HEAD", $data);
    }

    /*_____________________
        UPDATE PROCESS
    _______________________*/

    // SAVE UPDATE ASSIGNED STAFF
    public function saveUpdAssigned($form, $refid, $staffid)
    {
        $data = array(
            "STH_PARTICIPANT_ROLE" => $form['role'],
            "STH_STAFF_TRAINING_BENEFIT" => $form['training_benefit_staff'],
            "STH_DEPT_TRAINING_BENEFIT" => $form['training_benefit_department'],
            "STH_STATUS" => $form['status'],
            "STH_REMARK" => $form['remark'],
        );

        $this->db->where('STH_TRAINING_REFID', $refid);
        $this->db->where('STH_STAFF_ID', $staffid);

        return $this->db->update("STAFF_TRAINING_HEAD", $data);
    }

    /*_____________________
        DELETE PROCESS
    _______________________*/

    // DELETE ASSIGNED STAFF
    public function deleteAssignedStaff($refid, $staffId) {
        $this->db->where('STH_STAFF_ID', $staffId);
        $this->db->where('STH_TRAINING_REFID', $refid);

        return $this->db->delete('STAFF_TRAINING_HEAD');
    }


    /*===========================================================
       TRAINING QURIES - ATF008
    =============================================================*/

    // GET TRAINING STATUS LIST
    public function getTrainingStsList()
    {
        $this->db->select("TH_STATUS");
        $this->db->from('TRAINING_HEAD');
        $this->db->group_by("TH_STATUS");

        $q = $this->db->get();
        return $q->result();
    }

    // GET TRAINING COST
    public function getTrainingCost($tsRefID)
    {
        $this->db->select("TC_COST_CODE, TCT_DESC, TC_AMOUNT, TC_REMARK");
        $this->db->from("TRAINING_COST, TRAINING_COST_TYPE");
        $this->db->where("TC_COST_CODE = TCT_CODE");
        $this->db->where("TC_TRAINING_REFID", $tsRefID);

        $q = $this->db->get();
        return $q->result();
    }

    /*===========================================================
       APPROVE TRAINING SETUP - ATF027
    =============================================================*/

    // STAFF TRAINING RECORDS
    public function getStaffTrainingRecords($refid)
    {
        $this->db->select("COUNT(1) AS CC");
        $this->db->from("STAFF_TRAINING_HEAD");
        $this->db->where("STH_TRAINING_REFID", $refid);

        $q = $this->db->get();
        return $q->row();
    }

    // APPROVE TRAINING
    public function approveTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = 'SYSDATE';

        $data = array(
            "TH_STATUS" => 'APPROVE',
            "TH_APPROVE_BY" => $currentUsr
        );

        $this->db->set("TH_APPROVE_DATE", $curDate, false);

        $this->db->where("TH_REF_ID", $refid);

        return $this->db->update("TRAINING_HEAD", $data);
    } 
    
    // APPROVE TRAINING
    public function postponeTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = 'SYSDATE';

        $data = array(
            "TH_STATUS" => 'POSTPONE',
            "TH_APPROVE_BY" => $currentUsr
        );

        $this->db->set("TH_APPROVE_DATE", $curDate, false);

        $this->db->where("TH_REF_ID", $refid);

        return $this->db->update("TRAINING_HEAD", $data);
    }  
    
    // REJECT TRAINING
    public function rejectTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = 'SYSDATE';

        $data = array(
            "TH_STATUS" => 'REJECT',
            "TH_APPROVE_BY" => $currentUsr
        );

        $this->db->set("TH_APPROVE_DATE", $curDate, false);

        $this->db->where("TH_REF_ID", $refid);

        return $this->db->update("TRAINING_HEAD", $data);
    }

    public function rejectStaffTraining($refid)
    {
        // $currentUsr = $this->staff_id;
        // $curDate = 'SYSDATE';

        $data = array(
            "STH_STATUS" => 'REJECT'
        );

        //$this->db->set("TH_APPROVE_DATE", $curDate, false);

        $this->db->where("STH_TRAINING_REFID", $refid);

        return $this->db->update("STAFF_TRAINING_HEAD", $data);
    }

    // AMEND TRAINING
    public function amendTrainingSetup($refid)
    {
        $currentUsr = $this->staff_id;
        $curDate = 'SYSDATE';

        $data = array(
            "TH_STATUS" => 'ENTRY',
            "TH_APPROVE_BY" => $currentUsr
        );

        $this->db->set("TH_APPROVE_DATE", $curDate, false);

        $this->db->where("TH_REF_ID", $refid);

        return $this->db->update("TRAINING_HEAD", $data);
    }

    /*===========================================================
       EDIT APPROVE TRAINING SETUP - ATF044
    =============================================================*/

    // GET URL
    public function getEcommUrl()
    {
        $this->db->select("HP_PARM_DESC");
        $this->db->from("HRADMIN_PARMS");
        $this->db->where("HP_PARM_CODE = 'ECOMMUNITY_STAFF_URL'");

        $q = $this->db->get();
        return $q->row();
    }

    /*===========================================================
       QUERY STAFF TRAINING - ATF041
    =============================================================*/

    // GET STAFF LIST
    public function getStaffTrainingList($curUsrDept = null, $stfID = null)
    {
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, DM_DEPT_DESC, SS_SERVICE_DESC");
        $this->db->from("STAFF_MAIN");
        $this->db->join("SERVICE_SCHEME", "SS_SERVICE_CODE = SM_JOB_CODE", "LEFT");
        $this->db->join("DEPARTMENT_MAIN", "DM_DEPT_CODE = SM_DEPT_CODE", "LEFT");
        if(!empty($curUsrDept)) {
            $this->db->where("SM_DEPT_CODE = '$curUsrDept'");
        }
        if(!empty($stfID)) {
            $this->db->where("UPPER(SM_STAFF_ID) = UPPER('$stfID') OR UPPER(SM_STAFF_NAME) LIKE UPPER('%$stfID%')");
        }
        $this->db->where("SM_STAFF_STATUS IN (SELECT SS_STATUS_CODE FROM STAFF_STATUS WHERE SS_STATUS_STS='ACTIVE')");
        $this->db->where("SM_STAFF_TYPE <> 'SYSTEM'");
        $this->db->order_by("SM_STAFF_NAME");

        $q = $this->db->get();
        return $q->result();
    }

    // GET STAFF LIST
    public function trainingListStaff($stfID)
    {
        $this->db->select("STH_TRAINING_REFID, TH_TRAINING_TITLE, TPS_DESC, TPR_DESC, STH_STATUS, STH_REMARK,
                            CASE
                            WHEN STH_COMPLETE = 'Y' THEN 'YES'
                            ELSE 'NO'
                            END AS STHCOMPLETE");
        $this->db->from("STAFF_TRAINING_HEAD STH");
        $this->db->join("TRAINING_HEAD TH", "STH.STH_TRAINING_REFID = TH.TH_REF_ID", "LEFT");
        $this->db->join("TRAINING_PARTICIPANT_STATUS TPS", "STH.STH_PARTICIPANT_STATUS = TPS.TPS_CODE", "LEFT");
        $this->db->join("TRAINING_PARTICIPANT_ROLE TPR", "STH.STH_PARTICIPANT_ROLE = TPR.TPR_CODE", "LEFT");
        $this->db->where("STH_STAFF_ID", $stfID);
        $this->db->order_by("STH_TRAINING_REFID");

        $q = $this->db->get();
        return $q->result();
    }

    // GET STAFF LIST
    public function applicationDetail($refid, $stfID)
    {
        $this->db->select("STH_TRAINING_REFID ||' - '|| TH_TRAINING_TITLE TRAINING_ID, to_char(STH_APPLY_DATE, 'DD/MM/YYYY') AS APPL_DATE, 
                            CASE
                            WHEN STD_TRAINING_CALENDAR = 'Y' THEN 'YES'
                            ELSE 'NO'
                            END AS TRAINING_CALENDAR,
                            CASE
                            WHEN STD_WORK_RELATED = 'Y' THEN 'YES'
                            ELSE 'NO'
                            END AS WORK_RELATED, 
                            STH_STAFF_TRAINING_BENEFIT, STH_VERIFY_BY ||' - '|| SM1.SM_STAFF_NAME AS VER_BY,
                            to_char(STH_VERIFY_DATE, 'DD/MM/YYYY') AS VER_DATE, STH_DEPT_TRAINING_BENEFIT, STH_RECOMMEND_BY ||' - '|| SM2.SM_STAFF_NAME AS REC_BY, 
                            to_char(STH_RECOMMEND_DATE, 'DD/MM/YYYY') AS REC_DATE, STH_RECOMMENDER_REASON, STH_REMARK, STH_APPROVE_BY ||' - '|| SM3.SM_STAFF_NAME AS APPR_BY,
                            to_char(STH_APPROVE_DATE, 'DD/MM/YYYY') AS APPR_DATE, to_char(STD_MPE_DATE, 'DD/MM/YYYY') AS MPE_DATE, 
                            STH_APPROVE_REASON, STD_CANCEL_BY ||' - '|| SM4.SM_STAFF_NAME AS CANC_BY, to_char(STD_CANCEL_DATE, 'DD/MM/YYYY') AS CANC_DATE, 
                            STD_CANCEL_REASON");
        $this->db->from("STAFF_TRAINING_HEAD");
        $this->db->join("TRAINING_HEAD", "STH_TRAINING_REFID = TH_REF_ID", "LEFT");
        $this->db->join("STAFF_TRAINING_DETL", "STH_TRAINING_REFID = STD_TRAINING_REFID AND STH_STAFF_ID = STD_STAFF_ID", "LEFT");
        $this->db->join("STAFF_MAIN SM1", "SM1.SM_STAFF_ID = STH_VERIFY_BY", "LEFT");
        $this->db->join("STAFF_MAIN SM2", "SM2.SM_STAFF_ID = STH_RECOMMEND_BY", "LEFT");
        $this->db->join("STAFF_MAIN SM3", "SM3.SM_STAFF_ID = STH_APPROVE_BY", "LEFT");
        $this->db->join("STAFF_MAIN SM4", "SM4.SM_STAFF_ID = STD_CANCEL_BY", "LEFT");
        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID", $stfID);

        $q = $this->db->get();
        return $q->row();
    }

    /*===========================================================
       Confirmation Attend Training - ATF148
    =============================================================*/

    // GET STAFF LIST
    public function getStaffTrainingApplicationConf($refid, $stfID = null)
    {
        $this->db->select("STH_TRAINING_REFID, SM_STAFF_ID, SM_STAFF_NAME, SM_DEPT_CODE, 
                            TPR_DESC, to_char(STH_APPLY_DATE, 'DD/MM/YYYY') AS STHAPPDATE,
                            CASE
                            WHEN STD_ATTEND = 'A' THEN 'Yes (Auto)'
                            WHEN STD_ATTEND = 'Y' THEN 'Yes'
                            WHEN STD_ATTEND = 'N' THEN 'No'
                            END AS STD_ATTEND, 
                            CASE
                            WHEN STD_SENDMEMO = 'Y' THEN 'Yes'
                            WHEN STD_SENDMEMO = 'Y' THEN 'No'
                            END AS STD_SENDMEMO,
                            CASE
                            WHEN STH_HOD_EVALUATION = 'Y' THEN 'Yes'
                            WHEN STH_HOD_EVALUATION = 'N' THEN 'No'
                            END AS STH_HOD_EVALUATION,
                            CASE
                            WHEN STD_TRANSPORTATION = 'OWN_SHARING' THEN 'Owned  / Shared Transport'
                            WHEN STD_TRANSPORTATION = 'UPSI' THEN 'UPSI'
                            END AS STD_TRANSPORTATION, 
                            to_char(STD_ATTEND_DATE, 'DD/MM/YYYY') 
                            STD_ATTEND_DATE, STD_ATTEND_REMARK, STD_ATTEND AS STD_ATTEND2,
                            STD_TRANSPORTATION AS STD_TRANSPORTATION2");
        $this->db->from("STAFF_TRAINING_HEAD");
        $this->db->join("STAFF_MAIN", "STH_STAFF_ID = SM_STAFF_ID", "LEFT");
        $this->db->join("STAFF_SERVICE", "STH_STAFF_ID = SS_STAFF_ID", "LEFT");
        $this->db->join("TRAINING_PARTICIPANT_ROLE", "STH_PARTICIPANT_ROLE = TPR_CODE", "LEFT");
        $this->db->join("STAFF_TRAINING_DETL", "STH_TRAINING_REFID = STD_TRAINING_REFID AND STH_STAFF_ID = STD_STAFF_ID", "LEFT");

        if(!empty($stfID)) {
            $this->db->where("STH_STAFF_ID", $stfID);
            $this->db->where("STH_TRAINING_REFID", $refid);
            $this->db->where("STH_STATUS = 'APPROVE'"); 

            $q = $this->db->get();
            return $q->row();
        } else {
            $this->db->where("STH_TRAINING_REFID", $refid);
            $this->db->where("STH_STATUS = 'APPROVE'");
            $this->db->order_by("STH_STAFF_ID");

            $q = $this->db->get();
            return $q->result();
        }
    }

    // CHECK TRAINING EXTERNAL
    public function getTrainingExternal($refid)
    {
        $this->db->select("TH_INTERNAL_EXTERNAL, TH_TRAINING_CODE");
        $this->db->from("TRAINING_HEAD");
        $this->db->where("TH_REF_ID", $refid);

        $q = $this->db->get();
        return $q->row();
    }

    // AUTO ATTEND CONFIRMATION UPDATE
    public function autoAttendConfirmation($refid, $staffID, $transport)
    {
        $curDate = 'SYSDATE';

        $data = array(
            "STD_ATTEND" => 'A',
            "STD_TRANSPORTATION" => $transport
        );

        $this->db->set("STD_ATTEND_DATE", $curDate, false);

        $this->db->where("STD_TRAINING_REFID", $refid);
        $this->db->where("STD_STAFF_ID", $staffID);

        return $this->db->update("STAFF_TRAINING_DETL", $data);
    }

    // AUTO ATTEND CONFIRMATION INSERT
    public function autoAttendConfirmationIns($refid, $staffID, $transport)
    {
        $curDate = 'SYSDATE';

        $data = array(
            "STD_TRAINING_REFID" => $refid,
            "STD_STAFF_ID" => $staffID,
            "STD_ATTEND" => 'A',
            "STD_TRANSPORTATION" => $transport
        );

        $this->db->set("STD_ATTEND_DATE", $curDate, false);

        return $this->db->insert("STAFF_TRAINING_DETL", $data);
    }

    // COUNT TRAINING REQUIREMENT
    public function getTrainingRequirement($trCode, $staffID)
    {
        $query = "SELECT COUNT(1) AS R_COUNT
        FROM TRAINING_REQUIREMENT_MAIN,TRAINING_REQUIREMENT_DETL
        WHERE TRM_CODE = TRD_ID
        AND TRM_SETUP_CODE IN (SELECT TRS_CODE
        FROM TRAINING_REQUIREMENT_SETUP 
        WHERE TRS_REMARK IS NOT NULL
        AND TRS_DATE_TO IS NULL)
        AND TRM_STAFF_ID = '$staffID'
        AND TRD_TRAINING_REFID = '$trCode'
        AND TRD_STATUS <> 'APPROVE'";

        $q = $this->db->query($query);
        return $q->row();
    }

    // UPDATE TRAINING REQUIREMENT DETAIL
    public function updTrainingRequirementDetl($trCode, $staffID)
    {
        $currStaff = $this->staff_id;

        $query = "UPDATE TRAINING_REQUIREMENT_DETL
        SET TRD_STATUS = 'APPROVE',
        TRD_UPDATE_BY = '$currStaff',
        TRD_UPDATE_DATE = SYSDATE
        WHERE EXISTS  
        (SELECT TRM_CODE 
        FROM TRAINING_REQUIREMENT_MAIN
        WHERE TRM_CODE = TRD_ID
        AND TRM_STAFF_ID = '$staffID'
        AND TRM_SETUP_CODE IN 
            (SELECT TRS_CODE
            FROM TRAINING_REQUIREMENT_SETUP 
            WHERE TRS_REMARK IS NOT NULL
            AND TRS_DATE_TO IS NULL)
            ) 
        AND TRD_TRAINING_REFID = '$trCode'
        AND TRD_STATUS <> 'APPROVE'";

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

    // GET REMARK LIST
    public function getRemarkList()
    {
        $this->db->select("*");
        $this->db->from("TRAINING_REMARK_SETUP");
        $this->db->where("TRS_MODULE = 'APPLICATION'");
        $this->db->order_by("TRS_SEQ");

        $q = $this->db->get();
        return $q->result();
    }

    // SAVE UPDATE APPLICANT DETAILS
    public function saveUpdateApplicantDetails($form, $refid, $stfID)
    {
        $data = array(
            "STD_ATTEND" => $form['attendance_confirmation'],
            "STD_TRANSPORTATION" => $form['transportation'],
            "STD_ATTEND_REMARK" => $form['absent_remark']
        );

        if(!empty($form['confirm_date'])){
            $confirm_date = "to_date('".$form['confirm_date']."', 'DD/MM/YYYY')";
            $this->db->set("STD_ATTEND_DATE", $confirm_date, false);
        }

        $this->db->where("STD_TRAINING_REFID", $refid);
        $this->db->where("STD_STAFF_ID", $stfID);

        return $this->db->update("STAFF_TRAINING_DETL", $data);
    }

    // SAVE INSERT APPLICANT DETAILS
    public function saveInsertApplicantDetails($form, $refid, $stfID)
    {
        $data = array(
            "STD_TRAINING_REFID" => $refid,
            "STD_STAFF_ID" => $stfID,
            "STD_ATTEND" => $form['attendance_confirmation'],
            "STD_TRANSPORTATION" => $form['transportation'],
            "STD_ATTEND_REMARK" => $form['absent_remark']
        );

        if(!empty($form['confirm_date'])){
            $confirm_date = "to_date('".$form['confirm_date']."', 'DD/MM/YYYY')";
            $this->db->set("STD_ATTEND_DATE", $confirm_date, false);
        }

        return $this->db->insert("STAFF_TRAINING_DETL", $data);
    }

    // APPLICANT ATTENDANCE SUMMARY
    public function getCountAttendSum($refid, $att)
    {

        $this->db->select("COUNT(1) COUNT_ATTEND");
        $this->db->from("STAFF_TRAINING_HEAD");
        if($att == 0 || $att == 1 || $att == 2) {
            $this->db->join("STAFF_TRAINING_DETL", "STH_TRAINING_REFID = STD_TRAINING_REFID AND STH_STAFF_ID = STD_STAFF_ID", "LEFT");
        } 
        if($att == 0) {
            $this->db->where("STD_ATTEND IN ('Y','A')");
        }
        if($att == 1) {
            $this->db->where("STD_ATTEND = 'N'");
        }
        if($att == 2) {
            //$this->db->join("STAFF_TRAINING_DETL", "STH_TRAINING_REFID = STD_TRAINING_REFID AND STH_STAFF_ID = STD_STAFF_ID");
            $this->db->where("STD_ATTEND IS NULL");
        }
        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_PARTICIPANT_ROLE = 'D'");
        $this->db->where("STH_STATUS = 'APPROVE'");
        

        $q = $this->db->get();
        return $q->row();
    }

    // GET SEND DATE LIST
    public function getSendDate($refid)
    {
        $this->db->select("DISTINCT to_char(STD_SENDMEMO_DATE,'DD/MM/YYYY') SEND_DATE");
        $this->db->from("STAFF_TRAINING_DETL");
        $this->db->where("STD_TRAINING_REFID", $refid);
        $this->db->where("STD_SENDMEMO_DATE IS NOT NULL");
       
        $q = $this->db->get();
        return $q->result();
    }

    // GET STAFF LIST (SERVICE BOOK)
    public function getStaffListSvcBook($refid) {
        $query = "SELECT STH_STAFF_ID, SM_STAFF_NAME, SM_DEPT_CODE, TPR_DESC, STH_STATUS, STH_SERVICE_BOOK, SBH_REF_ID FROM (SELECT *
        FROM STAFF_TRAINING_HEAD 
        JOIN STAFF_MAIN ON STH_STAFF_ID = SM_STAFF_ID
        JOIN TRAINING_PARTICIPANT_ROLE ON STH_PARTICIPANT_ROLE = TPR_CODE 
        WHERE STH_STAFF_ID IN
        (
        SELECT STH_STAFF_ID
        FROM STAFF_TRAINING_HEAD,TRAINING_HEAD
        WHERE STH_STATUS = 'APPROVE'
        AND STH_TRAINING_REFID = TH_REF_ID 
        AND STH_PARTICIPANT_ROLE = 'D'
        AND STH_TRAINING_REFID = '$refid'
        AND to_char(TH_DATE_FROM,'YYYY') < '2016'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND (((STH_SERVICE_BOOK IS NULL OR STH_SERVICE_BOOK = 'N') AND NOT EXISTS (SELECT *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082')) 
        
        OR (STH_SERVICE_BOOK = 'Y' 
        AND NOT EXISTS (select *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = sth_training_refid
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082')))
        
        UNION
        SELECT STH_STAFF_ID
        FROM STAFF_TRAINING_HEAD,TRAINING_HEAD
        WHERE STH_STATUS = 'APPROVE'
        AND STH_TRAINING_REFID = TH_REF_ID 
        AND STH_PARTICIPANT_ROLE = 'D'
        AND STH_TRAINING_REFID = '$refid'
        AND TH_INTERNAL_EXTERNAL = 'EXTERNAL_AGENCY'
        AND (((STH_SERVICE_BOOK IS NULL OR STH_SERVICE_BOOK = 'N') AND NOT EXISTS (SELECT *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082')) 
        
        OR (STH_SERVICE_BOOK = 'Y' 
        AND NOT EXISTS (SELECT *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082')))
        
        UNION
        SELECT STD_STAFF_ID 
        FROM STAFF_TRAINING_DETL,TRAINING_HEAD,STAFF_TRAINING_HEAD
        WHERE NVL(STD_ATTEND,'N') IN ('Y','A')
        AND STD_TRAINING_REFID = '$refid'
        AND STH_STATUS = 'APPROVE' 
        AND STH_PARTICIPANT_ROLE = 'D'
        AND STH_TRAINING_REFID = STD_TRAINING_REFID
        AND STH_TRAINING_REFID = TH_REF_ID
        AND STH_STAFF_ID = STD_STAFF_ID
        AND to_char(TH_DATE_FROM,'YYYY') >= '2016'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND (((STH_SERVICE_BOOK IS NULL OR STH_SERVICE_BOOK = 'N') AND NOT EXISTS (SELECT *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082')) 
        
        OR (STH_SERVICE_BOOK = 'Y' 
        AND NOT EXISTS (SELECT *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082')))
        
        UNION
        SELECT STE_STAFF_ID
        FROM STAFF_TRAINING_EXAM,TRAINING_HEAD,STAFF_TRAINING_HEAD
        WHERE TH_REF_ID = STE_REF_ID
        AND STH_TRAINING_REFID = STE_REF_ID
        AND STH_STAFF_ID = STE_STAFF_ID
        AND STH_STATUS = 'APPROVE'
        AND STE_REF_ID = '$refid'
        AND STE_STATUS IN ('LULUS','PENGECUALIAN')
        AND (((STH_SERVICE_BOOK IS NULL OR STH_SERVICE_BOOK = 'N') AND NOT EXISTS (SELECT *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082')) 
        
        OR (STH_SERVICE_BOOK = 'Y' 
        AND NOT EXISTS (SELECT *
        FROM SERVICE_BOOK_HEAD 
        WHERE SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID
        AND SBH_STAFF_ID = STH_STAFF_ID
        AND SBH_SUBSYSTEM_ID = 'COURSE'
        AND SBH_TRANS_GROUP = 'SG0011'
        AND SBH_TRANS_CODE = 'S0082'))))
        
        AND STH_TRAINING_REFID = '$refid'
        AND STH_PARTICIPANT_ROLE = 'D'
        ) 
        LEFT JOIN SERVICE_BOOK_HEAD ON SBH_STAFF_ID = STH_STAFF_ID AND SBH_SUBSYSTEM_REFID = STH_TRAINING_REFID";

        $q = $this->db->query($query);
        return $q->result();
    }

    // VERIFY TRAINING SERVICE BOOK
    public function verifySvcBook($refid)
    {
        $this->db->select("TT_SERVICE_BOOK");
        $this->db->from("TRAINING_TYPE, TRAINING_HEAD");
        $this->db->where("TT_CODE = TH_TYPE");
        $this->db->where("TH_REF_ID", $refid);
       
        $q = $this->db->get();
        return $q->row();
    }

    // GET TRAINING DETAIL FOR SERVICE BOOK
    public function getTrDetlSvcBook($refid)
    {
        $this->db->select("TH_REF_ID, 
                            TH_TRAINING_TITLE,
                            TH_TRAINING_VENUE,
                            to_char(TH_DATE_FROM, 'DD-mm-YYYY') AS TH_DATEFR,
                            to_char(TH_DATE_TO, 'DD-mm-YYYY') AS TH_DATETO, 
                            TH_ORGANIZER_NAME");
        $this->db->from('TRAINING_HEAD');
        $this->db->where("TH_REF_ID", $refid);

        $q = $this->db->get();
        return $q->row();
    }

    // GET JOB CODE
    public function getJobCode($sid)
    {
        $this->db->select("SM_JOB_CODE");
        $this->db->from('STAFF_MAIN');
        $this->db->where("SM_STAFF_ID", $sid);

        $q = $this->db->get();
        return $q->row();
    }

    // GET PENSION STATUS
    public function getPenisionSts($sid)
    {
        $this->db->select("SS_PENSION_STATUS");
        $this->db->from('STAFF_SERVICE');
        $this->db->where("SS_STAFF_ID", $sid);

        $q = $this->db->get();
        return $q->row();
    }

    // ADD TO SERVICE BOOK
    public function addServiceBook($refid, $sid, $sb_remark, $jobCode, $pensionSts, $tr_date_from = null, $tr_date_to = null)
    {
        $curUser = $this->staff_id;
        $curDate = 'SYSDATE';
        $sbhRefid = "to_char(sysdate,'YYYY') || '-' || ltrim(to_char(SERVICE_DETL_SEQ.nextval,'00000000'))";
        

        $data = array(
            "SBH_STAFF_ID" => $sid,
            "SBH_TRANS_GROUP" => 'SG0011',
            "SBH_TRANS_CODE" => 'S0082',
            "SBH_SUBSYSTEM_ID" => 'COURSE',
            "SBH_SUBSYSTEM_REFID" => $refid,
            "SBH_SERVICE_CODE" => $jobCode,
            // "SBH_START_DATE" => $tr_date_from,
            // "SBH_END_DATE" => $tr_date_to,
            "SBH_REMARK" => $sb_remark,
            "SBH_STATUS" => 'APPRV',
            "SBH_ENTER_BY" => $curUser,
            //"SBH_ENTER_DATE" => $curDate,
            "SBH_DISPLAY" => 'Y',
            "SBH_PENSION_STATUS" => $pensionSts
        );

        if(!empty($tr_date_from)) {
            $date = "to_date('".$tr_date_from."','DD-mm-YYYY')";
            $this->db->set("SBH_START_DATE", $date, false);
        }

        if(!empty($tr_date_to)) {
            $date = "to_date('".$tr_date_to."','DD-mm-YYYY')";
            $this->db->set("SBH_END_DATE", $date, false);
        }

        $this->db->set("SBH_REF_ID", $sbhRefid, false);
        $this->db->set("SBH_ENTER_DATE", $curDate, false);

        return $this->db->insert("SERVICE_BOOK_HEAD", $data);
    }

    // UPDATE STH_SERVICE_BOOK
    public function updSthSvcBook($refid, $sid)
    {
        $data = array(
            "STH_SERVICE_BOOK" => 'Y'
        );

        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID", $sid);

        return $this->db->update("STAFF_TRAINING_HEAD", $data);
    }

    /*===========================================================
       Staff Training Evaluation - ATF104
    =============================================================*/

    // GET STAFF LIST
    public function getStaffListEvaluation($refid, $staffID = null)
    {
        $this->db->select("STH.STH_STAFF_ID STF_ID, SM1.SM_STAFF_NAME STF_N1, 
                            SM1.SM_DEPT_CODE STF_DEPT1, to_char(STH.STH_SUBMIT_DATE, 'DD/MM/YYYY') STH_SB_DT, 
                            STH.STH_EVALUATOR_ID EVA_ID, SM2.SM_STAFF_NAME STF_N2, 
                            CASE 
                            WHEN STH.STH_HOD_EVALUATION = 'Y' THEN 'Yes'
                            WHEN STH.STH_HOD_EVALUATION = 'N' THEN 'No'
                            END
                            SHE_DESC, STH.STH_HOD_EVALUATION SHE,
                            to_char(STH.STH_EVALUATION_DATE, 'DD/MM/YYYY') SED,
                            STH.STH_EVALUATOR_ID ||' - '|| SM2.SM_STAFF_NAME EVA_ID_NAME");
        $this->db->from("STAFF_TRAINING_HEAD STH");
        $this->db->join("STAFF_MAIN SM1", "STH.STH_STAFF_ID = SM1.SM_STAFF_ID", "LEFT");
        $this->db->join("STAFF_MAIN SM2", "STH.STH_EVALUATOR_ID = SM2.SM_STAFF_ID", "LEFT");
        
        $this->db->where("STH_STAFF_ID IN (SELECT 
                            SM_STAFF_ID FROM STAFF_MAIN,STAFF_STATUS 
                            WHERE SM_STAFF_STATUS = '01' AND 
                            SM_STAFF_STATUS = SS_STATUS_CODE AND SS_STATUS_STS = 'ACTIVE')");
        $this->db->where("STH_STAFF_ID IN 
                            (
                            SELECT STH_STAFF_ID 
                            FROM STAFF_TRAINING_HEAD,TRAINING_HEAD 
                            WHERE STH_TRAINING_REFID = TH_REF_ID 
                            AND STH_STATUS = 'APPROVE' 
                            AND to_char(TH_DATE_FROM,'yyyy') < '2016'
                            AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
                            AND STH_TRAINING_REFID = '$refid'
                            UNION
                            select STH_STAFF_ID 
                            from STAFF_TRAINING_HEAD,TRAINING_HEAD 
                            where STH_TRAINING_REFID = TH_REF_ID 
                            and STH_STATUS = 'APPROVE' 
                            and TH_INTERNAL_EXTERNAL = 'EXTERNAL_AGENCY'
                            and STH_TRAINING_REFID = '$refid'
                            union
                            SELECT STH_STAFF_ID 
                            FROM STAFF_TRAINING_HEAD,TRAINING_HEAD,STAFF_TRAINING_DETL
                            WHERE STH_TRAINING_REFID = TH_REF_ID 
                            AND STH_STATUS = 'APPROVE' 
                            AND to_char(TH_DATE_FROM,'yyyy') >= '2016'
                            AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
                            AND STH_TRAINING_REFID = STD_TRAINING_REFID
                            AND STH_STAFF_ID = STD_STAFF_ID
                            AND STH_TRAINING_REFID = '$refid'
                            AND STD_ATTEND IN ('Y','A')
                            )");
        
        if(!empty($staffID)) {
            $this->db->where("STH.STH_TRAINING_REFID", $refid);
            $this->db->where("STH.STH_STAFF_ID", $staffID);

            $q = $this->db->get();
            return $q->row();
        } else {
            $this->db->where("STH.STH_TRAINING_REFID", $refid);
            $this->db->order_by("STH.STH_STAFF_ID");

            $q = $this->db->get();
            return $q->result();
        }
    }

    // GET EVALUATION START DATE
    public function getStartDate() {
        $this->db->select("to_char(to_date(HP_PARM_DESC,'DD/MM/YYYY'), 'YYYYMMDD') AS HP_PARM_DESC");
        $this->db->from("HRADMIN_PARMS");
        $this->db->where("HP_PARM_CODE = 'TRAINING_EVALUATION_STARTED'");
        $this->db->where("HP_PARM_NO = 1");

        $q = $this->db->get();
        return $q->row();
    }

    // GET EVALUATOR LIST
    public function getEvaluatorList() {
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_STAFF_ID||' - '||SM_STAFF_NAME STF_ID_NAME");
        $this->db->from("STAFF_MAIN");
        $this->db->where("SM_STAFF_STATUS IN (SELECT SS_STATUS_CODE FROM STAFF_STATUS WHERE SS_STATUS_STS='ACTIVE')");
        $this->db->where("SM_STAFF_ID LIKE 'K%'");
        $this->db->order_by("SM_STAFF_NAME");


        $q = $this->db->get();
        return $q->result();
    }

    // SAVE UPDATE APPLICANT DETAILS
    public function saveUpdateStaffEvaDetails($form, $refid, $stfID)
    {
        $data = array(
            "STH_EVALUATOR_ID" => $form['evaluator_id'],
            "STH_HOD_EVALUATION" => $form['evaluation_status'],
        );

        if(!empty($form['submit_date'])){
            $submit_date = "to_date('".$form['submit_date']."', 'DD/MM/YYYY')";
            $this->db->set("STH_SUBMIT_DATE", $submit_date, false);
        }

        if(!empty($form['evaluation_date'])){
            $eva_date = "to_date('".$form['evaluation_date']."', 'DD/MM/YYYY')";
            $this->db->set("STH_EVALUATION_DATE", $eva_date, false);
        }

        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID", $stfID);

        return $this->db->update("STAFF_TRAINING_HEAD", $data);
    }

    // GET PROCESS EVALUATOR ID
    public function getProcessEvaluatorID($refid, $fid) {
        $this->db->select("NVL(STH_VERIFY_BY,NVL(STH_RECOMMEND_BY,NVL(LSH_RECOMMEND_BY,LSH_APPROVE_BY))) AS PROC_EVA_ID");
        $this->db->from("STAFF_TRAINING_HEAD, LEAVE_STAFF_HIERARCHY");
        $this->db->where("STH_STAFF_ID", $fid);
        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("LSH_STAFF_ID = STH_STAFF_ID");

        $q = $this->db->get();
        return $q->row();
    }

    // VERIFY STH_EVALUATOR_ID
    public function verifyEvaID($refid, $fid) {
        $this->db->select("STH_EVALUATOR_ID");
        $this->db->from("STAFF_TRAINING_HEAD");
        $this->db->where("STH_STAFF_ID", $fid);
        $this->db->where("STH_TRAINING_REFID", $refid);

        $q = $this->db->get();
        return $q->row();
    }

    // UPDATE EVA ID
    public function updateEvaID($refid, $fid, $procEvaID)
    {
        $data = array(
            "STH_EVALUATOR_ID" => $procEvaID,
        );

        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID", $fid);

        return $this->db->update("STAFF_TRAINING_HEAD", $data);
    }

     /*===========================================================
       Staff Evaluator Send Memo - ATF121
    =============================================================*/
    // GET STAFF LIST
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

    // GET STAFF LIST
    public function getTrainEvaMemoDetl()
    {
        $this->db->select("TEM_TITLE, TEM_CONTENT, TEM_SEND_BY");
        $this->db->from("TRAINING_EVALUATION_MEMO");
        $this->db->where("TEM_MODULE = 'TRAINING_EVALUATION'");
        $this->db->where("NVL(TEM_STATUS,'N') = 'Y'");

        $q = $this->db->get();
        return $q->row();
    }

    // GET STAFF INFO
    public function getStaffInfo($staffID) {
        $this->db->select("*");
        $this->db->from("STAFF_MAIN");
        $this->db->where("SM_STAFF_ID", $staffID);

        $q = $this->db->get();
        return $q->row();
    }

    // GET MAX SEQ
    public function getMaxSeq() {
        $this->db->select("TO_NUMBER(HP_PARM_DESC) HP_PARM_DESC");
        $this->db->from("HRADMIN_PARMS");
        $this->db->where("HP_PARM_CODE = 'TRAINING_EVALUATION_MEMO'");
        $this->db->where("HP_PARM_NO = 1");

        $q = $this->db->get();
        return $q->row();
    }

    // SEND MEMO
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

    // GET STAFF INFO
    public function getVenue($refid) {
        $this->db->select("DISTINCT DECODE(TH_TRAINING_VENUE,'',TH_TRAINING_VENUE||', ')||CM_COUNTRY_DESC TH_VENUE");
        $this->db->from("TRAINING_HEAD, COUNTRY_MAIN");
        $this->db->where("TH_TRAINING_COUNTRY = CM_COUNTRY_CODE");
        $this->db->where("TH_REF_ID", $refid);

        $q = $this->db->get();
        return $q->row();
    }

    // GET COUNT MEMO SEQ
    public function getCountMemoSeq($refid, $sendTO, $memoCC) {
        $this->db->select("COUNT(1)+1 AS MEMOC");
        $this->db->from("TRAINING_EVALUATION_HIS");
        $this->db->where("TEH_TRAINING_REFID", $refid);
        $this->db->where("TEH_SEND_TO", $sendTO);
        $this->db->where("TEH_CC", $memoCC);

        $q = $this->db->get();
        return $q->row();
    }

    // UPDATE EVA ID
    public function insertRefMemo($refid, $curMemoCount, $memoTitle, $defContent, $from, $sendTO, $memoCC)
    {
        $sendDate = 'SYSDATE';
        $data = array(
            "TEH_TRAINING_REFID" => $refid,
            "TEH_SEQ" => $curMemoCount,
            "TEH_TITLE" => $memoTitle,
            "TEH_CONTENT" => $defContent,
            "TEH_SEND_BY" => $from,
            "TEH_SEND_TO" => $sendTO,
            "TEH_CC" => $memoCC,
        );

        $this->db->set("TEH_SEND_DATE", $sendDate, false);

        return $this->db->insert("TRAINING_EVALUATION_HIS", $data);
    }

    /*===========================================================
       Report for Training Evaluation - ATF166
    =============================================================*/

    // GET STAFF LIST DD
    public function getStaffListDD() {
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_STAFF_ID||' - '||SM_STAFF_NAME AS STAFF_ID_NAME");
        $this->db->from("STAFF_MAIN, STAFF_STATUS");
        $this->db->where("SM_STAFF_STATUS = SS_STATUS_CODE");
        $this->db->where("SM_STAFF_TYPE = 'STAFF'");
        $this->db->where("SS_STATUS_STS = 'ACTIVE'");
        $this->db->order_by("SM_STAFF_NAME");

        $q = $this->db->get();
        return $q->result();
    }

    // GET COURSE DD EFF LIST
    public function getCourseListEff() {
        $this->db->select("TH_REF_ID, TH_TRAINING_TITLE, TH_REF_ID||' - '||TH_TRAINING_TITLE COURSE_ID_NAME,TH_REF_ID||' - '||TH_TRAINING_TITLE||'|'||TO_CHAR(TH_DATE_FROM,'DD/MM/YYYY')||' - '||TO_CHAR(TH_DATE_TO,'DD/MM/YYYY') COURSE_DETL,
                TO_CHAR(TH_DATE_FROM, 'DD/MM/YYYY')||' - '||TO_CHAR(TH_DATE_TO,'DD/MM/YYYY') TH_DATE");
        $this->db->from("TRAINING_HEAD, TRAINING_HEAD_DETL");
        $this->db->where("TH_REF_ID = THD_REF_ID");
        $this->db->where("NVL(THD_EVALUATION,'N') = 'Y'");
        $this->db->where("TH_STATUS = 'APPROVE'");
        $this->db->order_by("TH_REF_ID");

        $q = $this->db->get();
        return $q->result();
    }

    // GET COURSE LIST REPORT TRAINING EVALUATION
    public function courseListRptTe($year) {
        $this->db->select("TH_REF_ID, TH_TRAINING_TITLE, TH_REF_ID||' - '||TH_TRAINING_TITLE TH_ID_NAME,
                            TO_CHAR(TH_DATE_FROM, 'DD/MM/YYYY')||' - '||TO_CHAR(TH_DATE_TO,'DD/MM/YYYY') TH_DATE, TH_DATE_FROM");
        $this->db->from("TRAINING_HEAD");
        $this->db->where("TH_STATUS = 'APPROVE'");
        $this->db->where("TO_CHAR(TH_DATE_FROM,'YYYY') = NVL($year,TO_CHAR(SYSDATE,'YYYY'))");
        $this->db->where("TH_REF_ID IN (SELECT TMH_TRAINING_REFID FROM TRAINING_MEMO_HISTORY)");
        $this->db->order_by("TH_DATE_FROM, TH_TRAINING_TITLE");

        $q = $this->db->get();
        return $q->result();
    }

    /*===========================================================
       Training Secretariat Report - Manual Entry - ATF147
    =============================================================*/

    // GET COURSE LIST REPORT TRAINING EVALUATION
    public function getSubmittedReport($trCountRefid) {
        $this->db->select("*");
        $this->db->from("TRAINING_SECRETARIAT_REPORT");
        $this->db->where("TSR_REFID", $trCountRefid);

        $q = $this->db->get();
        return $q->row();
    }

    // GET TOTAL ATTEND < 2016
    public function getTotalAttend1($refid) {
        $this->db->select("TH_MAX_PARTICIPANT AS TOTAL_ATTEND");
        $this->db->from("TRAINING_HEAD");
        $this->db->where("TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'");
        $this->db->where("TO_CHAR(TH_DATE_FROM,'YYYY') < '2016'");
        $this->db->where("TH_REF_ID", $refid);

        $q = $this->db->get();
        return $q->row();
    }

    // GET TOTAL ATTEND >= 2016
    public function getTotalAttend2($refid) {
        $this->db->select("COUNT(STH_STAFF_ID) TOTAL_ATTEND");
        $this->db->from("STAFF_TRAINING_HEAD, TRAINING_HEAD");
        $this->db->where("STH_STATUS = 'APPROVE'");
        $this->db->where("STH_TRAINING_REFID = TH_REF_ID");
        $this->db->where("TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'");
        $this->db->where("TO_CHAR(TH_DATE_FROM,'YYYY') >= '2016'");
        $this->db->where("TH_REF_ID", $refid);
        $this->db->where("STH_PARTICIPANT_ROLE = 'D'");

        $q = $this->db->get();
        return $q->row();
    }

    // GET ATTENDED PARTICIPANT
    public function getAttendParticipant($refid) {
        $this->db->select("COUNT(STH_STAFF_ID) ATTENDED");
        $this->db->from("(
                            SELECT STH_STAFF_ID
                            FROM STAFF_TRAINING_HEAD,TRAINING_HEAD 
                            WHERE STH_STATUS = 'APPROVE' 
                            AND STH_TRAINING_REFID = TH_REF_ID 
                            AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
                            AND TO_CHAR(TH_DATE_FROM,'yyyy') < '2016'
                            AND STH_PARTICIPANT_ROLE = 'D'
                            AND STH_TRAINING_REFID = '$refid'
                            UNION
                            SELECT STH_STAFF_ID
                            FROM STAFF_TRAINING_HEAD,TRAINING_HEAD,STAFF_TRAINING_DETL 
                            WHERE STH_STATUS = 'APPROVE' 
                            AND STH_TRAINING_REFID = TH_REF_ID 
                            AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
                            AND TO_CHAR(TH_DATE_FROM,'yyyy') >= '2016'
                            AND STH_TRAINING_REFID = STD_TRAINING_REFID
                            AND STH_STAFF_ID = STD_STAFF_ID
                            AND STH_PARTICIPANT_ROLE = 'D'
                            AND STD_ATTEND IN ('Y','A')
                            AND STH_TRAINING_REFID = '$refid'
                        )");

        $q = $this->db->get();
        return $q->row();
    }

    // SECRETARIAT ON DUTY
    public function getScreOnDuty($refid) {
        $this->db->select("TSI_SEQ, TSI_INCHARGE, SM_STAFF_NAME, to_char(TSI_INCHARGE_DATE, 'DD/MM/YYYY') AS INCHARGE_DATE");
        $this->db->from("TRAINING_SECRET_INCHARGE");
        $this->db->join("STAFF_MAIN", "SM_STAFF_ID = TSI_INCHARGE");
        $this->db->where("TSI_REFID", $refid);
        $this->db->order_by("TSI_SEQ");

        $q = $this->db->get();
        return $q->result();
    }

    // INSERT SECRET REPORT
    public function insertSecretReport($form, $refid)
    {
        $data = array(
            "TSR_REFID" => $refid,
            "TSR_PARTICIPANT_DISCIPLINE" => $form['discipline'],
            "TSR_PARTICIPANT_TIME" => $form['participant_time'],
            "TSR_PARTICIPANT_REMARK" => $form['participant_remark'],

            "TSR_CAFE_NAME" => $form['cafe_name'],
            "TSR_CAFE_TIME" => $form['food_drink_time'],
            "TSR_CAFE_QUALITY" => $form['food_drink_quality'],
            "TSR_CAFE_REMARK" => $form['cafe_remark'],

            "TSR_ROOM_CHAIR" => $form['chair'],
            "TSR_ROOM_DESK" => $form['table'],
            "TSR_ROOM_AIRCOND" => $form['aircond'],
            "TSR_ROOM_LAMP" => $form['lamps'],
            "TSR_ROOM_REMARK" => $form['training_room_remark'],

            "TSR_EQUIPMENT_COMPUTER" => $form['laptop_desktop'],
            "TSR_EQUIPMENT_LASERPOINTER" => $form['laser_pointer'],
            "TSR_EQUIPMENT_LCD" => $form['lcd_pointer'],
            "TSR_EQUIPMENT_PASYSTEM" => $form['pa_system'],
            "TSR_EQUIPMENT_REMARK" => $form['equipment_remark'],

            "TSR_OVERALL_REPORT" => $form['overall_remark']
        );

        //$this->db->set("TH_REF_ID", $refID, false);

        return $this->db->insert("TRAINING_SECRETARIAT_REPORT", $data);
    }

    // UPDATE SECRET REPORT
    public function updateSecretReport($form, $refid)
    {
        $data = array(
            "TSR_PARTICIPANT_DISCIPLINE" => $form['discipline'],
            "TSR_PARTICIPANT_TIME" => $form['participant_time'],
            "TSR_PARTICIPANT_REMARK" => $form['participant_remark'],

            "TSR_CAFE_NAME" => $form['cafe_name'],
            "TSR_CAFE_TIME" => $form['food_drink_time'],
            "TSR_CAFE_QUALITY" => $form['food_drink_quality'],
            "TSR_CAFE_REMARK" => $form['cafe_remark'],

            "TSR_ROOM_CHAIR" => $form['chair'],
            "TSR_ROOM_DESK" => $form['table'],
            "TSR_ROOM_AIRCOND" => $form['aircond'],
            "TSR_ROOM_LAMP" => $form['lamps'],
            "TSR_ROOM_REMARK" => $form['training_room_remark'],

            "TSR_EQUIPMENT_COMPUTER" => $form['laptop_desktop'],
            "TSR_EQUIPMENT_LASERPOINTER" => $form['laser_pointer'],
            "TSR_EQUIPMENT_LCD" => $form['lcd_pointer'],
            "TSR_EQUIPMENT_PASYSTEM" => $form['pa_system'],
            "TSR_EQUIPMENT_REMARK" => $form['equipment_remark'],

            "TSR_OVERALL_REPORT" => $form['overall_remark']
        );

        $this->db->where("TSR_REFID", $refid);

        return $this->db->update("TRAINING_SECRETARIAT_REPORT", $data);
    }

    // GET STAFF DEPT
    public function getStaffDept($staff_id)
    {
        $this->db->select("SM_DEPT_CODE");
        $this->db->from("STAFF_MAIN");
        $this->db->where("SM_STAFF_ID", $staff_id);

        $q = $this->db->get();
        return $q->row();
    }

    // CHECK SECRETARIAT ON DUTY
    public function checkSecretDuty($refid, $secretariat_id) {
        $this->db->select("TSI_SEQ, TSI_INCHARGE, to_char(TSI_INCHARGE_DATE, 'DD/MM/YYYY') AS INCHARGE_DATE");
        $this->db->from("TRAINING_SECRET_INCHARGE");
        $this->db->where("TSI_REFID", $refid);
        $this->db->where("TSI_INCHARGE", $secretariat_id);

        $q = $this->db->get();
        return $q->row();
    }

    // INSERT SECRET REPORT
    public function insertSecretDuty($form, $refid)
    {
        $tsi_seq = "(SELECT CASE 
        WHEN TSI_SEQ IS NULL THEN 1
        WHEN TSI_SEQ IS NOT NULL THEN TSI_SEQ
        END AS TSI_SEQ
        FROM(
        SELECT MAX(TSI_SEQ)+1 AS TSI_SEQ
        FROM TRAINING_SECRET_INCHARGE
        WHERE TSI_REFID = '$refid'))";

        $curDate = "SYSDATE";

        $data = array(
            "TSI_REFID" => $refid,
            "TSI_INCHARGE" => $form['secretariat_id']
        );

        $this->db->set("TSI_SEQ", $tsi_seq, false);

        if(!empty($form['date'])){
            $date = "to_date('".$form['date']."', 'DD/MM/YYYY')";
            $this->db->set("TSI_INCHARGE_DATE", $date, false);
        } else {
            $this->db->set("TSI_INCHARGE_DATE", $curDate, false);
        }

        return $this->db->insert("TRAINING_SECRET_INCHARGE", $data);
    }

    // DELETE SECRETARIAT INCHARGE
    public function deleteScrIncharge($refid, $seq, $scrId) {
        $this->db->where('TSI_REFID', $refid);
        $this->db->where('TSI_SEQ', $seq);
        $this->db->where('TSI_INCHARGE', $scrId);
        return $this->db->delete('TRAINING_SECRET_INCHARGE');
    }

    /*===========================================================
       Training Application Report - ATF081
    =============================================================*/

    // GET DEPARTMENT LIST
    public function getDeptListAppRpt() {
        $this->db->select("DM_DEPT_CODE, DM_DEPT_DESC, DM_DEPT_CODE ||' - '|| DM_DEPT_DESC AS DEPT_CODE_DESC");
        $this->db->from('DEPARTMENT_MAIN');
		$this->db->where("DM_STATUS = 'ACTIVE'");
		$this->db->where('DM_LEVEL <= 2');
        $this->db->order_by('DM_DEPT_DESC');
        $q = $this->db->get();
		        
        return $q->result();
    }

    // GET COURSE LIST REPORT I
    public function courseTitlei($year) {
        $this->db->select("TH_REF_ID, TH_TRAINING_TITLE, TO_CHAR(TH_DATE_FROM,'DD/MM/YYYY')||' - '||TO_CHAR(TH_DATE_TO,'DD/MM/YYYY') TH_DATE,
                            TH_DATE_FROM");
        $this->db->from("TRAINING_HEAD");
        $this->db->where("TH_STATUS = 'APPROVE'");
        $this->db->where("TO_CHAR(TH_DATE_FROM,'YYYY') = NVL('$year',TO_CHAR(SYSDATE,'YYYY'))");
        $this->db->where("TH_REF_ID IN (SELECT TMH_TRAINING_REFID FROM TRAINING_MEMO_HISTORY)");
        
        $this->db->order_by("TH_DATE_FROM, TH_TRAINING_TITLE");

        $q = $this->db->get();
        return $q->result();
    }

    // GET ORGANIZER LIST REPORT II
    public function getOrganizer() {
        $this->db->select("TOH_ORG_CODE, TOH_ORG_DESC, TOH_ORG_CODE||' - '||TOH_ORG_DESC AS TOH_CODE_DESC,
                            TOH_ADDRESS, TOH_POSTCODE, TOH_CITY, 
                            SM_STATE_DESC, CM_COUNTRY_DESC");
        $this->db->from("TRAINING_ORGANIZER_HEAD, STATE_MAIN, COUNTRY_MAIN");
        $this->db->where("TOH_STATE = SM_STATE_CODE AND TOH_COUNTRY = CM_COUNTRY_CODE");
        $this->db->order_by("2");

        $q = $this->db->get();
        return $q->result();
    }

    // GET SECTOR LIST REPORT II
    public function getSector() {
        $this->db->select("TSL_CODE, TSL_DESC, TSL_CODE||' - '||TSL_DESC TSL_CODE_DESC");
        $this->db->from("TRAINING_SECTOR_LEVEL");

        $q = $this->db->get();
        return $q->result();
    }

    // GET COURSE LIST REPORT III
    public function courseTitleiii($year) {
        $this->db->select("TH_REF_ID, TH_TRAINING_TITLE, TH_REF_ID||' - '||TH_TRAINING_TITLE TH_ID_TITLE, 
                            TO_CHAR(TH_DATE_FROM,'DD/MM/YYYY')||' - '||TO_CHAR(TH_DATE_TO,'DD/MM/YYYY') TH_DATE,
                            TH_DATE_FROM");
        $this->db->from("TRAINING_HEAD");
        $this->db->where("TH_STATUS = 'APPROVE'");
        $this->db->where("TO_CHAR(TH_DATE_FROM,'YYYY') = NVL('$year',TO_CHAR(SYSDATE,'YYYY'))");
        
        $this->db->order_by("TH_DATE_FROM, TH_TRAINING_TITLE");

        $q = $this->db->get();
        return $q->result();
    }


    // GET UNIT LIST REPORT VII
    public function getUnitVii($deptCode) {
        $this->db->select("DM_DEPT_CODE, DM_DEPT_CODE||' - '||DM_DEPT_DESC DM_DEPT_DESC");
        $this->db->from("DEPARTMENT_MAIN");
        $this->db->where("DM_LEVEL >= '3'");
        $this->db->where("DM_STATUS = 'ACTIVE'");
        $this->db->where("DM_PARENT_DEPT_CODE", $deptCode);
        
        $this->db->order_by("2");

        $q = $this->db->get();
        return $q->result();
    }
    
     //start update @17/02/2020
    //--------------------------------------------------------------------------
    
    public function getTerasTrainingList($year,$month) {
        $this->db->select("TH_REF_ID, TH_TRAINING_TITLE, TO_CHAR(TH_DATE_FROM,'DD/MM/YYYY') AS DATE_FROM, TO_CHAR(TH_DATE_TO,'DD/MM/YYYY') AS DATE_TO,
            TH_DATE_FROM,THD_COORDINATOR||' - '||SM_STAFF_NAME as COOR");
        $this->db->from("TRAINING_HEAD");
        $this->db->join("TNA_TRAINING_HEAD","TTH_REF_ID = TH_TRAINING_CODE");
        $this->db->join("TRAINING_HEAD_DETL","TH_REF_ID = THD_REF_ID","LEFT");
        $this->db->join("STAFF_MAIN","SM_STAFF_ID = THD_COORDINATOR","LEFT");
        $this->db->where("TH_STATUS = 'APPROVE'");
        $this->db->where("COALESCE(TTH_STRUCTURED,'N') = 'Y'");
        $this->db->where("COALESCE(TTH_STATUS,'INACTIVE') = 'ACTIVE'");
        $this->db->where("TTH_REF_ID LIKE 'TRS%'");
        //$this->db->where("('$month' IS NULL OR ('$month' IS NOT NULL AND TO_CHAR(TH_DATE_FROM,'MM') = '$month'))");
        //$this->db->where("('$year' IS NULL OR ('$year' IS NOT NULL AND TO_CHAR(TH_DATE_FROM,'YYYY') = '$year'))");
        //$this->db->where("TO_CHAR(TH_DATE_FROM,'YYYY') = NVL('$year',TO_CHAR(SYSDATE,'YYYY'))"); 
        //$this->db->where("TO_CHAR(TH_DATE_FROM,'MM') = NVL('$month',TO_CHAR(SYSDATE,'MM'))"); 
        
        if(!empty($month) && !empty($year)) {
            $this->db->where("((NVL(to_char(TH_DATE_FROM,'MM/YYYY'),'') = '$month'||'/'||'$year'))");
        } elseif(!empty($month)) {
            $this->db->where("((NVL(to_char(TH_DATE_FROM,'MM'),'') = '$month'))");
        } elseif(!empty($year)) {
            $this->db->where("((NVL(to_char(TH_DATE_FROM,'YYYY'),'') = '$year'))");
        }
        
        $this->db->order_by("TH_DATE_FROM");

        $q = $this->db->get();
        return $q->result();
    }//getTerasTrainingList
    
    public function getTerasList() {
        $this->db->select("TTH_REF_ID, TTH_TRAINING_TITLE");
        $this->db->from("TNA_TRAINING_HEAD");
        $this->db->where("COALESCE(TTH_STRUCTURED,'N') = 'Y'");
        $this->db->where("COALESCE(TTH_STATUS,'INACTIVE') = 'ACTIVE'");
        $this->db->where("TTH_REF_ID LIKE 'TRS%'");

        $this->db->order_by("TTH_REF_ID");

        $q = $this->db->get();
        return $q->result();
    }//getTerasList
    
    //end update @17/02/2020 -----------------------------------------------------------
    
    // for ATF008Q 
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
    // GET ORGANIZER DETAILS QUERY SCREEN
    public function getOrganizerName_Query($organizerCode = null) {
        $this->db->select("TOH_ORG_CODE, TOH_ORG_DESC, TOH_ORG_CODE ||' - '|| TOH_ORG_DESC AS TOH_ORG_CODE_DESC, TOH_ADDRESS, TOH_POSTCODE, TOH_CITY, SM_STATE_DESC, CM_COUNTRY_DESC");
        $this->db->from('TRAINING_ORGANIZER_HEAD, STATE_MAIN, COUNTRY_MAIN');
        $this->db->where("TOH_STATE=SM_STATE_CODE");
        $this->db->where("TOH_COUNTRY=CM_COUNTRY_CODE");
        //$this->db->where("NVL(TOH_EXTERNAL_AGENCY,'N') <> 'Y'");

        if(!empty($organizerCode)) {
            $this->db->where("TOH_ORG_CODE", $organizerCode);
            $q = $this->db->get();
        
            return $q->row();
        } 
        else {
            $this->db->order_by("2");
            $q = $this->db->get();
        
            return $q->result();
        }
        
        
    }//getOrganizerName_Query
    // for ATF008Q
    
}
