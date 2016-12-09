<?php
namespace FoxORM\Validate;
use FoxORM\Validate\Ruler;
use FoxORM\Validate\Filter;
class Validate{
	protected $ruler;
	protected $filter;
	function __construct(){
		$this->ruler = new Ruler();
		$this->filter = new Filter();
	}
	function getRuler(){
		return $this->ruler;
	}
	function getFilter(){
		return $this->filter;
	}
}