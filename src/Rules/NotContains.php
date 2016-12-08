<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\Contains;
class NotContains extends Contains{
    function validate($input){
		return !parent::validate($input);
    }
}