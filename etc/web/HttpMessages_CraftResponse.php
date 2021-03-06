<?php

namespace Craft;

use \Zend\Diactoros\Response;

class HttpMessages_CraftResponse extends Response
{
    /**
     * Headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Item
     *
     * @var array
     */
    protected $item;

    /**
     * Collection
     *
     * @var array
     */
    protected $collection;

    /**
     * Criteria
     *
     * @var ElementCriteriaModel
     */
    protected $criteria;

    /**
     * With Headers
     *
     * @param array $headers Headers
     *
     * @return Response Response
     */
    public function withHeaders(array $headers)
    {
        $new = clone $this;

        $new->headers = $headers;

        return $new;
    }

    /**
     * Get Collection
     *
     * @return array Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * With Collection
     *
     * @param array $collection Collection
     *
     * @return RestResponse RestResponse
     */
    public function withCollection(array $collection)
    {
        $new = clone $this;

        $new->collection = $collection;

        return $new;
    }

    /**
     * Get Item
     *
     * @return Item Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * With Item
     *
     * @param mixed $item Item
     *
     * @return RestResponse RestResponse
     */
    public function withItem($item)
    {
        $new = clone $this;

        $new->item = $item;

        return $new;
    }

    /**
     * Get Criteria
     *
     * @return ElementCriteriaModel Criteria
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * With Criteria
     *
     * @param ElementCriteriaModel $criteria Criteria
     *
     * @return RestResponse RestResponse
     */
    public function withCriteria(ElementCriteriaModel $criteria)
    {
        $new = clone $this;

        $new->criteria = $criteria;

        return $new;
    }

    /**
     * With Json
     *
     * @param string $json Json
     *
     * @return Response Response
     */
    public function withJson($json)
    {
        return $this->writeToBody($json, 'json');
    }

    /**
     * With Html
     *
     * @param string $html Html
     *
     * @return Response Response
     */
    public function withHtml($html)
    {
        return $this->writeToBody($html, 'html');
    }

    /**
     * With Xml
     *
     * @param string $xml Xml
     *
     * @return Response Response
     */
    public function withXml($xml)
    {
        return $this->writeToBody($xml, 'xml');
    }

    /**
     * Write To Body
     *
     * @param string $body Body
     * @param string $type Type
     *
     * @return Response Response
     */
    public function writeToBody($body, $type)
    {
        $headers = craft()->config->get('headers', 'HttpMessages');
        $new = $this->withHeaders(array_merge($this->headers, $headers[$type]));

        $new->getBody()->write($body);
        $new->getBody()->rewind();

        return $new;
    }

    /**
     * Send
     *
     * @return void
     */
    public function send()
    {
        $this->applyStatus();

        $this->applyHeaders();

        $this->end();
    }

    /**
     * Apply Status
     *
     * @return void
     */
    private function applyStatus()
    {
        $header = [
            null,
            sprintf('HTTP/%s %d %s', $this->getProtocolVersion(), $this->getStatusCode(), $this->getReasonPhrase())
        ];

        HeaderHelper::setHeader($header);
    }

    /**
     * Apply Headers
     *
     * @return void
     */
    private function applyHeaders()
    {
        $headers = [];

        foreach ($this->headers as $header => $values) {
            $headers[$header] = implode(', ', $values);
        }

        HeaderHelper::setHeader($headers);
    }

    /**
     * End
     *
     * @return void
     */
    private function end()
    {
        ob_start();

        echo $this->getBody()->getContents();

        craft()->end();
    }

}
