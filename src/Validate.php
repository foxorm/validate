<?php
namespace FoxORM\Validate;
use Respect\Validation\Validator;
use Respect\Validation\Factory;
use Respect\Validation\Exceptions\ComponentException;
class Validate{
	protected $factory;
	function __construct(){
		$this->factory = new Factory();
		$this->factory->prependRulePrefix(__NAMESPACE__.'\\Rules');
	}
	function __call($method, $arguments){
		$validator = new Validator();
		return $validator->addRule($this->buildRule($ruleSpec, $arguments));
	}
	protected function buildRule($ruleSpec, $arguments = []){
        try {
            return $this->factory->rule($ruleSpec, $arguments);
        }
        catch (\Exception $exception) {
            throw new ComponentException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}