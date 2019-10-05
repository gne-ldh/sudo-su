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
include('../../RedirectModulesInc.php');
$next_syear = UserSyear()+1;
$_SESSION['DT'] = $DatabaseType; 
$_SESSION['DS'] = $DatabaseServer; 
$_SESSION['DU'] = $DatabaseUsername; 
$_SESSION['DP'] = $DatabasePassword; 
$_SESSION['DB'] = $DatabaseName; 
$_SESSION['DBP'] = $DatabasePort; 
$_SESSION['NY'] = $next_syear;


$tables = array('staff'=>'users','college_periods'=>'College Periods','college_years'=>'Marking Periods','college_calendars'=>'Calendars','report_card_grade_scales'=>'Report Card Grade Codes','courses'=>'Courses<b>*</b>','student_enrollment'=>'Students','report_card_comments'=>'Report Card Comment Codes','eligibility_activities'=>'Eligibility Activity Codes','attendance_codes'=>'Attendance Codes','student_enrollment_codes'=>'Student Enrollment Codes');
$no_college_tables = array('student_enrollment_codes'=>true,'staff'=>true);

$table_list = '<TABLE align=center>';
$table_list .= '<tr><td colspan=3 class=clear></td></tr>';
$table_list .= '<tr><td colspan=3>* You <i>must</i> roll users, college periods, marking periods, calendars, and report card<br>codes at the same time or before rolling courses<BR><BR>* You <i>must</i> roll courses at the same time or before rolling report card comments<BR><BR>Red items have already have data in the next college year (They might have been rolled).<BR><BR>Rolling red items will delete already existing data in the next college year.</td></tr>';
foreach($tables as $table=>$name)
{
	$exists_RET[$table] = DBGet(DBQuery('SELECT count(*) AS COUNT from '.$table.' WHERE SYEAR=\''.$next_syear.'\''.(!$no_college_tables[$table]?' AND COLLEGE_ID=\''.UserCollege().'\'':'')));
	if($exists_RET[$table][1]['COUNT']>0)
		$table_list .= '<TR><td width=1%></td><TD width=5%><INPUT type=checkbox value=Y name=tables['.$table.']></TD><TD width=94%>'.$name.' ('.$exists_RET[$table][1]['COUNT'].')</TD></TR>';
	else
		$table_list .= '<TR><td width=1%></td><TD width=5%><INPUT type=checkbox value=Y name=tables['.$table.'] CHECKED></TD><TD width=94%>'.$name.'</TD></TR>';
}
$table_list .= '</TABLE></CENTER><CENTER>';

DrawBC("College Setup > ".ProgramTitle());

if(Prompt_rollover('Confirm Rollover','Are you sure you want to roll the data for '.UserSyear().'-'.(UserSyear()+1).' to the next college year?',$table_list))
{
	if($_REQUEST['tables']['courses'] && ((!$_REQUEST['tables']['staff'] && $exists_RET['staff'][1]['COUNT']<1) || (!$_REQUEST['tables']['college_periods'] && $exists_RET['college_periods'][1]['COUNT']<1) || (!$_REQUEST['tables']['college_years'] && $exists_RET['college_years'][1]['COUNT']<1) || (!$_REQUEST['tables']['college_calendars'] && $exists_RET['college_calendars'][1]['COUNT']<1) || (!$_REQUEST['tables']['report_card_grade_scales'] && $exists_RET['report_card_grade_scales'][1]['COUNT']<1)))
		BackPrompt('You must roll users, college periods, marking periods, calendars, and report card codes at the same time or before rolling courses.');
	if($_REQUEST['tables']['report_card_comments'] && ((!$_REQUEST['tables']['courses'] && $exists_RET['courses'][1]['COUNT']<1)))
		BackPrompt('You must roll  courses at the same time or before rolling report card comments.');
	if(count($_REQUEST['tables']))
	{
		foreach($_REQUEST['tables'] as $table=>$value)
		{
			
			Rollover($table);
		}
	}
	
	
	DrawHeaderHome('<IMG SRC=assets/check.gif>The data have been rolled.','<input type=button onclick=document.location.href="index.php?modfunc=logout" value="Please login again" class=btn_large >');

	unset($_SESSION['_REQUEST_vars']['tables']);
	unset($_SESSION['_REQUEST_vars']['delete_ok']);	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
}

function Rollover($table)
{	global $next_syear;

	switch($table)
	{
		case 'staff':
			$user_custom='';
			$fields_RET = DBGet(DBQuery("SELECT ID FROM staff_fields"));
			foreach($fields_RET as $field)
				$user_custom .= ',CUSTOM_'.$field['ID'];
			DBQuery('DELETE FROM students_join_users WHERE STAFF_ID IN (SELECT STAFF_ID FROM staff WHERE SYEAR='.$next_syear.')');
			
			DBQuery('DELETE FROM program_user_config WHERE USER_ID IN (SELECT STAFF_ID FROM staff WHERE SYEAR='.$next_syear.')');
			DBQuery('DELETE FROM staff WHERE SYEAR=\''.$next_syear.'\'');

			DBQuery('INSERT INTO staff (SYEAR,CURRENT_COLLEGE_ID,TITLE,FIRST_NAME,LAST_NAME,MIDDLE_NAME,USERNAME,PASSWORD,PHONE,EMAIL,PROFILE,HOMEROOM,LAST_LOGIN,COLLEGES,PROFILE_ID,ROLLOVER_ID'.$user_custom.') SELECT SYEAR+1,CURRENT_COLLEGE_ID,TITLE,FIRST_NAME,LAST_NAME,MIDDLE_NAME,USERNAME,PASSWORD,PHONE,EMAIL,PROFILE,HOMEROOM,NULL,COLLEGES,PROFILE_ID,STAFF_ID'.$user_custom.' FROM staff WHERE SYEAR=\''.UserSyear().'\'');

			DBQuery('INSERT INTO program_user_config (USER_ID,PROGRAM,TITLE,VALUE) SELECT s.STAFF_ID,puc.PROGRAM,puc.TITLE,puc.VALUE FROM staff s,program_user_config puc WHERE puc.USER_ID=s.ROLLOVER_ID AND puc.PROGRAM=\'Preferences\' AND s.SYEAR=\''.$next_syear.'\'');

			

			DBQuery('INSERT INTO students_join_users (COLLEGE_ROLL_NO,STAFF_ID) SELECT j.COLLEGE_ROLL_NO,s.STAFF_ID FROM staff s,students_join_users j WHERE j.STAFF_ID=s.ROLLOVER_ID AND s.SYEAR=\''.$next_syear.'\'');
		break;

		case 'college_periods':
			DBQuery('DELETE FROM college_periods WHERE COLLEGE_ID=\''.UserCollege().'\' AND SYEAR=\''.$next_syear.'\'');
			DBQuery('INSERT INTO college_periods (SYEAR,COLLEGE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,ATTENDANCE,ROLLOVER_ID) SELECT SYEAR+1,COLLEGE_ID,SORT_ORDER,TITLE,SHORT_NAME,LENGTH,ATTENDANCE,PERIOD_ID FROM college_periods WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');
		break;

		case 'college_calendars':
			DBQuery('DELETE FROM college_calendars WHERE COLLEGE_ID=\''.UserCollege().'\' AND SYEAR=\''.$next_syear.'\'');
			DBQuery('INSERT INTO college_calendars (SYEAR,COLLEGE_ID,TITLE,DEFAULT_CALENDAR,ROLLOVER_ID) SELECT SYEAR+1,COLLEGE_ID,TITLE,DEFAULT_CALENDAR,CALENDAR_ID FROM college_calendars WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');
		break;

		case 'college_years':
			DBQuery('DELETE FROM college_progress_periods WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			DBQuery('DELETE FROM college_quarters WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			DBQuery('DELETE FROM college_semesters WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			DBQuery('DELETE FROM college_years WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');

			$r = DBGet(DBQuery('select max(m.marking_period_id) as marking_period_id from (select max(marking_period_id) as marking_period_id from college_years union select max(marking_period_id) as marking_period_id from college_semesters union select max(marking_period_id) as marking_period_id from college_quarters) m'));
			$mpi = $r[1]['MARKING_PERIOD_ID'] + 1;
		        DBQuery('ALTER TABLE marking_period_id_generator AUTO_INCREMENT = '.$mpi.'');
                         
			DBQuery('INSERT INTO college_years (MARKING_PERIOD_ID,SYEAR,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',SYEAR+1,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,date_add(START_DATE,INTERVAL 365 DAY),date_add(END_DATE,INTERVAL 365 DAY),date_add(POST_START_DATE,INTERVAL 365 DAY),date_add(POST_END_DATE,INTERVAL 365 DAY),DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM college_years WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');

                                                   
                        DBQuery('INSERT INTO college_semesters (MARKING_PERIOD_ID,YEAR_ID,SYEAR,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM college_years y WHERE y.SYEAR=s.SYEAR+1 AND y.ROLLOVER_ID=s.YEAR_ID),SYEAR+1,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,date_add(START_DATE, INTERVAL 365 DAY),date_add(END_DATE,INTERVAL 365 DAY),date_add(POST_START_DATE,INTERVAL 365 DAY),date_add(POST_END_DATE,INTERVAL 365 DAY),DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM college_semesters s WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');

                        DBQuery('INSERT INTO college_quarters (MARKING_PERIOD_ID,SEMESTER_ID,SYEAR,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM college_semesters s WHERE s.SYEAR=q.SYEAR+1 AND s.ROLLOVER_ID=q.SEMESTER_ID),SYEAR+1,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE+365,END_DATE+365,POST_START_DATE+365,POST_END_DATE+365,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM college_quarters q WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');

                        DBQuery('INSERT INTO college_progress_periods (MARKING_PERIOD_ID,QUARTER_ID,SYEAR,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT '.db_seq_nextval('marking_period_seq').',(SELECT MARKING_PERIOD_ID FROM college_quarters q WHERE q.SYEAR=p.SYEAR+1 AND q.ROLLOVER_ID=p.QUARTER_ID),SYEAR+1,COLLEGE_ID,TITLE,SHORT_NAME,SORT_ORDER,date_add(START_DATE,INTERVAL 365 DAY),date_add(END_DATE,INTERVAL 365 DAY),date_add(POST_START_DATE,INTERVAL 365 DAY),date_add(POST_END_DATE,INTERVAL 365 DAY),DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM college_progress_periods p WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');
		break;

		case 'courses':
			DBQuery('DELETE FROM course_subjects WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			
			DBQuery('DELETE FROM courses WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			DBQuery('DELETE FROM course_periods WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');

			// ROLL course_subjects
			DBQuery('INSERT INTO course_subjects (SYEAR,COLLEGE_ID,TITLE,SHORT_NAME,ROLLOVER_ID) SELECT SYEAR+1,COLLEGE_ID,TITLE,SHORT_NAME,SUBJECT_ID FROM course_subjects WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');

			// ROLL COURSE WEIGHTS
			DBQuery('INSERT INTO courses (SYEAR,SUBJECT_ID,COLLEGE_ID,GRADE_LEVEL,TITLE,SHORT_NAME,ROLLOVER_ID) SELECT SYEAR+1,(SELECT SUBJECT_ID FROM course_subjects s WHERE s.SYEAR=c.SYEAR+1 AND s.ROLLOVER_ID=c.SUBJECT_ID),COLLEGE_ID,GRADE_LEVEL,TITLE,SHORT_NAME,COURSE_ID FROM courses c WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');

			

			// ROLL course_periods
	
			DBQuery('INSERT INTO course_periods (SYEAR,COLLEGE_ID,COURSE_ID,COURSE_WEIGHT,TITLE,
SHORT_NAME,PERIOD_ID,MP,MARKING_PERIOD_ID,TEACHER_ID,ROOM,
TOTAL_SEATS,FILLED_SEATS,DOES_ATTENDANCE,GRADE_SCALE_ID,DOES_HONOR_ROLL,
DOES_CLASS_RANK,DOES_BREAKOFF,GENDER_RESTRICTION,HOUSE_RESTRICTION,CREDITS,
AVAILABILITY,DAYS,HALF_DAY,PARENT_ID,CALENDAR_ID,
ROLLOVER_ID) SELECT SYEAR+1,COLLEGE_ID,
(SELECT COURSE_ID FROM courses c WHERE c.SYEAR=p.SYEAR+1 AND c.ROLLOVER_ID=p.COURSE_ID),
COURSE_WEIGHT,TITLE,SHORT_NAME,(SELECT PERIOD_ID FROM college_periods n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.PERIOD_ID),MP,'.db_case(array('MP',"'FY'",'(SELECT MARKING_PERIOD_ID FROM college_years n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'SEM'",'(SELECT MARKING_PERIOD_ID FROM college_semesters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)',"'QTR'",'(SELECT MARKING_PERIOD_ID FROM college_quarters n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.MARKING_PERIOD_ID)')).',(SELECT STAFF_ID FROM staff n WHERE n.SYEAR=p.SYEAR+1 AND n.ROLLOVER_ID=p.TEACHER_ID),ROOM,TOTAL_SEATS,0 AS FILLED_SEATS,DOES_ATTENDANCE,(SELECT ID FROM report_card_grade_scales n WHERE n.ROLLOVER_ID=p.GRADE_SCALE_ID AND n.COLLEGE_ID='.UserCollege().'),DOES_HONOR_ROLL,DOES_CLASS_RANK,DOES_BREAKOFF,GENDER_RESTRICTION,HOUSE_RESTRICTION,CREDITS,AVAILABILITY,DAYS,HALF_DAY,PARENT_ID,(SELECT CALENDAR_ID FROM college_calendars n WHERE n.ROLLOVER_ID=p.CALENDAR_ID),COURSE_PERIOD_ID FROM course_periods p WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');

			$rowq=DBQUERY('SELECT * FROM course_periods  WHERE ROLLOVER_ID=PARENT_ID');
			DBQuery('UPDATE course_periods SET PARENT_ID=\''.$rowq['course_period_id'].'\' WHERE PARENT_ID IS NOT NULL AND SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
		break;

		case 'student_enrollment':
			$next_start_date = DBDate();
			DBQuery('DELETE FROM student_enrollment WHERE SYEAR=\''.$next_syear.'\' AND LAST_COLLEGE=\''.UserCollege().'\'');
			// ROLL STUDENTS TO NEXT GRADE
			DBQuery('INSERT INTO student_enrollment (SYEAR,COLLEGE_ID,COLLEGE_ROLL_NO,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_COLLEGE) SELECT SYEAR+1,COLLEGE_ID,COLLEGE_ROLL_NO,(SELECT NEXT_GRADE_ID FROM college_gradelevels g WHERE g.ID=e.GRADE_ID),\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,NULL AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM college_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),COLLEGE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.COLLEGE_ID=\''.UserCollege().'\' AND ((\''.DBDate().'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate().'\'>=e.START_DATE) AND e.NEXT_COLLEGE=\''.UserCollege().'\'');

			// ROLL STUDENTS WHO ARE TO BE RETAINED
			DBQuery('INSERT INTO student_enrollment (SYEAR,COLLEGE_ID,COLLEGE_ROLL_NO,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_COLLEGE) SELECT SYEAR+1,COLLEGE_ID,COLLEGE_ROLL_NO,GRADE_ID,\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,NULL AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM college_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),COLLEGE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.COLLEGE_ID=\''.UserCollege().'\' AND ((\''.DBDate().'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate().'\'>=e.START_DATE) AND e.NEXT_COLLEGE=\'0\'');

			// ROLL STUDENTS TO NEXT COLLEGE
			DBQuery('INSERT INTO student_enrollment (SYEAR,COLLEGE_ID,COLLEGE_ROLL_NO,GRADE_ID,START_DATE,END_DATE,ENROLLMENT_CODE,DROP_CODE,CALENDAR_ID,LAST_COLLEGE) SELECT SYEAR+1,NEXT_COLLEGE,COLLEGE_ROLL_NO,(SELECT g.ID FROM college_gradelevels g WHERE g.SORT_ORDER=1 AND g.COLLEGE_ID=e.NEXT_COLLEGE),\''.$next_start_date.'\' AS START_DATE,NULL AS END_DATE,NULL AS ENROLLMENT_CODE,NULL AS DROP_CODE,(SELECT CALENDAR_ID FROM college_calendars WHERE ROLLOVER_ID=e.CALENDAR_ID),COLLEGE_ID FROM student_enrollment e WHERE e.SYEAR=\''.UserSyear().'\' AND e.COLLEGE_ID=\''.UserCollege().'\' AND ((\''.DBDate().'\' BETWEEN e.START_DATE AND e.END_DATE OR e.END_DATE IS NULL) AND \''.DBDate().'\'>=e.START_DATE) AND e.NEXT_COLLEGE NOT IN (\''.UserCollege().'\',\'0\',\'-1\')');
		break;

		case 'report_card_grade_scales':
			DBQuery('DELETE FROM report_card_grade_scales WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			DBQuery('DELETE FROM report_card_grades WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			
                        DBQuery('INSERT INTO report_card_grade_scales (SYEAR,COLLEGE_ID,TITLE,COMMENT,SORT_ORDER,ROLLOVER_ID) SELECT SYEAR+1,COLLEGE_ID,TITLE,COMMENT,SORT_ORDER,ID FROM report_card_grade_scales WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			DBQuery('INSERT INTO report_card_grades (SYEAR,COLLEGE_ID,TITLE,COMMENT,BREAK_OFF,GPA_VALUE,GRADE_SCALE_ID,SORT_ORDER) SELECT SYEAR+1,COLLEGE_ID,TITLE,COMMENT,BREAK_OFF,GPA_VALUE,(SELECT ID FROM report_card_grade_scales WHERE ROLLOVER_ID=GRADE_SCALE_ID AND COLLEGE_ID=report_card_grades.COLLEGE_ID),SORT_ORDER FROM report_card_grades WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');
		break;

		case 'report_card_comments':
			DBQuery('DELETE FROM report_card_comments WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			DBQuery('INSERT INTO report_card_comments (SYEAR,COLLEGE_ID,TITLE,SORT_ORDER,COURSE_ID) SELECT SYEAR+1,COLLEGE_ID,TITLE,SORT_ORDER,'.db_case(array('COURSE_ID',"''",'NULL',"(SELECT COURSE_ID FROM courses WHERE ROLLOVER_ID=rc.COURSE_ID)")).' FROM report_card_comments rc WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');
		break;

		case 'eligibility_activities':
		case 'attendance_codes':
			DBQuery('DELETE FROM '.$table.' WHERE SYEAR=\''.$next_syear.'\' AND COLLEGE_ID=\''.UserCollege().'\'');
			$table_properties = db_properties($table);
			$columns = '';
			foreach($table_properties as $column=>$values)
			{
				if($column!='ID' && $column!='SYEAR')
					$columns .= ','.$column;
			}
			DBQuery('INSERT INTO '.$table.' (SYEAR'.$columns.') SELECT SYEAR+1'.$columns.' FROM '.$table.' WHERE SYEAR=\''.UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\'');
		break;

		// DOESN'T HAVE A COLLEGE_ID
		case 'student_enrollment_codes':
			DBQuery('DELETE FROM '.$table.' WHERE SYEAR=\''.$next_syear.'\'');
			$table_properties = db_properties($table);
			$columns = '';
			foreach($table_properties as $column=>$values)
			{
				if($column!='ID' && $column!='SYEAR')
					$columns .= ','.$column;
			}
			DBQuery('INSERT INTO '.$table.' (SYEAR'.$columns.') SELECT SYEAR+1'.$columns.' FROM '.$table.' WHERE SYEAR=\''.UserSyear().'\'');
		break;
	}
	

		// ---------------------------------------------------------------------- data write start ----------------------------------------------------------------------- //
			$string .= "<"."?php \n";
			$string .= "$"."DatabaseType = '".$_SESSION['DT']."'; \n"	;
			$string .= "$"."DatabaseServer = '".$_SESSION['DS']."'; \n"	;
			$string .= "$"."DatabaseUsername = '".$_SESSION['DU']."'; \n" ;
			$string .= "$"."DatabasePassword = '".$_SESSION['DP']."'; \n";
			$string .= "$"."DatabaseName = '".$_SESSION['DB']."'; \n";
			$string .= "$"."DatabasePort = '".$_SESSION['DBP'] ."'; \n";
			$string .= "$"."DefaultSyear = '".$_SESSION['NY']."'; \n";
			$string .="?".">";
			
			$err = "Can't write to file";
			
			$myFile = "Data.php";
			$fh = fopen($myFile, 'w') or exit($err);
			fwrite($fh, $string);
			fclose($fh);
		// ---------------------------------------------------------------------- data write end ------------------------------------------------------------------------ //
		
	
}

?>