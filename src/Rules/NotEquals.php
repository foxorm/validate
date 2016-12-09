<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class NotEquals extends AbstractRule{
	public $compareTo;
	public $strict;
    function __construct($compareTo,$strict=false){
		$this->compareTo = $compareTo;
		$this->strict = $strict;
	}
    function validate($v){
		return $this->strict ? $v !== $this->compareTo: $v != $this->compareTo;
    }
}