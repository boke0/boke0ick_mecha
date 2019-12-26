<?php

namespace Boke0\Mechanism\Api\Hook;

abstract class UpdateArticle{
    abstract public function handle($path,$meta,$content);
}
