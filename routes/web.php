<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//wap路由，要放到最前面，否则解析不到
Route::group(['domain' => env('APP_SUBDOMAIN'), 'namespace' => 'Wap'], function () {
	Route::get('/', 'IndexController@index')->name('wap_home');
	Route::get('/page404', 'IndexController@page404')->name('wap_page404');     //404页面
	Route::get('/tags', 'IndexController@tags')->name('wap_tags');
	Route::get('/search/{id}', 'IndexController@search')->name('wap_search');   //搜索页面
	Route::get('/p/{id}', 'IndexController@detail')->name('wap_detail');        //详情页
	Route::get('/cat{cat}/{page}', 'IndexController@category');                 //分类页，分页
	Route::get('/cat{cat}', 'IndexController@category')->name('wap_category');  //分类页
	Route::get('/tag{tag}/{page}', 'IndexController@tag');                      //标签页，分页
	Route::get('/tag{tag}', 'IndexController@tag')->name('wap_tag');            //标签页
	Route::get('/page/{id}', 'IndexController@page')->name('wap_singlepage');   //单页
	Route::get('/goods/{id}', 'IndexController@goods')->name('wap_goods');      //商品详情页
	Route::get('/goodstype{cat}/{page}', 'IndexController@goodstype');          //产品分类页，分页
	Route::get('/goodstype{cat}', 'IndexController@goodstype')->name('wap_goodstype'); //产品分类页
	Route::get('/sitemap.xml', 'IndexController@sitemap')->name('wap_sitemap'); //sitemap
});


//前台路由
Route::group(['namespace' => 'Home'], function () {
	Route::get('/', 'IndexController@index')->name('home');
	Route::get('/page404', 'IndexController@page404')->name('page404');         //404页面
	Route::get('/tags', 'IndexController@tags')->name('home_tags');
	Route::get('/search/{id}', 'IndexController@search')->name('home_search');  //搜索页面
	Route::get('/p/{id}', 'IndexController@detail')->name('home_detail');       //详情页
	Route::get('/cat{cat}/{page}', 'IndexController@category');                 //分类页，分页
	Route::get('/cat{cat}', 'IndexController@category')->name('home_category'); //分类页
	Route::get('/tag{tag}/{page}', 'IndexController@tag');                      //标签页，分页
	Route::get('/tag{tag}', 'IndexController@tag')->name('home_tag');           //标签页
	Route::get('/page/{id}', 'IndexController@page')->name('home_singlepage');  //单页
	Route::get('/goods/{id}', 'IndexController@goods')->name('home_goods');     //商品详情页
	Route::get('/goodstype{cat}/{page}', 'IndexController@goodstype');          //产品分类页，分页
	Route::get('/goodstype{cat}', 'IndexController@goodstype')->name('home_goodstype'); //产品分类页
	Route::get('/sitemap.xml', 'IndexController@sitemap')->name('home_sitemap');//sitemap
	
	Route::get('/test', 'IndexController@test')->name('home_test');             //测试
	Route::get('/aaa', function () {
		dd('wap');
	});
});


//微信路由
Route::group(['prefix' => 'weixin', 'namespace' => 'Weixin'], function () {
	Route::get('/', 'IndexController@index')->name('weixin');
	Route::get('/page404', 'IndexController@page404')->name('weixin_page404');         //404页面
	Route::get('/search', 'IndexController@search')->name('weixin_search');  //搜索页面
	Route::get('/p/{id}', 'ArticleController@detail')->name('weixin_article_detail'); //文章详情页
	Route::get('/cat{cat}', 'ArticleController@category')->name('weixin_article_category'); //分类页
	Route::get('/tag{tag}', 'IndexController@tag')->name('weixin_tag');           //标签页
	Route::get('/page/{id}', 'IndexController@page')->name('weixin_singlepage');  //单页
	Route::get('/goods/{id}', 'GoodsController@goods')->name('weixin_goods_detail'); //商品详情页
	Route::get('/goodslist', 'GoodsController@goodslist')->name('weixin_goods_list'); //产品分类页
    
	Route::get('/user', 'UserController@index')->name('weixin_user');
    Route::get('/user_goods_history', 'UserController@userGoodsHistory')->name('weixin_user_goods_history');
    Route::get('/user_goods_history_delete', 'UserController@userGoodsHistoryDelete')->name('weixin_user_goods_history_delete');
    Route::get('/user_goods_history_clear', 'UserController@userGoodsHistoryClear')->name('weixin_user_goods_history_clear');
    
    Route::get('/cart', 'CartController@index')->name('weixin_cart');
    
    Route::get('/user_address', 'AddressController@index')->name('weixin_user_address_list');
    
    //页面跳转
	Route::get('/jump', 'IndexController@jump')->name('weixin_jump');
    
	Route::get('/test', 'IndexController@test')->name('weixin_test');           //测试
});


//后台路由
Route::group(['prefix' => 'fladmin', 'namespace' => 'Admin', 'middleware' => ['web']], function () {
	Route::get('/', 'IndexController@index')->name('admin');
	Route::get('/welcome', 'IndexController@welcome')->name('admin_welcome');
	Route::get('/index/upconfig', 'IndexController@upconfig')->name('admin_index_upconfig'); //更新系统参数配置
	Route::get('/index/upcache', 'IndexController@upcache')->name('admin_index_upcache'); //更新缓存
	//文章
	Route::get('/article', 'ArticleController@index')->name('admin_article');
	Route::get('/article/add', 'ArticleController@add')->name('admin_article_add');
	Route::post('/article/doadd', 'ArticleController@doadd')->name('admin_article_doadd');
	Route::get('/article/edit', 'ArticleController@edit')->name('admin_article_edit');
	Route::post('/article/doedit', 'ArticleController@doedit')->name('admin_article_doedit');
	Route::get('/article/del', 'ArticleController@del')->name('admin_article_del');
	Route::get('/article/repetarc', 'ArticleController@repetarc')->name('admin_article_repetarc');
	Route::get('/article/recommendarc', 'ArticleController@recommendarc')->name('admin_article_recommendarc');
	Route::get('/article/articleexists', 'ArticleController@articleexists')->name('admin_article_articleexists');
	//栏目
	Route::get('/category', 'CategoryController@index')->name('admin_category');
	Route::get('/category/add', 'CategoryController@add')->name('admin_category_add');
	Route::post('/category/doadd', 'CategoryController@doadd')->name('admin_category_doadd');
	Route::get('/category/edit', 'CategoryController@edit')->name('admin_category_edit');
	Route::post('/category/doedit', 'CategoryController@doedit')->name('admin_category_doedit');
	Route::get('/category/del', 'CategoryController@del')->name('admin_category_del');
	//标签
	Route::get('/tag', 'TagController@index')->name('admin_tag');
	Route::get('/tag/add', 'TagController@add')->name('admin_tag_add');
	Route::post('/tag/doadd', 'TagController@doadd')->name('admin_tag_doadd');
	Route::get('/tag/edit', 'TagController@edit')->name('admin_tag_edit');
	Route::post('/tag/doedit', 'TagController@doedit')->name('admin_tag_doedit');
	Route::get('/tag/del', 'TagController@del')->name('admin_tag_del');
	//单页
	Route::get('/page', 'PageController@index')->name('admin_page');
	Route::get('/page/add', 'PageController@add')->name('admin_page_add');
	Route::post('/page/doadd', 'PageController@doadd')->name('admin_page_doadd');
	Route::get('/page/edit', 'PageController@edit')->name('admin_page_edit');
	Route::post('/page/doedit', 'PageController@doedit')->name('admin_page_doedit');
	Route::get('/page/del', 'PageController@del')->name('admin_page_del');
	//产品
	Route::get('/goods', 'GoodsController@index')->name('admin_goods');
	Route::get('/goods/add', 'GoodsController@add')->name('admin_goods_add');
	Route::post('/goods/doadd', 'GoodsController@doadd')->name('admin_goods_doadd');
	Route::get('/goods/edit', 'GoodsController@edit')->name('admin_goods_edit');
	Route::post('/goods/doedit', 'GoodsController@doedit')->name('admin_goods_doedit');
	Route::get('/goods/del', 'GoodsController@del')->name('admin_goods_del');
	Route::get('/goods/recommendarc', 'GoodsController@recommendarc')->name('admin_goods_recommendarc');
	Route::get('/goods/articleexists', 'GoodsController@goodsexists')->name('admin_goods_goodsexists');
	//产品分类
	Route::get('/goodstype', 'GoodsTypeController@index')->name('admin_goodstype');
	Route::get('/goodstype/add', 'GoodsTypeController@add')->name('admin_goodstype_add');
	Route::post('/goodstype/doadd', 'GoodsTypeController@doadd')->name('admin_goodstype_doadd');
	Route::get('/goodstype/edit', 'GoodsTypeController@edit')->name('admin_goodstype_edit');
	Route::post('/goodstype/doedit', 'GoodsTypeController@doedit')->name('admin_goodstype_doedit');
	Route::get('/goodstype/del', 'GoodsTypeController@del')->name('admin_goodstype_del');
	//友情链接
	Route::get('/friendlink', 'FriendlinkController@index')->name('admin_friendlink');
	Route::get('/friendlink/add', 'FriendlinkController@add')->name('admin_friendlink_add');
	Route::post('/friendlink/doadd', 'FriendlinkController@doadd')->name('admin_friendlink_doadd');
	Route::get('/friendlink/edit', 'FriendlinkController@edit')->name('admin_friendlink_edit');
	Route::post('/friendlink/doedit', 'FriendlinkController@doedit')->name('admin_friendlink_doedit');
	Route::get('/friendlink/del', 'FriendlinkController@del')->name('admin_friendlink_del');
	//关键词管理
	Route::get('/keyword', 'KeywordController@index')->name('admin_keyword');
	Route::get('/keyword/add', 'KeywordController@add')->name('admin_keyword_add');
	Route::post('/keyword/doadd', 'KeywordController@doadd')->name('admin_keyword_doadd');
	Route::get('/keyword/edit', 'KeywordController@edit')->name('admin_keyword_edit');
	Route::post('/keyword/doedit', 'KeywordController@doedit')->name('admin_keyword_doedit');
	Route::get('/keyword/del', 'KeywordController@del')->name('admin_keyword_del');
	//搜索关键词
	Route::get('/searchword', 'SearchwordController@index')->name('admin_searchword');
	Route::get('/searchword/add', 'SearchwordController@add')->name('admin_searchword_add');
	Route::post('/searchword/doadd', 'SearchwordController@doadd')->name('admin_searchword_doadd');
	Route::get('/searchword/edit', 'SearchwordController@edit')->name('admin_searchword_edit');
	Route::post('/searchword/doedit', 'SearchwordController@doedit')->name('admin_searchword_doedit');
	Route::get('/searchword/del', 'SearchwordController@del')->name('admin_searchword_del');
	//幻灯片
	Route::get('/slide', 'SlideController@index')->name('admin_slide');
	Route::get('/slide/add', 'SlideController@add')->name('admin_slide_add');
	Route::post('/slide/doadd', 'SlideController@doadd')->name('admin_slide_doadd');
	Route::get('/slide/edit', 'SlideController@edit')->name('admin_slide_edit');
	Route::post('/slide/doedit', 'SlideController@doedit')->name('admin_slide_doedit');
	Route::get('/slide/del', 'SlideController@del')->name('admin_slide_del');
	//在线留言管理
	Route::get('/guestbook', 'GuestbookController@index')->name('admin_guestbook');
	Route::get('/guestbook/del', 'GuestbookController@del')->name('admin_guestbook_del');
	//系统参数配置
	Route::get('/sysconfig', 'SysconfigController@index')->name('admin_sysconfig');
	Route::get('/sysconfig/add', 'SysconfigController@add')->name('admin_sysconfig_add');
	Route::post('/sysconfig/doadd', 'SysconfigController@doadd')->name('admin_sysconfig_doadd');
	Route::get('/sysconfig/edit', 'SysconfigController@edit')->name('admin_sysconfig_edit');
	Route::post('/sysconfig/doedit', 'SysconfigController@doedit')->name('admin_sysconfig_doedit');
	Route::get('/sysconfig/del', 'SysconfigController@del')->name('admin_sysconfig_del');
	//用户管理
	Route::get('/user', 'UserController@index')->name('admin_user');
	Route::get('/user/add', 'UserController@add')->name('admin_user_add');
	Route::post('/user/doadd', 'UserController@doadd')->name('admin_user_doadd');
	Route::get('/user/edit', 'UserController@edit')->name('admin_user_edit');
	Route::post('/user/doedit', 'UserController@doedit')->name('admin_user_doedit');
	Route::get('/user/del', 'UserController@del')->name('admin_user_del');
	//角色管理
	Route::get('/userrole', 'UserRoleController@index')->name('admin_userrole');
	Route::get('/userrole/add', 'UserRoleController@add')->name('admin_userrole_add');
	Route::post('/userrole/doadd', 'UserRoleController@doadd')->name('admin_userrole_doadd');
	Route::get('/userrole/edit', 'UserRoleController@edit')->name('admin_userrole_edit');
	Route::post('/userrole/doedit', 'UserRoleController@doedit')->name('admin_userrole_doedit');
	Route::get('/userrole/del', 'UserRoleController@del')->name('admin_userrole_del');
	Route::get('/userrole/permissions', 'UserRoleController@permissions')->name('admin_userrole_permissions'); //权限设置
	Route::post('/userrole/dopermissions', 'UserRoleController@dopermissions')->name('admin_userrole_dopermissions');
	//菜单管理
	Route::get('/menu', 'MenuController@index')->name('admin_menu');
	Route::get('/menu/add', 'MenuController@add')->name('admin_menu_add');
	Route::post('/menu/doadd', 'MenuController@doadd')->name('admin_menu_doadd');
	Route::get('/menu/edit', 'MenuController@edit')->name('admin_menu_edit');
	Route::post('/menu/doedit', 'MenuController@doedit')->name('admin_menu_doedit');
	Route::get('/menu/del', 'MenuController@del')->name('admin_menu_del');
	//后台登录注销
	Route::get('/login', 'LoginController@login')->name('admin_login');
	Route::post('/dologin', 'LoginController@dologin')->name('admin_dologin');
	Route::get('/logout', 'LoginController@logout')->name('admin_logout');
	Route::get('/recoverpwd', 'LoginController@recoverpwd')->name('admin_recoverpwd');
	//页面跳转
	Route::get('/jump', 'LoginController@jump')->name('admin_jump');
	//测试
	Route::get('/test', 'LoginController@test')->name('admin_test');
});

//无需token验证，全局
Route::group(['middleware' => ['web']], function () {
    Route::post('/dataapi/listarc', 'Api\IndexController@listarc')->name('api_listarc');
    Route::post('/dataapi/customer_login', 'Api\WechatAuthController@customerLogin');
	Route::post('/dataapi/', 'Api\UserController@signin'); //签到
});

//API接口路由，无需token验证
Route::group(['prefix' => 'dataapi', 'namespace' => 'Api', 'middleware' => ['web']], function () {
    //轮播图
	Route::get('/slide_list', 'SlideController@slideList');
    //文章
	Route::get('/article_list', 'ArticleController@articleList');
    Route::get('/article_detail', 'ArticleController@articleDetail');
    Route::get('/arctype_list', 'ArctypeController@arctypeList');
    Route::get('/arctype_detail', 'ArctypeController@arctypeDetail');
    //商品
    Route::get('/goods_list', 'GoodsController@goodsList'); //商品列表
    Route::get('/goodstype_list', 'GoodsTypeController@goodsTypeList'); //商品分类列表
});

//API接口路由，需token验证
Route::group(['prefix' => 'dataapi', 'namespace' => 'Api', 'middleware' => ['web','token']], function () {
    //用户中心
    Route::post('/user_signin', 'UserController@signin'); //签到
    Route::get('/user_info', 'UserController@userInfo'); //用户详细信息
    Route::post('/user_info_update', 'UserController@userInfoUpdate'); //修改用户信息
    Route::get('/user_list', 'UserController@userList'); //用户列表
    
    Route::get('/user_money_list', 'UserMoneyController@userMoneyList'); //用户余额明细
    Route::post('/user_money_add', 'UserMoneyController@userMoneyAdd'); //添加余额明细
    //浏览记录
    Route::get('/user_goods_history_list', 'UserGoodsHistoryController@userGoodsHistoryList'); //我的足迹列表
    Route::post('/user_goods_history_delete', 'UserGoodsHistoryController@userGoodsHistoryDelete'); //我的足迹删除一条
    Route::post('/user_goods_history_clear', 'UserGoodsHistoryController@userGoodsHistoryClear'); //我的足迹清空
    
    //商品评价
    Route::get('/goods_comment_list', 'CommentController@goodsCommentList'); //商品评价列表
    Route::post('/goods_comment_add', 'CommentController@goodsCommentAdd'); //商品评价添加
    Route::post('/goods_comment_update', 'CommentController@goodsCommentUpdate'); //商品评价修改
    Route::post('/goods_comment_delete', 'CommentController@goodsCommentDelete'); //商品评价删除
    //商品收藏
    Route::get('/collect_goods_list', 'CollectGoodsController@collectGoodsList'); //收藏商品列表
    Route::post('/collect_goods_add', 'CollectGoodsController@collectGoodsAdd'); //收藏商品
    Route::post('/collect_goods_delete', 'CollectGoodsController@collectGoodsDelete'); //取消收藏商品
    //订单
    
    //购物车
    Route::get('/cart_list', 'CartController@cartList'); //购物车列表
    Route::post('/cart_clear', 'CartController@cartClear'); //清空购物车
    Route::post('/cart_add', 'CartController@cartAdd'); //添加购物车
    Route::post('/cart_update', 'CartController@cartUpdate'); //修改购物车
    //分销
    
    //积分
    Route::get('/user_point_list', 'UserPointController@userPointList'); //用户积分列表
    Route::post('/user_point_add', 'UserPointController@userPointAdd');
    //优惠券
    Route::get('/user_bonus_list', 'BonusController@userBonusList'); //用户优惠券列表
    Route::post('/user_get_bonus', 'BonusController@userGetBonus'); //用户获取优惠券
    Route::get('/bonus_list', 'BonusController@bonusList'); //可用获取的优惠券列表
    Route::post('/bonus_add', 'BonusController@bonusAdd'); //添加优惠券
    Route::post('/bonus_update', 'BonusController@bonusUpdate'); //修改优惠券
    Route::post('/bonus_delete', 'BonusController@bonusDelete'); //删除优惠券
    //微信
    
    //意见反馈
    Route::get('/feedback_list', 'FeedBackController@feedbackList');
    Route::post('/feedback_add', 'FeedBackController@feedbackAdd');
    
    //其它
    Route::get('/verifycode_check', 'VerifyCodeController@check'); //验证码校验
    Route::get('/andriod_upgrade', 'IndexController@andriodUpgrade'); //安卓升级
    //图片上传
    Route::post('/image_upload', 'ImageController@imageUpload'); //普通文件/图片上传
    //二维码
    Route::get('/create_simple_qrcode', 'QrcodeController@createSimpleQrcode');
    //收货地址
    Route::get('/user_address_list', 'UserAddressController@userAddressList');
    Route::get('/user_address_detail', 'UserAddressController@userAddressDetail');
    Route::post('/user_address_setdefault', 'UserAddressController@userAddressSetDefault');
    Route::post('/user_address_add', 'UserAddressController@userAddressAdd');
    Route::post('/user_address_update', 'UserAddressController@userAddressUpdate');
    Route::post('/user_address_delete', 'UserAddressController@userAddressDelete');
    //地区，省市区
	Route::get('/region_list', 'RegionController@regionList');
    Route::get('/region_detail', 'RegionController@regionDetail');
});







