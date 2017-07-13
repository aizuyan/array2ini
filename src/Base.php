<?php

namespace Aizuyan\Array2Ini;

class Base
{
    /**
     * @var 没有章节的配置保存在该hash键名下面
     */
    const NO_SECTION_KEY = "noSection";

    /**
     * @desc ini章节内容保存数组
     *
     * @var array
     */
	protected $sections = [];

    /**
     * @desc 转换数组为ini文件内容输出
     *
     * @param $arr
     */
	public function convert($arr)
	{
		$this->handleFormat($arr);
		$this->handleMerge();
		$strIni = $this->output();
		return $strIni;
	}

    /**
     * @desc 将php数组分段
     *
     * @param $arr
     * @param string $pre
     * @param int $deep
     * @return array
     */
	protected function handleFormat($arr, $pre = "", $deep = 1)
	{
	    // 递归处理的时候保存当前的section名称
	    static $nowSection = self::NO_SECTION_KEY;
	    // 预处理
		foreach ($arr as $key => $val) {
			if (1 == $deep && is_array($val)) {
			    $this->sections[$key] = [];
			    $nowSection = $key;
				$now = "";
			} else {
                if (1 == $deep) {
                    $nowSection = self::NO_SECTION_KEY;
                }
				$now = $pre ? "{$pre}.{$key}" : $key;
			}
			if (is_array($val)) {
				$this->handleFormat($val, $now, $deep + 1);
			} else {
                $this->sections[$nowSection][$now] = $val;
			}
		}

		return $this->sections;
	}

    /**
     * @desc 将格式化之后的内容合并，只能合并连续的继承关系
     */
	protected function handleMerge()
    {
        $preSectionName = "";
        $preSection = [];
        $sections = $this->sections;
        $this->sections = [];
        if (isset($sections["noSection"])) {
            $this->sections["noSection"] = $sections[self::NO_SECTION_KEY];
            unset($sections["noSection"]);
        }

        foreach ($sections as $sectionName => $sectionVal){
            if (empty($preSection)) {
                $this->sections[$sectionName] = $sectionVal;
                $preSection = $sectionVal;
            } else {
                $isInherit = true;
                foreach ($preSection as $key => $val) {
                    if (!isset($sectionVal[$key])) {
                        $isInherit = false;
                        break;
                    }
                }

                if (!$isInherit) {
                    $this->sections[$sectionName] = $sectionVal;
                    $preSection = $sectionVal;
                } else {
                    $nowSectionName = $sectionName . ":" . $preSectionName;
                    $this->sections[$nowSectionName] = [];
                    foreach ($sectionVal as $key => $val) {
                        if (!isset($preSection[$key]) || $val != $preSection[$key]) {
                            $this->sections[$nowSectionName][$key] = $val;
                            $preSection[$key] = $val;
                        }
                    }
                }
            }
            $preSectionName = $sectionName;
        }
    }

    /**
     * @desc 将ini文件内容输出
     *
     * @return string
     */
    protected function output()
    {
        $strIni = "";
        foreach ($this->sections as $sectionName => $sectionVal) {
            if (self::NO_SECTION_KEY != $sectionName) {
                $strIni .= "[{$sectionName}]\n";
            }
            foreach ($sectionVal as $key => $val) {
                $strIni .= "{$key}=\"{$val}\"\n";
            }
        }

        $strIni = trim($strIni);

        return $strIni;
    }
}
