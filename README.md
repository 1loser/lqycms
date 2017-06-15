# lqycms
基于laravel框架的开源cms管理系统，git clone https://github.com/Fanli2012/lqycms.git


# 说明

1、基于Laravel 5.4

2、PHP+Mysql

3、后台登录：/fladmin/login，账号：admin888，密码：admin

4、恢复后台默认账号密码：/fladmin/recoverpwd


# 安装

1、 导入数据库
1) 打开根目录下的lqycms.sql文件，将 http://www.lqycms.com 改成自己的站点根网址，格式：http://+域名
2) 导入数据库

2、 复制.env.example重命名成.env，修改相应配置APP_DOMAIN、APP_SUBDOMAIN及数据库配置

3、 
php composer.phar install

php artisan key:generate


4、 登录后台：/fladmin/login.php，账号：admin888，密码：admin

顶部按钮，更新缓存


# 注意
只能放在根目录
如果要开启调试模式，请修改 .env 文件， APP_ENV=local 和 APP_DEBUG=true 。