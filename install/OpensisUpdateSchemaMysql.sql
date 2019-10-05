--
--
--

--SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `opensis`
--

CREATE TABLE `api_info` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `api_key` varchar(255) CHARACTER SET utf8 NOT NULL,
 `api_secret` varchar(255) CHARACTER SET utf8 NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE address (
    address_id int(8) not null auto_increment primary key,
    house_no numeric(5,0),
    fraction character varying(3),
    letter character varying(2),
    direction character varying(2),
    street character varying(30),
    apt character varying(5),
    zipcode character varying(50),
    plus4 character varying(4),
    city character varying(60),
    state character varying(50),
    mail_street character varying(30),
    mail_city character varying(60),
    mail_state character varying(50),
    mail_zipcode character varying(50),
    address character varying(255),
    mail_address character varying(255),
    phone character varying(30),
    college_roll_no numeric(10,0),
    bus_no character varying(20),
    bus_pickup character varying(2),
    bus_dropoff character varying(2),
    prim_student_relation character varying(100),
    pri_first_name character varying(100),
    pri_last_name character varying(100),
    home_phone character varying(100),
    work_phone character varying(100),
    mobile_phone character varying(100),
    email character varying(100),
    prim_custody character varying(2),
    prim_address character varying(100),
    prim_street character varying(100),
    prim_city character varying(100),
    prim_state character varying(100),
    prim_zipcode character varying(20),
    sec_student_relation character varying(100),
    sec_first_name character varying(100),
    sec_last_name character varying(100),
    sec_home_phone character varying(100),
    sec_work_phone character varying(100),
    sec_mobile_phone character varying(100),
    sec_email character varying(100),
    sec_custody character varying(2),
    sec_address character varying(100),
    sec_street character varying(100),
    sec_city character varying(60),
    sec_state character varying(100),
    sec_zipcode character varying(100)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE address AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `ethnicity` (
  `ethnicity_id` int(8) NOT NULL AUTO_INCREMENT,
  `ethnicity_name` varchar(255) NOT NULL,
  `sort_order` int(8) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date time ethnicity record modified',
  PRIMARY KEY (`ethnicity_id`)
) ;

CREATE TABLE address_field_categories (
    id int(8) not null auto_increment primary key,
    title character varying(100),
    sort_order numeric,
    residence character(1),
    mailing character(1),
    bus character(1)
)ENGINE=InnoDB;


ALTER TABLE address_field_categories AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `language` (
  `language_id` int(8) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(127) NOT NULL,
  `sort_order` int(8) DEFAULT NULL,
  PRIMARY KEY (`language_id`)
);
CREATE TABLE `login_authentication` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `profile_id` int(11) NOT NULL,
 `username` varchar(255) NOT NULL,
 `password` varchar(255) NOT NULL,
 `last_login` datetime DEFAULT NULL,
 `failed_login` int(3) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 UNIQUE KEY `COMPOSITE` (`user_id`,`profile_id`)
);
CREATE TABLE address_fields (
    id int(8) not null auto_increment primary key,
    type character varying(10),
    search character varying(1),
    title character varying(30),
    sort_order numeric,
    select_options character varying(10000),
    category_id numeric,
    system_field character(1),
    required character varying(1),
    default_selection character varying(255)
)ENGINE=InnoDB;

ALTER TABLE address_fields AUTO_INCREMENT=1;


CREATE TABLE app (
    name character varying(100) NOT NULL,
    value character varying(100) NOT NULL
)ENGINE=InnoDB;;


CREATE TABLE attendance_calendar (
    syear numeric(4,0) NOT NULL,
    college_id numeric NOT NULL,
    college_date date NOT NULL,
    minutes numeric,
    block character varying(10),
    calendar_id numeric NOT NULL
)ENGINE=InnoDB;


CREATE TABLE attendance_calendars (
    college_id numeric,
    title character varying(100),
    syear numeric(4,0),
    calendar_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    default_calendar character varying(1),
    rollover_id numeric
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `calendar_events_visibility` (
  `calendar_id` int(11) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `profile` character varying(50) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE attendance_code_categories (
    id  INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    syear numeric(4,0),
    college_id numeric,
    title character varying(255)
)ENGINE=InnoDB;


ALTER TABLE attendance_code_categories AUTO_INCREMENT=1;


CREATE TABLE attendance_codes (
    id int(8) not null auto_increment primary key,
    syear numeric(4,0),
    college_id numeric,
    title character varying(100),
    short_name character varying(10),
    type character varying(10),
    state_code character varying(1),
    default_code character varying(1),
    table_name numeric,
    sort_order numeric
)ENGINE=InnoDB;


ALTER TABLE attendance_codes AUTO_INCREMENT=1;


CREATE TABLE attendance_completed (
    staff_id numeric NOT NULL,
    college_date date NOT NULL,
    period_id numeric NOT NULL,
    course_period_id INT(11) NOT NULL,
    substitute_staff_id numeric NULL DEFAULT NULL,
    is_taken_by_substitute_staff char(1) NULL DEFAULT NULL
)ENGINE=InnoDB;


CREATE TABLE attendance_day (
    college_roll_no numeric NOT NULL,
    college_date date NOT NULL,
    minutes_present numeric,
    state_value numeric(2,1),
    syear numeric(4,0),
    marking_period_id integer,
    comment character varying(255)
)ENGINE=InnoDB;


CREATE TABLE attendance_period (
    college_roll_no numeric NOT NULL,
    college_date date NOT NULL,
    period_id numeric NOT NULL,
    attendance_code numeric,
    attendance_teacher_code numeric,
    attendance_reason character varying(100),
    admin character varying(1),
    course_period_id numeric,
    marking_period_id integer,
    comment character varying(100)
)ENGINE=InnoDB;


CREATE TABLE calendar_events (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    syear numeric(4,0),
    college_id numeric,
    calendar_id numeric,
    college_date date,
    title character varying(50),
    description text
)ENGINE=InnoDB;


ALTER TABLE calendar_events AUTO_INCREMENT=1;


CREATE TABLE config (
    title character varying(100),
    syear numeric(4,0),
    login character varying(3)
)ENGINE=InnoDB;


CREATE TABLE course_periods (
    syear numeric(4,0) NOT NULL,
    college_id numeric NOT NULL,
    course_period_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    course_id numeric NOT NULL,
    course_weight character varying(10),
    title character varying(100),
    short_name text,
    period_id numeric,
    mp character varying(3),
    marking_period_id integer,
    teacher_id numeric,
    secondary_teacher_id numeric,
    room character varying(10),
    total_seats numeric,
    filled_seats numeric default 0,
    does_attendance character varying(1),
    does_honor_roll character varying(1),
    does_class_rank character varying(1),
    gender_restriction character varying(1),
    house_restriction character varying(1),
    availability numeric,
    parent_id numeric,
    days character varying(7),
    calendar_id numeric,
    half_day character varying(1),
    does_breakoff character varying(1),
    rollover_id numeric,
    grade_scale_id numeric,
    credits decimal(10,3) null default null
)ENGINE=InnoDB;


CREATE TABLE courses (
    syear numeric(4,0) NOT NULL,
    course_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    subject_id numeric NOT NULL,
    college_id numeric NOT NULL,
    grade_level numeric,
    title character varying(100),
    short_name character varying(25),
    rollover_id numeric
)ENGINE=InnoDB;


ALTER TABLE courses AUTO_INCREMENT=1;


CREATE TABLE course_subjects (
    syear numeric(4,0),
    college_id numeric,
    subject_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title text,
    short_name text,
    rollover_id numeric
)ENGINE=InnoDB;



ALTER TABLE course_subjects AUTO_INCREMENT=1;

CREATE TABLE custom_fields (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type character varying(10),
    search character varying(1),
    title character varying(30),
    sort_order numeric,
    select_options character varying(10000),
    category_id numeric,
    system_field character(1),
    required character varying(1),
    default_selection character varying(255),
	hide varchar(1)
)ENGINE=InnoDB;
ALTER TABLE custom_fields AUTO_INCREMENT=1;


CREATE TABLE eligibility (
    college_roll_no numeric,
    syear numeric(4,0),
    college_date date,
    period_id numeric,
    eligibility_code character varying(20),
    course_period_id numeric
)ENGINE=InnoDB;


CREATE TABLE eligibility_activities (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    syear numeric(4,0),
    college_id numeric,
    title character varying(100),
    start_date date,
    end_date date
)ENGINE=InnoDB;



ALTER TABLE eligibility_activities AUTO_INCREMENT=1;


CREATE TABLE eligibility_completed (
    staff_id numeric NOT NULL,
    college_date date NOT NULL,
    period_id numeric NOT NULL
)ENGINE=InnoDB;


CREATE TABLE college_gradelevels (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_id numeric,
    short_name character varying(5),
    title character varying(50),
    next_grade_id numeric,
    sort_order numeric
)ENGINE=InnoDB;


CREATE TABLE student_enrollment (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    syear numeric(4,0),
    college_id numeric,
    college_roll_no numeric,
    grade_id numeric,
    start_date date,
    end_date date,
    enrollment_code numeric,
    drop_code numeric,
    next_college numeric,
    calendar_id numeric,
    last_college numeric
)ENGINE=InnoDB;

ALTER TABLE student_enrollment AUTO_INCREMENT=1;


CREATE TABLE gradebook_assignment_types (
    assignment_type_id  INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    staff_id numeric,
    course_id numeric,
    title character varying(100),
    final_grade_percent numeric(6,5),
    course_period_id numeric
)ENGINE=InnoDB;


ALTER TABLE gradebook_assignment_types AUTO_INCREMENT=1;


CREATE TABLE gradebook_assignments (
    assignment_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    staff_id numeric,
    marking_period_id integer,
    course_period_id numeric,
    course_id numeric,
    assignment_type_id numeric,
    title character varying(100),
    assigned_date date,
    due_date date,
    points numeric,
    description longtext,
	ungraded int(8) NOT NULL DEFAULT 1
)ENGINE=InnoDB;


ALTER TABLE gradebook_assignments AUTO_INCREMENT=1;


CREATE TABLE gradebook_grades (
    college_roll_no numeric NOT NULL,
    period_id numeric,
    course_period_id numeric NOT NULL,
    assignment_id numeric NOT NULL,
    points numeric(6,2),
    comment longtext
)ENGINE=InnoDB;


CREATE TABLE grades_completed (
    staff_id numeric NOT NULL,
    marking_period_id integer NOT NULL,
    period_id numeric NOT NULL
)ENGINE=InnoDB;


CREATE TABLE history_marking_periods (
    parent_id integer,
    mp_type character(20),
    name character(30),
    post_end_date date,
    college_id integer,
    syear integer,
    marking_period_id integer
)ENGINE=InnoDB;


CREATE TABLE `honor_roll` (
`id` INT NOT NULL AUTO_INCREMENT ,
`college_id` INT NOT NULL ,
`syear` INT(4) NOT NULL ,
`title` VARCHAR( 100 ) NOT NULL ,
`value` VARCHAR( 100 ) NULL ,
PRIMARY KEY (  `id` )
) ENGINE = InnoDB ;

CREATE TABLE lunch_period (
    college_roll_no numeric,
    college_date date,
    period_id numeric,
    attendance_code numeric,
    attendance_teacher_code numeric,
    attendance_reason character varying(100),
    admin character varying(1),
    course_period_id numeric,
    marking_period_id integer,
    lunch_period character varying(100),
    table_name numeric,
    comment varchar(100) default NULL
)ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `missing_attendance` (
  `college_id` int(11) NOT NULL,
  `syear` varchar(6) NOT NULL,
  `college_date` date NOT NULL,
  `course_period_id` int(11) NOT NULL,
  `period_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `secondary_teacher_id` int(11) default NULL
) ENGINE=InnoDB;

CREATE TABLE college_quarters (
    marking_period_id integer NOT NULL,
    syear numeric(4,0),
    college_id numeric,
    semester_id numeric,
    title character varying(50),
    short_name character varying(10),
    sort_order numeric,
    start_date date,
    end_date date,
    post_start_date date,
    post_end_date date,
    does_grades character varying(1),
    does_exam character varying(1),
    does_comments character varying(1),
    rollover_id numeric
)ENGINE=InnoDB;


CREATE TABLE college_semesters (
    marking_period_id integer NOT NULL,
    syear numeric(4,0),
    college_id numeric,
    year_id numeric,
    title character varying(50),
    short_name character varying(10),
    sort_order numeric,
    start_date date,
    end_date date,
    post_start_date date,
    post_end_date date,
    does_grades character varying(1),
    does_exam character varying(1),
    does_comments character varying(1),
    rollover_id numeric
)ENGINE=InnoDB;


CREATE TABLE college_years (
    marking_period_id integer NOT NULL,
    syear numeric(4,0),
    college_id numeric,
    title character varying(50),
    short_name character varying(10),
    sort_order numeric,
    start_date date,
    end_date date,
    post_start_date date,
    post_end_date date,
    does_grades character varying(1),
    does_exam character varying(1),
    does_comments character varying(1),
    rollover_id numeric
)ENGINE=InnoDB;


CREATE VIEW marking_periods AS
    SELECT q.marking_period_id, 'openSIS' AS mp_source, q.syear,
	q.college_id, 'quarter' AS mp_type, q.title, q.short_name,
	q.sort_order, q.semester_id AS parent_id,
	s.year_id AS grandparent_id, q.start_date,
	q.end_date, q.post_start_date,
	q.post_end_date, q.does_grades,
	q.does_exam, q.does_comments
    FROM college_quarters q
    JOIN college_semesters s ON q.semester_id = s.marking_period_id
UNION
    SELECT marking_period_id, 'openSIS' AS mp_source, syear,
	college_id, 'semester' AS mp_type, title, short_name,
	sort_order, year_id AS parent_id,
	-1 AS grandparent_id, start_date,
	end_date, post_start_date,
	post_end_date, does_grades,
	does_exam, does_comments
    FROM college_semesters
UNION
    SELECT marking_period_id, 'openSIS' AS mp_source, syear,
	college_id, 'year' AS mp_type, title, short_name,
	sort_order, -1 AS parent_id,
	-1 AS grandparent_id, start_date,
	end_date, post_start_date,
	post_end_date, does_grades,
	does_exam, does_comments
    FROM college_years
UNION
    SELECT marking_period_id, 'History' AS mp_source, syear,
	college_id, mp_type, name AS title, NULL AS short_name,
	NULL AS sort_order, parent_id,
	-1 AS grandparent_id, NULL AS start_date,
	post_end_date AS end_date, NULL AS post_start_date,
	post_end_date, 'Y' AS does_grades,
	NULL AS does_exam, NULL AS does_comments
    FROM history_marking_periods;




CREATE TABLE marking_period_id_generator (
    id INTEGER NOT NULL AUTO_INCREMENT KEY
);
DROP FUNCTION IF EXISTS `fn_marking_period_seq`;
DELIMITER $$
CREATE FUNCTION fn_marking_period_seq () RETURNS INT
BEGIN
  INSERT INTO marking_period_id_generator VALUES(NULL);
 -- DELETE FROM marking_period_id_generator;
RETURN LAST_INSERT_ID();
END$$
DELIMITER ;
ALTER TABLE marking_period_id_generator AUTO_INCREMENT=12;

-- ALTER TABLE `marking_periods` ADD PRIMARY KEY(`marking_period_id`);
 --ALTER TABLE `marking_periods` CHANGE `marking_period_id` `marking_period_id` INT(8) NOT NULL AUTO_INCREMENT ;


CREATE TABLE old_course_weights (
    syear numeric(4,0),
    college_id numeric,
    course_id numeric,
    course_weight character varying(10),
    gpa_multiplier numeric,
    year_fraction numeric,
    rollover_id numeric
)ENGINE=InnoDB;


CREATE TABLE people (
    person_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    last_name character varying(25) NOT NULL,
    first_name character varying(25) NOT NULL,
    middle_name character varying(25)
)ENGINE=InnoDB;


CREATE TABLE people_field_categories (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title character varying(100),
    sort_order numeric,
    custody character(1),
    emergency character(1)
)ENGINE=InnoDB;


ALTER TABLE people_field_categories AUTO_INCREMENT=1;


CREATE TABLE people_fields (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type character varying(10),
    search character varying(1),
    title character varying(30),
    sort_order numeric,
    select_options character varying(10000),
    category_id numeric,
    system_field character(1),
    required character varying(1),
    default_selection character varying(255)
)ENGINE=InnoDB;




ALTER TABLE people_fields AUTO_INCREMENT=1;


CREATE TABLE people_join_contacts (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    person_id numeric,
    title character varying(100),
    value character varying(100)
)ENGINE=InnoDB;


ALTER TABLE people_join_contacts AUTO_INCREMENT=1;




ALTER TABLE people AUTO_INCREMENT=1;


CREATE TABLE portal_notes (
    id int(8) not null auto_increment primary key,
    college_id numeric,
    syear numeric(4,0),
    title character varying(255),
    content longtext,
    sort_order numeric,
    published_user numeric,
    published_date timestamp ,
    start_date date,
    end_date date,
    published_profiles character varying(255)
)ENGINE=InnoDB;

ALTER TABLE portal_notes AUTO_INCREMENT=1;


CREATE TABLE profile_exceptions (
    profile_id numeric,
    modname character varying(255),
    can_use character varying(1),
    can_edit character varying(1)
)ENGINE=InnoDB;


CREATE TABLE program_config (
    syear numeric(4,0),
    college_id numeric,
    program character varying(255),
    title character varying(100),
    value character varying(100)
)ENGINE=InnoDB;


CREATE TABLE program_user_config (
    user_id numeric NOT NULL,
    college_id numeric NULL,
    program character varying(255),
    title character varying(100),
    value character varying(100)
)ENGINE=InnoDB;


CREATE TABLE report_card_comments (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    syear numeric(4,0),
    college_id numeric,
    course_id numeric,
    sort_order numeric,
    title text
)ENGINE=InnoDB;


ALTER TABLE report_card_comments AUTO_INCREMENT=1;


CREATE TABLE `report_card_grade_scales` (
 `id` int(8) NOT NULL AUTO_INCREMENT,
 `syear` decimal(4,0) DEFAULT NULL,
 `college_id` decimal(10,0) NOT NULL,
 `title` varchar(25) DEFAULT NULL,
 `comment` varchar(100) DEFAULT NULL,
 `sort_order` decimal(10,0) DEFAULT NULL,
 `rollover_id` decimal(10,0) DEFAULT NULL,
 `gp_scale` decimal(10,3) DEFAULT NULL,
 `gpa_cal` enum('Y','N') NOT NULL DEFAULT 'Y',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




ALTER TABLE report_card_grade_scales AUTO_INCREMENT=1;


CREATE TABLE report_card_grades (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    syear numeric(4,0),
    college_id numeric,
    title character varying(15),
    sort_order numeric,
    gpa_value numeric(4,2),
    break_off numeric,
    comment longtext,
    grade_scale_id numeric,
    unweighted_gp numeric(4,2)
   )ENGINE=InnoDB;


ALTER TABLE report_card_grades AUTO_INCREMENT=1;


CREATE TABLE schedule (
    syear numeric(4,0) NOT NULL,
    college_id numeric,
    college_roll_no numeric NOT NULL,
    start_date date NOT NULL,
    end_date date,
    modified_date date,
    modified_by character varying(255),
    course_id numeric NOT NULL,
    course_weight character varying(10),
    course_period_id numeric NOT NULL,
    mp character varying(3),
    marking_period_id integer,
    scheduler_lock character varying(1),
	dropped character varying(1) DEFAULT 'N',
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY
)ENGINE=InnoDB;


CREATE TABLE schedule_requests (
    syear numeric(4,0),
    college_id numeric,
    request_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric,
    subject_id numeric,
    course_id numeric,
    course_weight character varying(10),
    marking_period_id integer,
    priority numeric,
    with_teacher_id numeric,
    not_teacher_id numeric,
    with_period_id numeric,
    not_period_id numeric
)ENGINE=InnoDB;


ALTER TABLE schedule_requests AUTO_INCREMENT=1;


ALTER TABLE schedule AUTO_INCREMENT=1;


ALTER TABLE college_gradelevels AUTO_INCREMENT=1;


CREATE TABLE college_periods (
    period_id int(8) not null auto_increment primary key,
    syear numeric(4,0),
    college_id numeric,
    sort_order numeric,
    title character varying(100),
    short_name character varying(10),
    length numeric,
    block character varying(10),
    ignore_scheduling character varying(10), 
    attendance character varying(1),
    rollover_id numeric,
    start_time character varying(15),
    end_time character varying(15)
)ENGINE=InnoDB;


ALTER TABLE college_periods AUTO_INCREMENT=1;


CREATE TABLE college_progress_periods (
    marking_period_id integer NOT NULL,
    syear numeric(4,0),
    college_id numeric,
    quarter_id numeric,
    title character varying(50),
    short_name character varying(10),
    sort_order numeric,
    start_date date,
    end_date date,
    post_start_date date,
    post_end_date date,
    does_grades character varying(1),
    does_exam character varying(1),
    does_comments character varying(1),
    rollover_id numeric
)ENGINE=InnoDB;


CREATE TABLE colleges (
    id int(8) not null auto_increment primary key,
	syear numeric(4,0),
    title character varying(100),
    address character varying(100),
    city character varying(100),
    state character varying(100),
    zipcode character varying(10),
    area_code numeric(3,0),
    phone character varying(30),
    principal character varying(100),
    www_address character varying(100),
    e_mail character varying(100),
    ceeb character varying(100),
    reporting_gp_scale numeric(10,3)
)ENGINE=InnoDB;


CREATE TABLE system_preference (
id INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
college_id INT( 8 ) NOT NULL,
full_day_minute INT( 8 ),
half_day_minute INT( 8 )
)ENGINE=InnoDB;


CREATE TABLE staff (
    staff_id int(8) not null auto_increment primary key,
    current_college_id numeric,
    title character varying(5),
    first_name character varying(100),
    last_name character varying(100),
    middle_name character varying(100),
    username character varying(100),
    password character varying(100),
    phone character varying(100),
    email character varying(100),
    profile character varying(30),
    homeroom character varying(5),
    last_login datetime DEFAULT NULL,
    failed_login int(3) not null default 0,
    profile_id numeric,
    is_disable varchar(10) default NULL
)ENGINE=InnoDB;



CREATE TABLE staff_exceptions (
    user_id numeric NOT NULL,
    modname character varying(255),
    can_use character varying(1),
    can_edit character varying(1)
)ENGINE=InnoDB;


CREATE TABLE staff_field_categories (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title character varying(100),
    sort_order numeric,
    include character varying(100),
    admin character(1),
    teacher character(1),
    parent character(1),
    none character(1)
)ENGINE=InnoDB;


ALTER TABLE staff_field_categories AUTO_INCREMENT=1;


CREATE TABLE staff_fields (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type character varying(10),
    search character varying(1),
    title character varying(30),
    sort_order numeric,
    select_options character varying(10000),
    category_id numeric,
    system_field character(1),
    required character varying(1),
    default_selection character varying(255)
)ENGINE=InnoDB;



ALTER TABLE staff_fields AUTO_INCREMENT=1;


CREATE TABLE student_eligibility_activities (
    syear numeric(4,0),
    college_roll_no numeric,
    activity_id numeric
)ENGINE=InnoDB;


CREATE TABLE student_enrollment_codes (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    syear numeric(4,0),
    title character varying(100),
    short_name character varying(10),
    type character varying(4)
)ENGINE=InnoDB;


ALTER TABLE student_enrollment_codes AUTO_INCREMENT=1;


CREATE TABLE student_field_categories (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title character varying(100),
    sort_order numeric,
    include character varying(100)
)ENGINE=InnoDB;


ALTER TABLE student_field_categories AUTO_INCREMENT=1;


CREATE TABLE student_gpa_calculated (
    college_roll_no numeric,
    marking_period_id integer,
    mp character varying(4),
    gpa decimal(10,2),
    weighted_gpa decimal(10,2),
    unweighted_gpa decimal(10,2),
    class_rank numeric,
    grade_level_short character varying(100) default NULL
)ENGINE=InnoDB;


CREATE TABLE student_gpa_running (
    college_roll_no numeric,
    marking_period_id integer,
    gpa_points decimal(10,2),
    gpa_points_weighted decimal(10,2),
    divisor decimal(10,2),
	credit_earned decimal(10,2),
    cgpa decimal(10,2) DEFAULT NULL
)ENGINE=InnoDB;


CREATE TABLE student_medical (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric,
    type character varying(25),
    medical_date date,
    comments longtext
)ENGINE=InnoDB;


CREATE TABLE student_medical_alerts (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric,
    title text,
    alert_date date
)ENGINE=InnoDB;

ALTER TABLE student_medical_alerts AUTO_INCREMENT=1;


ALTER TABLE student_medical AUTO_INCREMENT=1;


CREATE TABLE student_medical_visits (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric,
    college_date date,
    time_in character varying(20),
    time_out character varying(20),
    reason text,
    result text,
    comments longtext
)ENGINE=InnoDB;

ALTER TABLE student_medical_visits AUTO_INCREMENT=1;

CREATE TABLE student_medical_notes (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric NOT NULL,
    doctors_note_date date,
    doctors_note_comments longtext
)ENGINE=InnoDB;

CREATE TABLE student_mp_comments (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric NOT NULL,
    syear numeric(4,0) NOT NULL,
    marking_period_id integer NOT NULL,
    staff_id integer,
    comment longtext,
    comment_date date
)ENGINE=InnoDB;


CREATE TABLE student_mp_stats (
    college_roll_no integer NOT NULL,
    marking_period_id integer NOT NULL,
    cum_weighted_factor decimal(10,6),
    cum_unweighted_factor decimal(10,6),
    cum_rank integer,
    mp_rank integer,
    sum_weighted_factors decimal(10,6),
    sum_unweighted_factors decimal(10,6),
    count_weighted_factors integer,
    count_unweighted_factors integer,
    grade_level_short character varying(3),
    class_size integer
)ENGINE=InnoDB;


CREATE TABLE student_report_card_comments (
    syear numeric(4,0) NOT NULL,
    college_id numeric,
    college_roll_no numeric NOT NULL,
    course_period_id numeric NOT NULL,
    report_card_comment_id numeric NOT NULL,
    comment character varying(1),
    marking_period_id integer NOT NULL
)ENGINE=InnoDB;

CREATE TABLE `student_report_card_grades` (
 `syear` decimal(4,0) DEFAULT NULL,
 `college_id` decimal(10,0) DEFAULT NULL,
 `college_roll_no` decimal(10,0) NOT NULL,
 `course_period_id` decimal(10,0) DEFAULT NULL,
 `report_card_grade_id` decimal(10,0) DEFAULT NULL,
 `report_card_comment_id` decimal(10,0) DEFAULT NULL,
 `comment` longtext,
 `grade_percent` decimal(5,2) DEFAULT NULL,
 `marking_period_id` varchar(10) NOT NULL,
 `grade_letter` varchar(5) DEFAULT NULL,
 `weighted_gp` decimal(10,3) DEFAULT NULL,
 `unweighted_gp` decimal(10,3) DEFAULT NULL,
 `gp_scale` decimal(10,3) DEFAULT NULL,
 `gpa_cal` varchar(2) DEFAULT NULL,
 `credit_attempted` decimal(10,3) DEFAULT NULL,
 `credit_earned` decimal(10,3) DEFAULT NULL,
 `credit_category` varchar(10) DEFAULT NULL,
 `course_code` varchar(100) DEFAULT NULL,
 `course_title` text,
 `id` int(8) NOT NULL AUTO_INCREMENT,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE student_report_card_grades AUTO_INCREMENT=1;

CREATE TABLE students (
    college_roll_no INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    last_name character varying(50) NOT NULL,
    first_name character varying(50) NOT NULL,
    middle_name character varying(50),
    name_suffix character varying(3),
    username character varying(100),
    password character varying(100),
    last_login date,
    failed_login int(3) not null default 0,
    gender character varying(255),
    ethnicity character varying(255),
    common_name character varying(255),
    social_security character varying(255),
    birthdate character varying(255),
    language character varying(255),
    physician character varying(255),
    physician_phone character varying(255),
    preferred_hospital character varying(255),
    estimated_grad_date character varying(255),
    alt_id character varying(50),
    email character varying(50),
    phone character varying(30),
    is_disable varchar(10) default NULL
)ENGINE=InnoDB;

ALTER TABLE students AUTO_INCREMENT=1;


CREATE TABLE students_join_address (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric NOT NULL,
    address_id numeric(10,0) NOT NULL,
    contact_seq numeric(10,0),
    gets_mail character varying(1),
    primary_residence character varying(1),
    legal_residence character varying(1),
    am_bus character varying(1),
    pm_bus character varying(1),
    mailing character varying(1),
    residence character varying(1),
    bus character varying(1),
    bus_pickup character varying(1),
    bus_dropoff character varying(1),
    bus_no character varying(50)
)ENGINE=InnoDB;

ALTER TABLE students_join_address AUTO_INCREMENT=1;


CREATE TABLE students_join_people (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    college_roll_no numeric NOT NULL,
    person_id numeric(10,0) NOT NULL,
    address_id numeric,
    custody character varying(1),
    emergency character varying(1),
    student_relation character varying(100),
    addn_bus_pickup character varying(2),
    addn_bus_dropoff character varying(2),
    addn_busno character varying(50),
    addn_home_phone character varying(100),
    addn_work_phone character varying(100),
    addn_mobile_phone character varying(100),
    addn_email character varying(100),
    addn_address character varying(100),
    addn_street character varying(100),
    addn_city character varying(100),
    addn_state character varying(100),
    addn_zipcode character varying(100)
)ENGINE=InnoDB;

ALTER TABLE students_join_people AUTO_INCREMENT=1;


CREATE TABLE students_join_users (
    college_roll_no numeric NOT NULL,
    staff_id numeric NOT NULL
)ENGINE=InnoDB;


CREATE TABLE user_profiles (
    id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    profile character varying(30),
    title character varying(100)
)ENGINE=InnoDB;

ALTER TABLE user_profiles AUTO_INCREMENT=1;


CREATE TABLE hacking_log (
    host_name character varying(20),
    ip_address character varying(20),
    login_date date,
    version character varying(20),
    php_self character varying(20),
    document_root character varying(100),
    script_name character varying(100),
    modname character varying(100),
    username character varying(20)
)ENGINE=InnoDB;


CREATE TABLE `teacher_reassignment` (
 `course_period_id` int(11) NOT NULL,
 `teacher_id` int(11) NOT NULL,
 `assign_date` date NOT NULL,
 `modified_date` date NOT NULL,
 `pre_teacher_id` int(11) NOT NULL,
 `modified_by` int(11) NOT NULL,
 `updated` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB;

CREATE TABLE `staff_college_relationship` (
 `staff_id` int(11) NOT NULL,
 `college_id` int(11) NOT NULL,
 `syear` int(4) NOT NULL,
 PRIMARY KEY (`staff_id`,`college_id`,`syear`)
) ENGINE=InnoDB;

--
--
--


CREATE TABLE IF NOT EXISTS `temp_message_filepath_ws` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyval` varchar(100) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE `device_info` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `device_type` varchar(255) CHARACTER SET utf8 NOT NULL,
  `device_token` longtext CHARACTER SET utf8 NOT NULL,
  `device_id` longtext CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





CREATE TABLE `filters` (
  `filter_id` int(11) NOT NULL,
  `filter_name` varchar(255) DEFAULT NULL,
  `college_id` int(11) DEFAULT '0',
  `show_to` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `filter_fields` (
  `filter_field_id` int(11) NOT NULL,
  `filter_id` int(11) DEFAULT NULL,
  `filter_column` varchar(255) DEFAULT NULL,
  `filter_value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE `filters`
  ADD PRIMARY KEY (`filter_id`);

--
-- Indexes for table `filter_fields`
--
ALTER TABLE `filter_fields`
  ADD PRIMARY KEY (`filter_field_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `filters`
--
ALTER TABLE `filters`
  MODIFY `filter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `filter_fields`
--
ALTER TABLE `filter_fields`
  MODIFY `filter_field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;



ALTER TABLE attendance_calendars AUTO_INCREMENT=1;



ALTER TABLE course_periods AUTO_INCREMENT=1;


-- ALTER TABLE marking_periods AUTO_INCREMENT=1;

--
--
--






ALTER TABLE attendance_calendar
    ADD CONSTRAINT attendance_calendar_pkey PRIMARY KEY (syear, college_id, college_date, calendar_id);


--ALTER TABLE attendance_completed
  -- ADD CONSTRAINT attendance_completed_pkey PRIMARY KEY (staff_id, college_date, period_id);


ALTER TABLE attendance_day
    ADD CONSTRAINT attendance_day_pkey PRIMARY KEY (college_roll_no, college_date);


--ALTER TABLE attendance_period
    --ADD CONSTRAINT attendance_period_pkey PRIMARY KEY (college_roll_no, college_date, course_period_id);


-- ALTER TABLE calendar_events
  --  ADD CONSTRAINT calendar_events_pkey PRIMARY KEY (id);


-- ALTER TABLE course_periods
 --   ADD CONSTRAINT course_periods_pkey PRIMARY KEY (course_period_id);


-- ALTER TABLE course_subjects
 --   ADD CONSTRAINT course_subjects_pkey PRIMARY KEY (subject_id);


-- ALTER TABLE courses
   --  ADD CONSTRAINT courses_pkey PRIMARY KEY (course_id);


-- ALTER TABLE custom_fields
 --   ADD CONSTRAINT custom_fields_pkey PRIMARY KEY (id);

-- ALTER TABLE eligibility_activities
 --   ADD CONSTRAINT eligibility_activities_pkey PRIMARY KEY (id);


ALTER TABLE eligibility_completed
    ADD CONSTRAINT eligibility_completed_pkey PRIMARY KEY (staff_id, college_date, period_id);


-- ALTER TABLE gradebook_assignment_types
 --   ADD CONSTRAINT gradebook_assignment_types_pkey PRIMARY KEY (assignment_type_id);


-- ALTER TABLE gradebook_assignments
--    ADD CONSTRAINT gradebook_assignments_pkey PRIMARY KEY (assignment_id);


ALTER TABLE gradebook_grades
    ADD CONSTRAINT gradebook_grades_pkey PRIMARY KEY (college_roll_no, assignment_id, course_period_id);


ALTER TABLE grades_completed
    ADD CONSTRAINT grades_completed_pkey PRIMARY KEY (staff_id, marking_period_id, period_id);


ALTER TABLE history_marking_periods
    ADD CONSTRAINT history_marking_periods_pkey PRIMARY KEY (marking_period_id);


-- ALTER TABLE people_field_categories
 --   ADD CONSTRAINT people_field_categories_pkey PRIMARY KEY (id);


-- ALTER TABLE people_fields
--    ADD CONSTRAINT people_fields_pkey PRIMARY KEY (id);


-- ALTER TABLE people_join_contacts
 --   ADD CONSTRAINT people_join_contacts_pkey PRIMARY KEY (id);


-- ALTER TABLE people
  --  ADD CONSTRAINT people_pkey PRIMARY KEY (person_id);



-- ALTER TABLE report_card_comments
 --   ADD CONSTRAINT report_card_comments_pkey PRIMARY KEY (id);


-- ALTER TABLE report_card_grade_scales
  --  ADD CONSTRAINT report_card_grade_scales_pkey PRIMARY KEY (id);


-- ALTER TABLE report_card_grades
 --   ADD CONSTRAINT report_card_grades_pkey PRIMARY KEY (id);


-- ALTER TABLE schedule
 --   ADD CONSTRAINT schedule_pkey PRIMARY KEY (syear, college_roll_no, course_id, course_period_id, start_date);


-- ALTER TABLE schedule_requests
 --   ADD CONSTRAINT schedule_requests_pkey PRIMARY KEY (request_id);


-- ALTER TABLE college_gradelevels
  --  ADD CONSTRAINT college_gradelevels_pkey PRIMARY KEY (id);



ALTER TABLE college_progress_periods
    ADD CONSTRAINT college_progress_periods_pkey PRIMARY KEY (marking_period_id);

--ALTER TABLE `college_progress_periods` CHANGE `marking_period_id` `marking_period_id` INT( 8 ) NOT NULL AUTO_INCREMENT ;

--ALTER TABLE college_progress_periods AUTO_INCREMENT=1;

ALTER TABLE college_quarters
    ADD CONSTRAINT college_quarters_pkey PRIMARY KEY (marking_period_id);

--ALTER TABLE `college_quarters` CHANGE `marking_period_id` `marking_period_id` INT( 8 ) NOT NULL AUTO_INCREMENT ;

--ALTER TABLE college_quarters AUTO_INCREMENT=1;

ALTER TABLE college_semesters
    ADD CONSTRAINT college_semesters_pkey PRIMARY KEY (marking_period_id);

--ALTER TABLE `college_semesters` CHANGE `marking_period_id` `marking_period_id` INT( 8 ) NOT NULL AUTO_INCREMENT ;

--ALTER TABLE college_semesters AUTO_INCREMENT=1;

ALTER TABLE college_years
    ADD CONSTRAINT college_years_pkey PRIMARY KEY (marking_period_id);

--ALTER TABLE `college_years` CHANGE `marking_period_id` `marking_period_id` INT( 8 ) NOT NULL AUTO_INCREMENT ;

--ALTER TABLE college_years AUTO_INCREMENT=1;



-- ALTER TABLE staff_field_categories
 --   ADD CONSTRAINT staff_field_categories_pkey PRIMARY KEY (id);


--ALTER TABLE staff_fields
 --   ADD CONSTRAINT staff_fields_pkey PRIMARY KEY (id);



-- ALTER TABLE student_enrollment
 --   ADD CONSTRAINT student_enrollment_pkey PRIMARY KEY (id);


-- ALTER TABLE student_field_categories
 --   ADD CONSTRAINT student_field_categories_pkey PRIMARY KEY (id);


-- ALTER TABLE student_medical_alerts
 --   ADD CONSTRAINT student_medical_alerts_pkey PRIMARY KEY (id);


-- ALTER TABLE student_medical
 --  ADD CONSTRAINT student_medical_pkey PRIMARY KEY (id);


--ALTER TABLE student_medical_visits
 --   ADD CONSTRAINT student_medical_visits_pkey PRIMARY KEY (id);


--ALTER TABLE student_mp_comments
 --   ADD CONSTRAINT student_mp_comments_pkey PRIMARY KEY (college_roll_no, syear, marking_period_id);


ALTER TABLE student_mp_stats
    ADD CONSTRAINT student_mp_stats_pkey PRIMARY KEY (college_roll_no, marking_period_id);


ALTER TABLE student_report_card_comments
    ADD CONSTRAINT student_report_card_comments_pkey PRIMARY KEY (syear, college_roll_no, course_period_id, marking_period_id, report_card_comment_id);


-- ALTER TABLE student_report_card_grades
 --   ADD CONSTRAINT student_report_card_grades_id_key UNIQUE (id);


-- ALTER TABLE student_report_card_grades
 --   ADD CONSTRAINT student_report_card_grades_pkey PRIMARY KEY (id);


-- ALTER TABLE students_join_address
 --   ADD CONSTRAINT students_join_address_pkey PRIMARY KEY (id);


-- ALTER TABLE students_join_people
 --   ADD CONSTRAINT students_join_people_pkey PRIMARY KEY (id);


ALTER TABLE students_join_users
    ADD CONSTRAINT students_join_users_pkey PRIMARY KEY (college_roll_no, staff_id);


-- ALTER TABLE students
 --   ADD CONSTRAINT students_pkey PRIMARY KEY (college_roll_no);
ALTER TABLE `staff_college_relationship` ADD `start_date` DATE NOT NULL ,
ADD `end_date` DATE NOT NULL ;
--
--
--

CREATE INDEX address_3  USING btree ON address(zipcode, plus4);


CREATE INDEX address_4  USING btree ON address(street);


CREATE INDEX address_desc_ind  USING btree ON address_fields(id);


CREATE INDEX address_desc_ind2  USING btree ON custom_fields(type);


CREATE INDEX address_fields_ind3  USING btree ON custom_fields(category_id);


CREATE INDEX attendance_code_categories_ind1  USING btree ON attendance_code_categories(id);


CREATE INDEX attendance_code_categories_ind2  USING btree ON attendance_code_categories(syear, college_id);


CREATE INDEX attendance_codes_ind2  USING btree ON attendance_codes(syear, college_id);


CREATE INDEX attendance_codes_ind3  USING btree ON attendance_codes(short_name);


CREATE INDEX attendance_period_ind1  USING btree ON attendance_period(college_roll_no);


CREATE INDEX attendance_period_ind2  USING btree ON attendance_period(period_id);


CREATE INDEX attendance_period_ind3  USING btree ON attendance_period(attendance_code);


CREATE INDEX attendance_period_ind4  USING btree ON attendance_period(college_date);


CREATE INDEX attendance_period_ind5  USING btree ON attendance_period(attendance_code);


CREATE INDEX course_periods_ind1  USING btree ON course_periods(syear);


CREATE INDEX course_periods_ind2  USING btree ON course_periods(course_id, course_weight, syear, college_id);


CREATE INDEX course_periods_ind3  USING btree ON course_periods(course_period_id);


CREATE INDEX course_periods_ind4  USING btree ON course_periods(period_id);


CREATE INDEX course_periods_ind5  USING btree ON course_periods(parent_id);


CREATE INDEX course_subjects_ind1  USING btree ON course_subjects(syear, college_id, subject_id);


CREATE INDEX courses_ind1  USING btree ON courses(course_id, syear);


CREATE INDEX courses_ind2  USING btree ON courses(subject_id);


CREATE INDEX custom_desc_ind  USING btree ON custom_fields(id);


CREATE INDEX custom_desc_ind2  USING btree ON custom_fields(type);


CREATE INDEX custom_fields_ind3  USING btree ON custom_fields(category_id);


CREATE INDEX eligibility_activities_ind1  USING btree ON eligibility_activities(college_id, syear);


CREATE INDEX eligibility_ind1  USING btree ON eligibility(college_roll_no, course_period_id, college_date);


CREATE INDEX gradebook_assignment_types_ind1  USING btree ON gradebook_assignments(staff_id, course_id);


CREATE INDEX gradebook_assignments_ind1  USING btree ON gradebook_assignments(staff_id, marking_period_id);


CREATE INDEX gradebook_assignments_ind2  USING btree ON gradebook_assignments(course_id, course_period_id);


CREATE INDEX gradebook_assignments_ind3  USING btree ON gradebook_assignments(assignment_type_id);


CREATE INDEX gradebook_grades_ind1  USING btree ON gradebook_grades(assignment_id);


CREATE INDEX history_marking_period_ind1  USING btree ON history_marking_periods(college_id);


CREATE INDEX history_marking_period_ind2  USING btree ON history_marking_periods(syear);


CREATE INDEX history_marking_period_ind3  USING btree ON history_marking_periods(mp_type);


CREATE INDEX name  USING btree ON students(last_name, first_name, middle_name);


CREATE INDEX people_1  USING btree ON people(last_name, first_name);


CREATE INDEX people_3  USING btree ON people(person_id, last_name, first_name, middle_name);


CREATE INDEX people_desc_ind  USING btree ON people_fields(id);


CREATE INDEX people_desc_ind2  USING btree ON custom_fields(type);


CREATE INDEX people_fields_ind3  USING btree ON custom_fields(category_id);


CREATE INDEX people_join_contacts_ind1  USING btree ON people_join_contacts(person_id);


CREATE INDEX program_config_ind1  USING btree ON program_config(program, college_id, syear);


CREATE INDEX program_user_config_ind1  USING btree ON program_user_config(user_id, program);


CREATE INDEX relations_meets_2  USING btree ON students_join_people(person_id);


CREATE INDEX relations_meets_5  USING btree ON students_join_people(id);


CREATE INDEX relations_meets_6  USING btree ON students_join_people(custody, emergency);


CREATE INDEX report_card_comments_ind1  USING btree ON report_card_comments(syear, college_id);


CREATE INDEX report_card_grades_ind1  USING btree ON report_card_grades(syear, college_id);


CREATE INDEX schedule_ind1  USING btree ON schedule(course_id, course_weight);


CREATE INDEX schedule_ind2  USING btree ON schedule(course_period_id);


CREATE INDEX schedule_ind3  USING btree ON schedule(college_roll_no, marking_period_id, start_date, end_date);


CREATE INDEX schedule_ind4  USING btree ON schedule(syear, college_id);


CREATE INDEX schedule_requests_ind1  USING btree ON schedule_requests(college_roll_no, course_id, course_weight, syear, college_id);


CREATE INDEX schedule_requests_ind2  USING btree ON schedule_requests(syear, college_id);


CREATE INDEX schedule_requests_ind3  USING btree ON schedule_requests(course_id, course_weight, syear, college_id);


CREATE INDEX schedule_requests_ind4  USING btree ON schedule_requests(with_teacher_id);


CREATE INDEX schedule_requests_ind5  USING btree ON schedule_requests(not_teacher_id);


CREATE INDEX schedule_requests_ind6  USING btree ON schedule_requests(with_period_id);


CREATE INDEX schedule_requests_ind7  USING btree ON schedule_requests(not_period_id);


CREATE INDEX schedule_requests_ind8  USING btree ON schedule_requests(request_id);


CREATE INDEX college_gradelevels_ind1  USING btree ON college_gradelevels(college_id);


CREATE INDEX college_periods_ind1  USING btree ON college_periods(period_id, syear);


CREATE INDEX college_progress_periods_ind1  USING btree ON college_progress_periods(quarter_id);


CREATE INDEX college_progress_periods_ind2  USING btree ON college_progress_periods(syear, college_id, start_date, end_date);


CREATE INDEX college_quarters_ind1  USING btree ON college_quarters(semester_id);


CREATE INDEX college_quarters_ind2  USING btree ON college_quarters(syear, college_id, start_date, end_date);


CREATE INDEX college_semesters_ind1  USING btree ON college_semesters(year_id);


CREATE INDEX college_semesters_ind2  USING btree ON college_semesters(syear, college_id, start_date, end_date);


CREATE INDEX college_years_ind2  USING btree ON college_years(syear, college_id, start_date, end_date);


CREATE INDEX colleges_ind1  USING btree ON colleges(syear);

CREATE INDEX staff_desc_ind1  USING btree ON staff_fields(id);


CREATE INDEX staff_desc_ind2  USING btree ON staff_fields(type);


CREATE INDEX staff_fields_ind3  USING btree ON staff_fields(category_id);


CREATE INDEX staff_ind2  USING btree ON staff(last_name, first_name);


CREATE INDEX stu_addr_meets_2  USING btree ON students_join_address(address_id);


CREATE INDEX stu_addr_meets_3  USING btree ON students_join_address(primary_residence);


CREATE INDEX stu_addr_meets_4  USING btree ON students_join_address(legal_residence);


CREATE INDEX student_eligibility_activities_ind1  USING btree ON student_eligibility_activities(college_roll_no);


CREATE INDEX student_enrollment_1  USING btree ON student_enrollment(college_roll_no, enrollment_code);


CREATE INDEX student_enrollment_2  USING btree ON student_enrollment(grade_id);


CREATE INDEX student_enrollment_3  USING btree ON student_enrollment(syear, college_roll_no, college_id, grade_id);


CREATE INDEX student_enrollment_6  USING btree ON student_enrollment(syear, college_roll_no,start_date, end_date);


CREATE INDEX student_enrollment_7  USING btree ON student_enrollment(college_id);


CREATE INDEX student_gpa_calculated_ind1  USING btree ON student_gpa_calculated(marking_period_id, college_roll_no);


CREATE INDEX student_gpa_running_ind1  USING btree ON student_gpa_running(marking_period_id, college_roll_no);


CREATE INDEX student_medical_alerts_ind1  USING btree ON student_medical_alerts(college_roll_no);


CREATE INDEX student_medical_ind1  USING btree ON student_medical(college_roll_no);


CREATE INDEX student_medical_visits_ind1  USING btree ON student_medical_visits(college_roll_no);


CREATE INDEX student_report_card_comments_ind1  USING btree ON student_report_card_comments(college_id);


CREATE INDEX student_report_card_grades_ind1  USING btree ON student_report_card_grades(college_id);


CREATE INDEX student_report_card_grades_ind2  USING btree ON student_report_card_grades(college_roll_no);


CREATE INDEX student_report_card_grades_ind3  USING btree ON student_report_card_grades(course_period_id);


CREATE INDEX student_report_card_grades_ind4  USING btree ON student_report_card_grades(marking_period_id);


CREATE INDEX students_join_address_ind1  USING btree ON students_join_address(college_roll_no);


CREATE INDEX students_join_people_ind1  USING btree ON students_join_people(college_roll_no);


CREATE INDEX sys_c007322  USING btree ON students_join_address(id, college_roll_no, address_id);

--
-- TABLE STRUCTURE FOR TABLE history_college
--
CREATE TABLE IF NOT EXISTS `history_college` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `college_roll_no` int(11) NOT NULL,
  `marking_period_id` int(11) NOT NULL,
  `college_name` varchar(100) NOT NULL,
   PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
--
--

CREATE VIEW course_details AS
  SELECT cp.college_id, cp.syear, cp.marking_period_id, cp.period_id, c.subject_id,
	  cp.course_id, cp.course_period_id, cp.teacher_id,cp. secondary_teacher_id, c.title AS course_title,
	  cp.title AS cp_title, cp.grade_scale_id, cp.mp, cp.credits
  FROM course_periods cp, courses c WHERE (cp.course_id = c.course_id);

CREATE VIEW enroll_grade AS
  SELECT e.id, e.syear, e.college_id, e.college_roll_no, e.start_date, e.end_date, sg.short_name, sg.title
  FROM student_enrollment e, college_gradelevels sg WHERE (e.grade_id = sg.id);


CREATE VIEW transcript_grades AS
    SELECT s.id AS college_id, IF(mp.mp_source='history',(SELECT college_name FROM history_college WHERE college_roll_no=rcg.college_roll_no and marking_period_id=mp.marking_period_id),s.title) AS college_name,mp_source, mp.marking_period_id AS mp_id,
 mp.title AS mp_name, mp.syear, mp.end_date AS posted, rcg.college_roll_no,
 sgc.grade_level_short AS gradelevel, rcg.grade_letter, rcg.unweighted_gp AS gp_value,
 rcg.weighted_gp AS weighting, rcg.gp_scale, rcg.credit_attempted, rcg.credit_earned,
 rcg.credit_category,rcg.course_period_id AS course_period_id, rcg.course_title AS course_name,
        (SELECT courses.short_name FROM course_periods,courses  WHERE course_periods.course_id=courses.course_id and course_periods.course_period_id=rcg.course_period_id) AS course_short_name,rcg.gpa_cal AS gpa_cal,
 sgc.weighted_gpa,
 sgc.unweighted_gpa,
                  sgc.gpa,
 sgc.class_rank,mp.sort_order
    FROM student_report_card_grades rcg
    INNER JOIN marking_periods mp ON mp.marking_period_id = rcg.marking_period_id AND mp.mp_type IN ('year','semester','quarter')
    INNER JOIN student_gpa_calculated sgc ON sgc.college_roll_no = rcg.college_roll_no AND sgc.marking_period_id = rcg.marking_period_id
    INNER JOIN colleges s ON s.id = mp.college_id;


-- ****************** For storing all log details ***************************
CREATE TABLE login_records
(
  syear numeric(5),
  first_name character varying(100),
  last_name character varying(100),
  profile character varying(50),
  user_name character varying(100),
  login_time timestamp,
  faillog_count numeric(4),
  staff_id numeric(10),
  id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  faillog_time varchar(255) DEFAULT NULL,
  ip_address character varying(20),
  status character varying(50),
  college_id DECIMAL( 10 ) NULL 
)ENGINE=InnoDB;


-- ******************** For Creating Login Seq **************************

ALTER TABLE login_records AUTO_INCREMENT=1;
-- ********************* Log Maintain Table *****************************
CREATE TABLE log_maintain
(
  id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  value numeric(30),
  session_id character varying(100)
)ENGINE=InnoDB;


-- *********************** Log Maintain Sequence ************************


ALTER TABLE log_maintain AUTO_INCREMENT=1;

--
-- Table structure for table  system_preference_misc
--

CREATE TABLE system_preference_misc (
  fail_count decimal(5,0) NOT NULL default '3',
  activity_days decimal(5,0) NOT NULL default '30',
  system_maintenance_switch char(1) character set utf8 collate utf8_bin default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE goal
(
  goal_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  college_roll_no numeric NOT NULL,
  goal_title character varying(100),
  start_date date,
  end_date date,
  goal_description text,
  college_id numeric(10),
  syear numeric(10)
)ENGINE=InnoDB;


CREATE TABLE progress
(
  progress_id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  goal_id numeric NOT NULL,
  college_roll_no numeric NOT NULL,
  start_date date,
  progress_name text NOT NULL,
  proficiency character varying(100) NOT NULL,
  progress_description text NOT NULL,
  course_period_id numeric(10)
)ENGINE=InnoDB;




ALTER TABLE goal AUTO_INCREMENT=1;





ALTER TABLE progress AUTO_INCREMENT=1;

--
-- TABLE STRUCTURE FOR TABLE login_message
--

CREATE TABLE login_message (
  id INT(8) NOT NULL AUTO_INCREMENT,
  message longtext DEFAULT NULL,
display char(1) character set utf8 collate utf8_bin default NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `student_enrollment` ADD  `section_id` VARCHAR( 255 ) NULL AFTER  `grade_id`;

CREATE TABLE IF NOT EXISTS `college_gradelevel_sections` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `college_id` decimal(10,0) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `sort_order` decimal(10,0) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `college_gradelevels_ind1` (`college_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `user_file_upload` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `college_id` int(11) NOT NULL,
  `syear` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `content` longblob NOT NULL,
  `file_info` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `staff` ADD `img_name` VARCHAR(255) NULL AFTER `disability_desc`;
ALTER TABLE `staff` ADD `img_content` LONGBLOB NULL AFTER `img_name`;

ALTER TABLE `students` CHANGE `language` `language_id` INT(8) NULL DEFAULT NULL;

CREATE VIEW student_contacts AS
   SELECT DISTINCT sta.college_roll_no AS college_roll_no,st.alt_id,CONCAT( st.first_name, ' ', st.last_name )AS student_name,'Primary' AS contact_type,
        prim_student_relation AS relation, 
        pri_first_name AS relation_first_name,  pri_last_name AS relation_last_name,prim_address AS address1, prim_street AS address2,city AS city,state AS state,zipcode AS zip,work_phone AS work_phone,home_phone AS home_phone,mobile_phone AS cell_phone,
        address.email AS email_id,'1' AS sort
    FROM students_join_address sta,address,students st WHERE   address.address_id=sta.address_id AND   st.college_roll_no=sta.college_roll_no 
           
UNION
   SELECT DISTINCT sta.college_roll_no AS college_roll_no,st.alt_id,CONCAT( st.first_name, ' ', st.last_name )AS student_name,'Secondary' AS contact_type,
        sec_student_relation AS relation,
        sec_first_name AS relation_first_name, sec_last_name AS relation_last_name,sec_address AS address1, sec_street AS address2,city AS city,state AS state,zipcode AS zip, sec_work_phone AS work_phone,sec_home_phone AS home_phone,sec_mobile_phone AS cell_phone,
        address.email AS email_id,'2' AS sort
    FROM students_join_address sta,address,students st WHERE   address.address_id=sta.address_id AND   st.college_roll_no=sta.college_roll_no 
          
UNION
    SELECT DISTINCT  stp.college_roll_no AS college_roll_no,st.alt_id, CONCAT( st.first_name, ' ', st.last_name )AS student_name,'Other' AS contact_type,
        stp.student_relation AS relation,
        people.first_name AS relation_first_name,  people.last_name AS relation_last_name,stp.addn_address AS address1, stp.addn_street AS address2,addn_city AS city,addn_state AS state,addn_zipcode AS zip,addn_work_phone AS work_phone,addn_home_phone AS home_phone,addn_mobile_phone AS cell_phone,
        stp.addn_email AS email_id,'3' AS sort
    FROM people,students_join_people stp ,students st  WHERE   people.person_id=stp.person_id  AND   st.college_roll_no=stp.college_roll_no;
