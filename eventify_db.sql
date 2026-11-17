-- phpMyAdmin SQL Dump
-- Eventify Database
-- SEN4001 Web Design & Databases
-- Cardiff Metropolitan University

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Create and select the database
CREATE DATABASE IF NOT EXISTS `cmoeventmanagement` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cmoeventmanagement`;

-- --------------------------------------------------------
-- Table structure for table `event_details`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `event_details`;
CREATE TABLE `event_details` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_description` varchar(500) DEFAULT NULL,
  `event_category` varchar(50) DEFAULT NULL,
  `keywords` varchar(100) DEFAULT NULL,
  `video_url` varchar(100) DEFAULT NULL,
  `image_url` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample data for event_details
-- --------------------------------------------------------

INSERT INTO `event_details` (`event_id`, `user_id`, `event_name`, `event_description`, `event_category`, `keywords`, `video_url`, `image_url`, `start_date`, `end_date`) VALUES
(1, 1, 'Cardiff Summer Music Festival', 'A three-day outdoor music festival featuring local and international artists across multiple stages in Cardiff Bay. Food stalls, craft markets and family activities also available throughout the event.', 'Music', 'outdoor, music, festival, cardiff, summer', '', 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600', '2025-07-12', '2025-07-14'),
(2, 1, 'Community Art Exhibition', 'An exhibition showcasing artwork created by local artists from across Cardiff. The exhibition will feature paintings, sculptures, photography and digital art. Entry is free and open to all.', 'Arts', 'art, exhibition, free, community, gallery', '', '', '2025-08-02', '2025-08-10'),
(3, 1, 'Cardiff Food and Drink Fair', 'A weekend food fair celebrating the best food and drink producers from Wales and beyond. Visitors can sample and buy artisan products, attend cooking demonstrations, and enjoy live entertainment.', 'Food', 'food, drink, fair, artisan, wales', '', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600', '2025-09-06', '2025-09-07');

-- --------------------------------------------------------
-- Indexes for table `event_details`
-- --------------------------------------------------------

ALTER TABLE `event_details`
  ADD PRIMARY KEY (`event_id`);

-- --------------------------------------------------------
-- AUTO_INCREMENT for table `event_details`
-- --------------------------------------------------------

ALTER TABLE `event_details`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
