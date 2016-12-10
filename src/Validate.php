<?php
namespace FoxORM\Validate;
use FoxORM\Validate\Ruler;
use FoxORM\Validate\Filter;
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
}