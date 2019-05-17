<?php
return array(
	//客户端类型列表
	'clients' => array(
		//ID => 名称
		1	=> 'iPhone',
		2	=> 'iPad',
		3	=> 'Android',
		4	=> 'PC(浏览器)',
		5	=> 'WinPhone',
		6	=> 'HTML5',
		7	=> 'PC(客户端)',
	),
	//房间等级
	'roomlevel' =>array(
		//等级=>名称
		12=>'初级场', 
		13=>'中级场', 
		14=>'高级场', 
		15=>'职业场', 
		16=>'精英场', 
		17=>'大师场',
		51=>'免费场',
		100=>'私人场',
		110=>'疯狂场',
		500=>'快速赛',#Warning#---当level大于500时不会记录牌局金币流---
		600=>'VIP比赛',
		700=>'定时赛',
	),
	//用户类型映射表
	'usertypes' => array(
		//用户类型ID => '用户类型',
		1 => '游客',
		2 => '博雅通行证',
		3 => '新浪微博',
		4 => '腾讯微博',
		5 => '淘宝帐号',
		6 => '360账号',
		7 => '移动基地',
        8 => '爱游戏',
        9 => '华为',
		1001 => '博雅通行证-博雅号', //占位ID，仅用于数据上报
		1002 => '博雅通行证-手机', //占位ID，仅用于数据上报
		1003 => '博雅通行证-邮箱', //占位ID，仅用于数据上报
	),
	//操作金钱action
	'moneyactid'=> array( //0~99 server使用 100以上PHP使用
		0	=> '玩牌获得',
		1	=> '玩牌失去',
		2	=> '破产救济',
		4	=> '免费场连胜奖励',
		5	=> '免费场累计奖励',
		20	=> '台费消耗',
		35  => '快速赛-报名费',
		36  => '快速赛-退赛',
		37  => '快速赛-奖励',
		38  => '限时赛-报名费',
		39  => '限时赛-退赛',
		40  => '限时赛-奖励',
		41  => '定时赛-报名费',
		42  => '定时赛-退赛',
		43  => '定时赛-奖励',
		44	=> '自建赛-报名',
		45	=> '自建赛-退赛',
		46	=> '自建赛-奖励',
		100	=> '用户注册',
		101	=> '连续登录',
		102	=> '管理员赠送',
		103	=> '管理员扣除',
		104	=> '任务奖励',
		105 => '充值金币',
		106 => '充值赠送金币',
		107 => '保险箱存钱',
		108 => '保险箱取钱',
		109 => '保险箱手续费',
		110 => '钻石功能掉落金币',
		114 => '每日任务奖励',
		115 => '普通任务奖励',
		116 => '社交任务奖励',
		117 => '淘金币兑换',
		118 => '广播消息花费',
		119 => '运营活动接口消耗',
		120 => '运营活动接口奖励',
		121 => '钻石兑换金币',
		122 => '首充加赠',
		123 => '老虎机下注',
		124 => '老虎机奖励',
		125 => '邀请奖励',
		126 => 'VIP周卡赠送',
		127 => 'VIP月卡赠送',
		128 => '付费引导充值赠送金币',
		129 => '川味斗地主奖池赛报名费',
		130 => '推送奖励',
		131 => '私信奖励',
		132 => 'QQ群奖励',
		133 => '绑定代理商手机号送金币',
		134 => '普通特权加赠',
		135 => '玩家绑定代理',
		136	=> '首次设置用户资料',
		137 => '免费场玩牌奖励',
		138	=> '水晶购买',
		139 => '连续签到奖励',
		140	=> '游戏内老虎机下注',
		141	=> '游戏内老虎机奖励',
		5000 => '道具奖励开始占位',#道具开始
		5001 => '道具VIP购买赠送',
		5002 => '道具购买',
		5003 => '道具购买失败取消扣费',
		6000 => '道具奖励结束占位',#道具结束
		10001 => '春节登录活动', //10001 ~ 20000 为活动金币操作
		10002 => '礼包活动',
		10003 => '社区联赛',
		10004 => '邀请好友',
		10005 => '推广码',
		10006 => '贰柒拾争霸赛',
		10007 => '中秋嘉年华活动',
		10008 => '更新到V1.3.0奖励',
		10009 => '圣诞嘉年华活动',
		10010 => '新春嘉年华活动',
		10011 => '贰柒十争霸赛奖励',
		10012 => '五一充值活动',
		10013 => '端午节充值活动',
		10014 => '微信摇一摇活动',
		10015 => '逮棋牌君活动',
		20001 => '每日任务-子任务', //20001 ~ 30000 为任务金币操作
	),
	//操作钻石action
	'diamondactid'=> array(
		1	=> '玩牌获得',
		2	=> '购买失去',
		3	=> '每日登录奖励',
		4	=> '每日任务奖励',
		5	=> '运营活动奖励',
		6	=> '运营活动消耗',
		7   => '马股挑战赛奖励',
		8	=> '邀请奖励',
		9	=> '道具赠送',
		10	=> '推送奖励',
		11	=> '贰柒十争霸赛奖励',
		12	=> '私信奖励',
		13  => '礼包活动',
		14  => '绑定代理商手机号送钻石',
		15  => '首充加赠',
		16  => '二充加赠',
		17  => '普通特权加赠',
		18  => '普通加赠',
		19	=> '购买失败回退',
		20 => '限时赛-奖励',
		21 => '限时赛-报名费',
		22 => '快速赛-奖励',
		23 => '快速赛-报名费',
		24 => '定时赛-奖励',
		25 => '定时赛-报名费',
		26 => '五一充值活动',
		27 => '微信红包摇一摇',
		28 => '绑定代理',
		29 => '测试钻石兑换',
		30 => '连赢牌局钻石',
		31 => '连续签到奖励',
		32 => '宜宾马股挑战赛活动奖励',
		999 => '后台赠送',
	),
	//操作银元action
	'silveractid'=> array(
		1	=> '玩牌获得',
		2	=> '推送奖励',
		3	=> '私信奖励',
		4	=> '每日任务奖励',
		10	=> '运营活动奖励',
		11	=> '运营活动消耗',
		20 => '限时赛-报名费',
		21 => '快速赛-报名费',
		22 => '定时赛-报名费',
		23 => '定时赛-退赛',
		24 => '定时赛-奖励',
		999 => '后台赠送',
	),
	//水晶操作action
	'crystalactid'=> array(
		1	=> '购买获得',
		2	=> '购买消耗',
		3	=> '发货失败退回',
		4	=> '游戏内老虎机下注',
		5	=> '游戏内老虎机中奖',
		10	=> '运营活动奖励',
		11	=> '运营活动消耗',
		999	=> '后台赠送',
	),
	//开放平台配置
	'platforms' => array(
		'boyaauc' => array('id' => '1362118431', 'key' => '', 'secret' => 'dfqpjt$!@iz%s_=a*Aux#23!@#(No_PC'),
		'taobao' => array('id' => '27515', 'key' => '', 'secret' => '8df3fd9bee40c635d7fa39e1f6dead1f'),
		'360' => array('id' => 202567201, 'key' => '463da1512d494917440e35eb6ad913cc', 'secret' => 'b37ac54c57a8d20cde3e1661c4565a75'),
        'aiyouxi' => array(
			//1 => array('id' => '5017771', 'key' => '', 'secret' => '986d56fef5141a937b3ee1988d8dfa5b'),
			//2 => array('id' => '5026952', 'key' => '', 'secret' => '339b59129f377c1d8ba77bda9bdf778e'),
			//4 => array('id' => '5061692', 'key' => '', 'secret' => 'e3bf884fa3d4580e283386c6a6e7458b'),
			5 => array('id' => '37322211', 'key' => '', 'secret' => 'ebb358778c7347abae3bd788a89b4784'),
        ),
    ),
	'voudcher' => array(
		1 => array(11,201),
		2 => array(189,384),
		3 => array(190,385),
		4 => array(188,383),		
	),
);
