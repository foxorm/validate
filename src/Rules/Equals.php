<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class Equals extends AbstractRule{
	protected $compareTo;
	protected $strict;
    function __construct($compareTo,$strict=false){
		$this->compareTo = $compareTo;
		$this->strict = $strict;
	}
    function validate($v){
		return $this->strict ? $v === $this->compareTo: $v == $this->compareTo;
    }
}