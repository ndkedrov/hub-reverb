<?php

namespace Laravel\Reverb\Servers\Reverb\Http;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/** Normalize Ratchet's integer protocol version at the PSR-7 boundary. */
final class HandshakeResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new class($code, [], null, '1.1', $reasonPhrase) extends Response
        {
            public function withHeader(string $name, $value): MessageInterface
            {
                if (strcasecmp($name, 'Sec-WebSocket-Version') === 0 && is_int($value)) {
                    $value = (string) $value;
                }

                return parent::withHeader($name, $value);
            }
        };
    }
}
