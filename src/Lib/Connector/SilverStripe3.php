<?php

namespace Codeception\Lib\Connector;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;

class SilverStripe3 extends Client {

    /**
     * @param Request $request
     * @return string
     */
    protected function doRequest($request)
    {
        codecept_debug($request);
        $response = \Director::test(
            $request->getUri(),
            $request->getParameters(),
            null,
            $request->getMethod(),
            $request->getContent(),
            [],
            $request->getCookies()
        );
        codecept_debug($response);
        return $this->SSResponseToResponse($response);
    }

    /**
     * @param \SS_HTTPResponse $response
     * @return Response
     */
    protected function SSResponseToResponse($response) {
        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }

}
