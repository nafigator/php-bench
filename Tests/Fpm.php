<?php
/**
 * Тестирование производительности записи в сокет
 *
 * @file      Fpm.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Wed Jul 24 17:45:29 2019
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use gateways\common\Sender;
use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\GetRequest;
use hollodotme\FastCGI\SocketConnections\UnixDomainSocket;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class Fpm
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class Fpm extends TestApplication
{
    protected $repeats = 1000;
    protected const FPM_QUERY = 'http://gateways.local/gateways/sovkom/send?fname=Тест&mname=Тест&lname=Тест&sum=15000&region=Ставропольский Край&city=Пятигорск&work_st=работает&bdate=1993-01-28&chan=testChan&apiKey=1q2w3e4r5t6y7u8i9o0p&wmid=1488&phone=9876543210&email=big_5336@rambler.ru&data1=data1&data2=data2&data3=data3&data4=data4&data5=data5&personal_data_agree=1&personal_data_datetime=2019-07-24 18:12:23&personal_data_url=https://example.com/anketa&advert_agree=1&user_agent=Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0&ip=127.0.0.1';
    protected const CLI_QUERY = 'http://gateways.local/gateways/mig/send?fname=Тест&mname=Тест&lname=Тест&sum=15000&region=Ставропольский Край&city=Пятигорск&work_st=работает&bdate=1993-01-28&chan=testChan&apiKey=1q2w3e4r5t6y7u8i9o0p&wmid=1488&phone=9876543210&email=big_5336@rambler.ru&data1=data1&data2=data2&data3=data3&data4=data4&data5=data5&personal_data_agree=1&personal_data_datetime=2019-07-24 18:12:23&personal_data_url=https://example.com/anketa&advert_agree=1&user_agent=Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0&ip=127.0.0.1';
    protected const FPM_QUERY_DATA = [
        'fname'                  => 'Тест',
        'mname'                  => 'Тест',
        'lname'                  => 'Тест',
        'sum'                    => 15000,
        'region'                 => 'Ставропольский Край',
        'city'                   => 'Пятигорск',
        'work_st'                => 'работает',
        'bdate'                  => '1993-01-28',
        'chan'                   => 'testChan',
        'apiKey'                 => '1q2w3e4r5t6y7u8i9o0p',
        'wmid'                   => 1488,
        'phone'                  => 9876543210,
        'email'                  => 'big_5336@rambler.ru',
        'data1'                  => 'data1',
        'data2'                  => 'data2',
        'data3'                  => 'data3',
        'data4'                  => 'data4',
        'data5'                  => 'data5',
        'personal_data_agree'    => 1,
        'personal_data_datetime' => '2019-07-24 18:12:23',
        'personal_data_url'      => 'https://example.com/anketa',
        'advert_agree'           => 1,
        'user_agent'             => 'Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0',
        'ip'                     => '127.0.0.1',
    ];

	public function run()
	{
	    ini_set('display_errors', true);
	    ini_set('display_startup_errors', true);
	    ini_set('log_errors', true);
	    ini_set('error_log', '/var/log/php_errors.log');
		$repeats = $this->getRepeats();

        $sender = new Sender;
        $sender->parseQuery(static::FPM_QUERY);

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$sender->sendFromQueue();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('CLI PHP', Timer::get());

		Timer::reset();

        $sender->parseQuery(static::CLI_QUERY);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
            $sender->sendFromQueue();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('cgi-fcgi', Timer::get());

        Timer::reset();

        $connection = new UnixDomainSocket('/run/php/alex-fpm.sock');

        $content = http_build_query(static::FPM_QUERY_DATA);
        $request = new GetRequest('/home/alex/dev/gateways/public/index.php', $content);
        $client = new Client;

        $bar = new CliProgressBar($repeats);
        for ($i = 1; $i <= $repeats; ++$i) {
            Timer::start();
            $client->sendRequest($connection, $request);
            Timer::stop();
            $bar->update($i);
        }

        $this->addResult('FPM lib', Timer::get());
	}
}
