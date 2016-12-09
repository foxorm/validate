<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class HtmlFilter extends FilterRule{
	protected $tags = [];
	protected $globalAttrs = [];
	protected $attrs = [];
	function __construct($tags,$globalAttrs=[],$attrs=[]){
		$this->tags = array_unique(array_merge($this->tags,$tags,array_keys($this->attrs)));
		$this->globalAttrs = array_unique(array_merge($this->globalAttrs,$globalAttrs));
		$this->attrs = $attrs;
	}
	function filter($str){
		$total = strlen($str);
		$nstr = '';
		for($i=0;$i<$total;$i++){
			$c = $str{$i};
			if($c=='<'){
				$tag = '';
				while($c!='>'){
					$c = $str{$i};
					$tag .= $c;
					$i++;
					if($c=='='){
						$sep = '';
						while($sep!='"'&&$sep!="'"){
							$sep = $str{$i};
							if($sep!='"'&&$sep!="'"&&$sep!=' '){
								$sep = ' ';
								while($c!=$sep&&$c!='/'&&$c!='>'){
									$c = $str{$i};
									$tag .= $c;
									$i++;
								}
								break;
							}
							$i++;
						}
						if($sep!=' '){
							$tag .= $sep;
							while($c!=$sep){
								$c = $str{$i};
								$tag .= $c;
								$i++;
							}
							$i-=1;
						}
					}
				}
				$i-=1;
				$tag = substr($tag,1,-1);
				if(strpos($tag,'/')===0){
					if(in_array(substr($tag,1),$this->tags))
						$nstr .= "<$tag>";
				}
				else{
					$e = strrpos($tag,'/')===strlen($tag)-1?'/':'';
					if($e)
						$tag = substr($tag,0,-1);
					if(($pos=strpos($tag,' '))!==false){
						$attr = substr($tag,$pos+1);
						$tag = substr($tag,0,$pos);
					}
					else
						$attr = '';
					if(!in_array($tag,$this->tags))
						continue;
					$allowed = isset($this->attrs[$tag])?$this->attrs[$tag]:[];
					$x = explode(' ',$attr);
					$attr = '';
					foreach($x as $_x){
						$x2 = explode('=',$_x);
						$k = array_shift($x2);
						$v = array_shift($x2);
						$v = trim($v,'"');
						$v = trim($v,"'");
						if($v)
							$v = "=\"$v\"";
						$ok = false;
						if(($pos=strpos($k,'-'))!==false){
							$key = substr($k,0,$pos+1).'*';
							if(in_array($key,$allowed)||($this->globalAttrs&&in_array($key,$this->globalAttrs)))
								$ok = true;
						}
						if(in_array($k,$allowed)||($this->globalAttrs&&in_array($k,$this->globalAttrs)))
							$ok = true;
						if($ok)
							$attr .= ' '.$k.$v;
					}
					$nstr .= "<$tag$attr$e>";
				}
			}
			else
				$nstr .= $c;
		}
		return $nstr;
	}
}