<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class Time extends AbstractRule{
    function validate($time){		
		if(mb_strlen($time)==5)
			$time .= ':00';
		$xp = explode(':',$time);
		$hour = (int)@$xp[0];
		$minute = (int)@$xp[1];
		$second = (int)@$xp[2];
		return $hour>-1&&$hour<24&&$minute>-1&&$minute<60&&$second>-1&&$second<60;
    }
}