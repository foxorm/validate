<?php
namespace FoxORM\Validate\Filters;
class HtmlFilterSimple extends HtmlFilter{
	protected $tags = ['br','p','a','strong','b','i','em','img','blockquote','code','dd','dl','hr','h1','h2','h3','h4','h5','h6','label','ul','li','span','sub','sup'];
	protected $map = [
		'img'=>['src','width','height','alt'],
		'a'=>['href','title'],
	];
}