-- ============================================================
-- RecipeBook - Sosa Project
-- Run this entire script in phpMyAdmin SQL tab
-- ============================================================

CREATE DATABASE IF NOT EXISTS `sosa_recipes`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `sosa_recipes`;

-- ============================================================
-- Table: users
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`            VARCHAR(255)    NOT NULL,
  `email`           VARCHAR(255)    NOT NULL UNIQUE,
  `password`        VARCHAR(255)    NOT NULL,
  `address`         VARCHAR(255)    DEFAULT NULL,
  `gender`          VARCHAR(20)     DEFAULT NULL,
  `phone`           VARCHAR(20)     DEFAULT NULL,
  `profile_picture`        VARCHAR(255)    DEFAULT NULL,
  `profile_picture_base64`  LONGTEXT        DEFAULT NULL,
  `role`            VARCHAR(20)     NOT NULL DEFAULT 'user',
  `remember_token`  VARCHAR(100)    DEFAULT NULL,
  `created_at`      TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: password_reset_tokens
-- ============================================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP    NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: recipes
-- ============================================================
DROP TABLE IF EXISTS `recipes`;
CREATE TABLE `recipes` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `title`        VARCHAR(255)    NOT NULL,
  `category`     VARCHAR(100)    NOT NULL,
  `ingredients`  TEXT            NOT NULL,
  `instructions` TEXT            NOT NULL,
  `prep_time`    INT             NOT NULL COMMENT 'minutes',
  `image`        VARCHAR(255)    DEFAULT NULL,
  `created_at`   TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `recipes_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: migrations (required by Laravel)
-- ============================================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch`     INT          NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('2024_01_01_000003_create_recipes_table', 1),
('2024_01_01_000004_add_profile_picture_base64_to_users_table', 1);

-- ============================================================
-- Sample Data: Users
-- Passwords are bcrypt hashed — all passwords = "password123"
-- ============================================================
INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `gender`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin Sosa',    'admin@sosa.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manila, Philippines',    'Male',   '+63 912 345 6789', 'admin', NOW(), NOW()),
(2, 'Maria Santos',  'maria@sosa.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cebu City, Philippines', 'Female', '+63 923 456 7890', 'user',  NOW(), NOW()),
(3, 'Juan dela Cruz','juan@sosa.com',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Davao City, Philippines','Male',   '+63 934 567 8901', 'user',  NOW(), NOW()),
(4, 'Ana Reyes',     'ana@sosa.com',     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quezon City, Philippines','Female','+63 945 678 9012', 'user',  NOW(), NOW()),
(5, 'Pedro Lim',     'pedro@sosa.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Makati, Philippines',    'Male',   '+63 956 789 0123', 'user',  NOW(), NOW());

-- ============================================================
-- Sample Data: Recipes
-- ============================================================
INSERT INTO `recipes` (`user_id`, `title`, `category`, `ingredients`, `instructions`, `prep_time`, `created_at`, `updated_at`) VALUES
(1, 'Chicken Adobo',
 'Dinner',
 '1 kg chicken pieces
1/2 cup soy sauce
1/2 cup white vinegar
1 head garlic, crushed
3 bay leaves
1 tsp black peppercorns
2 tbsp cooking oil',
 '1. Marinate chicken in soy sauce, vinegar, garlic, bay leaves, and peppercorns for 30 minutes.
2. Heat oil in a pan over medium heat.
3. Sear chicken pieces until golden brown on both sides.
4. Pour in the marinade and bring to a boil.
5. Lower heat and simmer for 30 minutes until chicken is tender.
6. Serve hot with steamed rice.',
 45, NOW(), NOW()),

(1, 'Sinigang na Baboy',
 'Lunch',
 '1 kg pork ribs
1 pack sinigang mix (tamarind)
2 medium tomatoes, quartered
1 medium onion, quartered
2 cups kangkong (water spinach)
1 cup sitaw (string beans)
2 medium radish, sliced
Fish sauce to taste
6 cups water',
 '1. Boil water in a large pot. Add pork ribs and bring to a boil.
2. Skim off scum that rises to the surface.
3. Add tomatoes and onion. Simmer for 20 minutes.
4. Add sinigang mix and stir well.
5. Add radish and sitaw. Cook for 5 minutes.
6. Add kangkong and season with fish sauce.
7. Serve hot.',
 60, NOW(), NOW()),

(2, 'Pancit Canton',
 'Lunch',
 '250g canton noodles
200g chicken breast, sliced
100g shrimp, peeled
1 cup cabbage, shredded
1 carrot, julienned
1 cup snow peas
3 cloves garlic, minced
1 onion, sliced
3 tbsp soy sauce
2 tbsp oyster sauce
2 cups chicken broth',
 '1. Soak canton noodles in warm water for 5 minutes. Drain and set aside.
2. Saute garlic and onion in oil until fragrant.
3. Add chicken and cook until no longer pink.
4. Add shrimp and cook for 2 minutes.
5. Add vegetables and stir-fry for 3 minutes.
6. Add noodles, soy sauce, oyster sauce, and broth.
7. Toss everything together and cook until liquid is absorbed.',
 30, NOW(), NOW()),

(2, 'Leche Flan',
 'Dessert',
 '10 egg yolks
1 can (390ml) condensed milk
1 can (370ml) evaporated milk
1 tsp vanilla extract
1 cup sugar (for caramel)',
 '1. Make caramel: melt sugar in a llanera over low heat until golden brown. Swirl to coat the bottom.
2. Beat egg yolks until smooth.
3. Mix in condensed milk, evaporated milk, and vanilla.
4. Strain the mixture and pour into the caramel-coated llanera.
5. Cover with aluminum foil.
6. Steam for 45 minutes or until set.
7. Cool completely before inverting onto a plate.',
 70, NOW(), NOW()),

(3, 'Garlic Fried Rice',
 'Breakfast',
 '3 cups cooked rice (day-old)
8 cloves garlic, minced
3 tbsp cooking oil
Salt and pepper to taste
2 eggs (optional)',
 '1. Heat oil in a wok over high heat.
2. Fry garlic until golden and crispy. Remove half and set aside.
3. Add rice to the wok and break up any clumps.
4. Stir-fry for 5 minutes until rice is heated through.
5. Season with salt and pepper.
6. Top with crispy garlic and serve with eggs.',
 15, NOW(), NOW()),

(3, 'Mango Float',
 'Dessert',
 '3 ripe mangoes, sliced
2 packs (250ml each) all-purpose cream
1 can (300ml) condensed milk
2 packs graham crackers',
 '1. Mix all-purpose cream and condensed milk until well combined.
2. In a rectangular container, layer graham crackers.
3. Spread cream mixture over crackers.
4. Add a layer of mango slices.
5. Repeat layers until ingredients are used up.
6. Top with remaining mango slices.
7. Refrigerate overnight before serving.',
 20, NOW(), NOW()),

(4, 'Beef Caldereta',
 'Dinner',
 '1 kg beef, cubed
1 can (250g) liver spread
1 can (400g) tomato sauce
1 cup tomato paste
2 potatoes, cubed
2 carrots, cubed
1 red bell pepper, sliced
1 green bell pepper, sliced
1 cup green olives
1 onion, chopped
4 cloves garlic, minced
2 cups beef broth',
 '1. Saute garlic and onion until fragrant.
2. Add beef and cook until browned on all sides.
3. Pour in tomato sauce, tomato paste, and beef broth.
4. Bring to a boil then simmer for 45 minutes.
5. Add potatoes and carrots. Cook for 15 minutes.
6. Stir in liver spread and bell peppers.
7. Add olives and season to taste. Simmer 10 more minutes.',
 90, NOW(), NOW()),

(5, 'Buko Pandan',
 'Drinks',
 '2 cups young coconut (buko) strips
1 pack pandan-flavored gelatin
2 cups all-purpose cream
1 can condensed milk
1 cup coconut milk
Pandan leaves for flavoring',
 '1. Prepare pandan gelatin according to package instructions. Let cool and cut into cubes.
2. Mix all-purpose cream, condensed milk, and coconut milk.
3. Add buko strips and gelatin cubes.
4. Mix gently until well combined.
5. Refrigerate for at least 2 hours before serving.
6. Serve chilled.',
 25, NOW(), NOW()),

(5, 'Tortang Talong',
 'Breakfast',
 '3 large eggplants
3 eggs, beaten
Salt and pepper to taste
Cooking oil for frying',
 '1. Grill or roast eggplants directly over flame until skin is charred.
2. Peel off the charred skin carefully, keeping the stem intact.
3. Flatten the eggplant with a fork.
4. Season beaten eggs with salt and pepper.
5. Dip flattened eggplant in beaten egg.
6. Fry in hot oil until golden brown on both sides.
7. Serve with ketchup or vinegar dip.',
 25, NOW(), NOW());

-- ============================================================
-- IMPORTANT: Fix root user auth plugin
-- If you cannot connect to MySQL from Laravel, run these 2 lines
-- in phpMyAdmin under the "mysql" database SQL tab:
-- ============================================================
-- UPDATE `mysql`.`user` SET `plugin`='mysql_native_password', `Password`='' WHERE `User`='root';
-- FLUSH PRIVILEGES;
