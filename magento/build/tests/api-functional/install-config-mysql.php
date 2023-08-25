<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

return [
    'language' => 'en_US',
    'timezone' => 'Europe/Berlin',
    'currency' => 'EUR',
    'db-host' => '${APP_DB_HOST}',
    'db-user' => '${APP_DB_USER}',
    'db-password' => '${APP_DB_PASS}',
    'db-name' => '${APP_DB2_NAME}',
    'db-prefix' => '',
    'backend-frontname' => 'backend',
    'base-url' => 'http://localhost/',
    'use-secure' => '0',
    'use-rewrites' => '0',
    'admin-user' => \Magento\TestFramework\Bootstrap::ADMIN_NAME,
    'admin-password' => \Magento\TestFramework\Bootstrap::ADMIN_PASSWORD,
    'admin-email' => \Magento\TestFramework\Bootstrap::ADMIN_EMAIL,
    'admin-firstname' => \Magento\TestFramework\Bootstrap::ADMIN_FIRSTNAME,
    'admin-lastname' => \Magento\TestFramework\Bootstrap::ADMIN_LASTNAME,
    'admin-use-security-key' => '0',
    /* PayPal has limitation for order number - 20 characters. 10 digits prefix + 8 digits number is good enough */
    'sales-order-increment-prefix' => time(),
    'session-save' => 'db',
    'cleanup-database' => true,
];
