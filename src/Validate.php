<?php
namespace FoxORM\Validate;
use FoxORM\Validate\Ruler;
use FoxORM\Validate\Filter;
use BadMethodCallException;
class Validate{
	protected $ruler;
	protected $filter;
	function __construct(){
		$this->ruler = new Ruler();
		$this->filter = new Filter();
	}
	function getRulerService(){
		return $this->ruler;
	}
	function getFilterService(){
		return $this->filter;
	}
	function createFilter(array $rules = []){
		return $this->filter->createFilter($rules);
	}
	function createRules(array $rules = []){
		return $this->ruler->createRule($rules);
	}
	function __call($method,$arguments){
		if(substr($method,0,6)=='filter'){
			$call = substr($method,6);
			return call_user_func_array([$this->filter,$call],$arguments);
		}
		else if(substr($method,0,4)=='rule'){
			$call = substr($method,4);
			$value = array_pop($arguments);
			$rule = call_user_func_array([$this->ruler,$call],$arguments);
			return $rule->validate($value);
		}
		else{
			throw new BadMethodCallException("Called validate \"$method\" doesn't exists");
		}
	}
}