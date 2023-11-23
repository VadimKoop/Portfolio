#customers
create table customers (
 id int(8) unsigned auto_increment primary key,
  first_name varchar(20) not null,
  last_name varchar(30) not null,
  ssid varchar(20) not null unique,
  contact_phone varchar(15),
  ads_allowed int,
  cust_number varchar(25), 
  language varchar(15)
  );
  
#inserts for customers
insert into `customers` (`first_name`,`last_name`, `ssid`, `contact_phone`, `ads_allowed`,`cust_number`, `language`) values ("Trevor","Paterson","489972","45704",1,"3467507","English");
insert into `customers` (`first_name`,`last_name`, `ssid`, `contact_phone`, `ads_allowed`,`cust_number`, `language`) values ("Cameron","Coleman","735185","34882",1,"1409761","English");
insert into `customers` (`first_name`,`last_name`, `ssid`, `contact_phone`, `ads_allowed`,`cust_number`, `language`) values ("Joanne","Clark","883387","46417",1,"6109353","Estonian");
insert into `customers` (`first_name`,`last_name`, `ssid`, `contact_phone`, `ads_allowed`,`cust_number`, `language`) values ("Molly","Oliver","294778","28705",0,"5539133","English");
insert into `customers` (`first_name`,`last_name`, `ssid`, `contact_phone`, `ads_allowed`,`cust_number`, `language`) values ("Simon","Nash","245866","85604",0,"8985621","Russian");
insert into `customers` (`first_name`,`last_name`, `ssid`, `contact_phone`, `ads_allowed`,`cust_number`, `language`) values ("Emma","King","840894","13148",0,"7966083","Estonian");
insert into `customers` (`first_name`,`last_name`, `ssid`, `contact_phone`, `ads_allowed`,`cust_number`, `language`) values ("Simon","Powell","540559","55893",0,"8902962","English");


#contracts
create table contracts (
  id int(8) unsigned auto_increment primary key,
  customer_id int(8),
  msisdn varchar(20),
  packet_name varchar(30),
  cost float,
  is_active int,
  employee_id integer
  );

#inserts for contracts
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (4, "500015808", "Moodne pakett",15.0,0,1);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (5, "500038307", "Moodne pakett",15.0,1,4);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (1, "500049519", "Moodne pakett",15.0,1,5);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (4, "50008755", "Tavapakett",10.0,1,3);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (4, "500017284", "Vanapakett",4.75,1,1);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (7, "500047428", "Vanapakett",4.75,0,2);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (2, "500049341", "Tavapakett",10.0,1,3);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (5, "500049835", "Reisija pakett",10.0,0,4);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (2, "500017731", "Tavapakett",10.0,0,3);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (1, "500026292", "Tavapakett",10.0,1,2);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (6, "500037977", "Reisija pakett",10.0,0,2);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (5, "500024903", "Reisija pakett",10.0,1,2);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (7, "500048985", "Vanapakett",4.75,1,1);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (7, "500035942", "Reisija pakett",10.0,0,4);
insert into `contracts` (`customer_id`,`msisdn`, `packet_name`, `cost`, `is_active`, `employee_id`) values (1, "500017836", "Tavapakett",10.0,1,4);

#employees
create table employees (
  id int(8) unsigned auto_increment primary key,
  first_name varchar(20),
  last_name varchar(30),
  ssid varchar(30),
  department varchar(70),
  is_active int);
 
#inserts for employees
insert into `employees` (`first_name`,`last_name`, `ssid`, `department`, `is_active`) values ("Zoe","Hardacre","776212","Technical department",1);
insert into `employees` (`first_name`,`last_name`, `ssid`, `department`, `is_active`) values ("Nicholas","Lewis","452049","Back-office",0);
insert into `employees` (`first_name`,`last_name`, `ssid`, `department`, `is_active`) values ("Kevin","Ross","445416","Technical department",1);
insert into `employees` (`first_name`,`last_name`, `ssid`, `department`, `is_active`) values ("Michael","McDonald","358691","Sales",1);
insert into `employees` (`first_name`,`last_name`, `ssid`, `department`, `is_active`) values ("Joe","Gibson","35777","Sales",1);