<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class RemoveWhiteSpaces extends FilterRule{
	function filter($v){
		return preg_replace('/\s+/', '', $v);
	}
}