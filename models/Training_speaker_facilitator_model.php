<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Training_speaker_facilitator_model extends MY_Model {

    private $staff_id;

    public function __construct() {
        $this->load->database();
        $this->staff_id = $this->lib->userid();
        //$this->staff_id = 'K00825';
    }

	/*===========================================================
       EXTERNAL SPEAKER 
    =============================================================*/

    // GET BASIC INFORMATION - view // -postgres
    public function getBasicExtSp()
    {
        $this->db->select('es_speaker_id, es_speaker_name, es_ic_no, es_dept, es_position, es_status');
        $this->db->from('ims_hris.external_speaker');
        $this->db->order_by('es_speaker_id');
        return $this->db->get()->result_case('UPPER');
    }

    // CHECK if record exist for ADD PROCESS based on name // -postgres
    public function checkESDetail($esName)
    {
        $this->db->select('es_speaker_name');
        $this->db->from('ims_hris.external_speaker');
        $this->db->where('es_speaker_name', $esName);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // GET ALL DETAILS for selected External Speaker based on speaker id
    // Used by DETAIL, EDIT and DELETE // -postgres
    public function getESDetail($esID)
    {
        $this->db->select('es_status, es_speaker_name, es_ic_no, es_dept, es_position, es_telno_work,
        es_handphone, es_address, es_pcode, es_city, es_state, es_country');
        $this->db->from('ims_hris.external_speaker');
        $this->db->where('es_speaker_id', $esID);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // get state & country full name for specific speaker
    // used by DETAIL only // -postgres
    public function getESCountryDesc($esID)
    {
        $this->db->select('external_speaker.es_speaker_id, external_speaker.es_state, country_main.cm_country_code, country_main.cm_country_desc');
        $this->db->from('ims_hris.external_speaker');
        //$this->db->join('STATE_MAIN', 'EXTERNAL_SPEAKER.ES_STATE=STATE_MAIN.SM_STATE_CODE');
        $this->db->join('ims_hris.country_main', 'ims_hris.external_speaker.es_country=ims_hris.country_main.cm_country_code');
        $this->db->where('ims_hris.external_speaker.es_speaker_id', $esID);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getESStateDesc($esID)
    {
        $this->db->select('external_speaker.es_speaker_id, external_speaker.es_state, state_main.sm_state_code, state_main.sm_state_desc');
        $this->db->from('ims_hris.external_speaker');
        $this->db->join('ims_hris.state_main', 'ims_hris.external_speaker.es_state=ims_hris.state_main.sm_state_code');
        //$this->db->join('COUNTRY_MAIN', 'EXTERNAL_SPEAKER.ES_COUNTRY=COUNTRY_MAIN.CM_COUNTRY_CODE');
        $this->db->where('ims_hris.external_speaker.es_speaker_id', $esID);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }
    
    // INSERT DATA to db // -postgres
    public function insertES($form)
    {
        $insertDate = "date_trunc('second', NOW()::timestamp)";
        // $insertESID = "to_char(sysdate,'YYYY')||ltrim(to_char(EXTERNAL_SPEAKER_SEQ.nextval,'000000'))";
        $insertESID = "TO_CHAR(CURRENT_DATE,'YYYY')||LTRIM(TO_CHAR(nextval('ims_hris.external_speaker_seq'),'000000'))";

        // set array data to be inserted
        $data = array(
            "es_status" => strtoupper($form['status']),
            "es_speaker_name" => $form['name'],
            "es_ic_no" => $form['ic_no'],
            "es_dept" => $form['organization'],
            "es_position" => $form['posES'],
            "es_telno_work" => $form['offTelES'],
            "es_handphone" => $form['hp_no'],
            "es_address" => $form['addressES'],
            "es_pcode" => $form['postcodeES'],
            "es_city" => $form['cityES'],
            "es_state" => $form['state_es'],
            "es_country" => $form['country_es'],
            "es_enter_by" => $this->staff_id,
        );

        // set data to be inserted (reference id, date)
        $this->db->set("es_speaker_id", $insertESID, false);
        $this->db->set("es_enter_date", $insertDate, false);
        
        return $this->db->insert("ims_hris.external_speaker", $data);
    } // insertES()
    
    // UPDATE DATA to db // -postgres
    public function saveUpdateES($IDes, $form)
    {   
        $updateDate = "date_trunc('second', NOW()::timestamp)";
        $updateESID = $form['IDes'];
        $data = array(
            "es_status" => strtoupper($form['status']),
            "es_speaker_name" => $form['name'],
            "es_ic_no" => $form['ic_no'],
            "es_dept" => $form['organization'],
            "es_position" => $form['posES'],
            "es_telno_work" => $form['offTelES'],
            "es_handphone" => $form['hp_no'],
            "es_address" => $form['addressES'],
            "es_pcode" => $form['postcodeES'],
            "es_city" => $form['cityES'],
            "es_state" => $form['state_es'],
            "es_country" => $form['country_es'],
            "es_update_by" => $this->staff_id,
        );

        // set data to be inserted (reference id, date)
        $this->db->set("es_speaker_id", $updateESID, false);
        $this->db->set("es_update_date", $updateDate, false);
        
        $this->db->where("es_speaker_id", $IDes);
        
        return $this->db->update("ims_hris.external_speaker", $data);
    }
    
    // DELETE DATA to db // -postgres
    public function deleteESdb($id_es)
    {
        $this->db->where('es_speaker_id', $id_es);
        return $this->db->delete('ims_hris.external_speaker');
    }

    /*===========================================================
       EXTERNAL FACILITATOR 
    =============================================================*/

    // ------------------
	// GET BASIC INFORMATION
    // ------------------
    // -postgres
    public function getExternalFacilitatorInfo() {
        $this->db->select('ef_facilitator_id, ef_facilitator_name, ef_department, ef_organization, 
        ef_position, ef_state, ef_status');
        $this->db->from('ims_hris.external_facilitator');
		
		/*if (!empty($searchK)) {
			$this->db->where('SVAE_STATUS', $statusK);
		} */
		
        $this->db->order_by('ef_facilitator_name ASC');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    }

    // INSERT DATA to db // -postgres
    public function insertEF($form)
    {
        $insertDate = "date_trunc('second', NOW()::timestamp)";
        // $insertEFID = "to_char(sysdate,'YYYY')||ltrim(to_char(EXTERNAL_FACILITATOR_SEQ.nextval,'000000'))";
        $insertEFID = "TO_CHAR(CURRENT_DATE,'YYYY')||LTRIM(TO_CHAR(nextval('ims_hris.external_facilitator_seq'),'000000'))";


        // set array data to be inserted
        $data = array(
            "ef_status" => strtoupper($form['status']),
            "ef_facilitator_name" => $form['name'],
            "ef_ic_no" => $form['ic_no'],
            "ef_department" => $form['deptEF'],
            "ef_organization" => $form['organization'],
            "ef_position" => $form['posEF'],
            "ef_telno_work" => $form['offTelEF'],
            "ef_handphone_no" => $form['hp_no'],
            "ef_email_addr" => $form['email'],
            "ef_area_1" => $form['area1'],
            "ef_area_2" => $form['area2'],
            "ef_area_3" => $form['area3'],
            "ef_address" => $form['addressEF'],
            "ef_state" => $form['state'],
            "ef_country" => $form['country'],
            "ef_pcode" => $form['postcodeEF'],
            "ef_city" => $form['cityEF'],
            "ef_enter_by" => $this->staff_id,
        );

        // set data to be inserted (reference id, date)
        $this->db->set("ef_facilitator_id", $insertEFID, false);
        $this->db->set("ef_enter_date", $insertDate, false);
        
        return $this->db->insert("ims_hris.external_facilitator", $data);
    }


    // CHECK if record exist for ADD PROCESS based on name // -postgres
    public function checkEFDetail($efName)
    {
        $this->db->select('ef_facilitator_name');
        $this->db->from('ims_hris.external_facilitator');
        $this->db->where('ef_facilitator_name', $efName);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // GET ALL DETAILS for selected External Facilitator based on Facilitator id
    // Used by DETAIL, EDIT and DELETE // -postgres
    public function getEFDetail($efID)
    {
        $this->db->select('ef_status, ef_facilitator_name, ef_ic_no, ef_department, ef_organization, 
        ef_position, ef_telno_work, ef_handphone_no, ef_email_addr, ef_area_1, ef_area_2, ef_area_3, 
        ef_address, ef_state, ef_country, ef_pcode, ef_city');
        $this->db->from('ims_hris.external_facilitator');
        $this->db->where('ef_facilitator_id', $efID);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // get state & country full name for specific Facilitator
    // used by DETAIL only // -postgres
    public function getEFCountryDesc($efID)
    {
        $this->db->select('external_facilitator.ef_facilitator_id, external_facilitator.ef_state, country_main.cm_country_code, 
        country_main.cm_country_desc');
        $this->db->from('ims_hris.external_facilitator');
        //$this->db->join('STATE_MAIN', 'EXTERNAL_FACILITATOR.EF_STATE=STATE_MAIN.SM_STATE_CODE');
        $this->db->join('ims_hris.country_main', 'ims_hris.external_facilitator.ef_country=ims_hris.country_main.cm_country_code');
        $this->db->where('external_facilitator.ef_facilitator_id', $efID);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // -postgres
    public function getEFStateDesc($efID)
    {
        $this->db->select('external_facilitator.ef_facilitator_id, external_facilitator.ef_state, state_main.sm_state_code, 
        state_main.sm_state_desc');
        $this->db->from('ims_hris.external_facilitator');
        $this->db->join('ims_hris.state_main', 'ims_hris.external_facilitator.ef_state=ims_hris.state_main.sm_state_code');
        //$this->db->join('COUNTRY_MAIN', 'EXTERNAL_FACILITATOR.EF_COUNTRY=COUNTRY_MAIN.CM_COUNTRY_CODE');
        $this->db->where('ims_hris.external_facilitator.ef_facilitator_id', $efID);
        $q = $this->db->get();

        return $q->row_case('UPPER');
    }

    // DELETE DATA to db // -postgres
    public function deleteEFdb($id_ef)
    {
        $this->db->where('ef_facilitator_id', $id_ef);
        return $this->db->delete('ims_hris.external_facilitator');
    }

    // UPDATE DATA to db // -postgres
    public function saveUpdateEF($EFid, $form)
    {   
        $updateDate = "date_trunc('second', NOW()::timestamp)";
        $updateESID = $form['efID'];
        $data = array(
            "ef_status" => strtoupper($form['status']),
            "ef_facilitator_name" => $form['name'],
            "ef_ic_no" => $form['ic_no'],
            "ef_department" => $form['deptEF'],
            "ef_organization" => $form['organization'],
            "ef_position" => $form['posEF'],
            "ef_telno_work" => $form['offTelEF'],
            "ef_handphone_no" => $form['hp_no'],
            "ef_email_addr" => $form['email'],
            "ef_area_1" => $form['area1'],
            "ef_area_2" => $form['area2'],
            "ef_area_3" => $form['area3'],
            "ef_address" => $form['addressEF'],
            "ef_state" => $form['state'],
            "ef_country" => $form['country'],
            "ef_pcode" => $form['postcodeEF'],
            "ef_city" => $form['cityEF'],
            "ef_update_by" => $this->staff_id,
        );

        // set data to be set (reference id, date)
        $this->db->set("ef_facilitator_id", $updateESID, false);
        $this->db->set("ef_update_date", $updateDate, false);
        
        $this->db->where("ef_facilitator_id", $updateESID);
        
        return $this->db->update("ims_hris.external_facilitator", $data);
    }

    /*=======================================================================
       EXTERNAL FACILITATOR && EXTERNAL SPEAKER - populate country and state
    =========================================================================*/
    // used for ADD & UPDATE form to get country list // -postgres
    public function getCountryList() {
        $this->db->select('cm_country_code, cm_country_desc');
        $this->db->from('ims_hris.country_main');
        $this->db->order_by('cm_country_desc');
        $q = $this->db->get();
		        
        return $q->result_case('UPPER');
    }
    
    // used by ADD & UPDATE form to get state based on choosen country // -postgres
    public function getCountryStateList($countCode) {
        $this->db->select('sm_state_code, sm_state_desc, sm_country_code');
        $this->db->from('ims_hris.state_main');
		$this->db->where('sm_country_code', $countCode);
        $this->db->order_by('sm_state_code');
        $q = $this->db->get();
        
        return $q->result_case('UPPER');
    } 
}


