<?php
/**
 * Test how background key rebuild impacts on inserts
 *
 * @file      AlterIndexes.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2018 Yancharuk Alexander
 * @date      Tue Jul 31 11:19:56 2018
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use PDO;
use Veles\DataBase\Db;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class AlterIndexes
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class AlterIndexes extends TestApplication
{
	protected $repeats = 10000;
	protected $sql = "
		INSERT united_requests (country_id, affiliate_id, created_date, created_datetime, status, phone, email, incoming_data, error_code, error_message)
		VALUES (7, 24234, now(), now(), 1, '7965444333222', 'mail@mail.ru', '', 0, '')
	";

	public function run()
	{
		$repeats = $this->getRepeats();
		$this->prepareDataBase();

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			Db::query($this->sql);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('insert', Timer::get());

		// Отправляем выполнение процесса в бэкграунд
		shell_exec("mysql landings -e 'alter table united_requests drop key wmid_date;alter table united_requests add key wmid_date (created_date, country_id, affiliate_id);' 2>sql.log >sql.log &");
		printf("Key rebuild started\n");

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			Db::query($this->sql);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('insert + key', Timer::get());
	}

    protected function prepareDataBase()
    {
//        $dbh = new PDO("mysql:host=localhost", 'root', '');
//        $dbh->exec("CREATE DATABASE IF NOT EXISTS landings");
//
//        $dbh->exec("
//          CREATE TABLE IF NOT EXISTS `united_countries` (
//           `id` int(11) NOT NULL AUTO_INCREMENT,
//           `name` varchar(255) NOT NULL,
//           `code` varchar(255) NOT NULL,
//           `description` text,
//           PRIMARY KEY (`id`)
//         ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");
//
//        $dbh->exec("
//            INSERT INTO `united_countries` VALUES
//            (7,'Россия','RU',NULL),
//            (6,'Украина лидовая','UA_lead',NULL),
//            (5,'Испания','ES',NULL),
//            (4,'Польша','PL',NULL),
//            (3,'Казахстан','KZ',NULL),
//            (2,'Единая Россия под авто','',NULL),
//            (1,'Украина','UA',NULL)
//        ");
//
//        $dbh->exec("
//           CREATE TABLE IF NOT EXISTS `united_requests` (
//          `id` int(11) NOT NULL AUTO_INCREMENT,
//          `country_id` int(11) NOT NULL,
//          `affiliate_id` int(10) unsigned NOT NULL,
//          `created_date` date NOT NULL,
//          `created_datetime` datetime NOT NULL,
//          `status` tinyint(4) NOT NULL,
//          `phone` varchar(32) DEFAULT NULL,
//          `email` varchar(255) DEFAULT NULL,
//          `incoming_data` text NOT NULL,
//          `error_code` int(4) DEFAULT NULL,
//          `error_message` varchar(1024) DEFAULT NULL,
//          `ip` varchar(40) DEFAULT NULL,
//          PRIMARY KEY (`id`),
//          KEY `phone_index` (`phone`),
//          KEY `date` (`created_date`),
//          KEY `countries` (`country_id`),
//          KEY `phone_date` (`phone`,`created_date`),
//          KEY `wmid_date` (`created_date`,`country_id`,`affiliate_id`),
//          CONSTRAINT `country_fk` FOREIGN KEY (`country_id`) REFERENCES `united_countries` (`id`)
//        ) ENGINE=InnoDB AUTO_INCREMENT=153958338 DEFAULT CHARSET=utf8");
	}
}
