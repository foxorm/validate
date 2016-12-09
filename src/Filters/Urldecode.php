<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class Urldecode extends FilterRule{
	function filter($v){
		return urldecode($v);
	}
}