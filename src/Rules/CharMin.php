<?php
namespace FoxORM\Validate\Rules;
class CharMin extends CharEqual{
    function validate($v){
		return $this->length($v)>=$this->length;
    }
}