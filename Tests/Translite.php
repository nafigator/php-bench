<?php
/**
 * Check performance of intl transliteration vs custom
 * @file      Translite.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2020 Yancharuk Alexander
 * @date      Wed Apr 17 14:00:05 2019
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class Translite
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class Translite extends TestApplication
{
    protected $ext_dependencies = ['intl'];

    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$value = 'Всем привет!';

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
            transliterator_transliterate('Any-Latin;Latin-ASCII;', $value);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Intl', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			transliterate($value);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Custom', Timer::get());

        Timer::reset();
        $bar = new CliProgressBar($repeats);
        for ($i = 1; $i <= $repeats; ++$i) {
            Timer::start();
            (new StringTransliterator)->fromRussianToEnglish($value);
            Timer::stop();
            $bar->update($i);
        }

        $this->addResult('Custom class', Timer::get());
	}
}

function transliterate($string)
{
    $cyr = [
        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я',' '
    ];
    $lat = [
        'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
        'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
        'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya','_'
    ];

    return str_replace($cyr, $lat, $string);
}


/**
 * Class StringTransliterator
 */
class StringTransliterator
{
    private const RUSSIAN_SYMBOLS = [
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
        'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', ' '
    ];

    private const LATIN_SYMBOLS = [
        'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
        'r', 's', 't', 'u', 'f', 'h', 'cz', 'ch', 'sh', 'shh', 'a', 'y', 'y', 'e', 'yu', 'ya',
        'A', 'B', 'V', 'G', 'D', 'E', 'Yo', 'Zh', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'R', 'S', 'T', 'U', 'F', 'H', 'Cz', 'Ch', 'Sh', 'Shh', 'A', 'Y', 'Y', 'E', 'Yu', 'Ya', '_'
    ];

    /**
     * @param string $string
     * @return string
     */
    public function fromRussianToEnglish(string $string): string
    {
        return str_replace(static::RUSSIAN_SYMBOLS, static::LATIN_SYMBOLS, $string);
    }

    /**
     * @param string $string
     * @return string
     */
    public function fromEnglishToRussian(string $string): string
    {
        $latin = [];
        $russian = [];
        foreach (static::LATIN_SYMBOLS as $key => $symbol) {
            $latin[strlen($symbol)][] = $symbol;
            $russian[strlen($symbol)][] = static::RUSSIAN_SYMBOLS[$key];
        }
        for ($i = 3; $i > 0; $i--) {
            $string = str_replace($latin[$i], $russian[$i], $string);
        }
        return $string;
    }
}
