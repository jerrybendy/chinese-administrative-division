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
    public function getProvinces()
    {
        $ret = [];
        foreach (self::CODES as $code => $value) {
            if ($value[2] === '') {
                array_push($ret, [
                    'code' => $code,
                    'name' => $value[0],
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
    public function getCities($provinceId)
    {
        $ret = [];
        $provinceId = (string)$provinceId;

        if (!$provinceId) {
            return [];
        }

        foreach (self::CODES as $code => $value) {
            if ($value[1] === '' && $value[2] === $provinceId) {
                array_push($ret, [
                    'code' => $code,
                    'name' => $value[0],
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
    public function getDistricts($cityId)
    {
        $ret = [];
        $cityId = (string)$cityId;

        if (!$cityId) {
            return [];
        }

        foreach (self::CODES as $code => $value) {
            if ($value[1] === $cityId && $value[2]) {
                array_push($ret, [
                    'code' => $code,
                    'name' => $value[0],
                ]);
            }
        }

        return $ret;
    }

    /**
     * 解析一个代码并返回所有的省、市、区数据
     * 用于不确定代码表示的行政级别，或需要返回所有级别数据的情景
     *
     * @param $code
     * @return array|null
     */
    public function parse($code)
    {
        $code = (string)$code;

        if (!isset(self::CODES[$code])) {
            return null;
        }

        list($name, $cityCode, $provinceCode) = self::CODES[$code];

        return [
            'province' => $this->parseProvince($provinceCode),
            'city'     => $this->parseCity($cityCode),
            'district' => $cityCode ? [
                'code' => $code,
                'name' => $name,
            ] : null,
        ];
    }

    /**
     * 解析省份数据，返回省的代码和名称。
     * 不存在或传入代码不是省份时返回 null
     *
     * @param $provinceCode
     * @return array|null
     */
    public function parseProvince($provinceCode)
    {
        $provinceCode = (string)$provinceCode;

        if (!$provinceCode || !isset(self::CODES[$provinceCode])) {
            return null;
        }

        $value = self::CODES[$provinceCode];

        if ($value[1] || $value[2]) {
            return null;
        }

        return [
            'code' => $provinceCode,
            'name' => $value[0],
        ];
    }

    /**
     * 解析城市数据，返回市的代码和名称。
     * 不存在或传入代码不是地级市时返回 null
     *
     * @param $cityCode
     * @return array|null
     */
    public function parseCity($cityCode)
    {
        $cityCode = (string)$cityCode;

        if (!$cityCode || !isset(self::CODES[$cityCode])) {
            return null;
        }

        $value = self::CODES[$cityCode];

        if ($value[1]) {
            return null;
        }

        return [
            'code' => $cityCode,
            'name' => $value[0],
        ];
    }


    /**
     * 解析区域数据，返回区域的代码和名称。
     * 不存在或传入代码不是县级市（区）时返回 null
     *
     * @param $districtCode
     * @return array|null
     */
    public function parseDistrict($districtCode)
    {
        $districtCode = (string)$districtCode;

        if (!$districtCode || !isset(self::CODES[$districtCode])) {
            return null;
        }

        $value = self::CODES[$districtCode];

        if (!$value[1]) {
            return null;
        }

        return [
            'code' => $districtCode,
            'name' => $value[0],
        ];
    }
}