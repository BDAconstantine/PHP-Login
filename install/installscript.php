<?php
function installDb($i, $dbhost, $dbname, $dbuser, $dbpw, $tblprefix, $superadmin, $saemail, $said, $sapw, $settingsArr = '')
{
    $status = '';
    $failure = 0;
    try {
        switch ($i) {
            // Create Database
            case 0:
                try {
                    $conn = new PDO("mysql:host={$dbhost}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating database <span class='dbtable'>{$dbname}</span>";
                    $sqlcreate = "CREATE DATABASE IF NOT EXISTS {$dbname} DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;";
                    $c = $conn->exec($sqlcreate);

                    unset($c);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create database: ". $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 1:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "members</span> table";
                    $sqlmembers = "SET FOREIGN_KEY_CHECKS= 0; CREATE TABLE IF NOT EXISTS {$tblprefix}members (
                                      `id` char(23) NOT NULL,
                                      `username` varchar(65) NOT NULL DEFAULT '',
                                      `password` varchar(255) NOT NULL DEFAULT '',
                                      `email` varchar(65) NOT NULL DEFAULT '',
                                      `verified` tinyint(1) NOT NULL DEFAULT '0',
                                      `banned` tinyint(1) NOT NULL DEFAULT '0',
                                      `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`id`),
                                    UNIQUE KEY `username_UNIQUE` (`username`),
                                    UNIQUE KEY `id_UNIQUE` (`id`),
                                    UNIQUE KEY `email_UNIQUE` (`email`) )
                                  ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $m = $conn->exec($sqlmembers);

                    unset($m);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "members</span> table." . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 2:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "roles</span> table";
                    $sqlroles = "SET FOREIGN_KEY_CHECKS= 0; CREATE TABLE IF NOT EXISTS {$tblprefix}roles (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `name` varchar(45) NOT NULL,
                                    `description` varchar(255) DEFAULT NULL,
                                    `required` tinyint(1) NOT NULL DEFAULT '0',
                                    `default_role` tinyint(1) DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  UNIQUE KEY `name_UNIQUE` (`name`),
                                  UNIQUE KEY `default_role_UNIQUE` (`default_role`) )
                                ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $m = $conn->exec($sqlroles);

                    unset($m);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "roles</span> table." . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 3:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "deleted_members</span> table";
                    $sqldeleted_members = "SET FOREIGN_KEY_CHECKS = 0;
                                            CREATE TABLE IF NOT EXISTS {$tblprefix}deleted_members (
                                                `id` char(23) NOT NULL,
                                                `username` varchar(65) NOT NULL DEFAULT '',
                                                `password` varchar(65) NOT NULL DEFAULT '',
                                                `email` varchar(65) NOT NULL,
                                                `verified` tinyint(1) NOT NULL DEFAULT '0',
                                                `banned` tinyint(1) NOT NULL DEFAULT '0',
                                                `mod_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                              PRIMARY KEY (`id`), UNIQUE KEY `id_UNIQUE` (`id`) )
                                            ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";

                    $dm = $conn->exec($sqldeleted_members);

                    unset($dm);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "deleted_members</span> table. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 4:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "login_attempts</span> table";
                    $sqllogin_attempts = "SET FOREIGN_KEY_CHECKS = 0;
                                          CREATE TABLE IF NOT EXISTS {$tblprefix}login_attempts (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
                                              `username` varchar(65) DEFAULT NULL,
                                              `ip` varchar(20) NOT NULL,
                                              `attempts` int(11) NOT NULL,
                                              `lastlogin` datetime NOT NULL,
                                            PRIMARY KEY (`ID`) )
                                          ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
                                          SET FOREIGN_KEY_CHECKS = 1;";
                    $la = $conn->exec($sqllogin_attempts);

                    unset($la);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "login_attempts</span> table." . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 5:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "member_info</span> table";
                    $sqlmember_info = "SET FOREIGN_KEY_CHECKS = 0;
                                      CREATE TABLE IF NOT EXISTS {$tblprefix}member_info (
                                          `userid` char(23) NOT NULL,
                                          `firstname` varchar(45) NOT NULL,
                                          `lastname` varchar(55) DEFAULT NULL,
                                          `phone` varchar(20) DEFAULT NULL,
                                          `address1` varchar(45) DEFAULT NULL,
                                          `address2` varchar(45) DEFAULT NULL,
                                          `city` varchar(45) DEFAULT NULL,
                                          `state` varchar(30) DEFAULT NULL,
                                          `country` varchar(45) DEFAULT NULL,
                                          `bio` varchar(20000) DEFAULT NULL,
                                          `userimage` varchar(255) DEFAULT NULL,
                                        UNIQUE KEY `userid_UNIQUE` (`userid`),
                                        KEY `fk_userid_idx` (`userid`),
                                        CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION )
                                      ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $mi = $conn->exec($sqlmember_info);

                    unset($mi);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "member_info</span> table. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 6:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "cookies</span> table";
                    $sqlcookies = "SET FOREIGN_KEY_CHECKS = 0;
                                  CREATE TABLE IF NOT EXISTS {$tblprefix}cookies (
                                      `cookieid` char(23) NOT NULL,
                                      `userid` char(23) NOT NULL,
                                      `tokenid` char(25) NOT NULL,
                                      `expired` tinyint(1) NOT NULL DEFAULT '0',
                                      `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`userid`),
                                    CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE )
                                  ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $cook = $conn->exec($sqlcookies);

                    unset($cook);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "cookies</span> table.". $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 7:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "tokens</span> table";
                    $sqltokens = "SET FOREIGN_KEY_CHECKS = 0;
                                  CREATE TABLE IF NOT EXISTS {$tblprefix}tokens (
                                      `tokenid` char(25) NOT NULL,
                                      `userid` char(23) NOT NULL,
                                      `expired` tinyint(1) NOT NULL DEFAULT '0',
                                      `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    PRIMARY KEY (`tokenid`),
                                    UNIQUE KEY `tokenid_UNIQUE` (`tokenid`),
                                    UNIQUE KEY `userid_UNIQUE` (`userid`),
                                    CONSTRAINT `userid_t` FOREIGN KEY (`userid`) REFERENCES `{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE )
                                  ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $tkn = $conn->exec($sqltokens);
                    unset($tkn);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "tokens</span> table. ". $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 8:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating <span class='dbtable'>" . $tblprefix . "app_config</span> table";
                    $sqlconfig = "SET FOREIGN_KEY_CHECKS = 0;
                                  CREATE TABLE IF NOT EXISTS {$tblprefix}app_config (
                                      `setting` char(26) NOT NULL,
                                      `value` varchar(12000) NOT NULL,
                                      `sortorder` int(5),
                                      `category` varchar(25) NOT NULL,
                                      `type` varchar(15) NOT NULL,
                                      `description` varchar(140),
                                      `required` tinyint(1) NOT NULL DEFAULT '0',
                                    PRIMARY KEY (`setting`),
                                    UNIQUE KEY `setting_UNIQUE` (`setting`) )
                                  ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                    $cnf = $conn->exec($sqlconfig);

                    unset($cnf);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "app_config</span> table. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 9:
              try {
                  $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $status = "Creating <span class='dbtable'>" . $tblprefix . "roles</span> table";
                  $sqlmemberroles = "SET FOREIGN_KEY_CHECKS= 0; CREATE TABLE IF NOT EXISTS {$tblprefix}member_roles (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `member_id` char(23) NOT NULL,
                                        `role_id` int(11) NOT NULL,
                                      UNIQUE KEY `uq_unique_idx` (`member_id`,`role_id`),
                                      PRIMARY KEY (`id`),
                                      KEY `member_id_idx` (`member_id`),
                                      KEY `fk_role_id_idx` (`role_id`),
                                      CONSTRAINT `fk_member_id` FOREIGN KEY (`member_id`) REFERENCES `{$tblprefix}members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                                      CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `{$tblprefix}roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE )
                                    ENGINE=InnoDB DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";
                  $m = $conn->exec($sqlmemberroles);

                  unset($m);
                  break 1;
                  sleep(0.5);
              } catch (Exception $e) {
                  throw new Exception("Failed to create <span class='dbtable'>" . $tblprefix . "member_roles</span> table." . $e->getMessage());
                  $failure = 1;
                  break 1;
              }

            case 10:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating triggers";
                    $sqldrtrig = "DROP TRIGGER IF EXISTS move_to_deleted_members;
                                  DROP TRIGGER IF EXISTS add_admin;
                                  DROP TRIGGER IF EXISTS assign_default_role;
                                  DROP TRIGGER IF EXISTS add_admin_beforeUpdate;
                                  DROP TRIGGER IF EXISTS stop_delete_required;";
                    $drtr = $conn->exec($sqldrtrig);
                    //$drtr = "blah";
                    unset($drtr);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 11:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating basic roles";
                    $sqlinsertroles = "SET FOREIGN_KEY_CHECKS = 0;
                                          REPLACE INTO {$tblprefix}roles
                                          (`id`, `name`, `description`, `required`, `default_role`)
                                          VALUES (1, 'Superadmin', 'Master administrator of site', 1, NULL),
                                          (2, 'Admin', 'Site administrator', 1, NULL),
                                          (3, 'Standard User', 'Default site role for standard users', 1, 1);
                                      SET FOREIGN_KEY_CHECKS = 1;";
                    $dmt = $conn->exec($sqlinsertroles);
                    unset($dmt);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 12:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating scheduled events";
                    $sqlcleanupOldDeletedEvent = "SET GLOBAL event_scheduler = ON;
                                                  DELIMITER $
                                                  CREATE EVENT IF NOT EXISTS cleanupOldDeleted
                                                  	ON SCHEDULE EVERY 1 DAY
                                                  DO
                                                  BEGIN
                                                    DELETE FROM {$tblprefix}deleted_members
                                                    WHERE mod_timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY);
                                                  END $
                                                  DELIMITER ;";
                    $code = $conn->exec($sqlcleanupOldDeletedEvent);

                    $sqlUnbanUsersSql = "DELIMITER $
                                          CREATE EVENT `unbanUsers`
                                              ON SCHEDULE EVERY 15 MINUTE
                                          DO
                                          BEGIN
                                              DELETE FROM `vw_banned_users` where hours_remaining < 0;
                                              UPDATE {$tblprefix}members m SET m.banned = 0 where m.banned = 1 AND m.id not in (select v.user_id from `vw_banned_users` v);
                                          END $
                                          DELIMITER ;";

                    $code = $conn->exec($sqlUnbanUsersSql);

                    unset($code);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create <span class='dbtable'>cleanupOldDeleted<span> event. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 13:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating superadmin user";
                    $sqlAddSuperAdmin = $conn->prepare("SET FOREIGN_KEY_CHECKS= 0;
                                                          INSERT INTO {$tblprefix}members
                                                          (id, username, password, email, verified)
                                                          VALUES (:said, :superadmin, :sapw, :saemail, 1);
                                                        SET FOREIGN_KEY_CHECKS= 1;");
                    $sqlAddSuperAdmin->bindParam(':said', $said);
                    $sqlAddSuperAdmin->bindParam(':superadmin', $superadmin);
                    $sqlAddSuperAdmin->bindParam(':sapw', $sapw);
                    $sqlAddSuperAdmin->bindParam(':saemail', $saemail);
                    $asa = $sqlAddSuperAdmin->execute();

                    unset($asa);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create superadmin. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 14:
                try {
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating triggers";
                    $sqlTriggerDeletedMembers = "CREATE TRIGGER move_to_deleted_members AFTER
                                                  DELETE ON {$tblprefix}members FOR EACH ROW
                                                  BEGIN
                                                    DELETE FROM {$tblprefix}deleted_members
                                                      WHERE {$tblprefix}deleted_members.id = OLD.id;
                                                    INSERT INTO {$tblprefix}deleted_members (id, username, password, email, verified)
                                                    VALUES ( OLD.id, OLD.username, OLD.password, OLD.email, OLD.verified );
                                                  END;";
                    $dmt = $conn->exec($sqlTriggerDeletedMembers);

                    $sqlTriggersDefaultRole = 'CREATE TRIGGER assign_default_role AFTER
                                                INSERT ON '.$tblprefix.'members FOR EACH ROW
                                                BEGIN
                                                  SET @default_role = (SELECT id FROM '.$tblprefix.'roles WHERE default_role = 1 LIMIT 1);
                                                  INSERT INTO '.$tblprefix.'member_roles (member_id, role_id) VALUES (NEW.id, @default_role);
                                                END;';

                    $dr = $conn->exec($sqlTriggersDefaultRole);
                    unset($dmt, $dr);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                    $failure = 1;
                    break 1;
                }

            case 15:
              try {
                  $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $status = "Creating setting superadmin role";
                  $sqlAddSuperAdminRole = $conn->prepare("SET FOREIGN_KEY_CHECKS = 0;
                                                              INSERT INTO {$tblprefix}member_roles
                                                              (`id`, `member_id`, `role_id`)
                                                              VALUES (1, :said, 1);
                                                          SET FOREIGN_KEY_CHECKS = 1;");

                  $sqlAddSuperAdminRole->bindParam(':said', $said);
                  $asar = $sqlAddSuperAdminRole->execute();

                  unset($asar);
                  break 1;
                  sleep(0.5);
              } catch (Exception $e) {
                  throw new Exception("Failed to set superadmin role " . $e->getMessage());
                  $failure = 1;
                  break 1;
              }

            case 16:
                try {
                    //INSERT APP SETTINGS
                    function func_enabled($function)
                    {
                        $disabled = explode(',', ini_get('disable_functions'));
                        return !in_array($function, $disabled);
                    }
                    if (func_enabled('shell_exec')) {
                        if (substr(PHP_OS, 0, 3) != 'WIN') {
                            if (!shell_exec('which curl')) {
                                $curl_enabled = 'false';
                            } else {
                                $curl_enabled = 'true';
                            }
                        } else {
                            if (!shell_exec('where curl')) {
                                $curl_enabled = 'false';
                            } else {
                                $curl_enabled = 'true';
                            }
                        }
                    } else {
                        $curl_enabled = 'false';
                    }
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $status = "Creating application settings";

                    $sqlappsettings = "REPLACE INTO {$tblprefix}app_config (`sortorder`,`setting`,`category`,`value`,`type`,`description`,`required`) VALUES
                                      (27,'active_email','Messages','Your new account is now active! Click this link to log in!','text','Email message when account is verified',1),
                                      (26,'active_msg','Messages','Your account has been verified!','text','Display message when account is verified',1),
                                      (21,'admin_verify','Security','false','boolean','Require admin verification',1),
                                      (6,'avatar_dir','Website','/user/avatars','text','Directory where user avatars should be stored inside of base site directory. Do not include base_dir path.',1),
                                      (2,'base_dir','Website','".addslashes($settingsArr['base_dir'])."','hidden','Base directory of website in filesystem. \"C:\\...\" in windows, \"/...\" in unix systems',1),
                                      (3,'base_url','Website','".$settingsArr['base_url']."','url','Base URL of website. Example: \"http://sitename.com\"',1),
                                      (19,'cookie_expire_seconds','Security','2592000','number','Cookie expiration (in seconds)',1),
                                      (13,'from_email','Mailer','','email','From email address. Should typically be the same as \"mail_user\" email.',1),
                                      (14,'from_name','Mailer','Test Website','text','Name that shows up in \"from\" field of emails',1),
                                      (4,'htmlhead','Website','<!DOCTYPE html><html lang=\'en\'><head><meta charset=\'utf-8\'><meta name=\'viewport\' content-width=\'device-width\', initial-scale=\'1\', shrink-to-fit=\'no\'>','textarea','Main HTML header of website (without login-specific includes and script tags). Do not close <html> tag! Will break application functionality',1),
                                      (20,'jwt_secret','Security','php-login','text','Secret for JWT for tokens (Can be anything)',1),
                                      (18,'login_timeout','Security','300','number','Cooloff time for too many failed logins (in seconds)',1),
                                      (12,'mail_port','Mailer','587','number','Mail port. Common settings are 465 for ssl, 587 for tls, 25 for other',1),
                                      (10,'mail_pw','Mailer','','password','Email password to authenticate mailer',1),
                                      (11,'mail_security','Mailer','tls','text','Mail security type. Possible values are \"ssl\", \"tls\" or leave blank',1),
                                      (8,'mail_server','Mailer','','text','Mail server address. Example: \"smtp.email.com\"',1),
                                      (7,'mail_server_type','Mailer','smtp','text','Type of email server. SMTP is most typical. Other server types untested.',1),
                                      (9,'mail_user','Mailer','','email','Email user',1), (5,'mainlogo','Website','','url','URL of main site logo. Example \"http://sitename.com/logo.jpg\"',1),
                                      (17,'max_attempts','Security','5','number','Maximum login attempts',1),
                                      (16,'password_min_length','Security','6','number','Minimum password length if \"password_policy_enforce\" is set to true',1),
                                      (15,'password_policy_enforce','Security','true','boolean','Require a mixture of upper and lowercase letters and minimum password length (set by \"password_min_length\")',1),
                                      (28,'reset_email','Messages','Click the link below to reset your password','text','Email message when user wants to reset their password',1),
                                      (23,'signup_requires_admin','Messages','Thank you for signing up! Before you can login, your account needs to be activated by an administrator.','text','Message displayed when user signs up, but requires admin approval',1),
                                      (22,'signup_thanks','Messages','Thank you for signing up! You will receive an email shortly confirming the verification of your account.','text','Message displayed wehn user signs up and can verify themselves via email',1),
                                      (1,'site_name','Website','".$settingsArr['site_name']."','text','Website name',1),
                                      (24,'verify_email_admin','Messages','Thank you for signing up! Your account will be reviewed by an admin shortly','text','Email message when account requires admin verification',1),
                                      (25,'verify_email_noadmin','Messages','Click this link to verify your new account!','text','Email message when user can verify themselves',1),
                                      (29, 'curl_enabled','Website','".$curl_enabled."', 'boolean','Enable curl for various processes such as background email sending', 1),
                                      (30, 'email_working','Mailer','false', 'hidden','Indicates if email settings are correct and can connect to a mail server', 1),
                                      (31, 'admin_email','Website','".$saemail."', 'text','Site administrator email address', 1),
                                      (32, 'timezone', 'Website', '".date_default_timezone_get()."', 'timezone', 'Server time zone', 1),
                                      (33, 'token_validity','Security','24','number','Token validity in Hours (default 24 hours)','1');";

                    $dm = $conn->exec($sqlappsettings);

                    unset($dm);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create application settings. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }
            case 17:
                try {
                    //Create MailLog Table
                    $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $status = "Creating mail log";
                    $values = "";

                    $sqlappsettings = "SET FOREIGN_KEY_CHECKS = 0; CREATE TABLE IF NOT EXISTS {$tblprefix}mail_log (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `type` varchar(45) NOT NULL DEFAULT 'generic',
                                          `status` varchar(45) DEFAULT NULL,
                                          `recipient` varchar(5000) DEFAULT NULL,
                                          `response` mediumtext NOT NULL,
                                          `isread` tinyint(1) NOT NULL DEFAULT b'0',
                                          `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                          PRIMARY KEY (`id`)
                                      ) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8; SET FOREIGN_KEY_CHECKS = 1;";

                    $dm = $conn->exec($sqlappsettings);

                    unset($dm);
                    break 1;
                    sleep(0.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to create mail log. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

              case 18:
                  try {
                      //Create MailLog Table
                      $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      $status = "Creating banned user table";

                      $sqljail = "CREATE TABLE {$tblprefix}member_jail (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
                                            `user_id` char(23) NOT NULL,
                                            `banned_hours` FLOAT NOT NULL DEFAULT '24',
                                            `reason` varchar(2000) DEFAULT NULL,
                                            `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                          PRIMARY KEY (`id`),
                                          UNIQUE KEY `user_id_UNIQUE` (`user_id`),
                                          KEY `fk_userid_idx` (`user_id`),
                                          CONSTRAINT `fk_userid_jail` FOREIGN KEY (`user_id`) REFERENCES {$tblprefix}members (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                      $dm = $conn->exec($sqljail);

                      unset($dm);
                      break 1;
                      sleep(0.5);
                  } catch (Exception $e) {
                      throw new Exception("Failed to create banned users table. " . $e->getMessage());
                      $failure = 1;
                      break 1;
                  }

              case 19:
                  try {
                      //Create MailLog Table
                      $conn = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpw);
                      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      $status = "Creating banned users view";

                      $sqljail = "CREATE
                                  VIEW `vw_banned_users` AS
                                      SELECT
                                          {$tblprefix}member_jail.user_id AS `user_id`,
                                          {$tblprefix}member_jail.timestamp AS `banned_timestamp`,
                                          {$tblprefix}member_jail.banned_hours AS `banned_hours`,
                                          ( {$tblprefix}member_jail.banned_hours - (TIME_TO_SEC(TIMEDIFF(NOW(), {$tblprefix}member_jail.timestamp)) / 3600)) AS `hours_remaining`
                                      FROM
                                          {$tblprefix}member_jail;";

                      $dm = $conn->exec($sqljail);

                      unset($dm);
                      break 1;
                      sleep(0.5);
                  } catch (Exception $e) {
                      throw new Exception("Failed to create banned users view. " . $e->getMessage());
                      $failure = 1;
                      break 1;
                  }

            case 20:
                require "confgen.php";
                break 1;

            case 21:
                try {
                    //Change file permissions
                    $status = "Changing file permissions";

                    chmod($settingsArr['base_dir'] . '/login/dbconf.php', 0660);

                    break 1;
                    sleep(1.5);
                } catch (Exception $e) {
                    throw new Exception("Failed to change file permissions. " . $e->getMessage());
                    $failure = 1;
                    break 1;
                }

            default:
                $i++;
                break 1;
        }
    } catch (Exception $e) {
        $status = "An error occurred: " . $e->getMessage();
        $failure = 1;
    }
    $returnArray = array("status" => $status, "failure" => $failure);
    return $returnArray;
}
