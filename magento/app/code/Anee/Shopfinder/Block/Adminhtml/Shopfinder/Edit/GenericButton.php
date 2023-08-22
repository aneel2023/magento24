<?php
declare(strict_types=1);
/**
 * Copyright © Shopfinder. All rights reserved.
 */
namespace Anee\Shopfinder\Block\Adminhtml\Shopfinder\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class GenericButton
{
    /** @var RequestInterface  */
    private $request;

    /** @var UrlInterface  */
    private $url;

    public function __construct(
        RequestInterface $request,
        UrlInterface $url
    ) {
        $this->request = $request;
        $this->url = $url;
    }

    /**
     * Return shop ID
     *
     * @return int
     */
    public function getShopId(): int
    {
        return (int)$this->request->getParam('entity_id');
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }
}
