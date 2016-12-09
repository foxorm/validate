<?php
namespace FoxORM\Validate;
use Respect\Validation\Factory;
use FoxORM\Validate\RuleSet;
class Validate{
	protected $factory;
	function __construct(){
		$this->factory = new Factory();
		$this->factory->prependRulePrefix(__NAMESPACE__.'\\Rules');
	}
	function __call($method, $arguments){
		return call_user_func_array([$this->createRule(),$method],$arguments);
	}
	function createRule($ruleSet=null){
		return new RuleSet($this,$ruleSet);
	}
	function getFactory(){
		return $this->factory;
	}
}