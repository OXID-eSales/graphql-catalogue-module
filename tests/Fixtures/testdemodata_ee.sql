SET @@session.sql_mode = '';

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

# Banner is configured for group
INSERT INTO `oxactions` (`OXID`, `OXSHOPID`, `OXTYPE`, `OXSORT`, `OXACTIVE`) VALUES
('_test_group_banner', 1, 3, 6, 1);
INSERT INTO `oxobject2action` (`OXACTIONID`, `OXOBJECTID`, `OXCLASS`) VALUES
('_test_group_banner','oxidadmin', 'oxgroups');

# Banner for second shop
INSERT INTO `oxactions` (`OXID`, `OXSHOPID`, `OXTYPE`, `OXSORT`, `OXACTIVE`, `OXTITLE`) VALUES
('_test_second_shop_banner_1', 2, 3, 2, 1, 'subshop banner 1'),
('_test_second_shop_banner_2', 2, 3, 1, 1, 'subshop banner 2');
