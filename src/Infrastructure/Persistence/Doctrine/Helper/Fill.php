<?php

namespace App\Infrastructure\Persistence\Doctrine\Helper;

trait Fill
{
    public function fill(array $data, bool $update = false)
    {
        foreach ($data as $column => $value) {
            $this->setColumnValueByName($column, $value);
        }

        if ($this->timestamp === true) {
            $this->setTimestamps($update);
        }
    }

    private function setColumnValueByName($column, $value)
    {
        $columnCamelCase = $this->strToCamelCase($column, false);
        $method = sprintf('set%s', ucfirst($columnCamelCase));

        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } elseif (property_exists($this, $columnCamelCase)) {
            $this->{$columnCamelCase} = $value;
        } elseif (property_exists($this, $column)) {
            $this->{$column} = $value;
        }
    }

    private function strToCamelCase($string, $capitalizeFirstCharacter = false): string
    {
        $str = ucwords($string, '_');
        $str = ucwords($string, '-');
        $str = preg_replace('/_+/', '', $str);
        $str = preg_replace('/-+/', '', $str);

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    private function setTimestamps(bool $update)
    {
        $this->setCreatedAt(new \DateTimeImmutable('now'));

        if ($update === true) {
            $this->setUpdatedAt(new \DateTimeImmutable('now'));
        }
    }
}
