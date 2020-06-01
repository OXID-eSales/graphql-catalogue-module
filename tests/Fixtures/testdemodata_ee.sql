SET @@session.sql_mode = '';

# Product with inactive bundle
UPDATE `oxarticles` SET `OXMAPID` = 1118, `OXVPE` = 1 WHERE OXID='_test_active_main_bundle';
UPDATE `oxarticles` SET `OXMAPID` = 1119, `OXVPE` = 1 WHERE OXID='_test_inactive_bundle';
INSERT INTO `oxarticles2shop` (`OXSHOPID`, `OXMAPOBJECTID`, `OXTIMESTAMP`) VALUES
(1, 1118, '2020-01-01 00:00:00'),
(1, 1119, '2020-01-01 00:00:00');

REPLACE INTO oxvendor2shop (OXSHOPID, OXMAPOBJECTID) VALUES
(1, 902), (1, 903), (1, 904);

INSERT INTO oxconfig (OXID, OXSHOPID, OXVARNAME, OXVARTYPE, OXVARVALUE) SELECT
MD5(RAND()), 2, OXVARNAME, OXVARTYPE, OXVARVALUE from oxconfig;

REPLACE INTO oxselectlist2shop (OXSHOPID, OXMAPOBJECTID) VALUES
(1, 1);

INSERT INTO `oxlinks2shop` (`OXSHOPID`, `OXMAPOBJECTID`, `OXTIMESTAMP`) VALUES
(1,	902, '2020-04-16 16:41:05'),
(1,	903, '2020-04-16 16:41:05'),
(1,	904, '2020-04-16 16:41:05'),
(1,	905, '2020-04-16 16:41:05');

# Banner for second shop
INSERT INTO `oxactions` (`OXID`, `OXSHOPID`, `OXTYPE`, `OXSORT`, `OXACTIVE`, `OXTITLE`) VALUES
('_test_second_shop_banner_1', 2, 3, 2, 1, 'subshop banner 1'),
('_test_second_shop_banner_2', 2, 3, 1, 1, 'subshop banner 2');

#promotions
INSERT INTO `oxactions` (`OXID`, `OXSHOPID`, `OXTYPE`, `OXTITLE`, `OXTITLE_1`, `OXTITLE_2`, `OXTITLE_3`, `OXLONGDESC`, `OXLONGDESC_1`, `OXLONGDESC_2`, `OXLONGDESC_3`, `OXACTIVE`, `OXACTIVEFROM`, `OXACTIVETO`, `OXPIC`, `OXPIC_1`, `OXPIC_2`, `OXPIC_3`, `OXLINK`, `OXLINK_1`, `OXLINK_2`, `OXLINK_3`, `OXSORT`, `OXTIMESTAMP`) VALUES
('test_active_sub_shop_promotion_1',        2,	2,	'Current sub shop Promotion 1 DE',	'Current sub shop Promotion 1 EN',	'',	'',	'Long description 1 DE',	'Long description 1 EN',	'',	'',	1,	'2010-10-10 00:00:00',	'2111-10-10 00:00:00',	'',	'',	'',	'',	'',	'',	'',	'',	3,	'2020-04-23 12:07:10'),
('test_active_sub_shop_promotion_2',	    2,	2,	'Current sub shop Promotion 2 DE',	'Current sub shop Promotion 2 EN',	'',	'',	'Long description 2 DE',	'Long description 2 EN',	'',	'',	1,	'2010-01-01 00:00:00',	'2111-10-10 00:00:00',	'',	'',	'',	'',	'',	'',	'',	'',	2,	'2020-04-23 12:07:10'),
('test_inactive_sub_shop_promotion_1',	    2,	2,	'Upcoming sub shop promotion DE',	'Upcoming sub shop promotion EN',	'',	'',	'Long description 3 DE',	'Long description 3 EN',	'',	'',	0,	'2010-01-01 00:00:00',	'2010-02-01 00:00:00',	'',	'',	'',	'',	'',	'',	'',	'',	1,	'2020-04-23 12:07:10');

INSERT INTO `oxarticles2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
(2, 933);
