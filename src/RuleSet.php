<?php
namespace FoxORM\Validate;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Factory;
use Respect\Validation\Exceptions\ComponentException;
class RuleSet extends AllOf{
	protected $factory;
	function __construct(Factory $factory){
		$this->factory = $factory;
	}
	function __call($method, $arguments){
		return $this->addRule($this->buildRule($method, $arguments));
	}
	function buildRule($ruleSpec, $arguments = []){
        try {
            return $this->factory->rule($ruleSpec, $arguments);
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
}