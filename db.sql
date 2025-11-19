SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `accounts`
(
    id                     INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    type                   VARCHAR(255)    default 'account',
    parent_id              INT,
    name                   VARCHAR(255),
    username               VARCHAR(255)                not null,
    email                  VARCHAR(255),
    phone                  VARCHAR(255),
    loginBy                VARCHAR(255)    default 'email',
    address                text,
    lang                   VARCHAR(255),
    password               VARCHAR(255),
    otp_code               VARCHAR(255),
    otp_activated_at       datetime,
    last_login             datetime,
    agent                  text,
    host                   VARCHAR(255),
    is_login               TINYINT(1) DEFAULT 0,
    is_active              TINYINT(1) DEFAULT 0 not null,
    is_notification_active TINYINT(1) DEFAULT 1 not null,
    deleted_at             datetime,
    created_at             datetime,
    updated_at             datetime
);

create unique index accounts_username_unique
    on accounts (username);

CREATE TABLE `cache`
(
    `key`        VARCHAR(255) not null
        primary key,
    value      text    not null,
    expiration INT not null
);

CREATE TABLE `cache_locks`
(
    `key`        VARCHAR(255) not null
        primary key,
    owner      VARCHAR(255) not null,
    expiration INT not null
);

CREATE TABLE `categories`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    parent_id    INT
        REFERENCES `categories`(id) (id)
            on delete cascade,
    `for`          VARCHAR(255)    default 'posts',
    type         VARCHAR(255)    default 'category',
    name         text    not null,
    slug         VARCHAR(255) not null,
    description  text,
    icon         VARCHAR(255),
    color        VARCHAR(255),
    is_active    TINYINT(1) DEFAULT 1,
    show_in_menu TINYINT(1) DEFAULT 0,
    created_at   datetime,
    updated_at   datetime,
    deleted_at   datetime
);

create unique index categories_slug_unique
    on categories (slug);

CREATE TABLE `categories_metas`
(
    id          INT not null
        PRIMARY KEY AUTO_INCREMENT,
    model_id    INT,
    model_type  VARCHAR(255),
    category_id INT not null
        REFERENCES `categories`(id) (id)
            on delete cascade,
    `key`         VARCHAR(255) not null,
    value       text,
    created_at  datetime,
    updated_at  datetime
);

create index categories_metas_key_index
    on `categories_metas` (`key`);

CREATE TABLE `comments`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    parent_id    INT,
    user_id      INT not null,
    user_type    VARCHAR(255) not null,
    content_id   INT not null,
    content_type VARCHAR(255) not null,
    comment      text    not null,
    rate         float      default '0',
    is_active    TINYINT(1) DEFAULT 1,
    created_at   datetime,
    updated_at   datetime
);

CREATE TABLE `countries`
(
    id              INT not null
        PRIMARY KEY AUTO_INCREMENT,
    name            VARCHAR(255) not null,
    code            VARCHAR(255) not null,
    phone           VARCHAR(255) not null,
    created_at      datetime,
    updated_at      datetime,
    translations    text,
    timezones       text,
    numeric_code    VARCHAR(255),
    iso3            VARCHAR(255),
    nationality     VARCHAR(255),
    capital         VARCHAR(255),
    tld             VARCHAR(255),
    native          VARCHAR(255),
    region          VARCHAR(255),
    currency        VARCHAR(255),
    currency_name   VARCHAR(255),
    currency_symbol VARCHAR(255),
    wikiDataId      VARCHAR(255),
    lat             numeric,
    lng             numeric,
    emoji           VARCHAR(255),
    emojiU          VARCHAR(255),
    flag            TINYINT(1) DEFAULT 0,
    is_activated    TINYINT(1) DEFAULT 1
);

CREATE TABLE `cities`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    name         VARCHAR(255) not null,
    country_id   INT not null
        REFERENCES `countries`(id) (id)
            on delete cascade,
    created_at   datetime,
    updated_at   datetime,
    translations text,
    timezone     VARCHAR(255),
    lat          numeric,
    lng          numeric,
    is_activated TINYINT(1) DEFAULT 1
);

CREATE TABLE `areas`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    name         VARCHAR(255) not null,
    city_id      INT not null
        REFERENCES `cities`(id) (id)
            on delete cascade,
    created_at   datetime,
    updated_at   datetime,
    translations text,
    is_activated TINYINT(1) DEFAULT 1
);

create index areas_name_index
    on areas (name);

create index cities_name_index
    on cities (name);

CREATE TABLE `companies`
(
    id                  INT not null
        PRIMARY KEY AUTO_INCREMENT,
    country_id          INT
        REFERENCES `countries`(id) (id),
    name                VARCHAR(255) not null,
    ceo                 VARCHAR(255),
    address             VARCHAR(255),
    city                VARCHAR(255),
    zip                 VARCHAR(255),
    registration_number VARCHAR(255),
    tax_number          VARCHAR(255),
    email               VARCHAR(255),
    phone               VARCHAR(255),
    website             VARCHAR(255),
    notes               text,
    created_at          datetime,
    updated_at          datetime
);

CREATE TABLE `branches`
(
    id            INT not null
        PRIMARY KEY AUTO_INCREMENT,
    company_id    INT not null
        REFERENCES `companies`(id) (id),
    name          VARCHAR(255) not null,
    phone         VARCHAR(255),
    branch_number INT default '1',
    address       VARCHAR(255),
    created_at    datetime,
    updated_at    datetime
);

create unique index branches_name_unique
    on branches (name);

create unique index countries_code_unique
    on countries (code);

create index countries_name_index
    on countries (name);

create unique index countries_name_unique
    on countries (name);

CREATE TABLE `coupons`
(
    id                        INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    code                      VARCHAR(255)                not null,
    type                      VARCHAR(255)    default 'discount_coupon',
    amount                    double     default '0' not null,
    is_limited                TINYINT(1) DEFAULT 0,
    end_at                    date,
    use_limit                 INT    default '0',
    use_limit_by_user         INT    default '0',
    order_total_limit         INT    default '0',
    is_activated              TINYINT(1) DEFAULT 1,
    is_marketing              TINYINT(1) DEFAULT 0,
    marketer_name             VARCHAR(255),
    marketer_type             VARCHAR(255),
    marketer_amount           double,
    marketer_amount_max       double,
    marketer_show_amount_max  TINYINT(1) DEFAULT 0,
    marketer_hide_total_sales TINYINT(1) DEFAULT 0,
    is_used                   double     default '0',
    apply_to                  text,
    `except`                  text,
    created_at                datetime,
    updated_at                datetime
);

create unique index coupons_code_unique
    on coupons (code);

CREATE TABLE `currencies`
(
    id            INT not null
        PRIMARY KEY AUTO_INCREMENT,
    arabic        VARCHAR(255),
    name          VARCHAR(255) not null,
    iso           VARCHAR(255) not null,
    created_at    datetime,
    updated_at    datetime,
    exchange_rate double,
    symbol        VARCHAR(255),
    translations  text,
    is_activated  TINYINT(1) DEFAULT 1
);

create index currencies_name_index
    on currencies (name);

CREATE TABLE `failed_jobs`
(
    id         INT                            not null
        PRIMARY KEY AUTO_INCREMENT,
    uuid       VARCHAR(255)                            not null,
    connection text                               not null,
    queue      text                               not null,
    payload    text                               not null,
    exception  text                               not null,
    failed_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP not null
);

create unique index failed_jobs_uuid_unique
    on failed_jobs (uuid);

CREATE TABLE `gift_cards`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    account_id   INT
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    name         VARCHAR(255) not null,
    code         VARCHAR(255) not null,
    balance      double     default '0',
    currency     VARCHAR(255)    default 'USD',
    is_activated TINYINT(1) DEFAULT 0,
    is_expired   TINYINT(1) DEFAULT 0,
    created_at   datetime,
    updated_at   datetime
);

create unique index gift_cards_code_unique
    on gift_cards (code);

CREATE TABLE `job_batches`
(
    id             VARCHAR(255) not null
        primary key,
    name           VARCHAR(255) not null,
    total_jobs     INT not null,
    pending_jobs   INT not null,
    failed_jobs    INT not null,
    failed_job_ids text    not null,
    options        text,
    cancelled_at   INT,
    created_at     INT not null,
    finished_at    INT
);

CREATE TABLE `jobs`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    queue        VARCHAR(255) not null,
    payload      text    not null,
    attempts     INT not null,
    reserved_at  INT,
    available_at INT not null,
    created_at   INT not null
);

create index jobs_queue_index
    on jobs (queue);

CREATE TABLE `languages`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    iso          VARCHAR(255) not null,
    name         VARCHAR(255) not null,
    arabic       VARCHAR(255),
    created_at   datetime,
    updated_at   datetime,
    translations text,
    is_activated TINYINT(1) DEFAULT 1
);

create unique index languages_iso_unique
    on languages (iso);

create index languages_name_index
    on languages (name);

CREATE TABLE `locations`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    model_type   VARCHAR(255),
    model_id     INT,
    street       VARCHAR(255) not null,
    area_id      INT
        REFERENCES `areas`(id) (id)
            on delete cascade,
    city_id      INT
        REFERENCES `cities`(id) (id)
            on delete cascade,
    country_id   INT
        REFERENCES `countries`(id) (id)
            on delete cascade,
    home_number  INT,
    flat_number  INT,
    floor_number INT,
    mark         VARCHAR(255),
    map_url      text,
    note         VARCHAR(255),
    lat          VARCHAR(255),
    lng          VARCHAR(255),
    zip          VARCHAR(255),
    is_main      TINYINT(1) DEFAULT 0,
    created_at   datetime,
    updated_at   datetime
);

CREATE TABLE `media`
(
    id                    INT not null
        PRIMARY KEY AUTO_INCREMENT,
    model_type            VARCHAR(255) not null,
    model_id              INT not null,
    uuid                  VARCHAR(255),
    collection_name       VARCHAR(255) not null,
    name                  VARCHAR(255) not null,
    file_name             VARCHAR(255) not null,
    mime_type             VARCHAR(255),
    disk                  VARCHAR(255) not null,
    conversions_disk      VARCHAR(255),
    size                  INT not null,
    manipulations         text    not null,
    custom_properties     text    not null,
    generated_conversions text    not null,
    responsive_images     text    not null,
    order_column          INT,
    created_at            datetime,
    updated_at            datetime
);

create index media_model_type_model_id_index
    on media (model_type, model_id);

create index media_order_column_index
    on media (order_column);

create unique index media_uuid_unique
    on media (uuid);

CREATE TABLE `migrations`
(
    id        INT not null
        PRIMARY KEY AUTO_INCREMENT,
    migration VARCHAR(255) not null,
    batch     INT not null
);

CREATE TABLE `password_reset_tokens`
(
    email      VARCHAR(255) not null
        primary key,
    token      VARCHAR(255) not null,
    created_at datetime
);

CREATE TABLE `posts`
(
    id                INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    author_id         INT,
    author_type       VARCHAR(255),
    type              VARCHAR(255)    default 'post',
    title             text                   not null,
    slug              VARCHAR(255)                not null,
    short_description text,
    keywords          text,
    body              text,
    is_published      TINYINT(1) DEFAULT 0 not null,
    is_trend          TINYINT(1) DEFAULT 0 not null,
    published_at      datetime,
    likes             double     default '0' not null,
    views             double     default '0' not null,
    meta_url          VARCHAR(255),
    meta              text,
    meta_redirect     text,
    created_at        datetime,
    updated_at        datetime,
    deleted_at        datetime
);

CREATE TABLE `post_metas`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    model_id   INT,
    model_type VARCHAR(255),
    post_id    INT not null
        REFERENCES `posts`(id) (id)
            on delete cascade,
    `key`        VARCHAR(255) not null,
    value      text,
    created_at datetime,
    updated_at datetime
);

create index post_metas_key_index
    on `post_metas` (`key`);

create unique index posts_slug_unique
    on posts (slug);

CREATE TABLE `posts_has_category`
(
    post_id     INT not null
        REFERENCES `posts`(id) (id)
            on delete cascade,
    category_id INT not null
        REFERENCES `categories`(id) (id)
            on delete cascade
);

CREATE TABLE `posts_has_tags`
(
    post_id INT not null
        REFERENCES `posts`(id) (id)
            on delete cascade,
    tag_id  INT not null
        REFERENCES `categories`(id) (id)
            on delete cascade
);

CREATE TABLE `products`
(
    id                  INT not null
        PRIMARY KEY AUTO_INCREMENT,
    category_id         INT
        REFERENCES `categories`(id) (id)
            on delete cascade,
    type                VARCHAR(255)    default 'product',
    name                text    not null,
    slug                VARCHAR(255) not null,
    sku                 VARCHAR(255),
    barcode             VARCHAR(255),
    about               text,
    description         text,
    keywords            text,
    details             text,
    price               double  not null,
    discount            double     default '0',
    discount_to         datetime,
    vat                 double     default '0',
    is_activated        TINYINT(1) DEFAULT 1,
    is_in_stock         TINYINT(1) DEFAULT 1,
    is_shipped          TINYINT(1) DEFAULT 0,
    is_trend            TINYINT(1) DEFAULT 0,
    has_options         TINYINT(1) DEFAULT 0,
    has_multi_price     TINYINT(1) DEFAULT 0,
    has_unlimited_stock TINYINT(1) DEFAULT 0,
    has_max_cart        TINYINT(1) DEFAULT 0,
    min_cart            INT,
    max_cart            INT,
    has_stock_alert     TINYINT(1) DEFAULT 0,
    min_stock_alert     INT,
    max_stock_alert     INT,
    created_at          datetime,
    updated_at          datetime
);

CREATE TABLE `carts`
(
    id         INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    account_id INT
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    product_id INT
        REFERENCES `products`(id) (id),
    session_id VARCHAR(255),
    item       VARCHAR(255)                not null,
    price      double     default '0' not null,
    discount   double     default '0',
    vat        double     default '0',
    qty        double     default '0',
    total      double     default '0',
    note       text,
    options    text,
    is_active  TINYINT(1) DEFAULT 1,
    created_at datetime,
    updated_at datetime
);

create index carts_item_index
    on carts (item);

CREATE TABLE `codes`
(
    id         INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    product_id INT                not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    code       VARCHAR(255)                not null,
    is_used    TINYINT(1) DEFAULT 0 not null,
    used_at    datetime,
    expires_at datetime,
    created_at datetime,
    updated_at datetime
);

CREATE TABLE `comparisons`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    user_id      INT,
    user_type    VARCHAR(255),
    product_id   INT not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    compare_with text,
    created_at   datetime,
    updated_at   datetime
);

CREATE TABLE `downloads`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    account_id INT not null
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    product_id INT not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    created_at datetime,
    updated_at datetime
);

CREATE TABLE `product_has_categories`
(
    category_id INT not null
        REFERENCES `categories`(id) (id)
            on delete cascade,
    product_id  INT not null
        REFERENCES `products`(id) (id)
);

CREATE TABLE `product_has_collection`
(
    product_id    INT not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    collection_id INT not null
        REFERENCES `products`(id) (id)
            on delete cascade
);

CREATE TABLE `product_has_tags`
(
    product_id INT not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    tag_id     INT not null
        REFERENCES `categories`(id) (id)
            on delete cascade
);

CREATE TABLE `product_metas`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    product_id INT not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    `key`        VARCHAR(255) not null,
    value      text,
    model_id   INT,
    model_type VARCHAR(255),
    created_at datetime,
    updated_at datetime
);

CREATE TABLE `product_reviews`
(
    id           INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    product_id   INT                not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    account_id   INT                not null
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    rate         double     default '0' not null,
    review       text,
    is_activated TINYINT(1) DEFAULT 1,
    created_at   datetime,
    updated_at   datetime
);

create unique index products_slug_unique
    on products (slug);

CREATE TABLE `referral_codes`
(
    id           INT not null
        PRIMARY KEY AUTO_INCREMENT,
    account_id   INT
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    name         VARCHAR(255) not null,
    code         VARCHAR(255) not null,
    counter      double     default '0',
    is_activated TINYINT(1) DEFAULT 0,
    is_public    TINYINT(1) DEFAULT 0,
    created_at   datetime,
    updated_at   datetime
);

create unique index referral_codes_code_unique
    on referral_codes (code);

CREATE TABLE `searches`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    search     VARCHAR(255) not null,
    count      INT default '0',
    created_at datetime,
    updated_at datetime
);

create unique index searches_search_unique
    on searches (search);

CREATE TABLE `sessions`
(
    id            VARCHAR(255) not null
        primary key,
    user_id       INT,
    ip_address    VARCHAR(255),
    user_agent    text,
    payload       text    not null,
    last_activity INT not null
);

create index sessions_last_activity_index
    on sessions (last_activity);

create index sessions_user_id_index
    on sessions (user_id);

CREATE TABLE `settings`
(
    id         INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    `group`    VARCHAR(255)                not null,
    name       VARCHAR(255)                not null,
    locked     TINYINT(1) DEFAULT 0 not null,
    payload    text                   not null,
    created_at datetime,
    updated_at datetime
);

create unique index settings_group_name_unique
    on settings (`group`, name);

CREATE TABLE `shipping_vendors`
(
    id                  INT not null
        PRIMARY KEY AUTO_INCREMENT,
    name                VARCHAR(255) not null,
    contact_person      VARCHAR(255),
    delivery_estimation VARCHAR(255),
    phone               VARCHAR(255),
    address             VARCHAR(255),
    price               double     default '0',
    is_activated        TINYINT(1) DEFAULT 0,
    integration         text,
    created_at          datetime,
    updated_at          datetime
);

CREATE TABLE `deliveries`
(
    id                 INT not null
        PRIMARY KEY AUTO_INCREMENT,
    shipping_vendor_id INT
        REFERENCES `shipping_vendors`(id) (id)
            on delete cascade,
    name               VARCHAR(255) not null,
    phone              VARCHAR(255) not null,
    address            VARCHAR(255),
    is_activated       TINYINT(1) DEFAULT 0,
    created_at         datetime,
    updated_at         datetime
);

create unique index deliveries_phone_unique
    on deliveries (phone);

CREATE TABLE `shipping_prices`
(
    id                 INT not null
        PRIMARY KEY AUTO_INCREMENT,
    shipping_vendor_id INT
        REFERENCES `shipping_vendors`(id) (id)
            on delete cascade,
    delivery_id        INT
        REFERENCES `deliveries`(id) (id)
            on delete cascade,
    type               VARCHAR(255) default 'delivery',
    country_id         INT
        REFERENCES `countries`(id) (id)
            on delete cascade,
    city_id            INT
        REFERENCES `cities`(id) (id)
            on delete cascade,
    area_id            INT
        REFERENCES `areas`(id) (id)
            on delete cascade,
    price              double  default '0',
    created_at         datetime,
    updated_at         datetime
);



CREATE TABLE `types`
(
    id           INT                not null
        PRIMARY KEY AUTO_INCREMENT,
    parent_id    INT
        REFERENCES `types`(id) (id)
            on delete cascade,
    model_type   VARCHAR(255),
    model_id     INT,
    `for`          VARCHAR(255)    default 'posts',
    type         VARCHAR(255)    default 'category',
    name         VARCHAR(255)                not null,
    `key`          VARCHAR(255)                not null,
    description  text,
    color        VARCHAR(255),
    icon         VARCHAR(255),
    is_activated TINYINT(1) DEFAULT 1,
    created_at   datetime,
    updated_at   datetime,
    `order`      INT    default '0' not null
);

CREATE TABLE `typables`
(
    type_id       INT not null
        REFERENCES `types`(id) (id)
            on delete cascade,
    typables_id   INT not null,
    typables_type VARCHAR(255) not null
);

CREATE TABLE `types_metas`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    model_id   INT,
    model_type VARCHAR(255),
    type_id    INT not null
        REFERENCES `types`(id) (id)
            on delete cascade,
    `key`        VARCHAR(255) not null,
    value      text,
    created_at datetime,
    updated_at datetime
);

create index types_metas_key_index
    on `types_metas` (`key`);

CREATE TABLE `users`
(
    id                INT not null
        PRIMARY KEY AUTO_INCREMENT,
    name              VARCHAR(255) not null,
    email             VARCHAR(255) not null,
    email_verified_at datetime,
    password          VARCHAR(255) not null,
    remember_token    VARCHAR(255),
    created_at        datetime,
    updated_at        datetime
);

CREATE TABLE `orders`
(
    id                 INT                     not null
        PRIMARY KEY AUTO_INCREMENT,
    uuid               VARCHAR(255)                     not null,
    type               VARCHAR(255)    default 'system',
    user_id            INT
        REFERENCES `users`(id) (id)
            on delete cascade,
    country_id         INT
        REFERENCES `countries`(id) (id)
            on delete cascade,
    area_id            INT
        REFERENCES `areas`(id) (id)
            on delete cascade,
    city_id            INT
        REFERENCES `cities`(id) (id)
            on delete cascade,
    address_id         INT
        REFERENCES `locations`(id) (id)
            on delete cascade,
    account_id         INT                     not null
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    cashier_id         INT
        REFERENCES `users`(id) (id)
            on delete cascade,
    coupon_id          INT
        REFERENCES `coupons`(id) (id)
            on delete cascade,
    shipper_id         INT
        REFERENCES `deliveries`(id) (id)
            on delete cascade,
    shipping_vendor_id INT
        REFERENCES `shipping_vendors`(id) (id)
            on delete cascade,
    company_id         INT
        REFERENCES `companies`(id) (id)
            on delete cascade,
    branch_id          INT
        REFERENCES `branches`(id) (id)
            on delete cascade,
    name               VARCHAR(255),
    phone              VARCHAR(255),
    flat               VARCHAR(255),
    address            text,
    source             VARCHAR(255)    default 'system' not null,
    shipper_vendor     VARCHAR(255),
    total              double     default '0',
    discount           double     default '0',
    shipping           double     default '0',
    vat                double     default '0',
    status             VARCHAR(255)    default 'pending',
    is_approved        TINYINT(1) DEFAULT 0,
    is_closed          TINYINT(1) DEFAULT 0,
    is_on_.table        TINYINT(1) DEFAULT 0,
    `table`            VARCHAR(255),
    notes              text,
    has_returns        TINYINT(1) DEFAULT 0,
    return_total       double     default '0',
    reason             VARCHAR(255),
    is_payed           TINYINT(1) DEFAULT 0,
    payment_method     VARCHAR(255),
    payment_vendor     VARCHAR(255),
    payment_vendor_id  VARCHAR(255),
    created_at         datetime,
    updated_at         datetime
);

CREATE TABLE `order_logs`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    user_id    INT
        REFERENCES `users`(id) (id)
            on delete cascade,
    order_id   INT not null
        REFERENCES `orders`(id) (id)
            on delete cascade,
    status     VARCHAR(255)    default 'pending',
    note       text    not null,
    is_closed  TINYINT(1) DEFAULT 0,
    created_at datetime,
    updated_at datetime
);

CREATE TABLE `order_metas`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    order_id   INT not null
        REFERENCES `orders`(id) (id)
            on delete cascade,
    `key`        VARCHAR(255) not null,
    value      text,
    type       VARCHAR(255) default 'text',
    `group`    VARCHAR(255) default 'general',
    created_at datetime,
    updated_at datetime
);

create unique index orders_uuid_unique
    on orders (uuid);

CREATE TABLE `orders_items`
(
    id                INT not null
        PRIMARY KEY AUTO_INCREMENT,
    order_id          INT not null
        REFERENCES `orders`(id) (id)
            on delete cascade,
    refund_id         INT,
    warehouse_move_id INT,
    account_id        INT not null
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    product_id        INT
        REFERENCES `products`(id) (id)
            on delete cascade,
    item              VARCHAR(255),
    price             double     default '0',
    discount          double     default '0',
    vat               double     default '0',
    total             double     default '0',
    returned          double     default '0',
    qty               double     default '1',
    returned_qty      double     default '0',
    is_free           TINYINT(1) DEFAULT 0,
    is_returned       TINYINT(1) DEFAULT 0,
    options           text,
    created_at        datetime,
    updated_at        datetime,
    code              VARCHAR(255)
);

create unique index users_email_unique
    on users (email);

CREATE TABLE `wishlists`
(
    id         INT not null
        PRIMARY KEY AUTO_INCREMENT,
    account_id INT not null
        REFERENCES `accounts`(id) (id)
            on delete cascade,
    product_id INT not null
        REFERENCES `products`(id) (id)
            on delete cascade,
    created_at datetime,
    updated_at datetime
);

SET FOREIGN_KEY_CHECKS=1;
