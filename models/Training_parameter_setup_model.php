<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Training_parameter_setup_model extends MY_Model
{
    private $staff_id;

    public function __construct()
    {
        $this->load->database();
        $this->staff_id = $this->lib->userid();
    }
    
    /*=======================================================================
                            TRAINING PARAMETER SETUP
    =========================================================================*/
    /*=======================================================================
       TRAINING TYPE
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // get max application training application setup // -postgres
    public function getTrMaxAppl()
    {
        $this->db->select('(hp_parm_desc)::numeric hp_parm_desc');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->where("hp_parm_code = 'TRAINING_MAX_APPL'");

        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // list training type // -postgres
    public function getTrainingTypeList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_type');
        
        $this->db->order_by('tt_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record - training type // -postgres
    public function getTTDetail($codeTT)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_type');
        $this->db->where('tt_code', strtoupper($codeTT));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db - training type // -postgres
    public function insertTT($form)
    {
        // set array data to be inserted
        $data = array(
            "tt_code" => strtoupper($form['code']),
            "tt_desc" => strtoupper($form['training_type']),
            "tt_counted" => $form['counted'],
            "tt_evaluation" => $form['training_evaluation'],
            "tt_confirmation" => $form['confirmation'],
            "tt_service_book" => $form['service_book']
        );
        
        return $this->db->insert("ims_hris.training_type", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    
    // delete validation // -postgres
    public function deleteTTVal($codeTT)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_head');
        $this->db->where('th_type', strtoupper($codeTT));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }


    // DELETE DATA to db - training type // -postgres
    public function deleteTTdb($codeTT)
    {
        $this->db->where('tt_code', $codeTT);
        return $this->db->delete('ims_hris.training_type');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateMA($form)
    {
        $data = array(
            "hp_parm_desc" => $form['maximum_application']
        );
        
        $this->db->where("hp_parm_code = 'TRAINING_MAX_APPL'");
        
        return $this->db->update("ims_hris.hradmin_parms", $data);
    }
    
    // -postgres
    public function saveUpdateTT($codeTT, $form)
    {
        $data = array(
            "tt_desc" => strtoupper($form['training_type']),
            "tt_counted" => $form['counted'],
            "tt_evaluation" => $form['training_evaluation'],
            "tt_confirmation" => $form['confirmation'],
            "tt_service_book" => $form['service_book']
        );
        
        $this->db->where("tt_code", $codeTT);
        
        return $this->db->update("ims_hris.training_type", $data);
    }


    /*=======================================================================
       TRAINING CATEGORY
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // list training category // -postgres
    public function getTrainingCategoryList($stTraining = null, $status = null)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_category');

        if (!empty($stTraining)) {
            $this->db->where('tc_structured', $stTraining);
        }

        if (!empty($status)) {
            $this->db->where('tc_status', $status);
        }
        
        $this->db->order_by('tc_ranking ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record - training type // -postgres
    public function getTCDetail($categoryTC)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_category');
        $this->db->where('tc_category', strtoupper($categoryTC));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTC($form)
    {
        // set array data to be inserted
        $data = array(
            "tc_category" => strtoupper($form['category']),
            "tc_confirmation" => $form['confirmation'],
            "tc_element" => $form['element'],
            "tc_structured" => $form['structured'],
            "tc_ranking" => $form['sort_by'],
            "tc_status" => $form['status']
        );
        
        return $this->db->insert("ims_hris.training_category", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db - training type // -postgres
    public function deleteTCdb($tc_cat)
    {
        $this->db->where('tc_category', $tc_cat);
        return $this->db->delete('ims_hris.training_category');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTC($categoryTC, $form)
    {
        $data = array(
            "tc_confirmation" => $form['confirmation'],
            "tc_element" => $form['element'],
            "tc_structured" => $form['structured'],
            "tc_ranking" => $form['sort_by'],
            "tc_status" => $form['status']
        );
        
        $this->db->where("tc_category", $categoryTC);
        
        return $this->db->update("ims_hris.training_category", $data);
    }


    /*===========================================================
        TRAINING LEVEL
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingLevelList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_level');
        
        $this->db->order_by('tl_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // -postgres
    public function getTLDetail($codeTL)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_level');
        $this->db->where('tl_code', strtoupper($codeTL));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTL($form)
    {
        // set array data to be inserted
        $data = array(
            "tl_code" => strtoupper($form['code']),
            "tl_desc" => strtoupper($form['training_level']),
            "tl_desc_eng" => strtoupper($form['training_level_english'])
        );
        
        return $this->db->insert("ims_hris.training_level", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // delete validation // -postgres
    public function getTLDelVal($codeTL)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_head');
        $this->db->where('th_level', strtoupper($codeTL));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteTLdb($tl_code)
    {
        $this->db->where('tl_code', $tl_code);
        return $this->db->delete('ims_hris.training_level');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTL($codeTT, $form)
    {
        $data = array(
            "tl_desc" => strtoupper($form['training_level']),
            "tl_desc_eng" => strtoupper($form['training_level_english'])
        );
        
        $this->db->where("tl_code", $codeTT);
        
        return $this->db->update("ims_hris.training_level", $data);
    }


    /*===========================================================
        TRAINING SPONSOR LEVEL
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingSponsorList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_sponsor_level');
        
        $this->db->order_by('tsl_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }
    // -postgres
    public function getTSLDetail($codeTSL)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_sponsor_level');
        $this->db->where('tsl_code', strtoupper($codeTSL));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTSL($form)
    {
        // set array data to be inserted
        $data = array(
            "tsl_code" => strtoupper($form['code']),
            "tsl_desc" => strtoupper($form['sponsor_level'])
        );
        
        return $this->db->insert("ims_hris.training_sponsor_level", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // delete validation // -postgres
    public function getTSLDelVal($tsl_code)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.staff_training_head');
        $this->db->where('sth_sponsor_level', strtoupper($tsl_code));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteTSLdb($tsl_code)
    {
        $this->db->where('tsl_code', $tsl_code);
        return $this->db->delete('ims_hris.training_sponsor_level');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTSL($codeTSL, $form)
    {
        $data = array(
            "tsl_desc" => strtoupper($form['sponsor_level'])
        );
        
        $this->db->where("tsl_code", $codeTSL);
        
        return $this->db->update("ims_hris.training_sponsor_level", $data);
    }


    /*===========================================================
        TRAINING ORGANIZER LEVEL
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingOrganizerLevelList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_organizer_level');
        
        $this->db->order_by('tol_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTOLDetail($codeTOL)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_organizer_level');
        $this->db->where('tol_code', strtoupper($codeTOL));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTOL($form)
    {
        // set array data to be inserted
        $data = array(
            "tol_code" => strtoupper($form['code']),
            "tol_desc" => strtoupper($form['organizer_level'])
        );
        
        return $this->db->insert("ims_hris.training_organizer_level", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // validation delete // -postgres
    public function getTOLDelVal($tol_code)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_head');
        $this->db->where('th_organizer_level', strtoupper($tol_code));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteTOLdb($tol_code)
    {
        $this->db->where('tol_code', $tol_code);
        return $this->db->delete('ims_hris.training_organizer_level');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTOL($codeTOL, $form)
    {
        $data = array(
            "tol_desc" => strtoupper($form['organizer_level'])
        );
        
        $this->db->where("tol_code", $codeTOL);
        
        return $this->db->update("ims_hris.training_organizer_level", $data);
    }


    /*===========================================================
        TRAINING PARTICIPANT ROLE
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingParticipantRoleList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_participant_role');
        
        $this->db->order_by('tpr_order_by ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTPRDetail($codeTPR)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_participant_role');
        $this->db->where('tpr_code', strtoupper($codeTPR));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTPR($form)
    {
        // set array data to be inserted
        $data = array(
            "tpr_code" => strtoupper($form['code']),
            "tpr_desc" => strtoupper($form['participant_role']),
            "tpr_order_by" => $form['sort_order'],
            "tpr_cpd_counted_nacad" => $form['cpd_counted_non_academic'],
            "tpr_cpd_counted_acad" => $form['cpd_counted_academic'],
            "tpr_display_training" => $form['display_training'],
            "tpr_display_conference" => $form['display_conference']
        );
        
        return $this->db->insert("ims_hris.training_participant_role", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // delete validation // -postgres
    public function getTPRDelVal($tpr_code)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.staff_training_head');
        $this->db->where('sth_participant_role', strtoupper($tpr_code));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteTPRdb($tpr_code)
    {
        $this->db->where('tpr_code', $tpr_code);
        return $this->db->delete('ims_hris.training_participant_role');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTPR($codeTPR, $form)
    {
        $data = array(
            "tpr_desc" => strtoupper($form['participant_role']),
            "tpr_order_by" => $form['sort_order'],
            "tpr_cpd_counted_nacad" => $form['cpd_counted_non_academic'],
            "tpr_cpd_counted_acad" => $form['cpd_counted_academic'],
            "tpr_display_training" => $form['display_training'],
            "tpr_display_conference" => $form['display_conference']
        );
        
        $this->db->where("tpr_code", $codeTPR);
        
        return $this->db->update("ims_hris.training_participant_role", $data);
    }


    /*===========================================================
        TRAINING PARTICIPANT STATUS
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingParticipantStatusList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_participant_status');
        
        $this->db->order_by('tps_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTPSDetail($codeTPS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_participant_status');
        $this->db->where('tps_code', strtoupper($codeTPS));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTPS($form)
    {
        // set array data to be inserted
        $data = array(
            "tps_code" => strtoupper($form['code']),
            "tps_desc" => strtoupper($form['participant_status']),
        );
        
        return $this->db->insert("ims_hris.training_participant_status", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // delete validation // -postgres
    public function getTPSDelVal($tps_code)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.staff_training_head');
        $this->db->where('sth_participant_status', strtoupper($tps_code));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }
    
    // DELETE DATA to db // -postgres
    public function deleteTPSdb($tps_code)
    {
        $this->db->where('tps_code', $tps_code);
        return $this->db->delete('ims_hris.training_participant_status');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTPS($codeTPS, $form)
    {
        $data = array(
            "tps_desc" => strtoupper($form['participant_status']),
        );
        
        $this->db->where("tps_code", $codeTPS);
        
        return $this->db->update("ims_hris.training_participant_status", $data);
    }


    /*===========================================================
        TRAINING SECTOR LEVEL
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingSectorLevelList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_sector_level');
        
        $this->db->order_by('tsl_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTSECLDetail($codeTSECL)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_sector_level');
        $this->db->where('tsl_code', strtoupper($codeTSECL));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTSECL($form)
    {
        // set array data to be inserted
        $data = array(
            "tsl_code" => strtoupper($form['code']),
            "tsl_desc" => strtoupper($form['sector_level']),
            "tsl_status" => $form['sector_level_status']
        );
        
        return $this->db->insert("ims_hris.training_sector_level", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTSECLdb($codeTSECL)
    {
        $this->db->where('tsl_code', $codeTSECL);
        return $this->db->delete('ims_hris.training_sector_level');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTSECL($codeTSECL, $form)
    {
        $data = array(
            "tsl_desc" => strtoupper($form['sector_level']),
            "tsl_status" => $form['sector_level_status']
        );
        
        $this->db->where("tsl_code", $codeTSECL);
        
        return $this->db->update("ims_hris.training_sector_level", $data);
    }


    /*===========================================================
        TRAINING REMARK SETUP
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingRemarkSetupList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_remark_setup');
        
        $this->db->order_by('trs_seq ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getRSDetail($no_seq)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_remark_setup');
        $this->db->where('trs_seq', strtoupper($no_seq));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertRS($form)
    {
        // set array data to be inserted
        $data = array(
            "trs_seq" => strtoupper($form['no']),
            "trs_remark" => strtoupper($form['remark']),
            "trs_module" => strtoupper($form['module'])
        );
        
        return $this->db->insert("ims_hris.training_remark_setup", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteRSdb($trs_seq)
    {
        $this->db->where('trs_seq', $trs_seq);
        return $this->db->delete('ims_hris.training_remark_setup');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateRS($no_seq, $form)
    {
        $data = array(
            "trs_seq" => strtoupper($form['no']),
            "trs_remark" => strtoupper($form['remark']),
            "trs_module" => strtoupper($form['module'])
        );
        
        $this->db->where("trs_seq", $no_seq);
        
        return $this->db->update("ims_hris.training_remark_setup", $data);
    }


    /*===========================================================
        TRAINING ATTENDANCE STATUS
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingAttendanceStatusList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_attendance_status');
        
        $this->db->order_by('tas_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTASDetail($code_tas)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_attendance_status');
        $this->db->where('tas_code', strtoupper($code_tas));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTAS($form)
    {
        // set array data to be inserted
        $data = array(
            "tas_code" => strtoupper($form['code']),
            "tas_desc" => strtoupper($form['attendance_status'])
        );
        
        return $this->db->insert("ims_hris.training_attendance_status", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTASdb($tas_code)
    {
        $this->db->where('tas_code', $tas_code);
        return $this->db->delete('ims_hris.training_attendance_status');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTAS($code_tas, $form)
    {
        $data = array(
            "tas_desc" => strtoupper($form['attendance_status'])
        );
        
        $this->db->where("tas_code", $code_tas);
        
        return $this->db->update("ims_hris.training_attendance_status", $data);
    }


    /*===========================================================
        TRAINING ASSESSMENT SETUP
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingAssessmentSetupList($sts = null)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_setup');
        $this->db->where("tas_type IN ('TRAINING','FACILITATOR')");

        if (!empty($sts)) {
            $this->db->where('tas_type', $sts);
        }

        $this->db->order_by('tas_type, tas_ordering');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTASVDetail($code_tasv, $type_tasv)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_setup');
        $this->db->where('tas_code', $code_tasv);
        $this->db->where("tas_type in ('".$type_tasv."')");
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // verify if record related to another table // -postgres
    public function getTASVVerDetail($tasv_type)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_grading');
        $this->db->join('ims_hris.training_assessment_setup', 'ims_hris.training_assessment_grading.tag_grade_type = ims_hris.training_assessment_setup.tas_type');
        //$this->db->where('STA_ASSESSMENT_CODE', $code_tasv);
        $this->db->where("ims_hris.training_assessment_setup.tas_type in ('".$tasv_type."')");
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTASV($form)
    {
        // set array data to be inserted
        $data = array(
            "tas_type" => $form['type'],
            "tas_ordering" => $form['order'],
            "tas_code" => $form['code'],
            "tas_desc" => $form['description'],
            "tas_assessment_type" => $form['assessment_type'],
            "tas_numbering" => $form['label'],
            "tas_status" => $form['status']
        );
        
        return $this->db->insert("ims_hris.training_assessment_setup", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // delete validation // -postgres
    public function getTASDelVal($tas_code)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.staff_training_attendance');
        $this->db->where('sta_attendance_status', strtoupper($tas_code));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteTASVdb($tasv_code, $tasv_type)
    {
        $this->db->where('tas_code', $tasv_code);
        $this->db->where("tas_type in ('".$tasv_type."')");
        // $date_from = "to_date('".$form['date_from']."', 'DD/MM/YYYY')";
        return $this->db->delete('ims_hris.training_assessment_setup');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTASV($code_tasv, $type_tasv, $form)
    {
        $data = array(
            "tas_type" => $form['type'],
            "tas_ordering" => $form['order'],
            "tas_desc" => $form['description'],
            "tas_assessment_type" => $form['assessment_type'],
            "tas_numbering" => $form['label'],
            "tas_status" => $form['status']
        );
        
        
        $this->db->where('tas_code', $code_tasv);
        $this->db->where("tas_type in ('".$type_tasv."')");
        
        return $this->db->update("ims_hris.training_assessment_setup", $data);
    }

    /*_____________________________________________
        GRADING SETUP
    _______________________________________________*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingGradingList($assessmentType)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_grading');
        $this->db->where("tag_grade_type in ('".$assessmentType."')");
        $this->db->order_by('tag_grade_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTGDetail($code_tg, $type_tg)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_grading');
        $this->db->where("tag_grade_code", $code_tg);
        $this->db->where("tag_grade_type in ('".$type_tg."')");
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTG($form)
    {
        // set array data to be inserted
        $data = array(
            "tag_grade_type" => $form['type'],
            "tag_grade_code" => $form['mark'],
            "tag_grade_desc" => $form['grading_description']
        );
        
        return $this->db->insert("ims_hris.training_assessment_grading", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTGdb($tg_code, $tg_type)
    {
        $this->db->where('tag_grade_code', $tg_code);
        $this->db->where("tag_grade_type in ('".$tg_type."')");
        return $this->db->delete('ims_hris.training_assessment_grading');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTG($code_tg, $type_tg, $form)
    {
        $data = array(
            "tag_grade_desc" => $form['grading_description']
        );
        
        
        $this->db->where('tag_grade_code', $code_tg);
        $this->db->where("tag_grade_type in ('".$type_tg."')");
        
        return $this->db->update("ims_hris.training_assessment_grading", $data);
    }


    /*===========================================================
        TRAINING REQUIREMENT TYPE
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingRequirementTypeList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_requirement_type');
        
        $this->db->order_by('trt_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record
    public function getTRTDetail($code_trt)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_requirement_type');
        $this->db->where('trt_code', strtoupper($code_trt));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTRT($form)
    {
        // set array data to be inserted
        $data = array(
            "trt_code" => strtoupper($form['code']),
            "trt_desc" => strtoupper($form['requirement_type'])
        );
        
        return $this->db->insert("ims_hris.training_requirement_type", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // delete validation // -postgres
    public function getTRTDelVal($trt_code)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_requirement');
        $this->db->where('tra_training_req_code', strtoupper($trt_code));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteTRTdb($trt_code)
    {
        $this->db->where('trt_code', $trt_code);
        return $this->db->delete('ims_hris.training_requirement_type');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTRT($code_trt, $form)
    {
        $data = array(
            "trt_desc" => strtoupper($form['requirement_type'])
        );
        
        
        $this->db->where("trt_code", $code_trt);
        
        return $this->db->update("ims_hris.training_requirement_type", $data);
    }


    /*===========================================================
        TRAINING ORGANIZER INFO (HEAD)
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingOrganizerHeadList()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_organizer_head');
        $this->db->join('ims_hris.country_main', 'ims_hris.training_organizer_head.toh_country = ims_hris.country_main.cm_country_code');
        $this->db->where("toh_external_agency = 'N' OR toh_external_agency is NULL");
        $this->db->order_by('toh_org_desc ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTOHDetail($code_toh)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_organizer_head');
        $this->db->where('toh_org_code', $code_toh);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }
    // -postgres
    public function getTOHDetailSDesc($code_toh)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_organizer_head');
        $this->db->join('ims_hris.state_main', 'ims_hris.training_organizer_head.toh_state = ims_hris.state_main.sm_state_code');
        $this->db->where('toh_org_code', $code_toh);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }
    // -postgres
    public function getTOHDetailCDesc($code_toh)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_organizer_head');
        $this->db->join('ims_hris.country_main', 'ims_hris.training_organizer_head.toh_country = ims_hris.country_main.cm_country_code');
        $this->db->where('toh_org_code', $code_toh);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTOH($form)
    {
        // set array data to be inserted
        $data = array(
            "toh_org_code" => strtoupper($form['code']),
            "toh_org_desc" => strtoupper($form['description']),
            "toh_address" => $form['address'],
            "toh_postcode" => $form['postcode'],
            "toh_city" => $form['city'],
            "toh_state" => $form['state'],
            "toh_country" => $form['country'],
            "toh_external_agency" => $form['external_agency']
        );
        
        return $this->db->insert("ims_hris.training_organizer_head", $data);
    }
    
    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTOHdb($toh_code)
    {
        $this->db->where('toh_org_code', $toh_code);
        return $this->db->delete('ims_hris.training_organizer_head');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTOH($code_toh, $form)
    {
        $data = array(
            "toh_org_desc" => strtoupper($form['description']),
            "toh_address" => $form['address'],
            "toh_postcode" => $form['postcode'],
            "toh_city" => $form['city'],
            "toh_state" => $form['state'],
            "toh_country" => $form['country']
        );
        
        
        $this->db->where("toh_org_code", $code_toh);
        
        return $this->db->update("ims_hris.training_organizer_head", $data);
    }
    
    // used for ADD & UPDATE form to get country list // -postgres
    public function getCountryList()
    {
        $this->db->select('cm_country_code, cm_country_desc');
        $this->db->from('ims_hris.country_main');
        $this->db->order_by('cm_country_desc');
        $q = $this->db->get();
                
        return $q->result_case('UPPER');
    }
    
    // used by ADD & UPDATE form to get state based on choosen country // -postgres
    public function getCountryStateList($countCode)
    {
        $this->db->select('sm_state_code, sm_state_desc, sm_country_code');
        $this->db->from('ims_hris.state_main');
        $this->db->where('sm_country_code', $countCode);
        $this->db->order_by('sm_state_code');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }
	
    /*_____________________
	TAB Assessment Setup: Training / Facilitator Assessment General Setup
    _____________________*/
	// ASF014 - TAB Assessment Setup: Training / Facilitator Assessment General Setup
	// GET MAIN INFO // -postgres
    public function getTrainFaciEvalSetupInfo() {
        // $this->db->select('HP_PARM_CODE AS PARM_CODE, HP_PARM_DESC AS PARM_DESC, 
		// 				DECODE(HP_PARM_CODE,\'TRAINING_FACILITATOR_EVAL_REMINDER_INTERVAL\',\'Send Memo Reminder Interval\', 
        //                 \'\') AS PARM_LABEL, 
        //                 case 
        //                 when 
		// 				DECODE(HP_PARM_CODE,\'TRAINING_FACILITATOR_EVAL_REMINDER_INTERVAL\',\'days\', 
        //                 \'\') AS PARM_REMARK');
        $this->db->select("hp_parm_code as parm_code, hp_parm_desc as parm_desc, 
        case hp_parm_code
        when 'TRAINING_FACILITATOR_EVAL_REMINDER_INTERVAL' then 'Send Memo Reminder Interval'
        else ''
        end as parm_label,
        case hp_parm_code
        when 'TRAINING_FACILITATOR_EVAL_REMINDER_INTERVAL' then 'days'
        else ''
        end as parm_remark");             
        $this->db->from('ims_hris.hradmin_parms');
		$this->db->where('hp_parm_code IN (\'TRAINING_FACILITATOR_EVAL_REMINDER_INTERVAL\')');
        $this->db->order_by("case hp_parm_code
        when 'TRAINING_FACILITATOR_EVAL_REMINDER_INTERVAL' then '1'
        else hp_parm_code
        end");
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    } //getTrainFaciEvalSetupInfo()
	
	// GET DETAILS // -postgres
    public function getTrainFaciEvalSetupDetail($parmCode, $parmDesc) {
        $this->db->select('hp_parm_code as parm_code, hp_parm_desc as parm_desc');
        $this->db->from('ims_hris.hradmin_parms');
		$this->db->where('hp_parm_code', $parmCode);
		$this->db->where('hp_parm_desc', $parmDesc);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    } //getTrainFaciEvalDetail()
	
    // UPDATE // -postgres
    public function updateTrainFaciEvalSetupDetail($parmCode, $newParmDesc) {	
        $data = array(
            "hp_parm_desc" => $newParmDesc
        );
        
		$this->db->where("hp_parm_code", $parmCode);
        
        return $this->db->update("ims_hris.hradmin_parms", $data);
    } // updateTrainFaciEvalSetupDetail()
}
