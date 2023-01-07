<?php

namespace Abduraim\Predictor\Services;

use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Relations\Relation;

class HelperService
{
    /**
     * Пытается найти полиморфный алиас класса, если нет - возвращает исходный
     * 
     * @param string $class Проверяемый класс
     * @return string
     */
    public static function getPolymorphClassAliasIfExist(string $class)
    {
        // Проверям - возможно это уже alias, если да - то возвращаем его
        if (Relation::getMorphedModel($class)) {
            return $class;
        }

        // Пытаемся определить alias
        try {
            $alias = (new $class)->getMorphClass();
            return $alias;
        } catch (ClassMorphViolationException $exception) {
            // Если его нет - возвращаем исходное название класса
            return $class;
        }
    }
}