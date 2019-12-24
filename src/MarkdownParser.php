<?php

namespace Boke0\Mechanism;
use \ParsedownExtra;

class MarkdownParser implements \Mni\FrontYAML\Markdown\MarkdownParser{
    public function __construct(ParsedownExtra $parsedown=NULL){
        $this->parser=$parsedown?:new ParsedownExtra();
    }
    public function parse($text){
        return $this->parser->text($text);
    }
}
