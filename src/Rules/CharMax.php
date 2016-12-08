<?php
namespace FoxORM\Validate\Rules;
class CharMax extends CharEqual{
    function validate($v){
		return $this->length($v)<=$this->max;
    }
}