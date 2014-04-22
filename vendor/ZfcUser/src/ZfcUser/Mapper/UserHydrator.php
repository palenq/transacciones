<?php

namespace ZfcUser\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcUser\Entity\UserInterface as UserEntityInterface;

class UserHydrator extends ClassMethods
{
    /**
    * On exctract, these keys will be unset, if they are null
    *
    * See the bug #352 (https://github.com/ZF-Commons/ZfcUser/issues/352)
    *
    * This is important for PostgreSQL user, error was:
    * `SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "user_id" violates not-null constraint`
    *
    * @var array
    */
    protected $deleteAttributeOnExtractIfNull = [
      'user_id'
    ];

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof UserEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of ZfcUser\Entity\UserInterface');
        }
        /* @var $object UserInterface*/
        $data = parent::extract($object);
        $data = $this->mapField('id', 'usuario', $data);
        foreach ($this->deleteAttributeOnExtractIfNull as $field) {
            if (array_key_exists($field, $data)) {
                unset($data[$field]);
            }
        }
        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof UserEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of ZfcUser\Entity\UserInterface');
        }
        $data = $this->mapField('usuario', 'id', $data);
        return parent::hydrate($data, $object);
    }

    protected function mapField($keyFrom, $keyTo, array $array)
    {
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);
        return $array;
    }
}
