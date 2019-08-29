
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- top_product
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `top_product`;

CREATE TABLE `top_product`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `element_key` VARCHAR(255) NOT NULL,
    `element_id` INTEGER NOT NULL,
    `product_id` INTEGER NOT NULL,
    `selection_code` VARCHAR(55) NOT NULL,
    `position` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FI_top_product_product_id` (`product_id`),
    CONSTRAINT `fk_top_product_product_id`
        FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
