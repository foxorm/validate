<?php
namespace FoxORM\Validate;
use FoxORM\Validate\Validate;
use FoxORM\Validate\StringParamTrait;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Factory;
use Respect\Validation\Exceptions\ComponentException;
class RuleSet extends AllOf{
	use StringParamTrait;
	protected $validate;
	function __construct(Validate $validate, $ruleSet=null){
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
	function optional($method, $arguments){
		return $this->__call('optional',[$this->buildRule($method, $arguments)]);
	}
	function addRuleSet($ruleSet){
		foreach($ruleSet as $key=>$rules){
			$method = strpos($key,'.')?'nestedKey':'key';
			if(is_string($rules)){
				$rules = $this->extractStringParamArray($rules);
			}
			$mandatory = false;
			foreach($rules as $ruleArray){
				if(is_string($ruleArray)){
					$ruleArray = $this->extractStringParam($ruleArray);
				}
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
}