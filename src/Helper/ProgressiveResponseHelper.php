<?php

namespace MaxBeckers\AmazonAlexa\Helper;

use MaxBeckers\AmazonAlexa\Request\Request;



use MaxBeckers\AmazonAlexa\Response\Card;
use MaxBeckers\AmazonAlexa\Response\Directives\Directive;
use MaxBeckers\AmazonAlexa\Response\OutputSpeech;
use MaxBeckers\AmazonAlexa\Response\Reprompt;
use MaxBeckers\AmazonAlexa\Response\Response;
use MaxBeckers\AmazonAlexa\Response\ResponseBody;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

/**
 * This helper class can invoke progressive response.
 *
 * @author ACO
 */
class ProgressiveResponseHelper
{
    /**
     * @vars for callback
     */
	private $apiAccessToken;
	private $requestId;
	private $apiEndpoint;
	public $logger;
    /**
     * @var ResponseBody
     */
    public $responseBody;

    /**
     * ProgressiveResponseHelper constructor creates a new progressive response object.
     */
    public function __construct(Request $request)
    {
        $this->apiAccessToken = $request->context->system->apiAccessToken;
		$this->requestId = $request->request->requestId;
		$this->apiEndpoint = $request->context->system->apiEndpoint;
    }

    /**
     * Call amazon service to start a progressive response.
     *
     * @param string $text (Text or SSML with optional audio tag limited to 30sec)
     *
     * @return bool
     */
    public function performProgressiveResponse(string $text)
    {
		// The data to send to the API
		$postData = array(
			'header' => array('requestId' => $this->requestId),
			'directive' => array(
				'type' => 'VoicePlayer.Speak',
				'speech' => $text
				)
		);
		$this->logger->debug("dati post preparati");
		// Setup cURL
		$ch = curl_init($this->apiEndpoint.'/v1/directives');
		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '.$this->apiAccessToken,
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => json_encode($postData)
		));
		$this->logger->debug("curl inizializzato con".$this->apiEndpoint."#".$this->apiAccessToken."#".$this->requestId."#".curl_getinfo($ch, CURLINFO_HTTP_CODE));
		// Send the request
		$response = curl_exec($ch);
		$this->logger->debug("curl eseguito".print_r($response,true)." code ".curl_getinfo($ch, CURLINFO_HTTP_CODE) );

		// Check HTTP status code
		if (!curl_errno($ch)) {
		  switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
			case 204:  # OK
				$this->logger->debug("codice ritornato:".$http_code);
				return true;
			  break;
			default:
				$this->logger->debug("codice ritornato:".$http_code);
			  return false;
		  }
		}
		// Decode the response
		//$responseData = json_decode($response, TRUE);
    }

}
