<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class Rmpunctuation extends FilterRule{
	function filter($v){
		return preg_replace("/(?![.=$'€%-])\p{P}/u", '', $v);
	}
}