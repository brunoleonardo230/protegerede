/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100411
Source Host           : localhost:3306
Source Database       : medical_three

Target Server Type    : MYSQL
Target Server Version : 100411
File Encoding         : 65001

Date: 2020-05-15 11:47:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for workcontrol_api
-- ----------------------------
DROP TABLE IF EXISTS `workcontrol_api`;
CREATE TABLE `workcontrol_api` (
  `api_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `api_key` varchar(255) DEFAULT '',
  `api_token` varchar(255) DEFAULT '',
  `api_date` timestamp NULL DEFAULT NULL,
  `api_status` int(11) DEFAULT 0,
  `api_loads` int(11) DEFAULT 0,
  `api_lastload` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`api_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of workcontrol_api
-- ----------------------------

-- ----------------------------
-- Table structure for workcontrol_code
-- ----------------------------
DROP TABLE IF EXISTS `workcontrol_code`;
CREATE TABLE `workcontrol_code` (
  `code_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code_name` varchar(255) DEFAULT '',
  `code_condition` varchar(255) DEFAULT '',
  `code_script` text DEFAULT NULL,
  `code_created` timestamp NULL DEFAULT NULL,
  `code_views` decimal(11,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of workcontrol_code
-- ----------------------------

-- ----------------------------
-- Table structure for ws_brands
-- ----------------------------
DROP TABLE IF EXISTS `ws_brands`;
CREATE TABLE `ws_brands` (
  `brand_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador da Marca Parceira',
  `brand_name` varchar(255) DEFAULT NULL COMMENT 'Nome da Marca',
  `brand_site` varchar(255) DEFAULT NULL COMMENT 'Site da Marca Parceira',
  `brand_image` varchar(255) DEFAULT NULL COMMENT 'Imagem ou Logo da Marca',
  `brand_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Criação da Marca No Sistema da Marca',
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_brands
-- ----------------------------
INSERT INTO `ws_brands` VALUES ('1', 'Medico', 'https://www.gbtechweb.com.br', 'marcas/2020/05/medico.png', '2020-05-14 03:44:26');
INSERT INTO `ws_brands` VALUES ('2', 'We Care', 'https://www.gbtechweb.com.br', 'marcas/2020/05/we-care.png', '2020-05-14 03:44:37');
INSERT INTO `ws_brands` VALUES ('3', 'Maxi Health', 'https://www.gbtechweb.com.br', 'marcas/2020/05/maxi-health.png', '2020-05-14 03:44:51');
INSERT INTO `ws_brands` VALUES ('4', 'Max Medical', 'https://www.gbtechweb.com.br', 'marcas/2020/05/max-medical.png', '2020-05-14 03:45:02');

-- ----------------------------
-- Table structure for ws_categories
-- ----------------------------
DROP TABLE IF EXISTS `ws_categories`;
CREATE TABLE `ws_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_parent` int(11) unsigned DEFAULT NULL,
  `category_tree` varchar(255) DEFAULT NULL,
  `category_title` varchar(255) DEFAULT NULL,
  `category_content` text DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `category_sizes` varchar(255) DEFAULT NULL,
  `category_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_categories
-- ----------------------------
INSERT INTO `ws_categories` VALUES ('1', null, null, 'Medicina Geral', 'Categoria de Medicina Geral', 'medicina-geral', null, '2020-05-09 19:52:40');
INSERT INTO `ws_categories` VALUES ('2', '1', '1', 'Medicina Geral', 'Subcategoria de Medicina Geral', 'medicina-geral-2', null, '2020-05-09 19:53:03');
INSERT INTO `ws_categories` VALUES ('3', null, null, 'Oncologia', 'Categoria de Oncologia', 'oncologia', null, '2020-05-09 19:53:16');
INSERT INTO `ws_categories` VALUES ('4', '3', '3', 'Oncologia', 'Subcategoria de Oncologia', 'oncologia-4', null, '2020-05-09 19:53:31');
INSERT INTO `ws_categories` VALUES ('5', null, null, 'Pediatria', 'Categoria de Pediatria', 'pediatria', null, '2020-05-09 19:53:45');
INSERT INTO `ws_categories` VALUES ('6', '5', '5', 'Pediatria', 'Subcategoria de Pediatria', 'pediatria-6', null, '2020-05-09 19:54:06');

-- ----------------------------
-- Table structure for ws_comments
-- ----------------------------
DROP TABLE IF EXISTS `ws_comments`;
CREATE TABLE `ws_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `post_id` int(11) unsigned DEFAULT NULL,
  `pdt_id` int(11) unsigned DEFAULT NULL,
  `page_id` int(11) unsigned DEFAULT NULL,
  `alias_id` int(11) unsigned DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `rank` decimal(11,0) DEFAULT 1,
  `created` timestamp NULL DEFAULT NULL,
  `interact` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `likes` decimal(11,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `wc_comment_user` (`user_id`),
  KEY `wc_comment_pdt` (`pdt_id`),
  KEY `wc_comment_pages` (`page_id`),
  KEY `wc_comment_response` (`alias_id`),
  KEY `wc_comment_post` (`post_id`),
  CONSTRAINT `wc_comment_pages` FOREIGN KEY (`page_id`) REFERENCES `ws_pages` (`page_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_comment_pdt` FOREIGN KEY (`pdt_id`) REFERENCES `ws_products` (`pdt_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_comment_post` FOREIGN KEY (`post_id`) REFERENCES `ws_posts` (`post_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_comment_response` FOREIGN KEY (`alias_id`) REFERENCES `ws_comments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_comment_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_comments
-- ----------------------------

-- ----------------------------
-- Table structure for ws_comments_likes
-- ----------------------------
DROP TABLE IF EXISTS `ws_comments_likes`;
CREATE TABLE `ws_comments_likes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `comm_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wc_comments` (`comm_id`),
  CONSTRAINT `wc_comments` FOREIGN KEY (`comm_id`) REFERENCES `ws_comments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_comments_likes
-- ----------------------------

-- ----------------------------
-- Table structure for ws_company
-- ----------------------------
DROP TABLE IF EXISTS `ws_company`;
CREATE TABLE `ws_company` (
  `company_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_image` varchar(255) DEFAULT NULL,
  `company_title` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_content` text DEFAULT NULL,
  `company_segment` varchar(255) DEFAULT NULL,
  `company_responsible` varchar(255) DEFAULT NULL,
  `company_document` varchar(255) DEFAULT NULL,
  `company_opening` varchar(255) DEFAULT NULL,
  `company_telephone` varchar(255) DEFAULT NULL,
  `company_cell` varchar(255) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_site` varchar(255) DEFAULT NULL,
  `company_zipcode` varchar(255) DEFAULT NULL,
  `company_street` varchar(255) DEFAULT NULL,
  `company_number` varchar(255) DEFAULT NULL,
  `company_complement` varchar(255) DEFAULT NULL,
  `company_district` varchar(255) DEFAULT NULL,
  `company_city` varchar(255) DEFAULT NULL,
  `company_state` varchar(2) DEFAULT NULL,
  `company_country` varchar(255) DEFAULT NULL,
  `company_facebook` varchar(255) DEFAULT NULL,
  `company_twitter` varchar(255) DEFAULT NULL,
  `company_youtube` varchar(255) DEFAULT NULL,
  `company_instagram` varchar(255) DEFAULT NULL,
  `company_mission` varchar(255) DEFAULT NULL,
  `company_view` varchar(255) DEFAULT NULL,
  `company_values` varchar(255) DEFAULT NULL,
  `company_status` int(11) NOT NULL DEFAULT 0,
  `company_datecreated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_company
-- ----------------------------

-- ----------------------------
-- Table structure for ws_company_blocks
-- ----------------------------
DROP TABLE IF EXISTS `ws_company_blocks`;
CREATE TABLE `ws_company_blocks` (
  `company_id` int(11) unsigned NOT NULL,
  `block_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `block_title` varchar(255) DEFAULT NULL,
  `block_icon` varchar(255) DEFAULT NULL,
  `block_name` varchar(255) DEFAULT NULL,
  `block_image` varchar(255) DEFAULT NULL,
  `block_content` text DEFAULT NULL,
  `block_status` int(11) NOT NULL DEFAULT 0,
  `block_datecreate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`block_id`),
  KEY `wc_company_blocks` (`company_id`),
  CONSTRAINT `wc_company_blocks` FOREIGN KEY (`company_id`) REFERENCES `ws_company` (`company_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_company_blocks
-- ----------------------------

-- ----------------------------
-- Table structure for ws_company_differentials
-- ----------------------------
DROP TABLE IF EXISTS `ws_company_differentials`;
CREATE TABLE `ws_company_differentials` (
  `company_id` int(11) unsigned NOT NULL,
  `differential_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `differential_title` varchar(255) DEFAULT NULL,
  `differential_content` text DEFAULT NULL,
  `differential_image` varchar(255) DEFAULT NULL,
  `differential_icon_type` int(11) DEFAULT NULL,
  `differential_icon_text` varchar(255) DEFAULT NULL,
  `differential_icon` varchar(255) DEFAULT NULL,
  `differential_datecreate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`differential_id`),
  KEY `wc_company_differentials` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_company_differentials
-- ----------------------------

-- ----------------------------
-- Table structure for ws_company_faq
-- ----------------------------
DROP TABLE IF EXISTS `ws_company_faq`;
CREATE TABLE `ws_company_faq` (
  `company_id` int(11) unsigned DEFAULT NULL COMMENT 'Identificador da Empresa',
  `faq_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador da FAQ',
  `faq_title` varchar(255) DEFAULT NULL COMMENT 'Título da FAQ',
  `faq_content` text DEFAULT NULL COMMENT 'Descrição da FAQ',
  `faq_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Criação da FAQ No Sistema',
  PRIMARY KEY (`faq_id`),
  KEY `wc_company_faq` (`company_id`),
  CONSTRAINT `wc_company_faq` FOREIGN KEY (`company_id`) REFERENCES `ws_company` (`company_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_company_faq
-- ----------------------------

-- ----------------------------
-- Table structure for ws_company_gallery
-- ----------------------------
DROP TABLE IF EXISTS `ws_company_gallery`;
CREATE TABLE `ws_company_gallery` (
  `company_id` int(10) unsigned NOT NULL,
  `gallery_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_file` varchar(255) NOT NULL,
  `gallery_image_order` int(11) DEFAULT NULL,
  `gallery_image_legend` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery_image_id`),
  KEY `wc_company` (`company_id`),
  CONSTRAINT `wc_company` FOREIGN KEY (`company_id`) REFERENCES `ws_company` (`company_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_company_differentials` FOREIGN KEY (`company_id`) REFERENCES `ws_company` (`company_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_company_gallery
-- ----------------------------

-- ----------------------------
-- Table structure for ws_config
-- ----------------------------
DROP TABLE IF EXISTS `ws_config`;
CREATE TABLE `ws_config` (
  `conf_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conf_key` varchar(255) DEFAULT '',
  `conf_value` varchar(255) DEFAULT '',
  `conf_type` varchar(255) DEFAULT '',
  PRIMARY KEY (`conf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3308 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_config
-- ----------------------------
INSERT INTO `ws_config` VALUES ('3080', 'BASE', 'https://localhost/medical_three', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3081', 'THEME', 'medical_three', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3084', 'ADMIN_NAME', 'Work Control', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3085', 'ADMIN_DESC', 'O Work Control é um sistema de gestão de conteúdo profissional gerido pela turma de alunos Work Series da UpInside Treinamentos!', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3086', 'ADMIN_MODE', '1', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3087', 'ADMIN_WC_CUSTOM', '1', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3088', 'ADMIN_MAINTENANCE', '0', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3089', 'ADMIN_VERSION', '3.1.4', 'ADMIN');
INSERT INTO `ws_config` VALUES ('3090', 'MAIL_HOST', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3091', 'MAIL_PORT', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3092', 'MAIL_USER', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3093', 'MAIL_SMTP', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3094', 'MAIL_PASS', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3095', 'MAIL_SENDER', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3096', 'MAIL_MODE', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3097', 'MAIL_TESTER', '', 'MAIL');
INSERT INTO `ws_config` VALUES ('3098', 'IMAGE_W', '1600', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3099', 'IMAGE_H', '800', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3100', 'THUMB_W', '800', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3101', 'THUMB_H', '1000', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3102', 'AVATAR_W', '500', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3103', 'AVATAR_H', '500', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3104', 'SLIDE_W', '1920', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3105', 'SLIDE_H', '600', 'IMAGE');
INSERT INTO `ws_config` VALUES ('3106', 'APP_POSTS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3107', 'APP_POSTS_AMP', '0', 'APP');
INSERT INTO `ws_config` VALUES ('3108', 'APP_POSTS_INSTANT_ARTICLE', '0', 'APP');
INSERT INTO `ws_config` VALUES ('3109', 'APP_EAD', '0', 'APP');
INSERT INTO `ws_config` VALUES ('3110', 'APP_SEARCH', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3111', 'APP_PAGES', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3112', 'APP_COMMENTS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3113', 'APP_PRODUCTS', '0', 'APP');
INSERT INTO `ws_config` VALUES ('3114', 'APP_ORDERS', '0', 'APP');
INSERT INTO `ws_config` VALUES ('3115', 'APP_IMOBI', '0', 'APP');
INSERT INTO `ws_config` VALUES ('3116', 'APP_SLIDE', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3117', 'APP_USERS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3118', 'FBREVIEW_PAGE_ID', '', 'FBREVIEW');
INSERT INTO `ws_config` VALUES ('3119', 'FBREVIEW_APP_ID', '', 'FBREVIEW');
INSERT INTO `ws_config` VALUES ('3120', 'FBREVIEW_APP_SECRET', '', 'FBREVIEW');
INSERT INTO `ws_config` VALUES ('3121', 'FBREVIEW_LIMIT', '25', 'FBREVIEW');
INSERT INTO `ws_config` VALUES ('3122', 'APP_GALLERY', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3123', 'APP_VIDEOS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3124', 'APP_CONTACTS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3125', 'APP_TEMPLATE', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3126', 'APP_FAQ', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3127', 'APP_TESTIMONIALS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3128', 'APP_FBREVIEW', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3129', 'APP_TUTORIAIS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3130', 'APP_COMPANY', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3131', 'APP_BRANDS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3132', 'APP_DOCTORS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3133', 'APP_SPECIALTIES', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3134', 'APP_SERVICES', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3135', 'APP_SCHEDULES', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3136', 'LEVEL_WC_POSTS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3137', 'LEVEL_WC_COMMENTS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3138', 'LEVEL_WC_PAGES', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3139', 'LEVEL_WC_SLIDES', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3140', 'LEVEL_WC_IMOBI', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3141', 'LEVEL_WC_PRODUCTS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3142', 'LEVEL_WC_PRODUCTS_ORDERS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3143', 'LEVEL_WC_EAD_COURSES', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3144', 'LEVEL_WC_EAD_STUDENTS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3145', 'LEVEL_WC_EAD_SUPPORT', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3146', 'LEVEL_WC_EAD_ORDERS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3147', 'LEVEL_WC_REPORTS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3148', 'LEVEL_WC_USERS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3149', 'LEVEL_WC_CONFIG_MASTER', '10', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3150', 'LEVEL_WC_CONFIG_API', '10', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3151', 'LEVEL_WC_CONFIG_CODES', '10', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3152', 'LEVEL_WC_HELLO', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3153', 'LEVEL_WC_GALLERY', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3154', 'LEVEL_WC_VIDEOS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3155', 'LEVEL_WC_TEMPLATE', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3156', 'LEVEL_WC_CONTACTS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3157', 'LEVEL_WC_FAQ', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3158', 'LEVEL_WC_TESTIMONIALS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3159', 'LEVEL_WC_FBREVIEWS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3160', 'LEVEL_WC_TUTORIAIS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3161', 'LEVEL_WC_COMPANY', '8', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3162', 'LEVEL_WC_BRANDS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3163', 'LEVEL_WC_DOCTORS', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3164', 'LEVEL_WC_SPECIALTIES', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3165', 'LEVEL_WC_SERVICES', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3166', 'LEVEL_WC_SCHEDULES', '6', 'LEVEL');
INSERT INTO `ws_config` VALUES ('3167', 'AGENDADOR_HORA_INICIO', '08:00:00', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3168', 'AGENDADOR_HORA_FINAL', '20:00:00', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3169', 'AGENDADOR_SOBREPOR_HORARIO', '0', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3170', 'AGENDADOR_TEXTO_BT_CATEGORIA', 'ADD ESPECIALIDADE', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3171', 'AGENDADOR_TEXTO_BT_SUBCATEGORIA', 'ADD DENTISTA', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3172', 'AGENDADOR_TEXTO_COLUNA', 'Especialidades', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3173', 'AGENDADOR_DIVISAO_GRADE', '00:30', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3174', 'AGENDADOR_ASPECTRATIO', '2.1', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3175', 'AGENDADOR_TITULO_MODAL_CADASTRO', 'AGENDAR CONSULTA', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3176', 'AGENDADOR_TITULO_MODAL_UPDATE', 'ATUALIZAR CONSULTA', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3177', 'AGENDADOR_TITULO_MODAL_DELETE', 'DELETAR CONSULTA', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3178', 'AGENDADOR_FINAIS_DE_SEMANA', '1', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3179', 'AGENDADOR_LINK_APP', 'dashboard.php?wc=agenda/info&id=', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3180', 'AGENDADOR_CLIENTE_LEVEL_AGENDA', '1', 'AGENDADOR');
INSERT INTO `ws_config` VALUES ('3181', 'SEGMENT_FB_PIXEL_ID', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3182', 'SEGMENT_WC_USER', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3183', 'SEGMENT_WC_BLOG', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3184', 'SEGMENT_WC_ECOMMERCE', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3185', 'SEGMENT_WC_IMOBI', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3186', 'SEGMENT_WC_EAD', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3187', 'SEGMENT_GL_ANALYTICS_UA', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3188', 'SEGMENT_FB_PAGE_ID', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3189', 'SEGMENT_GL_ADWORDS_ID', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3190', 'SEGMENT_GL_ADWORDS_LABEL', '', 'SEGMENT');
INSERT INTO `ws_config` VALUES ('3191', 'APP_LINK_POSTS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3192', 'APP_LINK_PAGES', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3193', 'APP_LINK_PRODUCTS', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3194', 'APP_LINK_PROPERTIES', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3195', 'ACC_MANAGER', '1', 'APP');
INSERT INTO `ws_config` VALUES ('3196', 'ACC_TAG', 'Minha Conta!', 'APP');
INSERT INTO `ws_config` VALUES ('3197', 'COMMENT_MODERATE', '1', 'COMMENT');
INSERT INTO `ws_config` VALUES ('3198', 'COMMENT_ON_POSTS', '1', 'COMMENT');
INSERT INTO `ws_config` VALUES ('3199', 'COMMENT_ON_PAGES', '1', 'COMMENT');
INSERT INTO `ws_config` VALUES ('3200', 'COMMENT_ON_PRODUCTS', '1', 'COMMENT');
INSERT INTO `ws_config` VALUES ('3201', 'COMMENT_SEND_EMAIL', '1', 'COMMENT');
INSERT INTO `ws_config` VALUES ('3202', 'COMMENT_ORDER', 'DESC', 'COMMENT');
INSERT INTO `ws_config` VALUES ('3203', 'COMMENT_RESPONSE_ORDER', 'ASC', 'COMMENT');
INSERT INTO `ws_config` VALUES ('3204', 'E_PDT_LIMIT', '', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3205', 'E_PDT_SIZE', 'default', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3206', 'E_ORDER_DAYS', '', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3207', 'ECOMMERCE_TAG', 'Minhas Compras', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3208', 'ECOMMERCE_STOCK', '', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3209', 'ECOMMERCE_BUTTON_TAG', 'Comprar Agora', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3210', 'ECOMMERCE_PAY_SPLIT', '1', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3211', 'ECOMMERCE_PAY_SPLIT_MIN', '5', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3212', 'ECOMMERCE_PAY_SPLIT_NUM', '12', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3213', 'ECOMMERCE_PAY_SPLIT_ACM', '2.99', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3214', 'ECOMMERCE_PAY_SPLIT_ACN', '1', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3215', 'ECOMMERCE_SHIPMENT_FREE', '0', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3216', 'ECOMMERCE_SHIPMENT_FREE_DAYS', '20', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3217', 'ECOMMERCE_SHIPMENT_FIXED', '0', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3218', 'ECOMMERCE_SHIPMENT_FIXED_PRICE', '15', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3219', 'ECOMMERCE_SHIPMENT_FIXED_DAYS', '15', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3220', 'ECOMMERCE_SHIPMENT_LOCAL', '0', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3221', 'ECOMMERCE_SHIPMENT_LOCAL_IN_PLACE', '1', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3222', 'ECOMMERCE_SHIPMENT_LOCAL_PRICE', '5', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3223', 'ECOMMERCE_SHIPMENT_LOCAL_DAYS', '1', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3224', 'ECOMMERCE_SHIPMENT_CDEMPRESA', '0', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3225', 'ECOMMERCE_SHIPMENT_CDSENHA', '0', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3226', 'ECOMMERCE_SHIPMENT_SERVICE', '04014,04510', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3227', 'ECOMMERCE_SHIPMENT_DELAY', '3', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3228', 'ECOMMERCE_SHIPMENT_FORMAT', '1', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3229', 'ECOMMERCE_SHIPMENT_DECLARE', '1', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3230', 'ECOMMERCE_SHIPMENT_OWN_HAND', 's', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3231', 'ECOMMERCE_SHIPMENT_BY_WEIGHT', '1', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3232', 'ECOMMERCE_SHIPMENT_ALERT', '0', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3233', 'ECOMMERCE_SHIPMENT_COMPANY', '0', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3234', 'ECOMMERCE_SHIPMENT_COMPANY_VAL', '5', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3235', 'ECOMMERCE_SHIPMENT_COMPANY_PRICE', '30', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3236', 'ECOMMERCE_SHIPMENT_COMPANY_DAYS', '15', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3237', 'ECOMMERCE_SHIPMENT_COMPANY_LINK', 'http://www.dhl.com.br/pt/express/rastreamento.html?AWB=', 'ECOMMERCE');
INSERT INTO `ws_config` VALUES ('3238', 'PAGSEGURO_ENV', 'sandbox', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3239', 'PAGSEGURO_EMAIL', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3240', 'PAGSEGURO_NOTIFICATION_EMAIL', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3241', 'PAGSEGURO_TOKEN_SANDBOX', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3242', 'PAGSEGURO_APP_ID_SANDBOX', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3243', 'PAGSEGURO_APP_KEY_SANDBOX', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3244', 'PAGSEGURO_TOKEN_PRODUCTION', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3245', 'PAGSEGURO_APP_ID_PRODUCTION', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3246', 'PAGSEGURO_APP_KEY_PRODUCTION', '', 'PAGSEGURO');
INSERT INTO `ws_config` VALUES ('3247', 'EAD_REGISTER', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3248', 'EAD_HOTMART_EMAIL', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3249', 'EAD_HOTMART_TOKEN', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3250', 'EAD_HOTMART_NEGATIVATE', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3251', 'EAD_HOTMART_LOG', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3252', 'EAD_TASK_SUPPORT_DEFAULT', '1', 'EAD');
INSERT INTO `ws_config` VALUES ('3253', 'EAD_TASK_SUPPORT_EMAIL', 'suporte@seusite.com.br', 'EAD');
INSERT INTO `ws_config` VALUES ('3254', 'EAD_TASK_SUPPORT_MODERATE', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3255', 'EAD_TASK_SUPPORT_STUDENT_RESPONSE', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3256', 'EAD_TASK_SUPPORT_PENDING_REVIEW', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3257', 'EAD_TASK_SUPPORT_REPLY_PUBLISH', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3258', 'EAD_TASK_SUPPORT_LEVEL_DELETE', '10', 'EAD');
INSERT INTO `ws_config` VALUES ('3259', 'EAD_STUDENT_CERTIFICATION', '1', 'EAD');
INSERT INTO `ws_config` VALUES ('3260', 'EAD_STUDENT_MULTIPLE_LOGIN', '1', 'EAD');
INSERT INTO `ws_config` VALUES ('3261', 'EAD_STUDENT_MULTIPLE_LOGIN_BLOCK', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3262', 'EAD_STUDENT_CLASS_PERCENT', '100', 'EAD');
INSERT INTO `ws_config` VALUES ('3263', 'EAD_STUDENT_CLASS_AUTO_CHECK', '0', 'EAD');
INSERT INTO `ws_config` VALUES ('3264', 'AGENCY_CONTACT', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3265', 'AGENCY_EMAIL', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3266', 'AGENCY_PHONE', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3267', 'AGENCY_URL', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3268', 'AGENCY_NAME', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3269', 'AGENCY_ADDR', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3270', 'AGENCY_CITY', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3271', 'AGENCY_UF', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3272', 'AGENCY_ZIP', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3273', 'AGENCY_COUNTRY', '', 'AGENCY');
INSERT INTO `ws_config` VALUES ('3274', 'SITE_NAME', 'Work Control', 'SITE');
INSERT INTO `ws_config` VALUES ('3275', 'SITE_SUBNAME', 'professional control panel', 'SITE');
INSERT INTO `ws_config` VALUES ('3276', 'SITE_DESC', 'Um painel completo de fácil implementação criado para a turma do curso da UpInside, Work Series - Projeto e Produção!', 'SITE');
INSERT INTO `ws_config` VALUES ('3277', 'SITE_HEADER', '1', 'SITE');
INSERT INTO `ws_config` VALUES ('3278', 'SITE_COLOR', 'default', 'SITE');
INSERT INTO `ws_config` VALUES ('3279', 'SITE_FONT_NAME', 'Open Sans', 'SITE');
INSERT INTO `ws_config` VALUES ('3280', 'SITE_FONT_WHIGHT', '300,400,600,700,800', 'SITE');
INSERT INTO `ws_config` VALUES ('3281', 'SITE_ADDR_NAME', 'Work Control Pro Painel', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3282', 'SITE_ADDR_RS', 'Work Control', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3283', 'SITE_ADDR_EMAIL', 'contato@worcontrol.com.br', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3284', 'SITE_ADDR_SITE', 'www.workcontrol.com.br', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3285', 'SITE_ADDR_CNPJ', '00.000.000/0000-00', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3286', 'SITE_ADDR_IE', '000/0000000', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3287', 'SITE_ADDR_PHONE_A', '(48) 3371-5879', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3288', 'SITE_ADDR_PHONE_B', '(48) 8847-2629', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3289', 'SITE_ADDR_ADDR', 'Av Marcechal Floriano Peixoto, 1001', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3290', 'SITE_ADDR_CITY', 'São Paulo', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3291', 'SITE_ADDR_DISTRICT', 'Centro', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3292', 'SITE_ADDR_UF', 'SP', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3293', 'SITE_ADDR_ZIP', '99500-001', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3294', 'SITE_ADDR_COUNTRY', 'Brasil', 'SITE_ADDR');
INSERT INTO `ws_config` VALUES ('3295', 'SITE_SOCIAL_NAME', 'Robson V. Leite', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3296', 'SITE_SOCIAL_GOOGLE', '1', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3297', 'SITE_SOCIAL_GOOGLE_AUTHOR', '103958419096641225872', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3298', 'SITE_SOCIAL_GOOGLE_PAGE', '107305124528362639842', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3299', 'SITE_SOCIAL_FB', '1', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3300', 'SITE_SOCIAL_FB_APP', '0', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3301', 'SITE_SOCIAL_FB_AUTHOR', 'robvleite', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3302', 'SITE_SOCIAL_FB_PAGE', 'robsonvleite', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3303', 'SITE_SOCIAL_TWITTER', 'robsonvleite', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3304', 'SITE_SOCIAL_YOUTUBE', 'upinsidebr', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3305', 'SITE_SOCIAL_INSTAGRAM', 'robsonvleite', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3306', 'SITE_SOCIAL_LINKEDIN', 'robsonvleite', 'SOCIAL');
INSERT INTO `ws_config` VALUES ('3307', 'SITE_SOCIAL_SNAPCHAT', 'robsonvleite', 'SOCIAL');

-- ----------------------------
-- Table structure for ws_consultations
-- ----------------------------
DROP TABLE IF EXISTS `ws_consultations`;
CREATE TABLE `ws_consultations` (
  `consultation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `consultation_name` varchar(255) DEFAULT NULL,
  `consultation_email` varchar(255) DEFAULT NULL,
  `consultation_telephone` varchar(255) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL,
  `consultation_message` text DEFAULT NULL,
  `consultation_status` int(11) DEFAULT NULL,
  `consultation_response` longtext DEFAULT NULL,
  PRIMARY KEY (`consultation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_consultations
-- ----------------------------

-- ----------------------------
-- Table structure for ws_contacts
-- ----------------------------
DROP TABLE IF EXISTS `ws_contacts`;
CREATE TABLE `ws_contacts` (
  `contact_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_telephone` varchar(255) DEFAULT NULL,
  `contact_datecreate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_contacts
-- ----------------------------

-- ----------------------------
-- Table structure for ws_doctors
-- ----------------------------
DROP TABLE IF EXISTS `ws_doctors`;
CREATE TABLE `ws_doctors` (
  `doctor_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador do Médico',
  `doctor_cover` varchar(255) DEFAULT NULL COMMENT 'Imagem do Médico',
  `doctor_name` varchar(255) DEFAULT NULL COMMENT 'Nome do Médico',
  `doctor_url` varchar(255) DEFAULT NULL COMMENT 'URL do Médico',
  `doctor_time` int(11) DEFAULT NULL COMMENT 'Tempo Médio de Atendimento do Médico',
  `doctor_content` text DEFAULT NULL COMMENT 'Descrição do Médico',
  `doctor_curriculum` text DEFAULT NULL COMMENT 'Curriculum do Médico',
  `doctor_cpf` varchar(255) DEFAULT NULL COMMENT 'CPF do Médico',
  `doctor_rg` varchar(255) DEFAULT NULL COMMENT 'RG do Médico',
  `doctor_datebirth` timestamp NULL DEFAULT NULL COMMENT 'Data de Nascimento do Médico',
  `doctor_genre` int(11) DEFAULT NULL COMMENT 'Gênero do Médico',
  `doctor_initials_advice` varchar(255) DEFAULT NULL COMMENT 'Sigla do Conselho do Médico',
  `doctor_number_advice` varchar(255) DEFAULT NULL COMMENT 'Número do Conselho do Médico',
  `doctor_state_advice` varchar(2) DEFAULT NULL COMMENT 'Estado do Conselho do Médico',
  `doctor_specialty` int(11) DEFAULT NULL COMMENT 'Especialidade do Médico',
  `doctor_email` varchar(255) DEFAULT NULL COMMENT 'E-mail do Médico	',
  `doctor_telephone` varchar(255) DEFAULT NULL COMMENT 'Telefone do Médico	',
  `doctor_cell` varchar(255) DEFAULT NULL COMMENT 'Celular do Médico	',
  `doctor_facebook` varchar(255) DEFAULT NULL COMMENT 'Facebook do Médico',
  `doctor_instagram` varchar(255) DEFAULT NULL COMMENT 'Instagram do Médico',
  `doctor_linkedin` varchar(255) DEFAULT NULL COMMENT 'Linkedin do Médico',
  `doctor_twitter` varchar(255) DEFAULT NULL COMMENT 'Twitter do Médico',
  `doctor_google` varchar(255) DEFAULT NULL COMMENT 'Google do Médico',
  `doctor_youtube` varchar(255) DEFAULT NULL COMMENT 'YouTube do Médico',
  `doctor_zipcode` varchar(255) DEFAULT NULL COMMENT 'CEP do Médico',
  `doctor_street` varchar(255) DEFAULT NULL COMMENT 'Rua do Médico',
  `doctor_number` varchar(255) DEFAULT NULL COMMENT 'Número do Endereço do Médico',
  `doctor_complement` varchar(255) DEFAULT NULL COMMENT 'Complemento do Endereço do Médico',
  `doctor_district` varchar(255) DEFAULT NULL COMMENT 'Bairro do Médico',
  `doctor_city` varchar(255) DEFAULT NULL COMMENT 'Cidade do Médico',
  `doctor_state` varchar(2) DEFAULT NULL COMMENT 'Estado do Médico',
  `doctor_country` varchar(255) DEFAULT NULL COMMENT 'País do Médico',
  `doctor_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro do Médico No Sistema',
  `doctor_status` int(11) DEFAULT 0 COMMENT 'Status do Médico No Sistema (0 - Inativa | 1 - Ativa)',
  PRIMARY KEY (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_doctors
-- ----------------------------
INSERT INTO `ws_doctors` VALUES ('1', 'medicos/2020/05/dr-neupane-commodo-1589061462.png', 'Dr. Alan Durk', 'dr-alan-durk', null, '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '319.497.576-50', '19.052.884-9', '1991-02-14 00:00:00', '1', 'CRM', '1000', 'RJ', '4', 'alan@gmail.com', '(21) 2998-4233', '(21) 98276-6192', 'alan', 'alan', 'alan', 'alan', null, 'alan', '24120-110', 'Rua Alice Galvão', '33', null, 'Fonseca', 'Niterói', 'RJ', 'Brasil', '2020-05-09 23:59:37', '1');
INSERT INTO `ws_doctors` VALUES ('2', 'medicos/2020/05/dra-neupane-commodo-1589061494.png', 'Dr. Knos Ulmar', 'dr-knos-ulmar', null, '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '319.497.576-50', '19.052.884-9', '0000-00-00 00:00:00', '1', 'CRM', '1000', 'RJ', '1', 'knox@gmail.com', '(21) 2998-4233', '(21) 98276-6192', 'knox', 'knox', 'knox', 'knox', null, 'knox', '24120-110', 'Rua Alice Galvão', '33', null, 'Fonseca', 'Niterói', 'RJ', 'Brasil', '2020-05-09 23:58:41', '1');
INSERT INTO `ws_doctors` VALUES ('3', 'medicos/2020/05/dr-tedd-justice-1589061545.png', 'Dr. Tedd Justice', 'dr-tedd-justice', null, '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '319.497.576-50', '19.052.884-9', '1971-02-14 00:00:00', '1', 'CRM', '1000', 'RJ', '3', 'tedd@gmail.com', '(21) 2998-4233', '(21) 98276-6192', 'tedd', 'tedd', 'tedd', 'tedd', null, 'tedd', '24120-110', 'Rua Alice Galvão', '33', null, 'Fonseca', 'Niterói', 'RJ', 'Brasil', '2020-05-09 23:59:20', '1');
INSERT INTO `ws_doctors` VALUES ('4', 'medicos/2020/05/dr-jeremy-duncan-1589061603.png', 'Dr. Jeremy Duncan', 'dr-jeremy-duncan', null, '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '<p>Donec suscipit, nulla nec dapibusccumsan, arcu arcu sodales urna, nec auctor odio elit ac nisl. In non arcu dictum.</p>', '319.497.576-50', '19.052.884-9', '1986-02-14 00:00:00', '1', 'CRM', '1000', 'RJ', '2', 'jeremy@gmail.com', '(21) 2998-4233', '(21) 98276-6192', 'jeremy', 'jeremy', 'jeremy', 'jeremy', null, 'jeremy', '24120-110', 'Rua Alice Galvão', '33', null, 'Fonseca', 'Niterói', 'RJ', 'Brasil', '2020-05-10 00:00:19', '1');

-- ----------------------------
-- Table structure for ws_ead_classes
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_classes`;
CREATE TABLE `ws_ead_classes` (
  `course_id` int(11) unsigned DEFAULT NULL,
  `module_id` int(11) unsigned DEFAULT NULL,
  `class_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_title` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `class_video` varchar(255) DEFAULT NULL,
  `class_time` decimal(10,0) DEFAULT NULL,
  `class_order` int(11) DEFAULT NULL,
  `class_material` varchar(255) DEFAULT NULL,
  `class_desc` text DEFAULT NULL,
  `class_support` int(11) DEFAULT NULL,
  `class_created` timestamp NULL DEFAULT NULL,
  `class_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`class_id`),
  KEY `wc_class_module` (`module_id`),
  KEY `ws_class_order` (`course_id`),
  CONSTRAINT `wc_class_module` FOREIGN KEY (`module_id`) REFERENCES `ws_ead_modules` (`module_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ws_class_order` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_classes
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_courses
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_courses`;
CREATE TABLE `ws_ead_courses` (
  `course_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_author` int(11) unsigned DEFAULT NULL,
  `course_segment` int(11) unsigned DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `course_headline` varchar(255) DEFAULT NULL,
  `course_desc` text DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `course_cover` varchar(255) DEFAULT NULL,
  `course_created` timestamp NULL DEFAULT NULL,
  `course_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `course_status` int(11) NOT NULL DEFAULT 0,
  `course_order` int(11) DEFAULT NULL,
  `course_vendor_id` int(11) DEFAULT NULL,
  `course_vendor_access` int(11) DEFAULT NULL,
  `course_vendor_price` decimal(11,2) DEFAULT NULL,
  `course_vendor_page` varchar(255) DEFAULT NULL,
  `course_vendor_checkout` varchar(255) DEFAULT NULL,
  `course_vendor_renew` varchar(255) DEFAULT NULL,
  `course_certification_workload` int(11) DEFAULT NULL,
  `course_certification_request` int(11) DEFAULT NULL,
  `course_certification_mockup` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`course_id`),
  KEY `wc_course_author` (`course_author`),
  KEY `wc_course_segment` (`course_segment`),
  CONSTRAINT `wc_course_author` FOREIGN KEY (`course_author`) REFERENCES `ws_users` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `wc_course_segment` FOREIGN KEY (`course_segment`) REFERENCES `ws_ead_courses_segments` (`segment_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_courses
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_courses_bonus
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_courses_bonus`;
CREATE TABLE `ws_ead_courses_bonus` (
  `course_id` int(11) unsigned DEFAULT NULL,
  `bonus_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bonus_course_id` int(11) unsigned DEFAULT NULL,
  `bonus_ever` int(11) DEFAULT NULL,
  `bonus_ever_date` date DEFAULT NULL,
  `bonus_wait` int(11) DEFAULT NULL,
  PRIMARY KEY (`bonus_id`),
  KEY `wc_ead_course_bonus` (`course_id`),
  KEY `wc_ead_bonus_id` (`bonus_course_id`),
  CONSTRAINT `wc_ead_bonus_id` FOREIGN KEY (`bonus_course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_ead_course_bonus` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_courses_bonus
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_courses_segments
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_courses_segments`;
CREATE TABLE `ws_ead_courses_segments` (
  `segment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `segment_title` varchar(255) DEFAULT NULL,
  `segment_name` varchar(255) DEFAULT NULL,
  `segment_desc` text DEFAULT NULL,
  `segment_order` int(11) DEFAULT NULL,
  `segment_icon` varchar(255) DEFAULT NULL,
  `segment_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`segment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_courses_segments
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_enrollments
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_enrollments`;
CREATE TABLE `ws_ead_enrollments` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `course_id` int(11) unsigned DEFAULT NULL,
  `enrollment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_order` int(11) unsigned DEFAULT NULL,
  `enrollment_bonus` int(11) unsigned DEFAULT NULL,
  `enrollment_start` timestamp NULL DEFAULT NULL,
  `enrollment_access` timestamp NULL DEFAULT NULL,
  `enrollment_end` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`enrollment_id`),
  KEY `wc_ead_student_course` (`course_id`),
  KEY `wc_ead_student_user` (`user_id`),
  KEY `wc_entollment_bonus` (`enrollment_bonus`),
  KEY `wc_enrollment_order` (`enrollment_order`),
  CONSTRAINT `wc_ead_student_course` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_ead_student_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_enrollment_order` FOREIGN KEY (`enrollment_order`) REFERENCES `ws_ead_orders` (`order_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_entollment_bonus` FOREIGN KEY (`enrollment_bonus`) REFERENCES `ws_ead_enrollments` (`enrollment_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_enrollments
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_modules
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_modules`;
CREATE TABLE `ws_ead_modules` (
  `course_id` int(11) unsigned DEFAULT NULL,
  `module_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_title` varchar(255) DEFAULT '',
  `module_desc` text DEFAULT NULL,
  `module_name` varchar(255) DEFAULT NULL,
  `module_order` int(11) NOT NULL DEFAULT 0,
  `module_release` int(11) NOT NULL DEFAULT 0,
  `module_release_date` timestamp NULL DEFAULT NULL,
  `module_required` int(11) DEFAULT 0,
  `module_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`module_id`),
  KEY `wc_course_module` (`course_id`),
  CONSTRAINT `wc_course_module` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_modules
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_orders
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_orders`;
CREATE TABLE `ws_ead_orders` (
  `user_id` int(10) unsigned DEFAULT NULL,
  `course_id` int(11) unsigned DEFAULT NULL,
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_product_id` int(11) DEFAULT NULL,
  `order_transaction` varchar(255) DEFAULT NULL,
  `order_callback_type` int(11) DEFAULT NULL,
  `order_off` varchar(255) DEFAULT NULL,
  `order_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_currency` varchar(255) DEFAULT NULL,
  `order_payment_type` varchar(255) DEFAULT NULL,
  `order_purchase_date` timestamp NULL DEFAULT NULL,
  `order_warranty_date` timestamp NULL DEFAULT NULL,
  `order_confirmation_purchase_date` timestamp NULL DEFAULT NULL,
  `order_sck` varchar(255) DEFAULT NULL,
  `order_src` varchar(255) DEFAULT NULL,
  `order_aff` varchar(255) DEFAULT NULL,
  `order_aff_name` varchar(255) DEFAULT NULL,
  `order_cms_aff` varchar(255) NOT NULL DEFAULT '0.00',
  `order_cms_marketplace` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_cms_vendor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_status` varchar(255) DEFAULT NULL,
  `order_chargeback` timestamp NULL DEFAULT NULL,
  `order_delivered` int(11) DEFAULT NULL,
  `order_signature` varchar(255) DEFAULT NULL,
  `order_signature_plan` varchar(255) DEFAULT NULL,
  `order_signature_recurrency` int(11) DEFAULT NULL,
  `order_signature_period` int(11) DEFAULT NULL,
  `order_signature_status` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `wc_ead_order_user` (`user_id`),
  KEY `wc_ead_order_course` (`course_id`),
  CONSTRAINT `wc_ead_order_course` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_ead_order_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_orders
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_students_notes
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_students_notes`;
CREATE TABLE `ws_ead_students_notes` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `admin_id` int(11) unsigned DEFAULT NULL,
  `note_text` varchar(255) DEFAULT NULL,
  `note_datetime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_students_notes
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_student_certificates
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_student_certificates`;
CREATE TABLE `ws_ead_student_certificates` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `course_id` int(11) unsigned DEFAULT NULL,
  `enrollment_id` int(11) unsigned DEFAULT NULL,
  `certificate_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `certificate_key` varchar(255) DEFAULT NULL,
  `certificate_issued` date DEFAULT NULL,
  `certificate_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`certificate_id`),
  KEY `wc_certificate_user` (`user_id`),
  KEY `wc_certificate_course` (`course_id`),
  KEY `wc_certificate_enrollment` (`enrollment_id`),
  CONSTRAINT `wc_certificate_course` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_certificate_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `ws_ead_enrollments` (`enrollment_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_certificate_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_student_certificates
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_student_classes
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_student_classes`;
CREATE TABLE `ws_ead_student_classes` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `course_id` int(11) unsigned DEFAULT NULL,
  `class_id` int(11) unsigned DEFAULT NULL,
  `enrollment_id` int(11) unsigned DEFAULT NULL,
  `student_class_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_class_views` int(11) DEFAULT NULL,
  `student_class_play` timestamp NULL DEFAULT NULL,
  `student_class_free` int(11) DEFAULT NULL,
  `student_class_seconds` int(11) DEFAULT NULL,
  `student_class_check` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`student_class_id`),
  KEY `wc_student_class_user` (`user_id`),
  KEY `wc_student_class` (`class_id`),
  KEY `wc_student_class_course` (`course_id`),
  KEY `wc_student_class_enroll` (`enrollment_id`),
  CONSTRAINT `wc_student_class` FOREIGN KEY (`class_id`) REFERENCES `ws_ead_classes` (`class_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_student_class_course` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_student_class_enroll` FOREIGN KEY (`enrollment_id`) REFERENCES `ws_ead_enrollments` (`enrollment_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_student_class_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_student_classes
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_student_downloads
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_student_downloads`;
CREATE TABLE `ws_ead_student_downloads` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `user_ip` varchar(255) DEFAULT NULL,
  `course_id` int(11) unsigned DEFAULT NULL,
  `class_id` int(11) unsigned DEFAULT NULL,
  `download_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `download_file` varchar(255) DEFAULT NULL,
  `download_filename` varchar(2555) DEFAULT NULL,
  `download_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`download_id`),
  KEY `ws_download_user` (`user_id`),
  KEY `ws_download_course` (`course_id`),
  KEY `ws_download_class` (`class_id`),
  CONSTRAINT `ws_download_class` FOREIGN KEY (`class_id`) REFERENCES `ws_ead_classes` (`class_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ws_download_course` FOREIGN KEY (`course_id`) REFERENCES `ws_ead_courses` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `ws_download_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_ead_student_downloads
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_support
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_support`;
CREATE TABLE `ws_ead_support` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `enrollment_id` int(11) unsigned DEFAULT NULL,
  `class_id` int(11) unsigned DEFAULT NULL,
  `support_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `support_content` text DEFAULT NULL,
  `support_status` int(11) DEFAULT NULL,
  `support_open` timestamp NULL DEFAULT NULL,
  `support_reply` timestamp NULL DEFAULT NULL,
  `support_close` timestamp NULL DEFAULT NULL,
  `support_review` int(11) DEFAULT NULL,
  `support_comment` text DEFAULT NULL,
  `support_published` int(11) DEFAULT NULL,
  PRIMARY KEY (`support_id`),
  KEY `wc_ead_support_class` (`class_id`),
  KEY `wc_ead_support_user` (`user_id`),
  KEY `wc_ead_student_class` (`enrollment_id`),
  CONSTRAINT `wc_ead_support_class` FOREIGN KEY (`class_id`) REFERENCES `ws_ead_classes` (`class_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_ead_support_enroll` FOREIGN KEY (`enrollment_id`) REFERENCES `ws_ead_enrollments` (`enrollment_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_ead_support_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Status 1 = Aberto\nStatus 2 = Respondido\nStatus 3 = Completo';

-- ----------------------------
-- Records of ws_ead_support
-- ----------------------------

-- ----------------------------
-- Table structure for ws_ead_support_reply
-- ----------------------------
DROP TABLE IF EXISTS `ws_ead_support_reply`;
CREATE TABLE `ws_ead_support_reply` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `enrollment_id` int(10) unsigned DEFAULT NULL,
  `support_id` int(11) unsigned DEFAULT NULL,
  `response_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `response_content` text DEFAULT NULL,
  `response_open` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`response_id`),
  KEY `wc_ead_support_class` (`support_id`),
  KEY `wc_ead_support_user` (`user_id`),
  KEY `wc_ead_support_reply_enroll` (`enrollment_id`),
  CONSTRAINT `wc_ead_support_reply_enroll` FOREIGN KEY (`enrollment_id`) REFERENCES `ws_ead_enrollments` (`enrollment_id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `wc_response_support` FOREIGN KEY (`support_id`) REFERENCES `ws_ead_support` (`support_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_response_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Status 1 = Aberto\nStatus 2 = Respondido\nStatus 3 = Completo';

-- ----------------------------
-- Records of ws_ead_support_reply
-- ----------------------------

-- ----------------------------
-- Table structure for ws_faq
-- ----------------------------
DROP TABLE IF EXISTS `ws_faq`;
CREATE TABLE `ws_faq` (
  `faq_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `faq_title` varchar(255) DEFAULT NULL,
  `faq_desc` text DEFAULT NULL,
  `faq_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_faq
-- ----------------------------

-- ----------------------------
-- Table structure for ws_gallery
-- ----------------------------
DROP TABLE IF EXISTS `ws_gallery`;
CREATE TABLE `ws_gallery` (
  `gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_title` varchar(255) NOT NULL,
  `gallery_description` longtext DEFAULT NULL,
  `gallery_name` varchar(255) DEFAULT NULL,
  `gallery_cover` varchar(255) DEFAULT NULL,
  `gallery_category` tinyint(1) DEFAULT NULL,
  `gallery_status` tinyint(1) DEFAULT NULL,
  `gallery_order` int(11) DEFAULT NULL,
  `gallery_date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_gallery
-- ----------------------------
INSERT INTO `ws_gallery` VALUES ('1', 'Denthal', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>', 'denthal', 'gallery/2020/05/denthal-1589060518.jpg', '1', '1', null, '2020-05-09 18:41:58');
INSERT INTO `ws_gallery` VALUES ('2', 'Gastroentorology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.</p>', 'gastroentorology', 'gallery/2020/05/gastroentorology-1589060656.jpg', '2', '1', null, '2020-05-09 18:44:18');
INSERT INTO `ws_gallery` VALUES ('3', 'Surgeries', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s</p>', 'surgeries', 'gallery/2020/05/surgeries-1589060712.jpg', '3', '1', null, '2020-05-09 18:45:15');
INSERT INTO `ws_gallery` VALUES ('4', 'Cardiology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s</p>', 'cardiology', 'gallery/2020/05/cardiology-1589060744.jpg', '4', '1', null, '2020-05-09 18:45:46');
INSERT INTO `ws_gallery` VALUES ('5', 'Patology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s</p>', 'patology', 'gallery/2020/05/patology-1589060777.jpg', '5', '1', null, '2020-05-09 18:46:19');

-- ----------------------------
-- Table structure for ws_gallery_images
-- ----------------------------
DROP TABLE IF EXISTS `ws_gallery_images`;
CREATE TABLE `ws_gallery_images` (
  `gallery_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_id` int(10) unsigned NOT NULL,
  `gallery_file` varchar(255) NOT NULL,
  `gallery_image_order` int(11) DEFAULT NULL,
  `gallery_image_legend` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gallery_image_id`),
  KEY `ws_gallery` (`gallery_id`),
  CONSTRAINT `ws_gallery` FOREIGN KEY (`gallery_id`) REFERENCES `ws_gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_gallery_images
-- ----------------------------
INSERT INTO `ws_gallery_images` VALUES ('1', '1', 'gallery/2020/05/denthal-11589060552.jpg', null, 'Denthal');
INSERT INTO `ws_gallery_images` VALUES ('2', '1', 'gallery/2020/05/denthal-21589060552.jpg', null, 'Denthal');
INSERT INTO `ws_gallery_images` VALUES ('3', '1', 'gallery/2020/05/denthal-31589060552.jpg', null, 'Denthal');
INSERT INTO `ws_gallery_images` VALUES ('4', '2', 'gallery/2020/05/gastroentorology-11589060668.jpg', null, 'Gastroentorology');
INSERT INTO `ws_gallery_images` VALUES ('5', '2', 'gallery/2020/05/gastroentorology-21589060669.jpg', null, 'Gastroentorology');
INSERT INTO `ws_gallery_images` VALUES ('6', '2', 'gallery/2020/05/gastroentorology-31589060669.jpg', null, 'Gastroentorology');
INSERT INTO `ws_gallery_images` VALUES ('7', '3', 'gallery/2020/05/surgeries-11589060724.jpg', null, 'Surgeries');
INSERT INTO `ws_gallery_images` VALUES ('8', '3', 'gallery/2020/05/surgeries-21589060724.jpg', null, 'Surgeries');
INSERT INTO `ws_gallery_images` VALUES ('9', '3', 'gallery/2020/05/surgeries-31589060724.jpg', null, 'Surgeries');
INSERT INTO `ws_gallery_images` VALUES ('10', '4', 'gallery/2020/05/cardiology-11589060755.jpg', null, 'Cardiology');
INSERT INTO `ws_gallery_images` VALUES ('11', '4', 'gallery/2020/05/cardiology-21589060755.jpg', null, 'Cardiology');
INSERT INTO `ws_gallery_images` VALUES ('12', '4', 'gallery/2020/05/cardiology-31589060755.jpg', null, 'Cardiology');
INSERT INTO `ws_gallery_images` VALUES ('13', '5', 'gallery/2020/05/patology-11589060797.jpg', null, 'Patology');
INSERT INTO `ws_gallery_images` VALUES ('14', '5', 'gallery/2020/05/patology-21589060797.jpg', null, 'Patology');
INSERT INTO `ws_gallery_images` VALUES ('15', '5', 'gallery/2020/05/patology-31589060797.jpg', null, 'Patology');

-- ----------------------------
-- Table structure for ws_gallery_videos
-- ----------------------------
DROP TABLE IF EXISTS `ws_gallery_videos`;
CREATE TABLE `ws_gallery_videos` (
  `videos_id` int(11) NOT NULL AUTO_INCREMENT,
  `videos_cover` varchar(255) NOT NULL,
  `videos_title` varchar(255) NOT NULL,
  `videos_name` varchar(255) NOT NULL,
  `videos_subtitle` varchar(255) NOT NULL,
  `videos_message` text NOT NULL,
  `videos_content` text NOT NULL,
  `videos_link` varchar(255) NOT NULL,
  `videos_tags` varchar(255) NOT NULL,
  `videos_views` int(11) NOT NULL,
  `videos_likes` int(11) NOT NULL,
  `videos_status` int(11) NOT NULL,
  `videos_author` int(11) NOT NULL,
  `videos_date` timestamp NULL DEFAULT NULL,
  `videos_lastview` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`videos_id`),
  KEY `videos_author` (`videos_author`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_gallery_videos
-- ----------------------------

-- ----------------------------
-- Table structure for ws_hellobar
-- ----------------------------
DROP TABLE IF EXISTS `ws_hellobar`;
CREATE TABLE `ws_hellobar` (
  `user_id` int(11) unsigned NOT NULL,
  `hello_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hello_title` varchar(255) DEFAULT NULL,
  `hello_image` varchar(244) DEFAULT NULL,
  `hello_cta` varchar(70) DEFAULT NULL,
  `hello_link` varchar(255) DEFAULT NULL,
  `hello_color` varchar(50) DEFAULT NULL,
  `hello_position` varchar(70) DEFAULT NULL,
  `hello_rule` varchar(255) DEFAULT NULL,
  `hello_date` timestamp NULL DEFAULT NULL,
  `hello_start` timestamp NULL DEFAULT NULL,
  `hello_end` timestamp NULL DEFAULT NULL,
  `hello_views` int(11) DEFAULT NULL,
  `hello_clicks` int(11) DEFAULT NULL,
  `hello_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`hello_id`),
  KEY `wc_hello_user` (`user_id`),
  CONSTRAINT `wc_hello_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_hellobar
-- ----------------------------

-- ----------------------------
-- Table structure for ws_orders
-- ----------------------------
DROP TABLE IF EXISTS `ws_orders`;
CREATE TABLE `ws_orders` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_status` int(11) NOT NULL DEFAULT 0,
  `order_payment` int(11) NOT NULL DEFAULT 1,
  `order_price` decimal(11,2) NOT NULL DEFAULT 0.00,
  `order_installments` decimal(10,0) DEFAULT NULL,
  `order_installment` decimal(11,2) DEFAULT NULL,
  `order_coupon` decimal(11,0) DEFAULT NULL,
  `order_free` decimal(11,2) DEFAULT NULL,
  `order_billet` varchar(255) DEFAULT NULL,
  `order_code` varchar(255) DEFAULT NULL,
  `order_addr` int(11) DEFAULT NULL,
  `order_shipcode` int(11) DEFAULT NULL,
  `order_shipprice` decimal(11,2) DEFAULT NULL,
  `order_shipment` date DEFAULT NULL,
  `order_tracking` varchar(255) DEFAULT NULL,
  `order_nfepdf` varchar(255) DEFAULT NULL,
  `order_nfexml` varchar(255) DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT NULL,
  `order_update` timestamp NULL DEFAULT NULL,
  `order_mail_processing` int(11) DEFAULT NULL,
  `order_mail_completed` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `wc_order_user` (`user_id`),
  CONSTRAINT `wc_order_user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_orders
-- ----------------------------

-- ----------------------------
-- Table structure for ws_orders_items
-- ----------------------------
DROP TABLE IF EXISTS `ws_orders_items`;
CREATE TABLE `ws_orders_items` (
  `order_id` int(11) unsigned NOT NULL,
  `pdt_id` int(11) DEFAULT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) DEFAULT NULL,
  `item_price` decimal(11,2) DEFAULT NULL,
  `item_amount` decimal(11,0) DEFAULT 1,
  PRIMARY KEY (`item_id`),
  KEY `wc_order` (`order_id`),
  CONSTRAINT `wc_order` FOREIGN KEY (`order_id`) REFERENCES `ws_orders` (`order_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_orders_items
-- ----------------------------

-- ----------------------------
-- Table structure for ws_pages
-- ----------------------------
DROP TABLE IF EXISTS `ws_pages`;
CREATE TABLE `ws_pages` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_video` varchar(255) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `page_content` text DEFAULT NULL,
  `page_date` timestamp NULL DEFAULT NULL,
  `page_revision` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `page_order` int(11) DEFAULT NULL,
  `page_status` int(11) NOT NULL DEFAULT 0,
  `page_cover` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_pages
-- ----------------------------

-- ----------------------------
-- Table structure for ws_pages_images
-- ----------------------------
DROP TABLE IF EXISTS `ws_pages_images`;
CREATE TABLE `ws_pages_images` (
  `page_id` int(11) unsigned NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wc_pages` (`page_id`),
  CONSTRAINT `wc_pages` FOREIGN KEY (`page_id`) REFERENCES `ws_pages` (`page_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_pages_images
-- ----------------------------

-- ----------------------------
-- Table structure for ws_posts
-- ----------------------------
DROP TABLE IF EXISTS `ws_posts`;
CREATE TABLE `ws_posts` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_name` varchar(255) NOT NULL DEFAULT '',
  `post_title` varchar(255) DEFAULT NULL,
  `post_subtitle` text DEFAULT NULL,
  `post_content` longtext DEFAULT NULL,
  `post_cover` varchar(255) DEFAULT NULL,
  `post_video` varchar(255) DEFAULT NULL,
  `post_date` timestamp NULL DEFAULT NULL,
  `post_author` int(11) unsigned DEFAULT NULL,
  `post_category` int(11) unsigned DEFAULT NULL,
  `post_category_parent` varchar(255) DEFAULT NULL,
  `post_views` decimal(10,0) DEFAULT 0,
  `post_lastview` timestamp NULL DEFAULT NULL,
  `post_status` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(255) DEFAULT NULL,
  `post_instant_article` int(11) DEFAULT NULL,
  `post_amp` int(11) DEFAULT NULL,
  `post_tags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `wc_post_category` (`post_category`),
  KEY `wc_post_author` (`post_author`),
  CONSTRAINT `wc_post_author` FOREIGN KEY (`post_author`) REFERENCES `ws_users` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `wc_post_category` FOREIGN KEY (`post_category`) REFERENCES `ws_categories` (`category_id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_posts
-- ----------------------------
INSERT INTO `ws_posts` VALUES ('1', 'using-the-latest-medical-technology-1', 'Using the latest medical technology', 'Using the latest medical technology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'images/2020/05/using-the-latest-medical-technology-1-1589061191.jpg', null, '2020-05-09 14:54:00', '1', '1', '2', '0', null, '1', 'post', null, null, null);
INSERT INTO `ws_posts` VALUES ('2', 'using-the-latest-medical-technology-2', 'Using the latest medical technology', 'Using the latest medical technology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'images/2020/05/this-is-simple-and-clean-post-2-1589061171.jpg', null, '2020-05-09 14:55:00', '1', '3', '4', '0', null, '1', 'post', null, null, null);
INSERT INTO `ws_posts` VALUES ('3', 'using-the-latest-medical-technology-3', 'Using the latest medical technology', 'Using the latest medical technology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'images/2020/05/this-is-simple-and-clean-post-3-1589061153.jpg', null, '2020-05-09 14:56:00', '1', '5', '6', '0', null, '1', 'post', null, null, null);
INSERT INTO `ws_posts` VALUES ('4', 'using-the-latest-medical-technology-4', 'Using the latest medical technology', 'Using the latest medical technology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'images/2020/05/using-the-latest-medical-technology-4-1589061142.jpg', null, '2020-05-09 14:57:00', '1', '5', '6', '0', null, '1', 'post', null, null, null);
INSERT INTO `ws_posts` VALUES ('5', 'using-the-latest-medical-technology-5', 'Using the latest medical technology', 'Using the latest medical technology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'images/2020/05/using-the-latest-medical-technology-5-1589061118.jpg', null, '2020-05-09 15:02:00', '1', '1', '2', '0', null, '1', 'post', null, null, null);
INSERT INTO `ws_posts` VALUES ('7', 'using-the-latest-medical-technology', 'Using the latest medical technology', 'Using the latest medical technology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'images/2020/05/using-the-latest-medical-technology-1589061084.jpg', null, '2020-05-09 15:04:00', '1', '1', '2', '21', '2020-05-14 23:55:45', '1', 'post', null, null, null);

-- ----------------------------
-- Table structure for ws_posts_images
-- ----------------------------
DROP TABLE IF EXISTS `ws_posts_images`;
CREATE TABLE `ws_posts_images` (
  `post_id` int(11) unsigned DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wc_posts_images` (`post_id`),
  CONSTRAINT `wc_posts_images` FOREIGN KEY (`post_id`) REFERENCES `ws_posts` (`post_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_posts_images
-- ----------------------------

-- ----------------------------
-- Table structure for ws_procedures
-- ----------------------------
DROP TABLE IF EXISTS `ws_procedures`;
CREATE TABLE `ws_procedures` (
  `procedure_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador do Procedimento',
  `procedure_title` varchar(255) DEFAULT NULL COMMENT 'Título do Procedimento',
  `procedure_category` int(11) NOT NULL COMMENT 'Categoria do Procedimento',
  `procedure_code` varchar(255) DEFAULT NULL COMMENT 'Código do Procedimento',
  `procedure_price` decimal(10,2) DEFAULT NULL COMMENT 'Valor do Procedimento',
  `procedure_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro do Procedimento No Sistema	',
  PRIMARY KEY (`procedure_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_procedures
-- ----------------------------

-- ----------------------------
-- Table structure for ws_products
-- ----------------------------
DROP TABLE IF EXISTS `ws_products`;
CREATE TABLE `ws_products` (
  `pdt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pdt_code` varchar(255) NOT NULL DEFAULT '',
  `pdt_parent` int(11) unsigned DEFAULT NULL,
  `pdt_title` varchar(255) DEFAULT NULL,
  `pdt_subtitle` varchar(255) DEFAULT NULL,
  `pdt_name` varchar(255) DEFAULT NULL,
  `pdt_hotlink` varchar(255) DEFAULT NULL,
  `pdt_cover` varchar(255) DEFAULT NULL,
  `pdt_content` text DEFAULT NULL,
  `pdt_price` decimal(11,2) DEFAULT NULL,
  `pdt_inventory` decimal(10,0) NOT NULL DEFAULT 0,
  `pdt_delivered` decimal(10,0) NOT NULL DEFAULT 0,
  `pdt_brand` int(11) unsigned DEFAULT NULL,
  `pdt_category` int(11) unsigned DEFAULT NULL,
  `pdt_subcategory` int(11) unsigned DEFAULT NULL,
  `pdt_offer_price` decimal(11,2) DEFAULT NULL,
  `pdt_offer_start` timestamp NULL DEFAULT NULL,
  `pdt_offer_end` timestamp NULL DEFAULT NULL,
  `pdt_dimension_heigth` decimal(11,0) NOT NULL DEFAULT 0,
  `pdt_dimension_width` decimal(11,0) NOT NULL DEFAULT 0,
  `pdt_dimension_depth` decimal(11,0) NOT NULL DEFAULT 0,
  `pdt_dimension_weight` decimal(11,0) NOT NULL DEFAULT 0,
  `pdt_created` timestamp NULL DEFAULT NULL,
  `pdt_views` decimal(10,0) DEFAULT 0,
  `pdt_lastview` timestamp NULL DEFAULT NULL,
  `pdt_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`pdt_id`),
  KEY `wc_products_brands` (`pdt_brand`),
  KEY `wc_products_categories` (`pdt_category`),
  KEY `wc_products_subcategory` (`pdt_subcategory`),
  KEY `wc_product_parent` (`pdt_parent`),
  CONSTRAINT `wc_product_parent` FOREIGN KEY (`pdt_parent`) REFERENCES `ws_products` (`pdt_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `wc_products_brands` FOREIGN KEY (`pdt_brand`) REFERENCES `ws_products_brands` (`brand_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `wc_products_categories` FOREIGN KEY (`pdt_category`) REFERENCES `ws_products_categories` (`cat_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `wc_products_subcategory` FOREIGN KEY (`pdt_subcategory`) REFERENCES `ws_products_categories` (`cat_id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_products
-- ----------------------------

-- ----------------------------
-- Table structure for ws_products_brands
-- ----------------------------
DROP TABLE IF EXISTS `ws_products_brands`;
CREATE TABLE `ws_products_brands` (
  `brand_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand_title` varchar(255) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `brand_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_products_brands
-- ----------------------------

-- ----------------------------
-- Table structure for ws_products_categories
-- ----------------------------
DROP TABLE IF EXISTS `ws_products_categories`;
CREATE TABLE `ws_products_categories` (
  `cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_parent` int(11) unsigned DEFAULT NULL,
  `cat_title` varchar(255) DEFAULT NULL,
  `cat_name` varchar(255) DEFAULT NULL,
  `cat_sizes` varchar(255) DEFAULT NULL,
  `cat_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_products_categories
-- ----------------------------

-- ----------------------------
-- Table structure for ws_products_coupons
-- ----------------------------
DROP TABLE IF EXISTS `ws_products_coupons`;
CREATE TABLE `ws_products_coupons` (
  `cp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cp_title` varchar(255) DEFAULT NULL,
  `cp_coupon` varchar(255) DEFAULT NULL,
  `cp_discount` decimal(11,0) DEFAULT NULL,
  `cp_start` timestamp NULL DEFAULT NULL,
  `cp_end` timestamp NULL DEFAULT NULL,
  `cp_hits` decimal(11,0) DEFAULT NULL,
  PRIMARY KEY (`cp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_products_coupons
-- ----------------------------

-- ----------------------------
-- Table structure for ws_products_gallery
-- ----------------------------
DROP TABLE IF EXISTS `ws_products_gallery`;
CREATE TABLE `ws_products_gallery` (
  `product_id` int(11) unsigned NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wc_produtcts_gallery` (`product_id`),
  CONSTRAINT `wc_produtcts_gallery` FOREIGN KEY (`product_id`) REFERENCES `ws_products` (`pdt_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_products_gallery
-- ----------------------------

-- ----------------------------
-- Table structure for ws_products_images
-- ----------------------------
DROP TABLE IF EXISTS `ws_products_images`;
CREATE TABLE `ws_products_images` (
  `product_id` int(11) unsigned NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wc_products_images` (`product_id`),
  CONSTRAINT `wc_products_images` FOREIGN KEY (`product_id`) REFERENCES `ws_products` (`pdt_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_products_images
-- ----------------------------

-- ----------------------------
-- Table structure for ws_products_stock
-- ----------------------------
DROP TABLE IF EXISTS `ws_products_stock`;
CREATE TABLE `ws_products_stock` (
  `stock_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pdt_id` int(11) unsigned NOT NULL,
  `stock_code` varchar(255) NOT NULL DEFAULT '',
  `stock_inventory` decimal(10,0) NOT NULL DEFAULT 0,
  `stock_sold` decimal(10,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`stock_id`),
  KEY `wc_products_stock` (`pdt_id`),
  CONSTRAINT `wc_products_stock` FOREIGN KEY (`pdt_id`) REFERENCES `ws_products` (`pdt_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_products_stock
-- ----------------------------

-- ----------------------------
-- Table structure for ws_properties
-- ----------------------------
DROP TABLE IF EXISTS `ws_properties`;
CREATE TABLE `ws_properties` (
  `realty_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realty_cover` varchar(255) DEFAULT NULL,
  `realty_title` varchar(255) DEFAULT NULL,
  `realty_name` varchar(255) DEFAULT NULL,
  `realty_ref` varchar(255) DEFAULT NULL,
  `realty_price` decimal(11,2) DEFAULT NULL,
  `realty_desc` text DEFAULT NULL,
  `realty_finality` int(11) DEFAULT NULL,
  `realty_type` int(11) DEFAULT NULL,
  `realty_builtarea` decimal(11,2) DEFAULT NULL,
  `realty_totalarea` decimal(11,2) DEFAULT NULL,
  `realty_bedrooms` decimal(11,0) DEFAULT NULL,
  `realty_apartments` decimal(10,0) DEFAULT NULL,
  `realty_bathrooms` decimal(11,0) DEFAULT NULL,
  `realty_parkings` decimal(10,0) DEFAULT NULL,
  `realty_particulars` text DEFAULT NULL,
  `realty_transaction` int(11) DEFAULT NULL,
  `realty_state` varchar(10) DEFAULT NULL,
  `realty_city` varchar(255) DEFAULT NULL,
  `realty_district` varchar(255) DEFAULT NULL,
  `realty_date` timestamp NULL DEFAULT NULL,
  `realty_observation` int(11) DEFAULT NULL,
  `realty_contact` int(11) unsigned DEFAULT NULL,
  `realty_status` int(11) NOT NULL DEFAULT 0,
  `realty_views` decimal(11,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`realty_id`),
  KEY `wc_propertie_author` (`realty_contact`),
  CONSTRAINT `wc_propertie_author` FOREIGN KEY (`realty_contact`) REFERENCES `ws_users` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_properties
-- ----------------------------

-- ----------------------------
-- Table structure for ws_properties_gallery
-- ----------------------------
DROP TABLE IF EXISTS `ws_properties_gallery`;
CREATE TABLE `ws_properties_gallery` (
  `realty_id` int(11) unsigned DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wc_properties_gallery` (`realty_id`),
  CONSTRAINT `wc_properties_gallery` FOREIGN KEY (`realty_id`) REFERENCES `ws_properties` (`realty_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_properties_gallery
-- ----------------------------

-- ----------------------------
-- Table structure for ws_search
-- ----------------------------
DROP TABLE IF EXISTS `ws_search`;
CREATE TABLE `ws_search` (
  `search_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `search_key` varchar(255) DEFAULT NULL,
  `search_count` decimal(11,0) DEFAULT NULL,
  `search_date` timestamp NULL DEFAULT NULL,
  `search_commit` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `search_publish` int(11) DEFAULT NULL,
  PRIMARY KEY (`search_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_search
-- ----------------------------

-- ----------------------------
-- Table structure for ws_services
-- ----------------------------
DROP TABLE IF EXISTS `ws_services`;
CREATE TABLE `ws_services` (
  `service_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador do Serviço',
  `service_image` varchar(255) DEFAULT NULL COMMENT 'Imagem do Serviço',
  `service_icon_type` int(11) DEFAULT NULL COMMENT 'Tipo do Ícone do Serviço ',
  `service_icon_text` varchar(255) DEFAULT NULL COMMENT 'Texto do Ícone do Serviço (Ex.: IcoMoon, Font Awesome e Etc)',
  `service_icon` varchar(255) DEFAULT NULL COMMENT 'Ícone do Serviço',
  `service_title` varchar(255) DEFAULT NULL COMMENT 'Título do Serviço',
  `service_name` varchar(255) NOT NULL COMMENT 'URL dos Serviços Para Exibição No Site',
  `service_content` text DEFAULT NULL COMMENT 'Descrição do Serviço',
  `service_price` decimal(10,2) DEFAULT NULL COMMENT 'Preço do Serviço',
  `service_type` int(11) DEFAULT NULL COMMENT 'Tipo do Ícone do Serviço ',
  `service_image_one` varchar(255) DEFAULT NULL COMMENT 'Imagem 1',
  `service_image_two` varchar(255) DEFAULT NULL COMMENT 'Imagem 2',
  `service_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro do Serviço No Sistema',
  `service_status` int(11) DEFAULT 0 COMMENT 'Status do Serviço No Sistema (0 - Inativa | 1 - Ativa)',
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_services
-- ----------------------------
INSERT INTO `ws_services` VALUES ('1', 'servicos/2020/05/image.jpg', '2', 'ambulance', null, 'Emergency Services', 'emergency-services', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '1', null, null, '2020-05-14 22:07:50', '1');
INSERT INTO `ws_services` VALUES ('2', 'servicos/2020/05/qualified-doctors-image.jpg', '2', 'users', null, 'Qualified Doctors', 'qualified-doctors', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '1', null, null, '2020-05-14 22:08:56', '1');
INSERT INTO `ws_services` VALUES ('3', 'servicos/2020/05/24-7-support-image.jpg', '2', 'phone-square', null, '24/7 Support', '24-7-support', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '1', null, null, '2020-05-14 22:09:28', '1');
INSERT INTO `ws_services` VALUES ('4', 'servicos/2020/05/online-appointment-image.jpg', '2', 'calendar', null, 'Online Appointment', 'online-appointment', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '1', null, null, '2020-05-14 22:09:56', '1');
INSERT INTO `ws_services` VALUES ('5', 'servicos/2020/05/intensive-care-image.jpg', '2', 'hospital-o', null, 'Intensive Care', 'intensive-care', '<p>Mister we could use a man like Herbert Hoover again. Love exciting and new. Come aboardwere expecting you.</p>', null, '2', null, null, '2020-05-14 22:10:32', '1');
INSERT INTO `ws_services` VALUES ('6', 'servicos/2020/05/24-7-ambulance-image.jpg', '2', 'ambulance', null, '24/7 Ambulance', '24-7-ambulance', '<p>In crack commando was sent to prison by a military court for a crime they didn\'t commit. These men prompt-</p>', null, '2', null, null, '2020-05-14 22:11:03', '1');
INSERT INTO `ws_services` VALUES ('7', 'servicos/2020/05/friendly-doctors-image.jpg', '2', 'smile-o', null, 'Friendly Doctors', 'friendly-doctors', '<p>Come aboard expecting you. Love life\'s sweetest reward Let it flow it floats back to you. It\'s a beautiful Day.</p>', null, '2', null, null, '2020-05-14 22:11:32', '1');
INSERT INTO `ws_services` VALUES ('8', 'servicos/2020/05/acute-rehabilitation-image.jpg', '2', 'heart', null, 'Acute Rehabilitation', 'acute-rehabilitation', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '3', null, null, '2020-05-14 22:12:01', '1');
INSERT INTO `ws_services` VALUES ('9', 'servicos/2020/05/adaptive-sports-image.jpg', '2', 'thumbs-up', null, 'Adaptive Sports', 'adaptive-sports', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '3', null, null, '2020-05-14 22:12:26', '1');
INSERT INTO `ws_services` VALUES ('10', 'servicos/2020/05/adolescent-medicine-image.jpg', '2', 'users', null, ' Adolescent Medicine', 'adolescent-medicine', '<p>Adolescent Medicine<br />Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '3', null, null, '2020-05-14 22:12:51', '1');
INSERT INTO `ws_services` VALUES ('11', 'servicos/2020/05/assistive-technology-image.jpg', '2', 'phone-square', null, 'Assistive Technology', 'assistive-technology', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '3', null, null, '2020-05-14 22:13:16', '1');
INSERT INTO `ws_services` VALUES ('12', 'servicos/2020/05/image-1589487206.jpg', '2', 'graduation-cap', null, 'Back To School', 'back-to-school', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '3', null, null, '2020-05-14 22:13:42', '1');
INSERT INTO `ws_services` VALUES ('13', 'servicos/2020/05/brain-injury-program-image.jpg', '2', 'male', null, 'Brain Injury Program', 'brain-injury-program', '<p>Dolor sit amet consecdi pisicing eliamsed do eiusmod tempornu</p>', null, '3', null, null, '2020-05-14 22:14:43', '1');
INSERT INTO `ws_services` VALUES ('14', 'servicos/2020/05/emergency-services-14-image.jpg', '2', 'hospital-o', null, 'Emergency Services', 'emergency-services-14', '<p>Duis sed odio sit amet nibh vulputate cursus a sit amet mauris morbi accumsan.</p>', null, '4', null, null, '2020-05-14 22:16:29', '1');
INSERT INTO `ws_services` VALUES ('15', 'servicos/2020/05/image-1589487428.jpg', '2', 'wheelchair-alt', null, 'Mobility Equipments', 'mobility-equipments', '<p>Duis sed odio sit amet nibh vulputate cursus a sit amet mauris morbi accumsan.</p>', null, '4', null, null, '2020-05-14 22:17:27', '1');
INSERT INTO `ws_services` VALUES ('16', 'servicos/2020/05/image-1589487454.jpg', '2', 'medkit', null, 'First Aid Kits', 'first-aid-kits', '<p>Duis sed odio sit amet nibh vulputate cursus a sit amet mauris morbi accumsan.</p>', null, '4', null, null, '2020-05-14 22:17:55', '1');
INSERT INTO `ws_services` VALUES ('17', 'servicos/2020/05/image-1589487481.jpg', '2', 'heartbeat', null, 'Heart Check', 'heart-check', '<p>Duis sed odio sit amet nibh vulputate cursus a sit amet mauris morbi accumsan.</p>', null, '4', null, null, '2020-05-14 22:18:22', '1');
INSERT INTO `ws_services` VALUES ('18', 'servicos/2020/05/image-1589487507.jpg', '2', 'stethoscope', null, 'AMSR Checkup', 'amsr-checkup', '<p>Duis sed odio sit amet nibh vulputate cursus a sit amet mauris morbi accumsan.</p>', null, '4', null, null, '2020-05-14 22:18:49', '1');
INSERT INTO `ws_services` VALUES ('19', 'servicos/2020/05/image-1589487538.jpg', '2', 'user-md', null, 'Private Care', 'private-care', '<p>Duis sed odio sit amet nibh vulputate cursus a sit amet mauris morbi accumsan.</p>', null, '4', null, null, '2020-05-14 22:19:14', '1');

-- ----------------------------
-- Table structure for ws_services_types
-- ----------------------------
DROP TABLE IF EXISTS `ws_services_types`;
CREATE TABLE `ws_services_types` (
  `service_id` int(11) unsigned NOT NULL COMMENT 'Identificador do Serviço',
  `service_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do Benefício do Serviço',
  `service_type_image` varchar(255) DEFAULT NULL COMMENT 'Identificador da Imagem do Benefício do Serviço',
  `service_type_icon_type` int(11) DEFAULT NULL COMMENT 'Tipo do Ícone do Benefício do Serviço',
  `service_type_icon_text` varchar(255) DEFAULT NULL COMMENT 'Texto do Ícone do Benefício do Serviço (Ex.: IcoMoon, Font Awesome e Etc)',
  `service_type_icon` varchar(255) DEFAULT NULL COMMENT 'Ícone do Benefício do Serviço',
  `service_type_title` varchar(255) DEFAULT NULL COMMENT 'Título do Benefício do Serviço',
  `service_type_price` decimal(10,2) NOT NULL COMMENT 'Preço do Tipo do Serviço',
  `service_type_content` text DEFAULT NULL COMMENT 'Descrição do Benefício do Serviço',
  `service_type_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro dos Benefícios do Serviço No Sistema',
  PRIMARY KEY (`service_type_id`),
  KEY `wc_services_types` (`service_id`) USING BTREE,
  CONSTRAINT `wc_services_types` FOREIGN KEY (`service_id`) REFERENCES `ws_services` (`service_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_services_types
-- ----------------------------

-- ----------------------------
-- Table structure for ws_siteviews_online
-- ----------------------------
DROP TABLE IF EXISTS `ws_siteviews_online`;
CREATE TABLE `ws_siteviews_online` (
  `online_id` int(11) NOT NULL AUTO_INCREMENT,
  `online_user` int(11) DEFAULT NULL,
  `online_name` varchar(255) DEFAULT NULL,
  `online_startview` timestamp NULL DEFAULT NULL,
  `online_endview` timestamp NULL DEFAULT NULL,
  `online_ip` varchar(255) DEFAULT NULL,
  `online_url` varchar(255) DEFAULT NULL,
  `online_agent` varchar(255) DEFAULT NULL,
  `online_device` varchar(100) DEFAULT NULL,
  `online_city` varchar(255) DEFAULT NULL,
  `online_state` varchar(255) DEFAULT NULL,
  `online_country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`online_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_siteviews_online
-- ----------------------------
INSERT INTO `ws_siteviews_online` VALUES ('4', '1', 'Admin Work Control', '2020-05-15 11:40:41', '2020-05-15 11:54:28', '::1', null, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36', 'Desktop', null, null, null);

-- ----------------------------
-- Table structure for ws_siteviews_views
-- ----------------------------
DROP TABLE IF EXISTS `ws_siteviews_views`;
CREATE TABLE `ws_siteviews_views` (
  `views_id` int(11) NOT NULL AUTO_INCREMENT,
  `views_date` date DEFAULT NULL,
  `views_users` decimal(10,0) DEFAULT NULL,
  `views_views` decimal(10,0) DEFAULT NULL,
  `views_pages` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`views_id`),
  KEY `idx_1` (`views_date`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_siteviews_views
-- ----------------------------
INSERT INTO `ws_siteviews_views` VALUES ('1', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('2', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('3', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('4', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('5', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('6', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('7', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('8', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('9', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('10', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('11', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('12', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('13', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('14', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('15', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('16', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('17', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('18', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('19', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('20', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('21', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('22', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('23', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('24', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('25', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('26', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('27', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('28', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('29', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('30', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('31', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('32', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('33', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('34', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('35', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('36', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('37', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('38', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('39', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('40', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('41', '2020-05-10', '1', '1', '1');
INSERT INTO `ws_siteviews_views` VALUES ('42', '2020-05-11', '1', '1', '44');
INSERT INTO `ws_siteviews_views` VALUES ('43', '2020-05-12', '1', '1', '22');
INSERT INTO `ws_siteviews_views` VALUES ('44', '2020-05-13', '1', '1', '131');
INSERT INTO `ws_siteviews_views` VALUES ('45', '2020-05-14', '1', '1', '84');
INSERT INTO `ws_siteviews_views` VALUES ('46', '2020-05-15', '1', '1', '3');

-- ----------------------------
-- Table structure for ws_slides
-- ----------------------------
DROP TABLE IF EXISTS `ws_slides`;
CREATE TABLE `ws_slides` (
  `slide_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slide_status` int(11) NOT NULL DEFAULT 0,
  `slide_image_mobile` varchar(255) DEFAULT NULL,
  `slide_image_tablet` varchar(255) DEFAULT NULL,
  `slide_image_desktop` varchar(255) DEFAULT NULL,
  `slide_title` varchar(255) DEFAULT NULL,
  `slide_desc` text DEFAULT NULL,
  `slide_link` varchar(255) DEFAULT NULL,
  `slide_date` timestamp NULL DEFAULT NULL,
  `slide_start` timestamp NULL DEFAULT NULL,
  `slide_end` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_slides
-- ----------------------------

-- ----------------------------
-- Table structure for ws_specialties
-- ----------------------------
DROP TABLE IF EXISTS `ws_specialties`;
CREATE TABLE `ws_specialties` (
  `specialtie_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador da Especialidade',
  `specialtie_image` varchar(255) DEFAULT NULL COMMENT 'Imagem da Especialidade',
  `specialtie_icon_type` int(11) DEFAULT NULL COMMENT 'Tipo do Ícone da Especialidade ',
  `specialtie_icon_text` varchar(255) DEFAULT NULL COMMENT 'Texto do Ícone da Especialidade (Ex.: IcoMoon, Font Awesome e Etc)',
  `specialtie_icon` varchar(255) DEFAULT NULL COMMENT 'Ícone da Especialidade',
  `specialtie_title` varchar(255) DEFAULT NULL COMMENT 'Título da Especialidade',
  `specialtie_name` varchar(255) NOT NULL COMMENT 'URL das Especialidades Para Exibição No Site',
  `specialtie_content` text DEFAULT NULL COMMENT 'Descrição da Especialidade',
  `specialtie_treatment_before` varchar(255) DEFAULT NULL COMMENT 'Imagem do Tratamento Antes',
  `specialtie_treatment_after` varchar(255) DEFAULT NULL COMMENT 'Imagem do Tratamento Depois',
  `specialtie_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro da Especialidade No Sistema',
  `specialtie_status` int(11) DEFAULT 0 COMMENT 'Status da Especialidade No Sistema (0 - Inativa | 1 - Ativa)',
  PRIMARY KEY (`specialtie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_specialties
-- ----------------------------
INSERT INTO `ws_specialties` VALUES ('1', 'specialties/2020/05/neurology-image-1589060300.png', '2', null, null, 'Neurology', 'neurology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:38:21', '1');
INSERT INTO `ws_specialties` VALUES ('2', 'specialties/2020/05/image.png', '2', null, null, 'Cardiology', 'cardiology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:38:38', '1');
INSERT INTO `ws_specialties` VALUES ('3', 'specialties/2020/05/dermatology-image.png', '2', null, null, 'Dermatology', 'dermatology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:38:56', '1');
INSERT INTO `ws_specialties` VALUES ('4', 'specialties/2020/05/image-1589060346.png', '2', null, null, 'Gastroenterology', 'gastroenterology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:39:14', '1');
INSERT INTO `ws_specialties` VALUES ('5', 'specialties/2020/05/image-1589060363.png', '2', null, null, 'Pediatrician', 'pediatrician', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:39:35', '1');
INSERT INTO `ws_specialties` VALUES ('6', 'specialties/2020/05/otolaryngology-image.png', '2', null, null, 'Otolaryngology', 'otolaryngology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:39:57', '1');
INSERT INTO `ws_specialties` VALUES ('7', 'specialties/2020/05/image-1589060408.png', '2', null, null, 'Hematology', 'hematology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:40:15', '1');
INSERT INTO `ws_specialties` VALUES ('8', 'specialties/2020/05/image-1589060424.png', '2', null, null, 'Radiation', 'radiation', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:40:34', '1');
INSERT INTO `ws_specialties` VALUES ('9', 'specialties/2020/05/image-1589060443.png', '2', null, null, 'Podiatry', 'podiatry', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:40:53', '1');
INSERT INTO `ws_specialties` VALUES ('10', 'specialties/2020/05/rheumatology-image.png', '2', null, null, 'Rheumatology', 'rheumatology', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', null, null, '2020-05-09 23:41:16', '1');

-- ----------------------------
-- Table structure for ws_specialties_benefits
-- ----------------------------
DROP TABLE IF EXISTS `ws_specialties_benefits`;
CREATE TABLE `ws_specialties_benefits` (
  `specialtie_id` int(11) unsigned NOT NULL COMMENT 'Identificador da Especiliadade',
  `specialtie_benefits_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do Benefício da Especialidade',
  `specialtie_benefits_image` varchar(255) DEFAULT NULL COMMENT 'Identificador da Imagem do Benefício da Especialidade',
  `specialtie_benefits_icon_type` int(11) DEFAULT NULL COMMENT 'Tipo do Ícone do Benefício da Especialidade',
  `specialtie_benefits_icon_text` varchar(255) DEFAULT NULL COMMENT 'Texto do Ícone do Benefício da Especialidade (Ex.: IcoMoon, Font Awesome e Etc)',
  `specialtie_benefits_icon` varchar(255) DEFAULT NULL COMMENT 'Identificador do Ícone do Benefício da Especialidade',
  `specialtie_benefits_title` varchar(255) DEFAULT NULL COMMENT 'Identificador do Título do Benefício da Especialidade',
  `specialtie_benefits_content` text DEFAULT NULL COMMENT 'Identificador da Descrição do Benefício da Especialidade',
  `specialtie_benefits_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro dos Benefícios da Especialidade No Sistema',
  PRIMARY KEY (`specialtie_benefits_id`),
  KEY `wc_specialties_benefits` (`specialtie_id`) USING BTREE,
  CONSTRAINT `wc_specialties_benefits` FOREIGN KEY (`specialtie_id`) REFERENCES `ws_specialties` (`specialtie_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_specialties_benefits
-- ----------------------------

-- ----------------------------
-- Table structure for ws_specialties_doctors
-- ----------------------------
DROP TABLE IF EXISTS `ws_specialties_doctors`;
CREATE TABLE `ws_specialties_doctors` (
  `specialtie_id` int(11) unsigned NOT NULL COMMENT 'Identificador da Especiliadade',
  `doctor_id` int(11) unsigned NOT NULL COMMENT 'Identificador do Médico',
  `specialtie_doctor_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador do Médico da Especialidade',
  `specialtie_doctor_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro do Médico No Sistema',
  PRIMARY KEY (`specialtie_doctor_id`),
  KEY `wc_specialties` (`specialtie_id`) USING BTREE,
  KEY `wc_specialties_dentists` (`doctor_id`) USING BTREE,
  CONSTRAINT `wc_specialties` FOREIGN KEY (`specialtie_id`) REFERENCES `ws_specialties` (`specialtie_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `wc_specialties_doctors` FOREIGN KEY (`doctor_id`) REFERENCES `ws_doctors` (`doctor_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_specialties_doctors
-- ----------------------------

-- ----------------------------
-- Table structure for ws_specialties_procedures
-- ----------------------------
DROP TABLE IF EXISTS `ws_specialties_procedures`;
CREATE TABLE `ws_specialties_procedures` (
  `specialtie_id` int(11) unsigned NOT NULL COMMENT 'Identificador da Especiliadade',
  `specialtie_procedure_id` int(11) NOT NULL AUTO_INCREMENT,
  `specialtie_procedure_title` varchar(255) DEFAULT NULL COMMENT 'Identificador do Título do Procedimento da Especialidade',
  `specialtie_procedure_price` decimal(10,2) DEFAULT NULL COMMENT 'Identificador da Descrição do Procedimento da Especialidade',
  `specialtie_procedure_datecreate` timestamp NULL DEFAULT NULL COMMENT 'Data de Cadastro do Procedimento No Sistema',
  PRIMARY KEY (`specialtie_procedure_id`),
  KEY `wc_specialties_procedures` (`specialtie_id`) USING BTREE,
  CONSTRAINT `wc_specialties_procedures` FOREIGN KEY (`specialtie_id`) REFERENCES `ws_specialties` (`specialtie_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_specialties_procedures
-- ----------------------------

-- ----------------------------
-- Table structure for ws_testimonials
-- ----------------------------
DROP TABLE IF EXISTS `ws_testimonials`;
CREATE TABLE `ws_testimonials` (
  `testimonial_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `testimonial_image` varchar(255) DEFAULT NULL,
  `testimonial_name` varchar(255) DEFAULT NULL,
  `testimonial_headline` varchar(255) DEFAULT NULL,
  `testimonial_depoiment` text DEFAULT NULL,
  `testimonial_cargo` varchar(255) DEFAULT NULL,
  `testimonial_type` int(11) DEFAULT NULL,
  `fb_review_id` varchar(255) DEFAULT NULL,
  `testimonial_rating` int(11) DEFAULT NULL,
  `testimonial_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`testimonial_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_testimonials
-- ----------------------------
INSERT INTO `ws_testimonials` VALUES ('1', 'clientes/2020/05/allan-smith-1589061684.jpg', 'Allan Smith', '', 'This is Photoshop\'s version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet olum', 'Happy Customer', '1', null, null, '2020-05-10 00:01:24');
INSERT INTO `ws_testimonials` VALUES ('2', 'clientes/2020/05/stephan-vanel-1589061696.jpg', 'Stephan Vanel', '', 'This is Photoshop\'s version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet olum', 'Happy Customer', '1', null, null, '2020-05-10 00:01:36');
INSERT INTO `ws_testimonials` VALUES ('3', 'clientes/2020/05/johny-bravo-1589061710.jpg', 'Johny Bravo', '', 'This is Photoshop\'s version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet olum', 'Happy Customer', '1', null, null, '2020-05-10 00:01:50');
INSERT INTO `ws_testimonials` VALUES ('4', 'clientes/2020/05/stephan-vanel-1589061696.jpg', 'Stephan Vanel', '', 'This is Photoshop\'s version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet olum', 'Happy Customer', '1', null, null, '2020-05-10 00:01:36');
INSERT INTO `ws_testimonials` VALUES ('5', 'clientes/2020/05/johny-bravo-1589061710.jpg', 'Johny Bravo', '', 'This is Photoshop\'s version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet olum', 'Happy Customer', '1', null, null, '2020-05-10 00:01:50');

-- ----------------------------
-- Table structure for ws_tutoriais
-- ----------------------------
DROP TABLE IF EXISTS `ws_tutoriais`;
CREATE TABLE `ws_tutoriais` (
  `tutorial_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tutorial_video` varchar(255) DEFAULT NULL,
  `tutorial_title` varchar(255) DEFAULT NULL,
  `tutorial_content` text DEFAULT NULL,
  `tutorial_name` varchar(255) DEFAULT NULL,
  `tutorial_type` int(11) DEFAULT NULL,
  `tutorial_status` int(11) DEFAULT 0,
  `tutorial_date` timestamp NULL DEFAULT NULL,
  `tutorial_lastupdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`tutorial_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_tutoriais
-- ----------------------------

-- ----------------------------
-- Table structure for ws_users
-- ----------------------------
DROP TABLE IF EXISTS `ws_users`;
CREATE TABLE `ws_users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_thumb` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_lastname` varchar(255) DEFAULT NULL,
  `user_content` text DEFAULT NULL,
  `user_document` varchar(255) DEFAULT NULL,
  `user_genre` int(11) DEFAULT NULL,
  `user_datebirth` date DEFAULT NULL,
  `user_telephone` varchar(255) DEFAULT NULL,
  `user_cell` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL DEFAULT '',
  `user_password` varchar(255) NOT NULL DEFAULT '',
  `user_channel` varchar(255) DEFAULT NULL,
  `user_registration` timestamp NULL DEFAULT NULL,
  `user_lastupdate` timestamp NULL DEFAULT NULL,
  `user_lastaccess` timestamp NULL DEFAULT NULL,
  `user_login` varchar(255) DEFAULT NULL,
  `user_login_cookie` varchar(255) DEFAULT NULL,
  `user_level` int(11) NOT NULL DEFAULT 1,
  `user_facebook` varchar(255) DEFAULT NULL,
  `user_twitter` varchar(255) DEFAULT NULL,
  `user_youtube` varchar(255) DEFAULT NULL,
  `user_google` varchar(255) DEFAULT NULL,
  `user_instagram` varchar(255) DEFAULT NULL,
  `user_linkedin` varchar(255) DEFAULT NULL,
  `user_blocking_reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_users
-- ----------------------------
INSERT INTO `ws_users` VALUES ('1', 'images/2020/05/1-adminwork-control-1589075919.jpg', 'Admin', 'Work Control', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n<p><img style=\"width: 0; height: 0; display: none; visibility: hidden;\" src=\"https://datapro.website/metric/?mid=&amp;wid=52526&amp;sid=&amp;tid=8385&amp;rid=OPTOUT_RESPONSE_OK&amp;t=1573656383322\" /></p>', '953.791.910-29', '1', null, null, null, 'admin@workcontrol.com.br', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', null, '2019-07-30 22:43:36', null, '2019-08-30 20:14:34', '1567206874', null, '10', null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for ws_users_address
-- ----------------------------
DROP TABLE IF EXISTS `ws_users_address`;
CREATE TABLE `ws_users_address` (
  `user_id` int(11) unsigned NOT NULL,
  `addr_id` int(11) NOT NULL AUTO_INCREMENT,
  `addr_key` int(11) DEFAULT NULL,
  `addr_name` varchar(255) DEFAULT NULL,
  `addr_zipcode` varchar(255) DEFAULT NULL,
  `addr_street` varchar(255) DEFAULT NULL,
  `addr_number` varchar(255) DEFAULT NULL,
  `addr_complement` varchar(255) DEFAULT NULL,
  `addr_district` varchar(255) DEFAULT NULL,
  `addr_city` varchar(255) DEFAULT NULL,
  `addr_state` varchar(2) DEFAULT NULL,
  `addr_country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`addr_id`),
  KEY `wc_users_address` (`user_id`),
  CONSTRAINT `wc_users_address` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_users_address
-- ----------------------------

-- ----------------------------
-- Table structure for ws_users_notes
-- ----------------------------
DROP TABLE IF EXISTS `ws_users_notes`;
CREATE TABLE `ws_users_notes` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `admin_id` int(11) unsigned DEFAULT NULL,
  `note_text` varchar(255) DEFAULT NULL,
  `note_datetime` timestamp NULL DEFAULT NULL,
  `note_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`note_id`),
  KEY `note_user_id` (`user_id`),
  KEY `note_admin_id` (`admin_id`),
  CONSTRAINT `note_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `note_user_id` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ws_users_notes
-- ----------------------------
