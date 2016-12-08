<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class Name extends AbstractRule{
    function validate($v){
		return preg_match("/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ '-])+$/i", $v)!==FALSE;
    }
}