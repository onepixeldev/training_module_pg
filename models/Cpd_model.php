<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cpd_model extends MY_Model
{
    private $staff_id;
    private $username;

    public function __construct()
    {
        $this->load->database();
        $this->staff_id = $this->lib->userid();
        $this->username = $this->lib->username();
    }

    // GET ADMIN DEPT
    public function getCpdAdminDeptCode() {
		$sID = $this->staff_id;
		
        $this->db->select('HP_PARM_DESC AS PARM_DESC');
        $this->db->from('HRADMIN_PARMS');
        $this->db->join('STAFF_MAIN','SM_DEPT_CODE = UPPER(TRIM(HP_PARM_DESC))');
        $this->db->where('HP_PARM_CODE', 'TRAINING_ADM_DEPT_CODE');
        $this->db->where('SM_STAFF_ID', $sID);
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

    // GET STAFF LIST DROPDOWN
    public function getStaffList($staffID = null, $dept)
    {
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_STAFF_ID ||' - '||SM_STAFF_NAME AS SM_STAFF_ID_NAME, TO_CHAR(SYSDATE, 'DD/MM/YYYY') AS CURR_DATE");
        $this->db->from("STAFF_MAIN, STAFF_STATUS");
        $this->db->where("SS_STATUS_CODE = SM_STAFF_STATUS");
        $this->db->where("SM_STAFF_TYPE = 'STAFF'");

        if(!empty($staffID)) {
            $this->db->where("UPPER(SM_STAFF_ID) = UPPER('$staffID')");
            $q = $this->db->get();
            return $q->row();
        } else {
            $this->db->where("SS_STATUS_STS = 'ACTIVE'");
            $this->db->where("SM_DEPT_CODE", $dept);
            $this->db->order_by("2");

            $q = $this->db->get();
            return $q->result();
        }
    }

    // get current date -postgres
    public function getCurDate() {		
        $this->db->select("TO_CHAR(current_date, 'MM') AS sysdate_mm, TO_CHAR(current_date, 'YYYY') AS sysdate_yyyy");
        //$this->db->from("DUAL");
        $q = $this->db->get();
                
        return $q->row_case('UPPER');
    } 
    
    /*===============================================================
       CPD SETUP (ATF098)
    ================================================================*/

    // CPD CATEGORYLIST
    public function cpdCategoryList($code = null)
    {
        $this->db->select("CC_CATEGORY_CODE, CC_CATEGORY_DESC, CC_STATUS, 
        CASE CC_STATUS
            WHEN 'Y' THEN 'Active'
            WHEN 'N' THEN 'Inactive'
        END AS CC_STATUS_DESC,
        CC_CATEGORY_CODE||' - '||CC_CATEGORY_DESC CC_CATEGORY_CODE_DESC");
        $this->db->from("CPD_CATEGORY");

        if(!empty($code)) {
            $this->db->where("CC_CATEGORY_CODE", $code);
            $q = $this->db->get();
        
            return $q->row();
        } else {
            $q = $this->db->get();
    
            return $q->result();
        }
    }

    // CPD POINT
    public function cpdPointList($sYear, $cp_scheme = null)
    {
        $this->db->select("CP_SCHEME, CP_RANK, CP_CPD_LAYAK, CP_CPD_KHUSUS_MIN, CP_CPD_UMUM_MIN,CP_UMUM_MANDATORY, CP_CPD_TERAS_MIN, CP_LNPT_WEIGHTAGE, SOG_GROUP_DESC");
        $this->db->join("SERVICE_ORG_GROUP", "SOG_GROUP_CODE = CP_SCHEME");
        $this->db->from("CPD_POINT");
        if(!empty($sYear)) {
            $this->db->where("CP_YEAR", $sYear);
            // $this->db->where("(:year_year is null and cp_year =to_char(sysdate,'yyyy')) or (:year_year is not null and  cp_year =:year_year");
        } else {
            $this->db->where("CP_YEAR = TO_CHAR(SYSDATE,'YYYY')");
        }   
        
        if(!empty($cp_scheme)) {
            $this->db->where("CP_SCHEME", $cp_scheme);

            $q = $this->db->get();
        
            return $q->row();
        } else {
            $this->db->order_by("CP_RANK");
            $q = $this->db->get();
        
            return $q->result();
        }
    }

    // SECTOR LIST
    public function sectorList()
    {   
        $this->db->distinct();
        $this->db->select("SC_CLASS_SECTOR");
        $this->db->from("SERVICE_CLASSIFICATION");
        $this->db->where("SC_CLASS_SECTOR IS NOT NULL");
        $q = $this->db->get();
    
        return $q->result();
    }

    // COORDINATOR LIST
    public function coorList($role, $sector, $rowid = null)
    {
        $this->db->select("ROWIDTOCHAR(CPD_USER_LEVEL.ROWID) ROWIDCHAR, CUL_STAFF_ID, SM_STAFF_NAME, CUL_ROLE, CUL_ROLE_PANEL, CUL_ROLE_DEPT1, CUL_ROLE_DEPT1||' - '||DM1.DM_DEPT_DESC DEPT_CODE_DESC1, CUL_ROLE_DEPT2, CUL_ROLE_DEPT2||' - '||DM2.DM_DEPT_DESC DEPT_CODE_DESC2, CUL_ROLE_DEPT3, CUL_ROLE_DEPT3||' - '||DM3.DM_DEPT_DESC DEPT_CODE_DESC3");
        $this->db->from("CPD_USER_LEVEL");
        $this->db->join("STAFF_MAIN", "CUL_STAFF_ID = SM_STAFF_ID");
        $this->db->join("DEPARTMENT_MAIN DM1", "CUL_ROLE_DEPT1 = DM1.DM_DEPT_CODE", "LEFT");
        $this->db->join("DEPARTMENT_MAIN DM2", "CUL_ROLE_DEPT2 = DM2.DM_DEPT_CODE", "LEFT");
        $this->db->join("DEPARTMENT_MAIN DM3", "CUL_ROLE_DEPT3 = DM3.DM_DEPT_CODE", "LEFT");

        if(!empty($role)) {
            $this->db->where("CUL_ROLE", $role); 
        }
        
        if(!empty($sector)) {
            $this->db->where("CUL_ROLE_PANEL ", $sector); 
        }

        if(!empty($rowid)) {
            $this->db->where("CPD_USER_LEVEL.ROWID = CHARTOROWID('$rowid')");

            $q = $this->db->get();
            return $q->row();
        } else {
            $q = $this->db->get();
            return $q->result();
        }
    }

    // SAVE CPD CATEGORY
    public function saveCpdCategory($form)
    {
        $data = array(
            "CC_CATEGORY_CODE" => $form['code'],
            "CC_CATEGORY_DESC" => $form['description'],
            "CC_STATUS" => $form['status']
        );

        return $this->db->insert("CPD_CATEGORY", $data);
    }

    // SAVE UPDATE CPD CATEGORY
    public function saveUpdCpdCategory($form, $code)
    {
        $data = array(
            "CC_CATEGORY_DESC" => $form['description'],
            "CC_STATUS" => $form['status']
        );

        $this->db->where("CC_CATEGORY_CODE", $code);

        return $this->db->update("CPD_CATEGORY", $data);
    }

    // DEPT LIST DD
    public function getDeptList()
    {   
        $this->db->distinct();
        $this->db->select("DM_DEPT_CODE, DM_DEPT_DESC, DM_DEPT_CODE||' - '||DM_DEPT_DESC DM_DEPT_CODE_DESC");
        $this->db->from("DEPARTMENT_MAIN");
        $this->db->where("DM_LEVEL != 3");
        $this->db->where("DM_STATUS = 'ACTIVE'");
        $this->db->order_by("DM_DEPT_CODE");
        $q = $this->db->get();
    
        return $q->result();

        // select distinct dm_dept_code, dm_dept_desc from department_main where dm_level <> 3 and dm_status = 'ACTIVE' order by dm_dept_code
    }

    // SAVE CPD COORDINATOR
    public function saveCpdCoor($form)
    {
        $data = array(
            "CUL_STAFF_ID" => strtoupper($form['staff_id']),
            "CUL_ROLE" => $form['role'],
            "CUL_ROLE_PANEL" => $form['role_panel'],
            "CUL_ROLE_DEPT1" => $form['department_1'],
            "CUL_ROLE_DEPT2" => $form['department_2'],
            "CUL_ROLE_DEPT3" => $form['department_3']
        );

        return $this->db->insert("CPD_USER_LEVEL", $data);
    }

    // SAVE UPDATE CPD COORDINATOR
    public function saveUpdCpdCoor($form)
    {
        $rowid = $form['row_id'];

        $data = array(
            "CUL_STAFF_ID" => strtoupper($form['staff_id']),
            "CUL_ROLE" => $form['role'],
            "CUL_ROLE_PANEL" => $form['role_panel'],
            "CUL_ROLE_DEPT1" => $form['department_1'],
            "CUL_ROLE_DEPT2" => $form['department_2'],
            "CUL_ROLE_DEPT3" => $form['department_3']
        );

        $this->db->where("ROWID = CHARTOROWID('$rowid')");

        return $this->db->update("CPD_USER_LEVEL", $data);
    }

    // DELETE CPD COORDINATOR
    public function deleteCpdCoor($rowid) {
        $this->db->where("ROWID = CHARTOROWID('$rowid')");
        return $this->db->delete("CPD_USER_LEVEL");
    }

    // COUNT STAFF HEAD CPD POINT
    public function countStaffCpdPointHead($cp_scheme, $cp_year)
    {  
        $this->db->select("COUNT(*) AS COUNT_STAFF");
        $this->db->from("STAFF_CPD_HEAD");
        $this->db->where("SCH_TAHUN", $cp_year);
        $this->db->where("SCH_KUMP", $cp_scheme);
        $q = $this->db->get();
    
        return $q->row();
    }

    // DELETE CPD POINT
    public function deleteCpdPoint($cp_scheme, $cp_year) {
        $this->db->where("CP_YEAR", $cp_year);
        $this->db->where("CP_SCHEME", $cp_scheme);
        return $this->db->delete("CPD_POINT");
    }

    // SCHEME LIST
    public function getSchemeList()
    {  
        $this->db->distinct();
        $this->db->select("SS_SERVICE_KUMPP, SOG_GROUP_DESC, SS_SERVICE_KUMPP||' - '||SOG_GROUP_DESC AS SS_SERVICE_KUMPP_DESC");
        $this->db->from("SERVICE_SCHEME, SERVICE_ORG_GROUP");
        $this->db->where("SS_SERVICE_KUMPP = SOG_GROUP_CODE");
        $this->db->order_by("SS_SERVICE_KUMPP DESC");
        $q = $this->db->get();
    
        return $q->result();
    }

    // SAVE CPD POINT
    public function saveCpdPoint($form)
    {
        $data = array(
            "CP_SCHEME" => $form['scheme'],
            "CP_YEAR" => $form['cp_year'],
            "CP_CPD_LAYAK" => $form['compulsory_cpd'],
            "CP_CPD_KHUSUS_MIN" => $form['minimum_cpd_khusus'],
            "CP_CPD_UMUM_MIN" => $form['minimum_cpd_umum'],
            "CP_UMUM_MANDATORY" => $form['cpd_umum_compulsory'],
            "CP_CPD_TERAS_MIN" => $form['minimum_cpd_teras'],
            "CP_LNPT_WEIGHTAGE" => $form['lnpt_weightage'],
            "CP_RANK" => $form['rank']
        );

        return $this->db->insert("CPD_POINT", $data);
    }

    // SAVE UPDATE CPD POINT
    public function saveUpdCpdPoint($form)
    {
        $data = array(
            "CP_CPD_LAYAK" => $form['compulsory_cpd'],
            "CP_CPD_KHUSUS_MIN" => $form['minimum_cpd_khusus'],
            "CP_CPD_UMUM_MIN" => $form['minimum_cpd_umum'],
            "CP_UMUM_MANDATORY" => $form['cpd_umum_compulsory'],
            "CP_CPD_TERAS_MIN" => $form['minimum_cpd_teras'],
            "CP_LNPT_WEIGHTAGE" => $form['lnpt_weightage'],
            "CP_RANK" => $form['rank']
        );

        $this->db->where("CP_SCHEME", $form['scheme']);
        $this->db->where("CP_YEAR", $form['cp_year']);

        return $this->db->update("CPD_POINT", $data);
    }

    // POPULATE DEPARTMENT
    public function generateStaff($cp_scheme, $cp_year) { 

        $query = "SELECT SM_STAFF_ID, SM_DEPT_CODE, SS_ACADEMIC, SS_CLASS_CODE
        FROM STAFF_MAIN, STAFF_SERVICE, STAFF_STATUS, SERVICE_SCHEME, DEPARTMENT_MAIN, TITLE_MAIN, SERVICE_GROUP
        WHERE SM_JOB_CODE = SS_SERVICE_CODE
        AND SS_STAFF_ID = SM_STAFF_ID
        AND SM_DEPT_CODE=DM_DEPT_CODE
        AND SM_STAFF_STATUS = SS_STATUS_CODE
        AND SM_STAS_TITLE=TM_TITLE_CODE
        AND SS_SERVICE_GROUP = SG_GROUP_CODE
        AND SS_SERVICE_GROUP IS NOT NULL
        AND SS_STATUS_STS = 'ACTIVE'
        AND SM_STAFF_TYPE = 'STAFF'
        AND SS_JOB_STATUS<>'02'
        AND SM_STAFF_ID NOT IN (SELECT SSLH_STAFF_ID STAFF_ID
        FROM STAFF_STUDY_LEAVE_HEAD,STAFF_MAIN SM2,TITLE_MAIN, STUDY_LEAVE_TYPE,STAFF_STATUS
        WHERE SM2.SM_STAFF_ID = SSLH_STAFF_ID
        AND SM2.SM_STAS_TITLE = TM_TITLE_CODE
        AND SSLH_PROGRAM_TYPE = SLT_CODE
        AND SM_STAFF_STATUS = SS_STATUS_CODE
        AND SS_STATUS_STS = 'ACTIVE'
        AND SM_STAFF_TYPE = 'STAFF'
        AND SLT_TYPE = 'FULL_TIME'
        AND SSLH_STATUS='APPROVE'
        AND TRIM(UPPER(NVL(SSLH_REP_DUTY_STATUS,'N'))) <> TRIM(UPPER('Lapor Diri'))
        AND TRIM(UPPER(NVL(SSLH_COMPLETE,'N'))) = 'N'
        AND TRIM(UPPER(NVL(SSLH_CLOSE_FILE,'N'))) = 'N'
        AND ((SYSDATE BETWEEN SSLH_DATE_FROM AND SSLH_DATE_TO)
        OR (SYSDATE  BETWEEN SSLH_EXTEND1_DATE_FROM AND SSLH_EXTEND1_DATE_TO)
        OR (SYSDATE  BETWEEN SSLH_EXTEND2_DATE_FROM AND SSLH_EXTEND2_DATE_TO)
        OR (SYSDATE  BETWEEN SSLH_EXTEND3_DATE_FROM AND SSLH_EXTEND3_DATE_TO)
        OR SYSDATE  <= SSLH_MAX_DATE_TO
        OR SYSDATE  <= SSLH_REP_DUTY_DATE))
        AND SS_SERVICE_KUMPP = '$cp_scheme'
        AND SM_STAFF_ID NOT IN (SELECT SCH_STAFF_ID FROM STAFF_CPD_HEAD WHERE SCH_TAHUN = '$cp_year' AND SCH_KUMP = '$cp_scheme' AND SCH_STAFF_ID = SM_STAFF_ID)";

        $q = $this->db->query($query);
        return $q->result();
    }

    // INSERT GENERATE STAFF CPD HEAD
    public function insStaffCpdHead($cp_scheme, $cp_year, $sid, $class_code, $dept_code, $aca, $cp_cpd_layak, $cp_cpd_khusus_min, $cp_cpd_umum_min, $cp_cpd_teras_min, $cp_lnpt_weightage)
    {
        $curDate = 'SYSDATE';
        $curUsr = $this->staff_id;

        $data = array(
            "SCH_TAHUN" => $cp_year,
            "SCH_STAFF_ID" => $sid, 
            "SCH_SCHEME" => $class_code, 
            "SCH_KUMP" => $cp_scheme, 
            "SCH_CPD_LAYAK" => $cp_cpd_layak, 
            "SCH_JUM_KHUSUS" => 0, 
            "SCH_JUM_UMUM" => 0,
            "SCH_JUM_TERAS" => 0, 
            "SCH_JUM_CPD" => 0, 
            "SCH_JUM_KHUSUS_MIN" => $cp_cpd_khusus_min, 
            "SCH_JUM_UMUM_MIN" => $cp_cpd_umum_min, 
            "SCH_JUM_TERAS_MIN" => $cp_cpd_teras_min,      
            "SCH_PEMBERAT_LPP" => $cp_lnpt_weightage, 
            "SCH_PERATUS_LPP" => 0, 
            "SCH_CREATE_BY" => $curUsr, 
            // "SCH_CREATE_DATE" => sysdate, 
            "SCH_DEPT_CODE" => $dept_code, 
            "SCH_PRORATE_SERVICE" => 12, 
            "SCH_ACADEMIC" => $aca
        );

        $this->db->set("SCH_CREATE_DATE", $curDate, false);

        return $this->db->insert("STAFF_CPD_HEAD", $data);
    }

    // GET CPD STAFF HEAD
    public function getUpdCpdStaff($cp_scheme, $cp_year)
    {  
        $this->db->select("SCH_STAFF_ID, SCH_PRORATE_SERVICE");
        $this->db->from("STAFF_CPD_HEAD");
        $this->db->where("SCH_TAHUN", $cp_year);
        $this->db->where("SCH_KUMP", $cp_scheme);
        $q = $this->db->get();
    
        return $q->result();
    }

    // UPDATE STAFF CPD HEAD
    public function updStaffCpdHead($cp_scheme, $cp_year, $sid, $sch_jum_khusus_min, $sch_jum_umum_min, $sch_jum_teras_min, $sch_pemberat_lpp)
    {
        $data = array(
            "SCH_JUM_KHUSUS_MIN" => $sch_jum_khusus_min,
            "SCH_JUM_UMUM_MIN" => $sch_jum_umum_min, 
            "SCH_JUM_TERAS_MIN" => $sch_jum_teras_min, 
            "SCH_PEMBERAT_LPP" => $sch_pemberat_lpp
        );

        $this->db->where("SCH_TAHUN", $cp_year);
        $this->db->where("SCH_KUMP", $cp_scheme);
        $this->db->where("SCH_STAFF_ID", $sid);

        return $this->db->update("STAFF_CPD_HEAD", $data);
    }

    /*===============================================================
       CONFERENCE SETUP - CPD (ATF097)
    ================================================================*/

    // GET LEVEL DROPDOWN
    public function getLevelList() {
        $this->db->select("TL_CODE, TL_DESC, TL_CODE||' - '||TL_DESC AS TL_CODE_DESC");
        $this->db->from("TRAINING_LEVEL");
        $this->db->order_by("TL_CODE");
        $q = $this->db->get();
                
        return $q->result();
    } 

    // SAVE CONFERENCE DETL CPD
    public function saveConDetlCpd($form)
    {
        $data = array(
            "CM_LEVEL" => $form['level']
        );

        $this->db->where("CM_REFID", $form['refid']);

        return $this->db->update("CONFERENCE_MAIN", $data);
    }

    // CONFERENCE CPD SETUP BTN
    public function getCrCpdDetl($refid)
    {  
        $this->db->select("*");
        $this->db->from("CPD_HEAD");
        $this->db->where("CH_TRAINING_REFID", $refid);
        $q = $this->db->get();
    
        return $q->row();
    }

    // SAVE UPD CONFERENCE CPD SETUP
    public function saveConCpdSetup($form)
    {
        if(empty($form['report_submission'])) {
            $form['report_submission'] = 'N';
        }

        if(empty($form['compulsory'])) {
            $form['compulsory'] = 'N';
        }

        $data = array(
            "CH_COMPETENCY" => $form['competency'],
            "CH_CATEGORY" => $form['category'],
            "CH_MARK" => $form['mark'],
            "CH_REPORT_SUBMISSION" => $form['report_submission'],
            "CH_COMPULSORY" => $form['compulsory'],
        );

        $this->db->where("CH_TRAINING_REFID", $form['refid']);

        return $this->db->update("CPD_HEAD", $data);
    }

    // SAVE INS CONFERENCE CPD SETUP
    public function insConCpdSetup($form)
    {
        if(empty($form['report_submission'])) {
            $form['report_submission'] = 'N';
        }

        if(empty($form['compulsory'])) {
            $form['compulsory'] = 'N';
        }

        $data = array(
            "CH_TRAINING_REFID" => $form['refid'],
            "CH_COMPETENCY" => $form['competency'],
            "CH_CATEGORY" => $form['category'],
            "CH_MARK" => $form['mark'],
            "CH_REPORT_SUBMISSION" => $form['report_submission'],
            "CH_COMPULSORY" => $form['compulsory'],
        );

        return $this->db->insert("CPD_HEAD", $data);
    }

    // DELETE CONFERENCE CPD SETUP
    public function delConCpdSetup($refid) {
        $this->db->where("CH_TRAINING_REFID", $refid);
        return $this->db->delete("CPD_HEAD");
    }

    // STAFF CPD MARK LIST
    public function staffCpdMarkList($refid) { 

        $query = "SELECT SCM_REFID, SCM_STAFF_ID, SM_STAFF_NAME, SCM_PARTICIPANT_ROLE,
        CASE SCM_STATUS 
            WHEN 'APPLY' THEN 'Permohonan'
            WHEN 'VERIFY_TNCA' THEN 'Disokong'
            WHEN 'VERIFY_VC' THEN 'Diperakukan'
            WHEN 'APPROVE' THEN 'Diluluskan'
            WHEN 'REJECT' THEN 'Ditolak'
            ELSE 'Dibatalkan'
        END STATUS_PMP,

        (CASE  
        WHEN SCR_APPLY_DATE2 IS NULL THEN 
            (
                CASE SCM_STATUS
                    WHEN 'REJECT' THEN '(PMP Ditolak)'
                    WHEN 'CANCEL' THEN '(PMP Dibatalkan)'
                    ELSE '(Belum Hantar LMP)'
                END  
            )
        ELSE 
            (
                CASE SCR_STATUS
                    WHEN 'VERIFY_TNCA' THEN SCR_APPLY_DATE2||' (Telah diperakukan)'
                    ELSE SCR_APPLY_DATE2||' (Belum diperakukan)'
                END  
            )
        END) STATUS_LMP,
        SCM_CPD_MARK, SCM_CPD_COMPETENCY 
        FROM(
        SELECT SCM_REFID, SCM_STAFF_ID, SM_STAFF_NAME, SCM_PARTICIPANT_ROLE, SCM_STATUS,
        SCM_CPD_MARK, SCM_CPD_COMPETENCY
        FROM STAFF_CONFERENCE_MAIN
        LEFT JOIN STAFF_MAIN ON SCM_STAFF_ID = SM_STAFF_ID
        WHERE SCM_REFID = '$refid')
        LEFT JOIN (
        SELECT SCR_REFID, SCR_STAFF_ID, TO_CHAR(SCR_APPLY_DATE,'dd/mm/yyyy') AS SCR_APPLY_DATE2, SCR_STATUS
        FROM STAFF_CONFERENCE_REP 
        WHERE SCR_REFID = '$refid'
        ) ON SCM_REFID = SCR_REFID AND SCR_STAFF_ID = SCM_STAFF_ID";

        $q = $this->db->query($query);
        return $q->result();
    }

    // STAFF CPD LIST
    public function getStaffCrCpd($refid) { 

        $query = "SELECT SCM_STAFF_ID, SCM_REFID
        FROM STAFF_CONFERENCE_MAIN ,CONFERENCE_MAIN, STAFF_CONFERENCE_REP, CPD_HEAD
        WHERE SCM_REFID = CM_REFID
        AND SCM_STAFF_ID = SCR_STAFF_ID(+)
        AND SCM_REFID = SCR_REFID(+)
        AND CH_TRAINING_REFID = CM_REFID
        AND TO_CHAR(CM_DATE_TO,'yyyy') = TO_CHAR(SYSDATE,'YYYY')
        AND SCM_STATUS = 'APPROVE'
        AND ((CH_REPORT_SUBMISSION = 'N')
        OR (CH_REPORT_SUBMISSION='Y' AND SCR_STATUS = 'VERIFY_TNCA'))
        AND SCM_REFID = '$refid'";

        $q = $this->db->query($query);
        return $q->result();
    }

    // UPDATE GENERATE CPD
    public function generateCpd($refid2, $sid, $competency, $mark)
    {
        $data = array(
            "SCM_CPD_COMPETENCY" => $competency,
            "SCM_CPD_MARK" => $mark
        );

        $this->db->where("SCM_STAFF_ID", $sid);
        $this->db->where("SCM_REFID", $refid2);
        $this->db->where("SCM_CPD_MARK IS NULL");

        return $this->db->update("STAFF_CONFERENCE_MAIN", $data);
    }

    // CPD POINT INFO -postgres
    public function getCpdPointInfo($sid)
    {  
        $this->db->select("sch_jum_cpd, sch_cpd_layak, cp_lnpt_weightage, sch_jum_khusus_min, sch_jum_umum_min, sch_jum_khusus, sch_jum_umum, sch_jum_teras_min, sch_jum_teras, cp_umum_mandatory, sch_prorate_service");
        $this->db->from("ims_hris.staff_cpd_head, ims_hris.cpd_point");
        $this->db->where("sch_tahun = cp_year");
        $this->db->where("sch_kump = cp_scheme");
        $this->db->where("sch_tahun = to_char(current_date,'YYYY')");
        $this->db->where("sch_staff_id", $sid);
        // $this->db->where("SCH_STAFF_ID = 'K02284'");
        $q = $this->db->get();
    
        return $q->row_case('UPPER');
    }

    // JKHU/JUMUM/JTERAS -postgres
    public function getTtlReqCpd($sid, $sys_yyyy, $comp) {
		$req_cpd = null;
		
		// $sql = oci_parse($this->db->conn_id, "begin :bindOutput1 := CPD.total_required_cpd(:bind1,:bind2,:bind3); end;");
		// oci_bind_by_name($sql, ":bind1", $sid, 10);	        //IN
		// oci_bind_by_name($sql, ":bind2", $sys_yyyy, 4);			//IN
		// oci_bind_by_name($sql, ":bind3", $comp, 6);			//IN
		// oci_bind_by_name($sql, ":bindOutput1", $req_cpd, 6);				//OUT
		// oci_execute($sql, OCI_DEFAULT); 
		
        // $data = array(
        //     'REQ_CPD' => $req_cpd
        // );
		
        // return $data;	
        
        $sql = 'select cpd_total_required_cpd as req_cpd
                from ims_hris.cpd_total_required_cpd(?,?,?)';

        $q = $this->db->query($sql, array($sid, $sys_yyyy, $comp));
        return $q->row_case('UPPER');
    }

    // TOTAL CPD BY COMPETENCY
    public function getTtlCpdByCom($sid, $sys_yyyy, $comp) {
		$total = null;
		
		// $sql = oci_parse($this->db->conn_id, "begin :bindOutput1 := CPD.total_cpd_by_competency(:bind1,:bind2,:bind3); end;");
		// oci_bind_by_name($sql, ":bind1", $sid, 10);	        //IN
		// oci_bind_by_name($sql, ":bind2", $sys_yyyy, 4);			//IN
		// oci_bind_by_name($sql, ":bind3", $comp, 6);			//IN
		// oci_bind_by_name($sql, ":bindOutput1", $total, 6);				//OUT
		// oci_execute($sql, OCI_DEFAULT); 
		
        // $data = array(
        //     'TTL_CPD' => $total
        // );
		
        // return $data;	
        
        $sql = 'select cpd_total_cpd_by_competency as ttl_cpd
                from ims_hris.cpd_total_cpd_by_competency(?,?,?)';

        $q = $this->db->query($sql, array($sid, $sys_yyyy, $comp));
        return $q->row_case('UPPER');
    }

    // UPDATE LNPT INFO -postgres
    public function updLnptInfo($sid, $jkhu, $jumum, $jteras, $jum_cpd, $lnptweightage, $res, $sys_yyyy)
    {
        if(empty($jkhu)) {
            $jkhu = 0;
        }

        if(empty($jumum)) {
            $jumum = 0;
        }

        if(empty($jteras)) {
            $jteras = 0;
        }

        if(empty($jum_cpd)) {
            $jum_cpd = 0;
        }

        $res2 = round($res, 2);

        $data = array(
            "sch_jum_khusus" => round($jkhu, 2),
            "sch_jum_umum" => round($jumum, 2),
            "sch_jum_teras" => round($jteras, 2),
            "sch_jum_cpd" => round($jum_cpd, 2),
            "sch_pemberat_lpp" => round($lnptweightage, 2),
            "sch_peratus_lpp" => round($res2, 2),
        );

        $this->db->where("sch_staff_id", $sid);
        $this->db->where("sch_tahun", $sys_yyyy);

        return $this->db->update("ims_hris.staff_cpd_head", $data);
    }

    // STAFF CPD DETL
    public function getStaffCpdDetl($refid, $staff_id)
    {  
        $this->db->select("SCM_REFID, SCM_STAFF_ID, SCM_CPD_MARK, SCM_CPD_COMPETENCY");
        $this->db->from("STAFF_CONFERENCE_MAIN");
        $this->db->where("SCM_REFID", $refid);
        $this->db->where("SCM_STAFF_ID", $staff_id);
        $q = $this->db->get();
    
        return $q->row();
    }

    // SAVE CPD INFO STAFF
    public function saveStaffUpdateCpd($form)
    {
        $data = array(
            "SCM_CPD_COMPETENCY" => $form['competency'],
            "SCM_CPD_MARK" => $form['mark']
        );

        $this->db->where("SCM_STAFF_ID", $form['staff_id']);
        $this->db->where("SCM_REFID", $form['refid']);

        return $this->db->update("STAFF_CONFERENCE_MAIN", $data);
    }

    /*===============================================================
       STAFF CPD SETUP (ATF122)
    ================================================================*/

    // GET HRD DEPT
    public function getHrdDept()
    {  
        $this->db->select("HP_PARM_DESC");
        $this->db->from("HRADMIN_PARMS");
        $this->db->where("HP_PARM_CODE = 'HRD_DEPT_CODE'");
        $q = $this->db->get();
    
        return $q->row();
    }

    // POPULATE DEPT
    public function populateDept()
    {  
        $this->db->select("DM_DEPT_CODE, DM_DEPT_CODE||' - '||DM_DEPT_DESC AS DM_DEPT_CODE_DESC");
        $this->db->from("DEPARTMENT_MAIN");
        $this->db->where("DM_LEVEL IN (1,2)");
        $this->db->where("DM_STATUS = 'ACTIVE'");
        $this->db->order_by("DM_DEPT_CODE");
        $q = $this->db->get();
    
        return $q->result();
    }

    // POPULATE DEPT NEW
    public function populateDeptNew($deptCode)
    {  
        $this->db->select("DM_DEPT_CODE, DM_DEPT_CODE||' - '||DM_DEPT_DESC AS DM_DEPT_CODE_DESC");
        $this->db->from("DEPARTMENT_MAIN");
        if(!empty($deptCode)) {
            $this->db->where("DM_LEVEL IN (1,2)");
            $this->db->where("DM_DEPT_CODE", $deptCode);
        }
        $this->db->where("DM_STATUS = 'ACTIVE'");
        $this->db->order_by("DM_DEPT_CODE");
        $q = $this->db->get();
    
        return $q->result();
    }

    // POPULATE DEPT 2 
    public function populateDept2($dept)
    {  
        $this->db->select("DM_DEPT_CODE, DM_DEPT_CODE||' - '||DM_DEPT_DESC AS DM_DEPT_CODE_DESC");
        $this->db->from("DEPARTMENT_MAIN");
        $this->db->where("DM_DEPT_CODE", $dept);
        $this->db->order_by("DM_DEPT_CODE");
        $q = $this->db->get();

        return $q->result();
    }

    // CURREMT USER DEPT
    public function currentUsrDept()
    {  
        $curr_usr = $this->username;

        $this->db->select("SM_DEPT_CODE");
        $this->db->from("STAFF_MAIN");
        $this->db->where("UPPER(SM_APPS_USERNAME)", $curr_usr);
        $q = $this->db->get();
    
        return $q->row();
    }

    // GET STAFF CPD LIST
    public function getStaffCpdSetupList($year = null, $department = null, $idName = null)
    {  
        // var_dump($idName);
        $this->db->select("SCH_STAFF_ID, SM_STAFF_NAME, SCH_JUM_KHUSUS_MIN, SCH_JUM_UMUM_MIN, SCH_JUM_TERAS_MIN, SCH_CPD_LAYAK,
        SCH_JUM_KHUSUS, SCH_JUM_UMUM, SCH_JUM_TERAS, SCH_JUM_CPD, SCH_PEMBERAT_LPP, SCH_PERATUS_LPP");
        $this->db->from("STAFF_CPD_HEAD");
        $this->db->join("STAFF_MAIN", "SCH_STAFF_ID = SM_STAFF_ID", "LEFT");

        if(!empty($year)) {
            $this->db->where("SCH_TAHUN", $year);
        }

        if(!empty($department)) {
            $this->db->where("SCH_DEPT_CODE", $department);
        }

        if(!empty($idName)) {
            $this->db->where("SCH_STAFF_ID = '$idName' OR SM_STAFF_NAME LIKE '%$idName%'");
        }
        
        $this->db->order_by("SCH_STAFF_ID");
        $q = $this->db->get();
    
        return $q->result();
    }

    // GET STAFF CPD POINT DETL
    public function getStaffCpdPointDetl($staff_id, $selc_date)
    {  
        // var_dump($idName);
        $this->db->select("SCH_STAFF_ID, SM_STAFF_NAME, 
        SCH_PRORATE_SERVICE, SCH_CPD_LAYAK, SCH_JUM_KHUSUS_MIN, SCH_JUM_UMUM_MIN, SCH_JUM_TERAS_MIN,
        SCH_JUM_KHUSUS, SCH_JUM_UMUM, SCH_JUM_TERAS, SCH_JUM_CPD, SCH_PEMBERAT_LPP, SCH_PERATUS_LPP,
        SCH_SCHEME, SC_CLASS_DESC, SCH_KUMP, SOG_GROUP_DESC");
        $this->db->from("STAFF_CPD_HEAD");
        $this->db->join("STAFF_MAIN", "SCH_STAFF_ID = SM_STAFF_ID", "LEFT");
        $this->db->join("SERVICE_CLASSIFICATION", "SCH_SCHEME = SC_CLASS_CODE", "LEFT");
        $this->db->join("SERVICE_ORG_GROUP", "SCH_KUMP = SOG_GROUP_CODE", "LEFT");
        $this->db->where("SCH_TAHUN", $selc_date);
        $this->db->where("SCH_STAFF_ID", $staff_id);

        $q = $this->db->get();
    
        return $q->row();
    }

    // SAVE UPDATE STAFF CPD POINT
    public function saveUpdStfCpdPoint($form)
    {
        if(empty($form['prorate_service_month'])) {
            $form['prorate_service_month'] = 0;
        }

        if(empty($form['jumlah_khusus_min'])) {
            $form['jumlah_khusus_min'] = 0;
        }

        if(empty($form['jumlah_umum_min'])) {
            $form['jumlah_umum_min'] = 0;
        }

        if(empty($form['jumlah_teras_min'])) {
            $form['jumlah_teras_min'] = 0;
        }

        $data = array(
            "SCH_PRORATE_SERVICE" => $form['prorate_service_month'],
            "SCH_CPD_LAYAK" => $form['cpd_layak'],
            "SCH_JUM_KHUSUS_MIN" => $form['jumlah_khusus_min'],
            "SCH_JUM_UMUM_MIN" => $form['jumlah_umum_min'],
            "SCH_JUM_TERAS_MIN" => $form['jumlah_teras_min']
        );

        $this->db->where("SCH_STAFF_ID", $form['staff_id']);
        $this->db->where("SCH_TAHUN", $form['year']);

        return $this->db->update("STAFF_CPD_HEAD", $data);
    }

    // VIEW DETAIL
    public function getStaffCpdPointDetlList($staff_id, $year)
    {  
        $this->db->select("VCP_REFID, VCP_TITLE, VCP_MARK, VCP_COMPETENCY, VCP_TYPE, CH_COMPULSORY, 
        CASE CH_COMPULSORY
            WHEN 'Y' THEN 'Yes'
            WHEN 'N' THEN 'No'
        END AS CH_STATUS, 
        STD_ATTEND,
        CASE STD_ATTEND
            WHEN 'Y' THEN 'Yes'
            WHEN 'N' THEN 'No'
            WHEN 'A' THEN 'Yes (Auto)'
        END AS STD_ATTEND_DESC");
        $this->db->from("V_CPD_POINT");
        $this->db->join("CPD_HEAD", "VCP_REFID = CH_TRAINING_REFID", "LEFT");
        $this->db->where("VCP_STAFF_ID", $staff_id);
        $this->db->where("VCP_YEAR", $year);

        $q = $this->db->get();
    
        return $q->result();
    }

    // VIEW ACCU CPD & REQ CPD
    public function getReqAccuCpd($staff_id, $year)
    {  
        $this->db->select("nvl(SCH_JUM_KHUSUS||' / '||SCH_JUM_KHUSUS_MIN,0) KHU_MIN, nvl(SCH_JUM_UMUM||' / '||SCH_JUM_UMUM_MIN,0) UM_MIN, nvl(SCH_JUM_TERAS||' / '||SCH_JUM_TERAS_MIN,0) TR_MIN,
        cpd.total_cpd_by_competency('$staff_id', '$year', 'KHUSUS') COMP_KHU, cpd.total_cpd_by_competency('$staff_id', '$year', 'UMUM') COMP_UM, 
        cpd.total_cpd_by_competency('$staff_id', '$year', 'TERAS') COMP_TR");
        $this->db->from("STAFF_CPD_HEAD");
        $this->db->where("SCH_STAFF_ID", $staff_id);
        $this->db->where("SCH_TAHUN", $year);
        $q = $this->db->get();
    
        return $q->row();
    }

    /*===============================================================
       STAFF CPD MANUAL ENTRY (ATF149)
    ================================================================*/

     // LIST UNREGISTERED STAFF
    public function getUnregStaff($year = null, $department = null)
    {  
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_STAFF_STATUS, SS_STATUS_DESC, SM_STAFF_STATUS||' - '||SS_STATUS_DESC SM_STAFF_STATUS_DESC, SM_DEPT_CODE, SM_JOB_CODE, SS_SERVICE_DESC");
        $this->db->from("STAFF_MAIN");
        $this->db->join("SERVICE_SCHEME", "SS_SERVICE_CODE = SM_JOB_CODE", "LEFT");
        $this->db->join("STAFF_STATUS", "SS_STATUS_CODE = SM_STAFF_STATUS", "LEFT");
        $this->db->where("SM_DEPT_CODE", $department);
        $this->db->where("SM_RECORD_OWNER = 'BSM'");
        $this->db->where("SM_STAFF_ID LIKE 'K%'");
        $this->db->where("SM_STAFF_TYPE = 'STAFF'");
        $this->db->where("SM_STAFF_ID  IN ( SELECT SM_STAFF_ID FROM STAFF_MAIN, STAFF_STATUS
        WHERE SM_STAFF_STATUS = SS_STATUS_CODE
        AND SS_STATUS_STS = 'ACTIVE')");

        if(!empty($year) && !empty($department)) {
            $this->db->where("SM_STAFF_ID NOT IN (SELECT SCH_STAFF_ID 
            FROM STAFF_CPD_HEAD WHERE SCH_TAHUN = '$year' AND SCH_DEPT_CODE = '$department')");
        }

        if(!empty($year) && empty($department)) {
            $this->db->where("SM_STAFF_ID NOT IN (SELECT SCH_STAFF_ID 
            FROM STAFF_CPD_HEAD WHERE SCH_TAHUN = '$year'");
        }

        if(empty($year) && !empty($department)) {
            $this->db->where("SM_STAFF_ID NOT IN (SELECT SCH_STAFF_ID 
            FROM STAFF_CPD_HEAD WHERE SCH_DEPT_CODE = '$department')");
        }

        // $this->db->where("AND SM_STAFF_ID NOT IN (SELECT SCH_STAFF_ID 
        // FROM STAFF_CPD_HEAD 
        // WHERE (:YEAR_YEAR IS NULL OR SCH_TAHUN = :YEAR_YEAR) 
        // AND (:DEPARTMENT IS NULL OR  SCH_DEPT_CODE = :DEPARTMENT))");
        
        $this->db->order_by("SM_STAFF_ID");
        $q = $this->db->get();
    
        return $q->result();
    }

    // STAFF INFO
    public function getStaffDetl($job_code)
    {  
        $this->db->select("SS_SERVICE_DESC, SS_CLASS_CODE, SS_SERVICE_KUMPP, SS_ACADEMIC");
        $this->db->from("SERVICE_SCHEME");
        $this->db->where("SS_SERVICE_CODE", $job_code);
        $q = $this->db->get();
    
        return $q->row();
    }

    // INSERT STAFF CPD MANUAL ENTRY
    public function insStaffCpdHeadManEnt($year, $staff_id, $scheme, $kumpp, $dept, $academic)
    {  
        $curUsrId = $this->staff_id;
        $curDate = "SYSDATE";

        $data = array(
            "SCH_TAHUN" => $year,
            "SCH_STAFF_ID" => $staff_id,
            "SCH_SCHEME" => $scheme,
            "SCH_KUMP" => $kumpp,
            "SCH_CREATE_BY" => $curUsrId,
            "SCH_DEPT_CODE" => $dept,
            "SCH_ACADEMIC" => $academic
        );

        $this->db->set("SCH_CREATE_DATE", $curDate, false);

        return $this->db->insert("STAFF_CPD_HEAD", $data);
    }

    // STAFF CPD HEAD DETL
    public function getStaffCpdHeadDetl($staff_id, $year)
    {  
        $this->db->select("SCH_TAHUN, SCH_STAFF_ID, SM_STAFF_NAME, SCH_SCHEME, SC_CLASS_DESC, 
        SCH_KUMP, SOG_GROUP_DESC, SCH_PRORATE_SERVICE, SCH_PEMBERAT_LPP, SCH_PERATUS_LPP,
        SCH_JUM_KHUSUS_MIN, SCH_JUM_UMUM_MIN, SCH_JUM_TERAS_MIN, SCH_CPD_LAYAK, 
        SCH_JUM_KHUSUS, SCH_JUM_UMUM, SCH_JUM_TERAS, SCH_JUM_CPD");
        $this->db->from("STAFF_CPD_HEAD");
        $this->db->join("STAFF_MAIN", "SCH_STAFF_ID = SM_STAFF_ID", "LEFT");
        $this->db->join("SERVICE_CLASSIFICATION", "SCH_SCHEME = SC_CLASS_CODE", "LEFT");
        $this->db->join("SERVICE_ORG_GROUP", "SCH_KUMP = SOG_GROUP_CODE", "LEFT");
        $this->db->where("SCH_TAHUN", $year);
        $this->db->where("SCH_STAFF_ID", $staff_id);
        $q = $this->db->get();
    
        return $q->row();
    }

    // GET CP
    public function getCp($year, $kump)
    {  
        $this->db->select("CP_CPD_LAYAK, CP_CPD_KHUSUS_MIN, CP_CPD_UMUM_MIN, CP_CPD_TERAS_MIN, CP_LNPT_WEIGHTAGE");
        $this->db->from("CPD_POINT");
        $this->db->where("CP_YEAR", $year);
        $this->db->where("CP_SCHEME", $kump);
        $q = $this->db->get();
    
        return $q->row();
    }

    // GET TOTAL CPD MARK
    public function getSumCpdMark($year, $staff_id) { 

        $query = "SELECT SUM(STH_CPD_MARK) AS TTL_CPD_MARK
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('KHUSUS','UMUM')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL = 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('KHUSUS','UMUM','TERAS')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') < '2016'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD, STAFF_TRAINING_DETL
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('KHUSUS','UMUM','TERAS')
        AND STH_STATUS = 'APPROVE'
        AND STH_TRAINING_REFID = STD_TRAINING_REFID
        AND STH_STAFF_ID = STD_STAFF_ID
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND NVL(STD_ATTEND,'N') IN ('Y','A')
        AND TO_CHAR(TH_DATE_FROM,'yyyy') >= '2016'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(SCM_CPD_MARK)
        FROM STAFF_CONFERENCE_MAIN,CONFERENCE_MAIN,STAFF_CONFERENCE_REP, CPD_HEAD
        WHERE SCM_REFID=CM_REFID
        AND SCM_STAFF_ID = SCR_STAFF_ID(+)
        AND SCM_REFID = SCR_REFID(+)
        AND CH_TRAINING_REFID=CM_REFID
        AND SCM_STATUS = 'APPROVE'
        AND ((CH_REPORT_SUBMISSION = 'N')
        OR (CH_REPORT_SUBMISSION='Y' AND SCR_STATUS = 'VERIFY_TNCA'))
        AND SCM_CPD_COMPETENCY IN ('KHUSUS','UMUM','TERAS')
        AND TO_CHAR(CM_DATE_TO,'yyyy') = '$year'
        AND SCM_STAFF_ID = '$staff_id'";

        $q = $this->db->query($query);
        return $q->row();
    }

    // GET TOTAL CPD MARK 2
    public function getSumCpdMark2($year, $staff_id) { 

        $query = "SELECT SUM(STH_CPD_MARK) AS TTL_CPD_MARK2
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('KHUSUS')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL = 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('KHUSUS')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') < '2016'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD, STAFF_TRAINING_DETL
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('KHUSUS')
        AND STH_STATUS = 'APPROVE'
        AND STH_TRAINING_REFID = STD_TRAINING_REFID
        AND STH_STAFF_ID = STD_STAFF_ID
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND NVL(STD_ATTEND,'N') IN ('Y','A')
        AND TO_CHAR(TH_DATE_FROM,'yyyy') >= '2016'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(SCM_CPD_MARK)
        FROM STAFF_CONFERENCE_MAIN,CONFERENCE_MAIN,STAFF_CONFERENCE_REP, CPD_HEAD
        WHERE SCM_REFID=CM_REFID
        AND SCM_STAFF_ID = SCR_STAFF_ID(+)
        AND SCM_REFID = SCR_REFID(+)
        AND CH_TRAINING_REFID=CM_REFID
        AND SCM_STATUS = 'APPROVE'
        AND ((CH_REPORT_SUBMISSION = 'N')
        OR (CH_REPORT_SUBMISSION='Y' AND SCR_STATUS = 'VERIFY_TNCA'))
        AND SCM_CPD_COMPETENCY IN ('KHUSUS')
        AND TO_CHAR(CM_DATE_TO,'yyyy') = '$year'
        AND SCM_STAFF_ID = '$staff_id'";

        $q = $this->db->query($query);
        return $q->row();
    }

    // GET TOTAL CPD MARK 3
    public function getSumCpdMark3($year, $staff_id) { 

        $query = "SELECT SUM(STH_CPD_MARK) TTL_CPD_MARK3
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('UMUM')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL = 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('UMUM')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') < '2016'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD, STAFF_TRAINING_DETL
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('UMUM')
        AND STH_STATUS = 'APPROVE'
        AND STH_TRAINING_REFID = STD_TRAINING_REFID
        AND STH_STAFF_ID = STD_STAFF_ID
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND NVL(STD_ATTEND,'N') IN ('Y','A')
        AND TO_CHAR(TH_DATE_FROM,'yyyy') >= '2016'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(SCM_CPD_MARK)
        FROM STAFF_CONFERENCE_MAIN,CONFERENCE_MAIN,STAFF_CONFERENCE_REP, CPD_HEAD
        WHERE SCM_REFID=CM_REFID
        AND SCM_STAFF_ID = SCR_STAFF_ID(+)
        AND SCM_REFID = SCR_REFID(+)
        AND CH_TRAINING_REFID=CM_REFID
        AND SCM_STATUS = 'APPROVE'
        AND ((CH_REPORT_SUBMISSION = 'N')
        OR (CH_REPORT_SUBMISSION='Y' AND SCR_STATUS = 'VERIFY_TNCA'))
        AND SCM_CPD_COMPETENCY IN ('UMUM')
        AND TO_CHAR(CM_DATE_TO,'yyyy') = '$year'
        AND SCM_STAFF_ID = '$staff_id'";

        $q = $this->db->query($query);
        return $q->row();
    }

    // GET TOTAL CPD MARK 4
    public function getSumCpdMark4($year, $staff_id) { 

        $query = "SELECT SUM(STH_CPD_MARK) AS TTL_CPD_MARK4
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('TERAS')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL = 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('TERAS')
        AND STH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND STH_STAFF_ID = '$staff_id'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') < '2016'
        UNION
        SELECT SUM(STH_CPD_MARK)
        FROM STAFF_TRAINING_HEAD, TRAINING_HEAD, STAFF_TRAINING_DETL
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_CPD_COMPETENCY IN ('TERAS')
        AND STH_STATUS = 'APPROVE'
        AND STH_TRAINING_REFID = STD_TRAINING_REFID
        AND STH_STAFF_ID = STD_STAFF_ID
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') = '$year'
        AND NVL(STD_ATTEND,'N') IN ('Y','A')
        AND TO_CHAR(TH_DATE_FROM,'yyyy') >= '2016'
        AND STH_STAFF_ID = '$staff_id'
        UNION
        SELECT SUM(SCM_CPD_MARK)
        FROM STAFF_CONFERENCE_MAIN,CONFERENCE_MAIN,STAFF_CONFERENCE_REP, CPD_HEAD
        WHERE SCM_REFID=CM_REFID
        AND SCM_STAFF_ID = SCR_STAFF_ID(+)
        AND SCM_REFID = SCR_REFID(+)
        AND CH_TRAINING_REFID=CM_REFID
        AND SCM_STATUS = 'APPROVE'
        AND ((CH_REPORT_SUBMISSION = 'N')
        OR (CH_REPORT_SUBMISSION='Y' AND SCR_STATUS = 'VERIFY_TNCA'))
        AND SCM_CPD_COMPETENCY IN ('TERAS')
        AND TO_CHAR(CM_DATE_TO,'yyyy') = '$year'
        AND SCM_STAFF_ID = '$staff_id'";

        $q = $this->db->query($query);
        return $q->row();
    }

    // SAVE UPDATE CPD INFO
    public function saveUpdCpdPointInfo($form)
    {  

        $data = array(
            "SCH_PRORATE_SERVICE" => $form['prorate_service'],
            "SCH_PEMBERAT_LPP" => $form['pemberat_lnpt'],
            "SCH_PERATUS_LPP" => $form['peratus_lnpt'],

            "SCH_JUM_KHUSUS_MIN" => $form['jumlah_khusus_min'],
            "SCH_JUM_UMUM_MIN" => $form['jumlah_umum_min'],
            "SCH_JUM_TERAS_MIN" => $form['jumlah_teras_min'],
            "SCH_CPD_LAYAK" => $form['cpd_layak'],

            "SCH_JUM_KHUSUS" => $form['jumlah_khusus'],
            "SCH_JUM_UMUM" => $form['jumlah_umum'],
            "SCH_JUM_TERAS" => $form['jumlah_teras'],
            "SCH_JUM_CPD" => $form['jumlah_cpd'],
        );

        $this->db->where("SCH_TAHUN", $form['year']);
        $this->db->where("SCH_STAFF_ID", $form['staff_id']);

        return $this->db->update("STAFF_CPD_HEAD", $data);
    }

    /*===============================================================
       UPDATE CPD INFO - ATF123
    ================================================================*/

    // TRAINING INFO -postgres
    public function getTrainingDetl($refid)
    {  
        $this->db->select("th_ref_id, th_training_title, th_training_code, 
        to_char(th_date_from, 'dd/mm/yyyy') as th_date_from, to_char(th_date_to, 'dd/mm/yyyy') as th_date_to, 
        ch_competency, ch_mark, 
        CASE thd_evaluation
        WHEN 'Y' THEN 'Yes'
        ELSE 'No'
        END AS thd_evaluation_desc, th_generate_cpd");
        $this->db->from("ims_hris.training_head");
        $this->db->join("ims_hris.cpd_head", "ch_training_refid = th_ref_id", "left");
        $this->db->join("ims_hris.training_head_detl", "thd_ref_id = th_ref_id", "left");
        $this->db->where("th_ref_id", $refid);
        $q = $this->db->get();
    
        return $q->row_case('UPPER');
    }

    // STAFF CPD MARK LIST -postgres
    public function getStaffTrCpd($refid) { 

        $query = "SELECT sth_staff_id, sm_staff_name, sm_dept_code, tpr_desc, sth_status, sth_cpd_mark, sth_cpd_competency, sth_hod_evaluation, CASE sth_hod_evaluation
        WHEN 'Y' THEN 'Yes'
        WHEN 'N' THEN 'No'
        END AS sth_hod_evaluation_desc
        FROM ims_hris.staff_training_head
        left join ims_hris.staff_main on sth_staff_id = sm_staff_id
        left join ims_hris.training_participant_role on tpr_code = sth_participant_role
        where sth_training_refid = '$refid'
        and sth_staff_id in 
        (
        select sth_staff_id
        from ims_hris.staff_training_head,ims_hris.training_head
        where sth_status = 'APPROVE'
        and sth_training_refid = th_ref_id
        and sth_training_refid = '$refid'
        and to_char(th_date_from,'yyyy') < '2016'
        and th_internal_external <> 'EXTERNAL_AGENCY'
        union
        select sth_staff_id
        from ims_hris.staff_training_head,ims_hris.training_head
        where sth_status = 'APPROVE'
        and sth_training_refid = th_ref_id
        and sth_training_refid = '$refid'
        and th_internal_external = 'EXTERNAL_AGENCY'
        union
        select std_staff_id 
        from ims_hris.staff_training_detl,ims_hris.training_head,ims_hris.staff_training_head
        where coalesce(std_attend,'N') IN ('Y','A')
        and std_training_refid = '$refid'
        and sth_status = 'APPROVE'
        and sth_training_refid = std_training_refid
        and sth_training_refid = th_ref_id
        and sth_staff_id = std_staff_id
        and TO_CHAR(th_date_from,'yyyy') >= '2016'
        and th_internal_external <> 'EXTERNAL_AGENCY'
        )
        order by sth_staff_id, upper(ims_hris.get_staff_dept(sth_staff_id)), sth_status, upper(ims_hris.get_staff_name(sth_staff_id))";

        $q = $this->db->query($query);
        return $q->result_case('UPPER');
    }

    // UPDATE TR CPD INFO STAFF -postgres
    public function updateTrCpd($refid, $staff_id)
    {  
        $this->db->select("sth_staff_id, sth_cpd_mark, sth_cpd_competency");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->where("sth_training_refid", $refid);
        $this->db->where("sth_staff_id", $staff_id);
        $q = $this->db->get();
    
        return $q->row_case('UPPER');
    }

    // EVALUATION START DATE -postgres
    public function getEvaStart()
    {  
        $this->db->select("hp_parm_desc, to_char(current_date, 'yyyy') curr_year");
        $this->db->from("ims_hris.hradmin_parms");
        $this->db->where("hp_parm_code = 'TRAINING_EVALUATION_STARTED'");
        $this->db->where("hp_parm_no = 1");
        $q = $this->db->get();
    
        return $q->row_case('UPPER');
    }

    // EVALUATION START DATE -postgres
    public function getCountTrDetl($refid)
    {  
        $this->db->select("count(1) count_g");
        $this->db->from("ims_hris.training_head_detl");
        $this->db->where("coalesce(thd_evaluation,'N') = 'Y'");
        $this->db->where("thd_ref_id", $refid);
        $q = $this->db->get();
    
        return $q->row_case('UPPER');
    }

    // EVALUATION START DATE -postgres
    public function getSthDetl($refid, $staff_id)
    {  
        $this->db->select("coalesce(sth_hod_evaluation,'N') sth_hod_evaluation");
        $this->db->from("ims_hris.staff_training_head");
        $this->db->where("sth_staff_id", $staff_id);
        $this->db->where("sth_training_refid", $refid);
        $q = $this->db->get();
    
        return $q->row_case('UPPER');
    }

    // SAVE CPD MARK INFO STAFF -postgress
    public function saveStaffUpdateCpdMark($form)
    {
        $data = array(
            // "sth_cpd_mark" => $form['mark'],
            "sth_cpd_competency" => $form['competency']
        );

        if(!empty($form['mark'])){
            $this->db->set("sth_cpd_mark", $form['mark'], false);
        } else {
            $this->db->set("sth_cpd_mark", NULL, true);
        }

        $this->db->where("sth_staff_id", $form['staff_id']);
        $this->db->where("sth_training_refid", $form['refid']);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // STAFF CPD MARK LIST -postgres
    public function getStaffGenMark($refid, $eva_start_dt) { 

        $query = "SELECT sth_staff_id
		FROM ims_hris.staff_training_head,ims_hris.training_head
		WHERE sth_status = 'APPROVE'
		AND sth_training_refid = th_ref_id
		AND STH_TRAINING_REFID = '$refid'
		AND TO_CHAR(th_date_from,'yyyy') < '2016'
		AND th_internal_external <> 'EXTERNAL_AGENCY'
		UNION
		SELECT sth_staff_id
		FROM ims_hris.staff_training_head,ims_hris.training_head
		WHERE sth_status = 'APPROVE'
		AND sth_training_refid = th_ref_id
		AND sth_training_refid = '$refid'
		AND th_internal_external = 'EXTERNAL_AGENCY'
		AND th_date_from < TO_DATE('$eva_start_dt', 'DD/MM/YYYY')
		UNION
		SELECT sth_staff_id
		FROM ims_hris.staff_training_head,ims_hris.training_head
		WHERE sth_status = 'APPROVE'
		AND sth_training_refid = th_ref_id
		AND sth_training_refid = '$refid'
		AND th_internal_external = 'EXTERNAL_AGENCY'
		AND th_date_from >= TO_DATE('$eva_start_dt', 'DD/MM/YYYY')
		AND coalesce(sth_hod_evaluation,'N') = 'Y'
		AND th_ref_id IN (SELECT thd_ref_id
		FROM ims_hris.TRAINING_HEAD_DETL
		WHERE coalesce(thd_evaluation,'N') = 'Y')
		UNION
		SELECT sth_staff_id
		FROM ims_hris.staff_training_head,ims_hris.training_head
		WHERE sth_status = 'APPROVE'
		AND sth_training_refid = th_ref_id
		AND sth_training_refid = '$refid'
		AND th_internal_external = 'EXTERNAL_AGENCY'
		AND th_date_from >= TO_DATE('$eva_start_dt', 'DD/MM/YYYY')
		AND th_ref_id NOT IN (SELECT thd_ref_id
		FROM ims_hris.training_head_detl
		WHERE coalesce(thd_evaluation,'N') = 'Y')
		UNION
		SELECT std_staff_id 
		FROM ims_hris.staff_training_detl,ims_hris.training_head,ims_hris.staff_training_head
		WHERE coalesce(std_attend,'N') IN ('Y','A')
		AND std_training_refid = '$refid'
		AND sth_status = 'APPROVE'
		AND sth_training_refid = std_training_refid
		AND sth_training_refid = th_ref_id
		AND sth_staff_id = std_staff_id
		AND (TO_CHAR(th_date_from,'yyyy') >= '2016' 
		AND TO_CHAR(th_date_from,'yyyy') < '2018')
		AND th_internal_external <> 'EXTERNAL_AGENCY'
		UNION
		SELECT std_staff_id 
		FROM ims_hris.staff_training_detl,ims_hris.training_head,ims_hris.staff_training_head
		WHERE coalesce(std_attend,'N') IN ('Y','A')
		AND std_training_refid = '$refid'
		AND sth_status = 'APPROVE'
		AND sth_training_refid = std_training_refid
		AND sth_training_refid = th_ref_id
		AND sth_staff_id = std_staff_id
		AND th_date_from >= TO_DATE('$eva_start_dt', 'DD/MM/YYYY')
		AND th_internal_external <> 'EXTERNAL_AGENCY'
		AND coalesce(sth_hod_evaluation,'N') = 'Y'
		AND th_ref_id IN (SELECT thd_ref_id
		FROM ims_hris.training_head_detl
		WHERE coalesce(thd_evaluation,'N') = 'Y')
		UNION
		SELECT std_staff_id 
		FROM ims_hris.staff_training_detl,ims_hris.training_head,ims_hris.staff_training_head
		WHERE coalesce(std_attend,'N') IN ('Y','A')
		AND std_training_refid = '$refid'
		AND sth_status = 'APPROVE'
		AND sth_training_refid = std_training_refid
		AND sth_training_refid = th_ref_id
		AND sth_staff_id = std_staff_id
		AND th_date_from >= TO_DATE('$eva_start_dt', 'DD/MM/YYYY')
		AND th_internal_external <> 'EXTERNAL_AGENCY'
		AND th_ref_id NOT IN (SELECT thd_ref_id
		FROM ims_hris.training_head_detl
		WHERE coalesce(thd_evaluation,'N') = 'Y')";

        $q = $this->db->query($query);
        return $q->result_case('UPPER');
    }

    // UPDATE GENERATE CPD MARK -postgres
    public function generateCpdMark($refid, $sid, $competency, $mark)
    {
        $data = array(
            "sth_cpd_competency" => $competency,
            "sth_cpd_mark" => $mark
        );

        $this->db->where("sth_staff_id", $sid);
        $this->db->where("sth_training_refid", $refid);

        return $this->db->update("ims_hris.staff_training_head", $data);
    }

    // UPDATE GENERATE CPD TRAINING HEAD -postgres
    public function updThGenCpd($refid)
    {
        $data = array(
            "th_generate_cpd" => 'Y',
        );

        $this->db->where("th_ref_id", $refid);

        return $this->db->update("ims_hris.training_head", $data);
    }

    /*===============================================================
       CPD REPORT SUBMISSION - ATF103
    ================================================================*/

    // GET CONFERENCE INFO LIST
    public function getTrainingList($month = null, $year = null, $refidTitle = null) 
    {		
        $this->db->select("TH_REF_ID, TH_TRAINING_CODE, TH_TRAINING_TITLE, TO_CHAR(TH_DATE_FROM, 'DD/MM/YYYY') AS TH_DATE_FROM2, TO_CHAR(TH_DATE_TO, 'DD/MM/YYYY') AS TH_DATE_TO2, TH_DATE_FROM, TH_DATE_TO");
        $this->db->from("TRAINING_HEAD");
        $this->db->where("TH_STATUS = 'APPROVE'");
        $this->db->where("TH_REF_ID IN (SELECT CH_TRAINING_REFID FROM CPD_HEAD WHERE NVL(CH_REPORT_SUBMISSION,'N') = 'Y')");

        if(!empty($month) && empty($year)) {
            $this->db->where("TO_CHAR(TH_DATE_FROM, 'MM') = '$month'");
        } 
        elseif(!empty($year) && empty($month)) {
            $this->db->where("TO_CHAR(TH_DATE_FROM, 'YYYY') = '$year'");
        }   
        elseif(!empty($month) && !empty($year)) {
            $this->db->where("TO_CHAR(TH_DATE_FROM, 'MM') = '$month'");
            $this->db->where("TO_CHAR(TH_DATE_FROM, 'YYYY') = '$year'");
        }
        elseif(empty($month) && empty($year) && !empty($refidTitle)) {
            $this->db->where("(TH_REF_ID LIKE '%$refidTitle%' OR UPPER(TH_TRAINING_TITLE) LIKE UPPER('%$refidTitle%'))");
        }
        
        $this->db->order_by("TH_DATE_FROM, TH_DATE_TO, TH_TRAINING_TITLE");
        $q = $this->db->get();
                
        return $q->result();
    } 

    // TRAINING STAFF LIST
    public function getStaffTrainingList($refid = null, $status = null) 
    {		
        $this->db->select("STH_STAFF_ID, SM_STAFF_NAME, SM_DEPT_CODE, 
        STR_STATUS, 
        TO_CHAR(STR_APPLY_DATE, 'DD/MM/YYYY') AS STR_APPLY_DATE, 
        TO_CHAR(STR_RECOMMEND_DATE, 'DD/MM/YYYY') AS STR_RECOMMEND_DATE, 
        STH_CPD_REPORT, NVL(STH_CPD_REPORT,'N') AS STH_CPD_REPORT_APP,
        TO_CHAR(STH_CPD_REPORT_DATE, 'DD/MM/YYYY') AS STH_CPD_REPORT_DATE");
        $this->db->from("STAFF_TRAINING_HEAD");
        $this->db->join("STAFF_MAIN", "SM_STAFF_ID = STH_STAFF_ID", "LEFT");
        $this->db->join("STAFF_TRAINING_REPORT", "(STR_TRAINING_REFID = STH_TRAINING_REFID and STR_STAFF_ID = STH_STAFF_ID)", "LEFT");
        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID IN (SELECT STH_STAFF_ID
        FROM TRAINING_HEAD,STAFF_TRAINING_HEAD
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_STATUS = 'APPROVE'
        AND TH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') < 2016
        AND TH_REF_ID = '$refid'
        UNION
        SELECT STH_STAFF_ID
        FROM TRAINING_HEAD,STAFF_TRAINING_HEAD,STAFF_TRAINING_DETL
        WHERE STH_TRAINING_REFID = TH_REF_ID
        AND STH_STATUS = 'APPROVE'
        AND TH_STATUS = 'APPROVE'
        AND TH_INTERNAL_EXTERNAL <> 'EXTERNAL_AGENCY'
        AND TO_CHAR(TH_DATE_FROM,'yyyy') >= 2016
        AND STH_TRAINING_REFID = STD_TRAINING_REFID
        AND STH_STAFF_ID = STD_STAFF_ID
        AND STD_ATTEND IN ('Y','A')
        AND TH_REF_ID = '$refid') 
        
        AND ('$status' IS NULL 
        OR ('$status' IS NOT NULL 
        AND ( ( (
        '$status' = 'N' 
        AND NVL(STH_CPD_REPORT,'N') = 'N' 
        AND (
        SELECT NVL(COUNT(1),0) 
        FROM STAFF_TRAINING_REPORT 
        WHERE STR_TRAINING_REFID = STH_TRAINING_REFID
        AND STR_STAFF_ID = STH_STAFF_ID) = 0 ) ) 
        OR ( (
        '$status' <> 'N' 
        AND (STH_TRAINING_REFID,STH_STAFF_ID) IN
        (SELECT STR_TRAINING_REFID,STR_STAFF_ID
        FROM STAFF_TRAINING_REPORT
        WHERE STR_STATUS = '$status') ) OR (
        '$status' <> 'N' AND 
        (DECODE(STH_CPD_REPORT,'Y','APPROVE','N') = '$status' AND 
        (SELECT NVL(COUNT(1),0) FROM STAFF_TRAINING_REPORT WHERE STR_TRAINING_REFID = STH_TRAINING_REFID
        AND STR_STAFF_ID = STH_STAFF_ID) = 0 ) ) ) ) ) )");
        
        $this->db->order_by("STH_STAFF_ID");
        $q = $this->db->get();
                
        return $q->result();
    } 

    // VALIDATE PRINT REPORT
    public function validateReportSub($refid, $staff_id)
    {  
        $this->db->select("NVL(COUNT(STR_STAFF_ID),0) AS COUNT_REP");
        $this->db->from("STAFF_TRAINING_REPORT");
        $this->db->where("STR_TRAINING_REFID", $refid);
        $this->db->where("STR_STAFF_ID", $staff_id);
        $q = $this->db->get();
    
        return $q->row();
    }

    // REPORT SUB DETL
    public function getReportSubDetl($staff_id, $refid)
    {  
        $this->db->select("TO_CHAR(STR_APPLY_DATE, 'DD/MM/YYYY') AS STR_APPLY_DATE, 
        STR_OBJECTIVE, STR_STAFF_BENEFIT, 
        STR_DEPT_BENEFIT, STR_RECOMMEND_BY, SM.SM_STAFF_NAME AS STR_RECOMMEND_NAME, 
        TO_CHAR(STR_RECOMMEND_DATE, 'DD/MM/YYYY') AS STR_RECOMMEND_DATE, 
        STR_APPROVE_BY, SM2.SM_STAFF_NAME AS STR_APPROVE_NAME, 
        TO_CHAR(STR_APPROVE_DATE, 'DD/MM/YYYY') AS STR_APPROVE_DATE");
        $this->db->from("STAFF_TRAINING_REPORT");
        $this->db->join("STAFF_MAIN SM", "SM.SM_STAFF_ID = STR_RECOMMEND_BY", "LEFT");
        $this->db->join("STAFF_MAIN SM2", "SM2.SM_STAFF_ID = STR_APPROVE_BY", "LEFT");
        $this->db->where("STR_TRAINING_REFID", $refid);
        $this->db->where("STR_STAFF_ID", $staff_id);
        $q = $this->db->get();
    
        return $q->row();
    }

    // REPORT SUB DETL 2
    public function getReportSubDetl2($staff_id, $refid)
    {  
        $this->db->select("STRP_SEQ,
        STRP_TASK,
        STRP_IMPLEMENTATION,
        STRP_EFFECT");
        $this->db->from("STAFF_TRAINING_REP_PT1");
        $this->db->where("STRP_TRAINING_REFID", $refid);
        $this->db->where("STRP_STAFF_ID", $staff_id);
        $q = $this->db->get();
    
        return $q->result();
    }

    // UPDATE STH APPROVE
    public function updAppSth($refid, $staff_id, $sth_cpd_report, $sth_cpd_report_date)
    {
        $data = array(
            "STH_CPD_REPORT" => $sth_cpd_report,
        );

        if(!empty($sth_cpd_report_date)) {
            $date = "TO_DATE('$sth_cpd_report_date', 'DD/MM/YYYY')";
            $this->db->set("STH_CPD_REPORT_DATE", $date, false);
        }

        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID", $staff_id);

        return $this->db->update("STAFF_TRAINING_HEAD", $data);
    }

    // UPDATE STR APPROVE
    public function updAppStr($refid, $staff_id)
    {
        $curUsrId = $this->staff_id;
        $date = "TRUNC(SYSDATE)";

        $data = array(
            "STR_STATUS" => 'APPROVE',
            "STR_APPROVE_BY" => $curUsrId,
        );

        $this->db->set("STR_APPROVE_DATE", $date, false);

        $this->db->where("STR_TRAINING_REFID", $refid);
        $this->db->where("STR_STAFF_ID", $staff_id);

        return $this->db->update("STAFF_TRAINING_REPORT", $data);
    }

    // TRAINING STAFF DETL
    public function getStaffTrainingDetl($refid, $staff_id) 
    {		
        $this->db->select("STH_STAFF_ID, SM_STAFF_NAME, SM_DEPT_CODE, 
        STR_STATUS, 
        TO_CHAR(STR_APPLY_DATE, 'DD/MM/YYYY') AS STR_APPLY_DATE, 
        TO_CHAR(STR_RECOMMEND_DATE, 'DD/MM/YYYY') AS STR_RECOMMEND_DATE, 
        STH_CPD_REPORT, NVL(STH_CPD_REPORT,'N') AS STH_CPD_REPORT_APP,
        TO_CHAR(STH_CPD_REPORT_DATE, 'DD/MM/YYYY') AS STH_CPD_REPORT_DATE");
        $this->db->from("STAFF_TRAINING_HEAD");
        $this->db->join("STAFF_MAIN", "SM_STAFF_ID = STH_STAFF_ID", "LEFT");
        $this->db->join("STAFF_TRAINING_REPORT", "(STR_TRAINING_REFID = STH_TRAINING_REFID and STR_STAFF_ID = STH_STAFF_ID)", "LEFT");
        $this->db->where("STH_TRAINING_REFID", $refid);
        $this->db->where("STH_STAFF_ID", $staff_id);

        $q = $this->db->get();
                
        return $q->row();
    } 

    /*===============================================================
       CPD REPORT (ATF136)
    ================================================================*/

    // CURRENT STAFF DEPT
    public function getStaffDept() 
    {		
        $curUsrId = $this->staff_id;
        $this->db->select("SM_DEPT_CODE");
        $this->db->from("STAFF_MAIN");
        $this->db->where("SM_STAFF_ID", $curUsrId);

        $q = $this->db->get();
                
        return $q->row();
    }

    // PTJ DD LIST
    public function getPtjList($is_hr_staff) 
    {		
        $curUsrName = $this->username;

        $this->db->select("DM_DEPT_CODE, DM_DEPT_DESC, 
        DM_DEPT_CODE||' - '||DM_DEPT_DESC AS DM_DEPT_CD");
        $this->db->from("DEPARTMENT_MAIN");
        $this->db->where("DM_LEVEL <= 2");
        $this->db->where("(('$is_hr_staff'='Y')
        OR ('$is_hr_staff'='N' AND DM_DEPT_CODE IN ((SELECT CUL_ROLE_DEPT1
        FROM CPD_USER_LEVEL,STAFF_MAIN 
        WHERE CUL_STAFF_ID = SM_STAFF_ID
        AND  UPPER(SM_APPS_USERNAME)=UPPER('$curUsrName')), (SELECT CUL_ROLE_DEPT3
        FROM CPD_USER_LEVEL,STAFF_MAIN 
        WHERE CUL_STAFF_ID = SM_STAFF_ID
        AND  UPPER(SM_APPS_USERNAME)=UPPER('$curUsrName')),(SELECT CUL_ROLE_DEPT2
        FROM CPD_USER_LEVEL,STAFF_MAIN 
        WHERE CUL_STAFF_ID = SM_STAFF_ID
        AND  UPPER(SM_APPS_USERNAME)=UPPER('$curUsrName')))))");
        $this->db->order_by("DM_DEPT_DESC");
        
        $q = $this->db->get();
                
        return $q->result();
    }

    // SEARCH STAFF
    public function getStaffSearch($staffID, $dept = null)
    {
        $this->db->select("SM_STAFF_ID, SM_STAFF_NAME, SM_STAFF_ID ||' - '||SM_STAFF_NAME AS SM_STAFF_ID_NAME");
        $this->db->from("STAFF_MAIN, STAFF_STATUS");
        $this->db->where("SS_STATUS_CODE = SM_STAFF_STATUS");
        $this->db->where("SM_STAFF_TYPE = 'STAFF'");
        $this->db->where("SS_STATUS_STS = 'ACTIVE'");

        $this->db->where("(UPPER(SM_STAFF_ID) LIKE UPPER('%$staffID%') OR UPPER(SM_STAFF_NAME) LIKE UPPER('%$staffID%'))");
        if(!empty($dept)) {
            $this->db->where("SM_DEPT_CODE", $dept);
        }
        $this->db->order_by("2");

        $q = $this->db->get();
        return $q->result();
    }

    // GET SECTOR LIST
    public function getSector() {
        $this->db->distinct();
        $this->db->select("SC_CLASS_SECTOR");
        $this->db->from("SERVICE_CLASSIFICATION");

        $q = $this->db->get();
        return $q->result();
    }

    // GET SCHEME LIST
    public function getScheme($sector) {
        $this->db->distinct();
        $this->db->select("SC_CLASS_CODE, SC_CLASS_DESC");
        $this->db->from("SERVICE_CLASSIFICATION");
        $this->db->where("SC_CLASS_SECTOR", $sector);

        $q = $this->db->get();
        return $q->result();
    }

    // GET SCHEME LIST
    public function getJobList($sector = null, $scheme = null, $year = null) {
        $this->db->distinct();
        $this->db->select("SS_SERVICE_CODE, SS_SERVICE_DESC, SS_SERVICE_CODE||' - '||SS_SERVICE_DESC SS_SERVICE_CODE_DC");
        $this->db->from("STAFF_CPD_HEAD, STAFF_MAIN, SERVICE_SCHEME, SERVICE_CLASSIFICATION");
        $this->db->where("SCH_STAFF_ID = SM_STAFF_ID");
        $this->db->where("SM_JOB_CODE = SS_SERVICE_CODE");
        $this->db->where("SS_CLASS_CODE = SC_CLASS_CODE");
        $this->db->where("SS_SERVICE_KUMPP NOT IN ('SPP','T','SSU')");

        if(!empty($year)) {
            $this->db->where("SCH_TAHUN", $year);
        }

        if(!empty($scheme)) {
            $this->db->where("SC_CLASS_CODE", $scheme);
        }

        if(!empty($sector)) {
            $this->db->where("SC_CLASS_SECTOR", $sector);
        }



        // and (:SECTOR is null or (:SECTOR is not null and sc_class_sector = :SECTOR))
        // and (:SCHEME is null or (:SCHEME is not null and sc_class_code = :SCHEME))
        // and ss_service_kumpp not in ('SPP','T','SSU')
        // and sch_tahun = :YEAR3
        // order by ss_service_desc;

        $this->db->order_by("SS_SERVICE_DESC");
        
        $q = $this->db->get();
        return $q->result();
    }

    // COUNT USER LVL
    public function getCntUsrLvl($role) 
    {
        $curUsrName = $this->username;

        $this->db->select("COUNT(1) AS COUNT_USR");
        $this->db->from("CPD_USER_LEVEL, STAFF_MAIN");
        $this->db->where("CUL_STAFF_ID = SM_STAFF_ID");
        $this->db->where("UPPER(SM_APPS_USERNAME) = UPPER('$curUsrName')");
        $this->db->where("CUL_ROLE", $role);

        $q = $this->db->get();
        return $q->row();
    }

    /*===============================================================
       UPDATE 09/09/2020
    ================================================================*/
    
    // GET CURRENT DEFAULT USER DEPARTMENT - STAFF MAIN -postgres
    public function getCurUserDept($staffID = null) 
    {

        $curUsername = $this->username;

        $this->db->select("sm_staff_id, sm_staff_name, sm_dept_code, sm_email_addr");
        $this->db->from("ims_hris.staff_main");



        if(empty($staffID)) {
            $this->db->where("sm_apps_username", $curUsername);
        } else {
            $this->db->where("sm_staff_id", $staffID);
        }
        
        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET CURRENT DEFAULT YEAR -postgres
    public function getCurYear() 
    {
        $this->db->select("to_char(current_date, 'YYYY') as cur_year");
        
        //$this->db->from("DUAL");
        
        $q = $this->db->get();
        return $q->row_case('UPPER');
    }

    // GET DEPARTMENT LIST -postgres
    public function getDeptList2() 
    {
        $this->db->select("dm_dept_code, dm_dept_desc, dm_dept_code ||' - '|| dm_dept_desc as dept_code_desc");
        $this->db->from('ims_hris.department_main');
		$this->db->where('coalesce(dm_status,\'INACTIVE\')', 'ACTIVE');
		$this->db->where('dm_level <= 2');
        $this->db->order_by('dm_dept_code');
        $q = $this->db->get();
		        
        return $q->result_case('UPPER');
    }

    // GET YEAR DROPDOWN -postgres
    public function getYearList() 
    {		
        $this->db->select("to_char(cm_date, 'YYYY') AS cm_year");
        $this->db->from("ims_hris.calendar_main");
		$this->db->where("to_char(cm_date, 'YYYY')::numeric >= to_char(current_date, 'YYYY')::numeric - 15");
        $this->db->group_by("to_char(cm_date, 'YYYY')");
        $this->db->order_by("to_char(cm_date, 'YYYY') DESC");
        $q = $this->db->get();
		        
        return $q->result_case('UPPER');
    } 

    // GET MONTH DROPDOWN -postgres
    public function getMonthList() 
    {		
        $this->db->select("to_char(cm_date, 'MM') AS cm_mm, to_char(cm_date, 'MONTH') AS cm_month");
        $this->db->from("ims_hris.calendar_main");
        $this->db->group_by("to_char(cm_date,'MM'), to_char(cm_date, 'MONTH')");
        $this->db->order_by("to_char(cm_date, 'MM')");
        $q = $this->db->get();
		        
        return $q->result_case('UPPER');
    } 

    // GET TRAINING HEAD BASED ON FILTER -postgres
    public function getTrainingList2($defIntExt = null, $curUsrDept = null, $defMonth = null, $curYear = null, $defTrSts = null, $evaluation = null, $screRpt = null)
    {
        if($screRpt == '1') {
            $this->db->select("th_ref_id, 
                                th_training_title, 
                                th_training_desc, 
                                th_type, 
                                th_status, 
                                th_internal_external,
                                th_level, 
                                th_training_venue, 
                                th_training_country, 
                                th_organizer_name, 
                                th_organizer_level, 
                                th_organizer_address,
                                th_organizer_postcode, 
                                th_organizer_city, 
                                th_organizer_state, 
                                th_organizer_country, 
                                th_sponsor, 
                                to_char(th_date_from, 'dd/mm/yyyy') as th_date_from,
                                to_char(th_date_to, 'dd/mm/yyyy') as th_date_to, 
                                th_total_hours, 
                                th_training_fee, 
                                to_char(th_apply_closing_date, 'dd-mm-yyyy') as th_app_closing_date, 
                                th_current_participant, 
                                th_max_participant,
                                th_open, 
                                th_dept_code, 
                                th_enter_by, 
                                th_enter_date, 
                                th_approve_by, 
                                th_approve_date, 
                                th_training_state, 
                                th_attendance_type,
                                th_print_certificate, 
                                th_evaluation_compulsory, 
                                th_service_group, 
                                th_category, 
                                to_char(th_evaluation_date_from, 'dd-mm-yyyy') as th_eva_date_from,
                                to_char(th_evaluation_date_to, 'dd-mm-yyyy') as th_eva_date_to, 
                                th_training_history, 
                                th_competency_code, 
                                th_training_code, 
                                th_offer, 
                                th_generate_cpd,
                                to_char(th_time_from, 'hh:mi am') as time_fr, 
                                to_char(th_time_to, 'hh:mi am') as time_t, 
                                to_char(th_confirm_date_from, 'dd-mm-yyyy') as th_con_date_from,
                                to_char(th_confirm_date_to, 'dd-mm-yyyy') as th_con_date_to, 
                                th_field,
                                tsr_refid
                            ");
            $this->db->from('ims_hris.training_head');
            $this->db->join("ims_hris.training_secretariat_report","th_ref_id = tsr_refid","left");
        } else {
            $this->db->select("th_ref_id, 
                                th_training_title, 
                                th_training_desc, 
                                th_type, 
                                th_status, 
                                th_internal_external,
                                th_level, 
                                th_training_venue, 
                                th_training_country, 
                                th_organizer_name, 
                                th_organizer_level, 
                                th_organizer_address,
                                th_organizer_postcode, 
                                th_organizer_city, 
                                th_organizer_state, 
                                th_organizer_country, 
                                th_sponsor, 
                                to_char(th_date_from, 'dd/mm/yyyy') as th_date_from,
                                to_char(th_date_to, 'dd/mm/yyyy') as th_date_to, 
                                th_total_hours, 
                                th_training_fee, 
                                to_char(th_apply_closing_date, 'dd-mm-yyyy') as th_app_closing_date, 
                                th_current_participant, 
                                th_max_participant,
                                th_open, 
                                th_dept_code, 
                                th_enter_by, 
                                th_enter_date, 
                                th_approve_by, 
                                th_approve_date, 
                                th_training_state, 
                                th_attendance_type,
                                th_print_certificate, 
                                th_evaluation_compulsory, 
                                th_service_group, 
                                th_category, 
                                to_char(th_evaluation_date_from, 'dd-mm-yyyy') as th_eva_date_from,
                                to_char(th_evaluation_date_to, 'dd-mm-yyyy') as th_eva_date_to, 
                                th_training_history, 
                                th_competency_code, 
                                th_training_code, 
                                th_offer, 
                                th_generate_cpd,
                                to_char(th_time_from, 'hh:mi am') as time_fr, 
                                to_char(th_time_to, 'hh:mi am') as time_t, 
                                to_char(th_confirm_date_from, 'dd-mm-yyyy') as th_con_date_from,
                                to_char(th_confirm_date_to, 'dd-mm-yyyy') as th_con_date_to, 
                                th_field
                            ");
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
            $this->db->where("th_internal_external not in ('EXTERNAL_AGENCY')");
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
        return $q->result_case('UPPER');
    }

    /*===============================================================
       UPDATE 23/02/2021
    ================================================================*/
    
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
}