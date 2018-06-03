<?php
/**
 * Find a fastest algorithm for geo-distance calculation
 *
 * @file      GeoDistance.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2017 Yancharuk Alexander
 * @date      Fri Dec 02 10:46:02 2016
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class GeoDistance
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class GeoDistance extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$latitude_from  = 55.7402;
		$longitude_from = 37.5085;
		$latitude_to    = 55.577206;
		$longitude_to   = 38.914059;

		//var_dump(vincentyGreatCircleDistance($latitude_from, $longitude_from, $latitude_to, $longitude_to));
		//var_dump(codexworldGetDistance($latitude_from, $longitude_from, $latitude_to, $longitude_to));
		//var_dump(codexworldGetDistanceOpt($latitude_from, $longitude_from, $latitude_to, $longitude_to));
		//var_dump(distanceUserChecker($latitude_from, $longitude_from, $latitude_to, $longitude_to));
		//var_dump(distanceUserChecker1($latitude_from, $longitude_from, $latitude_to, $longitude_to));
		//var_dump(distanceUserChecker2($latitude_from, $longitude_from, $latitude_to, $longitude_to));

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			vincentyGreatCircleDistance($latitude_from, $longitude_from, $latitude_to, $longitude_to);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('vincenty', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			codexworldGetDistance($latitude_from, $longitude_from, $latitude_to, $longitude_to);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('codexworld', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			codexworldGetDistanceOpt($latitude_from, $longitude_from, $latitude_to, $longitude_to);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('codexworld-opt', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			distanceUserChecker($latitude_from, $longitude_from, $latitude_to, $longitude_to);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('custom', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			distanceUserChecker1($latitude_from, $longitude_from, $latitude_to, $longitude_to);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('custom1', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			distanceUserChecker2($latitude_from, $longitude_from, $latitude_to, $longitude_to);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('custom2', Timer::get());
	}
}

/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 *
 * @param float     $latitudeFrom  Latitude of start point in [deg decimal]
 * @param float     $longitudeFrom Longitude of start point in [deg decimal]
 * @param float     $latitudeTo    Latitude of target point in [deg decimal]
 * @param float     $longitudeTo   Longitude of target point in [deg decimal]
 * @param float|int $earthRadius   Mean earth radius in [m]
 *
 * @return float Distance between points in [m] (same as earthRadius)
 */
function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
	// convert from degrees to radians
	$latFrom  = deg2rad($latitudeFrom);
	$lonFrom  = deg2rad($longitudeFrom);
	$latTo    = deg2rad($latitudeTo);
	$lonTo    = deg2rad($longitudeTo);
	$lonDelta = $lonTo - $lonFrom;

	$a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
	$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

	$angle = atan2(sqrt($a), $b);

	return $angle * $earthRadius;
}

/**
 * Algorithm got from http://www.codexworld.com/distance-between-two-addresses-google-maps-api-php/
 *
 * @param $latitudeFrom
 * @param $longitudeFrom
 * @param $latitudeTo
 * @param $longitudeTo
 *
 * @return float [km]
 */
function codexworldGetDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    //Calculate distance from latitude and longitude
    $theta = $longitudeFrom - $longitudeTo;
    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);

    return $dist * 60 *  1.852;
}

/**
 * Optimized algorithm from http://www.codexworld.com/distance-between-two-addresses-google-maps-api-php/
 *
 * @param $latitudeFrom
 * @param $longitudeFrom
 * @param $latitudeTo
 * @param $longitudeTo
 *
 * @return float [km]
 */
function codexworldGetDistanceOpt($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
	$rad = M_PI / 180;
    //Calculate distance from latitude and longitude
    $theta = $longitudeFrom - $longitudeTo;
    $dist = sin($latitudeFrom * $rad) * sin($latitudeTo * $rad) +  cos($latitudeFrom * $rad) * cos($latitudeTo * $rad) * cos($theta * $rad);

    return acos($dist) / $rad * 60 *  1.853159616;
}

/**
 * Returns distance in km
 *
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 *
 * @return float
 */
function distanceUserChecker($lat1, $lng1, $lat2, $lng2)
{
	$pi80 = M_PI / 180;
	$lat1 *= $pi80;
	$lng1 *= $pi80;
	$lat2 *= $pi80;
	$lng2 *= $pi80;

	$r = 6372.797; // mean radius of Earth in km
	$dlat = $lat2 - $lat1;
	$dlng = $lng2 - $lng1;
	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

	return $r * $c;
}

/**
 * Returns distance in km
 *
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 *
 * @return float
 */
function distanceUserChecker1($lat1, $lng1, $lat2, $lng2)
{
	$r = 6372.797; // mean radius of Earth in km
	$dlat = deg2rad($lat2) - deg2rad($lat1);
	$dlng = deg2rad($lng2) - deg2rad($lng1);
	$a = sin($dlat / 2) * sin($dlat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

	return $r * $c;
}

/**
 * Returns distance in km
 *
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 *
 * @return float
 */
function distanceUserChecker2($lat1, $lng1, $lat2, $lng2)
{
	$pi80 = M_PI / 180;
	$lat1 *= $pi80;
	$lng1 *= $pi80;
	$lat2 *= $pi80;
	$lng2 *= $pi80;

	$r = 6372.797; // mean radius of Earth in km
	$dlat = ($lat2 - $lat1) / 2;
	$dlng = ($lng2 - $lng1) / 2;
	$a = sin($dlat) * sin($dlat) + cos($lat1) * cos($lat2) * sin($dlng) * sin($dlng);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

	return $r * $c;
}
