<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\Proxy\Proxy;

abstract class AbstractEntity implements \ArrayAccess
{
    private $AnnotationReader;
    private $inflector;

    public function offsetExists(mixed $offset): bool
    {
        $inflector = $this->getInflector();

        $method = $inflector->classify($offset);

        return method_exists($this, $method)
            || method_exists($this, "get$method")
            || method_exists($this, "is$method")
            || method_exists($this, "has$method");
    }
    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetGet($offset)
    {
        $inflector = $this->getInflector();
        $method = $inflector->classify($offset);

        if (method_exists($this, $method)) {
            return $this->$method();
        } elseif (method_exists($this, "get$method")) {
            return $this->{"get$method"}();
        } elseif (method_exists($this, "is$method")) {
            return $this->{"is$method"}();
        } elseif (method_exists($this, "has$method")) {
            return $this->{"has$method"}();
        }
    }

    public function offsetUnset(mixed $offset): void
    {
    }


    public function getInflector(): Inflector
    {
        if ($this->inflector) {
            return $this->inflector;
        }
        $inflectorFactory = InflectorFactory::create();
        $this->inflector = $inflectorFactory->build();
        return $this->inflector;
    }

    /**
     * Set AnnotationReader.
     *
     * @param Reader $Reader
     *
     * @return AbstractEntity
     */
    public function setAnnotationReader(Reader $Reader)
    {
        $this->AnnotationReader = $Reader;

        return $this;
    }

    /**
     * Get AnnotationReader.
     *
     * @return Reader
     */
    public function getAnnotationReader()
    {
        if ($this->AnnotationReader) {
            return $this->AnnotationReader;
        }

        return new \Doctrine\Common\Annotations\AnnotationReader();
    }

    /**
     * 引数の連想配列を元にプロパティを設定します.
     * DBから取り出した連想配列を, プロパティへ設定する際に使用します.
     *
     * @param array $arrProps プロパティの情報を格納した連想配列
     * @param \ReflectionClass $parentClass 親のクラス. 本メソッドの内部的に使用します.
     * @param string[] $excludeAttribute 除外したいフィールド名の配列
     */
    public function setPropertiesFromArray(array $arrProps, array $excludeAttribute = [], \ReflectionClass $parentClass = null)
    {
        $objReflect = null;
        if (is_object($parentClass)) {
            $objReflect = $parentClass;
        } else {
            $objReflect = new \ReflectionClass($this);
        }
        $arrProperties = $objReflect->getProperties();
        foreach ($arrProperties as $objProperty) {
            $objProperty->setAccessible(true);
            $name = $objProperty->getName();
            if (in_array($name, $excludeAttribute) || !array_key_exists($name, $arrProps)) {
                continue;
            }
            $objProperty->setValue($this, $arrProps[$name]);
        }

        // 親クラスがある場合は再帰的にプロパティを取得
        $parentClass = $objReflect->getParentClass();
        if (is_object($parentClass)) {
            self::setPropertiesFromArray($arrProps, $excludeAttribute, $parentClass);
        }
    }

    public function toArray(array $excludeAttribute = ['__initializer__', '__cloner__', '__isInitialized__', 'AnnotationReader', 'inflector'], \ReflectionClass $parentClass = null)
    {
        $objReflect = null;
        if (is_object($parentClass)) {
            $objReflect = $parentClass;
        } else {
            $objReflect = new \ReflectionClass($this);
        }
        $arrProperties = $objReflect->getProperties();
        $arrResults = [];
        foreach ($arrProperties as $objProperty) {
            $objProperty->setAccessible(true);
            $name = $objProperty->getName();
            if (in_array($name, $excludeAttribute)) {
                continue;
            }
            $arrResults[$name] = $objProperty->getValue($this);
        }

        $parentClass = $objReflect->getParentClass();
        if (is_object($parentClass)) {
            $arrParents = self::toArray($excludeAttribute, $parentClass);
            if (!is_array($arrParents)) {
                $arrParents = [];
            }
            if (!is_array($arrResults)) {
                $arrResults = [];
            }
            $arrResults = array_merge($arrParents, $arrResults);
        }

        return $arrResults;
    }

    public function toNormalizedArray(array $excludeAttribute = ['__initializer__', '__cloner__', '__isInitialized__', 'AnnotationReader', 'inflector'])
    {
        $arrResult = $this->toArray($excludeAttribute);
        foreach ($arrResult as &$value) {
            if ($value instanceof \DateTime) {
                // see also https://stackoverflow.com/a/17390817/4956633
                $value->setTimezone(new \DateTimeZone('UTC'));
                $value = $value->format('Y-m-d\TH:i:s\Z');
            } elseif ($value instanceof AbstractEntity) {
                // Entity の場合は [id => value] の配列を返す
                $value = $this->getEntityIdentifierAsArray($value);
            } elseif ($value instanceof Collection) {
                // Collection の場合は ID を持つオブジェクトの配列を返す
                $Collections = $value;
                $value = [];
                foreach ($Collections as $Child) {
                    $value[] = $this->getEntityIdentifierAsArray($Child);
                }
            }
        }

        return $arrResult;
    }

    public function toJSON(array $excludeAttribute = ['__initializer__', '__cloner__', '__isInitialized__', 'AnnotationReader', 'inflector'])
    {
        return json_encode($this->toNormalizedArray($excludeAttribute));
    }

    public function getEntityIdentifierAsArray(AbstractEntity $Entity)
    {
        $Result = [];
        $PropReflect = new \ReflectionClass($Entity);
        if ($Entity instanceof Proxy) {
            // Doctrine Proxy の場合は親クラスを取得
            $PropReflect = $PropReflect->getParentClass();
        }
        $Properties = $PropReflect->getProperties();

        foreach ($Properties as $Property) {
            $anno = $this->getAnnotationReader()->getPropertyAnnotation($Property, Id::class);
            if ($anno) {
                $Property->setAccessible(true);
                $Result[$Property->getName()] = $Property->getValue($Entity);
            }
        }

        return $Result;
    }

    public function copyProperties($srcObject, array $excludeAttribute = [])
    {
        $this->setPropertiesFromArray($srcObject->toArray($excludeAttribute), $excludeAttribute);

        return $this;
    }

}
