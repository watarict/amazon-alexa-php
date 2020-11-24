<?php

namespace MaxBeckers\AmazonAlexa\Request\Request;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
abstract class AbstractRequest
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var \DateTime
     */
    public $timestamp;

    /**
     * @param array $amazonRequest
     *
     * @return AbstractRequest
     */
    abstract public static function fromAmazonRequest(array $amazonRequest): self;

    /**
     * @return bool
     */
    public function validateTimestamp(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function validateSignature(): bool
    {
        return !$GLOBALS['DISABLE_VALIDATOR'];
        //return true;
    }
}
