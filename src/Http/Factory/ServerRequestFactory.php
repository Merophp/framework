<?php
namespace Merophp\Framework\Http\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Shieldon\Psr7\ServerRequest;

final class ServerRequestFactory extends \Shieldon\Psr17\ServerRequestFactory
{
    public static function fromNew(): ServerRequestInterface
    {
        return new ServerRequest;
    }
}
