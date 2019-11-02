DROP DATABASE IF EXISTS estore;
CREATE DATABASE estore;
USE estore;

CREATE TABLE user (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    user_email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(25),
    user_password VARCHAR(255) NOT NULL,
    activation_code VARCHAR(255) NOT NULL DEFAULT '-1'
);

CREATE TABLE types (
    type_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(255) NOT NULL
);

CREATE TABLE brands (
    brand_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    brand VARCHAR(255) NOT NULL
);

CREATE TABLE item (
    item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type_id INT NOT NULL,
    brand_id INT NOT NULL,
    model VARCHAR(255) NOT NULL,
    price FLOAT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    post_date DATETIME NOT NULL DEFAULT NOW(),
    item_img VARCHAR(255) NOT NULL DEFAULT 'user_data/item_img/default_img.png',
    description VARCHAR(255),
    status ENUM('Available', 'Sold', 'Remaining') NOT NULL DEFAULT 'Available',
    FOREIGN KEY (user_id)
        REFERENCES user (user_id)
        ON DELETE CASCADE,
    FOREIGN KEY (type_id)
        REFERENCES types (type_id)
        ON DELETE CASCADE,
    FOREIGN KEY (brand_id)
        REFERENCES brands (brand_id)
        ON DELETE CASCADE
);

CREATE TABLE message (
    message_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    msg_text TEXT NOT NULL,
    msg_date DATETIME NOT NULL
);

CREATE TABLE user_messages (
    user_message_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    message_id INT NOT NULL,
    user_id INT NOT NULL,
    other_user_id INT NOT NULL,
    folder ENUM('Inbox', 'Sent') NOT NULL DEFAULT 'Inbox',
    unread BOOL NOT NULL DEFAULT TRUE,
    deleted ENUM('None', 'Deleted') NOT NULL DEFAULT 'None',
    FOREIGN KEY (message_id)
        REFERENCES message (message_id)
        ON DELETE CASCADE,
    FOREIGN KEY (user_id)
        REFERENCES user (user_id)
        ON DELETE CASCADE,
    FOREIGN KEY (other_user_id)
        REFERENCES user (user_id)
        ON DELETE CASCADE
);

INSERT INTO types (type) VALUES ('SMARTPHONE');
INSERT INTO types (type) VALUES ('LAPTOP');
INSERT INTO types (type) VALUES ('GAMING CONSOLE');
INSERT INTO types (type) VALUES ('OTHER');

INSERT INTO brands (brand) VALUES ('GOOGLE');
INSERT INTO brands (brand) VALUES ('LG');
INSERT INTO brands (brand) VALUES ('APPLE');
INSERT INTO brands (brand) VALUES ('HP');
INSERT INTO brands (brand) VALUES ('SONY');
INSERT INTO brands (brand) VALUES ('MICROSOFT');
INSERT INTO brands (brand) VALUES ('SAMSUNG');
INSERT INTO brands (brand) VALUES ('LENOVO');
INSERT INTO brands (brand) VALUES ('OTHER');


INSERT INTO user (first_name, last_name, user_email, phone_number, user_password, activation_code) VALUES ('R', 'W', 'raafatwahb@gmail.com', '70000000', 'c129b324aee662b04eccf68babba85851346dff9', '0');
INSERT INTO user (first_name, last_name, user_email, phone_number, user_password, activation_code) VALUES ('Y', 'Z', 'ytz00@mail.aub.edu', '71000000', 'c129b324aee662b04eccf68babba85851346dff9', '0');


INSERT INTO item (user_id, type_id, brand_id, model, description, price, item_img) VALUES (2, 1, 2, 'G2', 'Great Condition, Used For One Month Only', 200, 'user_data/item_img/1.png');
INSERT INTO item (user_id, type_id, brand_id, model, description, price, item_img) VALUES (2, 1, 3, 'IPHONE 5S', 'Great Condition, Super Clean', 250, 'user_data/item_img/2.png');


USE estore;
SELECT * FROM user;
