<?php

#**************************************************************************
#  openSIS is a free student information system for publirc and non-public 
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
include('RedirectRootInc.php');
include'ConfigInc.php';
include 'Warehouse.php';
if ($_REQUEST['modfunc'] == 'detail' && $_REQUEST['college_roll_no'] && $_REQUEST['college_roll_no'] != 'new') {
    if ($_POST['button'] == 'Save') {

        if ($_REQUEST['TRANSFER']['COLLEGE'] != '' && $_REQUEST['TRANSFER']['Grade_Level'] != '') {
            $drop_code = $_REQUEST['drop_code'];

            $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] = date("Y-m-d", strtotime($_REQUEST['year_TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '-' . $_REQUEST['month_TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '-' . $_REQUEST['day_TRANSFER']['STUDENT_ENROLLMENT_END_DATE']));

            $gread_exists = DBGet(DBQuery('SELECT COUNT(TITLE) AS PRESENT,ID FROM college_gradelevels WHERE COLLEGE_ID=\'' . $_REQUEST['TRANSFER']['COLLEGE'] . '\' AND TITLE=(SELECT TITLE FROM
                            college_gradelevels WHERE ID=(SELECT GRADE_ID FROM student_enrollment WHERE
                            COLLEGE_ROLL_NO=\'' . $_REQUEST['college_roll_no'] . '\' AND COLLEGE_ID=\'' . UserCollege() . '\'  AND SYEAR=\'' . UserSyear() . '\'  ORDER BY ID DESC LIMIT 1))'));  //pinki

            $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START'] = date("Y-m-d", strtotime($_REQUEST['year_TRANSFER']['STUDENT_ENROLLMENT_START'] . '-' . $_REQUEST['month_TRANSFER']['STUDENT_ENROLLMENT_START'] . '-' . $_REQUEST['day_TRANSFER']['STUDENT_ENROLLMENT_START']));




            if (strtotime($_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START']) >= strtotime($_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'])) {
                $check_asociation = DBGet(DBQuery('SELECT COUNT(COLLEGE_ROLL_NO) as REC_EX FROM student_enrollment WHERE COLLEGE_ROLL_NO=' . $_REQUEST['college_roll_no'] . ' AND SYEAR=' . UserSyear() . ' AND COLLEGE_ID=' . UserCollege() . ' AND START_DATE<=\'' . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '\' AND (END_DATE IS NULL OR END_DATE=\'0000-00-00\' AND END_DATE<=\'' . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '\') ORDER BY ID DESC LIMIT 0,1'));
                if ($check_asociation[1]['REC_EX'] != 0) {
                    DBQuery('UPDATE student_enrollment SET DROP_CODE=\'' . $drop_code . '\',END_DATE=\'' . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_END_DATE'] . '\' WHERE COLLEGE_ROLL_NO=\'' . $_REQUEST['college_roll_no'] . '\' AND COLLEGE_ID=\'' . UserCollege() . '\'  AND SYEAR=\'' . UserSyear() . '\'');  //pinki    
                    $syear_RET = DBGet(DBQuery("SELECT MAX(SYEAR) AS SYEAR,TITLE FROM college_years WHERE COLLEGE_ID=" . $_REQUEST['TRANSFER']['COLLEGE']));
                    $syear = $syear_RET[1]['SYEAR'];
                    $enroll_code = DBGet(DBQuery('SELECT id FROM student_enrollment_codes WHERE syear=\'' . $syear . '\' AND type=\'TrnE\''));  //pinki
                    $last_college_RET = DBGet(DBQuery('SELECT COLLEGE_ID FROM student_enrollment WHERE COLLEGE_ROLL_NO=\'' . $_REQUEST['college_roll_no'] . '\' AND SYEAR=\'' . UserSyear() . '\'')); //pinki
                    $last_college = $last_college_RET[1]['COLLEGE_ID'];
                    $sch_id = $_REQUEST['TRANSFER']['COLLEGE'];
                    $num_default_cal = DBGet(DBQuery('SELECT CALENDAR_ID FROM college_calendars WHERE COLLEGE_ID=' . $_REQUEST['TRANSFER']['COLLEGE'] . ' AND DEFAULT_CALENDAR=\'Y\' '));
                    if (empty($num_default_cal)) {
                        $qr = DBGet(DBQuery('SELECT CALENDAR_ID FROM college_calendars WHERE COLLEGE_ID=' . $_REQUEST['TRANSFER']['COLLEGE'] . ' LIMIT 0,1'));

                        $calender_id = $qr[1]['CALENDAR_ID'];
                    }
                    if (count($num_default_cal) == 1) {
                        $calender_id = $num_default_cal[1]['CALENDAR_ID'];
                    } else {
                        $calender_id = 'NULL';
                    }
                    if ($gread_exists[1]['PRESENT'] == 1 && $gread_exists[1]['ID']) {
                        DBQuery("INSERT INTO student_enrollment (SYEAR ,COLLEGE_ID ,COLLEGE_ROLL_NO ,GRADE_ID ,START_DATE ,END_DATE ,ENROLLMENT_CODE ,DROP_CODE ,NEXT_COLLEGE ,CALENDAR_ID ,LAST_COLLEGE) VALUES (" . $syear . "," . $_REQUEST['TRANSFER']['COLLEGE'] . "," . $_REQUEST['college_roll_no'] . "," . $_REQUEST['TRANSFER']['Grade_Level'] . ",'" . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START'] . "',''," . $enroll_code[1]['ID'] . ",'','" . $_REQUEST['TRANSFER']['COLLEGE'] . "',$calender_id,$last_college)");
                    } else {
                        DBQuery("INSERT INTO student_enrollment (SYEAR ,COLLEGE_ID ,COLLEGE_ROLL_NO ,GRADE_ID ,START_DATE ,END_DATE ,ENROLLMENT_CODE ,DROP_CODE ,NEXT_COLLEGE ,CALENDAR_ID ,LAST_COLLEGE) VALUES (" . $syear . "," . $_REQUEST['TRANSFER']['COLLEGE'] . "," . $_REQUEST['college_roll_no'] . "," . $_REQUEST['TRANSFER']['Grade_Level'] . ",'" . $_REQUEST['TRANSFER']['STUDENT_ENROLLMENT_START'] . "',''," . $enroll_code[1]['ID'] . ",'','" . $_REQUEST['TRANSFER']['COLLEGE'] . "',$calender_id,$last_college)");
                    }
                    $trans_college = $syear_RET[1]['TITLE'];

                    $trans_student_RET = DBGet(DBQuery("SELECT FIRST_NAME,LAST_NAME,MIDDLE_NAME,NAME_SUFFIX FROM students WHERE COLLEGE_ROLL_NO='" . $_REQUEST['college_roll_no'] . "'"));

                    $trans_student = $trans_student_RET[1]['LAST_NAME'] . ' ' . $trans_student_RET[1]['FIRST_NAME'];
                    DBQuery('UPDATE medical_info SET COLLEGE_ID=' . $_REQUEST['TRANSFER']['COLLEGE'] . ', SYEAR=' . $syear . ' WHERE COLLEGE_ROLL_NO=\'' . $_REQUEST['college_roll_no'] . '\' AND SYEAR=\'' . UserSyear() . '\' AND COLLEGE_ID=\'' . UserCollege() . '\'');
                    unset($_REQUEST['modfunc']);
                    unset($_SESSION['_REQUEST_vars']['college_roll_no']);
                    echo '<SCRIPT language=javascript>opener.document.location = "Modules.php?modname=students/Student.php&modfunc=&search_modfunc=list&next_modname=students/Student.php&stuid=' . $_REQUEST['college_roll_no'] . '"; window.close();</script>';
                } else {
                    unset($_REQUEST['modfunc']);
                    unset($_SESSION['_REQUEST_vars']['college_roll_no']);
                    echo '<SCRIPT language=javascript>alert("Please provide valid date");window.close();</script>';
                }
            } else {
                unset($_REQUEST['modfunc']);
                unset($_SESSION['_REQUEST_vars']['college_roll_no']);
                echo '<SCRIPT language=javascript>alert("Please provide valid date");window.close();</script>';
            }
        } else {

            if ($_REQUEST['TRANSFER']['COLLEGE'] == '' && $_REQUEST['TRANSFER']['Grade_Level'] != '')
                echo '<SCRIPT language=javascript>alert("Please select College");window.close();</script>';
            if ($_REQUEST['TRANSFER']['COLLEGE'] != '' && $_REQUEST['TRANSFER']['Grade_Level'] == '')
                echo '<SCRIPT language=javascript>alert("Please select Grade Level");window.close();</script>';
            if ($_REQUEST['TRANSFER']['COLLEGE'] == '' && $_REQUEST['TRANSFER']['Grade_Level'] == '')
                unset($_REQUEST['modfunc']);
            echo '<SCRIPT language=javascript>alert("Please select College and Grade Level");window.close();</script>';
        }
    }
    else {

        $sql = "SELECT ID,TITLE FROM colleges WHERE ID !=" . UserCollege();
        $sql2 = DBGet(DBQuery('SELECT ID,TITLE FROM colleges WHERE ID !=' . UserCollege() . '  LIMIT 0,1'));
        $sch_id = $sql2[1]['ID'];
        if ($sch_id != '') {
            $QI = DBQuery($sql);
            $colleges_RET = DBGet($QI);
            foreach ($colleges_RET as $college_array) {
                $options[$college_array['ID']] = $college_array['TITLE'];
            }
            $res = DBGet(DBQuery('SELECT * FROM college_gradelevels WHERE college_id=' . $sch_id . ''));
            foreach ($res as $res1) {
                $options1[$res1['ID']] = $res1['TITLE'];
            }

            $extraM .= 'onchange=grab_GradeLevel(this.value)';
            $exg = 'id="grab_grade"';
            
            echo '<div class="modal-header">';
            echo '<button type="button" class="close" data-dismiss="modal">×</button>';
            echo '<h5 class="modal-title">Transferred Out</h5>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<input type="hidden" name="values[student_enrollment]['.$_REQUEST['college_roll_no'].'][DROP_CODE]" value="'.$_REQUEST['drop_code'].'" />';
            echo '<div class="form-group datepicker-group">';
            echo '<label class="control-label">Current college drop date</label>';
            //echo DateInput_for_EndInputModal('', 'TRANSFER[STUDENT_ENROLLMENT_END_DATE]', '', $div, true);
            echo custom_datepicker('222', 'TRANSFER[STUDENT_ENROLLMENT_END_DATE]');

            echo '</div>';

            echo '<div class="form-group">';
            echo '<label class="control-label">Transferring to</label>';
            echo SelectInputModal('', 'TRANSFER[COLLEGE]', '', $options, false, $extraM, 'class=cell_medium');
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label class="control-label">Grade Level</label>';
            echo SelectInputModal('', 'TRANSFER[Grade_Level]', '', $options1, false, $exg, 'class=cell_medium');
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label class="control-label">New college\'s enrollment date</label>';
            //echo DateInput_for_EndInputModal('', 'TRANSFER[STUDENT_ENROLLMENT_START]', '', $div, true);
            echo custom_datepicker('223', 'TRANSFER[STUDENT_ENROLLMENT_START]');
            echo '</div>';
            echo '</div>'; //.modal-body

            echo '<div class="modal-footer">';
            echo '<INPUT type=submit class="btn btn-primary" name=button value=Save>';
            echo '</div>';

            //echo '</FORM>';

            unset($_REQUEST['values']);
            unset($_SESSION['_REQUEST_vars']['values']);
            unset($_REQUEST['button']);
            unset($_SESSION['_REQUEST_vars']['button']);
        } else {
            echo '<div align=center>There is only one college in the system so student cannot be transfered to any other college<br /><br>
                   <input type=button class="btn btn-default" value=Close onclick=\'window.close();\'></div>
                    </form>';
//            PopTableWindow('footer');


            unset($_REQUEST['values']);
            unset($_SESSION['_REQUEST_vars']['values']);
            unset($_REQUEST['button']);
            unset($_SESSION['_REQUEST_vars']['button']);
        }
    }
}

function custom_datepicker($id, $name) {
    $dt.= '<div class="input-group datepicker-group" id="original_date_' . $id . '" value="" style="">';
    $dt.= '<span class="input-group-addon"><i class="icon-calendar22"></i></span>';
    $dt.= '<input id="date_' . $id . '" placeholder="Select Date" value="" class="form-control daterange-single" type="text">';
    $dt.= '</div>';
    $dt.= '<input value="" id="monthSelect_date_' . $id . '" name="month_' . $name . '" type="hidden">';
    $dt.= '<input value="" id="daySelect_date_' . $id . '" name="day_' . $name . '" type="hidden">';
    $dt.= '<input value="" id="yearSelect_date_' . $id . '" name="year_' . $name . '" type="hidden">';
    echo $dt;
}

echo '<script type="text/javascript" src="assets/js/pages/picker_date.js"></script>';
