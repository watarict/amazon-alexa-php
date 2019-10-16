<?php

namespace MaxBeckers\AmazonAlexa\Request\Custom;

use MaxBeckers\AmazonAlexa\Exception\MissingRequestDataException;
use MaxBeckers\AmazonAlexa\Exception\MissingRequiredHeaderException;
use MaxBeckers\AmazonAlexa\Request\Custom\Request\AbstractRequest;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
class Request
{
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
        $this->version = isset($amazonRequest['version']) ? $amazonRequest['version'] : null;
        $this->session = isset($amazonRequest['session']) ? Session::fromAmazonRequest($amazonRequest['session']) : null;
        $this->context = isset($amazonRequest['context']) ? Context::fromAmazonRequest($amazonRequest['context']) : null;
        if (!isset($amazonRequest['request']['type']) ||
            !isset(RequestTypes::REQUEST_TYPES[$amazonRequest['request']['type']])) {
            throw new MissingRequestDataException();
        }
        $this->request = (RequestTypes::REQUEST_TYPES[$amazonRequest['request']['type']])::fromAmazonRequest($amazonRequest['request']);
    }
}
