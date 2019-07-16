<?php

namespace MaxBeckers\AmazonAlexa\Request\Standard\Request\Display;

use MaxBeckers\AmazonAlexa\Request\Standard\Request\AbstractRequest;
use MaxBeckers\AmazonAlexa\Request\Standard\Request\Standard\StandardRequest;

/**
 * @author Fabian Graßl <fabian.grassl@db-n.com>
 */
class ElementSelectedRequest extends StandardRequest
{
    const TYPE = 'Display.ElementSelected';

    /**
     * {@inheritdoc}
     */
    public static function fromAmazonRequest(array $amazonRequest): AbstractRequest
    {
        $request = new self();

        $request->type  = self::TYPE;
        $request->token = $amazonRequest['token'];
        $request->setRequestData($amazonRequest);

        return $request;
    }
}
