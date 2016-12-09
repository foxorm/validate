<?php
namespace FoxORM\Validate;
trait StringParamTrait{
	function extractStringParam($str){
		$x = explode('|',$str);
		$rules = [];
		foreach($x as $rule){
			if(strpos($rule,':')){
				$x2 = explode(':',$rule);
				$params = explode(',',array_pop($x2));
				$name = count($params)==1?$x2[0]:$x2;
				array_unshift($params,$name);
				$rules[] = $params;
			}
			else{
				$rules[] = [$rule];
			}
		}
		return $rules;
	}
}