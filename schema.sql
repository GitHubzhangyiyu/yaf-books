CREATE DATABASE IF NOT EXISTS `book` ;
USE `book`;
CREATE TABLE IF NOT EXISTS `book_admin`(
    `admin_id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(16) NOT NULL,
    `email` varchar(255) DEFAULT NULL,
    `password` varchar(40) NOT NULL,
    `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `is_del` bit(1) DEFAULT NULL,
    PRIMARY KEY (`admin_id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `book_category`(
    `category_id` int(11) NOT NULL AUTO_INCREMENT,
    `cate_name` varchar(45) NOT NULL,
    `parent_id` varchar(45) NOT NULL,
    `category_layer` int(6) unsigned zerofill DEFAULT NULL,
    PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `book_category` (`category_id`, `cate_name`, `parent_id`, `category_layer`) VALUES
	(1, '计算机系列', '0', 000100),
	(2, '编程', '1', 000101),
	(3, '算法', '1', 000102),
	(4, '文学小说', '0', 000200),
	(5, '中国文学', '4', 000201),
	(6, '外国文学', '4', 000202);

CREATE TABLE IF NOT EXISTS `book_fav` (
    `fav_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_uuid` varchar(36) NOT NULL,
    `product_uuid` varchar(36) NOT NULL,
    `fav_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `comment` varchar(45) DEFAULT NULL,
    PRIMARY KEY (`fav_id`),
    KEY `user_uuid_idx` (`user_uuid`),
    CONSTRAINT `fk_fav_user_uuid` FOREIGN KEY (`user_uuid`) REFERENCES `book_user` (`user_uuid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `book_product` (
    `product_id` int(11) NOT NULL AUTO_INCREMENT,
    `product_uuid` varchar(36) NOT NULL,
    `product_name` varchar(45) NOT NULL,
    `reg_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `score` int(11),
    `category_id` int(11) NOT NULL,
    `writer` varchar(45) NOT NULL,
    `detailed_introduction` TEXT NOT NULL,
    `is_del` bit(1) NOT NULL,
    PRIMARY KEY (`product_id`),
    KEY `product_uuid_index` (`product_uuid`),
    KEY `pk_category_id` (`category_id`),
    CONSTRAINT `fk_category_id` FOREIGN KEY (`category_id`) REFERENCES `book_category` (`category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `book_product` (`product_id`, `product_uuid`, `product_name`, `reg_time`, `score`, `category_id`, `writer`, `detailed_introduction`, `is_del`) VALUES
    (1, '12163A7E-0C61-6E11-567B-18971683E15C', 'C程序设计语言', '2018-09-28 10:55:01', 94, 2, '(美)Brian W. Kernighan', '在计算机发展的历史上，没有哪一种程序设计语言像C语言这样应用广泛。本书原著即为C语言的设计者之一Dennis M.Ritchie和著名计算机科学家Brian W.Kernighan合著的一本介绍C语言的权威经典著作。我们现在见到的大量论述C语言程序设计的教材和专著均以此书为蓝本。原著第1版中介绍的C语言成为后来广泛使用的C语言版本——标准C的基础。人们熟知的“hello,World"程序就是由本书首次引入的，现在，这一程序已经成为众多程序设计语言入门的第一课。原著第2版根据1987年制定的ANSIC标准做了适当的修订．引入了最新的语言形式，并增加了新的示例，通过简洁的描述、典型的示例，作者全面、系统、准确地讲述了C语言的各个特性以及程序设计的基本方法。对于计算机从业人员来说，《C程序设计语言》是一本必读的程序设计语 言方面的参考书。', b'0'),
    (2, '017D1BF7-0AD9-6850-C01F-B095E96A5E00', 'JavaScript高级程序设计（第3版)', '2018-09-28 10:55:01', 93, 2, '(美)尼古拉斯·泽卡斯', '本书是JavaScript 超级畅销书的最新版。ECMAScript 5 和HTML5 在标准之争中双双胜出，使大量专有实现和客户端扩展正式进入规范，同时也为JavaScript 增添了很多适应未来发展的新特性。本书这一版除增加5 章全新内容外，其他章节也有较大幅度的增补和修订，新内容篇幅约占三分之一。全书从JavaScript 语言实现的各个组成部分——语言核心、DOM、BOM、事件模型讲起，深入浅出地探讨了面向对象编程、Ajax 与Comet 服务器端通信，HTML5 表单、媒体、Canvas（包括WebGL）及Web Workers、地理定位、跨文档传递消息、客户端存储（包括IndexedDB）等新API，还介绍了离线应用和与维护、性能、部署相关的最佳开发实践。本书附录展望了未来的API 和ECMAScript Harmony 规范。本书适合有一定编程经验的Web 应用开发人员阅读，也可作为高校及社会实用技术培训相关专业课程的教材。', b'0'),
    (3, '77989D34-084E-F85C-06B1-58BE5B043372', 'Python编程：从入门到实践', '2018-09-28 10:55:01', 91, 2, '(美)埃里克·马瑟斯', '本书是一本针对所有层次的Python 读者而作的Python 入门书。全书分两部分：第一部分介绍用Python 编程所必须了解的基本概念，包括matplotlib、NumPy 和Pygal 等强大的Python 库和工具介绍，以及列表、字典、if 语句、类、文件与异常、代码测试等内容；第二部分将理论付诸实践，讲解如何开发三个项目，包括简单的Python 2D 游戏开发如何利用数据生成交互式的信息图，以及创建和定制简单的Web 应用，并帮读者解决常见编程问题和困惑。', b'0'),
    (4, '3DB4D533-DB32-2D26-858A-7D0B3184ED34', 'JavaScript语言精粹', '2018-09-28 10:55:01', 91, 2, '(美)Douglas Crockford', '本书通过对JavaScript语言的分析，甄别出好的和坏的特性，从而提取出相对这门语言的整体而言具有更好的可靠性、可读性和可维护性的JavaScript的子集，以便你能用它创建真正可扩展的和高效的代码。雅虎资深JavaScript架构师Douglas Crockford倾力之作。向读者介绍如何运用JavaScript创建真正可扩展的和高效的代码。', b'0'),
    (5, '8B1B27FA-B232-9705-2DAA-B8C82A7E0945', 'C和指针', '2018-09-28 10:55:01', 90, 2, '(美)Kenneth A·Reek', '本书提供与C语言编程相关的全面资源和深入讨论。本书通过对指针的基础知识和高级特性的探讨，帮助程序员把指针的强大功能融入到自己的程序中去。全书共18章，覆盖了数据、语句、操作符和表达式、指针、函数、数组、字符串、结构和联合等几乎所有重要的C编程话题。书中给出了很多编程技巧和提示，每章后面有针对性很强的练习，附录部分则给出了部分练习的解答。本书适合C语言初学者和初级C程序员阅读，也可作为计算机专业学生学习C语言的参考。', b'0'),
    (6, 'FC44E720-4A4F-5425-F059-3859924D2A52', 'Modern PHP（中文版）', '2018-09-28 10:55:01', 88, 2, '(美)Josh Lockhart', 'PHP正在重生，不过所有PHP在线教程都过时了，很难体现这一点。通过这本实用的指南，你会发现，借助面向对象、命名空间和不断增多的可重用的组件库，PHP已经成为一门功能完善的成熟语言。本书作者Josh Lockhart是“PHP之道”的发起人，这是个受欢迎的新方案，鼓励开发者使用PHP最佳实践。Josh通过实践揭示了PHP语言的这些新特性。你会学到关于应用架构、规划、数据库、安全、测试、调试和部署方面的最佳实践。如果你具有PHP基础知识，想提高自己的技能，绝对不能错过这本书。• 学习现代的PHP特性，例如命名空间、性状、生成器和闭包。• 探索如何查找、使用和创建PHP组件。• 遵从应用安全方面的最佳实践，将其运用在数据库、错误和异常处理等方面。• 学习部署、调优、测试和分析PHP应用的工具和技术。• 探索Facebook开发的HHVM和Hack语言。• 搭建与生产服务器高度一致的本地开发环境。', b'0'),
    (7, 'D5E1060E-88FE-0C4C-E849-9CE001F5CF13', '算法导论（原书第2版）', '2018-09-28 10:55:01', 93, 3, '(美)Thomas H.Cormen', '这本书深入浅出，全面地介绍了计算机算法。对每一个算法的分析既易于理解又十分有趣，并保持了数学严谨性。本书的设计目标全面，适用于多种用途。涵盖的内容有：算法在计算中的作用，概率分析和随机算法的介绍。书中专门讨论了线性规划，介绍了动态规划的两个应用，随机化和线性规划技术的近似算法等，还有有关递归求解、快速排序中用到的划分方法与期望线性时间顺序统计算法，以及对贪心算法元素的讨论。此书还介绍了对强连通子图算法正确性的证明，对哈密顿回路和子集求和问题的NP完全性的证明等内容。全书提供了900多个练习题和思考题以及叙述较为详细的实例研究。', b'0'),
    (8, '58930FBD-382E-D723-5B9F-E88A2F31C5C6', '数据结构与算法分析', '2018-09-28 10:55:01', 89, 3, '(美)Mark Allen Weiss', '本书是《Data Structures and Algorithm Analysis in C》一书第2版的简体中译本。原书曾被评为20世纪顶尖的30部计算机著作之一，作者Mark Allen Weiss在数据结构和算法分析方面卓有建树，他的数据结构和算法分析的著作尤其畅销，并受到广泛好评．已被世界500余所大学用作教材。在本书中，作者更加精炼并强化了他对算法和数据结构方面创新的处理方法。通过C程序的实现，着重阐述了抽象数据类型的概念，并对算法的效率、性能和运行时间进行了分析。全书特点如下：●专用一章来讨论算法设计技巧，包括贪婪算法、分治算法、动态规划、随机化算法以及回溯算法●介绍了当前流行的论题和新的数据结构，如斐波那契堆、斜堆、二项队列、跳跃表和伸展树●安排一章专门讨论摊还分析，考查书中介绍的一些高级数据结构●新开辟一章讨论高级数据结...', b'0'),
    (9, '64CDD23D-FF1B-DB3C-685E-2F473A366E17', '图解HTTP', '2018-09-28 10:55:01', 81, 1, '(日)上野宣','本书对互联网基盘——HTTP协议进行了全面系统的介绍。作者由HTTP协议的发展历史娓娓道来，严谨细致地剖析了HTTP协议的结构，列举诸多常见通信场景及实战案例，最后延伸到Web安全、最新技术动向等方面。本书的特色为在讲解的同时，辅以大量生动形象的通信图例，更好地帮助读者深刻理解HTTP通信过程中客户端与服务器之间的交互情况。读者可通过本书快速了解并掌握HTTP协议的基础，前端工程师分析抓包数据，后端工程师实现REST API、实现自己的HTTP服务器等过程中所需的HTTP相关知识点本书均有介绍。本书适合Web开发工程师，以及对HTTP协议感兴趣的各层次读者。', b'0'),
    (10, '9DC48BCB-7C54-7DF1-941B-473D8B1C13C6', '百年孤独', '2018-09-28 10:55:01', 92, 6, '(哥伦比亚)加西亚·马尔克斯','《百年孤独》是魔幻现实主义文学的代表作，描写了布恩迪亚家族七代人的传奇故事，以及加勒比海沿岸小镇马孔多的百年兴衰，反映了拉丁美洲一个世纪以来风云变幻的历史。作品融入神话传说、民间故事、宗教典故等神秘因素，巧妙地糅合了现实与虚幻，展现出一个瑰丽的想象世界，成为20世纪最重要的经典文学巨著之一。1982年加西亚•马尔克斯获得诺贝尔文学奖，奠定世界级文学大师的地位，很大程度上乃是凭借《百年孤独》的巨大影响。', b'0'),
    (11, '1D16EB77-85FC-D5D2-C6A8-B0F64C67A4D0', '红楼梦', '2018-09-28 10:55:01', 96, 5, '(清)曹雪芹','《红楼梦》是一部百科全书式的长篇小说。以宝黛爱情悲剧为主线，以四大家族的荣辱兴衰为背景，描绘出18世纪中国封建社会的方方面面，以及封建专制下新兴资本主义民主思想的萌动。结构宏大、情节委婉、细节精致，人物形象栩栩如生，声口毕现，堪称中国古代小说中的经典。由红楼梦研究所校注、人民文学出版社出版的《红楼梦》以庚辰（1760）本《脂砚斋重评石头记》为底本，以甲戌（1754）本、已卯（1759）本、蒙古王府本、戚蓼生序本、舒元炜序本、郑振铎藏本、红楼梦稿本、列宁格勒藏本（俄藏本）、程甲本、程乙本等众多版本为参校本，是一个博采众长、非常适合大众阅读的本子；同时，对底本的重要修改，皆出校记，读者可因以了解《红楼梦》的不同版本状况。红学所的校注本已印行二十五年，其间1994年曾做过一次修订，又十几年过去，2008年推出修订第三版，体现了新的校注成果和科研成果。关于《红楼梦》的作者，原本就有多种说法及推想，“前八十回曹雪芹著、后四十回高鹗续”的说法只是其中之一，这次修订中校注者改为“前八十回曹雪芹著；后四十回无名氏续，程伟元、高鹗整理”，应当是一种更科学的表述，体现了校注者对这一问题的新的认识。现在这个修订后的《红楼梦》是更加完善。', b'0'),
    (12, 'F866E83B-DE55-D28F-9055-1D7EDD425E75', '海边的卡夫卡', '2018-09-28 10:55:01', 81, 4, '(日)村上春树','小说的主人公是一位自称名叫田村卡夫卡——作者始终未交代其真名——的少年。他在十五岁生日前夜独自离家出走，乘坐夜行长途巴士远赴四国。出走的原因是为了逃避父亲所作的比俄底浦斯王还要可怕的预言：尔将弑父，将与尔母、尔姐交合。卡夫卡四岁时，母亲突然失踪，带走了比卡夫卡年长四岁、其实是田村家养女的姐姐，不知何故却将亲生儿子抛弃。他从未见过母亲的照片，甚至连名字也不知道。仿佛是运命在冥冥之中引导，他偶然来到某私立图书馆，遂栖身于此。馆长佐伯女士是位四十多岁气质高雅的美妇，有着波澜曲折的神秘身世。卡夫卡疑心她是自己的生母，佐伯却对此不置可否。卡夫卡恋上了佐伯，并与之发生肉体关系。小说还另设一条副线，副线的主角是老人中田，他在二战期间读小学时，经历过一次神秘的昏迷事件，从此丧失了记忆，将学过的知识完全忘记，甚至不会认字计数，却获得了与猫对话的神秘能力。中田在神智失控的情况下杀死了一个自称焦尼·沃卡（Johnny Walker）、打扮得酷似那著名威士忌酒商标上所画的英国绅士的狂人，一路搭车也来到此地。小说共分49章，奇数章基本上用写实手法讲述卡夫卡的故事，偶数章则用魔幻手法展现中田的奇遇。两种手法交互使用，编织出极富强烈虚构色彩的、奇幻诡诘的现代寓言。佐伯是将这两个故事联结为一体的结合点，而弑父的预言似乎最终也未能避免，因为狂人焦尼·沃卡居然是卡夫卡生父乔装改扮的，真正的凶手也并非中田……', b'0');

CREATE TABLE IF NOT EXISTS `book_user` (
    `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_uuid` varchar(36) NOT NULL,
    `username` varchar(20) NOT NULL,
    `password` varchar(40) NOT NULL,
    `email` varchar(40) NOT NULL,
    `phone` int(10) unsigned NULL,
    `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `is_del` bit(1) NOT NULL DEFAULT b'0',
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `user_id_UNIQUE`(`user_id`, `user_uuid`),
    KEY `user_uuid_idx` (`user_uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `book_user`(`user_id`, `user_uuid`, `username`, `password`, `email`, `phone`, `create_time`, `is_del`) VALUES
    (1, '62EDF0BA-3060-4D89-3010-0CD37F2EC6B8', 'zhangyiyu', '89e495e7941cf9e40e6980d14a16bf023ccd4c91', 'a125@qq.com', NULL, '2018-09-28 10:55:01', b'0');

INSERT INTO `book_admin`(`admin_id`, `username`, `email`, `password`, `create_time`, `is_del`) VALUES
    (1, 'zhangyiyu', 'a125@qq.com', '89e495e7941cf9e40e6980d14a16bf023ccd4c91', '2018-09-28 10:55:01', b'0');
