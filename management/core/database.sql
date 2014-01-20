CREATE TABLE IF NOT EXISTS CMS_logins (
  user_id bigint(20) NOT NULL default '0',
  last_login datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS CMS_modules (
  mod_id bigint(20) NOT NULL auto_increment,
  sub_id bigint(20) NOT NULL default '0',
  mod_name varchar(150) NOT NULL default '',
  mod_short_name varchar(50) NOT NULL default '',
  mod_url varchar(100) NOT NULL default '',
  mod_width int(3) NOT NULL default '100',
  is_active enum('Y','N') NOT NULL default 'N',
  sort_order int(1) NOT NULL default '1',
  is_locked enum('Y','N') NOT NULL default 'N',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (mod_id)
) ENGINE=MyISAM;

INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('User Admin','useradmin','Y','1','Y');
INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('Modules','modules','Y','2','Y');
INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('Pages','pages','Y','3','Y');
INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('DB Manager','dbmanager','Y','4','Y');
INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('Media Manager','mediamanager','Y','5','Y');
INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('Site Manager','sitemanager','Y','6','Y');
INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('Themes','themes','Y','7','Y');
INSERT INTO CMS_modules (`mod_name`,`mod_short_name`,`is_active`,`sort_order`,`is_locked`) VALUES ('Blog','blog','Y','8','Y');

CREATE TABLE IF NOT EXISTS CMS_setup (
  st_multiple enum('Y','N') NOT NULL default 'N',
  st_donations enum('Y','N') NOT NULL default 'N',
  st_user_events enum('Y','N') NOT NULL default 'N',
  st_teams enum('Y','N') NOT NULL default 'N',
  st_test_mode enum('Y','N') NOT NULL default 'N',
  st_merchant_no varchar(20) NOT NULL default '',
  st_password varchar(20) NOT NULL default '',
  st_css text NOT NULL,
  st_site_url varchar(150) NOT NULL default '',
  st_secure_url varchar(150) NOT NULL default '',
  st_prefix_donation varchar(10) NOT NULL default '',
  st_prefix_registration varchar(10) NOT NULL default ''
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS CMS_users (
  user_id bigint(20) NOT NULL auto_increment,
  usertype_id bigint(20) NOT NULL default '0',
  u_name varchar(150) NOT NULL default '',
  u_email varchar(150) NOT NULL default '',
  u_pass varchar(32) NOT NULL default '',
  u_results int(2) NOT NULL default '10',
  u_country varchar(10) NOT NULL default '',
  lang_id varchar(50) NOT NULL default '',
  u_editor varchar(20) NOT NULL default 'html',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  u_status int(11) NOT NULL default '1',
  PRIMARY KEY  (user_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS CMS_usertypes (
  usertype_id bigint(20) NOT NULL auto_increment,
  usertype varchar(100) NOT NULL default '',
  access varchar(150) NOT NULL default '',
  ut_status int(1) NOT NULL default '1',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (usertype_id)
) ENGINE=MyISAM;

INSERT INTO CMS_usertypes (`usertype_id`,`usertype`,`access`,`ut_status`,`date_created`) VALUES ('','Administrator','1,2,3,4,5,6,7,8','1',now());

CREATE TABLE IF NOT EXISTS blog_categories (
  cat_id bigint(20) NOT NULL auto_increment,
  sub_id bigint(20) NOT NULL default '0',
  cat_name varchar(150) NOT NULL default '',
  cat_dir varchar(150) NOT NULL default '',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (cat_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS blog_comments (
  comment_id bigint(20) NOT NULL auto_increment,
  post_id bigint(20) NOT NULL default '0',
  comment_author varchar(150) NOT NULL default '',
  comment_author_email varchar(150) NOT NULL default '',
  `comment` text NOT NULL,
  comment_status int(1) NOT NULL default '0',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (comment_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS blog_posts (
  post_id bigint(20) NOT NULL auto_increment,
  cat_id bigint(20) NOT NULL default '0',
  template_id bigint(20) NOT NULL default '0',
  created_by varchar(100) NOT NULL default '',
  updated_by varchar(100) NOT NULL default '',
  in_nav enum('Y','N') NOT NULL default 'N',
  is_home enum('Y','N') NOT NULL default 'N',
  on_home enum('Y','N') NOT NULL default 'Y',
  post_file_name varchar(255) NOT NULL default '',
  post_title varchar(255) NOT NULL default '',
  post_html text NOT NULL,
  post_link_title varchar(150) NOT NULL default '',
  post_description varchar(255) NOT NULL default '',
  post_keywords varchar(255) NOT NULL default '',
  post_expiry date NOT NULL default '0000-00-00',
  post_image varchar(150) NOT NULL default '',
  post_thumb varchar(150) NOT NULL default '',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  last_published datetime NOT NULL default '0000-00-00 00:00:00',
  sort_order bigint(20) NOT NULL default '1',
  post_status int(1) NOT NULL default '0',
  PRIMARY KEY  (post_id),
  FULLTEXT KEY post_name (post_title),
  FULLTEXT KEY post_html (post_html)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS blog_setup (
  admin_email varchar(150) NOT NULL default '',
  allow_anons enum('Y','N') NOT NULL default 'N',
  auto_approve_anons enum('Y','N') NOT NULL default 'N',
  auto_approve_returns enum('Y','N') NOT NULL default 'N',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS blog_templates (
  template_id bigint(20) NOT NULL auto_increment,
  template_name varchar(150) NOT NULL default '',
  template_html text NOT NULL,
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (template_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS themes (
  theme_id bigint(20) NOT NULL auto_increment,
  theme_name varchar(150) NOT NULL default '',
  theme_short_name varchar(150) NOT NULL default '',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (theme_id)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS ajax_chat_online;
CREATE TABLE ajax_chat_online (
   userID INT(11) NOT NULL,
   userName VARCHAR(64) NOT NULL,
   userRole INT(1) NOT NULL,
   channel INT(11) NOT NULL,
   dateTime DATETIME NOT NULL,
   ip VARBINARY(16) NOT NULL
);
 
DROP TABLE IF EXISTS ajax_chat_messages;
CREATE TABLE ajax_chat_messages (
   id INT(11) NOT NULL AUTO_INCREMENT,
   userID INT(11) NOT NULL,
   userName VARCHAR(64) NOT NULL,
   userRole INT(1) NOT NULL,
   channel INT(11) NOT NULL,
   dateTime DATETIME NOT NULL,
   ip VARBINARY(16) NOT NULL,
   text TEXT,
   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS ajax_chat_bans;
CREATE TABLE ajax_chat_bans (
   userID INT(11) NOT NULL,
   userName VARCHAR(64) NOT NULL,
   dateTime DATETIME NOT NULL,
   ip VARBINARY(16) NOT NULL
);
 
DROP TABLE IF EXISTS ajax_chat_invitations;
CREATE TABLE ajax_chat_invitations (
    userID INT(11) NOT NULL,
    channel INT(11) NOT NULL,
    dateTime DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS pages (
  page_id bigint(20) NOT NULL auto_increment,
  nav_id bigint(20) NOT NULL default '0',
  sub_id bigint(20) NOT NULL default '0',
  template_id bigint(20) NOT NULL default '0',
  created_by varchar(100) NOT NULL default '',
  updated_by varchar(100) NOT NULL default '',
  page_name varchar(150) NOT NULL default '',
  page_desc_60 varchar(60) NOT NULL default '',
  page_desc_100 varchar(100) NOT NULL default '',
  page_desc_255 text NOT NULL,
  page_link_title varchar(150) NOT NULL default '',
  page_link_external varchar(150) default NULL,
  page_link_target varchar(20) NOT NULL default '_self',
  page_link_pdf varchar(150) NOT NULL default '',
  page_link_title2 varchar(150) default NULL,
  page_link_external2 varchar(150) default NULL,
  page_link_pdf2 varchar(150) NOT NULL default '',
  page_file_name varchar(150) NOT NULL default '',
  short_name varchar(50) NOT NULL default '',
  show_email_friend enum('Y','N') NOT NULL default 'N',
  show_optin enum('Y','N') NOT NULL default 'N',
  in_nav enum('Y','N') NOT NULL default 'N',
  is_home enum('Y','N') NOT NULL default 'N',
  page_title varchar(255) NOT NULL default '',
  page_description varchar(255) NOT NULL default '',
  page_keywords varchar(255) NOT NULL default '',
  page_expiry date NOT NULL default '0000-00-00',
  page_heading varchar(255) NOT NULL default '',
  page_copy text NOT NULL,
  page_image varchar(150) NOT NULL default '',
  page_thumb varchar(150) NOT NULL default '',
  page_redirect enum('Y','N') NOT NULL default 'N',
  page_redirect_url varchar(255) NOT NULL default '',
  page_redirect_option varchar(50) NOT NULL default '',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  last_published datetime NOT NULL default '0000-00-00 00:00:00',
  page_html text NOT NULL,
  page_plaintext text NOT NULL,
  sort_order bigint(20) NOT NULL default '1',
  page_status int(1) NOT NULL default '0',
  is_locked enum('Y','N') NOT NULL default 'N',
  on_home enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (page_id),
  FULLTEXT KEY page_name (page_name),
  FULLTEXT KEY page_plaintext (page_plaintext),
  FULLTEXT KEY page_html (page_html)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS pages_forms (
  form_id bigint(20) NOT NULL auto_increment,
  form_name varchar(150) NOT NULL default '',
  form_code text NOT NULL,
  lang_id char(2) NOT NULL default 'au',
  form_response text NOT NULL,
  form_emailto varchar(255) NOT NULL default '',
  form_status int(1) NOT NULL default '1',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (form_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS pages_form_data (
  id bigint(20) NOT NULL auto_increment,
  session_id bigint(20) NOT NULL default '0',
  form_id bigint(20) NOT NULL default '1',
  field_name varchar(100) NOT NULL default '',
  field_value text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS pages_form_submissions (
  session_id bigint(20) NOT NULL auto_increment,
  form_id bigint(20) NOT NULL default '0',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  referer varchar(255) NOT NULL default '',
  PRIMARY KEY  (session_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS pages_lumps (
  lump_id bigint(20) NOT NULL auto_increment,
  page_id bigint(20) NOT NULL default '0',
  lump_heading varchar(150) NOT NULL default '',
  lump_copy text NOT NULL,
  lump_image varchar(150) NOT NULL default '',
  lump_image_class varchar(50) NOT NULL default '',
  lump_flash varchar(150) NOT NULL default '',
  lump_flash_width int(4) NOT NULL default '0',
  lump_flash_height int(4) NOT NULL default '0',
  lump_pdf varchar(150) NOT NULL default '',
  lump_link_text varchar(150) NOT NULL default '',
  lump_url varchar(150) NOT NULL default '',
  lump_form bigint(20) NOT NULL default '0',
  sort_order int(4) NOT NULL default '1',
  PRIMARY KEY  (lump_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS pages_navigation (
  nav_id bigint(20) NOT NULL auto_increment,
  sub_id bigint(20) NOT NULL default '0',
  nav_name varchar(150) NOT NULL default '',
  nav_dir varchar(150) NOT NULL default '',
  nav_username varchar(150) NOT NULL default '',
  nav_password varchar(150) NOT NULL default '',
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (nav_id)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS pages_templates (
  template_id bigint(20) NOT NULL auto_increment,
  template_name varchar(150) NOT NULL default '',
  template_html text NOT NULL,
  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
  date_created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (template_id)
) ENGINE=MyISAM;