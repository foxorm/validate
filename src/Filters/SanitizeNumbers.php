<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class SanitizeNumbers extends FilterRule{
	function filter($v){
		return filter_var($v, FILTER_SANITIZE_NUMBER_INT);
	}
}