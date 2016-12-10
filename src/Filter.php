<?php
namespace FoxORM\Validate;
use FoxORM\Validate\FilterSet;
class Filter{
	function createFilter($filterSet){
		$filter = new FilterSet();
		$filter->addFilters($filterSet);
		return $filter;
	}
	function __call($method,$arguments){
		$filter = new FilterSet();
		$filterResource = $filter->getFilterResource();
		$value = array_pop($arguments);
		call_user_func_array([$filterResource,$method],$arguments);
		$result = $filter->filter([$value]);
		$result = current($result);
		return $result;
	}
}