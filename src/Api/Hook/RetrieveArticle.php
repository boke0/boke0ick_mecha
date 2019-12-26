<?php

namespace Boke0\Mechanism\Api\Hook;

abstract class RetrieveArticle{
    abstract public function handle($path,$meta,$content);
}
