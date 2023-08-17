<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Infrastructure\Persistence\Doctrine\Helper\Fill;

abstract class Model
{
    use Fill;
    
    protected string $primaryKey = 'id';

    protected bool $timestamp = false;
    
    protected array $fillable = [];

    public function toArray()
    {
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
            $key = Inflector::tableize($matches[1]);
            // filter unwanted data
            if (in_array($key, $this->fillable) === false) {
                continue;
            }
            $array[$key] = call_user_func([$this, $methodName]);
        }
        return $array;
    }
}
