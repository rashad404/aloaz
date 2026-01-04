<?php
/**
 * @link https://github.com/himiklab/yii2-ipgeobase-component
 * @copyright Copyright (c) 2014 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace frontend\components\ipgeobase;

use Yii;
use yii\base\Component;
use yii\base\Exception;


class IpGeoBase extends Component
{

    const DB_IP_TABLE_NAME = '{{%ip}}';
    const DB_CITY_TABLE_NAME = '{{%city}}';
    const DB_COUNTRY_TABLE_NAME = '{{%country}}';


    public function getLocation($ip, $asArray = true)
    {
        $ipDataArray = $this->fromDB($ip) + ['ip' => $ip];

        if ($asArray) {
            return $ipDataArray;
        } else {
            return new IpData($ipDataArray);
        }
    }

    /**
     * Тест скорости получения данных из БД.
     * @param int $iterations
     * @return float IP/second
     */
    public function speedTest($iterations)
    {
        $ips = [];
        for ($i = 0; $i < $iterations; ++$i) {
            $ips[] = mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
        }

        $begin = microtime(true);
        foreach ($ips as $ip) {
            $this->getLocation($ip);
        }
        $time = microtime(true) - $begin;

        if ($time != 0 && $iterations != 0) {
            return $iterations / $time;
        } else {
            return 0.0;
        }
    }


    /**
     * @param string $ip
     * @return array
     */
    protected function fromDB($ip)
    {
        $dbIpTableName = self::DB_IP_TABLE_NAME;
        $dbCityTableName = self::DB_CITY_TABLE_NAME;
        $dbCountryTableName = self::DB_COUNTRY_TABLE_NAME;

        $result = Yii::$app->db->createCommand(

            "SELECT tIp.country_code AS country_code, tCity.id as city_id, tCity.name AS city,
                    tCountry.name AS country ,tCountry.id as country_id

            FROM (SELECT * FROM {$dbIpTableName} WHERE ip_from <= INET_ATON(:ip) ORDER BY ip_from DESC LIMIT 1) AS tIp

            LEFT JOIN {$dbCityTableName} AS tCity ON tCity.id = tIp.city_id

            LEFT JOIN {$dbCountryTableName} AS tCountry ON tCountry.code = tCity.country_code
          
            WHERE INET_ATON(:ip) <= tIp.ip_to"
        )->bindValue(':ip', $ip)->queryOne();

        if ($result != false) {
            return $result;
        } else {
            return [];
        }
    }


    public function getIP()
    {
        $ipaddress = '';

        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR']) {

            $ipArr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ipaddress = trim($ipArr[0]);
        } else if (array_key_exists('HTTP_X_FORWARDED', $_SERVER) && $_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (array_key_exists('HTTP_FORWARDED', $_SERVER) && $_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '';

        return $ipaddress;
    }
}
