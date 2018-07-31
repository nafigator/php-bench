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
}