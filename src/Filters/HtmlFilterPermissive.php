<?php
namespace FoxORM\Validate\Filters;
use Particle\Filter\FilterRule;
class HtmlFilterPermissive extends HtmlFilter{
	protected $tags = ['a','abbr','acronym','address','applet','area','article','aside','audio','b','basefont','bdi','bdo','big','blockquote','br','button','canvas','caption','center','cite','code','col','colgroup','command','datalist','dd','del','details','dfn','dialog','dir','div','dl','dt','em','embed','fieldset','figcaption','figure','font','footer','form','head','header','h1','h2','h3','h4','h5','h6','hr','html','i','img','input','ins','kbd','keygen','label','legend','li','link','map','mark','menu','meta','meter','nav','object','ol','optgroup','option','output','p','param','pre','progress','q','rp','rt','ruby','s','samp','section','select','small','source','span','strike','strong','style','sub','summary','sup','table','tbody','td','textarea','tfoot','th','thead','time','title','tr','track','tt','u','ul','var','video','wbr'];
}