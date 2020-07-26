-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 26, 2020 at 10:21 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `openemr_pt`
--

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_attachments`
--

CREATE TABLE `aa_mi_desk_attachments` (
  `id` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Attachment',
  `messageId` bigint(20) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `properties` json DEFAULT NULL,
  `revision` bigint(20) NOT NULL DEFAULT '1' COMMENT 'The current revision number, referring to files revision'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_files`
--

CREATE TABLE `aa_mi_desk_files` (
  `id` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL,
  `attachmentId` bigint(20) NOT NULL,
  `documentId` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `revision` bigint(20) NOT NULL DEFAULT '1',
  `properties` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_filters`
--

CREATE TABLE `aa_mi_desk_filters` (
  `id` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'folder',
  `name` varchar(512) NOT NULL,
  `faIcon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_jobs`
--

CREATE TABLE `aa_mi_desk_jobs` (
  `id` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `payload` json NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_messages`
--

CREATE TABLE `aa_mi_desk_messages` (
  `id` bigint(20) NOT NULL,
  `subject` varchar(512) NOT NULL,
  `body` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL,
  `userId` int(11) NOT NULL,
  `recipients` json DEFAULT NULL,
  `teams` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_message_meta`
--

CREATE TABLE `aa_mi_desk_message_meta` (
  `id` bigint(20) NOT NULL,
  `messageId` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `isRead` tinyint(1) NOT NULL DEFAULT '0',
  `isOpen` tinyint(1) NOT NULL DEFAULT '0',
  `properties` json DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_msgs_filters`
--

CREATE TABLE `aa_mi_desk_msgs_filters` (
  `id` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL,
  `userId` bigint(20) DEFAULT NULL,
  `messageId` bigint(20) NOT NULL,
  `filterId` bigint(20) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_msgs_teams`
--

CREATE TABLE `aa_mi_desk_msgs_teams` (
  `id` bigint(20) NOT NULL,
  `teamId` bigint(20) NOT NULL,
  `messageId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_msgs_users`
--

CREATE TABLE `aa_mi_desk_msgs_users` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `messageId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_replies`
--

CREATE TABLE `aa_mi_desk_replies` (
  `id` bigint(20) NOT NULL,
  `messageId` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `body` text NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_teams`
--

CREATE TABLE `aa_mi_desk_teams` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `filterId` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL,
  `updatedAt` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_mi_desk_users_teams`
--

CREATE TABLE `aa_mi_desk_users_teams` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `teamId` bigint(20) NOT NULL COMMENT 'this is a link to id on teams table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `aa_pt_notifications`
--

CREATE TABLE `aa_pt_notifications` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL,
  `account` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aa_mi_desk_attachments`
--
ALTER TABLE `aa_mi_desk_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_files`
--
ALTER TABLE `aa_mi_desk_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_filters`
--
ALTER TABLE `aa_mi_desk_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_jobs`
--
ALTER TABLE `aa_mi_desk_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_messages`
--
ALTER TABLE `aa_mi_desk_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_message_meta`
--
ALTER TABLE `aa_mi_desk_message_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_msgs_filters`
--
ALTER TABLE `aa_mi_desk_msgs_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_msgs_teams`
--
ALTER TABLE `aa_mi_desk_msgs_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_msgs_users`
--
ALTER TABLE `aa_mi_desk_msgs_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_replies`
--
ALTER TABLE `aa_mi_desk_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_teams`
--
ALTER TABLE `aa_mi_desk_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_mi_desk_users_teams`
--
ALTER TABLE `aa_mi_desk_users_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aa_pt_notifications`
--
ALTER TABLE `aa_pt_notifications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aa_mi_desk_attachments`
--
ALTER TABLE `aa_mi_desk_attachments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_files`
--
ALTER TABLE `aa_mi_desk_files`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_filters`
--
ALTER TABLE `aa_mi_desk_filters`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_jobs`
--
ALTER TABLE `aa_mi_desk_jobs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_messages`
--
ALTER TABLE `aa_mi_desk_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_message_meta`
--
ALTER TABLE `aa_mi_desk_message_meta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_msgs_filters`
--
ALTER TABLE `aa_mi_desk_msgs_filters`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_msgs_teams`
--
ALTER TABLE `aa_mi_desk_msgs_teams`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_msgs_users`
--
ALTER TABLE `aa_mi_desk_msgs_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_replies`
--
ALTER TABLE `aa_mi_desk_replies`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_teams`
--
ALTER TABLE `aa_mi_desk_teams`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_mi_desk_users_teams`
--
ALTER TABLE `aa_mi_desk_users_teams`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aa_pt_notifications`
--
ALTER TABLE `aa_pt_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;


INSERT INTO `aa_mi_desk_filters` (`id`, `createdAt`, `updatedAt`, `type`, `name`, `faIcon`) VALUES
(1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'folder', 'Inbox', 'fa-inbox'),
(2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'folder', 'Sent', 'fa-paper-plane'),
(3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'folder', 'Archive', 'fa-archive');
