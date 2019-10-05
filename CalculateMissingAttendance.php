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
error_reporting(0);
include('RedirectRootInc.php');
include 'Warehouse.php';
include 'Data.php';
$syear = $_SESSION['UserSyear'];
$flag=FALSE;
$RET=DBGet(DBQuery('SELECT COLLEGE_ID,COLLEGE_DATE,COURSE_PERIOD_ID,TEACHER_ID,SECONDARY_TEACHER_ID FROM missing_attendance WHERE SYEAR=\''.  UserSyear().'\' AND COLLEGE_ID=\''.UserCollege().'\' LIMIT 0,1'));
 if (count($RET))
{
     $flag=TRUE;
 }
$last_update=DBGet(DBQuery('SELECT VALUE FROM program_config WHERE PROGRAM=\'MissingAttendance\' AND TITLE=\'LAST_UPDATE\' AND SYEAR=\''.$syear.'\' AND COLLEGE_ID=\''.UserCollege().'\''));
$last_update=trim($last_update[1]['VALUE']);

DBQuery("INSERT INTO missing_attendance(COLLEGE_ID,SYEAR,COLLEGE_DATE,COURSE_PERIOD_ID,PERIOD_ID,TEACHER_ID,SECONDARY_TEACHER_ID) 
        SELECT s.ID AS COLLEGE_ID,acc.SYEAR,acc.COLLEGE_DATE,cp.COURSE_PERIOD_ID,cpv.PERIOD_ID, IF(tra.course_period_id=cp.course_period_id AND acc.college_date<tra.assign_date =true,tra.pre_teacher_id,cp.teacher_id) AS TEACHER_ID,
        cp.SECONDARY_TEACHER_ID FROM attendance_calendar acc INNER JOIN course_periods cp ON cp.CALENDAR_ID=acc.CALENDAR_ID INNER JOIN course_period_var cpv ON cp.COURSE_PERIOD_ID=cpv.COURSE_PERIOD_ID 
        AND (cpv.COURSE_PERIOD_DATE IS NULL AND position(substring('UMTWHFS' FROM DAYOFWEEK(acc.COLLEGE_DATE) FOR 1) IN cpv.DAYS)>0 OR cpv.COURSE_PERIOD_DATE IS NOT NULL AND cpv.COURSE_PERIOD_DATE=acc.COLLEGE_DATE)
        INNER JOIN colleges s ON s.ID=acc.COLLEGE_ID LEFT JOIN teacher_reassignment tra ON (cp.course_period_id=tra.course_period_id) INNER JOIN schedule sch ON sch.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID 
        AND sch.college_roll_no IN(SELECT college_roll_no FROM student_enrollment se WHERE sch.college_id=se.college_id AND sch.syear=se.syear AND start_date<=acc.college_date AND (end_date IS NULL OR end_date>=acc.college_date))
        AND (cp.MARKING_PERIOD_ID IN (SELECT MARKING_PERIOD_ID FROM college_years WHERE COLLEGE_ID=acc.COLLEGE_ID AND acc.COLLEGE_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM college_semesters WHERE COLLEGE_ID=acc.COLLEGE_ID AND acc.COLLEGE_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM college_quarters WHERE COLLEGE_ID=acc.COLLEGE_ID AND acc.COLLEGE_DATE BETWEEN START_DATE AND END_DATE) or cp.MARKING_PERIOD_ID is NULL OR acc.college_date BETWEEN cp.begin_date AND cp.end_date)
        AND sch.START_DATE<=acc.COLLEGE_DATE AND (sch.END_DATE IS NULL OR sch.END_DATE>=acc.COLLEGE_DATE ) AND cpv.DOES_ATTENDANCE='Y' AND acc.COLLEGE_DATE<=CURDATE() AND acc.COLLEGE_DATE > '".$last_update."' AND acc.syear=$syear AND acc.COLLEGE_ID='".UserCollege()."' 
        AND NOT EXISTS (SELECT '' FROM  attendance_completed ac WHERE ac.COLLEGE_DATE=acc.COLLEGE_DATE AND ac.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND ac.PERIOD_ID=cpv.PERIOD_ID) 
        GROUP BY acc.COLLEGE_DATE,cp.COURSE_PERIOD_ID,cpv.PERIOD_ID");

DBQuery("UPDATE program_config SET VALUE=CURDATE() WHERE PROGRAM='MissingAttendance' AND TITLE='LAST_UPDATE'");

$RET=DBGet(DBQuery("SELECT COLLEGE_ID,COLLEGE_DATE,COURSE_PERIOD_ID,TEACHER_ID,SECONDARY_TEACHER_ID FROM missing_attendance WHERE SYEAR='".  UserSyear()."' LIMIT 0,1"));
 if (count($RET) && $flag==FALSE)
{
     echo '<span style="display:none">NEW_MI_YES</span>';
 }
if(count($RET))
echo '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>Missing attendance data list created.</div>';

?>
