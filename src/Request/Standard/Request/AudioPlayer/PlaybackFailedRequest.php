<?php

namespace MaxBeckers\AmazonAlexa\Request\Standard\Request\AudioPlayer;

use MaxBeckers\AmazonAlexa\Request\Standard\Request\AbstractRequest;
use MaxBeckers\AmazonAlexa\Request\Standard\Request\Error;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
class PlaybackFailedRequest extends AudioPlayerRequest
{
    const TYPE = 'AudioPlayer.PlaybackFailed';

    /**
     * @var Error|null
     */
    public $error;

    /**
     * @var PlaybackState|null
     */
    public $currentPlaybackState;

    /**
     * {@inheritdoc}
     */
    public static function fromAmazonRequest(array $amazonRequest): AbstractRequest
    {
        $request = new self();

        $request->type                 = self::TYPE;
        $request->error                = isset($amazonRequest['error']) ? Error::fromAmazonRequest($amazonRequest['error']) : null;
        $request->currentPlaybackState = isset($amazonRequest['currentPlaybackState']) ? PlaybackState::fromAmazonRequest($amazonRequest['currentPlaybackState']) : null;
        $request->setRequestData($amazonRequest);

        return $request;
    }
}
