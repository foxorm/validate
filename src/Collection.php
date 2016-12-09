<?php
namespace FoxORM\Validate;
use FoxORM\Validate\StringParamTrait;
use Respect\Validation\Validator;
use Respect\Validation\Validatable;
use Respect\Validation\Exceptions\NestedValidationException;
use InvalidArgumentException;
class Collection{
	use StringParamTrait;
	protected $data;
	protected $rules = [];
	protected $errors = [];
	protected $validate;
	function __construct($rules = [], $data = [], Validate $validate=null){
		$this->addRules($rules);
		$this->data = $data;
		$this->validate = $validate?:new Validate();
	}
	function assert($data=null){
		if(!$data) $data = $this->data;
		$this->errors = [];
		foreach($this->validator as $k=>$validator){
			$value = $this->getValue($data,$k);
			try{
				$validator->assert($value);
			}
			catch(NestedValidationException $exception){
				$this->errors[] = $exception;
			}
		}
		return empty($this->errors);
	}
	function getErrors(){
		return $this->errors;
	}
	function check($data=null){
		if(!$data) $data = $this->data;
		foreach($this->validator as $k=>$validator){
			$value = $this->getValue($data,$k);
			$validator->check($value);
		}
		return true;
	}
	function validate($data=null){
		if(!$data) $data = $this->data;
		foreach($this->validator as $k=>$validator){
			$value = $this->getValue($data,$k);
			if(!$validator->validate($value)){
				return false;
			}
		}
		return true;
	}
	function getValue(){
		if(is_object($data)){
			return isset($data->$k)?$data->$k:null;
		}
		else{
			return isset($data[$k])?$data[$k]:null;
		}
	}
	function getRule($k){
		if(!isset($this->rules[$k])){
			$this->rules[$k] = new Validator();
		}
		return $this->rules[$k];
	}
	function addRules(array $rules){
		foreach($rules as $k=>$rule){
			$this->addRule($k,$rule);
		}
	}
	function addRule($k,$rules){
		$validator = $this->getRule($k);
		if(is_string($rules)){
			$rules = $this->extractStringParam($rules);
		}
		if(is_array($rules)){
			foreach($rules as $rule){
				$name = array_shift($rule);
				$validator->addRule($this->validate->buildRule($name, $rule));
			}
		}
		else if($rules instanceof Validatable){
			$validator->addRule($rules);
		}
		else{
			throw new InvalidArgumentException('Expected '.gettype($rules).', addRule accept rule-set string/array definition or single rule Validatable object');
		}
	}
	function __call($method, $arguments){
		$k = array_shift($arguments);
		return $this->getRule($k)->addRule($this->validate->buildRule($method, $arguments));
	}
}