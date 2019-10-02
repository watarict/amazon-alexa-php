<?php

namespace MaxBeckers\AmazonAlexa\Request;

use MaxBeckers\AmazonAlexa\Exception\MissingRequestDataException;
use MaxBeckers\AmazonAlexa\Exception\MissingRequiredHeaderException;
use MaxBeckers\AmazonAlexa\Request\Custom\Context;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\AbstractRequest;
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
use MaxBeckers\AmazonAlexa\Request\Custom\Session;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
class Request
{
    /**
     * List of all supported amazon request types.
     */
    const STANDARD_REQUEST_TYPES = [
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

    /**
     * @var string|null
     */
    public $version;

    /**
     * @var Session|null
     */
    public $session;

    /**
     * @var Context|null
     */
    public $context;

    /**
     * @var AbstractRequest|null
     */
    public $request;

    /**
     * @var string
     */
    public $amazonRequestBody;

    /**
     * @var string
     */
    public $signatureCertChainUrl;

    /**
     * @var string
     */
    public $signature;

    /**
     * @param string $amazonRequestBody
     * @param string $signatureCertChainUrl
     * @param string $signature
     *
     * @throws MissingRequestDataException
     * @throws MissingRequiredHeaderException
     *
     * @return Request
     */
    public static function fromAmazonRequest(string $amazonRequestBody, string $signatureCertChainUrl, string $signature): self
    {
        $request = new self();

        $request->signatureCertChainUrl = $signatureCertChainUrl;
        $request->signature             = $signature;
        $request->amazonRequestBody     = $amazonRequestBody;
        $amazonRequest                  = (array) json_decode($amazonRequestBody, true);

        $request->setRequest($amazonRequest);

        if ($request->request->validateSignature()) {
            if (!$request->signatureCertChainUrl || !$request->signature) {
                throw new MissingRequiredHeaderException();
            }
        }

        return $request;
    }

    /**
     * @return string|null
     */
    public function getApplicationId()
    {
        // workaround for developer console
        if ($this->session && $this->session->application) {
            return $this->session->application->applicationId;
        } elseif ($this->context && ($system = $this->context->system) && $system->application) {
            return $system->application->applicationId;
        }

        return null;
    }

    /**
     * @param array $amazonRequest
     *
     * @throws MissingRequestDataException
     */
    private function setRequest(array $amazonRequest)
    {
        if (
            isset($amazonRequest['request']['type']) &&
            isset(static::STANDARD_REQUEST_TYPES[$amazonRequest['request']['type']])
        ) {
            $this->setStandardRequest($amazonRequest);
        } elseif (
            isset($amazonRequest['directive']['header']['namespace']) &&
            isset($amazonRequest['directive']['header']['name']) &&
            isset(static::STANDARD_REQUEST_TYPES[$amazonRequest['directive']['header']['namespace']][$amazonRequest['directive']['header']['name']])
        ) {
            $this->setSmartHomeRequest($amazonRequest);
        } else {
            throw new MissingRequestDataException();
        }
    }

    /**
     * @param array $amazonRequest
     */
    private function setStandardRequest(array $amazonRequest)
    {
        $this->version = isset($amazonRequest['version']) ? $amazonRequest['version'] : null;
        $this->session = isset($amazonRequest['session']) ? Session::fromAmazonRequest($amazonRequest['session']) : null;
        $this->context = isset($amazonRequest['context']) ? Context::fromAmazonRequest($amazonRequest['context']) : null;
        $this->request = (static::STANDARD_REQUEST_TYPES[$amazonRequest['request']['type']])::fromAmazonRequest($amazonRequest['request']);
    }

    /**
     * @param array $amazonRequest
     */
    private function setSmartHomeRequest(array $amazonRequest)
    {
        $this->request = (static::STANDARD_REQUEST_TYPES[$amazonRequest['directive']['header']['namespace']][$amazonRequest['directive']['header']['name']])::fromAmazonRequest($amazonRequest['request']);
    }
}
