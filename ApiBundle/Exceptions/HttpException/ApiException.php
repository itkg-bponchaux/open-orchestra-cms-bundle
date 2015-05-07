<?php

namespace OpenOrchestra\ApiBundle\Exceptions\HttpException;

use OpenOrchestra\ApiBundle\Exceptions\ApiException as BaseApiAxception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ApiException
 *
 * @deprecated use the one from base-api-bundle, will be removed in 0.2.2
 */
abstract class ApiException extends BaseApiAxception implements HttpExceptionInterface
{
    protected $statusCode;
    protected $errorCode;
    protected $developerMessage;
    protected $humanMessage;

    /**
     * @param string      $statusCode
     * @param int         $errorCode
     * @param string      $developerMessage
     * @param string      $humanMessage
     */
    public function __construct($statusCode, $errorCode, $developerMessage, $humanMessage)
    {
        $this->statusCode        = $statusCode;
        $this->errorCode         = $errorCode;
        $this->developerMessage  = $developerMessage;
        $this->humanMessage      = $humanMessage;

        parent::__construct($developerMessage);
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getDeveloperMessage()
    {
        return $this->developerMessage;
    }

    /**
     * @return string
     */
    public function getHumanMessage()
    {
        return $this->humanMessage;
    }

    /**
     * Returns the status code.
     *
     * @return integer An HTTP response status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return array();
    }
}
