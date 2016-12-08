<?php
namespace FoxORM\Validate\Rules;
use Respect\Validation\Rules\AbstractRule;
class StringAlphaNumeric extends AbstractRule{
    function validate($v){
		return preg_match("/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i",$v)!==FALSE;
    }
}