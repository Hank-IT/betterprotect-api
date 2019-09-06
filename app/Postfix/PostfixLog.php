<?php

namespace App\Postfix;

abstract class PostfixLog
{
    public function search(string $pattern)
    {
        $data = $this->get();

        return array_filter($data, function($a) use($pattern)  {
            return preg_grep('/' . $pattern . '/i', $a);
        });
    }

    public abstract function get();
}
