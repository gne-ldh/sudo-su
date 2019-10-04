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
unset($_SESSION['_REQUEST_vars']['values']);
unset($_SESSION['_REQUEST_vars']['modfunc']);
DrawBC("College Setup > " . ProgramTitle());
// --------------------------------------------------------------- Test SQL ------------------------------------------------------------------ //
// --------------------------------------------------------------- Tset SQL ------------------------------------------------------------------ //

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update' && (clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == 'Save' || clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == 'Update' || clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == '')) {
    if (clean_param($_REQUEST['values'], PARAM_NOTAGS) && $_POST['values'] && User('PROFILE') == 'admin') {
        if ($_REQUEST['new_college'] != 'true') {

            $sql = 'UPDATE colleges SET ';


            foreach ($_REQUEST as $col => $val) {
                $dt_ex = explode("_", $col);
                if ($dt_ex[0] == 'month') {
                    if ($_REQUEST['day_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] != '' && $_REQUEST['month_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] != '' && $_REQUEST['year_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] != '') {
//                        $_REQUEST['values']['CUSTOM_' . $dt_ex[1]] = $_REQUEST['year_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] . "-" . MonthFormatter($_REQUEST['month_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]]) . '-' . $_REQUEST['day_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]];
               $_REQUEST['values']['CUSTOM_' . $dt_ex[1]] = $_REQUEST['year_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] . "-" . $_REQUEST['month_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] . '-' . $_REQUEST['day_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]];
                        }
            }
            }

            foreach ($_REQUEST['values'] as $column => $value) {
                if (substr($column, 0, 6) == 'CUSTOM') {
                    $custom_id = str_replace("CUSTOM_", "", $column);
                    $custom_RET = DBGet(DBQuery("SELECT TITLE,TYPE,REQUIRED FROM college_custom_fields WHERE ID=" . $custom_id));

                    $custom = DBGet(DBQuery("SHOW COLUMNS FROM colleges WHERE FIELD='" . $column . "'"));
                    $custom = $custom[1];
                    if ($custom['NULL'] == 'NO' && trim($value) == '' && $custom['DEFAULT']) {
                        $value = $custom['DEFAULT'];
                    } else if ($custom['NULL'] == 'NO' && $value == '' && $custom_RET[1]['REQUIRED'] == 'Y') {
                        $custom_TITLE = $custom_RET[1]['TITLE'];
                        echo "<font color=red><b>Unable to save data, because " . $custom_TITLE . ' is required.</b></font><br/>';
                        $error = true;
                        break;
                    } else if ($custom_RET[1]['TYPE'] == 'numeric' && (!is_numeric($value) && $value != '')) {
                        $custom_TITLE = $custom_RET[1]['TITLE'];
                        echo "<font color=red><b>Unable to save data, because " . $custom_TITLE . ' is Numeric type.</b></font><br/>';
                        $error = true;
                    } else {
                        $m_custom_RET = DBGet(DBQuery("select ID,TITLE,TYPE from college_custom_fields WHERE ID='" . $custom_id . "' AND TYPE='multiple'"));
                        if ($m_custom_RET) {
                            $str = "";
                            foreach ($value as $m_custom_val) {
                                if ($m_custom_val)
                                    $str.="||" . $m_custom_val;
                            }
                            if ($str)
                                $value = $str . "||";
                            else {
                                $value = '';
                            }
                        }
                    }
                }  ###Custom Ends#####
                if ($column != 'WWW_ADDRESS')
                $value = paramlib_validation($column, trim($value));
//                                ',\''.singleQuoteReplace('','',trim($value)).'\''
                if (stripos($_SERVER['SERVER_SOFTWARE'], 'linux')) {
                    $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                } else {
                    $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                }
            }
            $sql = substr($sql, 0, -1) . ' WHERE ID=\'' . UserCollege() . '\'';
           
            if ($error != 1)
                DBQuery($sql);
            //echo '<script language=JavaScript>parent.side.location="' . $_SESSION['Side_PHP_SELF'] . '?modcat="+parent.side.document.forms[0].modcat.value;</script>';
            $note[] = 'This college has been modified.';
            $_REQUEST['modfunc'] = '';
        }
        else {
            $fields = $values = '';

            foreach ($_REQUEST['values'] as $column => $value)
                if ($column != 'ID' && $value) {
                    if ($column != 'WWW_ADDRESS')
                    $value = paramlib_validation($column, trim($value));
                    $fields .= ',' . $column;
                    $values .= ',\'' . singleQuoteReplace('', '', trim($value)) . '\'';
                }

            if ($fields && $values) {


                $id = DBGet(DBQuery('SHOW TABLE STATUS LIKE \'colleges\''));
                $id = $id[1]['AUTO_INCREMENT'];

                
                $start_date=$_REQUEST['year__min'].'-'.$_REQUEST['month__min'].'-'.$_REQUEST['day__min'];
                $end_date=$_REQUEST['year__max'].'-'.$_REQUEST['month__max'].'-'.$_REQUEST['day__max'];
                $syear=$_REQUEST['year__min'];
                $sql = 'INSERT INTO colleges (SYEAR' . $fields . ') values(' . $syear . '' . $values . ')';

                DBQuery($sql);
                DBQuery('INSERT INTO  staff_college_relationship(staff_id,college_id,syear,start_date) VALUES (' . UserID() . ',' . $id . ',' . $syear. ',"'.date('Y-m-d').'")');
                $other_admin_details=DBGet(DBQuery('SELECT * FROM login_authentication WHERE PROFILE_ID=0 AND USER_ID!=' . UserID() . ''));
                if(!empty($other_admin_details))
                {
                foreach($other_admin_details as $college_data)
                {
                DBQuery('INSERT INTO  staff_college_relationship(staff_id,college_id,syear,start_date) VALUES (' . $college_data['USER_ID'] . ',' . $id . ',' . $syear. ',"'.date('Y-m-d').'")');    
                }
                }
                if (User('PROFILE_ID') != 0) {
                    $super_id = DBGet(DBQuery('SELECT STAFF_ID FROM staff WHERE PROFILE_ID=0 AND PROFILE=\'admin\''));
                    $staff_exists=DBGet(DBQuery('SELECT * FROM staff_college_relationship WHERE STAFF_ID='.$super_id[1]['STAFF_ID'] . ' AND SCHOOL_ID='. $id . ' AND SYEAR='.$syear));
                    if(count($staff_exists)==0)
                        DBQuery('INSERT INTO  staff_college_relationship(staff_id,college_id,syear,start_date) VALUES (' . $super_id[1]['STAFF_ID'] . ',' . $id . ',' . $syear . ',"'.date('Y-m-d').'")');
                }
//                DBQuery('INSERT INTO college_years (MARKING_PERIOD_ID,SYEAR,SCHOOL_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT fn_marking_period_seq(),SYEAR,\'' . $id . '\' AS SCHOOL_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM college_years WHERE SYEAR=\'' . UserSyear() . '\' AND SCHOOL_ID=\'' . UserCollege() . '\' ORDER BY MARKING_PERIOD_ID');
                DBQuery('INSERT INTO college_years (MARKING_PERIOD_ID,SYEAR,SCHOOL_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,ROLLOVER_ID) SELECT fn_marking_period_seq(),\''.$syear.'\' as SYEAR,\'' . $id . '\' AS SCHOOL_ID,TITLE,SHORT_NAME,SORT_ORDER,\''.$start_date.'\' as START_DATE,\''.$end_date.'\' as  END_DATE,MARKING_PERIOD_ID FROM college_years WHERE SYEAR=\'' . UserSyear() . '\' AND SCHOOL_ID=\'' . UserCollege() . '\' ORDER BY MARKING_PERIOD_ID');
                DBQuery('INSERT INTO system_preference(college_id, full_day_minute, half_day_minute) VALUES (' . $id . ', NULL, NULL)');

                DBQuery('INSERT INTO program_config (SCHOOL_ID,SYEAR,PROGRAM,TITLE,VALUE) VALUES(\'' . $id . '\',\'' . $syear. '\',\'MissingAttendance\',\'LAST_UPDATE\',\'' . date('Y-m-d') . '\')');
                DBQuery('INSERT INTO program_config(SCHOOL_ID,SYEAR,PROGRAM,TITLE,VALUE) VALUES(\'' . $id . '\',\'' . $syear . '\',\'UPDATENOTIFY\',\'display_college\',"Y")');
                $_SESSION['UserCollege'] = $id;
                unset($_REQUEST['new_college']);
            }
echo '<FORM action=Modules.php?modname='.strip_tags(trim($_REQUEST['modname'])).' method=POST>';
	//echo '<script language=JavaScript>parent.side.location="'.$_SESSION['Side_PHP_SELF'].'?modcat="+parent.side.document.forms[0].modcat.value;</script>';
	
        echo '<div class="panel panel-default">';
        echo '<div class="panel-body text-center">';
        echo '<div class="new-college-created  p-30">';
        echo '<div class="icon-college">';
        echo '<span></span>';
        echo '</div>';
        echo '<h5 class="p-20">A new college called <b class="text-success">'.GetCollege(UserCollege()).'</b> has been created. To finish the operation, click the button below.</h5>';
        echo '<div class="text-right p-r-20"><INPUT type="submit" value="Finish Setup" class="btn btn-primary btn-lg"></div>';
        echo '</div>'; //.new-college-created
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
        
	//DrawHeaderHome('<IMG SRC=assets/check.gif> &nbsp; A new college called <strong>'.  GetCollege(UserCollege()).'</strong> has been created. To finish the operation, click OK button.','<INPUT  type=submit value=OK class="btn_medium">');
	echo '<input type="hidden" name="copy" value="done"/>';
	echo '</FORM>';
        }
    } else {
        $_REQUEST['modfunc'] = '';
    }


    unset($_SESSION['_REQUEST_vars']['values']);
    unset($_SESSION['_REQUEST_vars']['modfunc']);
}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update' && clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == 'Delete' && User('PROFILE') == 'admin') {
    if (DeletePrompt('college')) {
        if (BlockDelete('college')) {
            DBQuery('DELETE FROM colleges WHERE ID=\'' . UserCollege() . '\'');
            DBQuery('DELETE FROM college_gradelevels WHERE SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('DELETE FROM attendance_calendar WHERE SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('DELETE FROM college_periods WHERE SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('DELETE FROM college_years WHERE SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('DELETE FROM college_semesters WHERE SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('DELETE FROM college_quarters WHERE SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('DELETE FROM college_progress_periods WHERE SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('UPDATE staff SET CURRENT_SCHOOL_ID=NULL WHERE CURRENT_SCHOOL_ID=\'' . UserCollege() . '\'');
            DBQuery('UPDATE staff SET SCHOOLS=replace(SCHOOLS,\',' . UserCollege() . ',\',\',\')');

            unset($_SESSION['UserCollege']);
            //echo '<script language=JavaScript>parent.side.location="' . $_SESSION['Side_PHP_SELF'] . '?modcat="+parent.side.document.forms[0].modcat.value;</script>';
            unset($_REQUEST);
            $_REQUEST['modname'] = "collegesetup/Colleges.php?new_college=true";
            $_REQUEST['new_college'] = true;
            unset($_REQUEST['modfunc']);
            echo '
				<SCRIPT language="JavaScript">
				window.location="Side.php?college_id=new&modcat=' . strip_tags(trim($_REQUEST['modcat'])) . '";
				</SCRIPT>
				';
        }
    }
}
if (clean_param($_REQUEST['copy'], PARAM_ALPHAMOD) == 'done') {
    echo '<br><strong>College has been created successfully.</strong>';
} else {
    if (!$_REQUEST['modfunc']) {
        if (!$_REQUEST['new_college']) {
            $collegedata = DBGet(DBQuery('SELECT * FROM colleges WHERE ID=\'' . UserCollege() . '\''));
            $collegedata = $collegedata[1];
            $college_name = GetCollege(UserCollege());
        } 
        else
            $college_name = 'Add a College';
        if (!$_REQUEST['new_college'])
            $_REQUEST['new_college'] = false;
        //echo "<FORM name=college  id=college class=\"form-horizontal\"  enctype='multipart/form-data'  METHOD='POST' ACTION='Modules.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=update&btn=" . $_REQUEST['button'] . "&new_college=$_REQUEST[new_college]'>";
        echo "<FORM name=college  id=college class=\"form-horizontal\"  enctype='multipart/form-data'  METHOD='POST' ACTION='Modules.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=update'>";

        PopTable('header', 'College Information');

        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">College Name<span class=\"text-danger\">*</span></label><div class=\"col-md-8\">" . TextInput($collegedata['TITLE'], 'values[TITLE]', '', ' size=24 onKeyUp=checkDuplicateName(1,this,' . $collegedata['ID'] . '); onBlur=checkDuplicateName(1,this,' . $collegedata['ID'] . ');') . "</div></div>";
        echo "<input type=hidden id=checkDuplicateNameTable1 value='colleges'/>";
        echo "<input type=hidden id=checkDuplicateNameField1 value='title'/>";
        echo "<input type=hidden id=checkDuplicateNameMsg1 value='college name'/>";
        echo '</div>'; //.col-lg-6

        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Address</label><div class=\"col-md-8\">" . TextInput($collegedata['ADDRESS'], 'values[ADDRESS]', '', 'class=cell_floating maxlength=100 size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row


        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">City</label><div class=\"col-md-8\">" . TextInput($collegedata['CITY'], 'values[CITY]', '', 'maxlength=100, class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6

        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">State</label><div class=\"col-md-8\">" . TextInput($collegedata['STATE'], 'values[STATE]', '', 'maxlength=100, class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row


        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Zip/Postal Code</label><div class=\"col-md-8\">" . TextInput($collegedata['ZIPCODE'], 'values[ZIPCODE]', '', 'maxlength=10 class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6

        
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Area Code</label><div class=\"col-md-8\">" . TextInput($collegedata['AREA_CODE'], 'values[AREA_CODE]', '', 'class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row 
        
        
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Telephone</label><div class=\"col-md-8\">" . TextInput($collegedata['PHONE'], 'values[PHONE]', '', 'class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row 


        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Principal</label><div class=\"col-md-8\">" . TextInput($collegedata['PRINCIPAL'], 'values[PRINCIPAL]', '', 'class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6

        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Base Grading Scale<span class=\"text-danger\">*</span></label><div class=\"col-md-8\">" . TextInput($collegedata['REPORTING_GP_SCALE'], 'values[REPORTING_GP_SCALE]', '', 'class=cell_floating maxlength=10 size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row


        echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">E-Mail</label><div class=\"col-md-8\">" . TextInput($collegedata['E_MAIL'], 'values[E_MAIL]', '', 'class=cell_floating maxlength=100 size=24') . "</div></div>";
        echo '</div>'; //.col-md-6

        echo '<div class="col-md-6">';
        
        if (AllowEdit() || !$collegedata['WWW_ADDRESS']) {

            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Website</label><div class=\"col-md-8\">" . TextInput($collegedata['WWW_ADDRESS'], 'values[WWW_ADDRESS]', '', 'class=cell_floating size=24') . "</div></div>";
        } else {
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Website</label><div class=\"col-md-8\"><A HREF=http://$collegedata[WWW_ADDRESS] target=_blank>$collegedata[WWW_ADDRESS]</A></div></div>";
        }
        echo '</div>';
        echo '</div>';
        
        echo '<div class="row">';
        if ($college_name != 'Add a College')
            include('modules/collegesetup/includes/CollegecustomfieldsInc.php');
        echo '</div>';

        echo '<div class="row">';
        echo '<div class="col-md-6">';

//        $uploaded_sql = DBGet(DBQuery("SELECT VALUE FROM program_config WHERE SCHOOL_ID='" . UserCollege() . "' AND SYEAR IS NULL AND TITLE='PATH'"));
//        $_SESSION['logo_path'] = $uploaded_sql[1]['VALUE'];
//        if (!$_REQUEST['new_college'] && file_exists($uploaded_sql[1]['VALUE']))
        
        $sch_img_info= DBGet(DBQuery('SELECT * FROM user_file_upload WHERE SCHOOL_ID='. UserCollege().' AND FILE_INFO=\'schlogo\''));
    
        
        if(!$_REQUEST['new_college'] && count($sch_img_info)>0)
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">College Logo</label><div class=\"col-md-8\">" . (AllowEdit() != false ? "<a href ='Modules.php?modname=collegesetup/UploadLogo.php&modfunc=edit'>" : '') . "<div class=\"image-holder\"><img src='data:image/jpeg;base64,".base64_encode($sch_img_info[1]['CONTENT'])."' class=img-responsive /></div>" . (AllowEdit() != false ? "</a>" : '') . (AllowEdit() != false ? "<a href='Modules.php?modname=collegesetup/UploadLogo.php&modfunc=edit' class=\"show text-center m-t-10 text-primary\"><i class=\"icon-upload position-left\"></i> Click here to change logo</a>" : '') . "</div></div>";
        else if (!$_REQUEST['new_college'])
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">College Logo</label><div class=\"col-md-8\">" . (AllowEdit() != false ? "<a href ='Modules.php?modname=collegesetup/UploadLogo.php' class=\"form-control text-primary\" readonly=\"readonly\"><i class=\"icon-upload position-left\"></i> Click here to upload logo</a>" : '-') . "</div></div>";

        echo '</div>'; //.col-md-4
        echo '</div>'; //.row  

        if($_REQUEST['new_college']=='true')
        {
        $get_this_college_date=DBGet(DBQuery('SELECT * FROM college_years where SYEAR='.UserSyear().' AND SCHOOL_ID='.UserCollege()));  
        
        echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Start Date</label><div class=\"col-md-8\">" . DateInputAY($get_this_college_date[1]['START_DATE'], '_min', 1). "</div></div>";
        echo '</div>'; //.col-md-6
        
        echo '<div class="col-md-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">End Date</label><div class=\"col-md-8\">" . DateInputAY($get_this_college_date[1]['END_DATE'], '_max', 2). "</div></div>";
        echo '</div>'; //.col-md-6
        echo '</div>'; //.row  
        }
        $btns = '';
        if (User('PROFILE') == 'admin' && AllowEdit()) {
            //echo '<hr class="no-margin"/>';
            if ($_REQUEST['new_college']) {
                $btns = "<div class=\"text-right p-r-20\"><INPUT TYPE=submit name=button id=button class=\"btn btn-primary\" VALUE='Save' onclick='return formcheck_college_setup_college();'></div>";
            } else {

                $btns = "<div class=\"text-right p-r-20\"><INPUT TYPE=submit name=button id=button class=\"btn btn-primary\" VALUE='Update' onclick='return formcheck_college_setup_college();'></div>";
            }
        }


        PopTable('footer',$btns);

        echo "</FORM>";
    }
}

?>