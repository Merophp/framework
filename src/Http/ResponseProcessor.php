<?php
namespace Merophp\Framework\Http;

use Psr\Http\Message\ResponseInterface;

use Merophp\Framework\EventManagement\EventManagerAwareInterface;
use Merophp\Framework\EventManagement\EventManagerTrait;
use Merophp\Framework\Http\Events\BeforePrintingResponseBodyEvent;

/**
 * @author Robert Becker
 */
class ResponseProcessor implements EventManagerAwareInterface
{
    use EventManagerTrait;

    const OUTPUT_MODE_DIE = 'die';
    const OUTPUT_MODE_PRINT = 'print';

	protected string $outputMode = self::OUTPUT_MODE_DIE;

    /**
     * Sets the output method (
     *      ResponseProcessor::OUTPUT_MODE_DIE => use the 'die' function
     *      ResponseProcessor::OUTPUT_MODE_PRINT => use the 'print' function
     * )
     * @param string $outputMode
     * @api
     */
	public function setOutputMode(string $outputMode)
    {
        $this->outputMode = $outputMode;
    }

	/**
	 * Generates a HTTP response from a response object.
     *
     * @param ResponseInterface $response
	 */
	public function process(ResponseInterface $response)
    {
        $this->sendRawHeader($response);

        $this->eventManager->dispatch(
            new BeforePrintingResponseBodyEvent($response)
        );

        $this->sendRawBody($response);
	}

    /**
     * @param ResponseInterface $response
     */
    private function sendRawHeader(ResponseInterface $response)
    {
        header(sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));

        foreach($response->getHeaders() as $headerKey => $headerValue){
            header($headerKey.':'.$response->getHeaderLine($headerKey));
        }
    }

    /**
     * @param ResponseInterface $response
     */
    private function sendRawBody(ResponseInterface $response)
    {
        $responseBodyContent = '';

        if($response->getBody()){
            $response->getBody()->rewind();
            $responseBodyContent = $response->getBody()->getContents();
        }

        if($this->outputMode === self::OUTPUT_MODE_PRINT)
            print $responseBodyContent;
        else
            die($responseBodyContent);
    }
}
