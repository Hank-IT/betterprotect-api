<?php

namespace App\Postfix;

abstract class PostfixLog
{
    public function search(string $pattern)
    {
        return $this->get()->filter(function($a) use($pattern)  {
            return preg_grep('/' . $pattern . '/i', $a);
        });
    }

    public abstract function get();
}
