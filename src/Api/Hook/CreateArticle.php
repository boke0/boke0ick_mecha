<?php

namespace Boke0\Mechanism\Api\Hook;

abstract class AddArticle{
    abstract public function handle($path,$meta,$content);
}
