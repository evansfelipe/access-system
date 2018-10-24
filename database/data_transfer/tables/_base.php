<?php

class BaseClass
{
    protected function parsedString($string) {
        $copy = trim((string) $string);
        return strlen($copy) > 0 ? $copy : null;
    }

    public function __construct($mysql, $values) {
        $this->values = $values;
        $this->mysql  = $mysql;
        $this->now    = date('Y-m-d H:i:s');
    }

    public function scape($value) {
        $str = $this->parsedString($value);
        return $str !== null ? mysqli_real_escape_string($this->mysql, $str) : null;
    }
}
