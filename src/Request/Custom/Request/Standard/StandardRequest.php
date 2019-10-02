<?php

namespace MaxBeckers\AmazonAlexa\Request\Custom\Request\Standard;

use MaxBeckers\AmazonAlexa\Request\Custom\Request\AbstractRequest;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
abstract class StandardRequest extends AbstractRequest
{
    /**
     * @var string|null
     */
    public $token;

    /**
     * @var string
     */
    public $requestId;

    /**
     * @var string
     */
    public $locale;

    /**
     * @param array $amazonRequest
     */
    protected function setRequestData(array $amazonRequest)
    {
        $this->requestId = isset($amazonRequest['requestId']) ? $amazonRequest['requestId'] : null;
        if (isset($amazonRequest['timestamp'])) {
            //Workaround for amazon developer console sending unix timestamp
            try {
                $this->timestamp = new \DateTime($amazonRequest['timestamp']);
            } catch (\Exception $e) {
                $this->timestamp = (new \DateTime())->setTimestamp(intval($amazonRequest['timestamp'] / 1000));
            }
        } else {
            $this->timestamp = new \DateTime();
        }
        $this->locale = isset($amazonRequest['locale']) ? $amazonRequest['locale'] : null;
    }
}
