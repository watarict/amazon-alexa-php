<?php

namespace MaxBeckers\AmazonAlexa\Test\Request\Custom;

use MaxBeckers\AmazonAlexa\Request\Custom\Request\Standard\SessionEndedRequest;
use MaxBeckers\AmazonAlexa\Request\Request;
use PHPUnit\Framework\TestCase;

/**
 * @author Maximilian Beckers <beckers.maximilian@gmail.com>
 */
class SessionEndedRequestTest extends TestCase
{
    public function testSessionEndedRequestRequest()
    {
        $requestBody = file_get_contents(__DIR__.'/RequestData/sessionEnded.json');
        $request     = Request::fromAmazonRequest($requestBody, 'https://s3.amazonaws.com/echo.api/echo-api-cert.pem', 'signature');
        $this->assertInstanceOf(SessionEndedRequest::class, $request->request);
    }
}
