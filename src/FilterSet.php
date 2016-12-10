<?php
namespace FoxORM\Validate;
use Particle\Filter\Filter;
use FoxORM\Validate\FilterResource;
use FoxORM\Validate\StringParamTrait;
use ArrayAccess;
class FilterSet extends Filter{
	use StringParamTrait;
	function filterByReference($mixed){
		$results = $this->mixedFilter($mixed);
		$mixed = $this->recursiveArrayAssignObject($results,$mixed);
		return $mixed;
	}
	function mixedFilter($mixed){
		$mixed = $this->recursiveObjectToArray($mixed);
		return $this->filter($mixed);
	}
	private function recursiveArrayAssignObject($array,$mixed){
		foreach($array as $k=>$v){
			if(is_array($mixed)||$mixed instanceof ArrayAccess){
				$mixed[$k] = is_array($v)?$this->recursiveArrayAssignObject($v,$mixed[$k]):$v;
			}
			else{
				$mixed->$k = is_array($v)?$this->recursiveArrayAssignObject($v,$mixed->$k):$v;
			}
		}
		return $mixed;
	}
	private function recursiveObjectToArray($mixed){
		$array = [];
		foreach($mixed as $k=>$v){
			$array[$k] = is_object($v)?$this->recursiveObjectToArray($v):$v;
		}
		return $array;
	}
	function getFilterResource($keys = null){
        return new FilterResource($this, $keys);
    }
    function addFilter($keys, array $rule=[]){
		$rule = (array)$rule;
		$method = array_shift($rule);
		$filterResource = $this->getFilterResource($keys);
		call_user_func_array([$filterResource,$method],$rule);
	}
    function addFilters($filterSet){
		foreach($filterSet as $key=>$filters){
			if(is_string($filters)){
				$filters = $this->extractStringParamArray($filters);
			}
			foreach($filters as $i=>$filterArray){
				if(is_string($filterArray)){
					$filterArray = $this->extractStringParam($filterArray);
				}
				$this->addFilter($key=='*'?null:$key, $filterArray);
			}
		}
	}
}