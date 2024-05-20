CREATE DATABASE IF NOT EXISTS your_guardian;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    birthdate DATE,
    email VARCHAR(255),
    password VARCHAR(255),
    is_admin BOOLEAN,
    created_at DATE,
    updated_at DATE
);

CREATE TABLE IF NOT EXISTS wallets (
    id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    balance DECIMAL(10, 2),
    created_at DATE,
    updated_at DATE
);

CREATE TABLE IF NOT EXISTS transaction_categories (
    id BIGINT UNSIGNED,
    name VARCHAR(255),
    created_at DATE,
    updated_at DATE
);

CREATE TABLE IF NOT EXISTS transactions (
    id BIGINT UNSIGNED,
    wallet_id BIGINT UNSIGNED,
    transaction_category_id BIGINT UNSIGNED,
    amount DECIMAL(10, 2),
    type ENUM('income', 'expense'),
    created_at DATE,
    updated_at DATE
);

CREATE TABLE IF NOT EXISTS bills (
    id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    title VARCHAR(255),
    description TEXT,
    amount DECIMAL(10, 2),
    due_date DATE,
    status ENUM('pending', 'paid', 'overdue'),
    created_at DATE,
    updated_at DATE
);

CREATE TABLE IF NOT EXISTS notifications (
    id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    title VARCHAR(255),
    message VARCHAR(255),
    is_read BOOLEAN,
    created_at DATE,
    updated_at DATE
);

CREATE TABLE IF NOT EXISTS task_categories (
    id BIGINT UNSIGNED,
    name VARCHAR(255),
    created_at DATE,
    updated_at DATE
);

CREATE TABLE IF NOT EXISTS tasks (
    id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    task_category_id BIGINT UNSIGNED,
    title VARCHAR(255),
    description TEXT,
    due_date DATE,
    status ENUM('pending', 'completed', 'failed'),
    created_at DATE,
    updated_at DATE
);

ALTER TABLE users
ADD CONSTRAINT PRIMARY KEY (id),
ADD CONSTRAINT uk_users_email UNIQUE KEY (email);

ALTER TABLE wallets
ADD CONSTRAINT PRIMARY KEY (id),
ADD CONSTRAINT uk_wallets_user_id UNIQUE KEY (user_id),
ADD CONSTRAINT fk_wallets_user_id FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE transaction_categories
ADD CONSTRAINT PRIMARY KEY (id);

ALTER TABLE transactions
ADD CONSTRAINT PRIMARY KEY (id),
ADD CONSTRAINT fk_transactions_wallet_id FOREIGN KEY (wallet_id) REFERENCES wallets(id),
ADD CONSTRAINT fk_transactions_transaction_category_id FOREIGN KEY (transaction_category_id) REFERENCES transaction_categories(id);

ALTER TABLE bills
ADD CONSTRAINT PRIMARY KEY (id),
ADD CONSTRAINT fk_bills_user_id FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE notifications
ADD CONSTRAINT PRIMARY KEY (id),
ADD CONSTRAINT fk_notifications_user_id FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE task_categories
ADD CONSTRAINT PRIMARY KEY (id);

ALTER TABLE tasks
ADD CONSTRAINT PRIMARY KEY (id),
ADD CONSTRAINT fk_tasks_user_id FOREIGN KEY (user_id) REFERENCES users(id),
ADD CONSTRAINT fk_tasks_task_category_id FOREIGN KEY (task_category_id) REFERENCES task_categories(id);
