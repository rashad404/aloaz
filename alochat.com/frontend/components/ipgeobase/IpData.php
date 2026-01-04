<?php

namespace frontend\components\ipgeobase;

use yii\base\Object;

/**
 * Информация о конкретном IP-адресе.
 *
 * @author HimikLab
 * @package himiklab\ipgeobase
 */
class IpData extends Object
{

    public $ip;
    public $country_code;
    public $country_name;
    public $region_name;
    public $city_name;
    public $city_id;
    public $latitude;
    public $longitude;
    public $zip_code;
    public $time_zone;

    public function __construct(array $data)
    {
        foreach ($data as $fieldName => $fieldValue) {
            if (property_exists($this, $fieldName)) {
                $this->$fieldName = $fieldValue;
            }
        }
    }
}
