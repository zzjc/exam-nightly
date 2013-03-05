<?php
return array(
    'APP_DEBUG' => false,
    // 数据库配置
    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'exam',
    'DB_USER' => 'root',
    'DB_PWD' => '',
    'DB_PORT' => '3306',
    'DB_PREFIX' => '',
    'SALT' => 'wtf%6%#*&2',
    // 分组配置
    'APP_GROUP_LIST' => 'Home,Admin,Api',
    'DEFAULT_GROUP' => 'Home',
    // URL 配置
    'URL_MODEL' => '2',
    'URL_CASE_INSENSITIVE' => true,
    // 表单令牌验证
    'TOKEN_ON' => true,
    'TOKEN_NAME' => '__hash__',
    'TOKEN_TYPE' => 'md5',
    
    // 站点信息
    'URL' => 'http://192.168.100.217/exam/'
);