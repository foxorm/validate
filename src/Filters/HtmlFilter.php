<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;

use lincanbin\WhiteHTMLFilter;

class HtmlFilter extends FilterRule{
	protected $tags = [];
	protected $globalAttrs = [];
	protected $attrs = [];
	function __construct($tags=[],$globalAttrs=[],$attrs=[],$preventJavascriptInjection=true){
		if(is_string($tags)){
			$tags = explode('+',$tags);
		}
		$this->globalAttrs = array_unique(array_merge($this->globalAttrs,$globalAttrs));
		$this->attrs = $attrs;
		foreach($tags as $tag){
			if(!isset($this->attrs[$tag])){
				$this->attrs[$tag] = [];
			}
		}
		$this->tags = array_unique(array_merge($this->tags,$tags,array_keys($this->attrs)));
		
		$this->whiteHtmlFilter = new WhiteHTMLFilter();
	}
	function filter($str){
		if(!isset($this->attrs['*'])){
			$this->attrs['*'] = [];
		}
		foreach($this->globalAttrs as $k=>$v){
			$this->attrs['*'][] = $v;
		}
		return $this->htmlfilter($str, $this->attrs);
	}
	
	function htmlfilter($htmlUserInput, $allowedAttributesByTag=[]){
	
		$allowedAttributesForAllTags = [];
		if(isset($allowedAttributesByTag['*'])){
			$allowedAttributesForAllTags = $allowedAttributesByTag['*'];
			unset($allowedAttributesByTag['*']);
		}
		
		$this->whiteHtmlFilter->config->removeAllAllowTag();
		$this->whiteHtmlFilter->config->modifyTagWhiteList($allowedAttributesByTag);
		$this->whiteHtmlFilter->config->WhiteListHtmlGlobalAttributes = $allowedAttributesForAllTags;
		
		$this->whiteHtmlFilter->loadHTML($htmlUserInput);
		$this->whiteHtmlFilter->clean();
		return $this->whiteHtmlFilter->outputHtml();
	}

}
