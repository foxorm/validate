<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class DateBefore extends AbstractRule{
	protected $inclusive;
	protected $before;
    function __construct($before, $inclusive=false){
		$this->before = $before;
		$this->inclusive = $inclusive;
	}
    function validate($value){
		$vtime = ($value instanceof \DateTime) ? $value->getTimestamp() : strtotime($value);
		$ptime = ($this->before instanceof \DateTime) ? $before->getTimestamp() : strtotime($this->before);
		return $this->inclusive?$vtime <= $ptime:$vtime < $ptime;
    }
}