<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class FR_SiretToSiren extends FilterRule{
	function filter($siret){
		return substr($siret,0,9);
	}
}