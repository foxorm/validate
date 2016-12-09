<?php
namespace FoxORM\Validate;
use FoxORM\Validate\Ruler;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Factory;
use Respect\Validation\Exceptions\ComponentException;
class RuleSet extends AllOf{
	protected $validate;
	function __construct(Ruler $validate, $ruleSet=null){
		$this->validate = $validate;
		if($ruleSet){
			$this->addRuleSet($ruleSet);
		}
	}
	function __call($method, $arguments){
		return $this->addRule($this->buildRule($method, $arguments));
	}
	function buildRule($ruleSpec, $arguments = []){
        try {
            return $this->validate->getFactory()->rule($ruleSpec, $arguments);
        }
        catch (\Exception $exception) {
            throw new ComponentException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
	function not($method, $arguments){
		return $this->__call('not',[$this->buildRule($method, $arguments)]);
	}
	function addRuleSet($ruleSet){
		foreach($ruleSet as $key=>$rules){
			$method = strpos($key,'.')?'nestedKey':'key';
			if(is_string($rules)){
				$rules = $this->extractStringParamArray($rules);
			}
			$mandatory = false;
			foreach($rules as $i=>$ruleArray){
				if(is_string($ruleArray)){
					$rules[$i] = $ruleArray = $this->extractStringParam($ruleArray);
				}
				if($ruleArray==['required']){
					$mandatory = true;
					unset($rules[$i]);
				}
			}
			foreach($rules as $ruleArray){
				$name = array_shift($ruleArray);
				if(is_array($name)){
					foreach(array_reverse($name) as $nestedRule){
						$ruleObject = $this->buildRule($nestedRule, $ruleArray);
						$ruleArray = [$ruleObject];
					}
				}
				else{
					$ruleObject = $this->buildRule($name, $ruleArray);
				}
				$rule = $this->buildRule($method, [$key,$ruleObject,$mandatory]);
				$this->addRule($rule);
			}
		}
		return $this;
	}
	
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