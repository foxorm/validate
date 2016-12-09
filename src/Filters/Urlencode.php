<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class Urlencode extends FilterRule{
	function filter($v){
		return urlencode($v);
	}
}