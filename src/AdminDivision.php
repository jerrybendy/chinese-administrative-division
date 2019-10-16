<?php
/**
 * 行政区划的操作类
 *
 * @author Jerry Bendy (jerry@icewingcc.com)
 * @date   2018-05-30
 * @link   https://icewing.cc
 */

namespace Jerrybendy\ChineseAdminDivision;


class AdminDivision extends Codes
{
    /**
     * 获取省份列表
     *
     * @return array
     */
    public static function getProvinces()
    {
        $ret = [];
        foreach (self::$CODES as $code => $value) {
            if ($code % 10000 === 0) {
                array_push($ret, [
                    'code' => $code,
                    'name' => $value,
                ]);
            }
        }

        return $ret;
    }

    /**
     * 获取省下级的地级市列表
     *
     * @param integer $provinceId 省的代码
     * @return array
     */
    public static function getCities($provinceId)
    {
        $ret = [];
        $provinceId = intval($provinceId);

        if (!$provinceId) {
            return [];
        }

        foreach (self::$CODES as $code => $value) {
            if ($code > $provinceId && $code < $provinceId + 9999 && $code % 100 === 0) {
                array_push($ret, [
                    'code' => $code,
                    'name' => $value,
                ]);
            }
        }

        return $ret;
    }

    /**
     * 获取地级市下面的区县数据
     *
     * @param integer $cityId 地级市的代码
     * @return array
     */
    public static function getDistricts($cityId)
    {
        $ret = [];
        $cityId = intval($cityId);

        if (!$cityId) {
            return [];
        }

        foreach (self::$CODES as $code => $value) {
            if ($code > $cityId && $code < $cityId + 99) {
                array_push($ret, [
                    'code' => $code,
                    'name' => $value,
                ]);
            }
        }

        return $ret;
    }

    /**
     * 解析一个代码并返回所有的省、市、区数据
     * 用于不确定代码表示的行政级别，或需要返回所有级别数据的情景
     *
     * @param int|string $code
     * @return array|null
     */
    public static function parse($code)
    {
        $code = intval($code);

        if (!isset(self::$CODES[$code])) {
            return null;
        }

        return [
            'province' => self::parseProvince($code),
            'city' => self::parseCity($code),
            'district' => self::parseDistrict($code),
        ];
    }

    /**
     * 解析省份数据，返回省的代码和名称。
     * 不存在或传入代码不是省份时返回 null
     *
     * @param int|string $provinceCode
     * @return array|null
     */
    public static function parseProvince($provinceCode)
    {
        $provinceCode = intval($provinceCode);
        $provinceCode = $provinceCode - ($provinceCode % 10000);

        if (!$provinceCode || !isset(self::$CODES[$provinceCode])) {
            return null;
        }

        return [
            'code' => $provinceCode,
            'name' => self::$CODES[$provinceCode],
        ];
    }

    /**
     * 解析城市数据，返回市的代码和名称。
     * 不存在或传入代码不是地级市时返回 null
     *
     * @param int|string $cityCode
     * @return array|null
     */
    public static function parseCity($cityCode)
    {
        $cityCode = intval($cityCode);
        $cityCode = $cityCode - ($cityCode % 100);

        if (!$cityCode || $cityCode % 10000 === 0 || !isset(self::$CODES[$cityCode])) {
            return null;
        }

        return [
            'code' => $cityCode,
            'name' => self::$CODES[$cityCode],
        ];
    }


    /**
     * 解析区域数据，返回区域的代码和名称。
     * 不存在或传入代码不是县级市（区）时返回 null
     *
     * @param $districtCode
     * @return array|null
     */
    public static function parseDistrict($districtCode)
    {
        $districtCode = intval($districtCode);

        if (!$districtCode || $districtCode % 100 === 0 || !isset(self::$CODES[$districtCode])) {
            return null;
        }

        return [
            'code' => $districtCode,
            'name' => self::$CODES[$districtCode],
        ];
    }
}