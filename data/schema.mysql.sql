-- To create a new database, run MySQL client:
--   mysql -u root -p
-- Then in MySQL client command line, type the following (replace <password> with password string):
--   create database blog;
--   grant all privileges on blog.* to blog@localhost identified by '<password>';
--   quit
-- Then, in shell command line, type:
--   mysql -u root -p blog < schema.mysql.sql

set names 'utf8';

-- Category
CREATE TABLE `category` (     
  `id` int(11) PRIMARY KEY AUTO_INCREMENT, -- Unique ID
  `name` text NOT NULL,      -- Name  
  `status` int(11) NOT NULL,  -- Status  
  `created_at` datetime NOT NULL -- Creation date    
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';


INSERT INTO category(`name`, `status`, `created_at`) VALUES(
   'Albums',
   1, '2020-02-20 20:48');

INSERT INTO category(`name`, `status`, `created_at`) VALUES(
   'Books',
   1, '2020-02-20 20:49');

INSERT INTO category(`name`, `status`, `created_at`) VALUES(
   'Comics',
   1, '2020-02-20 20:50');

INSERT INTO category(`name`, `status`, `created_at`) VALUES(
   'Apps',
   1, '2020-02-20 20:50');
