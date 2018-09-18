<?php
/**
 * Sirateck_Lemonway extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Sirateck
 * @package        Sirateck_Lemonway
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Lemonway module install script
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
$this->startSetup();
$sql[] = "CREATE TABLE IF NOT EXISTS {$this->getTable('sirateck_lemonway_moneyout')} (
    `moneyout_id` int(11) NOT NULL AUTO_INCREMENT,
	`wallet_id` varchar(255) NOT NULL,
	`customer_id` int(11) NOT NULL DEFAULT 0,
	`is_admin` tinyint(1) NOT NULL DEFAULT 0,
	`lw_iban_id` int(11) NOT NULL,
	`prev_bal` decimal(20,6) NOT NULL,
	`new_bal`  decimal(20,6) NOT NULL,
	`iban` varchar(34) NOT NULL,
	`amount_to_pay`  decimal(20,6) NOT NULL,
	`created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY  (`moneyout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sql[] = "CREATE TABLE IF NOT EXISTS {$this->getTable('sirateck_lemonway_iban')} (
    `iban_id` int(11) NOT NULL AUTO_INCREMENT,
	`lw_iban_id` int(11) NOT NULL,
	`customer_id` int(11) NOT NULL,
	`wallet_id` varchar(255) NOT NULL,
	`holder` varchar(100) NOT NULL,
	`iban` varchar(34) NOT NULL,
	`bic` varchar(50) NOT NULL DEFAULT '',
	`dom1` text NOT NULL DEFAULT '',
	`dom2` text NOT NULL DEFAULT '',
	`comment` text NOT NULL DEFAULT '',
	`status_id` int(2) DEFAULT NULL,
	`created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY  (`iban_id`),
	UNIQUE KEY (`lw_iban_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

foreach ($sql as $q){
	$this->run($q);
}


$this->endSetup();
