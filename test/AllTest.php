<?php

namespace Test;

use Jerrybendy\ChineseAdminDivision\AdminDivision;
use PHPUnit\Framework\TestCase;

class AllTest extends TestCase
{
    function testGetProvinces()
    {
        $provinces = AdminDivision::getProvinces();

        $this->assertCount(34, $provinces);
        $this->assertContains(['code' => 110000, 'name' => '北京市'], $provinces);
        $this->assertContains(['code' => 320000, 'name' => '江苏省'], $provinces);
    }


    function testGetCities()
    {
        $cities = AdminDivision::getCities(320000);

        $this->assertCount(13, $cities);
        $this->assertContains(['code' => 320100, 'name' => '南京市'], $cities);
        $this->assertContains(['code' => 320500, 'name' => '苏州市'], $cities);
    }

    function testGetDistricts()
    {
        $districts = AdminDivision::getDistricts(320500);

        $this->assertContains(['code' => 320508, 'name' => '姑苏区'], $districts);
    }

    function testParseProvince()
    {
        $beijing = AdminDivision::parseProvince(110000);
        $this->assertEquals(['code' => 110000, 'name' => '北京市'], $beijing);

        $jiangsu = AdminDivision::parseProvince(321010);
        $this->assertEquals(['code' => 320000, 'name' => '江苏省'], $jiangsu);

        $null = AdminDivision::parseProvince(9999999);
        $this->assertNull($null);
    }

    function testParseCity()
    {
        $sz = AdminDivision::parseCity(320599);
        $this->assertEquals(['code' => 320500, 'name' => '苏州市'], $sz);

        $null = AdminDivision::parseCity(9999999);
        $this->assertNull($null);
    }

    function testParseDistrict()
    {
        $sz = AdminDivision::parseDistrict(320508);
        $this->assertEquals(['code' => 320508, 'name' => '姑苏区'], $sz);

        $null = AdminDivision::parseDistrict(9999999);
        $this->assertNull($null);
    }

    function testParse()
    {
        $gs = AdminDivision::parse(320508);
        $this->assertEquals([
            'province' => ['code' => 320000, 'name' => '江苏省'],
            'city' => ['code' => 320500, 'name' => '苏州市'],
            'district' => ['code' => 320508, 'name' => '姑苏区'],
        ], $gs);

        $sz = AdminDivision::parse(320500);
        $this->assertEquals([
            'province' => ['code' => 320000, 'name' => '江苏省'],
            'city' => ['code' => 320500, 'name' => '苏州市'],
            'district' => null,
        ], $sz);

        $js = AdminDivision::parse(320000);
        $this->assertEquals([
            'province' => ['code' => 320000, 'name' => '江苏省'],
            'city' => null,
            'district' => null,
        ], $js);

        $null = AdminDivision::parse(999999);
        $this->assertNull($null);
    }
}
