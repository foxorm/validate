<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class CharEqual extends AbstractRule{
	protected $max;
    function __construct($max){
		$this->max = $max;
	}
    function validate($v){
		return $this->length($v)==$this->max;
    }
    protected function length($v){
		return mb_strlen($this->cleanString($v));
	}
    protected function cleanString($v){
		$v = strip_tags($v);
		$v = str_replace([' ',"\n","\r","\t"],'',$v);
		return $v;
	}
}