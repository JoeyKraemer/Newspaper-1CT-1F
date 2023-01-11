-- Test rows for all tables
-- Add rows to TypeOfStaff
INSERT INTO `TypesOfStaff`(
   type_of_staff_id,
   type_of_staff_description
)
VALUES(
   NOT NULL,
   "Employee"
);

INSERT INTO `TypesOfStaff`(
   type_of_staff_id,
   type_of_staff_description
)
VALUES(
   NOT NULL,
   "Freelancer"
);

INSERT INTO `TypesOfStaff`(
   type_of_staff_id,
   type_of_staff_description
)
VALUES(
   NOT NULL,
   "Management"
);
-- Add rows to Roles
INSERT INTO `Roles`(
   role_id,
   role_name,
   role_description
)VALUES
(
   NOT NULL,
   "Editor",
   "Person that writes articles based on content they get from photographer"
);

INSERT INTO `Roles`(
   role_id,
   role_name,
   role_description
)VALUES
(
   NOT NULL,
   "Photographer",
   "Person that takes photos on events"
);

INSERT INTO `Roles`(
   role_id,
   role_name,
   role_description
)
VALUES(
   NOT NULL,
   "IT-Specialist",
   "Person that takes care of the system and has Admin priviliges"
);

INSERT INTO `Roles`(
   role_id,
   role_name,
   role_description
)
VALUES(
   NOT NULL,
   "Journalist",
   "Person that will visit events"
);
INSERT INTO `Roles`(
   role_id,
   role_name,
   role_description
)
VALUE(
   NOT NULL,
   "Head of Editor",
   "Head editor department, he/she takes care of all articles"
);

-- add events
INSERT INTO `Event` (
   event_id,
   event_name,
   event_description,
   location_street,
   location_postal_code,
   location_city,
   event_time,
   event_date,
   inactive
)
VALUES(
   NOT NULL,
   "Sachen die Sachen machen",
   "SACHEN DIE SACHEN MACHEN NUR IN CAPS",
   "SACHEN STREET",
   "123456",
   "Sachen Stadt",
   '13:37',
   '2022-01-01',
   0
);

INSERT INTO `Event`(
   event_id,
   event_name,
   event_description,
   location_street,
   location_postal_code,
   location_city,
   event_time,
   event_date,
   inactive
)
VALUES(
   NOT NULL,
   "Machen Sachen Wachen",
   "SACHEN DIE SACHEN MACHEN NUR IN CAPS",
   "SACHEN STREET",
   "123456",
   "Sachen Stadt",
   '13:31',
   '2022-06-12',
   0
);
-- add Users
INSERT INTO `User`(
   user_id,
   user_password,
   password_change_date,
   first_name,
   last_name,
   email_address,
   type_of_staff,
   user_role,
   inactive
)
VALUES(
   NOT NULL,
   123456,
   NOT NULL,
   "Thomas",
   "The Lokomotiv",
   "Thomas@Lokomotiv.nl",
   1,
   2,
   0
);
INSERT INTO `User`(
   user_id,
   user_password,
   password_change_date,
   first_name,
   last_name,
   email_address,
   type_of_staff,
   user_role,
   inactive
)
VALUES(
   NOT NULL,
   123456,
   NOT NULL,
   "Not Thomas",
   "The Lokomotiv",
   "NotThomas@Lokomotiv.nl",
   2,
   3,
   0
);
INSERT INTO `User`(
   user_id,
   user_password,
   password_change_date,
   first_name,
   last_name,
   email_address,
   type_of_staff,
   user_role,
   inactive
)
VALUES(
   NOT NULL,
   123456,
   NOT NULL,
   "OtherThomas",
   "DE Lokomotiv",
   "OtherThomas@Lokomotiv.nl",
   2,
   1,
   0
);
-- add Event_details
INSERT INTO `Event_Details`(
   event_details_id,
   event_id,
   user_id,
   checkin_date
)
VALUES(
   NOT NULL,
   1,
   2,
   NOT NULL
);
INSERT INTO `Event_Details`(
   event_details_id,
   event_id,
   user_id,
   checkin_date
)
VALUES(
   NOT NULL,
   1,
   1,
   NOT NULL
);

INSERT INTO `Event_Details`(
   event_details_id,
   event_id,
   user_id,
   checkin_date
)
VALUES(
   NOT NULL,
   2,
   3,
   NOT NULL
);

