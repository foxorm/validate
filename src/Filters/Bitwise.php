<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class Bitwise extends FilterRule{
	function filter($v){
		if(is_array($v)){
			$binary = 0;
			foreach($v as $bin)
				$binary |= (int)$bin;
			return $binary;
		}
		return (int)$v;
	}
}