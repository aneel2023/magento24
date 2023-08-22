<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Model\Source;

use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Country implements OptionSourceInterface
{
    /**
     * @var CountryCollectionFactory
     */
    public $countryCollectionFactory;

    /** @var array */
    private $options = [];

    public function __construct(
        CountryCollectionFactory $countryCollectionFactory
    ) {
        $this->countryCollectionFactory = $countryCollectionFactory;
    }

    /**
     * get options as key value pair
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        if (count($this->options) == 0) {
            $this->options = $this->countryCollectionFactory->create()->toOptionArray(' ');
        }
        return $this->options;
    }
}
