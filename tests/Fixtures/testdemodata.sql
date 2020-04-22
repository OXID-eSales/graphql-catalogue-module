SET @@session.sql_mode = '';

INSERT INTO `oxvendor` (`OXID`, `OXSHOPID`, `OXACTIVE`, `OXICON`, `OXTITLE`, `OXSHORTDESC`, `OXTITLE_1`, `OXSHORTDESC_1`, `OXTITLE_2`, `OXSHORTDESC_2`, `OXTITLE_3`, `OXSHORTDESC_3`, `OXSHOWSUFFIX`, `OXTIMESTAMP`) VALUES
('fe07958b49de225bd1dbc7594fb9a6b0', 1, 1, '', 'https://fashioncity.com/de', 'Fashion city', 'https://fashioncity.com/en', 'Fashion city', '', '', '', '', 1, '2020-01-10 15:00:00'),
('05833e961f65616e55a2208c2ed7c6b8', 1, 0, '', 'https://demo.com', 'Demo vendor', 'https://demo.com', 'Demo vendor', '', '', '', '', 1, '2020-01-10 15:00:00');

INSERT INTO oxseo (OXOBJECTID,OXIDENT,OXSHOPID,OXLANG,OXSTDURL,OXSEOURL,OXTYPE,OXFIXED,OXEXPIRED,OXPARAMS,OXTIMESTAMP) VALUES
('3a909e7c886063857e86982c7a3c5b84','c11c29d926de486b5ce80520da25e47b',1,0,'index.php?cl=manufacturerlist&amp;mnid=3a909e7c886063857e86982c7a3c5b84','Nach-Hersteller/Mauirippers/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('3a97c94553428daed76ba83e54d3876f','72dce378114a143e848aef67d0ae28d7',1,0,'index.php?cl=manufacturerlist&amp;mnid=3a97c94553428daed76ba83e54d3876f','Nach-Hersteller/Big-Matsol/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('3a9fd0ec4b41d001e770b1d2d7af3e73','0e3d2fdcfe72c8cdd5670b6b2497cf51',1,0,'index.php?cl=manufacturerlist&amp;mnid=3a9fd0ec4b41d001e770b1d2d7af3e73','Nach-Hersteller/Jucker-Hawaii/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('90a0b84564cde2394491df1c673b6aa0','a080a0622ad64fe032c7c2dde4282e41',1,0,'index.php?cl=manufacturerlist&amp;mnid=90a0b84564cde2394491df1c673b6aa0','Nach-Hersteller/ION/','oxmanufacturer',0,0,'','2020-01-09 15:37:39'),
('90a3eccf9d7121a9ca7d659f29021b7a','44d07810b897a415dee6584e57bda35d',1,0,'index.php?cl=manufacturerlist&amp;mnid=90a3eccf9d7121a9ca7d659f29021b7a','Nach-Hersteller/Cabrinha/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('90a8a18dd0cf0e7aec5238f30e1c6106','f43a56850960a9b53ab1cbccbf56602a',1,0,'index.php?cl=manufacturerlist&amp;mnid=90a8a18dd0cf0e7aec5238f30e1c6106','Nach-Hersteller/Naish/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('9434afb379a46d6c141de9c9e5b94fcf','08b373dc43691a65bcf12184b719ef11',1,0,'index.php?cl=manufacturerlist&amp;mnid=9434afb379a46d6c141de9c9e5b94fcf','Nach-Hersteller/Kuyichi/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('adc566c366db8eaf30c6c124a09e82b3','8e798a64d958dfa059c39093a5e43cda',1,0,'index.php?cl=manufacturerlist&amp;mnid=adc566c366db8eaf30c6c124a09e82b3','Nach-Hersteller/Core-Kiteboarding/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('adc6df0977329923a6330cc8f3c0a906','74ac133a0e9403952de061c9fd735449',1,0,'index.php?cl=manufacturerlist&amp;mnid=adc6df0977329923a6330cc8f3c0a906','Nach-Hersteller/Liquid-Force/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('dc5ec524a9aa6175cf7a498d70ce510a','83c2a9997f022c249da68174f2cc5746',1,0,'index.php?cl=manufacturerlist&amp;mnid=dc5ec524a9aa6175cf7a498d70ce510a','Nach-Hersteller/NPX/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('oiaf6ab7e12e86291e86dd3ff891fe40','dc84430f5673d3c1a560d19fffc3b1fc',1,0,'index.php?cl=manufacturerlist&amp;mnid=oiaf6ab7e12e86291e86dd3ff891fe40','Nach-Hersteller/O-Reilly/','oxmanufacturer',0,0,'','2020-01-09 15:37:39'),
('root','9d52dd3016f5f797bb7f86be69ed06eb',1,0,'index.php?cl=manufacturerlist&amp;mnid=root','Nach-Hersteller/','oxmanufacturer',0,0,'','2020-01-09 15:54:06'),
('root','9411d92bed92a131712b1f0f03d9fb42',1,1,'index.php?cl=manufacturerlist&amp;mnid=root','en/By-manufacturer/','oxmanufacturer',0,0,'','2020-01-09 15:54:14'),
('05833e961f65616e55a2208c2ed7c6b8',	'b5a8c2a04e56e4e824bd8a19c73a0441',	1,	0,	'index.php?cl=vendorlist&amp;cnid=v_05833e961f65616e55a2208c2ed7c6b8',	'Nach-Lieferant/https-demo-com/',	'oxvendor',	0,	0,	'',	'2020-01-21 13:04:37'),
('05833e961f65616e55a2208c2ed7c6b8',	'4418e67c61addcec06dc84366315fd1c',	1,	1,	'index.php?cl=vendorlist&amp;cnid=v_05833e961f65616e55a2208c2ed7c6b8',	'en/By-distributor/https-demo-com/',	'oxvendor',	0,	0,	'',	'2020-01-21 13:04:35'),
('a57c56e3ba710eafb2225e98f058d989',	'8cddec2c98b7186e94fea7e0dbfc66ed',	1,	0,	'index.php?cl=vendorlist&amp;cnid=v_a57c56e3ba710eafb2225e98f058d989',	'Nach-Lieferant/www-true-fashion-com/',	'oxvendor',	0,	0,	'',	'2020-01-21 13:04:45'),
('a57c56e3ba710eafb2225e98f058d989',	'9c4de227950cb0b7e15e03acc60c704a',	1,	1,	'index.php?cl=vendorlist&amp;cnid=v_a57c56e3ba710eafb2225e98f058d989',	'en/By-distributor/www-true-fashion-com/',	'oxvendor',	0,	0,	'',	'2020-01-21 13:04:43'),
('fe07958b49de225bd1dbc7594fb9a6b0',	'6a1bd3d7c1981181b02ef99f5b914cae',	1,	0,	'index.php?cl=vendorlist&amp;cnid=v_fe07958b49de225bd1dbc7594fb9a6b0',	'Nach-Lieferant/https-fashioncity-com-de/',	'oxvendor',	0,	0,	'',	'2020-01-21 13:04:39'),
('fe07958b49de225bd1dbc7594fb9a6b0',	'b3b9076081cefb087149f241f708e0ae',	1,	1,	'index.php?cl=vendorlist&amp;cnid=v_fe07958b49de225bd1dbc7594fb9a6b0',	'en/By-distributor/https-fashioncity-com-en/',	'oxvendor',	0,	0,	'',	'2020-01-21 13:04:42');

UPDATE `oxcategories` SET `OXACTIVE` = 0, `OXACTIVE_1` = 0, `OXACTIVE_2` = 0, `OXACTIVE_3` = 0 WHERE `OXID` = 'd8665fef35f4d528e92c3d664f4a00c0';

REPLACE INTO `oxobject2seodata` (`OXOBJECTID`, `OXSHOPID`, `OXLANG`, `OXKEYWORDS`, `OXDESCRIPTION`) VALUES
('058de8224773a1d5fd54d523f0c823e0', 1, 0, 'german seo keywords', 'german seo description'),
('058de8224773a1d5fd54d523f0c823e0', 1, 1, 'english seo keywords', 'english seo description'),
('943173edecf6d6870a0f357b8ac84d32', 1, 0, 'german cat seo keywords', 'german cat seo description'),
('943173edecf6d6870a0f357b8ac84d32', 1, 1, 'english cat seo keywords', 'english cat seo description'),
('fe07958b49de225bd1dbc7594fb9a6b0', 1, 0, 'german vendor seo keywords', 'german vendor seo description'),
('fe07958b49de225bd1dbc7594fb9a6b0', 1, 1, 'english vendor seo keywords', 'english vendor seo description'),
('oiaf6ab7e12e86291e86dd3ff891fe40', 1, 0, 'german manufacturer seo keywords', 'german manufacturer seo description'),
('oiaf6ab7e12e86291e86dd3ff891fe40', 1, 1, 'english manufacturer seo keywords', 'english manufacturer seo description'),
('058e613db53d782adfc9f2ccb43c45fe', 1, 0, 'german product seo keywords', 'german product seo description'),
('058e613db53d782adfc9f2ccb43c45fe', 1, 1, 'english product seo keywords', 'english product seo description');

REPLACE INTO `oxselectlist` (`OXID`, `OXSHOPID`, `OXTITLE`, `OXIDENT`, `OXVALDESC`, `OXTITLE_1`, `OXVALDESC_1`) VALUES
('testsellist', 1, 'test selection list [DE] šÄßüл', 'test sellist šÄßüл', 'selvar1 [DE]!P!1__@@selvar2 [DE]__@@selvar3 [DE]!P!-2__@@selvar4 [DE]!P!2%__@@', 'test selection list [EN] šÄßüл', 'selvar1 [EN] šÄßüл!P!1__@@selvar2 [EN] šÄßüл__@@selvar3 [EN] šÄßüл!P!-2__@@selvar4 [EN] šÄßüл!P!2%__@@');

REPLACE INTO `oxobject2selectlist` (`OXID`, `OXOBJECTID`, `OXSELNID`, `OXSORT`) VALUES
('article2testsellis', '058de8224773a1d5fd54d523f0c823e0', 'testsellist', 0);

INSERT INTO `oxratings` (`OXID`, `OXUSERID`, `OXTYPE`, `OXOBJECTID`, `OXRATING`) VALUES
('_test_wrong_user', 'wronguserid', 'oxarticle', 'b56597806428de2f58b1c6c7d3e0e093', 4),
('_test_wrong_product', 'e7af1c3b786fd02906ccd75698f4e6b9', 'oxarticle', 'wrongobjectid', 4),
('_test_wrong_object_type', 'e7af1c3b786fd02906ccd75698f4e6b9', 'oxrecommlist', 'b56597806428de2f58b1c6c7d3e0e093', 4),
('_test_more_ratings', 'e7af1c3b786fd02906ccd75698f4e6b9', 'oxarticle', '058e613db53d782adfc9f2ccb43c45fe', 4),
('_test_more_ratings_2', 'e7af1c3b786fd02906ccd75698f4e6b9', 'oxarticle', '058e613db53d782adfc9f2ccb43c45fe', 4),
('_test_more_ratings_3', 'e7af1c3b786fd02906ccd75698f4e6b9', 'oxarticle', '058e613db53d782adfc9f2ccb43c45fe', 4);

UPDATE `oxreviews` SET `OXACTIVE` = 1 WHERE `OXID` = '94415306f824dc1aa2fce0dc4f12783d';
INSERT INTO `oxreviews` (`OXID`, `OXACTIVE`, `OXOBJECTID`, `OXTYPE`, `OXTEXT`, `OXUSERID`, `OXRATING`) VALUES
('_test_wrong_user', 1, 'b56597806428de2f58b1c6c7d3e0e093', 'oxarticle', 'example wrong userid text', 'wronguserid', 4),
('_test_wrong_product', 1, 'wrongobjectid', 'oxarticle', 'example wrong userid text', 'e7af1c3b786fd02906ccd75698f4e6b9', 4),
('_test_wrong_object_type', 1, 'wrongobjectid', 'oxrecommlist', 'example wrong userid text', 'e7af1c3b786fd02906ccd75698f4e6b9', 4),
('_test_real_product_1', 1, '058e613db53d782adfc9f2ccb43c45fe', 'oxarticle', 'example review for product 1', 'e7af1c3b786fd02906ccd75698f4e6b9', 3),
('_test_real_product_2', 1, '058e613db53d782adfc9f2ccb43c45fe', 'oxarticle', 'example review for product 2', 'e7af1c3b786fd02906ccd75698f4e6b9', 4),
('_test_real_product_inactive', 0, '058e613db53d782adfc9f2ccb43c45fe', 'oxarticle', 'example review for product inactive', 'e7af1c3b786fd02906ccd75698f4e6b9', 5);

UPDATE `oxlinks` SET `OXACTIVE` = 0 WHERE `OXID` = 'ce342e8acb69f1748.25672556';
INSERT INTO `oxlinks` (`OXID`, `OXSHOPID`, `OXACTIVE`, `OXURL`, `OXURLDESC`, `OXURLDESC_1`, `OXURLDESC_2`, `OXURLDESC_3`, `OXINSERT`) VALUES
('test_active', 1, 1, 'http://www.oxid-esales.com', '<p>Deutsche Beschreibung aktiv</p>', '<p>English Description active</p>', '', '', '2012-06-04 07:04:54'),
('test_active_2', 1, 1, 'http://www.oxid-esales.com', '<p>Aktiv link</p>', '<p>Active link</p>', '', '', '2012-06-04 07:04:54'),
('test_inactive', 1, 0, 'http://www.oxid-esales.com', '<p>Deutsche Beschreibung inakitv</p>', '<p>English Description inactive</p>', '', '', '2012-06-04 07:04:54'),
('test_inactive_2', 1, 0, 'http://www.oxid-esales-inactive.com', '<p>Inaktiv link</p>', '<p>Incative link</p>', '', '', '2012-06-05 07:04:54');

UPDATE oxarticles SET oxdelivery = '2999-12-31' WHERE oxid = 'f4fe754e1692b9f79f2a7b1a01bb8dee';

#banners
UPDATE `oxactions` SET `OXTITLE_1` = 'Banner 1 en' WHERE `OXID` = 'b5639c6431b26687321f6ce654878fa5';
UPDATE `oxactions` SET `OXTITLE_1` = 'Banner 4 en' WHERE `OXID` = 'cb34f86f56162d0c95890b5985693710';
UPDATE `oxactions` SET `OXACTIVE` = 0 WHERE `OXID` = 'b56a097dedf5db44e20ed56ac6defaa8';
INSERT INTO `oxactions` (`OXID`, `OXSHOPID`, `OXTYPE`, `OXSORT`, `OXACTIVE`, `OXACTIVEFROM`, `OXACTIVETO`) VALUES
('_test_active_interval', 1, 3, 5, 0, '2020-01-01 00:00:00', '2035-01-01 00:00:00');
UPDATE `oxactions` SET `OXSORT` = 1 WHERE `OXID` = 'cb34f86f56162d0c95890b5985693710';
UPDATE `oxactions` SET `OXSORT` = 2 WHERE `OXID` = 'b56efaf6c93664b6dca5b1cee1f87057';
UPDATE `oxactions` SET `OXSORT` = 3 WHERE `OXID` = 'b56a097dedf5db44e20ed56ac6defaa8';
UPDATE `oxactions` SET `OXSORT` = 4 WHERE `OXID` = 'b5639c6431b26687321f6ce654878fa5';

UPDATE `oxarticles` SET `OXVENDORID` = 'fe07958b49de225bd1dbc7594fb9a6b0' where `OXID` = '10049f9322cf8852f8d567e9662cb12c';
UPDATE `oxarticles` SET `OXVENDORID` = 'fe07958b49de225bd1dbc7594fb9a6b0' where `OXID` = '10067ab25bf275b7e68bc0431b204d24';
UPDATE `oxarticles` SET `OXVENDORID` = 'fe07958b49de225bd1dbc7594fb9a6b0' where `OXID` = '1008b12cef0476f5e941da460ba621e6';

