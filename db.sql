SET FOREIGN_KEY_CHECKS=0;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT 'account',
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `loginBy` varchar(255) DEFAULT 'email',
  `address` text DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_activated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `agent` text DEFAULT NULL,
  `host` varchar(255) DEFAULT NULL,
  `is_login` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_notification_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `translations` text DEFAULT NULL,
  `timezones` text DEFAULT NULL,
  `numeric_code` varchar(255) DEFAULT NULL,
  `iso3` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `capital` varchar(255) DEFAULT NULL,
  `tld` varchar(255) DEFAULT NULL,
  `native` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `currency_name` varchar(255) DEFAULT NULL,
  `currency_symbol` varchar(255) DEFAULT NULL,
  `wikiDataId` varchar(255) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `emoji` varchar(255) DEFAULT NULL,
  `emojiU` varchar(255) DEFAULT NULL,
  `flag` tinyint(1) DEFAULT 0,
  `is_activated` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_code_unique` (`code`),
  UNIQUE KEY `countries_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `translations` text DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `cities_country_id_foreign` (`country_id`),
  CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
CREATE TABLE `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `translations` text DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `areas_city_id_foreign` (`city_id`),
  CONSTRAINT `areas_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `for` varchar(255) DEFAULT 'posts',
  `type` varchar(255) DEFAULT 'category',
  `name` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `show_in_menu` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id`                  INT NOT NULL AUTO_INCREMENT,
    `category_id`         INT NULL,
    `type`                VARCHAR(255)    default 'product',
    `name`                text    not null,
    `slug`                VARCHAR(255) not null,
    `sku`                 VARCHAR(255),
    `barcode`             VARCHAR(255),
    `about`               text,
    `description`         text,
    `keywords`            text,
    `details`             text,
    `price`               double  not null,
    `discount`            double     default '0',
    `discount_to`         datetime,
    `vat`                 double     default '0',
    `is_activated`        tinyint(1) default '1',
    `is_in_stock`         tinyint(1) default '1',
    `is_shipped`          tinyint(1) default '0',
    `is_trend`            tinyint(1) default '0',
    `has_options`         tinyint(1) default '0',
    `has_multi_price`     tinyint(1) default '0',
    `has_unlimited_stock` tinyint(1) default '0',
    `has_max_cart`        tinyint(1) default '0',
    `min_cart`            INT,
    `max_cart`            INT,
    `has_stock_alert`     tinyint(1) default '0',
    `min_stock_alert`     INT,
    `max_stock_alert`     INT,
    `created_at`          datetime,
    `updated_at`          datetime,
    PRIMARY KEY (`id`),
    UNIQUE KEY `products_slug_unique` (`slug`),
    CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
    `id`         INT  NOT NULL AUTO_INCREMENT,
    `account_id` INT NULL,
    `product_id` INT NULL,
    `session_id` VARCHAR(255),
    `item`       VARCHAR(255) not null,
    `price`      double     default '0' not null,
    `discount`   double     default '0',
    `vat`        double     default '0',
    `qty`        double     default '0',
    `total`      double     default '0',
    `note`       text,
    `options`    text,
    `is_active`  tinyint(1) default '1',
    `created_at` datetime,
    `updated_at` datetime,
    PRIMARY KEY (`id`),
    KEY `carts_item_index` (`item`),
    CONSTRAINT `carts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Other tables without foreign keys first
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id`             VARCHAR(255) not null primary key,
    `name`           VARCHAR(255) not null,
    `total_jobs`     INT not null,
    `pending_jobs`   INT not null,
    `failed_jobs`    INT not null,
    `failed_job_ids` text    not null,
    `options`        text,
    `cancelled_at`   INT,
    `created_at`     INT not null,
    `finished_at`    INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dependent tables
--

DROP TABLE IF EXISTS `categories_metas`;
CREATE TABLE `categories_metas` (
    `id`          INT NOT NULL AUTO_INCREMENT,
    `model_id`    INT,
    `model_type`  VARCHAR(255),
    `category_id` INT not null,
    `key`         VARCHAR(255) not null,
    `value`       text,
    `created_at`  datetime,
    `updated_at`  datetime,
    PRIMARY KEY (`id`),
    KEY `categories_metas_key_index` (`key`),
    CONSTRAINT `categories_metas_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
    `id`           INT NOT NULL AUTO_INCREMENT,
    `parent_id`    INT,
    `user_id`      INT not null,
    `user_type`    VARCHAR(255) not null,
    `content_id`   INT not null,
    `content_type` VARCHAR(255) not null,
    `comment`      text    not null,
    `rate`         float      default '0',
    `is_active`    tinyint(1) default '1',
    `created_at`   datetime,
    `updated_at`   datetime,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
    `id`                  INT NOT NULL AUTO_INCREMENT,
    `country_id`          INT NULL,
    `name`                VARCHAR(255) not null,
    `ceo`                 VARCHAR(255),
    `address`             VARCHAR(255),
    `city`                VARCHAR(255),
    `zip`                 VARCHAR(255),
    `registration_number` VARCHAR(255),
    `tax_number`          VARCHAR(255),
    `email`               VARCHAR(255),
    `phone`               VARCHAR(255),
    `website`             VARCHAR(255),
    `notes`               text,
    `created_at`          datetime,
    `updated_at`          datetime,
    PRIMARY KEY (`id`),
    CONSTRAINT `companies_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
    `id`            INT NOT NULL AUTO_INCREMENT,
    `company_id`    INT not null,
    `name`          VARCHAR(255) not null,
    `phone`         VARCHAR(255),
    `branch_number` INT default '1',
    `address`       VARCHAR(255),
    `created_at`    datetime,
    `updated_at`    datetime,
    PRIMARY KEY (`id`),
    UNIQUE KEY `branches_name_unique` (`name`),
    CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
    `id`                        INT NOT NULL AUTO_INCREMENT,
    `code`                      VARCHAR(255) not null,
    `type`                      VARCHAR(255) default 'discount_coupon',
    `amount`                    double     default '0' not null,
    `is_limited`                tinyint(1) default '0',
    `end_at`                    date,
    `use_limit`                 INT    default '0',
    `use_limit_by_user`         INT    default '0',
    `order_total_limit`         INT    default '0',
    `is_activated`              tinyint(1) default '1',
    `is_marketing`              tinyint(1) default '0',
    `marketer_name`             VARCHAR(255),
    `marketer_type`             VARCHAR(255),
    `marketer_amount`           double,
    `marketer_amount_max`       double,
    `marketer_show_amount_max`  tinyint(1) default '0',
    `marketer_hide_total_sales` tinyint(1) default '0',
    `is_used`                   double     default '0',
    `apply_to`                  text,
    `except`                  text,
    `created_at`                datetime,
    `updated_at`                datetime,
    PRIMARY KEY (`id`),
    UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `shipping_vendors`;
CREATE TABLE `shipping_vendors` (
    `id`                  INT NOT NULL AUTO_INCREMENT,
    `name`                VARCHAR(255) not null,
    `contact_person`      VARCHAR(255),
    `delivery_estimation` VARCHAR(255),
    `phone`               VARCHAR(255),
    `address`             VARCHAR(255),
    `price`               double     default '0',
    `is_activated`        tinyint(1) default '0',
    `integration`         text,
    `created_at`          datetime,
    `updated_at`          datetime,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `deliveries`;
CREATE TABLE `deliveries` (
    `id`                 INT NOT NULL AUTO_INCREMENT,
    `shipping_vendor_id` INT NULL,
    `name`               VARCHAR(255) not null,
    `phone`              VARCHAR(255) not null,
    `address`            VARCHAR(255),
    `is_activated`       tinyint(1) default '0',
    `created_at`         datetime,
    `updated_at`         datetime,
    PRIMARY KEY (`id`),
    UNIQUE KEY `deliveries_phone_unique` (`phone`),
    CONSTRAINT `deliveries_shipping_vendor_id_foreign` FOREIGN KEY (`shipping_vendor_id`) REFERENCES `shipping_vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
    `id`           INT NOT NULL AUTO_INCREMENT,
    `model_type`   VARCHAR(255),
    `model_id`     INT,
    `street`       VARCHAR(255) not null,
    `area_id`      INT NULL,
    `city_id`      INT NULL,
    `country_id`   INT NULL,
    `home_number`  INT,
    `flat_number`  INT,
    `floor_number` INT,
    `mark`         VARCHAR(255),
    `map_url`      text,
    `note`         VARCHAR(255),
    `lat`          VARCHAR(255),
    `lng`          VARCHAR(255),
    `zip`          VARCHAR(255),
    `is_main`      tinyint(1) default '0',
    `created_at`   datetime,
    `updated_at`   datetime,
    PRIMARY KEY (`id`),
    CONSTRAINT `locations_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL,
    CONSTRAINT `locations_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
    CONSTRAINT `locations_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id`                 INT NOT NULL AUTO_INCREMENT,
    `uuid`               VARCHAR(255) not null,
    `type`               VARCHAR(255) default 'system',
    `user_id`            INT NULL,
    `country_id`         INT NULL,
    `area_id`            INT NULL,
    `city_id`            INT NULL,
    `address_id`         INT NULL,
    `account_id`         INT not null,
    `cashier_id`         INT NULL,
    `coupon_id`          INT NULL,
    `shipper_id`         INT NULL,
    `shipping_vendor_id` INT NULL,
    `company_id`         INT NULL,
    `branch_id`          INT NULL,
    `name`               VARCHAR(255),
    `phone`              VARCHAR(255),
    `flat`               VARCHAR(255),
    `address`            text,
    `source`             VARCHAR(255) default 'system' not null,
    `shipper_vendor`     VARCHAR(255),
    `total`              double     default '0',
    `discount`           double     default '0',
    `shipping`           double     default '0',
    `vat`                double     default '0',
    `status`             VARCHAR(255) default 'pending',
    `is_approved`        tinyint(1) default '0',
    `is_closed`          tinyint(1) default '0',
    `is_on_table`        tinyint(1) default '0',
    `table`              VARCHAR(255),
    `notes`              text,
    `has_returns`        tinyint(1) default '0',
    `return_total`       double     default '0',
    `reason`             VARCHAR(255),
    `is_payed`           tinyint(1) default '0',
    `payment_method`     VARCHAR(255),
    `payment_vendor`     VARCHAR(255),
    `payment_vendor_id`  VARCHAR(255),
    `created_at`         datetime,
    `updated_at`         datetime,
    PRIMARY KEY (`id`),
    UNIQUE KEY `orders_uuid_unique` (`uuid`),
    CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `orders_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_shipper_id_foreign` FOREIGN KEY (`shipper_id`) REFERENCES `deliveries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_shipping_vendor_id_foreign` FOREIGN KEY (`shipping_vendor_id`) REFERENCES `shipping_vendors` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
    CONSTRAINT `orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `orders_items`;
CREATE TABLE `orders_items` (
    `id`                INT NOT NULL AUTO_INCREMENT,
    `order_id`          INT not null,
    `refund_id`         INT,
    `warehouse_move_id` INT,
    `account_id`        INT not null,
    `product_id`        INT NULL,
    `item`              VARCHAR(255),
    `price`             double     default '0',
    `discount`          double     default '0',
    `vat`               double     default '0',
    `total`             double     default '0',
    `returned`          double     default '0',
    `qty`               double     default '1',
    `returned_qty`      double     default '0',
    `is_free`           tinyint(1) default '0',
    `is_returned`       tinyint(1) default '0',
    `options`           text,
    `created_at`        datetime,
    `updated_at`        datetime,
    `code`              VARCHAR(255),
    PRIMARY KEY (`id`),
    CONSTRAINT `orders_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `orders_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `orders_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_logs`;
CREATE TABLE `order_logs` (
    `id`         INT NOT NULL AUTO_INCREMENT,
    `user_id`    INT NULL,
    `order_id`   INT not null,
    `status`     VARCHAR(255)    default 'pending',
    `note`       text    not null,
    `is_closed`  tinyint(1) default '0',
    `created_at` datetime,
    `updated_at` datetime,
    PRIMARY KEY (`id`),
    CONSTRAINT `order_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `order_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_metas`;
CREATE TABLE `order_metas` (
    `id`         INT NOT NULL AUTO_INCREMENT,
    `order_id`   INT not null,
    `key`        VARCHAR(255) not null,
    `value`      text,
    `type`       VARCHAR(255) default 'text',
    `group`    VARCHAR(255) default 'general',
    `created_at` datetime,
    `updated_at` datetime,
    PRIMARY KEY (`id`),
    CONSTRAINT `order_metas_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (
    `id`         INT NOT NULL AUTO_INCREMENT,
    `account_id` INT not null,
    `product_id` INT not null,
    `created_at` datetime,
    `updated_at` datetime,
    PRIMARY KEY (`id`),
    CONSTRAINT `wishlists_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS=1;