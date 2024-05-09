-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2024 at 08:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_num` int(11) NOT NULL,
  `Username` varchar(32) NOT NULL,
  `message` varchar(255) NOT NULL,
  `toWho` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`, `price`) VALUES
(1, 'meat', 70),
(2, 'tomato', 5),
(3, 'Cherry tomatoes', 10),
(4, 'Onions', 8),
(5, 'Mushrooms', 10),
(6, 'Lettuce', 5),
(7, 'Jalapeno', 10),
(8, 'Gherkins', 5),
(9, 'Garlic', 10),
(10, 'Cucumber', 7),
(11, 'Cauliflower', 9),
(12, 'Carrot', 8),
(13, 'Capsicum', 6),
(14, 'Chicken', 30),
(15, 'milk', 5),
(16, 'strawberry', 5);

-- --------------------------------------------------------

--
-- Table structure for table `item_ingredients`
--

CREATE TABLE `item_ingredients` (
  `ItemID` int(11) DEFAULT NULL,
  `IngredientID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_ingredients`
--

INSERT INTO `item_ingredients` (`ItemID`, `IngredientID`) VALUES
(1, 1),
(1, 4),
(1, 2),
(2, 15),
(2, 16);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` int(11) NOT NULL,
  `resturant_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `img` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `resturant_id`, `name`, `description`, `price`, `img`, `quantity`) VALUES
(1, 2, 'dessert', 'a dessert', 20, 'imgs/lnd7.jpg', 15),
(2, 1, 'Hallucinatory Dish', 'A Dish Idk', 50, 'imgs/lnd5.jpg', 0),
(3, 1, 'Livnoni Salad', 'A Salad', 15, 'imgs/lnd8.jpg', 3),
(4, 1, 'Pasta with Chicken Loaf', 'Pasta with Chicken Soap stuff', 30, 'imgs/lnd11.jpg', 4),
(5, 1, 'Mascarabone Ravioli', 'Mascarabone Ravioli', 24, 'imgs/lnd15.jpg', 1),
(6, 2, 'Mojeto', 'Mojeto : cold drink idk', 10, 'imgs/ind17.jpeg', 5),
(7, 1, 'meat steak', 'a meat steak', 70, 'imgs/ind20.jpg', 4),
(8, 1, 'noodils', 'spicy thing and white ', 37, 'imgs/ind21.jpg', 1),
(9, 1, 'pizza', 'Description of Product 1.', 10, 'imgs/pizza.jpg', 5),
(10, 1, 'hamburger', 'Description of Product 2', 15, 'imgs/hamburger.jpg', 3),
(11, 1, 'bbq', 'Description of Product 3.', 12, 'imgs/bbq.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `OrderDate` date NOT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `OrderDate`, `totalPrice`) VALUES
(7, 2, '2024-04-02', 120),
(14, 2, '2024-04-02', 70),
(15, 2, '2024-04-02', 37),
(16, 2, '2024-04-02', 100);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quanity` int(11) NOT NULL,
  `resturant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `item_id`, `quanity`, `resturant_id`) VALUES
(8, 7, 7, 1, 1),
(9, 7, 2, 1, 1),
(17, 14, 7, 1, 1),
(18, 15, 8, 1, 1),
(19, 16, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `resturants`
--

CREATE TABLE `resturants` (
  `resturant_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `rating` float NOT NULL,
  `address` varchar(32) NOT NULL,
  `Username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `resturant_phone` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resturants`
--

INSERT INTO `resturants` (`resturant_id`, `name`, `rating`, `address`, `Username`, `password`, `resturant_phone`) VALUES
(1, 'pltnm', 4.5, 'sakhnin', 'pltnm', '123456', '0512345678'),
(2, 'shake', 4.5, 'sakhnin', 'shake', '123456', '0501234567');

-- --------------------------------------------------------

--
-- Table structure for table `temp_orders`
--

CREATE TABLE `temp_orders` (
  `temp_order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quanity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `temp_orders`
--

INSERT INTO `temp_orders` (`temp_order_id`, `user_id`, `item_id`, `quanity`) VALUES
(28, 2, 7, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `First name` varchar(32) NOT NULL,
  `Last name` varchar(32) NOT NULL,
  `Username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `e-mail` varchar(32) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `birth date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `Locked` tinyint(1) NOT NULL,
  `atempt_Date` varchar(255) NOT NULL,
  `temp_password` varchar(255) NOT NULL,
  `chan_bool` smallint(6) NOT NULL,
  `img` varchar(255) NOT NULL,
  `login_attempt` int(11) NOT NULL,
  `failed_atempt` int(11) NOT NULL,
  `last_Login` date DEFAULT NULL,
  `login_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`First name`, `Last name`, `Username`, `password`, `e-mail`, `phone`, `birth date`, `user_id`, `Locked`, `atempt_Date`, `temp_password`, `chan_bool`, `img`, `login_attempt`, `failed_atempt`, `last_Login`, `login_status`) VALUES
('hasen', '3tman', 'hasen', '123', 'hasen@hotmail.com', '8036323248', '2014-03-11', 1, 0, '', '', 0, 'imgs/user1.png', 3, 1, '2024-04-05', 0),
('yousef', 'sleman', 'yousef', '12345', 'yousef@hotmail.com', '1503270576', '2001-09-11', 2, 0, '', '', 0, 'imgs/user2.png', 12, 2, '2024-04-05', 0),
('John', 'Cena', 'John', 'password1', 'John@hotmail.com', '9334623195', '2009-10-22', 3, 0, '', '', 0, 'imgs/user3.png', 0, 0, NULL, 0),
('Bob', 'Builder', 'Bob', 'password1', 'Bob@hotmail.com', '7985659160', '2010-08-11', 4, 0, '', '', 0, 'imgs/user4.png', 0, 0, NULL, 0),
('jj', 'kk', 'jjkk', '1234', 'kjhoh@pksadkasd.ocm', '4857321', '2001-11-03', 6, 0, '', '', 0, 'imgs/user5.png', 0, 0, NULL, 0),
('jjj', 'kkk', 'askldmdsal', '1234', 'jasopjdsaasd@oiasdjasd.cpm', '39383298', '2001-11-30', 7, 0, '', '', 0, 'imgs/user6.png', 0, 0, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_num`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Indexes for table `item_ingredients`
--
ALTER TABLE `item_ingredients`
  ADD KEY `ItemID` (`ItemID`),
  ADD KEY `IngredientID` (`IngredientID`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `resturant_id` (`resturant_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `Item_id` (`item_id`),
  ADD KEY `resturant_id` (`resturant_id`);

--
-- Indexes for table `resturants`
--
ALTER TABLE `resturants`
  ADD PRIMARY KEY (`resturant_id`);

--
-- Indexes for table `temp_orders`
--
ALTER TABLE `temp_orders`
  ADD PRIMARY KEY (`temp_order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `UserName` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_num` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `resturants`
--
ALTER TABLE `resturants`
  MODIFY `resturant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `temp_orders`
--
ALTER TABLE `temp_orders`
  MODIFY `temp_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_ingredients`
--
ALTER TABLE `item_ingredients`
  ADD CONSTRAINT `item_ingredients_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `menu_items` (`item_id`),
  ADD CONSTRAINT `item_ingredients_ibfk_2` FOREIGN KEY (`IngredientID`) REFERENCES `ingredients` (`ingredient_id`);

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`resturant_id`) REFERENCES `resturants` (`resturant_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `ss` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`resturant_id`) REFERENCES `resturants` (`resturant_id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`);

--
-- Constraints for table `temp_orders`
--
ALTER TABLE `temp_orders`
  ADD CONSTRAINT `temp_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `temp_orders_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
