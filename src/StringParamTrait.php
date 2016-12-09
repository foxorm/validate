<?php
namespace FoxORM\Validate;
trait StringParamTrait{
	protected function extractStringParam($rule){
		$params = explode(':',$rule);
		$name = array_shift($params);
		if(strpos($name,'(')){
			$name = $this->extractRecursiveNestedFunction($name);
		}
		array_unshift($params,$name);
		return $params;
	}
	protected function extractStringParamArray($str){
		$x = explode('|',$str);
		$rules = [];
		foreach($x as $rule){
			$rules[] = $this->extractStringParam($rule);
		}
		return $rules;
	}
	protected function extractRecursiveNestedFunction($str){
		$depth = 0;
		$len = strlen($str);
		$seq = [];
		for($i = 0; $i < $len; $i++){
			$char = $str[$i];
			switch($char){
				case '(':
					$depth++;
				break;
				case ')':
					$depth--;
				break;
				default:
					if(!isset($seq[$depth])){
						$seq[$depth] = '';
					}
					$seq[$depth] .= $char;
				break;
			}
		}
		return $seq;
	}
}