<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class Luhn extends AbstractRule{
    function validate($val){
		$len = strlen($val);
		$total = 0;
		for ($i = 1; $i <= $len; $i++) {
			$chiffre = substr($val,-$i,1);
			if($i % 2 == 0) {
				$total += 2 * $chiffre;
				if((2 * $chiffre) >= 10) $total -= 9;
			}
			else{
				$total += $chiffre;
			}
		}
		return !!($total % 10 == 0);
    }
}