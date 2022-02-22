<?php
namespace Roadmap\CustomizeCheckoutStep\Setup;

use Magento\Framework\DB\Ddl\Table;

class SchemaInformation
{
    const ATTRIBUTE_CUSTOMER_COMMENT = 'customer_comment';

    const ATTRIBUTE_ATTR = [
        'type' => Table::TYPE_TEXT,
        'comment' => 'Customer comment'
    ];
}
