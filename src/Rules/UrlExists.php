<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class UrlExists extends AbstractRule{
    function validate($v){
		$v = str_replace(['http://','https://','ftp://'],'',strtolower($v));
		return function_exists('checkdnsrr')?checkdnsrr($v):gethostbyname($v)!=$v;
    }
}