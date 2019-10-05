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
 $var1= explode("?",$_SERVER['REQUEST_URI']);
include("functions/ParamLibFnc.php");
$url=validateQueryString($var1[1]);
if($url===FALSE || !$var1[1])
{
 header('Location: index.php');
}
error_reporting(1);
include "Warehouse.php";
$tmp_REQUEST = $_REQUEST;
$_SESSION['Side_PHP_SELF'] = "Side.php";

$old_college = UserCollege();
$old_syear = UserSyear();
$old_period = UserCoursePeriod();

$btnn =optional_param('btnn','',PARAM_SPCL);

$nsc = optional_param('nsc','',PARAM_SPCL);
unset($_SESSION['smc']);
unset($_SESSION['g']);
unset($_SESSION['p']);
unset($_SESSION['smn']);
unset($_SESSION['sm']);
unset($_SESSION['sma']);
unset($_SESSION['smv']);
unset($_SESSION['s']);
unset($_SESSION['_search_all']);
unset($_SESSION['custom_count_sql']);
unset($_SESSION['inactive_stu_filter']);
unset($_SESSION['new_sql']);
unset($_SESSION['newsql']);
unset($_SESSION['newsql1']);
unset($_SESSION['ASSIGNMENT_DESCRIPTION']);
if(isset($_SESSION['stu_search']['sql']))
{
    unset($_SESSION['stu_search']);
}
if(isset ($_SESSION['staf_search']['sql']))
{
    unset ($_SESSION['staf_search']['sql']);
}

	

if((optional_param('college','',PARAM_SPCL) && optional_param('college','',PARAM_SPCL)!=$old_college) || (optional_param('period','',PARAM_SPCL) && optional_param('period','',PARAM_SPCL)!=$old_period))
{ 
	unset($_SESSION['college_roll_no']);
	$_SESSION['unset_student'] = true;
	unset($_SESSION['staff_id']);

	unset($_REQUEST['mp']);
}


if(optional_param('modfunc','',PARAM_SPCL)=='update' && $_POST)
{
	
	//===================
		
	
	
	if(User('PROFILE')=='teacher' &&(optional_param('college','',PARAM_SPCL)!=$old_college || $nsc=='NT'))
 	{
	
		if(optional_param('act','',PARAM_SPCL)=='college')
		{
			
			 $_SESSION['UserCollege'] = optional_param('college','',PARAM_SPCL);
 			DBQuery("UPDATE staff SET CURRENT_COLLEGE_ID='".UserCollege()."' WHERE STAFF_ID='".User('STAFF_ID')."'");
  		
                                        unset($_SESSION['UserSyear']);
		unset($_SESSION['UserMP']);
		unset($_SESSION['UserSubject']);
		unset($_SESSION['UserCourse']);
		unset($_SESSION['UserCoursePeriod']);
		
                                        $college_years_RET=DBGet(DBQuery("SELECT MAX(sy.SYEAR) AS SYEAR FROM college_years sy,staff s INNER JOIN staff_college_relationship ssr USING(staff_id) WHERE ssr.college_id=sy.college_id AND sy.syear=ssr.syear AND sy.COLLEGE_ID=".UserCollege()." AND  STAFF_ID='$_SESSION[STAFF_ID]'"));
                                        $_SESSION['UserSyear']=$college_years_RET[1]['SYEAR'];

                                        $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_quarters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                                        if(!isset($_SESSION['UserMP']))
                                        {
                                                $_SESSION['UserMP'] = GetCurrentMP('QTR',DBDate());
		}
                                        if(!$RET)
                                        {
                                            $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_semesters WHERE COLLEGE_ID='" . UserCollege() . "' AND SYEAR='" . UserSyear() . "' ORDER BY SORT_ORDER"));
                                            if (!isset($_SESSION['UserMP'])) 
                                            {
                                                $_SESSION['UserMP'] = GetCurrentMP('SEM', DBDate());
                                            }
                                        }

                                        if(!$RET)
                                        {
                                            $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_years WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                                            if  (!isset($_SESSION['UserMP'])) 
                                            {
                                                $_SESSION['UserMP'] = GetCurrentMP('FY', DBDate());
                                            }	
                                        }
		
		}
		
		elseif(optional_param('act','',PARAM_SPCL)=='syear')
		{
                                        unset($_SESSION['college_roll_no']);
                                        unset($_SESSION['_REQUEST_vars']['college_roll_no']);
                                        $_SESSION['UserSyear'] = optional_param('syear','',PARAM_SPCL);
			unset($_SESSION['UserMP']);
			unset($_SESSION['UserSubject']);
			unset($_SESSION['UserCourse']);
			unset($_SESSION['UserCoursePeriod']);
                                        $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_quarters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                                        if(!isset($_SESSION['UserMP']))
                                        {
                                                $_SESSION['UserMP'] = GetCurrentMP('QTR',DBDate());
                                        }	
                                        if(!$RET)
                                        {
                                            $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_semesters WHERE COLLEGE_ID='" . UserCollege() . "' AND SYEAR='" . UserSyear() . "' ORDER BY SORT_ORDER"));
                                            if (!isset($_SESSION['UserMP'])) {
                                                $_SESSION['UserMP'] = GetCurrentMP('SEM', DBDate());
                                            }
                                        }
			
                                        if(!$RET)
                                        {
                                            $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_years WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                                            if  (!isset($_SESSION['UserMP'])) {
                                                $_SESSION['UserMP'] = GetCurrentMP('FY', DBDate());
		}
                                        }
			
		}
		
		elseif(optional_param('act','',PARAM_SPCL)=='mp')
		{
			
			$_SESSION['UserMP'] = optional_param('mp','',PARAM_SPCL);
			unset($_SESSION['UserSubject']);
			unset($_SESSION['UserCourse']);
			unset($_SESSION['UserCoursePeriod']);
		}
		
		elseif(optional_param('act','',PARAM_SPCL)=='subject')
		{
			$_SESSION['UserSubject'] = optional_param('subject','',PARAM_SPCL);
			unset($_SESSION['UserCourse']);
			unset($_SESSION['UserCoursePeriod']);
                        unset($_SESSION['CpvId']);
  		}
		
		elseif(optional_param('act','',PARAM_SPCL)=='course')
		{
			$_SESSION['UserCourse'] =clean_param($_REQUEST['course'],PARAM_NOTAGS);
                        unset($_SESSION['UserCoursePeriod']);
                        unset($_SESSION['CpvId']);
			unset($_SESSION['UserCoursePeriod']);
		}
		
		elseif(optional_param('act','',PARAM_SPCL)=='period')
		{
                    $_SESSION['CpvId']=optional_param('period','',PARAM_SPCL);
		}

	
	}
	
	//===================
 
                   if (User('PROFILE') == 'admin' && (clean_param($_REQUEST['college'], PARAM_NOTAGS) != $old_college || $nsc == 'NT')) 
                   {
                        
                       
                       if ($_POST['college']) 
                        {
                            $_SESSION['UserCollege'] = clean_param($_REQUEST['college'], PARAM_NOTAGS);
                            DBQuery("UPDATE staff SET CURRENT_COLLEGE_ID='" . UserCollege() . "' WHERE STAFF_ID='" . $_SESSION['STAFF_ID']. "'");
                            unset($_SESSION['UserSyear']);
                            unset($_SESSION['UserMP']);
 
                            $college_years_RET=DBGet(DBQuery("SELECT MAX(sy.SYEAR) AS SYEAR FROM college_years sy,staff_college_relationship ssr WHERE ssr.SYEAR=sy.SYEAR AND sy.COLLEGE_ID=".UserCollege()." AND ssr.STAFF_ID='$_SESSION[STAFF_ID]'"));
                            $_SESSION['UserSyear']=$college_years_RET[1]['SYEAR'];
 
                            $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_quarters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                            if(!isset($_SESSION['UserMP']))
                            {
                                    $_SESSION['UserMP'] = GetCurrentMP('QTR',DBDate());
                            }	
                            if(!$RET)
                            {
                                $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_semesters WHERE COLLEGE_ID='" . UserCollege() . "' AND SYEAR='" . UserSyear() . "' ORDER BY SORT_ORDER"));
                                if (!isset($_SESSION['UserMP'])) {
                                    $_SESSION['UserMP'] = GetCurrentMP('SEM', DBDate());
                                }
                            }
 
                            if(!$RET)
                            {
                                $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_years WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                                if  (!isset($_SESSION['UserMP'])) {
                                    $_SESSION['UserMP'] = GetCurrentMP('FY', DBDate());
                                }
                            }
                        } 
	
                        elseif ($_POST['syear']) 
                        {
                            unset($_SESSION['college_roll_no']);
                            unset($_SESSION['_REQUEST_vars']['college_roll_no']);
                            $_SESSION['UserSyear'] = optional_param('syear', '', PARAM_SPCL);
                            unset($_SESSION['UserMP']);
                            $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_quarters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                            if(!isset($_SESSION['UserMP']))
                            {
                                    $_SESSION['UserMP'] = GetCurrentMP('QTR',DBDate());
                            }	
                            if(!$RET)
                            {
                                $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_semesters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                                if(!isset($_SESSION['UserMP']))
                                {
                                        $_SESSION['UserMP'] = GetCurrentMP('SEM',DBDate());
                                }	
                            }

                            if(!$RET)
                            {
                                $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_years WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                                if(!isset($_SESSION['UserMP']))
                                {
                                        $_SESSION['UserMP'] = GetCurrentMP('FY',DBDate());
                                }	
                            }
                        }
                        
                        elseif ($_POST['mp']) 
                        {
                            $_SESSION['UserMP'] = optional_param('mp', '', PARAM_SPCL);
                        }
                    }
	
	

    if (User('PROFILE') == 'parent' || User('PROFILE')=='student') 
         {
        if ($_POST['syear'])
        {
            $_SESSION['UserSyear'] = optional_param('syear', '', PARAM_SPCL);
            unset ($_SESSION['UserMP']);
            $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_quarters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
            if(!isset($_SESSION['UserMP']))
            {
                    $_SESSION['UserMP'] = GetCurrentMP('QTR',DBDate());
         }
            if(!$RET)
            {
                $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_semesters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                if(!isset($_SESSION['UserMP']))
                {
                        $_SESSION['UserMP'] = GetCurrentMP('SEM',DBDate());
                }	
            }

            if(!$RET)
            {
                $RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_years WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
                if(!isset($_SESSION['UserMP']))
                {
                        $_SESSION['UserMP'] = GetCurrentMP('FY',DBDate());
                }	
            }
		
        }
        elseif($_POST['college_roll_no'])
	{
            unset ($_SESSION['UserMP']);
            $_SESSION['college_roll_no'] = optional_param('college_roll_no', '', PARAM_ALPHANUM);
        }
        elseif($_POST['mp'])
        {
             $_SESSION['UserMP'] = optional_param('mp', '', PARAM_SPCL);
        }

        
	}
        $ses_modname['vars']['modname']='miscellaneous/Portal.php';
        unset($_SESSION['_REQUEST_vars']);
	echo "<script language=javascript>window.location.href='".str_replace('&amp;','&',PreparePHP_SELF($ses_modname['vars']))."';</script>";
}//---------------------------- Updaet End-------------------------------------------



if(!$_SESSION['college_roll_no'] && User('PROFILE')=='student')
	$_SESSION['college_roll_no'] = $_SESSION['COLLEGE_ROLL_NO'];

if(!$_SESSION['UserCollege'])
{
	if(User('PROFILE')=='admin' && (!User('COLLEGES') || strpos(User('COLLEGES'),','.User('CURRENT_COLLEGE_ID').',')!==false))
		$_SESSION['UserCollege'] = User('CURRENT_COLLEGE_ID');
	elseif(User('PROFILE')=='student')
		$_SESSION['UserCollege'] = trim(User('COLLEGES'),',');
	elseif(User('PROFILE')=='teacher')
	{
		
            $mp = GetAllMP('QTR',UserMP());

            if(!isset($mp))
              $mp = GetAllMP('SEM',UserMP());

            if(!isset($mp))
              $mp = GetAllMP('FY',UserMP());
            
            
                $QI = DBQuery("SELECT cp.COLLEGE_ID FROM course_periods cp, college_periods sp,courses c WHERE c.COURSE_ID=cp.COURSE_ID AND cp.PERIOD_ID=sp.PERIOD_ID AND cp.SYEAR='".UserSyear()."' AND cp.TEACHER_ID='".User('STAFF_ID')."''".(UserMP()?' AND cp.MARKING_PERIOD_ID IN ('.$mp.')':'')."' ORDER BY sp.SORT_ORDER LIMIT 1");
		$RET = DBGet($QI);
		$_SESSION['UserCollege'] = $RET[1]['COLLEGE_ID'];
	}
}


if((optional_param('college','',PARAM_SPCL) && optional_param('college','',PARAM_SPCL) != $old_college) || (optional_param('syear','',PARAM_SPCL) && optional_param('syear','',PARAM_SPCL) != $old_syear))
{
	unset($_SESSION['UserPeriod']);
	unset($_SESSION['UserCoursePeriod']);
}


if(optional_param('college_roll_no','',PARAM_ALPHANUM)=='new')
{
	unset($_SESSION['college_roll_no']);
        unset($_SESSION['students_order']);
	unset($_SESSION['_REQUEST_vars']['college_roll_no']);
	unset($_SESSION['_REQUEST_vars']['search_modfunc']);
	
	#------------- New added ----------------------------#
	
	if($_SESSION['_REQUEST_vars']['modfunc']=='choose_course')
    	unset($_SESSION['_REQUEST_vars']['modfunc']);
	#------------- New added ----------------------------#
        #------------- New added on 09.12.2011 For removing old date----------------------------#
	if($_SESSION['_REQUEST_vars']['modname']=='attendance/Administration.php')
                 {
                      unset($_SESSION['_REQUEST_vars']['month_date']);
                      unset($_SESSION['_REQUEST_vars']['day_date']);
                      unset($_SESSION['_REQUEST_vars']['year_date']);
                 }
        #------------- New added on 09.12.2011 ----------------------------#
	
	echo "<script language=javascript>window.location.href='".str_replace('&amp;','&',PreparePHP_SELF($_SESSION['_REQUEST_vars']))."';</script>";
}

if(optional_param('college_id','',PARAM_ALPHANUM)=='new')
{
	unset($_SESSION['UserCollege']);
	echo "<script language=javascript>window.location.href='".str_replace('&amp;','&',PreparePHP_SELF($_SESSION['_REQUEST_vars']))."';</script>";
	
}


if(optional_param('staff_id','',PARAM_ALPHANUM)=='new')
{
	unset($_SESSION['miss_attn']);
	unset($_SESSION['staff_id']);
	unset($_SESSION['_REQUEST_vars']['staff_id']);
	unset($_SESSION['_REQUEST_vars']['search_modfunc']);
        $usr_modname['vars']['modname']=$_SESSION['_REQUEST_vars']['modname'];
        unset($_SESSION['_REQUEST_vars']);
	echo "<script language=javascript>window.location.href='".str_replace('&amp;','&',PreparePHP_SELF($usr_modname['vars']))."'</script>";
}
unset($_REQUEST['modfunc']);

echo "
<HTML>
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
		<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />
		<link rel=stylesheet type=\"text/css\" href=\"styles/Stylesheet.css\">
		<script language=\"JavaScript\" type=\"text/javascript\">
		
		</script>
		<title>".Config('TITLE')."</title>
	</head>
	<BODY background=assets/themes/".Preferences('THEME')."/bg.jpg leftmargin=6 marginwidth=4 topmargin=0 ".(clean_param($_REQUEST['modcat'],PARAM_NOTAGS)?"onload=openMenu('".clean_param($_REQUEST['modcat'],PARAM_NOTAGS)."');":'').">";

// User Information
echo "<TABLE border=0 cellpadding=0 cellspacing=0 width=100%><TR><TD height=30>";
echo '<A HREF=index.php target=_top>'.DrawPNG('themes/'.Preferences('THEME').'/logo.png','border=0 width=160').'</A>';
echo "</TD></TR><TR>";
echo "<TD class=BoxContents style='border: inset #C9C9C9 2px; background-image:url(\"assets/bg.gif\")' width=100% valign=top>
	<FORM action=Side.php?modfunc=update method=POST>
	<INPUT type=hidden name=modcat value='' id=modcat_input>
	<b>".User('NAME')."</b>
	<BR>".date('l F j, Y')."
	<BR>";
if(User('PROFILE')=='admin')
{
	$colleges = substr(str_replace(",","','",User('COLLEGES')),2,-2);
	$QI = DBQuery("SELECT ID,TITLE FROM colleges".($colleges?" WHERE ID IN ($colleges)":''));
	$RET = DBGet($QI);



	echo "<SELECT name=college onChange='document.forms[0].submit();' style='width:150;'>";
	foreach($RET as $college)
		echo "<OPTION value=$college[ID]".((UserCollege()==$college['ID'])?' SELECTED':'').">".$college['TITLE']."</OPTION>";

	echo "</SELECT><BR>";
}

if(1)
{
if(User('PROFILE')!='student')
	$sql = "SELECT DISTINCT sy.SYEAR FROM college_years sy,staff s,staff_college_relationship ssr, login_authentication la WHERE s.STAFF_ID=la.USER_ID AND s.PROFILE_ID=la.PROFILE_ID AND ssr.SYEAR=sy.SYEAR AND s.STAFF_ID=ssr.STAFF_ID AND la.USERNAME=(SELECT USERNAME FROM login_authentication WHERE USER_ID='$_SESSION[STAFF_ID]' AND PROFILE_ID='$_SESSION[PROFILE_ID]')";
else
	$sql = "SELECT DISTINCT sy.SYEAR FROM college_years sy,student_enrollment se WHERE se.SYEAR=sy.SYEAR AND se.COLLEGE_ROLL_NO='$_SESSION[COLLEGE_ROLL_NO]'";
$years_RET = DBGet(DBQuery($sql));
}
else
$years_RET = array(1=>array('SYEAR'=>"$DefaultSyear"));

echo "<SELECT name=syear onChange='document.forms[0].submit();'>";
foreach($years_RET as $year)
	echo "<OPTION value=$year[SYEAR]".((UserSyear()==$year['SYEAR'])?' SELECTED':'').">$year[SYEAR]-".($year['SYEAR']+1)."</OPTION>";
echo '</SELECT><BR>';

if(User('PROFILE')=='parent')
{
	$RET = DBGet(DBQuery("SELECT sju.COLLEGE_ROLL_NO, CONCAT(s.LAST_NAME,', ',s.FIRST_NAME) AS FULL_NAME,se.COLLEGE_ID FROM students s,students_join_people sju, student_enrollment se WHERE s.COLLEGE_ROLL_NO=sju.COLLEGE_ROLL_NO AND sju.PERSON_ID='".User('STAFF_ID')."' AND se.SYEAR=".UserSyear()." AND se.COLLEGE_ROLL_NO=sju.COLLEGE_ROLL_NO AND (('".DBDate()."' BETWEEN se.START_DATE AND se.END_DATE OR se.END_DATE IS NULL) AND '".DBDate()."'>=se.START_DATE)"));

	if(!UserStudentID())
		$_SESSION['college_roll_no'] = $RET[1]['COLLEGE_ROLL_NO'];

	echo "<SELECT name=college_roll_no onChange='document.forms[0].submit();'>";
	if(count($RET))
	{
		foreach($RET as $student)
		{
			echo "<OPTION value=$student[COLLEGE_ROLL_NO]".((UserStudentID()==$student['COLLEGE_ROLL_NO'])?' SELECTED':'').">".$student['FULL_NAME']."</OPTION>";
			if(UserStudentID()==$student['COLLEGE_ROLL_NO'])
				$_SESSION['UserCollege'] = $student['COLLEGE_ID'];
		}
	}
	echo "</SELECT><BR>";

	if(!UserMP())
		$_SESSION['UserMP'] = GetCurrentMP('QTR',DBDate());
}



$RET = DBGet(DBQuery("SELECT MARKING_PERIOD_ID,TITLE FROM college_quarters WHERE COLLEGE_ID='".UserCollege()."' AND SYEAR='".UserSyear()."' ORDER BY SORT_ORDER"));
echo "<SELECT name=mp onChange='document.forms[0].submit();'>";
if(count($RET))
{
	if(!UserMP())
		$_SESSION['UserMP'] = $RET[1]['MARKING_PERIOD_ID'];

	foreach($RET as $quarter)
		echo "<OPTION value=$quarter[MARKING_PERIOD_ID]".(UserMP()==$quarter['MARKING_PERIOD_ID']?' SELECTED':'').">".$quarter['TITLE']."</OPTION>";
}
echo "</SELECT>";
echo '</FORM>';
if(UserStudentID() && User('PROFILE')!='parent' && User('PROFILE')!='student')
{
	$RET = DBGet(DBQuery("SELECT FIRST_NAME,LAST_NAME,MIDDLE_NAME,NAME_SUFFIX FROM students WHERE COLLEGE_ROLL_NO='".UserStudentID()."'"));
	
	
	echo '<TABLE border=0 cellpadding=0 cellspacing=0 width=100%><TR><TD width=19 valign=middle><A HREF=Side.php?college_roll_no=new&modcat='.optional_param('modcat','',PARAM_SPCL).'><IMG SRC=assets/x.gif height=17 border=0></A></TD><TD ><B><A HREF=Modules.php?modname=students/Student.php&college_roll_no='.UserStudentID().' target=body><font color=#FFFFFF size=-2>'.$RET[1]['FIRST_NAME'].'&nbsp;'.($RET[1]['MIDDLE_NAME']?$RET[1]['MIDDLE_NAME'].' ':'').$RET[1]['LAST_NAME'].'&nbsp;'.$RET[1]['NAME_SUFFIX'].'</font></A></B></TD></TR></TABLE>';
}
if(UserStaffID() && User('PROFILE')=='admin')
{
	if(UserStudentID())
		echo '<IMG SRC=assets/pixel_trans.gif height=2>';
	$RET = DBGet(DBQuery("SELECT FIRST_NAME,LAST_NAME FROM staff WHERE STAFF_ID='".UserStaffID()."'"));
	
	
	echo '<TABLE border=0 cellpadding=0 cellspacing=0 width=100%><TR><TD bgcolor=#336633 width=19 valign=middle><A HREF=Side.php?staff_id=new&modcat='.optional_param('modcat','',PARAM_SPCL).'><IMG SRC=assets/x.gif height=17 border=0></A></TD><TD bgcolor=#336633><B><A HREF=Modules.php?modname=users/User.php&staff_id='.UserStaffID().' target=body><font color=#FFFFFF size=-2>'.$RET[1]['FIRST_NAME'].'&nbsp;'.$RET[1]['LAST_NAME'].'</font></A></B></TD></TR></TABLE>';
}
echo '<BR>';
echo '</TD></TR></TABLE>';
echo '</BODY>';
echo '</HTML>';
?>