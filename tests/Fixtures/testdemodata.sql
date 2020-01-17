SET @@session.sql_mode = '';

INSERT INTO `oxvendor` (`OXID`, `OXSHOPID`, `OXACTIVE`, `OXICON`, `OXTITLE`, `OXSHORTDESC`, `OXTITLE_1`, `OXSHORTDESC_1`, `OXTITLE_2`, `OXSHORTDESC_2`, `OXTITLE_3`, `OXSHORTDESC_3`, `OXSHOWSUFFIX`, `OXTIMESTAMP`) VALUES
('fe07958b49de225bd1dbc7594fb9a6b0', 1, 1, '', 'https://fashioncity.com', 'Fashion city', 'https://fashioncity.com', 'Fashion city', '', '', '', '', 1, '2020-01-10 15:00:00'),
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
('a57c56e3ba710eafb2225e98f058d989','8cddec2c98b7186e94fea7e0dbfc66ed',1,1,'index.php?cl=vendorlist&amp;cnid=v_a57c56e3ba710eafb2225e98f058d989','Nach-Lieferant/www-true-fashion-com/','oxvendor',0,0,'','2020-01-10 15:00:00'),
('fe07958b49de225bd1dbc7594fb9a6b0','356ea1ced11b2b0d30a0935fa207780f',1,1,'index.php?cl=vendorlist&amp;cnid=v_fe07958b49de225bd1dbc7594fb9a6b0','Nach-Lieferant/https-fashioncity-com/','oxvendor',0,0,'','2020-01-10 15:00:00'),
('05833e961f65616e55a2208c2ed7c6b8','1d282c96e6923a547f56942d6cd020d9',1,1,'index.php?cl=vendorlist&amp;cnid=v_05833e961f65616e55a2208c2ed7c6b8','Nach-Lieferant/https-demo-com/','oxvendor',0,0,'','2020-01-10 15:00:00');
