-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2026 at 07:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `access_form`
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
(1, 1, 'Normal', 0, 0, 1, 0),
(2, 2, 'Normal', 0, 0, 1, 0),
(7, 7, 'Extra Larg', 1, 1, 1, 0),
(8, 8, 'Normal', 0, 0, 1, 1),
(9, 9, 'Normal', 0, 1, 1, 0);

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

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('Text','Multiple Choice','Rating','Boolean') NOT NULL,
  `survey_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `question_text`, `question_type`, `survey_id`) VALUES
(1, 'How satisfied are you with our service?[Alt: Satisfaction level]', 'Text', 1),
(4, 'asdfbdzfvbzdfgv[Alt: Satisfaction level]', 'Multiple Choice', 4),
(13, 'The Course objectives available at VULMS are clear and specific.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(14, 'The Course workload is manageable.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(15, 'The Course is well organized (e.g. scheduling of graded activities, notification of changes in scheduled activities etc.)[Alt: Satisfaction level]', 'Multiple Choice', 8),
(16, 'The access to Course contents, assignments, quizzes etc. on VULMS is available without any interruption and/or delay.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(17, 'The Course integrates theoretical concepts with real-world applications.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(18, 'The Course contents are updated and modern.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(19, 'I watch all lectures of this Course.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(20, 'I participated actively in the Course through emails, MDBs, Skype/TeamViewer sessions.', 'Multiple Choice', 8),
(21, 'I think I have made progress in this Course.', 'Multiple Choice', 8),
(22, 'I read handouts/textbook in advance before watching the video lecture.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(23, 'The teaching methodology in lectures stimulates my attention.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(24, 'The e-learning environment encourages my participation.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(25, 'The overall environment in the VULMS and video lectures is conducive to learning.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(26, 'Lectures can easily be watched at VU Campuses.', 'Multiple Choice', 8),
(27, 'Availability of video lectures in multiple modes increases my lecture watching frequency.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(28, 'The learning material like handouts, semester plan, recommended books etc. are relevant and available.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(29, 'The provision of learning resources in the e-library is adequate and appropriate.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(30, 'The provision of learning resources like FAQs, Glossary, important URLs etc. on the VULMS is adequate and appropriate (if relevant).', 'Multiple Choice', 8),
(31, 'The method of assessment in the Course is reasonable and fair.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(32, 'Feedback on assessment of various graded activities is timely and constructive.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(33, 'Consistency and uniformity are observed during assessment.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(34, 'The assessment activities accurately assess what students have learned in this Course.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(35, 'The graded activities like assignments, GDBs and exams cover the contents taught in the Course.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(36, 'In video lectures, the instructor demonstrates knowledge of the subject.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(37, 'In video lectures, the course is presented in a manner to inspire students to apply higher order critical thinking skills.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(38, 'The instructor gives assignments and GDBs to demonstrate application of concepts discussed in the Course.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(39, 'The reply of queries through email or MDB reflects instructor’s command on the subject.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(40, 'The replies of queries through email or MDBs are comprehensive to refine the concepts highlights instructor’s communication skills.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(41, 'The Instructor encourages participation through email & MDBs.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(42, 'Whenever contacted, the Instructor is available and regular throughout the Course.[Alt: Satisfaction level]', 'Multiple Choice', 8),
(43, 'The best features of the Course were:[Alt: Satisfaction level]', 'Text', 8),
(44, 'The Course could have been improved by: [Alt: Satisfaction level]', 'Text', 8),
(45, 'About Instructor:[Alt: Satisfaction level]', 'Text', 8);

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
(1, 'Customer Feedback', 'Category: Feedback\nPlease tell us about your experience.', 'Published', '2026-02-25 11:40:04', 2),
(4, 'Student Feedback', 'Category: Feedback\nSelect the following Mutliple choice question', 'Published', '2026-02-25 15:13:07', 2),
(8, 'Teacher Evalution', 'Category: Education\nPlease give us your views so that Course quality can be improved. You are encouraged to be frank and constructive in your comments', 'Published', '2026-02-27 12:20:16', 2);

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
  `is_disabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `role`, `is_disabled`) VALUES
(1, 'John', 'admin@test.com', '$2y$10$ftJ30bzjj5NPujdzBmQloO3sNM4zqCCGLpnahiIei/HG0U2MRxvCi', 'Admin', 0),
(2, 'william', 'creator@test.com', '$2y$10$jli8Fj4CHPCO6wGaOo9SruSXYUbTe.TZISDd52SZ.7YXl2wjzQaM2', 'Form Creator', 0),
(7, 'John Hilton', 'johnhilton@test.com', '$2y$10$T4kmpAI2D78zcuNOgPutW.y.Rq.pEJDQCHbm2one96s63F0QJpQBm', 'Respondent', 0),
(8, 'Tom', 'respondent@test.com', '$2y$10$3KFXjLhGzDY7C0iIXEUEmezIAWB6UULiEm2ii5u6fP1tW/ANNPKBG', 'Respondent', 0),
(9, 'Jimmy', 'jimmy@test.com', '$2y$10$ZqGRFKWxo9cl1xxz9BFgROdfQO/UKPOcyPZ0aCXJVntXGxlbH6KM6', 'Respondent', 0);

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
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `response`
--
ALTER TABLE `response`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
