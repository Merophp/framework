<?php
namespace Merophp\Framework\Http\Events;

use Psr\Http\Message\ResponseInterface;

class BeforePrintingResponseBodyEvent
{

    /**
     * @var ResponseInterface
     */
	protected ResponseInterface $response;

    /**
     * @param ResponseInterface $response
     */
	public function __construct(ResponseInterface $response)
    {
		$this->response = $response;
	}

    /**
     * @return ResponseInterface
     */
	public function getResponse(): ResponseInterface
    {
	    return $this->response;
    }
}
