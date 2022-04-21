-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2022 at 03:46 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2110database`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `username` varchar(36) NOT NULL,
  `body` mediumtext NOT NULL,
  `time_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_modified` timestamp NULL DEFAULT NULL,
  `votes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answer_id`, `question_id`, `username`, `body`, `time_posted`, `time_modified`, `votes`) VALUES
(35, 23, 'composition2', 'You\'ll get a million different answers - people will suggest what they\'ve used, and what works for different people is always different.\r\n\r\nUltimately, any \"intro to python\" type course will be fine.\r\n\r\nThe \"Zero to Mastery\" Udemy series looks good, but understand that you won\'t get anywhere near advanced within a month or two. It takes months/years to get to intermediate, and years beyond that to become an \"advanced\".\r\n\r\nWith that in mind, go into it without a sense of urgency and take your time to learn and understand concepts.', '2022-04-20 15:55:32', NULL, 1),
(36, 23, 'ant3', 'Code Academy. It will teach you enough to move on to other tutorials - even if you don’t go Pro.', '2022-04-20 16:06:04', '2022-04-20 16:20:51', -1),
(37, 24, 'ant3', '> Another method I saw people talking about was something about creating a C++ process as a server and making requests to it from the electron app. I don\'t really understand what that means and how you do it, if someone knows please ELI5.\r\n\r\nYou can do this by sending socket connections, or use a library like https://grpc.io/.\r\n\r\n> Apparently I need to use NAPI or something? Anyways tutorials go straight to the code and include a header called <napi.h>. Cool, but I have to somehow install or download this header from somewhere, right? VScode/CLion don\'t see it automatically. But for some reason nobody is explaining how to do that. Does anyone have any idea what I am supposed to do?\r\n\r\nYou should have a node API header file as part of your node install. Where did you install node? I just installed node 17.9.0, and I did not get a napi.h header file, I got node_api.h, which is found here on my install: node/17.9.0/include/node/node_api.h. To include it in a C++ project would need to add the include path to your build', '2022-04-20 16:11:01', NULL, 2),
(38, 29, 'oyster1', 'Find out how much you can store in 1 int and what happens if you try to store more.', '2022-04-20 17:04:59', '2022-04-20 17:12:23', 1),
(39, 23, 'chair5', 'There’s a guy on who posted a syllabus in learn programming and python subreddits that he created that helped a couple of his friends land a job. I can’t figure out how to link it here on mobile though. Also, automate the boring stuff has been a great jumping off point for me', '2022-04-20 20:35:04', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `answer_votes`
--

CREATE TABLE `answer_votes` (
  `answer_id` int(11) NOT NULL,
  `voter` int(11) NOT NULL,
  `vote` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answer_votes`
--

INSERT INTO `answer_votes` (`answer_id`, `voter`, `vote`) VALUES
(35, 17, 1),
(37, 15, 1),
(38, 15, 1),
(36, 18, -1),
(37, 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `username` varchar(36) NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` mediumtext NOT NULL,
  `time_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_modified` timestamp NULL DEFAULT NULL,
  `votes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `username`, `title`, `body`, `time_posted`, `time_modified`, `votes`) VALUES
(23, 'oyster1', 'Where to start learning Python?', 'I am a complete newbie to this field. I have zero programing experience. But I want to learn Python. Is Udemy\'s Python: zero to mastery course a good place to start. Or please recommend where and how to start learning Python.', '2022-04-20 15:53:12', NULL, 3),
(24, 'composition2', 'Calling C++ from nodejs', 'I\'m creating an electron app that is going to need to call some C++ code. The method that seems to be recommended the most is creating node addons in C++. Another method I saw people talking about was something about creating a C++ process as a server and making requests to it from the electron app. I don\'t really understand what that means and how you do it, if someone knows please ELI5.\r\n\r\nSo I decided to see how to create node addons. Apparently I need to use NAPI or something? Anyways tutorials go straight to the code and include a header called `<napi.h>`. Cool, but I have to somehow install or download this header from somewhere, right? VScode/CLion don\'t see it automatically. But for some reason nobody is explaining how to do that. Does anyone have any idea what I am supposed to do?', '2022-04-20 15:56:44', '2022-04-20 16:01:11', 2),
(29, 'ant3', 'Binary number to normal number converter acting weird.', 'So i made a really simple binary number to normal number converter and it is giving me -1 instead of 4294967295?\r\n\r\nHere is my code:\r\n```\r\npublic class main {\r\n  public static void main(String[] args) {\r\n    String binary = \"11111111111111111111111111111111\";\r\n    int result = 0;\r\n    int num = 1;\r\n    for(int i = binary.length()-1; i >= 0; i--) {\r\n      if(binary.charAt(i) != \'0\') {\r\n        result += num;\r\n      }\r\n      num *= 2;\r\n    }\r\n    System.out.println(result);\r\n  }\r\n}\r\n```', '2022-04-20 16:07:20', NULL, -1),
(30, 'oyster1', 'What is the best way to have graphics in a program?', 'I have been programming with java for about a year now, and I\'ve made a text based car dealership game. I want to add graphics to my next game, so what\'s the best way to do that?', '2022-04-20 17:05:38', NULL, 0),
(31, 'arc4', 'How to convert milliseconds to a timestamp like this?', '> April 19, 2022 at 10:52:02 AM UTC-4\r\nI\'m trying to use JS to turn date that\'s in milliseconds into the format of the photo above. I\'m using\r\n\r\n`const today = Date.now()`\r\n\r\nto get today\'s date in milliseconds. Thanks!', '2022-04-20 17:08:30', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `question_votes`
--

CREATE TABLE `question_votes` (
  `question_id` int(11) NOT NULL,
  `voter` int(11) NOT NULL,
  `vote` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_votes`
--

INSERT INTO `question_votes` (`question_id`, `voter`, `vote`) VALUES
(23, 16, 1),
(23, 17, 1),
(29, 16, -1),
(24, 17, 1),
(23, 18, 1),
(24, 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `registration_date`, `is_admin`) VALUES
(15, 'oyster1', 'oyster1@example.com', '$2y$10$lfOKfecEscVQkbfNDMkPmOsE/t90eqqs.RtiG9EiGeGgKlFZYZs5q', '2022-04-20 15:52:25', 0),
(16, 'composition2', 'composition2@example.com', '$2y$10$XsgzNN0i4JmYMV.dt8JnOe2IMP4x/n7Cdybe0MH4kw8nugR1wsore', '2022-04-20 15:55:13', 0),
(17, 'ant3', 'ant3@example.com', '$2y$10$V6yIE.rwYVY4VsD8Ly67Je/Zi65TUgCA7iICsz3JFe0/JE5KhHXNi', '2022-04-20 16:05:35', 0),
(18, 'arc4', 'arc4@example.com', '$2y$10$lXZn0kYSepq/a2uF06tSTe48OtVbe44e8cKH6dttIi.6R8bho4xlG', '2022-04-20 17:07:55', 0),
(19, 'chair5', 'chair5@example.com', '$2y$10$DIRyn0ASg64ifz2k3NsG9..vv/hIWYPAr3PeG2OZL3onPy3FGbBCu', '2022-04-20 17:11:33', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

GRANT SELECT, INSERT, DELETE, UPDATE 
ON `2110database`.* 
TO `2110`@localhost 
IDENTIFIED BY 'pass';
