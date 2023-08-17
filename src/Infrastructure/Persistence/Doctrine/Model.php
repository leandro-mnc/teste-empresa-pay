<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Infrastructure\Persistence\Doctrine\Helper\Fill;
use Doctrine\Inflector\InflectorFactory;

abstract class Model
{
    use Fill;

    protected string $primaryKey = 'id';

    protected bool $timestamp = true;

    protected array $fillable = [];

    public function toArray()
    {
        $inflector = InflectorFactory::create()->build();

        $methods = get_class_methods($this);
        $array = [];
        foreach ($methods as $methodName) {
            // remove methods with arguments
            $method = new \ReflectionMethod(static::class, $methodName);

            if ($method->getNumberOfParameters() > 0) {
                continue;
            }

            $matches = null;
            if (preg_match('/^get(.+)$/', $methodName, $matches) === false) {
                continue;
            }

            // beautify array keys
            $key = $inflector->tableize($matches[1]);
            // filter unwanted data
            if (in_array($key, $this->fillable) === false) {
                continue;
            }
            $array[$key] = call_user_func([$this, $methodName]);
        }
        return $array;
    }
}
