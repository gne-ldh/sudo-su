
<?php

#**************************************************************************
#  openSIS is a free student information system for public and non-public 
#  colleges from Open Solutions for Education, Inc. web: www.os4ed.com
#
#  openSIS is  web-based, open source, and comes packed with features that 
#  include student demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more.
#  If you have question regarding this system or the license, please send 
#  an email to info@os4ed.com.
#
#  This program is released under the terms of the GNU General Public License as  
#  published by the Free Software Foundation, version 2 of the License. 
#  See license.txt.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#***************************************************************************************
/*
 * To change this template, choose tools | Templates
 * and open the template in the editor.
 */
//print_r($_REQUEST['values']);
if ($_REQUEST['teacher_view'] != 'y') {
    $sql_college_admin = 'SELECT ssr.COLLEGE_ID FROM colleges s,staff st INNER JOIN staff_college_relationship ssr USING(staff_id) WHERE s.id=ssr.college_id AND ssr.syear=' . UserSyear() . ' AND st.staff_id=' . User('STAFF_ID');
    $college_admin = DBGet(DBQuery($sql_college_admin));
    foreach ($college_admin as $index => $college) {
        if ($_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']]) {

            //$start_date = $_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['month_values']['START_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['year_values']['START_DATE'][$college['COLLEGE_ID']];
            //$check_start_date = $_REQUEST['year_values']['START_DATE'][$college['COLLEGE_ID']] . '-' . MonthFormatter(strtoupper($_REQUEST['month_values']['START_DATE'][$college['COLLEGE_ID']]), 1) . '-' . $_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']];
            $start_date = $_REQUEST['year_values']['START_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['month_values']['START_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']];
            $check_start_date = $_REQUEST['year_values']['START_DATE'][$college['COLLEGE_ID']] . '-' . $_REQUEST['month_values']['START_DATE'][$college['COLLEGE_ID']] . '-' . $_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']];
        } else {
            $start_date = '';
            $check_start_date = '';
        }
        if ($_REQUEST['day_values']['END_DATE'][$college['COLLEGE_ID']]) {
            $end_month = array("01" => "JAN", "02" => "FEB", "03" => "MAR", "04" => "APR", "05" => "MAY", "06" => "JUN", "07" => "JUL", "08" => "AUG", "09" => "SEP", "10" => "OCT", "11" => "NOV", "12" => "DEC");
            foreach ($end_month as $ei => $ed) {
                if ($ed == $_REQUEST['month_values']['END_DATE'][$college['COLLEGE_ID']])
                    $_REQUEST['month_values']['END_DATE'][$college['COLLEGE_ID']] = $ei;
            }

            //$end_date = $_REQUEST['day_values']['END_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['month_values']['END_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['year_values']['END_DATE'][$college['COLLEGE_ID']];
            $end_date = $_REQUEST['year_values']['END_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['month_values']['END_DATE'][$college['COLLEGE_ID']] . "-" . $_REQUEST['day_values']['END_DATE'][$college['COLLEGE_ID']];
        }
        else {
            $end_date = '';
        }
        if (($start_date != '' && VerifyDate(date('d-M-Y',strtotime($start_date)))) || ($end_date != '' && VerifyDate(date('d-M-Y',strtotime($end_date)))) || ($start_date == '' && $end_date == '')) {

 
            if (in_array($college['COLLEGE_ID'], $cur_college)) {
                $colleges_each_staff = DBGet(DBQuery('SELECT COLLEGE_ID,START_DATE,END_DATE FROM staff_college_relationship WHERE staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=' . $college['COLLEGE_ID']));
                if ($colleges_each_staff[1]['START_DATE'] == '')
                    DBQuery('UPDATE staff_college_relationship SET START_DATE=\'0000-00-00\' WHERE staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=' . $college['COLLEGE_ID']);

                $colleges_each_staff = DBGet(DBQuery('SELECT COLLEGE_ID,START_DATE,END_DATE FROM staff_college_relationship WHERE staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=' . $college['COLLEGE_ID']));
                $start = $colleges_each_staff[1]['START_DATE'];

                $colleges_start_date = DBGet(DBQuery('SELECT START_DATE FROM college_years WHERE COLLEGE_ID=' . $college['COLLEGE_ID'] . ' AND SYEAR=' . UserSyear()));
                $colleges_start_date = $colleges_start_date[1]['START_DATE'];

                // $colleges_each_staff[1]['START_DATE'] = date('d-m-Y', strtotime($colleges_each_staff[1]['START_DATE']));
                if ($colleges_each_staff[1]['START_DATE'] > $end_date && $end_date != '') {
                    $error = 'end_date';
                }

//       if($check_start_date!='' && strtotime($check_start_date)<strtotime($colleges_start_date))
//       {
//           $error='start_date_college_year';
//       }
                if (!empty($colleges_each_staff) && $start != '') {
                    $update = 'false';
                    unset($sql_up);
                    foreach ($_REQUEST['values']['COLLEGES'] as $index => $value) {
                        if($value!='Y' && $value!='N' && $value!='')
                        $value = 'Y';
                        if ($index == $college['COLLEGE_ID'] && $value == 'Y') {
                            $update = 'go';
                        }
                    }
                    ;
                    if ($update == 'go') {

                        if ($start_date != '' && $end_date != '' && $end_date != NULL) {
                            if (strtotime($start_date) <= strtotime($end_date))
                                $sql_up = 'UPDATE staff_college_relationship SET START_DATE=\'' . date('Y-m-d', strtotime($start_date)) . '\', END_DATE=\'' . date('Y-m-d', strtotime($end_date)) . '\' where staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=\'' . $college['COLLEGE_ID'] . '\'';
                            else
                                $error = 'end_date';
                        }
                        elseif ($start_date == '' && $end_date != '') {
                            if (isset($_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']]) && $_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']] == '') {
                                $error1 = 'start_date';
                            } else {
                                if (strtotime($colleges_each_staff[1]['START_DATE']) <= strtotime($end_date))
                                    $sql_up = 'UPDATE staff_college_relationship SET END_DATE=\'' . date('Y-m-d', strtotime($end_date)) . '\' where staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=\'' . $college['COLLEGE_ID'] . '\'';
                                else
                                    $error = 'end_date';
                            }
                        }
                        elseif ($start_date != '' && ($end_date == '' || $end_date == NULL) && strtotime($start) != strtotime($start_date)) {
                            if (strtotime($colleges_each_staff[1]['END_DATE']) >= strtotime($start_date) || $colleges_each_staff[1]['END_DATE'] == '0000-00-00' || $colleges_each_staff[1]['END_DATE'] == NULl) {
                                $cp_check = DBGet(DBQuery('SELECT * FROM course_periods WHERE SYEAR=' . UserSyear() . ' AND BEGIN_DATE <\'' . date('Y-m-d', strtotime($start_date)) . '\' AND (TEACHER_ID=' . $_REQUEST[staff_id] . ' OR SECONDARY_TEACHER_ID=' . $_REQUEST[staff_id] . ') AND COLLEGE_ID=\'' . $college['COLLEGE_ID'] . '\' '));

                                if ($cp_check[1]['COURSE_PERIOD_ID'] == '') {
                                    $sql_up = 'UPDATE staff_college_relationship SET START_DATE=\'' . date('Y-m-d', strtotime($start_date)) . '\' where staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=\'' . $college['COLLEGE_ID'] . '\'';
                                } else {
                                    $error = 'cp_association';
                                }
                            } else
                                $error = 'end_date';
                        }
                        elseif (isset($_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']]) && isset($_REQUEST['day_values']['END_DATE'][$college['COLLEGE_ID']]) && $_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']] == '' && $_REQUEST['day_values']['END_DATE'][$college['COLLEGE_ID']] == '') {

                            $sql_up = 'UPDATE staff_college_relationship SET START_DATE=\'0000-00-00\', END_DATE=\'0000-00-00\' where staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=\'' . $college['COLLEGE_ID'] . '\'';
                        } elseif (isset($_REQUEST['day_values']['END_DATE'][$college['COLLEGE_ID']]) && $_REQUEST['day_values']['END_DATE'][$college['COLLEGE_ID']] == '') {
                            $sql_up = 'UPDATE staff_college_relationship SET END_DATE=\'0000-00-00\' where staff_id=\'' . $_REQUEST[staff_id] . '\' AND syear=\'' . UserSyear() . '\' AND COLLEGE_ID=\'' . $college['COLLEGE_ID'] . '\'';
                        }

                        if (!$error && !$error1 && $sql_up != '') {

                            DBQuery($sql_up);
                        }
                    }
                } else {

                    $sql_up = 'INSERT INTO staff_college_relationship(staff_id,syear,college_id';
                    $sql_up_data = 'VALUES(\'' . $_REQUEST[staff_id] . '\',\'' . UserSyear() . '\',\'' . $college['COLLEGE_ID'] . '\'';

                    if ($start_date != '') {
                        $sql_up.=',start_date';
                    }
                    if ($end_date != '') {
                        if ($_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']] != '') {

                            $sql_up.=',end_date';
                        }
                    }
                    if ($start_date != '') {
                        $sql_up_data.=',\'' . date('Y-m-d', strtotime($start_date)) . '\'';
                    }
                    if ($end_date != '') {
                        if ($_REQUEST['day_values']['START_DATE'][$college['COLLEGE_ID']] != '')
                            $sql_up_data.=',\'' . date('Y-m-d', strtotime($end_date)) . '\'';
                    }
                    $sql_up.=')' . $sql_up_data . ')';

                    if ($start_date != '' && $end_date != '' && $end_date != NULL) {
                        if (strtotime($start_date) > strtotime($end_date))
                            $error = 'end_date';
                    }


                    if (!$error)
                        DBQuery($sql_up);
                }
            }
            else {
                $user_profile = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . $_REQUEST['staff_id'] . "'"));
                if ($user_profile[1]['PROFILE_ID'] != '' && count($cur_college)>0) {
                    $college_selected = implode(',', array_unique(array_keys($_REQUEST['values']['COLLEGES'])));
                    $del_qry.="DELETE FROM staff_college_relationship WHERE STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "'";
                    if ($college_selected != '')
                        $del_qry.=" AND COLLEGE_ID NOT IN (" . $college_selected . ")";

                    DBQuery($del_qry);

                    $del_qry = '';
                }
                
                
            }
        }
        else {
            $err = "<div class=\"alert bg-danger alert-styled-left\">The invalid date could not be saved.</div>";
        }
    }
    if ($error == 'end_date') {
        echo '<script type=text/javascript>document.getElementById(\'sh_err\').innerHTML=\'<b><font color=red>Start date can not be greater than end date</font></b>\';</script>';

        unset($error);
    }
    
//if($error=='start_date_college_year')
//{
//unset($error);
//echo "<script>window.location.href='Modules.php?modname=users/Staff.php&include=CollegesInfoInc&category_id=3&s_err=y'</script>";
////    echo 'Start date can not be before college\'s start date';
//
//}
    if ($error == 'cp_association') {
        echo '<script type=text/javascript>document.getElementById(\'sh_err\').innerHTML=\'<b><font color=red>Can not change the staff start date because it has association</font></b>\';</script>';

        unset($error);
    }
    if ($error1 == 'start_date') {
        echo '<script type=text/javascript>document.getElementById(\'sh_err\').innerHTML=\'<font color=red><b>Start date can not be blank</b></font>\';</script>';
        unset($error1);
    }
}

if ($_REQUEST['month_values']['JOINING_DATE'] && $_REQUEST['day_values']['JOINING_DATE'] && $_REQUEST['year_values']['JOINING_DATE']) {
    $_REQUEST['values']['COLLEGE']['JOINING_DATE'] = $_REQUEST['year_values']['JOINING_DATE'] . '-' . $_REQUEST['month_values']['JOINING_DATE'] . '-' . $_REQUEST['day_values']['JOINING_DATE'];
    $_REQUEST['values']['COLLEGE']['JOINING_DATE'] = date("Y-m-d", strtotime($_REQUEST['values']['COLLEGE']['JOINING_DATE']));
} elseif (isset($_REQUEST['month_values']['JOINING_DATE']) && isset($_REQUEST['day_values']['JOINING_DATE']) && isset($_REQUEST['year_values']['JOINING_DATE']))
    $_REQUEST['values']['COLLEGE']['JOINING_DATE'] = '';


if ($_REQUEST['month_values']['ENDING_DATE'] && $_REQUEST['day_values']['ENDING_DATE'] && $_REQUEST['year_values']['ENDING_DATE']) {
    $_REQUEST['values']['COLLEGE']['ENDING_DATE'] = $_REQUEST['year_values']['ENDING_DATE'] . '-' . $_REQUEST['month_values']['ENDING_DATE'] . '-' . $_REQUEST['day_values']['ENDING_DATE'];
    $_REQUEST['values']['COLLEGE']['ENDING_DATE'] = date("Y-m-d", strtotime($_REQUEST['values']['COLLEGE']['ENDING_DATE']));
} elseif (isset($_REQUEST['month_values']['ENDING_DATE']) && isset($_REQUEST['day_values']['ENDING_DATE']) && isset($_REQUEST['year_values']['ENDING_DATE']))
    $_REQUEST['values']['COLLEGE']['ENDING_DATE'] = '';

$end_date = $_REQUEST['values']['COLLEGE']['ENDING_DATE'];
unset($_REQUEST['values']['COLLEGE']['ENDING_DATE']);
$_REQUEST['values']['COLLEGE']['END_DATE'] = $end_date;

if ($_REQUEST['values']['COLLEGE_IDS']) {
    $_REQUEST['values']['COLLEGE']['COLLEGE_ACCESS'] = ',';
    foreach ($_REQUEST['values']['COLLEGE_IDS'] as $key => $val) {
        $_REQUEST['values']['COLLEGE']['COLLEGE_ACCESS'].=$key . ",";
    }
}

$select_RET = DBGet(DBQuery("SELECT STAFF_ID FROM staff_college_info where STAFF_ID='" . UserStaffID() . "'"));
$select = $select_RET[1]['STAFF_ID'];

$_REQUEST['staff_college']['PASSWORD'];
$password = md5($_REQUEST['staff_college']['PASSWORD']);
$sql = DBGet(DBQuery('SELECT PASSWORD FROM login_authentication WHERE PASSWORD=\'' . $password . '\'' . (UserStaffID() != '' ? ' AND USER_ID!=' . UserStaffID() . ' AND PROFILE_ID IN (SELECT id FROM user_profiles WHERE profile=\'teacher\')' : '')));
$number = count($sql);
if ($number != 0) {
    echo '<div class="alert bg-danger alert-styled-left">Invalid password</div>';
}


if ($_REQUEST['values']['COLLEGE']['OPENSIS_PROFILE'] == '1') {
    $college_id1 = DBGet(DBQuery("SELECT ID FROM colleges"));

    foreach ($college_id1 as $index => $val) {
        $colleges[] = $val['ID'];
    }

    $colleges = implode(",", $colleges);
    $_REQUEST['values']['COLLEGE']['COLLEGE_ACCESS'] = "," . $colleges . ",";
} else {
    foreach ($_REQUEST['values']['COLLEGES'] as $college => $val) {
        if ($val == 'Y') {
            $colleges[] = $college;
        }
    }
    $colleges = implode(",", $colleges);
    $_REQUEST['values']['COLLEGE']['COLLEGE_ACCESS'] = "," . $colleges . ",";
}

if ($select == '') {
//    print_r($_REQUEST);exit;
    if ($_REQUEST['values']['COLLEGE']['OPENSIS_ACCESS'] == 'Y') {
        $sql = "INSERT INTO staff_college_info ";
        $fields = 'STAFF_ID,';
        $values = "'" . UserStaffID() . "',";
        foreach ($_REQUEST['values']['COLLEGE'] as $column => $value) {


            if ($column == 'COLLEGE_ACCESS' && $value == ',,')
                $value = ',' . UserCollege() . ',';
            if ($value) {

                $fields .= $column . ',';
//                                      if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux')){
//                                                 $values .= "'".str_replace("'","\'",$value)."',";
//                                        }else
                $values .= "'" . singleQuoteReplace('', '', $value) . "',";
            }
            if ($column == 'OPENSIS_PROFILE' && $value == 0) {
                $fields .= $column . ',';
//                                      if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux')){
//                                                 $values .= "'".str_replace("'","\'",$value)."',";
//                                        }else
                $values .= "'" . singleQuoteReplace('', '', $value) . "',";
            }
        }
        $sql .= '(' . substr($fields, 0, -1) . ') values(' . substr($values, 0, -1) . ')';

        DBQuery($sql);
        $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_college_info where STAFF_ID='" . UserStaffID() . "'"));
        $update_staff = $update_staff_RET[1];
        $profile_name_RET = DBGet(DBQuery("SELECT PROFILE from user_profiles WHERE id=" . $update_staff['OPENSIS_PROFILE']));
        $profile = $profile_name_RET[1]['PROFILE'];
        $staff_CHECK = DBGet(DBQuery("SELECT  s.*,la.*  FROM staff s,login_authentication la where s.STAFF_ID='" . UserStaffID() . "' AND la.PROFILE_ID NOT IN (3,4) AND la.USER_ID=s.STAFF_ID"));
        $staff = $staff_CHECK[1];
        $sql_staff = "UPDATE staff SET ";

        if ($_REQUEST['staff_college']['CURRENT_COLLEGE_ID'])
            $sql_staff.="PROFILE_ID='" . $update_staff['OPENSIS_PROFILE'] . "',PROFILE='" . $profile . "',CURRENT_COLLEGE_ID='" . $_REQUEST['staff_college']['CURRENT_COLLEGE_ID'] . "',";
        else
            $sql_staff.="PROFILE_ID='" . $update_staff['OPENSIS_PROFILE'] . "',PROFILE='" . $profile . "',";

        foreach ($_REQUEST['staff_college'] as $field => $value) {
            if ($field == 'IS_DISABLE') {
                if ($value) {
                    $sql_staff .= $field . "='" . singleQuoteReplace('', '', $value) . "',";
                }
            } elseif ($field == 'PASSWORD') {
                $password = md5($value);
                $sql = DBQuery('SELECT PASSWORD FROM login_authentication  WHERE PASSWORD=\'' . $password . '\'');
                $number = $sql->num_rows;
                if ($number == 0) {
                    if ((!$staff['USERNAME']) && (!$staff['PASSWORD'])) {
                        $sql_staff_pwd = $field . "=NULL";
                    } else {
                        $sql_staff_pwd = $field . "='" . singleQuoteReplace('', '', md5($value)) . "'";
                    }
                }
            }
        }
        $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        if ($sql_staff_pwd != '') {
            $sql_staff_pwd = 'Update login_authentication SET ' . $sql_staff_pwd . ' WHERE USER_ID=' . UserStaffID();


            if (SelectedUserProfile('PROFILE_ID') != '')
                $sql_staff_pwd.=' AND PROFILE_ID=' . SelectedUserProfile('PROFILE_ID');
        }

        if ($update_staff['OPENSIS_PROFILE'] != '') {
            $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
            if ($check_rec[1]['REC_EXISTS'] == 0)
                $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['OPENSIS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
            else
                $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['OPENSIS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
        }

        DBQuery($sql_staff);
        if ($sql_staff_pwd != '') {
            DBQuery($sql_staff_pwd);
        }
        if ($update_staff['OPENSIS_PROFILE'] != '')
            DBQuery($sql_staff_prf);
        if ((!$staff['USERNAME']) && (!$staff['PASSWORD']) && $_REQUEST['USERNAME'] != '' && $_REQUEST['PASSWORD'] != '') {
            $sql_staff_algo = "UPDATE login_authentication l,staff s, staff_college_info ssi SET
                                l.username = '" . $_REQUEST['USERNAME'] . "',
                               l.password ='" . md5($_REQUEST['PASSWORD']) . "' 
                                WHERE s.staff_id = ssi.staff_id AND l.user_id=s.staff_id AND l.profile_id NOT IN (3,4) AND s.staff_id = " . UserStaffID();

            DBQuery($sql_staff_algo);
        }
        if ($update_staff['OPENSIS_PROFILE'] == '1') {

            $college_id3 = DBGet(DBQuery("SELECT ID FROM colleges WHERE ID NOT IN (SELECT college_id FROM staff_college_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
            foreach ($college_id3 as $index => $val) {

                $sql_up = 'INSERT INTO staff_college_relationship(staff_id,syear,college_id';
                $sql_up.=')VALUES(\'' . $_REQUEST[staff_id] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                $sql_up.=')';
            }
        }
    } elseif ($_REQUEST['values']['COLLEGE']['OPENSIS_ACCESS'] == 'N') {
        $sql = "INSERT INTO staff_college_info ";
        $fields = 'STAFF_ID,';
        $values = "'" . UserStaffID() . "',";
        foreach ($_REQUEST['values']['COLLEGE'] as $column => $value) {

//            if ($column == 'OPENSIS_PROFILE') {
//                $fields .= $column . ',';
//                $values .= "NULL,";
//            } else {
                if ($value) {
                    $fields .= $column . ',';
//                                    if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux'))
//                                      {
//                                        $values .= "'".str_replace("'","\'",$value)."',";
//                                    }
//                                    else
                    $values .= "'" . singleQuoteReplace('', '', $value) . "',";
                }
//            }
        }
        $sql .= '(' . substr($fields, 0, -1) . ') values(' . substr($values, 0, -1) . ')';

        DBQuery($sql);
        $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_college_info where STAFF_ID='" . UserStaffID() . "'"));
        $update_staff = $update_staff_RET[1];
        $staff_CHECK = DBGet(DBQuery("SELECT  *  FROM staff where STAFF_ID='" . UserStaffID() . "'"));
        $staff = $staff_CHECK[1];
        
        if($update_staff['OPENSIS_PROFILE']!=''){
        $profile_det=DBGet(DBQuery('SELECT * FROM user_profiles WHERE ID='.$update_staff['OPENSIS_PROFILE']));
        
        $sql_staff = "UPDATE staff SET ";
        $sql_staff.="PROFILE_ID='" . $update_staff['OPENSIS_PROFILE'] . "',PROFILE='" . $profile_det[1]['PROFILE'] . "' ";
        }else{
        $sql_staff = "UPDATE staff SET ";
        $sql_staff.="PROFILE_ID='" . $update_staff['OPENSIS_PROFILE'] . "',"; 
        }
        $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        DBQuery($sql_staff);
        
        
        if ($update_staff['OPENSIS_PROFILE'] != '') {
            $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
            if ($check_rec[1]['REC_EXISTS'] == 0)
                $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['OPENSIS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
            else
                $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['OPENSIS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
        }

       
        if ($update_staff['OPENSIS_PROFILE'] != '')
            DBQuery($sql_staff_prf);
        
        if ($update_staff['OPENSIS_PROFILE'] == '1') {

            $college_id3 = DBGet(DBQuery("SELECT ID FROM colleges WHERE ID NOT IN (SELECT college_id FROM staff_college_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
            foreach ($college_id3 as $index => $val) {

                $sql_up = 'INSERT INTO staff_college_relationship(staff_id,syear,college_id';
                $sql_up.=')VALUES(\'' . $_REQUEST[staff_id] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                $sql_up.=')';
            }
        }
        
        
        
    }
} else {
    
    if ($_REQUEST['values']['COLLEGE']['OPENSIS_ACCESS'] == 'Y') {
         if(count($_REQUEST['values']['COLLEGES'])==0)
                {
                    $sch_err= "<div class=\"alert bg-danger alert-styled-left\">Please Select atleast one College.</div>";
                }
        $sql = "UPDATE staff_college_info  SET ";

        foreach ($_REQUEST['values']['COLLEGE'] as $column => $value) {

            if (strtoupper($column) == 'OPENSIS_PROFILE' || strtoupper($column) == 'CATEGORY') {
                $check_prof = DBGet(DBQuery('SELECT * FROM staff_college_info WHERE STAFF_ID=' . UserStaffID()));
                if (strtoupper($column) == 'OPENSIS_PROFILE' && $value != $check_prof[1]['OPENSIS_PROFILE']) {
                    if ($value != '') {
                        $check_staff_cp = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL_ASSIGNED FROM course_periods WHERE TEACHER_ID=' . UserStaffID() . ' OR SECONDARY_TEACHER_ID=' . UserStaffID() . ''));
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] == 0 && $value != '') {
                        $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] > 0 && $value != '') {
                        $get_staff_prof = DBGet(DBQuery('SELECT PROFILE FROM user_profiles WHERE ID=' . $value));
                        if ($get_staff_prof[1]['PROFILE'] == 'teacher') {
                            DBQuery('UPDATE staff SET PROFILE_ID=' . $value . ',PROFILE=\'teacher\' WHERE STAFF_ID=' . UserStaffID());
                            DBQuery('UPDATE staff_college_info SET OPENSIS_PROFILE=' . $value . ' WHERE STAFF_ID=' . UserStaffID());
                        } else {
                            if (strtoupper($column) == 'OPENSIS_PROFILE')
                                echo '<script type=text/javascript>document.getElementById(\'prof_err\').innerHTML=\'<font color=red><b>Cannot change the profile as this staff has one or more course periods.</b></font>\';</script>';
                        }
                    }
                }
                if (strtoupper($column) == 'CATEGORY' && $value != $check_prof[1]['CATEGORY']) {
                    if ($value != '') {
                        $check_staff_cp = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL_ASSIGNED FROM course_periods WHERE TEACHER_ID=' . UserStaffID() . ' OR SECONDARY_TEACHER_ID=' . UserStaffID() . ''));
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] == 0 && $value != '') {
                        $go = true;

                        $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] > 0 && $value != '') {
                        if (strtoupper($column) == 'CATEGORY')
                            echo '<script type=text/javascript>document.getElementById(\'cat_err\').innerHTML=\'<font color=red><b>Cannot change the category as this staff has one or more course periods.</b></font>\';</script>';
                    }
                }
            } else
                $sql .= "$column='" . singleQuoteReplace('', '', $value) . "',";
        }
        $sql = substr($sql, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        DBQuery($sql);
        $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_college_info where STAFF_ID='" . UserStaffID() . "'"));
        $update_staff = $update_staff_RET[1];
        $profile_name_RET = DBGet(DBQuery("SELECT PROFILE from user_profiles WHERE id=" . $update_staff['OPENSIS_PROFILE']));
        $profile = $profile_name_RET[1]['PROFILE'];
        $staff_CHECK = DBGet(DBQuery("SELECT  s.*,l.*  FROM staff s,login_authentication l where s.STAFF_ID='" . UserStaffID() . "' AND l.USER_ID=s.STAFF_ID AND l.PROFILE_ID NOT IN (3,4) "));
        $staff = $staff_CHECK[1];

        $sql_staff = "UPDATE staff SET ";

        $sql_staff.=" PROFILE_ID='" . $update_staff['OPENSIS_PROFILE'] . "',
                                       PROFILE='" . $profile . "',CURRENT_COLLEGE_ID='" . $_REQUEST['staff_college']['CURRENT_COLLEGE_ID'] . "',";

        foreach ($_REQUEST['staff_college'] as $field => $value) {
            if ($field == 'IS_DISABLE') {
                if ($value) {
                    $sql_staff .= $field . "='" . singleQuoteReplace('', '', $value) . "',";
                }
            } elseif ($field == 'PASSWORD') {
                $password = md5($value);
                $sql = DBQuery('SELECT PASSWORD FROM login_authentication WHERE PASSWORD=\'' . $password . '\'');
                $number = $sql->num_rows;
                if ($number == 0) {
                    if ((!$staff['USERNAME']) && (!$staff['PASSWORD'])) {
                        $sql_staff_pwd = $field . "=NULL";
                    } else {
                        $sql_staff_pwd = $field . "='" . singleQuoteReplace('', '', md5($value)) . "'";
                    }
                }
            }
        }
        $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        if ($sql_staff_pwd != '')
            $sql_staff_pwd = 'Update login_authentication SET ' . $sql_staff_pwd . ' WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID=' . SelectedUserProfile('PROFILE_ID');

        if ($update_staff['OPENSIS_PROFILE'] != '') {
            $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
            if ($check_rec[1]['REC_EXISTS'] == 0)
                $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['OPENSIS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
            else
                $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['OPENSIS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
        }

        DBQuery($sql_staff);
        if ($sql_staff_pwd != '')
            DBQuery($sql_staff_pwd);

        if ($update_staff['OPENSIS_PROFILE'] != '')
            DBQuery($sql_staff_prf);

        if ((!$staff['USERNAME']) && (!$staff['PASSWORD']) && $_REQUEST['USERNAME'] != '' && $_REQUEST['PASSWORD'] != '') {


            $sql_staff_algo = "UPDATE login_authentication l,staff s, staff_college_info ssi SET
                                l.username = '" . $_REQUEST['USERNAME'] . "',
                               l.password ='" . md5($_REQUEST['PASSWORD']) . "' 
                                WHERE s.staff_id = ssi.staff_id AND l.user_id=s.staff_id AND l.profile_id NOT IN (3,4) AND s.staff_id = " . UserStaffID();



            DBQuery($sql_staff_algo);
        }
        if ($update_staff['OPENSIS_PROFILE'] == '1') {

            $college_id3 = DBGet(DBQuery("SELECT ID FROM colleges WHERE ID NOT IN (SELECT college_id FROM staff_college_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
            foreach ($college_id3 as $index => $val) {

                $sql_up = 'INSERT INTO staff_college_relationship(staff_id,syear,college_id';
                $sql_up.=')VALUES(\'' . $_REQUEST[staff_id] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                $sql_up.=')';
            }
        }
       
    } elseif ($_REQUEST['values']['COLLEGE']['OPENSIS_ACCESS'] == 'N') {
         if(count($_REQUEST['values']['COLLEGES'])==0)
                {
                    $sch_err= "<div class=\"alert bg-danger alert-styled-left\">Please Select atleast one College.</div>";
                }

        $sql = "UPDATE staff_college_info  SET ";

        foreach ($_REQUEST['values']['COLLEGE'] as $column => $value) {
//                                                 if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux')){
//                                                        $sql .= "$column='".str_replace("'","\'",str_replace("`","''",$value))."',";
//                                                        }else
            $sql .= "$column='" . singleQuoteReplace('', '', $value) . "',";
        }
        $sql = substr($sql, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        DBQuery($sql);
        
        if(isset($_REQUEST['values']['COLLEGE']['OPENSIS_PROFILE']) && $_REQUEST['values']['COLLEGE']['OPENSIS_PROFILE']!='')
        {
        
        $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_college_info where STAFF_ID='" . UserStaffID() . "'"));
        $update_staff = $update_staff_RET[1];
        $staff_CHECK = DBGet(DBQuery("SELECT  *  FROM staff where STAFF_ID='" . UserStaffID() . "'"));
        $staff = $staff_CHECK[1];
        
        if($update_staff['OPENSIS_PROFILE']!=''){
        $profile_det=DBGet(DBQuery('SELECT * FROM user_profiles WHERE ID='.$update_staff['OPENSIS_PROFILE']));
        
        $sql_staff = "UPDATE staff SET ";
        $sql_staff.="PROFILE_ID='" . $update_staff['OPENSIS_PROFILE'] . "',PROFILE='" . $profile_det[1]['PROFILE'] . "' ";
        }else{
        $sql_staff = "UPDATE staff SET ";
        $sql_staff.="PROFILE_ID='" . $update_staff['OPENSIS_PROFILE'] . "',"; 
        }
        $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        DBQuery($sql_staff);
        
        
        if ($update_staff['OPENSIS_PROFILE'] != '') {
            $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
            if ($check_rec[1]['REC_EXISTS'] == 0)
                $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['OPENSIS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
            else
                $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['OPENSIS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
        }

       
        if ($update_staff['OPENSIS_PROFILE'] != '')
            DBQuery($sql_staff_prf);
        
        if ($update_staff['OPENSIS_PROFILE'] == '1') {

            $college_id3 = DBGet(DBQuery("SELECT ID FROM colleges WHERE ID NOT IN (SELECT college_id FROM staff_college_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
            foreach ($college_id3 as $index => $val) {

                $sql_up = 'INSERT INTO staff_college_relationship(staff_id,syear,college_id';
                $sql_up.=')VALUES(\'' . $_REQUEST[staff_id] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                $sql_up.=')';
                
                DBQuery($sql_up);
            }
        }
        }
        
        unset($_REQUEST['values']['COLLEGE']['COLLEGE_ACCESS']);
        unset($_REQUEST['values']['COLLEGE']['OPENSIS_PROFILE']);
    }
}
if($sch_err!='')
    {
        echo $sch_err;
        unset($sch_err);
    }
if (!$_REQUEST['modfunc']) {
    $this_college_RET = DBGet(DBQuery("SELECT * FROM staff_college_info   WHERE   STAFF_ID=" . UserStaffID()));
    $this_college = $this_college_RET[1];

    $this_college_RET_mod = DBGet(DBQuery("SELECT s.*,l.* FROM staff s,login_authentication l  WHERE l.USER_ID=s.STAFF_ID AND l.PROFILE_ID NOT IN (3,4) AND s.STAFF_ID=" . UserStaffID()));

    $this_college_mod = $this_college_RET_mod[1];


    if (User('PROFILE') == 'admin')
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE ID <> 3 AND PROFILE <> 'parent' AND ID<>0 ORDER BY ID"));

    $prof_check = DBGet(DBQuery('SELECT PROFILE_ID FROM staff WHERE STAFF_ID=' . UserStaffID()));
    if (User('PROFILE_ID') == 0 && $prof_check[1]['PROFILE_ID'] == 0)
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE ID <> 3  AND PROFILE <> 'parent' ORDER BY ID"));
    if (User('PROFILE_ID') == 0 && $prof_check[1]['PROFILE_ID'] != 0)
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE ID <> 0  AND PROFILE <> 'parent' AND ID<>'4' ORDER BY ID"));

    if (User('PROFILE_ID') == 2)
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE  PROFILE ='teacher' ORDER BY ID"));
    $i = 1;
    foreach ($profiles_options as $options) {
        if ($options['PROFILE'] != 'student')
            $option[$options['ID']] = $options['TITLE'];
        $i++;
    }
    if (count($option) == 0 && User('PROFILE') != 'admin') {
        $profiles_options = DBGet(DBQuery('SELECT TITLE, ID FROM user_profiles WHERE ID=' . User('PROFILE_ID')));
        $option[$profiles_options[1]['ID']] = $profiles_options[1]['TITLE'];
    }
    $_REQUEST['category_id'] = 3;
    $_REQUEST['custom'] = 'staff';
    include('modules/users/includes/OtherInfoInc.inc.php');


    $style = '';


    if (isset($_REQUEST['college_info_id'])) {
        $get_end_date = DBGet(DBQuery('SELECT MAX(END_DATE) AS END_DATE FROM college_years WHERE  SYEAR=' . UserSyear()));
        $get_end_date = $get_end_date[1]['END_DATE'];


        echo "<INPUT type=hidden name=college_info_id value=$_REQUEST[college_info_id]>";

        if ($_REQUEST['college_info_id'] != '0' && $_REQUEST['college_info_id'] !== 'old') {

            echo '<h5 class="text-primary">Official Information</h5>';
            
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            if (User('PROFILE_ID') == 0 && $prof_check[1]['PROFILE_ID'] == 0 && User('STAFF_ID') == UserStaffID())
                echo '<div class="form-group"><label class="control-label text-right col-lg-4">Category <span class=text-danger>*</span></label><div class="col-lg-8">' . SelectInput($this_college['CATEGORY'], 'values[COLLEGE][CATEGORY]', '', array('Super Administrator' => 'Super Administrator', 'Administrator' => 'Administrator', 'Teacher' => 'Teacher', 'Non Teaching Staff' => 'Non Teaching Staff', 'Custodian' => 'Custodian', 'Principal' => 'Principal', 'Clerk' => 'Clerk'), false) . '</div></div>';
            else
                echo '<div class="form-group"><label class="control-label text-right col-lg-4">Category <span class=text-danger>*</span></label><div class="col-lg-8">' . SelectInput($this_college['CATEGORY'], 'values[COLLEGE][CATEGORY]', '', array('Administrator' => 'Administrator', 'Teacher' => 'Teacher', 'Non Teaching Staff' => 'Non Teaching Staff', 'Custodian' => 'Custodian', 'Principal' => 'Principal', 'Clerk' => 'Clerk'), false) . '</div></div>';
            echo '</div><div class="col-md-6">';
            echo '<div class="form-group">' . TextInput($this_college['JOB_TITLE'], 'values[COLLEGE][JOB_TITLE]', 'Job Title', 'class=cell_medium') . '</div>';
            echo '</div>'; //.col-md-6
            echo '</div>'; //.row
            
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">Joining Date <span class=text-danger>*</span></label><div class="col-lg-8">' . DateInputAY(isset($this_college['JOINING_DATE']) && $this_college['JOINING_DATE']!="" ? $this_college['JOINING_DATE'] : "", 'values[JOINING_DATE]', 1, 'class=cell_medium') . '</div></div>';
            echo '<input type=hidden id=end_date_college value="' . $get_end_date . '" >';
            echo '</div><div class="col-md-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">End Date</label><div class="col-lg-8">' . DateInputAY($this_college['END_DATE']!="" ? $this_college['END_DATE'] : "", 'values[ENDING_DATE]', 2, '') . '</div></div>';
            echo "<INPUT type=hidden name=values[COLLEGE][HOME_COLLEGE] value=" . UserCollege() . ">";
            echo '</div>'; //.col-md-6
            echo '</div>'; //.row
            
            $staff_profile = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . UserStaffID() . "'"));
            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">Profile</label><div class="col-lg-8">' . SelectInput($this_college['OPENSIS_PROFILE'], 'values[COLLEGE][OPENSIS_PROFILE]', '', $option, false, 'id=values[COLLEGE][OPENSIS_PROFILE]') . '</div></div>';
            echo '</div>'; //.col-lg-6            
            echo '</div>'; //.row
            
            echo '';
            
            if ($this_college_mod['USERNAME'] && (!$this_college['OPENSIS_ACCESS'] == 'Y')) {
                echo '<div class="row">';
                echo '<div class="col-md-12">';
                echo '<h5 class="text-primary inline-block">openSIS Access Information</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[COLLEGE][OPENSIS_ACCESS]" value="N" onClick="hidediv();">No Access</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[COLLEGE][OPENSIS_ACCESS]" value="Y" onClick="showdiv();" checked>Access</label></div>';
                echo '</div>'; //.col-md-6
                echo '</div>'; //.row
                echo '<div id="hideShow" class="mt-15">';
            } elseif ($this_college_mod['USERNAME'] && $this_college_mod['PASSWORD'] && $this_college['OPENSIS_ACCESS']) {
                if ($this_college['OPENSIS_ACCESS'] == 'N'){
                    echo '<div class="row">';
                    echo '<div class="col-md-12">';
                    echo '<h5 class="text-primary inline-block">openSIS Access Information</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[COLLEGE][OPENSIS_ACCESS]" value="N" checked>No Access</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[COLLEGE][OPENSIS_ACCESS]" value="Y" >Access</label></div>';
                    echo '</div>'; //.col-md-6
                    echo '</div>'; //.row
                }elseif ($this_college['OPENSIS_ACCESS'] == 'Y'){
                    echo '<div class="row">';
                    echo '<div class="col-md-12">';
                    echo '<h5 class="text-primary inline-block">openSIS Access Information</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[COLLEGE][OPENSIS_ACCESS]" value="N">No Access</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[COLLEGE][OPENSIS_ACCESS]" value="Y"  checked>&nbsp;Access</label></div>';
                    echo '</div>'; //.col-md-6
                    echo '</div>'; //.row
                }
                echo '<div id="hideShow" class="mt-15">';
            }
            elseif (!$this_college_mod['USERNAME'] || $this_college['OPENSIS_ACCESS'] == 'N') {
                echo '<div class="row">';
                echo '<div class="col-md-12">';
                echo '<h5 class="text-primary inline-block">openSIS Access Information</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[COLLEGE][OPENSIS_ACCESS]" value="N" onClick="hidediv();" checked>No Access</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[COLLEGE][OPENSIS_ACCESS]" value="Y" onClick="showdiv();">&nbsp;Access</label></div>';
                echo '</div>'; //.col-md-6
                echo '</div>'; //.row
                echo '<div id="hideShow" class="mt-15" style="display:none">';
            }

            
//            $staff_profile = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . UserStaffID() . "'"));
//            echo '<div class="row">';
//            echo '<div class="col-lg-6">';
//            echo '<div class="form-group"><label class="control-label text-right col-lg-4">Profile</label><div class="col-lg-8">' . SelectInput($this_college['OPENSIS_PROFILE'], 'values[COLLEGE][OPENSIS_PROFILE]', '', $option, false, 'id=values[COLLEGE][OPENSIS_PROFILE]') . '</div></div>';
//            echo '</div>'; //.col-lg-6            
//            echo '</div>'; //.row
            
            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">Username <span class=text-danger>*</span></label><div class="col-lg-8">';
            if (!$this_college_mod['USERNAME']) {
                echo TextInput('', 'USERNAME', '', 'size=20 maxlength=50 onblur="usercheck_init_staff(this)"');
                echo '<span id="ajax_output_st"></span><input type=hidden id=usr_err_check value=0>';
            } else {
                echo NoInput($this_college_mod['USERNAME'], '', '', 'onkeyup="usercheck_init(this)"') . '<div id="ajax_output"></div>';
            }
            echo '</div></div>';
            echo '</div>'; //.col-lg-6
            echo '<div class="col-lg-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">Password <span class=text-danger>*</span></label><div class="col-lg-8">';
            if (!$this_college_mod['PASSWORD']) {
                echo TextInputModHidden('', 'PASSWORD', '', 'size=20 maxlength=100 AUTOCOMPLETE = off onblur=passwordStrength(this.value);validate_password_staff(this.value);');

                echo '<span id="ajax_output_st"></span>';
            } else {
                echo TextInputModHidden(array($this_college_mod['PASSWORD'], str_repeat('*', strlen($this_college_mod['PASSWORD']))), 'staff_college[PASSWORD]', '', 'size=20 maxlength=100 AUTOCOMPLETE = off onkeyup=passwordStrength(this.value);validate_password(this.value);');
            }
            echo "<span id='passwordStrength'></span></div></div>";
            echo '</div>'; //.col-lg-6
            echo '</div>'; //.row
            
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">Disable User</label><div class="col-lg-8">';
            if ($this_college_mod['IS_DISABLE'] == 'Y')
                $dis_val = 'Y';
            else
                $dis_val = 'N';
            echo CheckboxInput_No($dis_val, 'staff_college[IS_DISABLE]', '', 'CHECKED', $new, '<i class="icon-checkbox-checked"></i>', '<i class="icon-checkbox-unchecked"></i>');
            echo '</div></div>';
            echo '</div>'; //.col-md-6
            echo '</div>'; //.row
            
            echo '</div>'; //#hideShow
            
            if ($this_college['COLLEGE_ACCESS']) {

                $pieces = explode(",", $this_college['COLLEGE_ACCESS']);
            }

            
            $profile_return = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . UserStaffID() . "'"));
            if ($profile_return[1]['PROFILE_ID'] != '') {
                echo '<h5 class="text-primary">College Information</h5>';
                echo '<hr/>';
                $functions = array('START_DATE' => '_makeStartInputDate', 'PROFILE' => '_makeUserProfile', 'END_DATE' => '_makeEndInputDate', 'COLLEGE_ID' => '_makeCheckBoxInput_gen', 'ID' => '_makeStatus');


                $sql = 'SELECT s.ID,ssr.COLLEGE_ID as SCH_ID,ssr.COLLEGE_ID,s.TITLE,ssr.START_DATE,ssr.END_DATE,st.PROFILE FROM colleges s,staff st INNER JOIN staff_college_relationship ssr USING(staff_id) WHERE s.id=ssr.college_id  AND st.staff_id=' . User('STAFF_ID') . ' AND ssr.SYEAR='.UserSyear().' GROUP BY ssr.COLLEGE_ID';
                $college_admin = DBGet(DBQuery($sql), $functions);
                //print_r($college_admin);
//                $columns = array('COLLEGE_ID' => '<a><INPUT type=checkbox value=Y name=controller onclick="checkAll(this.form,this.form.controller.checked,\'unused\');" /></a>', 'TITLE' => 'College', 'PROFILE' => 'Profile', 'START_DATE' => 'Start Date', 'END_DATE' => 'Drop Date', 'ID' => 'Status');
                
                $columns = array('COLLEGE_ID' => '<a><INPUT type=checkbox value=Y name=controller onclick="checkAllDtMod(this,\'values[COLLEGES]\');" /></a>', 'TITLE' => 'College', 'PROFILE' => 'Profile', 'START_DATE' => 'Start Date', 'END_DATE' => 'Drop Date', 'ID' => 'Status');
                $college_ids_for_hidden=array();
                echo '<div id="hidden_checkboxes">';
                foreach($college_admin as $sai=>$sad){
//                    echo '<pre>';
//                    print_r($sad);
                    $college_ids_for_hidden[]=$sad['SCH_ID'];
                    if(strip_tags($sad['ID'])=='Active')
                    echo '<input type=hidden name="values[COLLEGES]['.$sad['SCH_ID'].']" value="Y" data-checkbox-hidden-id="'.$sad['SCH_ID'].'" />';
                }
                echo '</div>';
                $college_ids_for_hidden=implode(',',$college_ids_for_hidden);
                echo '<input type=hidden id=college_ids_hidden value="'.$college_ids_for_hidden.'" />';
                
                $check_all_arr=array();
                foreach($college_admin as $xy)
                {
                    
                    $check_all_arr[]=$xy['SCH_ID'];
                }
                $check_all_stu_list=implode(',',$check_all_arr);
                echo'<input type=hidden name=res_length id=res_length value=\''.count($check_all_arr).'\'>';
                echo '<br>';
                echo'<input type=hidden name=res_len id=res_len value=\''.$check_all_stu_list.'\'>';
                
                ListOutputStaffPrintCollegeInfo($college_admin, $columns, 'College Record', 'College Records', array(), array(), array('search' => false));
                
            }
        }
    }
    else
        echo '';
    $separator = '<HR>';
}

function CheckboxInput_No($value, $name, $title = '', $checked = '', $new = false, $yes = 'Yes', $no = 'No', $div = true, $extra = '') {
    // $checked has been deprecated -- it remains only as a placeholder
    if (Preferences('HIDDEN') != 'Y')
        $div = false;

    if ($div == false || $new == true) {
        if ($value && $value != 'N')
            $checked = 'CHECKED';
        else
            $checked = '';
    }

    if (AllowEdit() && !$_REQUEST['_openSIS_PDF']) {
        if ($new || $div == false) {
            return "<INPUT type=checkbox name=$name value=Y  $extra>" . ($title != '' ? '<BR><small>' . (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '') . '</small>' : '');
        } else {
            if($value=='' || $value=='N')
            return "<DIV id='div$name' class=\"form-control\" readonly=\"readonly\"><INPUT type=checkbox name=$name " . (($value == 'Y') ? 'checked' : '') . " value=Y " . str_replace('"', '\"', $extra) . "></DIV>";
            else
            return "<DIV id='div$name' class=\"form-control\" readonly=\"readonly\"><div onclick='javascript:addHTML(\"<INPUT type=hidden name=$name value=\\\"N\\\"><INPUT type=checkbox name=$name " . (($value == 'Y') ? 'checked' : '') . " value=Y " . str_replace('"', '\"', $extra) . ">" . ($title != '' ? '<BR><small>' . str_replace("'", '&#39;', (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '')) . '</small>' : '') . "\",\"div$name\",true)'>" . (($value != 'N') ? $yes : $no) . ($title != '' ? "<BR><small>" . str_replace("'", '&#39;', (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '')) . "</small>" : '') . "</div></DIV>";
        }
    } else
        return (($value != 'N') ? $yes : $no) . ($title != '' ? '<BR><small>' . (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '') . '</small>' : '');
}

function _makeStartInputDate($value, $column) {
    global $THIS_RET;

    if ($_REQUEST['staff_id'] == 'new') {
        $date_value = '';
    } else {
        $sql = 'SELECT ssr.START_DATE FROM staff s,staff_college_relationship ssr  WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=' . UserSyear();
        $user_exist_college = DBGet(DBQuery($sql));
        if ($user_exist_college[1]['START_DATE'] == '0000-00-00' || $user_exist_college[1]['START_DATE'] == '')
            $date_value = '';
        else
            $date_value = $user_exist_college[1]['START_DATE'];
    }

    return '<TABLE class=LO_field><TR>' . '<TD nowrap="nowrap">' . DateInputAY($date_value!='' ? $date_value : $date_value , 'values[START_DATE][' . $THIS_RET['ID'] . ']', '1' . $THIS_RET['ID']) . '</TD></TR></TABLE>';
}

function _makeUserProfile($value, $column) {
    global $THIS_RET;
    if ($_REQUEST['staff_id'] == 'new') {
        $profile_value = '';
    } else {
        $sql = 'SELECT up.TITLE FROM staff s,staff_college_relationship ssr,user_profiles up  WHERE ssr.STAFF_ID=s.STAFF_ID AND up.ID=s.PROFILE_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=   (SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        $user_profile = DBGet(DBQuery($sql));
        $profile_value = $user_profile[1]['TITLE'];
    }
    return '<TABLE class=LO_field><TR>' . '<TD>' . $profile_value . '</TD></TR></TABLE>';
}

function _makeEndInputDate($value, $column) {
    global $THIS_RET;
    if ($_REQUEST['staff_id'] == 'new') {
        $date_value = '';
    } else {

        $sql = 'SELECT ssr.END_DATE FROM staff s,staff_college_relationship ssr  WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=   (SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        $user_exist_college = DBGet(DBQuery($sql));
        if ($user_exist_college[1]['END_DATE'] == '0000-00-00' || $user_exist_college[1]['END_DATE'] == '')
            $date_value = '';
        else
            $date_value = $user_exist_college[1]['END_DATE'];
    }
    if (SelectedUserProfile('PROFILE_ID') == 0)
        return '<TABLE class=LO_field><TR>' . '<TD nowrap="nowrap">' . ProperDateAY($date_value) . '</TD></TR></TABLE>';
    else
        return '<TABLE class=LO_field><TR>' . '<TD nowrap="nowrap">' . DateInputAY($date_value, 'values[END_DATE][' . $THIS_RET['ID'] . ']', '2' . $THIS_RET['ID']) . '</TD></TR></TABLE>';
}

function _makeCheckBoxInput_gen($value, $column) {
    global $THIS_RET;

    $_SESSION[staff_college_chkbox_id] ++;
    $staff_college_chkbox_id = $_SESSION[staff_college_chkbox_id];
    if ($_REQUEST['staff_id'] == 'new') {
        return '<TABLE class=LO_field><TR>' . '<TD>' . "<input name=unused[$THIS_RET[ID]]  type='checkbox' id=$staff_college_chkbox_id onClick='setHiddenCheckbox(\"values[COLLEGES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />" . '</TD></TR></TABLE>';
//        return '<TABLE class=LO_field><TR>' . '<TD>' . CheckboxInput('', 'values[COLLEGES][' . $THIS_RET['ID'] . ']', '', '', true, '<IMG SRC=assets/check.gif width=15>', '<IMG SRC=assets/x.gif width=15>', true, 'id=staff_COLLEGES' . $staff_college_chkbox_id) . '</TD></TR></TABLE>';
    } else {
        $sql = '';
        $staff_infor_qr = DBGet(DBQuery('select * from staff_college_relationship where STAFF_ID=\'' . $_SESSION['staff_selected'] . '\' AND SYEAR='. UserSyear()));
        if(count($staff_infor_qr)>0)
        {
            $i=0;
            foreach($staff_infor_qr as $skey => $sval)
            {
                $sch_li[$i]=$sval['COLLEGE_ID'];
                $i++;
            }
        }
        //$sch_li = explode(',', trim($staff_infor_qr[1]['COLLEGE_ACCESS']));
        $dates = DBGet(DBQuery("SELECT ssr.START_DATE,ssr.END_DATE FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID='" . $THIS_RET['COLLEGE_ID'] . "' AND ssr.STAFF_ID='" . $_SESSION['staff_selected'] . "' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID='" . $THIS_RET['COLLEGE_ID'] . "' AND STAFF_ID='" . $_SESSION['staff_selected'] . "')"));
        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] == '0000-00-00' && in_array($THIS_RET['COLLEGE_ID'], $sch_li)) {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        }
        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00' && in_array($THIS_RET['COLLEGE_ID'], $sch_li)) {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ') AND (ssr.END_DATE>=CURDATE() OR ssr.END_DATE=\'0000-00-00\' OR ssr.END_DATE IS NULL)';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00' && in_array($THIS_RET['COLLEGE_ID'], $sch_li)) {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')  AND (ssr.START_DATE>=ssr.END_DATE OR ssr.START_DATE=\'0000-00-00\' OR ssr.END_DATE>=CURDATE() OR ssr.END_DATE IS NULL)';
        }
        if ($sql != '')
            $user_exist_college = DBGet(DBQuery($sql));
        else
            $user_exist_college = array();
//        if($THIS_RET['COLLEGE_ID']==108 || $THIS_RET['COLLEGE_ID']==109)
//            echo $sql;
//        if(!empty($user_exist_college)){
//            
//        print_r($user_exist_college);
//        echo '<br>'.$THIS_RET[ID].'<hr>';}
//        if (!empty($user_exist_college)) {
//            if (SelectedUserProfile('PROFILE_ID') == 0)
//                return '<TABLE class=LO_field><TR>' . '<TD>' . CheckboxInput('Y', 'values[COLLEGES][' . $THIS_RET['ID'] . ']', '', '', true, '<IMG SRC=assets/check.gif width=15>', '<IMG SRC=assets/x.gif width=15>', true, 'id=staff_COLLEGES onclick="return false;" onkeydown="return false;" ' . $staff_college_chkbox_id) . '</TD></TR></TABLE>';
//            else
//                return '<TABLE class=LO_field><TR>' . '<TD>' . CheckboxInput('Y', 'values[COLLEGES][' . $THIS_RET['ID'] . ']', '', '', true, '<IMG SRC=assets/check.gif width=15>', '<IMG SRC=assets/x.gif width=15>', true, 'id=staff_COLLEGES' . $staff_college_chkbox_id) . '</TD></TR></TABLE>';
//        }
//        else {
//            if (SelectedUserProfile('PROFILE_ID') == 0)
//                return '<TABLE class=LO_field><TR>' . '<TD>' . CheckboxInput('Y', 'values[COLLEGES][' . $THIS_RET['ID'] . ']', '', '', true, '<IMG SRC=assets/check.gif width=15>', '<IMG SRC=assets/x.gif width=15>', true, 'id=staff_COLLEGES onclick="return false;" onkeydown="return false;" ' . $staff_college_chkbox_id) . '</TD></TR></TABLE>';
//            else
//                return '<TABLE class=LO_field><TR>' . '<TD>' . CheckboxInput('', 'values[COLLEGES][' . $THIS_RET['ID'] . ']', '', '', true, '<IMG SRC=assets/check.gif width=15>', '<IMG SRC=assets/x.gif width=15>', true, 'id=staff_COLLEGES' . $staff_college_chkbox_id) . '</TD></TR></TABLE>';
//        }
        
        if (!empty($user_exist_college)) {
            if (SelectedUserProfile('PROFILE_ID') == 0)
                return '<TABLE class=LO_field><TR>' . '<TD>' ."<input checked name=unused[$THIS_RET[ID]] type='checkbox'  id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[COLLEGES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);'  />" . '</TD></TR></TABLE>';
            else
                return '<TABLE class=LO_field><TR>' . '<TD>' ."<input checked name=unused[$THIS_RET[ID]]  type='checkbox' id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[COLLEGES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />". '</TD></TR></TABLE>';
        }
        else {
            if (SelectedUserProfile('PROFILE_ID') == 0)
                return '<TABLE class=LO_field><TR>' . '<TD>' . "<input name=unused[$THIS_RET[ID]]  type='checkbox' id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[COLLEGES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />" . '</TD></TR></TABLE>';
            else
                return '<TABLE class=LO_field><TR>' . '<TD>' ."<input name=unused[$THIS_RET[ID]]  type='checkbox' id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[COLLEGES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />" . '</TD></TR></TABLE>';
        }
        
    }
}

function _makeStatus($value, $column) {
    global $THIS_RET;
    if ($_REQUEST['staff_id'] == 'new')
        $status_value = '';
    else {

        $dates = DBGet(DBQuery("SELECT ssr.START_DATE,ssr.END_DATE FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID='" . $THIS_RET['COLLEGE_ID'] . "' AND ssr.STAFF_ID='" . $_SESSION['staff_selected'] . "' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID='" . $THIS_RET['COLLEGE_ID'] . "' AND STAFF_ID='" . $_SESSION['staff_selected'] . "')"));
        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] == '0000-00-00') {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        }

        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00') {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ') AND (ssr.END_DATE>=CURDATE() OR ssr.END_DATE=\'0000-00-00\' OR ssr.END_DATE IS NULL)';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00' && $dates[1]['END_DATE'] == '0000-00-00') {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ') ';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00') {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')  AND ssr.END_DATE>=\'' . date('Y-m-d') . '\' ';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00') {
            $sql = 'SELECT COLLEGE_ID FROM staff s,staff_college_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_college_relationship WHERE COLLEGE_ID=' . $THIS_RET['COLLEGE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')  AND (ssr.END_DATE>=\'' . date('Y-m-d') . '\' OR ssr.END_DATE IS NULL OR ssr.END_DATE=\'0000-00-00\')';
        }
        $user_exist_college = DBGet(DBQuery($sql));
        if (!empty($user_exist_college))
            $status_value = 'Active';
        else {
            if ($dates[1]['START_DATE'] != '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00')
                $status_value = 'Inactive';
            else
                $status_value = '';
        }
    }
    return '<TABLE class=LO_field><TR>' . '<TD>' . $status_value . '</TD></TR></TABLE>';
}

?>
