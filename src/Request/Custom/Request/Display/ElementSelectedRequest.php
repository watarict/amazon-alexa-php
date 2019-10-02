<?php

namespace MaxBeckers\AmazonAlexa\Request\Custom\Request\Display;

use MaxBeckers\AmazonAlexa\Request\Custom\Request\AbstractRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\Standard\StandardRequest;

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
