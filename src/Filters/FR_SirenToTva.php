<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class FR_SirenToTva extends FilterRule{
	function filter($siren){
		return "FR" . (( 12 + 3 * ( $siren % 97 ) ) % 97 ) . $siren;
	}
}