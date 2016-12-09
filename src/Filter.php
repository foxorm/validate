<?php
namespace FoxORM\Validate;
use FoxORM\Validate\FilterSet;
class Filter{
	function createFilter($filterSet){
		$filter = new FilterSet();
		$filter->addFilters($filterSet);
		return $filter;
	}
}