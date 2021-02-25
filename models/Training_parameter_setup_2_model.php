<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Training_parameter_setup_2_model extends MY_Model
{
    private $staff_id;

    public function __construct()
    {
        $this->load->database();
        $this->staff_id = $this->lib->userid();
    }
    
    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                    TRAINING PARAMETER SETUP 2
    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    /*===========================================================
       Training Effectiveness Evaluation 
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTraEffEvaSetup($thm)
    {
        $this->db->select('(hp_parm_desc)::numeric hp_parm_desc, hp_parm_no, hp_parm_code');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->where("hp_parm_code = 'TRAINING_HOD_MEMO'");
        $this->db->where('hp_parm_no', $thm);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTrEvaMemSetup()
    {
        $this->db->select('(hp_parm_desc)::numeric hp_parm_desc, hp_parm_code, hp_parm_no');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->where("hp_parm_code = 'TRAINING_EVALUATION_MEMO'");
        $this->db->where('hp_parm_no = 1');
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTrEvaPorSetup()
    {
        $this->db->select('hp_parm_desc, hp_parm_code, hp_parm_no');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->where("hp_parm_code = 'TRAINING_EVALUATION_PORTAL'");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/

    // -postgres
    public function saveUpdateHODmem($hpp_no, $form)
    {
        $parmCode = $form['hpp_code'];

        if ($parmCode == 'TRAINING_HOD_MEMO') {
            $data = array(
                "hp_parm_desc" => $form['day']
            );

            $this->db->where("hp_parm_code = 'TRAINING_HOD_MEMO'");
        } 
        if ($parmCode == 'TRAINING_EVALUATION_MEMO') {
            $data = array(
                "hp_parm_desc" => $form['time']
            );
            $this->db->where("hp_parm_code = 'TRAINING_EVALUATION_MEMO'");
        }
        if ($parmCode == 'TRAINING_EVALUATION_PORTAL') {
            $data = array(
                "hp_parm_desc" => $form['function_portal']
            );
            $this->db->where("hp_parm_code = 'TRAINING_EVALUATION_PORTAL'");
        }
        
        //$this->db->where("HP_PARM_CODE = 'TRAINING_HOD_MEMO'");
        $this->db->where("hp_parm_no", $hpp_no);
        
        return $this->db->update("ims_hris.hradmin_parms", $data);
    }


    /*=======================================================================
       Training Effectiveness SETUP
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // -postgres
    public function getTrEffSetup()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_category');
        
        $this->db->order_by('tac_status DESC, tac_ordering ');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // -postgres
    public function getEffTitleSetup($codeTAC)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_category');
        $this->db->join("ims_hris.training_evaluation_title", "ims_hris.training_evaluation_title.tet_category = ims_hris.training_assessment_category.tac_code");
        $this->db->where('tac_code', $codeTAC);
        $this->db->order_by('tet_ordering');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // -postgres
    public function getEffSetup($codeTAC)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_setup');
        $this->db->join("ims_hris.training_assessment_category", "ims_hris.training_assessment_category.tac_code = ims_hris.training_assessment_setup.tas_category");
        $this->db->where('ims_hris.training_assessment_category.tac_code', $codeTAC);
        $this->db->where('tas_title IS NULL');
        $this->db->where("tas_type IN ('EFFECTIVENESS')");
        $this->db->order_by('tas_status ASC, tas_ordering');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // -postgres
    public function getEffSetup2($codeTET)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_evaluation_title');
        $this->db->join("ims_hris.training_assessment_setup", "ims_hris.training_assessment_setup.tas_category = ims_hris.training_evaluation_title.tet_category");
        $this->db->where('ims_hris.training_evaluation_title.tet_code', $codeTET);
        $this->db->where('ims_hris.training_assessment_setup.tas_numbering IS NOT NULL');
        $this->db->where('ims_hris.training_assessment_setup.tas_title', $codeTET);
        $this->db->where("ims_hris.training_assessment_setup.tas_type IN ('EFFECTIVENESS')");
        $this->db->order_by('tas_status ASC,tas_ordering');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTACDetail($codeTAC)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_category');
        $this->db->where('tac_code', $codeTAC);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTACDetailDel($codeTAC)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_evaluation_title');
        $this->db->where('tet_category', $codeTAC);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTACDetailDel2($codeTAC)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_setup');
        $this->db->where('tas_category', $codeTAC);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTETDetail($codeTET)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_evaluation_title');
        $this->db->where('tet_code', $codeTET);
        //$this->db->where('TET_CATEGORY', $codeTAC);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTETDetailDel($codeTET)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_setup');
        $this->db->where('tas_title', $codeTET);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTASEffDetail($codeTAS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_setup');
        $this->db->where('tas_code', $codeTAS);
        $this->db->where("tas_type IN ('EFFECTIVENESS')");
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTASEffDetailDel($tasCode, $typeTAS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_setup');
        $this->db->where('tas_type', $typeTAS);
        $this->db->where('tas_code', $tasCode);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTAC($form)
    {
        $insertDate = "date_trunc('second', NOW()::timestamp)";
        // set array data to be inserted
        $data = array(
            "tac_code" => strtoupper($form['code']),
            "tac_desc" => strtoupper($form['description']),
            "tac_ordering" => $form['order'],
            "tac_status" => $form['status'],
            "tac_update_by" => $this->staff_id
        );

        // set data to be inserted (reference id, date)
        $this->db->set("tac_update_date", $insertDate, false);
        
        return $this->db->insert("ims_hris.training_assessment_category", $data);
    }

    // -postgres
    public function insertTET($form)
    {
        // set array data to be inserted
        $data = array(
            "tet_code" => strtoupper($form['code']),
            "tet_desc" => strtoupper($form['description']),
            "tet_ordering" => $form['order'],
            "tet_category" => $form['codetac']
        );
        
        return $this->db->insert("ims_hris.training_evaluation_title", $data);
    }

    // -postgres
    public function insertTASEff($form)
    {
        // set array data to be inserted
        $data = array(
            "tas_type" => $form['type'],
            "tas_category" => $form['tas_category'],
            "tas_ordering" => $form['order'],
            "tas_numbering" => $form['no_portal'],
            "tas_code" => $form['no'],
            "tas_desc" => $form['description'],
            "tas_assessment_type" => $form['effectiveness_type'],
            "tas_status" => $form['status']
        );
        
        return $this->db->insert("ims_hris.training_assessment_setup", $data);
    }

    // -postgres
    public function insertTASEff2($form)
    {
        // set array data to be inserted
        $data = array(
            "tas_type" => $form['type'],
            "tas_title" => $form['tas_title'],
            "tas_category" => $form['tas_category'],
            "tas_ordering" => $form['order'],
            "tas_numbering" => $form['no_portal'],
            "tas_code" => $form['no'],
            "tas_desc" => $form['description'],
            "tas_assessment_type" => $form['effectiveness_type'],
            "tas_status" => $form['status']
        );
        
        return $this->db->insert("ims_hris.training_assessment_setup", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTACdb($codeTAC)
    {
        $this->db->where('tac_code', $codeTAC);
        return $this->db->delete('ims_hris.training_assessment_category');
    }

    // -postgres
    public function deleteTETdb($codeTET, $codeTAC)
    {
        $this->db->where('tet_code', $codeTET);
        $this->db->where('tet_category', $codeTAC);
        return $this->db->delete('ims_hris.training_evaluation_title');
    }

    // -postgres
    public function deleteTASEffdb($tasCode, $tasType)
    {
        $this->db->where('tas_code', $tasCode);
        $this->db->where('tas_type', $tasType);
        return $this->db->delete('ims_hris.training_assessment_setup');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTAC($codeTAC, $form)
    {
        $upDate = "date_trunc('second', NOW()::timestamp)";

        $data = array(
            "tac_desc" => strtoupper($form['description']),
            "tac_ordering" => $form['order'],
            "tac_status" => $form['status'],
            "tac_update_by" => $this->staff_id
        );
        
        $this->db->set("tac_update_date", $upDate, false);

        $this->db->where("tac_code", $codeTAC);
        
        return $this->db->update("ims_hris.training_assessment_category", $data);
    }

    // -postgres
    public function saveUpdateTET($codeTET, $codeTAC, $form)
    {
        $data = array(
            "tet_desc" => strtoupper($form['description']),
            "tet_ordering" => $form['order']
        );

        $this->db->where("tet_code", $codeTET);
        $this->db->where("tet_category", $codeTAC);
        
        return $this->db->update("ims_hris.training_evaluation_title", $data);
    }

    // -postgres
    public function saveUpdateTASEff($tasCode, $tasType, $form)
    {
        $data = array(
            "tas_ordering" => $form['order'],
            "tas_numbering" => $form['no_portal'],
            "tas_desc" => $form['description'],
            "tas_assessment_type" => $form['effectiveness_type'],
            "tas_status" => $form['status']
        );

        $this->db->where("tas_code", $tasCode);
        $this->db->where("tas_type", $tasType);
        
        return $this->db->update("ims_hris.training_assessment_setup", $data);
    }


    /*===========================================================
       Training Component
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // -postgres
    public function getTrModCom()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_module_component');
        $this->db->order_by('tmc_component_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // -postgres
    public function getTMCDetail($code_tmc){
        $this->db->select('*');
        $this->db->from('ims_hris.training_module_component');
        $this->db->where('tmc_component_code', $code_tmc);
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _____________________*/
    // -postgres
    public function insertTMC($form)
    {
        $data = array(
            "tmc_component_code" => strtoupper($form['code']),
            "tmc_component_desc" => $form['description']
        );
        
        return $this->db->insert("ims_hris.training_module_component", $data);
    }

    /*_____________________
        DELETE PROCESS
    _____________________*/
    // -postgres
    public function deleteTMCdb($tmc_code)
    {
        $this->db->where('tmc_component_code', $tmc_code);
        return $this->db->delete('ims_hris.training_module_component');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // -postgres
    public function saveUpdateTMC($code_tmc, $form)
    {
        $data = array(
            "tmc_component_desc" => $form['description']
        );
         
        $this->db->where("tmc_component_code", $code_tmc);
        
        return $this->db->update("ims_hris.training_module_component", $data);
    }

    /*===========================================================
       Training External Agency Setup
    =============================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // -postgres
    public function getExAgSetup()
    {
        $this->db->select('(hp_parm_desc)::numeric hp_parm_desc, hp_parm_no, hp_parm_code');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->where("hp_parm_code = 'EXTERNAL_MAX_DAY_APPL'");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // -postgres
    public function getExAgSetupUrl()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.hradmin_parms');
        $this->db->where("hp_parm_code = 'EXTERNAL_URL'");
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/

    // -postgres
    public function saveUpdateEAS($eas_no, $form)
    {
        $parmCode = $form['eas_code'];

        if ($parmCode == 'EXTERNAL_MAX_DAY_APPL') {
            $data = array(
                "hp_parm_desc" => $form['external_max_day_apply']
            );
            $this->db->where("hp_parm_code = 'EXTERNAL_MAX_DAY_APPL'");
        }
        if ($parmCode == 'EXTERNAL_URL') {
            $data = array(
                "hp_parm_desc" => $form['guideline_url']
            );
            $this->db->where("hp_parm_code = 'EXTERNAL_URL'");
        }
        
        //$this->db->where("HP_PARM_CODE = 'TRAINING_HOD_MEMO'");
        //$this->db->where("HP_PARM_NO", $hpp_no);
        
        return $this->db->update("ims_hris.hradmin_parms", $data);
    }


    /*=======================================================================
       TRAINING COMPETENCY LEVEL
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // -postgress
    public function getTrComLvl()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_competency_level');
        
        $this->db->order_by('tcl_ordering ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTCLDetail($codeTCL)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_competency_level');
        $this->db->where('tcl_competency_code', strtoupper($codeTCL));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTCL($form)
    {
        // set array data to be inserted
        $data = array(
            "tcl_competency_code" => strtoupper($form['code']),
            "tcl_competency_desc" => $form['description'],
            "tcl_service_year_from" => $form['service_year_from'],
            "tcl_service_year_to" => $form['service_year_to'],
            "tcl_ordering" => $form['ordering'],
            "tcl_status" => $form['status']
        );
        
        return $this->db->insert("ims_hris.training_competency_level", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTCLdb($codeTCL)
    {
        $this->db->where('tcl_competency_code', $codeTCL);
        return $this->db->delete('ims_hris.training_competency_level');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTCL($codeTCL, $form)
    {
        $data = array(
            "tcl_competency_desc" => $form['description'],
            "tcl_service_year_from" => $form['service_year_from'],
            "tcl_service_year_to" => $form['service_year_to'],
            "tcl_ordering" => $form['ordering'],
            "tcl_status" => $form['status']
        );
        
        $this->db->where("tcl_competency_code", $codeTCL);
        
        return $this->db->update("ims_hris.training_competency_level", $data);
    }


    /*=======================================================================
       TRAINING COST SETUP (TYPE)
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // -postgres
    public function getTrCostSt()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_cost_type');
        
        $this->db->order_by('tct_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTCSDetail($codeTCS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_cost_type');
        $this->db->where('tct_code', $codeTCS);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTCS($form)
    {
        // set array data to be inserted
        $data = array(
            "tct_code" => strtoupper($form['code']),
            "tct_desc" => $form['description'],
            "tct_status" => $form['status']
        );
        
        return $this->db->insert("ims_hris.training_cost_type", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    // delete validation // -postgres
    public function getTCSDelVal($codeTCS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.staff_training_cost_detl');
        $this->db->where('stcd_cost_code', strtoupper($codeTCS));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTCSDelVal2($codeTCS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_cost');
        $this->db->where('tc_cost_code', strtoupper($codeTCS));
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteTCSdb($codeTCS)
    {
        $this->db->where('tct_code', $codeTCS);
        return $this->db->delete('ims_hris.training_cost_type');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTCS($codeTCS, $form)
    {
        $data = array(
            "tct_desc" => $form['description'],
            "tct_status" => $form['status']
        );
        
        $this->db->where("tct_code", $codeTCS);
        
        return $this->db->update("ims_hris.training_cost_type", $data);
    }


    /*=======================================================================
       TRAINING COST SETUP (FEE CATEGORY)
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // -postgres
    public function getTrFeeSt()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_fee_setup');
        
        $this->db->order_by('tfs_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTFSDetail($codeTFS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_fee_setup');
        $this->db->where('tfs_code', $codeTFS);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTFSDeptFull($codeTFS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_fee_setup');
        $this->db->join('ims_hris.department_main', 'ims_hris.training_fee_setup.tfs_dcr_approve = ims_hris.department_main.dm_dept_code');
        $this->db->where('tfs_code', $codeTFS);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getTFSDeptFull2($codeTFS)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_fee_setup');
        $this->db->join('ims_hris.department_main', 'ims_hris.training_fee_setup.tfs_registrar_approve = ims_hris.department_main.dm_dept_code');
        $this->db->where('tfs_code', $codeTFS);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getDeptList()
    {
        $this->db->select('dm_dept_code, dm_dept_desc');
        $this->db->from('ims_hris.department_main');
        $this->db->where("coalesce(dm_status, 'INACTIVE') = 'ACTIVE'");
        $this->db->order_by('dm_dept_code');
        $q = $this->db->get();
		        
        return $q->result_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTFS($form)
    {
        // set array data to be inserted
        $data = array(
            "tfs_code" => strtoupper($form['code']),
            "tfs_desc" => $form['description'],
            "tfs_amount_from" => $form['minimum_range'],
            "tfs_amount_to" => $form['maximum_range'],
            "tfs_dcr_approve" => $form['dcr_approve'],
            "tfs_registrar_approve" => $form['registrar_approve'],
            "tfs_mpe_approve" => $form['mpe_approve'],
            "tfs_status" => $form['status']
        );
        
        return $this->db->insert("ims_hris.training_fee_setup", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTFSdb($codeTFS)
    {
        $this->db->where('tfs_code', $codeTFS);
        return $this->db->delete('ims_hris.training_fee_setup');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTFS($codeTFS, $form)
    {
        $data = array(
            "tfs_desc" => $form['description'],
            "tfs_amount_from" => $form['minimum_range'],
            "tfs_amount_to" => $form['maximum_range'],
            "tfs_dcr_approve" => $form['dcr_approve'],
            "tfs_registrar_approve" => $form['registrar_approve'],
            "tfs_mpe_approve" => $form['mpe_approve'],
            "tfs_status" => $form['status']
        );
        
        $this->db->where("tfs_code", $codeTFS);
        
        return $this->db->update("ims_hris.training_fee_setup", $data);
    }


    /*=======================================================================
       TRAINING Effectiveness Grading Setup
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/

    // -postgres
    public function getEffGraSetup($typeEGS = null)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_grading');
        if (!empty($typeEGS)) {
            $this->db->where("tag_grade_type", $typeEGS);
        }
        
        $this->db->order_by('tag_grade_code ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getEGSDetail($code_egs)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_assessment_grading');
        $this->db->where("tag_grade_type = 'EFFECTIVENESS'");
        $this->db->where('tag_grade_code', $code_egs);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertEGS($form)
    {
        // set array data to be inserted
        $data = array(
            "tag_grade_code" => $form['mark'],
            "tag_grade_desc" => $form['description'],
            "tag_grade_type" => $form['type']
        );
        
        return $this->db->insert("ims_hris.training_assessment_grading", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteEGSdb($egs_code)
    {
        $this->db->where("tag_grade_type = 'EFFECTIVENESS'");
        $this->db->where('tag_grade_code', $egs_code);
        return $this->db->delete('ims_hris.training_assessment_grading');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateEGS($code_egs, $form)
    {
        $data = array(
            "tag_grade_desc" => $form['description'],
            "tag_grade_type" => $form['type']
        );
        
        $this->db->where("tag_grade_type = 'EFFECTIVENESS'");
        $this->db->where('tag_grade_code', $code_egs);
        
        return $this->db->update("ims_hris.training_assessment_grading", $data);
    }


    /*=======================================================================
       TRAINING MEMO FOR PARTICIPANTS
    =========================================================================*/

    /*_____________________
        GET BASIC INFO
    _______________________*/
    // -postgres
    public function getTrainingMemoPar()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_memo_content');
        $this->db->where("tmc_module IN ('TRAINING_EFFECTIVENESS','ASSIGN_TRAINING','TRAINING_HELPDESK','TRAINING_APPL_SECTOR')");
        //$this->db->order_by("TMC_CODE ASC");
        
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // -postgres
    public function getTrainingMemoEva()
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_evaluation_memo');
        $this->db->where("tem_module = 'TRAINING_EVALUATION'");
        
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // -postgres
    public function getStaffFullName($staffID)
    {
        //select sm_staff_id, sm_staff_name from staff_main,staff_status where ss_status_code = sm_staff_status and ss_status_sts = 'ACTIVE' and sm_staff_type = 'STAFF' order by 2
        $this->db->select('sm_staff_id, sm_staff_name');
        $this->db->from('ims_hris.staff_main, ims_hris.staff_status');
        $this->db->where("ss_status_code = sm_staff_status");
        $this->db->where("ss_status_sts = 'ACTIVE'");
        $this->db->where("sm_staff_type = 'STAFF'");
        $this->db->where("sm_staff_id", $staffID);
        
        $q = $this->db->get();
        
        return $q->row_case('UPPER');
    }

    // -postgres
    public function getStaffList()
    {
        //select sm_staff_id, sm_staff_name from staff_main,staff_status where ss_status_code = sm_staff_status and ss_status_sts = 'ACTIVE' and sm_staff_type = 'STAFF' order by 2
        $this->db->select('sm_staff_id, sm_staff_name');
        $this->db->from('ims_hris.staff_main, ims_hris.staff_status');
        $this->db->where("ss_status_code = sm_staff_status");
        $this->db->where("ss_status_sts = 'ACTIVE'");
        $this->db->where("sm_staff_type = 'STAFF'");
        $this->db->order_by("2");
        
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // CHECK record // -postgres
    public function getTMPDetail($codeTMP)
    {
        $this->db->select('*');
        $this->db->from('ims_hris.training_memo_content');
        $this->db->where('tmc_code', $codeTMP);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    /*public function getTEMDetail($codeTEM)
    {
        $this->db->select('*');
        $this->db->from('TRAINING_EVALUATION_MEMO');
        $this->db->where('TEM_CODE', $codeTEM);
        $q = $this->db->get();

        return $q->row();
    }*/

    /*_____________________
        ADD PROCESS
    _______________________*/
    // INSERT DATA to db // -postgres
    public function insertTMP($form)
    {
        // $tmp_code = "'TMC'||LTRIM(TO_CHAR(nextval('ims_hris.training_memo_content_seq'),'0000000'))";
        $tmp_code = "'TMC'||LTRIM(TO_CHAR(nextval('ims_hris.training_memo_content_seq'),'0000000'))";


        $status = "";
        if (empty($form['status'])) {
            $status = 'Y';
        } else {
            $status = $form['status'];
        }

        // set array data to be inserted
        $data = array(
            "tmc_module" => $form['module'],
            "tmc_address" => $form['address'],
            "tmc_link" => $form['link'],
            "tmc_email" => $form['email'],
            "tmc_telno" => $form['tel_no'],
            "tmc_faxno" => $form['fax_no'],
            "tmc_send_by" => $form['send_by'],
            "tmc_status" => $status
        );

        // set data to be inserted (reference id, date)
        $this->db->set("tmc_code", $tmp_code, false);
        
        return $this->db->insert("ims_hris.training_memo_content", $data);
    }

    /*_______________________
        DELETE PROCESS
    _______________________*/
    // DELETE DATA to db // -postgres
    public function deleteTMPdb($tmp_code)
    {
        $this->db->where('tmc_code', $tmp_code);
        return $this->db->delete('ims_hris.training_memo_content');
    }

    /*_______________________
        UPDATE PROCESS
    _______________________*/
    // UPDATE DATA to db // -postgres
    public function saveUpdateTMP($code_tmp, $form)
    {
        $status = "";
        if (empty($form['status'])) {
            $status = 'Y';
        } else {
            $status = $form['status'];
        }

        $data = array(
            "tmc_module" => $form['module'],
            "tmc_address" => $form['address'],
            "tmc_link" => $form['link'],
            "tmc_email" => $form['email'],
            "tmc_telno" => $form['tel_no'],
            "tmc_faxno" => $form['fax_no'],
            "tmc_send_by" => $form['send_by'],
            "tmc_status" => $status
        );
        
        $this->db->where('tmc_code', $code_tmp);
        
        return $this->db->update("ims_hris.training_memo_content", $data);
    }

    // -postgres
    public function saveUpdateTEM($form)
    {
        $data = array(
            "tem_title" => $form['title'],
            "tem_content" => $form['content'],
            "tem_send_by" => $form['send_by'],
            "tem_status" => $form['status'],
        );
        
        $this->db->where("tem_code = 'TEM00001'");
        
        return $this->db->update("ims_hris.training_evaluation_memo", $data);
    }
}
