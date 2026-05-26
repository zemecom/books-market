SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS authassignment;
DROP TABLE IF EXISTS authitemchild;
DROP TABLE IF EXISTS authitem;
DROP TABLE IF EXISTS sms_notification_log;
DROP TABLE IF EXISTS author_subscription;
DROP TABLE IF EXISTS book_author;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  UNIQUE KEY ux_users_login (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE authors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  bio TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  KEY ix_authors_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  isbn VARCHAR(64) NOT NULL,
  publish_year INT NOT NULL,
  published_at DATE NOT NULL,
  cover_path VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  UNIQUE KEY ux_books_isbn (isbn),
  KEY ix_books_title (title),
  KEY ix_books_publish_year_title (publish_year, title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE book_author (
  book_id INT NOT NULL,
  author_id INT NOT NULL,
  PRIMARY KEY (book_id, author_id),
  KEY ix_book_author_author_id (author_id),
  CONSTRAINT fk_book_author_book FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_book_author_author FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE author_subscription (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT NOT NULL,
  phone VARCHAR(32) NOT NULL,
  phone_normalized VARCHAR(32) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  UNIQUE KEY ux_author_subscription_unique (author_id, phone_normalized),
  KEY ix_author_subscription_author_id_created_at (author_id, created_at),
  CONSTRAINT fk_author_subscription_author FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sms_notification_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  book_id INT NOT NULL,
  phone VARCHAR(32) NOT NULL,
  message TEXT NOT NULL,
  status VARCHAR(16) NOT NULL,
  error_text TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  KEY ix_sms_notification_log_created_at (created_at),
  CONSTRAINT fk_sms_notification_log_book FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE authitem (
  name VARCHAR(64) NOT NULL,
  type INT NOT NULL,
  description TEXT NULL,
  bizrule TEXT NULL,
  data TEXT NULL,
  PRIMARY KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE authitemchild (
  parent VARCHAR(64) NOT NULL,
  child VARCHAR(64) NOT NULL,
  PRIMARY KEY (parent, child),
  CONSTRAINT fk_authitemchild_parent FOREIGN KEY (parent) REFERENCES authitem (name) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_authitemchild_child FOREIGN KEY (child) REFERENCES authitem (name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE authassignment (
  itemname VARCHAR(64) NOT NULL,
  userid VARCHAR(64) NOT NULL,
  bizrule TEXT NULL,
  data TEXT NULL,
  PRIMARY KEY (itemname, userid),
  CONSTRAINT fk_authassignment_itemname FOREIGN KEY (itemname) REFERENCES authitem (name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (id, login, password_hash, created_at, updated_at) VALUES
  (1, 'admin', '$2y$12$gS98KOi8lTcOqUjISsFcq.KeoFbut9lGFn3IMksqgwxUd2J7WHjCy', '2026-05-26 00:00:00', '2026-05-26 00:00:00'),
  (2, 'user', '$2y$12$YaCS.VNIbiNDtjbjz9NYbON86fn8DHpiBk8hKISdHhE2i5JStS.G2', '2026-05-26 00:00:00', '2026-05-26 00:00:00');

INSERT INTO authitem (name, type, description, bizrule, data) VALUES
  ('user', 2, 'User', NULL, NULL),
  ('admin', 2, 'Administrator', NULL, NULL);

INSERT INTO authitemchild (parent, child) VALUES
  ('admin', 'user');

INSERT INTO authassignment (itemname, userid, bizrule, data) VALUES
  ('admin', '1', NULL, NULL),
  ('user', '2', NULL, NULL);

INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES
  (1, 'Eric Evans', 'DDD pioneer', '2026-05-26 00:00:00', '2026-05-26 00:00:00'),
  (2, 'Martin Fowler', 'Architecture writer', '2026-05-26 00:00:00', '2026-05-26 00:00:00'),
  (3, 'Robert C. Martin', 'Clean Code author', '2026-05-26 00:00:00', '2026-05-26 00:00:00'),
  (4, 'Kent Beck', 'TDD pioneer', '2026-05-26 00:00:00', '2026-05-26 00:00:00');

INSERT INTO books (id, title, description, isbn, publish_year, published_at, cover_path, created_at, updated_at) VALUES
  (1, 'Strategic Design', 'Catalog seed book', '978-0-321-12521-7', 2026, '2026-01-10', NULL, '2026-05-26 00:00:00', '2026-05-26 00:00:00'),
  (2, 'Refactoring Workflows', 'Joint authorship sample', '978-0-201-48567-7', 2026, '2026-03-12', NULL, '2026-05-26 00:00:00', '2026-05-26 00:00:00'),
  (3, 'Clean Architecture', 'A Craftsman''s Guide to Software Structure and Design', '978-0-13-449416-6', 2017, '2017-09-10', NULL, '2026-05-26 00:00:00', '2026-05-26 00:00:00'),
  (4, 'Test Driven Development: By Example', 'The foundational book on TDD', '978-0-321-14653-3', 2002, '2002-11-08', NULL, '2026-05-26 00:00:00', '2026-05-26 00:00:00');

INSERT INTO book_author (book_id, author_id) VALUES
  (1, 1),
  (2, 1),
  (2, 2),
  (3, 3),
  (4, 4);
