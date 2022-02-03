<?php

namespace Merophp\Framework\Http\Cors;

/**
 * @author Robert Becker
 */
class CorsConfiguration
{
    /**
     * List of allowed HTTP methods
     * @var array
     */
    private array $allowedMethods = [];

    /**
     * List of allowed origins
     * @var array
     */
    private array $allowedOrigins = [];

    /**
     * List of allowed HTTP headers
     * @var array
     */
    private array $allowedHeaders = [];

    /**
     * Max Age
     * @var int
     */
    private int $maxAge = -1;

    /**
     * @param array $allowedMethods List of allowed HTTP methods
     */
    public function setAllowedMethods(array $allowedMethods): void
    {
        $this->allowedMethods = $allowedMethods;
    }

    /**
     * @param array $allowedOrigins List of allowed origins
     */
    public function setAllowedOrigins(array $allowedOrigins): void
    {
        $this->allowedOrigins = $allowedOrigins;
    }

    /**
     * @param array $allowedHeaders List of allowed headers
     */
    public function setAllowedHeaders(array $allowedHeaders): void
    {
        $this->allowedHeaders = $allowedHeaders;
    }

    /**
     * @param int $maxAge
     */
    public function setMaxAge(int $maxAge): void
    {
        $this->maxAge = $maxAge;
    }

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }

    /**
     * @return array
     */
    public function getAllowedOrigins(): array
    {
        return $this->allowedOrigins;
    }

    /**
     * @return array
     */
    public function getAllowedHeaders(): array
    {
        return $this->allowedHeaders;
    }

    /**
     * @return int
     */
    public function getMaxAge(): int
    {
        return $this->maxAge;
    }
}
