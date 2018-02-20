<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
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
		$this->tags = array_unique(array_merge($this->tags,$tags,array_keys($this->attrs)));
	}
	function filter($str){
		if(!isset($this->attrs['*'])){
			$this->attrs['*'] = [];
		}
		foreach($this->globalAttrs as $k=>$v){
			$this->attrs['*'][] = $v;
		}
		return static::htmlfilter($str, $this->attrs);
	}
	
	protected static function htmlfilter($htmlUserInput, $allowedAttributesByTag=[]){
	
		$allowedAttributesForAllTags = [];
		if(isset($allowedAttributesByTag['*'])){
			$allowedAttributesForAllTags = $allowedAttributesByTag['*'];
			unset($allowedAttributesByTag['*']);
		}
		
		
		$htmlUserInput = preg_replace('/<!--(.*)-->/Uis', '', $htmlUserInput); //remove comments
		$htmlUserInput = preg_replace("/^<!\[CDATA\[(.*)\]\]>$/s", '', $htmlUserInput); //remove CDATA
		
		$htmlLength = strlen($htmlUserInput);
		$securisedHtml = '';
		
		$state = 'PARSING';
		$charContainer = '';
		$quoteType = '';
		
		for($i=0;$i<$htmlLength;$i++){
			$currentChar = $htmlUserInput{$i};
			switch($currentChar){
				case '<':
					switch($state){
						case 'PARSING':
							$state = 'PARSING_OPENER';
						case 'PARSING_OPENER':
							$securisedHtml .= $charContainer;
							$charContainer = '';
						break;
						break;
						case 'ATTR_VALUE':
							$charContainer .= $currentChar;
						break;
						default:
							$securisedHtml .= $charContainer;
							$charContainer = '';
							$state = 'PARSING';
							$i+=-1;
						break;
					}
				break;
				case '=':
					switch($state){
						case 'PARSING_OPENER':
							if(!isset($htmlUserInput{$i+1}))
								break;
							$quote = $htmlUserInput{$i+1};
							$y = $i+2;
							$charContainer .= '='.$quote;
							while(($ch=$htmlUserInput{$y++})!=$quote){
								$charContainer .= $ch;
								if(!isset($htmlUserInput{$y+1}))
									break 2;
							}
							$charContainer .= $quote;
							$i = $y-1;
						break;
						default:
							$charContainer .= $currentChar;
						break;
					}
				break;
				case '"':
				case "'":
					switch($state){
						case 'PARSING_OPENER':
							$state = 'ATTR_VALUE';
							$quoteType = $currentChar;
						break;
						case 'ATTR_VALUE':
							if($quoteType==$currentChar)
								$state = 'PARSING_OPENER';
						break;
					}
					$charContainer .= $currentChar;
				break;
				case '>':
					switch($state){
						case 'PARSING_OPENER':						
							$state = 'PARSING';
						case 'PARSING':						
							$firstChar = isset($charContainer{0})?$charContainer{0}:'';
							$myAttributes = [];
							switch($firstChar){
								case '/':
									$tagName = substr($charContainer, 1);
									if(!isset($allowedAttributesByTag[$tagName]))
										break;
									$securisedHtml .= "</$tagName>";
								break;
								default:
									if ((strpos($charContainer, '"') !== false) || (strpos($charContainer, "'") !== false)){
										$tagName = '';
										for($y=0;$y<strlen($charContainer);$y++){
											$currentChar = $charContainer{$y};
											if (($currentChar == ' ') || ($currentChar == "\t") ||
												($currentChar == "\n") || ($currentChar == "\r") ||
												($currentChar == "\x0B")) {
												$myAttributes = static::parseAttributes(substr($charContainer, $y));
												break;
											}
											else
												$tagName .= $currentChar;
										}
										if(!isset($allowedAttributesByTag[$tagName]))
											break;
										
										$attrs = [];
										$myAttributes = static::filterAttributes($tagName, $myAttributes);
										foreach($myAttributes as $k=>$v){
											if(!in_array($k, $allowedAttributesByTag[$tagName])&&!in_array($k, $allowedAttributesForAllTags))
												continue;
											$attrs[] = $k.'="'.$v.'"';
										}
										$attrs = implode(' ',$attrs);
										
										$securisedHtml .= '<'.$tagName;
										if(!empty($attrs)){
											$securisedHtml .= ' '.$attrs;
										}
										if(strrpos($charContainer, '/')==(strlen($charContainer)-1)){
											$securisedHtml .= '/';
										}
										$securisedHtml .= '>';
									}
									else{
										if(strpos($charContainer,' ')!==false){
											$x = explode(' ',$charContainer);
											$charContainer = array_shift($x);
											foreach($x as $k)
												if($k=='/')
													$charContainer .= '/';
												else
													$myAttributes[] = $k;
										}
										
										$tagName = $charContainer;
										
										if(!isset($allowedAttributesByTag[$tagName]))
											break;
											
										$attrs = [];
										$myAttributes = static::filterAttributes($tagName, $myAttributes);
										foreach($myAttributes as $k=>$v){
											$attrs[] = $k.'='.$v;
										}
										$attrs = implode(' ',$attrs);
										$securisedHtml .= '<'.$tagName;
										if(!empty($attrs)){
											$securisedHtml .= ' '.$attrs;
										}
										if(strrpos($charContainer, '/')==(strlen($charContainer)-1)){
											$securisedHtml .= '/';
										}
										$securisedHtml .= '>';									}
								break;				
							}
							$charContainer = '';
						break;
						default:
							$charContainer .= $currentChar;
						break;
					}
				break;
				default:
					$charContainer .= $currentChar;
				break;
			}
		}
		$securisedHtml .= $charContainer;
		
		return $securisedHtml;
	}


	protected static function parseAttributes($attrText){
		$attrArray = [];
		$total = strlen($attrText);
		$keyDump = '';
		$valueDump = '';
		$currentState = 'ATTR_NONE';
		$quoteType = '';
		$keyDumpI = 0;
		for($i=0;$i<$total;$i++){	
			$currentChar = $attrText{$i};
			if($currentState=='ATTR_NONE'&&trim($currentChar))
				$currentState = 'ATTR_KEY';
			switch ($currentChar){
				case '=':
					if ($currentState == 'ATTR_VALUE')
						$valueDump .= $currentChar;
					else {
						$currentState = 'ATTR_VALUE';
						$quoteType = '';
					}
				break;
				case '"':
					if ($currentState == 'ATTR_VALUE') {
						if ($quoteType=='')
							$quoteType = '"';
						elseif ($quoteType == $currentChar) {
							$keyDump = trim($keyDump);
							$tValueDump = trim($valueDump);
							$attrArray[$keyDump] = $tValueDump||$tValueDump==='0'?$valueDump:'';
							$keyDump = $valueDump = $quoteType = '';
							$currentState = 'ATTR_NONE';
						}
						else
							$valueDump .= $currentChar;
					}
					else{
						$keyDump = $keyDumpI++;
						$valueDump = '';
						$currentState = 'ATTR_VALUE';
						$quoteType = '"';
					}
				break;
				case "'":
					if ($currentState == 'ATTR_VALUE') {
						if ($quoteType == '')
							$quoteType = "'";
						elseif ($quoteType == $currentChar){
							$keyDump = trim($keyDump);
							$tValueDump = trim($valueDump);
							$attrArray[$keyDump] = $tValueDump||$tValueDump==='0'?$valueDump:'';
							$keyDump = $valueDump = $quoteType = '';
							$currentState = 'ATTR_NONE';
						}
						else
							$valueDump .= $currentChar;
					}
					else{
						$keyDump = $keyDumpI++;
						$valueDump = '';
						$currentState = 'ATTR_VALUE';
						$quoteType = "'";
					}
				break;
				case "\t":
				case "\x0B":
				case "\n":
				case "\r":
				case ' ':
					if($currentState=='ATTR_KEY'){
						$currentState = 'ATTR_NONE';
						if($keyDump)
							$attrArray[] = trim($keyDump);
						$keyDump = $valueDump = $quoteType = '';
					}
					elseif($currentState=='ATTR_VALUE')
						$valueDump .= $currentChar;
				break;
				default:
					if ($currentState == 'ATTR_KEY')
						$keyDump .= $currentChar;
					else
						$valueDump .= $currentChar;
				break;
			}
		}
		if(trim($keyDump))
			$attrArray[] = trim($keyDump);
		return $attrArray;
	}

	protected static function filterAttributes($tag,$attrsInput){
		//see http://heideri.ch/jso/
		$attrs = [];
		foreach($attrsInput as $k=>$v){
			if(substr($k,0,2)=='on'){
				continue;
			}
			switch($k){
				case 'form':
				case 'formaction':
				case 'autofocus':
				case 'dirname':
					continue;
				break;
				case 'href':
				case 'poster':
				case 'xlink:href':
					if(substr(trim($v),0,11)=='javascript:'){
						continue 2;
					}				
				break;
			}
			switch($tag.'['.$k.']'){
				case 'link[rel]':
					if(trim($v)=='import'){
						continue 2;
					}
				break;
				case 'iframe[srcdoc]':
					continue 2;
				break;
			}
			$attrs[$k] = $v;
		}
		return $attrs;
	}

}
