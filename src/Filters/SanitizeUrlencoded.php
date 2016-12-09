<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class SanitizeUrlencoded extends FilterRule{
	function filter($v){
		return filter_var($v, FILTER_SANITIZE_ENCODED);
	}
}