<?php
namespace FoxORM\Validate;
trait StringParamTrait{
	function extractStringParam($rule){
		if(strpos($rule,':')){
			$x2 = explode(':',$rule);
			$params = explode(',',array_pop($x2));
			$name = count($x2)==1?$x2[0]:$x2;
			array_unshift($params,$name);
			return $params;
		}
		else{
			return [$rule];
		}
	}
	function extractStringParamArray($str){
		$x = explode('|',$str);
		$rules = [];
		foreach($x as $rule){
			$rules[] = $this->extractStringParam($rule);
		}
		return $rules;
	}
}