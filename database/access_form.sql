-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: May 06, 2026 at 01:17 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41714280_db_accessform`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessibilitysettings`
--

CREATE TABLE `accessibilitysettings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `font_size` varchar(10) DEFAULT 'Normal',
  `high_contrast` tinyint(1) DEFAULT 0,
  `screen_reader` tinyint(1) DEFAULT 0,
  `keyboard_navigation` tinyint(1) DEFAULT 1,
  `color_blind` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accessibilitysettings`
--

INSERT INTO `accessibilitysettings` (`setting_id`, `user_id`, `font_size`, `high_contrast`, `screen_reader`, `keyboard_navigation`, `color_blind`) VALUES
(25, 31, 'Normal', 0, 0, 1, 1),
(26, 32, 'Normal', 0, 0, 1, 1),
(27, 33, 'Normal', 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answer_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answer_id`, `response_id`, `question_id`, `answer_text`) VALUES
(188, 22, 123, 'Strongly Disagree'),
(189, 22, 124, 'Strongly Disagree'),
(190, 22, 125, 'Strongly Disagree'),
(191, 22, 126, 'Strongly Disagree'),
(192, 22, 127, 'Strongly Disagree'),
(193, 22, 128, 'Strongly Disagree'),
(194, 22, 130, 'Strongly Disagree'),
(195, 22, 131, 'Strongly Disagree'),
(196, 22, 132, 'Strongly Disagree'),
(197, 22, 133, 'Strongly Disagree'),
(198, 22, 135, 'Strongly Disagree'),
(199, 22, 136, 'Disagree'),
(200, 22, 137, 'Disagree'),
(201, 22, 138, 'Uncertain'),
(202, 22, 139, 'Agree'),
(203, 22, 141, 'Strongly Agree'),
(204, 22, 142, 'Agree'),
(205, 22, 143, 'Uncertain'),
(206, 22, 145, 'Strongly Agree'),
(207, 22, 146, 'Uncertain'),
(208, 22, 147, 'Agree'),
(209, 22, 148, 'Strongly Agree'),
(210, 22, 149, 'Disagree'),
(211, 22, 151, 'Disagree'),
(212, 22, 152, 'Uncertain'),
(213, 22, 153, 'Strongly Agree'),
(214, 22, 154, 'Uncertain'),
(215, 22, 155, 'Agree'),
(216, 22, 156, 'Disagree'),
(217, 22, 157, 'Strongly Agree'),
(218, 22, 159, 'asdwef'),
(219, 22, 160, 'sdfga'),
(220, 22, 161, 'fadg');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('Text','Multiple Choice','Rating','Boolean','Section') NOT NULL,
  `survey_id` int(11) NOT NULL,
  `condition_question_id` int(11) DEFAULT NULL,
  `condition_answer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `question_text`, `question_type`, `survey_id`, `condition_question_id`, `condition_answer`) VALUES
(122, 'Course Content and Organization', 'Section', 17, NULL, NULL),
(123, 'The Course objectives available at VULMS are clear and specific.', 'Multiple Choice', 17, NULL, NULL),
(124, 'The Course workload is manageable.', 'Multiple Choice', 17, NULL, NULL),
(125, 'The Course is well organized (e.g. scheduling of graded activities, notification of changes in scheduled activities etc.)', 'Multiple Choice', 17, NULL, NULL),
(126, 'The access to Course contents, assignments, quizzes etc. on VULMS is available without any interruption and/or delay.', 'Multiple Choice', 17, NULL, NULL),
(127, 'The Course integrates theoretical concepts with real-world applications.', 'Multiple Choice', 17, NULL, NULL),
(128, 'The Course contents are updated and modern.', 'Multiple Choice', 17, NULL, NULL),
(129, 'Student Contribution', 'Section', 17, NULL, NULL),
(130, 'I watch all lectures of this Course.', 'Multiple Choice', 17, NULL, NULL),
(131, 'I participated actively in the Course through emails, MDBs, Skype/TeamViewer sessions.', 'Multiple Choice', 17, NULL, NULL),
(132, 'I think I have made progress in this Course.', 'Multiple Choice', 17, NULL, NULL),
(133, 'I read handouts/textbook in advance before watching the video lecture.', 'Multiple Choice', 17, NULL, NULL),
(134, 'Learning Environment and Teaching Methods', 'Section', 17, NULL, NULL),
(135, 'The teaching methodology in lectures stimulates my attention.', 'Multiple Choice', 17, NULL, NULL),
(136, 'The e-learning environment encourages my participation.', 'Multiple Choice', 17, NULL, NULL),
(137, 'The overall environment in the VULMS and video lectures is conducive to learning.', 'Multiple Choice', 17, NULL, NULL),
(138, 'Lectures can easily be watched at VU Campuses.', 'Multiple Choice', 17, NULL, NULL),
(139, 'Availability of video lectures in multiple modes increases my lecture watching frequency.', 'Multiple Choice', 17, NULL, NULL),
(140, 'Learning Resources', 'Section', 17, NULL, NULL),
(141, 'The learning material like handouts, semester plan, recommended books etc. are relevant and available.', 'Multiple Choice', 17, NULL, NULL),
(142, 'The provision of learning resources in the e-library is adequate and appropriate.', 'Multiple Choice', 17, NULL, NULL),
(143, 'The provision of learning resources like FAQs, Glossary, important URLs etc. on the VULMS is adequate and appropriate (if relevant).', 'Multiple Choice', 17, NULL, NULL),
(144, 'Assessment', 'Section', 17, NULL, NULL),
(145, 'The method of assessment in the Course is reasonable and fair.', 'Multiple Choice', 17, NULL, NULL),
(146, 'Feedback on assessment of various graded activities is timely and constructive.', 'Multiple Choice', 17, NULL, NULL),
(147, 'Consistency and uniformity are observed during assessment.', 'Multiple Choice', 17, NULL, NULL),
(148, 'The assessment activities accurately assess what students have learned in this Course.', 'Multiple Choice', 17, NULL, NULL),
(149, 'The graded activities like assignments, GDBs and exams cover the contents taught in the Course.', 'Multiple Choice', 17, NULL, NULL),
(150, 'Instructor', 'Section', 17, NULL, NULL),
(151, 'In video lectures, the instructor demonstrates knowledge of the subject.', 'Multiple Choice', 17, NULL, NULL),
(152, 'In video lectures, the course is presented in a manner to inspire students to apply higher order critical thinking skills.', 'Multiple Choice', 17, NULL, NULL),
(153, 'The instructor gives assignments and GDBs to demonstrate application of concepts discussed in the Course.', 'Multiple Choice', 17, NULL, NULL),
(154, 'The reply of queries through email or MDB reflects instructor’s command on the subject.', 'Multiple Choice', 17, NULL, NULL),
(155, 'The replies of queries through email or MDBs are comprehensive to refine the concepts highlights instructor’s communication skills.', 'Multiple Choice', 17, NULL, NULL),
(156, 'The Instructor encourages participation through email & MDBs.', 'Multiple Choice', 17, NULL, NULL),
(157, 'Whenever contacted, the Instructor is available and regular throughout the Course.', 'Multiple Choice', 17, NULL, NULL),
(158, 'Comments', 'Section', 17, NULL, NULL),
(159, 'The best features of the Course were:', 'Text', 17, NULL, NULL),
(160, 'The Course could have been improved by:', 'Text', 17, NULL, NULL),
(161, 'About Instructor:', 'Text', 17, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE `response` (
  `response_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `respondent_id` int(11) NOT NULL,
  `submit_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `response`
--

INSERT INTO `response` (`response_id`, `survey_id`, `respondent_id`, `submit_date`) VALUES
(22, 17, 34, '2026-05-04 04:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `survey`
--

CREATE TABLE `survey` (
  `survey_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Draft','Published','Closed') DEFAULT 'Draft',
  `created_date` datetime DEFAULT current_timestamp(),
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey`
--

INSERT INTO `survey` (`survey_id`, `title`, `description`, `status`, `created_date`, `creator_id`) VALUES
(16, 'PTI survey ', 'Category: Feedback\nParty popularity ', 'Draft', '2026-04-25 04:37:43', 32),
(17, ' Teacher Evaluation', 'Category: Feedback\nPlease give us your views so that Course quality can be improved. You are encouraged to be frank and constructive in your comments', 'Published', '2026-05-04 03:50:35', 33),
(18, 'PTI Survey', 'Category: Feedback\nplease fill this survey', 'Draft', '2026-05-04 04:17:38', 33);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Form Creator','Respondent') NOT NULL,
  `is_disabled` tinyint(1) DEFAULT 0,
  `disability_profile` varchar(50) DEFAULT 'none',
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `role`, `is_disabled`, `disability_profile`, `reset_token`, `token_expire`) VALUES
(31, 'Muhammad Arqam', 'kingzmuzik8@gmail.com', '$2y$10$.NdmAh.2SWzf4ihdim0oX.B2MTm0ieliQWvY26hXN52YJE7XdamKK', 'Admin', 0, 'none', 'ecb8811d74f233fdc473c5a0df363a1550d49a8eb1df7447d25f2d0a95f9a532', '2026-05-05 00:14:12'),
(32, 'Umaar Ahmad', 'umaarahmad5656@gmail.com', '$2y$10$f5j2ufJLGuw79uVOFOjL7uFdV7R61rkKrFDl0kmjpgpnY3TK7cUue', 'Form Creator', 0, 'colorblind', NULL, NULL),
(33, 'creator', 'creator@test.com', '$2y$10$Mf40bF8pb2h0AClpEkI39.tWUtzujfVXaEb5Kl6zGSOuRaYW7bCIS', 'Form Creator', 0, 'none', NULL, NULL),
(34, 'Muhammad Arqam', 'm.arqambunkers88@gmail.com', 'guest_no_pass', 'Respondent', 0, 'none', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessibilitysettings`
--
ALTER TABLE `accessibilitysettings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `response`
--
ALTER TABLE `response`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `survey_id` (`survey_id`),
  ADD KEY `respondent_id` (`respondent_id`);

--
-- Indexes for table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`survey_id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accessibilitysettings`
--
ALTER TABLE `accessibilitysettings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `response`
--
ALTER TABLE `response`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accessibilitysettings`
--
ALTER TABLE `accessibilitysettings`
  ADD CONSTRAINT `accessibilitysettings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `response` (`response_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`survey_id`) ON DELETE CASCADE;

--
-- Constraints for table `response`
--
ALTER TABLE `response`
  ADD CONSTRAINT `response_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`survey_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `response_ibfk_2` FOREIGN KEY (`respondent_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `survey`
--
ALTER TABLE `survey`
  ADD CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
