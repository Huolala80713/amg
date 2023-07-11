<?php
function getMenuList(){
    return [
        [
            'name' => '参数设置',
            'url' => 'zb9n8rUvp0.php?m=setting&a=setting',
            'm' => 'setting',
            'fa' => 'fa fa-commenting-o',
            'id' => 1,
            'child_list' => [

            ]
        ],
        [
            'name' => '管理员管理',
            'url' => 'zb9n8rUvp0.php?m=admin',
            'm' => 'admin',
            'fa' => 'fa fa-user',
            'id' => '4',
            'child_list' => []
        ],
        [
            'name' => '游戏设置',
            'url' => '#',
            'm' => 'g_setting',
            'fa' => 'fa fa-gamepad',
            'id' => '2',
            'child_list' => [
			    [
                    'name' => '澳洲幸运10',
                    'url' => 'zb9n8rUvp0.php?m=g_setting&g=1',
                    'a' => 'setaozhou',
                    'id' => '2_0',
                ],
                [
                    'name' => '幸运飞艇',
                    'url' => 'zb9n8rUvp0.php?m=g_setting&g=2',
                    'a' => 'setfeiting',
                    'id' => '2_1',
                ],
                [
                    'name' => '极速飞艇',
                    'url' => 'zb9n8rUvp0.php?m=g_setting&g=3',
                    'a' => 'setjishu',
                    'id' => '2_2',
                ],
                [
                    'name' => '极速赛车',
                    'url' => 'zb9n8rUvp0.php?m=g_setting&g=4',
                    'a' => 'setsaiche',
                    'id' => '2_3',
                ],
                [
                    'name' => '加拿大28',
                    'url' => 'zb9n8rUvp0.php?m=g_setting&g=4',
                    'a' => 'setjinada',
                    'id' => '2_4',
                ],
                [
                    'name' => '极速摩托/飞艇',
                    'url' => 'zb9n8rUvp0.php?m=g_setting&g=5',
                    'a' => 'none',
                    'id' => '2_5',
                ],
			]
        ],
        [
            'name' => '即时注单',
            'url' => '#',
            'm' => 'zhudan',
            'fa' => 'fa fa-gamepad',
            'id' => '3',
            'child_list' => []
        ],
        [
            'name' => '分数报表',
            'url' => '#',
            'm' => 'report',
            'fa' => 'fa fa-list-ol',
            'id' => '7',
            'child_list' => [
                [
                    'name' => '分数报表汇总',
                    'url' => 'zb9n8rUvp0.php?m=report&a=huizong',
                    'a' => 'huizong',
                    'id' => '7_0',
                ],
                [
                    'name' => '盈亏报表',
                    'url' => 'zb9n8rUvp0.php?m=report&a=baobiao',
                    'a' => 'baobiao',
                    'id' => '7_1',
                ],
                [
                    'name' => '即时注单',
                    'url' => 'zb9n8rUvp0.php?m=report&a=jishi',
                    'a' => 'jishi',
                    'id' => '7_2',
                ],
                [
                    'name' => '账变日志',
                    'url' => 'zb9n8rUvp0.php?m=report&a=up',
                    'a' => 'up',
                    'id' => '7_3',
                ],
                [
                    'name' => '期期报表',
                    'url' => 'zb9n8rUvp0.php?m=report&a=term',
                    'a' => 'term',
                    'id' => '7_4',
                ],
                [
                    'name' => '未结算报表',
                    'url' => 'zb9n8rUvp0.php?m=report&a=none',
                    'a' => 'none',
                    'id' => '7_5',
                ],
            ]
        ],
        [
            'name' => '分数管理',
            'url' => '#',
            'm' => 'manage',
            'fa' => 'fa fa-database',
            'id' => '8',
            'child_list' => [
                [
                    'name' => '上分管理',
                    'url' => 'zb9n8rUvp0.php?m=manage&a=up',
                    'a' => 'up',
                    'id' => '8_1',
                ],
                [
                    'name' => '下分管理',
                    'url' => 'zb9n8rUvp0.php?m=manage&a=down',
                    'a' => 'down',
                    'id' => '8_2',
                ]
            ]
        ],
        [
            'name' => '用户管理',
            'url' => 'zb9n8rUvp0.php?m=user',
            'fa' => 'fa fa-users',
            'm' => 'user',
            'id' => '5',
            'child_list' => []
        ],
        [
            'name' => '禁言管理',
            'url' => 'zb9n8rUvp0.php?m=ban',
            'fa' => 'glyphicon glyphicon-warning-sign',
            'm' => 'ban',
            'id' => '6',
            'child_list' => []
        ],

        [
            'name' => '代理系统',
            'url' => '#',
            'm' => 'extend',
            'fa' => 'fa fa-user-plus',
            'id' => '14',
            'child_list' => [
                '14_1' => [
                    'name' => '代理管理',
                    'url' => 'zb9n8rUvp0.php?m=extend&a=list',
                    'a' => 'extend',
                    'id' => '14_1',
                ],
                '14_2' => [
                    'name' => '代理设置',
                    'url' => 'zb9n8rUvp0.php?m=extend&a=agent',
                    'a' => 'agent',
                    'id' => '14_2',
                ]
            ]
        ],
//        [
//            'name' => '退水设置',
//            'url' => 'zb9n8rUvp0.php?m=tui',
//            'm' => 'tui',
//            'fa' => 'fa fa-cloud-download',
//            'id' => '9',
//            'child_list' => [
//
//            ]
//        ],
        [
            'name' => '聊天管理',
            'url' => '#',
            'm' => 'chat',
            'fa' => 'fa fa-comments-o',
            'id' => '10',
            'child_list' => [
                [
                    'name' => '房间聊天',
                    'url' => 'zb9n8rUvp0.php?m=chat&a=room',
                    'a' => 'room',
                    'id' => '10_1',
                ],
                [
                    'name' => '客服管理',
                    'url' => 'zb9n8rUvp0.php?m=chat&a=custom',
                    'a' => 'custom',
                    'id' => '10_2',
                ]
            ]
        ],
        [
            'name' => '自动拖管理',
            'url' => '#',
            'm' => 'robot',
            'fa' => 'fa fa-commenting-o',
            'id' => '11',
            'child_list' => [
//                [
//                    'name' => '方案管理',
//                    'url' => 'zb9n8rUvp0.php?m=robot&a=plan',
//                    'a' => 'plan',
//                    'id' => '11_1',
//                ],
                [
                    'name' => '机器人管理',
                    'url' => 'zb9n8rUvp0.php?m=robot&a=robots',
                    'a' => 'robots',
                    'id' => '11_2',
                ]
            ]
        ],
        [
            'name' => '数据清理',
            'url' => 'zb9n8rUvp0.php?m=clean',
            'm' => 'clean',
            'fa' => 'fa fa-trash',
            'id' => '12',
            'child_list' => [

            ]
        ],
        [
            'name' => '操作日志',
            'url' => 'zb9n8rUvp0.php?m=dolog',
            'm' => 'dolog',
            'fa' => 'fa fa-reorder',
            'id' => '14',
            'child_list' => [

            ]
        ],
//        [
//            'name' => '手动补期',
//            'url' => 'zb9n8rUvp0.php?m=kaijiang',
//            'm' => 'tui',
//            'fa' => 'fa fa-cubes',
//            'id' => '13',
//            'child_list' => [
//
//            ]
//        ],
    ];
}