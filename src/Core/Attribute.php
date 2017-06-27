<?php
namespace IopenWechat\Core;

abstract class Attribute extends Collection
{
    /**
     * Attribute constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Set attribute.
     *
     * @param string $attribute
     * @param string $value
     *
     * @return Attribute
     */
    public function setAttribute($attribute, $value)
    {
        $this->set($attribute, $value);

        return $this;
    }

    /**
     * Get attribute.
     *
     * @param string $attribute
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($attribute, $default)
    {
        return $this->get($attribute, $default);
    }
}