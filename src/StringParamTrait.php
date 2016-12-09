<?php
namespace FoxORM\Validate;
trait StringParamTrait{
	function extractStringParam($str){
		$x = explode('|',$str);
		$rules = [];
		foreach($x as $rule){
			$params = explode(':',$rule);
			$name = array_shift($params);
			$params = explode(',',implode(':',$params));
			array_unshift($params,$name);
			$rules[] = $params;
		}
		return $rules;
	}
}