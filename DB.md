# База данных (схема)

## Сущности

 - **`users`**
 - **`profiles`**
 - **`categories`**
 - **`tags`**
 - **`communities`**
 - **`articles`**
 - **`article_tag`** (pivot)
 - **`community_user`** (подписки)

## Типы связей

 - **1:1 (один к одному - центральная таблица)**
   - **`users` → `profiles`** (`profiles.user_id`, `ON DELETE CASCADE`)
 - **1:N (один ко многим)**
   - **`users` → `communities`** (владелец: `communities.user_id`, `ON DELETE CASCADE`)
   - **`users` → `articles`** (автор: `articles.user_id`, `ON DELETE CASCADE`)
   - **`categories` → `articles`** (`articles.category_id`, `ON DELETE SET NULL`)
   - **`communities` → `articles`** (`articles.community_id`, `ON DELETE SET NULL`)
 - **M:N (многие ко многим, через pivot-таблицы)**
   - **`articles` ↔ `tags`** через `article_tag` (`ON DELETE CASCADE`)
   - **`communities` ↔ `users`** через `community_user` (`ON DELETE CASCADE`)

## SQL (DDL)

### Таблица: `users`

 ```sql
 CREATE TABLE users (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     email VARCHAR(255) NOT NULL UNIQUE,
     email_verified_at DATETIME NULL,
     password VARCHAR(255) NOT NULL,
     role ENUM('admin','moderator','user') NOT NULL DEFAULT 'user',
     is_blocked BOOLEAN NOT NULL DEFAULT FALSE,
     created_at DATETIME NULL,
     updated_at DATETIME NULL
 );
 ```

### Таблица: `profiles`

 ```sql
 CREATE TABLE profiles (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     user_id BIGINT UNSIGNED NOT NULL,
     avatar VARCHAR(255) NULL,
     bio TEXT NULL,
     CONSTRAINT fk_profiles_user
         FOREIGN KEY (user_id) REFERENCES users(id)
         ON DELETE CASCADE
 );
 ```

### Таблица: `categories`

 ```sql
 CREATE TABLE categories (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     slug VARCHAR(255) NOT NULL UNIQUE
 );
 ```

### Таблица: `tags`

 ```sql
 CREATE TABLE tags (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     slug VARCHAR(255) NOT NULL UNIQUE
 );
 ```

### Таблица: `communities`

 ```sql
 CREATE TABLE communities (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     slug VARCHAR(255) NOT NULL UNIQUE,
     description TEXT NULL,
     cover_image VARCHAR(255) NULL,
     user_id BIGINT UNSIGNED NOT NULL,
     is_private BOOLEAN NOT NULL DEFAULT FALSE,
     created_at DATETIME NULL,
     updated_at DATETIME NULL,
     CONSTRAINT fk_communities_user
         FOREIGN KEY (user_id) REFERENCES users(id)
         ON DELETE CASCADE
 );
 ```

### Таблица: `articles`

 ```sql
 CREATE TABLE articles (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     user_id BIGINT UNSIGNED NOT NULL,
     community_id BIGINT UNSIGNED NULL,
     category_id BIGINT UNSIGNED NULL,
     title VARCHAR(255) NOT NULL,
     slug VARCHAR(255) NOT NULL UNIQUE,
     excerpt VARCHAR(500) NULL,
     content LONGTEXT NOT NULL,
     image VARCHAR(255) NULL,
     status ENUM('draft','published') NOT NULL DEFAULT 'draft',
     views_count INT UNSIGNED NOT NULL DEFAULT 0,
     created_at DATETIME NULL,
     updated_at DATETIME NULL,
     CONSTRAINT fk_articles_user
         FOREIGN KEY (user_id) REFERENCES users(id)
         ON DELETE CASCADE,
     CONSTRAINT fk_articles_category
         FOREIGN KEY (category_id) REFERENCES categories(id)
         ON DELETE SET NULL,
     CONSTRAINT fk_articles_community
         FOREIGN KEY (community_id) REFERENCES communities(id)
         ON DELETE SET NULL
 );
 ```

### Таблица: `article_tag` (pivot)

 ```sql
 CREATE TABLE article_tag (
     article_id BIGINT UNSIGNED NOT NULL,
     tag_id BIGINT UNSIGNED NOT NULL,
     PRIMARY KEY (article_id, tag_id),
     CONSTRAINT fk_article_tag_article
         FOREIGN KEY (article_id) REFERENCES articles(id)
         ON DELETE CASCADE,
     CONSTRAINT fk_article_tag_tag
         FOREIGN KEY (tag_id) REFERENCES tags(id)
         ON DELETE CASCADE
 );
 ```

### Таблица: `community_user` (подписки)

 ```sql
 CREATE TABLE community_user (
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     community_id BIGINT UNSIGNED NOT NULL,
     user_id BIGINT UNSIGNED NOT NULL,
     created_at DATETIME NULL,
     CONSTRAINT fk_community_user_comm
         FOREIGN KEY (community_id) REFERENCES communities(id)
         ON DELETE CASCADE,
     CONSTRAINT fk_community_user_user
         FOREIGN KEY (user_id) REFERENCES users(id)
         ON DELETE CASCADE
 );
 ```
