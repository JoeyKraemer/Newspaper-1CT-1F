-- Please import this statement to your database via PHPmyAdmin
DROP DATABASE IF EXISTS `gemorskos`;
-- Create database change value in ` ` to your needs 
CREATE DATABASE IF NOT EXISTS `gemorskos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
use gemorskos;

-- Table TypesOfStaff
CREATE TABLE `TypesOfStaff` (
   type_of_staff_id INT AUTO_INCREMENT NOT NULL,
   type_of_staff_description VARCHAR(25) UNIQUE NOT NULL,
   PRIMARY KEY (type_of_staff_id)
);

-- Table Roles
CREATE TABLE `Roles` (
   role_id INT AUTO_INCREMENT NOT NULL,
   role_name VARCHAR(25) UNIQUE NOT NULL,
   role_description TEXT NOT NULL,
   PRIMARY KEY(role_id)
);

-- Table Event
CREATE TABLE `Events` (
   event_id INT AUTO_INCREMENT NOT NULL,
   event_name VARCHAR(70) UNIQUE NOT NULL,
   event_description TEXT NOT NULL,
   location_street VARCHAR(50),
   location_postal_code VARCHAR(6),
   location_city VARCHAR(30),
   event_time TIME,
   event_date DATE,
   event_max_participant INT,
   active BOOLEAN,
   PRIMARY KEY(event_id)
);

-- Table User
CREATE TABLE `Users` (
   user_id INT AUTO_INCREMENT NOT NULL,
   user_password VARCHAR(60) NOT NULL,
   password_change_date TIMESTAMP,
   first_name VARCHAR(25) NOT NULL,
   last_name VARCHAR(25) NOT NULL,
   email_address VARCHAR(50) UNIQUE NOT NULL,
   type_of_staff INT NOT NULL,
   user_role INT NOT NULL,
   active BOOLEAN,
   photo TEXT,
   
   PRIMARY KEY(user_id),
   FOREIGN KEY(type_of_staff) REFERENCES TypesOfStaff(type_of_staff_id) ON UPDATE CASCADE ON DELETE NO ACTION,
   FOREIGN KEY(user_role) REFERENCES Roles(role_id) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- Table Event_Details
CREATE TABLE `Event_Details` (
   event_details_id INT AUTO_INCREMENT NOT NULL,
   event_id INT NOT NULL,
   user_id INT NOT NULL,
   checkin_date TIMESTAMP NOT NULL,
   PRIMARY KEY(event_details_id),
   FOREIGN KEY(event_id) REFERENCES Events(event_id) ON UPDATE CASCADE ON DELETE NO ACTION,
   FOREIGN KEY(user_id) REFERENCES Users(user_id) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- Table pdwReset
CREATE TABLE pwdReset (
	pwdResetId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	pwdResetEmail TEXT NOT NULL,
	pwdResetSelector TEXT NOT NULL,
	pwdResetToken LONGTEXT NOT NULL,
	pwdResetExpires TEXT NOT NULL
);