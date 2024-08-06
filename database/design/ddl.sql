CREATE DATABASE IF NOT EXISTS your_guardian;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    email VARCHAR(255) NOT NULL,
    language_preference ENUM('en', 'pt_BR') DEFAULT 'en',
    index_view_preference ENUM('cards', 'table') DEFAULT 'cards',
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transaction_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    user_id BIGINT UNSIGNED ON DELETE CASCADE NOT NULL,
    bill_id BIGINT UNSIGNED ON DELETE CASCADE,
    transaction_category_id BIGINT UNSIGNED ON DELETE SET NULL,
    amount DECIMAL(11, 2) NOT NULL,
    type ENUM('income', 'expense') DEFAULT 'expense',
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bills (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    user_id BIGINT UNSIGNED ON DELETE CASCADE NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(11, 2) NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('pending', 'paid', 'overdue') DEFAULT 'pending',
    paid_at DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS task_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    transaction_type ENUM('income', 'expense') DEFAULT 'expense',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    user_id BIGINT UNSIGNED ON DELETE CASCADE NOT NULL,
    task_category_id BIGINT UNSIGNED ON DELETE SET NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
ADD CONSTRAINT fk_transactions_user_id FOREIGN KEY (user_id) REFERENCES users(id),
ADD CONSTRAINT uk_transactions_bill_id UNIQUE KEY (bill_id),
ADD CONSTRAINT fk_transactions_bill_id FOREIGN KEY (bill_id) REFERENCES bills(id),
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
