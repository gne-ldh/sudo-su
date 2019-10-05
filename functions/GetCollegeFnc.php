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
function GetCollege($sch)
{	global $_openSIS;
		if(!$_openSIS['GetCollege'])
	{
		$QI=DBQuery('SELECT ID,TITLE FROM colleges');
		$_openSIS['GetCollege'] = DBGet($QI,array(),array('ID'));
	}

	if($_openSIS['GetCollege'][$sch])
		return $_openSIS['GetCollege'][$sch][1]['TITLE'];
	else
		return $sch;
}
function GetUserColleges($staff_id,$str=false)
{
      if(User('PROFILE_ID')!=4 && User('PROFILE')!='parent')
      {
        $str_return='';
        $colleges=DBGet(DBQuery('SELECT COLLEGE_ID FROM staff_college_relationship WHERE staff_id='.$staff_id.' AND syear='.  UserSyear()));
        foreach($colleges as $college)
        {
            $return[]=$college['COLLEGE_ID'];
            $str_return .=$college['COLLEGE_ID'].',';
        }
        if($str==true)
        {
            return substr($str_return,0,-1);
        }
        else
        {
            return $return;
        }
      }
      else if (User('PROFILE_ID')==4 || User('PROFILE')=='parent')
      {
          $colleges=DBGet(DBQuery('SELECT COLLEGE_ID FROM student_enrollment WHERE COLLEGE_ROLL_NO='.UserStudentID().' AND SYEAR='.UserSyear().' ORDER BY ID DESC LIMIT 0,1'));
          return $colleges[1]['COLLEGE_ID'];
      }
}

function GetCollegeInfo($sch)
{	global $_openSIS;
		if(!$_openSIS['GetCollegeInfo'])
	{
		$QI=DBQuery('SELECT * FROM colleges');
		$_openSIS['GetCollegeInfo'] = DBGet($QI,array(),array('ID'));
	}
	if($_openSIS['GetCollegeInfo'][$sch])
		return 'Address :'.$_openSIS['GetCollegeInfo'][$sch][1]['ADDRESS'].','.$_openSIS['GetCollegeInfo'][$sch][1]['CITY'].','.$_openSIS['GetCollegeInfo'][$sch][1]['STATE'].','.$_openSIS['GetCollegeInfo'][$sch][1]['ZIPCODE']. ($_openSIS['GetCollegeInfo'][$sch][1]['PHONE']!=NULL ? ' <p> Phone :'.$_openSIS['GetCollegeInfo'][$sch][1]['PHONE'].'</p>' : '');
                 
	else
		return $sch;
}


?>
