CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tg_uid` VARCHAR(64) UNIQUE NOT NULL,
  `tg_username` VARCHAR(255),
  `tg_name` VARCHAR(255),
  `balance` DECIMAL(10,4) DEFAULT 0.0000,
  `is_blocked` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `referrals` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `referrer_uid` VARCHAR(64) NOT NULL,
  `referred_uid` VARCHAR(64) UNIQUE NOT NULL,
  `status` ENUM('pending', 'approved') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`referrer_uid`) REFERENCES `users`(`tg_uid`),
  FOREIGN KEY (`referred_uid`) REFERENCES `users`(`tg_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tg_uid` VARCHAR(64) NOT NULL,
  `task_id` VARCHAR(100) NOT NULL,
  `reward` DECIMAL(10,4) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`tg_uid`) REFERENCES `users`(`tg_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `tasks` ADD UNIQUE KEY `unique_user_task` (`tg_uid`, `task_id`);

CREATE TABLE `ad_views` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tg_uid` VARCHAR(64) NOT NULL,
  `reward` DECIMAL(10,4) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`tg_uid`) REFERENCES `users`(`tg_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `withdrawals` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tg_uid` VARCHAR(64) NOT NULL,
  `amount` DECIMAL(10,4) NOT NULL,
  `method` VARCHAR(50) NOT NULL,
  `network` VARCHAR(50),
  `details` VARCHAR(255) NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`tg_uid`) REFERENCES `users`(`tg_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `admin_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_uid` VARCHAR(64) NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `target_uid` VARCHAR(64),
  `amount` DECIMAL(10,4),
  `reason` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `csrf_tokens` (
  `token` VARCHAR(64) PRIMARY KEY,
  `tg_uid` VARCHAR(64) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
