<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class StripTags extends FilterRule{
	function filter($v){
		return strip_tags($v);
	}
}