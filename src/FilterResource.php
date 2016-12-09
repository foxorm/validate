<?php
namespace FoxORM\Validate;
use BadMethodCallException;
use Particle\Filter\FilterResource as ParticleFilterResource;
use ReflectionClass;
class FilterResource extends ParticleFilterResource{
	function __call($method, $args){
		$filter = __NAMESPACE__.'\\Filters\\'.ucfirst($method);
		if(!class_exists($filter)){
			throw new BadMethodCallException("Called filter \"$filter\" doesn't exists");
		}
		$reflect = new ReflectionClass($filter);
		$rule = $reflect->newInstanceArgs($args);
		$this->addRule($rule);
	}
}