<?php

declare(strict_types=1);


namespace rollun\Entity\Usps;

/**
 *
 * */
class LabelData implements \ArrayAccess
{
    const FIELDS = [
        5 => 'FromFirstName',
        6 => 'FromLastName',
        7 => 'FromFirm',
        8 => 'FromAddress1',
        9 => 'FromAddress2',
        10 => 'FromCity',
        11 => 'FromState',
        12 => 'FromZip5',
        13 => 'FromZip4',
        14 => 'FromPhone',
        15 => 'ToLastName',
        16 => 'ToFirm',
        17 => 'ToAddress1',
        18 => 'ToAddress2',
        19 => 'ToCity',
        20 => 'ToState',
        21 => 'ToZip5',
        22 => 'ToZip4',
        23 => 'ToPhone',
        25 => 'WeightInOunces'
    ];

    const DEFAULT = [
        'FromFirstName' => 'NONAME',
        'FromLastName' => 'NOLASTNAME',
        'FromFirm' => 'NOFIRM',
        'FromAddress1' => 'NOADDRES1',
        'FromCity' => 'NOCITY',
        'FromState' => 'NOSTATE',
        'FromZip5' => 'NOZIP',
        'ToFirstName' => 'NONAME',
        'ToLastName' => 'NOLASTNAME',
        'ToFirm' => 'NOFIRM',
        'ToAddress1' => 'NOADDRES1',
        'ToCity' => 'NOCITY',
        'ToState' => 'NOSTATE',
        'ToZip5' => 'NOZIP',
        'WeightInOunces' => '1'
    ];

    public $data = [];

    public function __construct(array $data)
    {

        foreach (self::FIELDS as $key) {
            $this->data[$key] = array_key_exists($key, $data) ? $data[$key] :
                (array_key_exists($key, self::DEFAULT) ? self::DEFAULT[$key] : null);
        }

        /* TODO CHECK if fields is correct */
    }

    /**
     *
     * @param string|int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @param string|int $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @return  array
     * */
    public function toArray()
    {
        return $this->data;
    }
}