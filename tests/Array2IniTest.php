<?php
/**
 ****************************************************************
 *                                                              *
 * Copyright (c) weibo.com. All Rights Reserved.                *
 *                                                              *
 ****************************************************************
 * Created by PhpStorm.                                  *
 * User: ruitao                                                *
 * Date: 2017/7/13                                                *
 * Time: 下午4:41                                                *
 ****************************************************************
 */
use PHPUnit\Framework\TestCase;
use Aizuyan\Array2Ini\Base;

class Array2IniTest extends TestCase
{
    protected $obj = null;

    protected function setUp()
    {
        $this->obj = new Base();
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple($arr, $ini)
    {
        $ret = $this->obj->convert($arr);
        var_dump($ret);
        $this->assertEquals($ini, $ret);
    }

    public function simpleDataProvider()
    {
        $ini1 = <<<ET
name="yaconf"
year="2017"
[features]
0="fast"
1="light"
plus="zero-copy"
constant="7.1.1"
ET;

        $ini2 = <<<ET
[base]
parent="yaconf"
children="NULL"
[children:base]
children="set"
ET;

        $ini3 = <<<ET
name="燕睿涛"
age="25"
[base]
parent="yaconf"
children="NULL"
[children:base]
children="set"
ET;

        $ini4 = <<<ET
yrt=""
name="燕睿涛"
age="25"
[base]
parent="yaconf"
children="NULL"
yrt=""
[children:base]
children="set"
ET;

        $ret = [
            [
                [
                    "name" => "yaconf",
                    "year" => 2017,
                    "features" => [
                        "fast",
                        "light",
                        "plus" => "zero-copy",
                        "constant" => "7.1.1"
                    ]
                ],
                $ini1
            ],
            [
                [
                    "base" => [
                        "parent" => "yaconf",
                        "children" => "NULL"
                    ],
                    "children" => [
                        "parent" => "yaconf",
                        "children" => "set"
                    ]
                ]
                ,
                $ini2
            ],
            [
                [
                    "base" => [
                        "parent" => "yaconf",
                        "children" => "NULL"
                    ],
                    "name" => "燕睿涛",
                    "age" => 25,
                    "children" => [
                        "parent" => "yaconf",
                        "children" => "set"
                    ]
                ]
                ,
                $ini3
            ],
            [
                [
                    "yrt" => "",
                    "base" => [
                        "parent" => "yaconf",
                        "children" => "NULL",
                        "yrt" => ""
                    ],
                    "name" => "燕睿涛",
                    "age" => 25,
                    "children" => [
                        "parent" => "yaconf",
                        "children" => "set",
                        "yrt" => ""
                    ]
                ]
                ,
                $ini4
            ]
        ];

        return $ret;
    }
}