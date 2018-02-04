<?php

namespace Nilnice\MiniSms\Traits;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

trait RequestTrait
{
    /**
     * Send a get request.
     *
     * @param string $url
     * @param array  $query
     * @param array  $headers
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function get(string $url, array $query = [], array $headers = [])
    {
        return $this->request('get', $url, [
            'headers' => $headers,
            'query'   => $query,
        ]);
    }

    /**
     * Send a post request.
     *
     * @param string $url
     * @param array  $parameter
     * @param array  $headers
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function post(
        string $url,
        array $parameter = [],
        array $headers = []
    ) {
        return $this->request('post', $url, [
            'headers'     => $headers,
            'form_params' => $parameter,
        ]);
    }

    /**
     * Send a request.
     *
     * @param string $method
     * @param string $gateway
     * @param array  $options
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function request(
        string $method,
        string $gateway,
        array $options = []
    ) {
        $client = new Client($this->getOptions());
        $response = $client->{$method}($gateway, $options);

        return $this->jsonResponse($response);
    }

    /**
     * Return to the Guzzle base options.
     *
     * @return array
     */
    protected function getOptions() : array
    {
        $baseuri = method_exists($this, 'getBaseUri') ? $this->getBaseUri : '';
        $timeout = property_exists($this, 'timeout') ? $this->timeout : 10;
        $options = [
            'base_uri' => $baseuri,
            'timeout'  => $timeout,
        ];

        return $options;
    }

    /**
     * Decodes a json/javascript/xml response contents.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    protected function jsonResponse(ResponseInterface $response)
    {
        $type = $response->getHeaderLine('Content-Type');
        $content = $response->getBody()->getContents();

        if (false !== self::contains($type, 'json')) {
            $content = json_decode($content, true);
        }

        return $content;
    }

    /**
     * Find the position of the first occurrence of a substring in a string.
     *
     * @param string $needle
     * @param string $haystack
     * @param bool   $isStrict
     *
     * @return bool|int
     */
    private static function contains(
        string $haystack,
        string $needle,
        bool $isStrict = false
    ) {
        return $isStrict
            ? strpos($haystack, $needle)
            : stripos($haystack, $needle);
    }
}
