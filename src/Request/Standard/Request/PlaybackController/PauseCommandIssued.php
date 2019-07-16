<?php

namespace MaxBeckers\AmazonAlexa\Request\Standard\Request\PlaybackController;

use MaxBeckers\AmazonAlexa\Request\Standard\Request\AbstractRequest;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
class PauseCommandIssued extends AbstractPlaybackController
{
    const TYPE = 'PlaybackController.PauseCommandIssued';

    /**
     * {@inheritdoc}
     */
    public static function fromAmazonRequest(array $amazonRequest): AbstractRequest
    {
        $nextCommandIssued = new self();

        $nextCommandIssued->type = self::TYPE;

        $nextCommandIssued->setRequestData($amazonRequest);

        return $nextCommandIssued;
    }
}
