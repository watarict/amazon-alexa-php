<?php

namespace MaxBeckers\AmazonAlexa\Request\Custom;

use MaxBeckers\AmazonAlexa\Request\Custom\Request\AudioPlayer\PlaybackFailedRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\AudioPlayer\PlaybackFinishedRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\AudioPlayer\PlaybackNearlyFinishedRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\AudioPlayer\PlaybackStartedRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\AudioPlayer\PlaybackStoppedRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\CanFulfill\CanFulfillIntentRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\Display\ElementSelectedRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\GameEngine\InputHandlerEvent;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\PlaybackController\NextCommandIssued;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\PlaybackController\PauseCommandIssued;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\PlaybackController\PlayCommandIssued;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\PlaybackController\PreviousCommandIssued;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\Standard\IntentRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\Standard\LaunchRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\Standard\SessionEndedRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\System\ConnectionsResponseRequest;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\System\ExceptionEncounteredRequest;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
interface RequestTypes
{
    /**
     * List of all supported amazon request types.
     */
    const REQUEST_TYPES = [
        // Standard types
        IntentRequest::TYPE                 => IntentRequest::class,
        LaunchRequest::TYPE                 => LaunchRequest::class,
        SessionEndedRequest::TYPE           => SessionEndedRequest::class,
        // AudioPlayer types
        PlaybackStartedRequest::TYPE        => PlaybackStartedRequest::class,
        PlaybackNearlyFinishedRequest::TYPE => PlaybackNearlyFinishedRequest::class,
        PlaybackFinishedRequest::TYPE       => PlaybackFinishedRequest::class,
        PlaybackStoppedRequest::TYPE        => PlaybackStoppedRequest::class,
        PlaybackFailedRequest::TYPE         => PlaybackFailedRequest::class,
        // PlaybackController types
        NextCommandIssued::TYPE             => NextCommandIssued::class,
        PauseCommandIssued::TYPE            => PauseCommandIssued::class,
        PlayCommandIssued::TYPE             => PlayCommandIssued::class,
        PreviousCommandIssued::TYPE         => PreviousCommandIssued::class,
        // System types
        ExceptionEncounteredRequest::TYPE   => ExceptionEncounteredRequest::class,
        // Display types
        ElementSelectedRequest::TYPE        => ElementSelectedRequest::class,
        // Game engine types
        InputHandlerEvent::TYPE             => InputHandlerEvent::class,
        // can fulfill intent
        CanFulfillIntentRequest::TYPE       => CanFulfillIntentRequest::class,
        // Connections Response Request
        ConnectionsResponseRequest::TYPE    => ConnectionsResponseRequest::class,
    ];
}
