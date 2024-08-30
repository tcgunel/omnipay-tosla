<?php

namespace Omnipay\Tosla\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tosla Abstract Response
 */
abstract class RemoteAbstractResponse extends AbstractResponse
{
    protected $response;

    protected $request;

    /**
     * @throws \JsonException
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        $this->response = $data;

        if ($data instanceof ResponseInterface) {

            $body = (string) $data->getBody();

            $this->response = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        }
    }
}
