-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2020 at 10:04 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `per_academic_session`
--

CREATE TABLE `per_academic_session` (
  `id` int(12) NOT NULL,
  `session_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `is_current` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_academic_session_settings`
--

CREATE TABLE `per_academic_session_settings` (
  `id` int(11) NOT NULL,
  `session_id` int(20) NOT NULL,
  `can_enroll` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `can_update_timetable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `can_update_attendance` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_assignment`
--

CREATE TABLE `per_assignment` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `session_id` int(20) NOT NULL,
  `subject_id` int(20) NOT NULL,
  `marks` int(20) NOT NULL,
  `submission_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_assignment_marks`
--

CREATE TABLE `per_assignment_marks` (
  `id` int(11) NOT NULL,
  `assignment_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `obtained_marks` decimal(20,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_assignment_submission`
--

CREATE TABLE `per_assignment_submission` (
  `id` int(20) NOT NULL,
  `assignment_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL COMMENT 'links to student table id',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_assign_teacher`
--

CREATE TABLE `per_assign_teacher` (
  `id` int(20) NOT NULL,
  `session_id` int(20) NOT NULL,
  `subject_id` int(20) NOT NULL,
  `teacher_id` int(20) NOT NULL COMMENT 'links to teacher table id',
  `type` enum('lecture','lab') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_attendance`
--

CREATE TABLE `per_attendance` (
  `id` int(20) NOT NULL,
  `class_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL COMMENT 'links to student table id',
  `week_id` int(20) NOT NULL,
  `remarks` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_classes`
--

CREATE TABLE `per_classes` (
  `id` int(11) NOT NULL,
  `session_id` int(20) NOT NULL,
  `subject_id` int(20) NOT NULL,
  `teacher_id` int(20) NOT NULL COMMENT 'links to teaher table''s id',
  `room_id` int(20) NOT NULL,
  `day_id` int(20) NOT NULL,
  `start_time` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('lecture','lab') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_courses`
--

CREATE TABLE `per_courses` (
  `id` int(11) NOT NULL,
  `course_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `course_type_id` int(12) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_course_type`
--

CREATE TABLE `per_course_type` (
  `id` int(11) NOT NULL,
  `course_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_enrollment`
--

CREATE TABLE `per_enrollment` (
  `id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL COMMENT 'links to student table id',
  `session_id` int(20) NOT NULL,
  `course_id` int(20) NOT NULL,
  `course_type_id` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_enrollment_subjects`
--

CREATE TABLE `per_enrollment_subjects` (
  `id` int(20) NOT NULL,
  `enrollment_id` int(20) NOT NULL,
  `subject_id` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_events`
--

CREATE TABLE `per_events` (
  `id` int(20) NOT NULL,
  `event_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `event_for` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_exam`
--

CREATE TABLE `per_exam` (
  `id` int(11) NOT NULL,
  `session_id` int(20) NOT NULL,
  `exam_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `marks` int(20) NOT NULL,
  `start_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_exam_marks`
--

CREATE TABLE `per_exam_marks` (
  `id` int(11) NOT NULL,
  `exam_id` int(25) NOT NULL,
  `student_id` int(25) NOT NULL,
  `subject_id` int(25) NOT NULL,
  `obtained_marks` decimal(25,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_lectures`
--

CREATE TABLE `per_lectures` (
  `id` int(20) NOT NULL,
  `lecture_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lecture_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_id` int(20) NOT NULL,
  `subject_id` int(20) NOT NULL,
  `sort_order` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_password_resets`
--

CREATE TABLE `per_password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_resources`
--

CREATE TABLE `per_resources` (
  `id` int(20) NOT NULL,
  `resource_id` int(20) NOT NULL,
  `unique_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filename` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `s3_url` text COLLATE utf8_unicode_ci NOT NULL,
  `resource_table` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_result`
--

CREATE TABLE `per_result` (
  `id` int(20) NOT NULL,
  `session_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL COMMENT 'links to student table id',
  `subject_id` int(20) NOT NULL,
  `assignment_marks` decimal(20,2) DEFAULT NULL,
  `assignment_assessable_marks` decimal(20,2) DEFAULT NULL,
  `exam_marks` decimal(20,2) DEFAULT NULL,
  `exam_assessable_marks` decimal(20,2) DEFAULT NULL,
  `total_assessable_marks` decimal(20,2) DEFAULT NULL,
  `grade` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `total_obtained_marks` decimal(20,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_result_details`
--

CREATE TABLE `per_result_details` (
  `id` int(11) NOT NULL,
  `result_id` int(20) NOT NULL,
  `assignment_details` text COLLATE utf8_unicode_ci NOT NULL,
  `exam_details` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_roles`
--

CREATE TABLE `per_roles` (
  `id` int(20) NOT NULL,
  `role_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `per_roles`
--

INSERT INTO `per_roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'lecturer', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'student', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'tutor', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `per_room`
--

CREATE TABLE `per_room` (
  `id` int(20) NOT NULL,
  `room_code` int(20) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `room_type` enum('lecture_room','lab_room') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_slider`
--

CREATE TABLE `per_slider` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL,
  `is_active` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_student`
--

CREATE TABLE `per_student` (
  `id` int(20) NOT NULL,
  `student_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `current_course_id` int(20) NOT NULL,
  `current_course_type_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_subjects`
--

CREATE TABLE `per_subjects` (
  `id` int(12) NOT NULL,
  `subject_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_graded` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `credit_points` double(12,2) NOT NULL,
  `course_id` int(12) NOT NULL,
  `course_type_id` int(12) NOT NULL,
  `full_marks` double(12,2) NOT NULL,
  `pass_marks` double(12,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_teacher`
--

CREATE TABLE `per_teacher` (
  `id` int(20) NOT NULL,
  `teacher_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_timetable`
--

CREATE TABLE `per_timetable` (
  `id` int(20) NOT NULL,
  `class_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL COMMENT 'links to student table id',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_users`
--

CREATE TABLE `per_users` (
  `id` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `emergency_contact_name` varchar(20) DEFAULT NULL,
  `dob` date NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `per_users`
--

INSERT INTO `per_users` (`id`, `name`, `email`, `password`, `address`, `phone`, `photo`, `emergency_contact_number`, `emergency_contact_name`, `dob`, `remember_token`, `api_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', '$2y$10$M8WTShCz.5K6.yFvWhmsbOx8nmZ4liPVqgRiMIjTh.j8TOT.4TFoq', 'asdasdsdssds', '042344412', NULL, '0421300231', 'Moone Collins', '1994-02-01', 'PHt34uLDvmN2X8z8wQt88PK9MfaFw0ShS65XDj28YsBS3Y1kq2rNgyO7sM8J', 'EfTkMDn3aIQ2mUFhEAqG0DehUREoz3QyS6OaSgaMJol0hIh3Z5o9y43wcAod', '2018-05-20 07:57:14', '2020-09-06 13:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `per_user_roles`
--

CREATE TABLE `per_user_roles` (
  `id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `role_id` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `per_user_roles`
--

INSERT INTO `per_user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `per_academic_session`
--
ALTER TABLE `per_academic_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_academic_session_settings`
--
ALTER TABLE `per_academic_session_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `per_assignment`
--
ALTER TABLE `per_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `per_assignment_marks`
--
ALTER TABLE `per_assignment_marks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `per_assignment_submission`
--
ALTER TABLE `per_assignment_submission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `per_assign_teacher`
--
ALTER TABLE `per_assign_teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `per_attendance`
--
ALTER TABLE `per_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `per_classes`
--
ALTER TABLE `per_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `per_courses`
--
ALTER TABLE `per_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_type_id` (`course_type_id`);

--
-- Indexes for table `per_course_type`
--
ALTER TABLE `per_course_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_enrollment`
--
ALTER TABLE `per_enrollment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `course_type_id` (`course_type_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `per_enrollment_subjects`
--
ALTER TABLE `per_enrollment_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_id` (`enrollment_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `per_events`
--
ALTER TABLE `per_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_exam`
--
ALTER TABLE `per_exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `per_exam_marks`
--
ALTER TABLE `per_exam_marks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `per_lectures`
--
ALTER TABLE `per_lectures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `per_password_resets`
--
ALTER TABLE `per_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_resources`
--
ALTER TABLE `per_resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_result`
--
ALTER TABLE `per_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `per_result_details`
--
ALTER TABLE `per_result_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_id` (`result_id`);

--
-- Indexes for table `per_roles`
--
ALTER TABLE `per_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_room`
--
ALTER TABLE `per_room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_slider`
--
ALTER TABLE `per_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_student`
--
ALTER TABLE `per_student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `current_course_id` (`current_course_id`),
  ADD KEY `current_course_type_id` (`current_course_type_id`);

--
-- Indexes for table `per_subjects`
--
ALTER TABLE `per_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `course_type_id` (`course_type_id`);

--
-- Indexes for table `per_teacher`
--
ALTER TABLE `per_teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `per_timetable`
--
ALTER TABLE `per_timetable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `per_users`
--
ALTER TABLE `per_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_user_roles`
--
ALTER TABLE `per_user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `per_academic_session`
--
ALTER TABLE `per_academic_session`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `per_academic_session_settings`
--
ALTER TABLE `per_academic_session_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `per_assignment`
--
ALTER TABLE `per_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `per_assignment_marks`
--
ALTER TABLE `per_assignment_marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `per_assignment_submission`
--
ALTER TABLE `per_assignment_submission`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `per_assign_teacher`
--
ALTER TABLE `per_assign_teacher`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `per_attendance`
--
ALTER TABLE `per_attendance`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `per_classes`
--
ALTER TABLE `per_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `per_courses`
--
ALTER TABLE `per_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `per_course_type`
--
ALTER TABLE `per_course_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `per_enrollment`
--
ALTER TABLE `per_enrollment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `per_enrollment_subjects`
--
ALTER TABLE `per_enrollment_subjects`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `per_events`
--
ALTER TABLE `per_events`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `per_exam`
--
ALTER TABLE `per_exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `per_exam_marks`
--
ALTER TABLE `per_exam_marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `per_lectures`
--
ALTER TABLE `per_lectures`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `per_password_resets`
--
ALTER TABLE `per_password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `per_resources`
--
ALTER TABLE `per_resources`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `per_result`
--
ALTER TABLE `per_result`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `per_result_details`
--
ALTER TABLE `per_result_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `per_roles`
--
ALTER TABLE `per_roles`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `per_room`
--
ALTER TABLE `per_room`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `per_slider`
--
ALTER TABLE `per_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `per_student`
--
ALTER TABLE `per_student`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `per_subjects`
--
ALTER TABLE `per_subjects`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `per_teacher`
--
ALTER TABLE `per_teacher`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `per_timetable`
--
ALTER TABLE `per_timetable`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `per_users`
--
ALTER TABLE `per_users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `per_user_roles`
--
ALTER TABLE `per_user_roles`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `per_academic_session_settings`
--
ALTER TABLE `per_academic_session_settings`
  ADD CONSTRAINT `per_academic_session_settings_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_assignment`
--
ALTER TABLE `per_assignment`
  ADD CONSTRAINT `per_assignment_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_assignment_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `per_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_assignment_marks`
--
ALTER TABLE `per_assignment_marks`
  ADD CONSTRAINT `per_assignment_marks_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `per_assignment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_assignment_marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `per_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_assignment_submission`
--
ALTER TABLE `per_assignment_submission`
  ADD CONSTRAINT `per_assignment_submission_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `per_assignment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_assignment_submission_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `per_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_assign_teacher`
--
ALTER TABLE `per_assign_teacher`
  ADD CONSTRAINT `per_assign_teacher_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_assign_teacher_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `per_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_assign_teacher_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `per_teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_attendance`
--
ALTER TABLE `per_attendance`
  ADD CONSTRAINT `per_attendance_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `per_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_attendance_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `per_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_classes`
--
ALTER TABLE `per_classes`
  ADD CONSTRAINT `per_classes_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_classes_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `per_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_classes_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `per_teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_classes_ibfk_4` FOREIGN KEY (`room_id`) REFERENCES `per_room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_courses`
--
ALTER TABLE `per_courses`
  ADD CONSTRAINT `per_courses_ibfk_1` FOREIGN KEY (`course_type_id`) REFERENCES `per_course_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_enrollment`
--
ALTER TABLE `per_enrollment`
  ADD CONSTRAINT `per_enrollment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `per_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_enrollment_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_enrollment_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `per_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_enrollment_ibfk_4` FOREIGN KEY (`course_type_id`) REFERENCES `per_course_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_enrollment_subjects`
--
ALTER TABLE `per_enrollment_subjects`
  ADD CONSTRAINT `per_enrollment_subjects_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `per_enrollment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_enrollment_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `per_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_exam`
--
ALTER TABLE `per_exam`
  ADD CONSTRAINT `per_exam_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_exam_marks`
--
ALTER TABLE `per_exam_marks`
  ADD CONSTRAINT `per_exam_marks_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `per_exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_exam_marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `per_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_exam_marks_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `per_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_lectures`
--
ALTER TABLE `per_lectures`
  ADD CONSTRAINT `per_lectures_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_lectures_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `per_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_result`
--
ALTER TABLE `per_result`
  ADD CONSTRAINT `per_result_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `per_academic_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_result_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `per_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_result_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `per_subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_result_details`
--
ALTER TABLE `per_result_details`
  ADD CONSTRAINT `per_result_details_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `per_result` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_student`
--
ALTER TABLE `per_student`
  ADD CONSTRAINT `per_student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `per_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_student_ibfk_4` FOREIGN KEY (`current_course_id`) REFERENCES `per_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_student_ibfk_5` FOREIGN KEY (`current_course_type_id`) REFERENCES `per_course_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_subjects`
--
ALTER TABLE `per_subjects`
  ADD CONSTRAINT `per_subjects_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `per_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_subjects_ibfk_2` FOREIGN KEY (`course_type_id`) REFERENCES `per_course_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_teacher`
--
ALTER TABLE `per_teacher`
  ADD CONSTRAINT `fk_constraint_cascade` FOREIGN KEY (`user_id`) REFERENCES `per_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_timetable`
--
ALTER TABLE `per_timetable`
  ADD CONSTRAINT `per_timetable_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `per_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_timetable_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `per_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `per_user_roles`
--
ALTER TABLE `per_user_roles`
  ADD CONSTRAINT `per_user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `per_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `per_user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `per_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
