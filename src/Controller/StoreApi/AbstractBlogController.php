<?php

declare(strict_types=1);

namespace Sas\BlogModule\Controller\StoreApi;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

abstract class AbstractBlogController
{
    abstract public function getDecorated(): AbstractBlogController;

    abstract public function load(Criteria $criteria, SalesChannelContext $context): BlogControllerResponse;
}
