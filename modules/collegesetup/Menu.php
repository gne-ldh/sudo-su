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
$menu['collegesetup']['admin'] = array(
						'collegesetup/PortalNotes.php'=>'Notice',
						'collegesetup/MarkingPeriods.php'=>'Sessions',
						'collegesetup/Calendar.php'=>'Calendars',
						'collegesetup/Periods.php'=>'Periods',
						'collegesetup/GradeLevels.php'=>'Years',
                                                'collegesetup/Sections.php'=>'Sections',
                                                //'collegesetup/Rooms.php'=>'Rooms',
                         1=>'College',
                        'collegesetup/Colleges.php'=>'College Information',
						'collegesetup/Colleges.php?new_college=true'=>'Add a College',
						'collegesetup/CopyCollege.php'=>'Copy College',
						'collegesetup/SystemPreference.php'=>'System Preference',
                                                'collegesetup/CollegeCustomFields.php'=>'College Custom Fields',
                         2=>'Courses',
                        'collegesetup/Courses.php'=>'Course Manager',
                        'collegesetup/CourseCatalog.php'=>'Course Catalog',
                        'collegesetup/PrintCatalog.php'=>'Print Catalog by Term', 
                        'collegesetup/PrintCatalogGradeLevel.php'=>'Print Catalog by Grade Level', 
                        'collegesetup/PrintAllCourses.php'=>'Print all Courses',
                        'collegesetup/TeacherReassignment.php'=>'Teacher Re-Assignment'
              );

$menu['collegesetup']['teacher'] = array(
						'collegesetup/Colleges.php'=>'College Information',
						'collegesetup/MarkingPeriods.php'=>'Marking Periods',
						'collegesetup/Calendar.php'=>'Calendar',
						1=>'Courses',
                        'collegesetup/Courses.php'=>'Course Manager',
                        'collegesetup/CourseCatalog.php'=>'Course Catalog',
                        'collegesetup/PrintCatalog.php'=>'Print Catalog by Term', 
                        'collegesetup/PrintAllCourses.php'=>'Print all Courses'
					);

$menu['collegesetup']['parent'] = array(
						'collegesetup/Colleges.php'=>'College Information',
						'collegesetup/Calendar.php'=>'Calendar'
					);

$exceptions['collegesetup'] = array(
						'collegesetup/PortalNotes.php'=>true,
						'collegesetup/Colleges.php?new_college=true'=>true,
						'collegesetup/Rollover.php'=>true
					);
?>
