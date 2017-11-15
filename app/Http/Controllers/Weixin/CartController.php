<?php
namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Weixin\CommonController;
use Illuminate\Http\Request;

class CartController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }
	
    //商品列表
    public function index(Request $request)
	{
        //购物车列表
        $postdata = array(
            'access_token' => $_SESSION['weixin_user_info']['access_token']
		);
        $url = env('APP_API_URL')."/cart_list";
		$res = curl_request($url,$postdata,'GET');
        $data['list'] = $res['data']['list'];
        
        //猜你喜欢商品列表
        $postdata = array(
            'limit'  => 4,
            'orderby'=> 1,
            'offset' => 0
		);
        $url = env('APP_API_URL')."/goods_list";
		$res = curl_request($url,$postdata,'GET');
        $data['like_goods_list'] = $res['data']['list'];
        
		return view('weixin.cart.index', $data);
	}
    
    //购物车结算
    public function cartCheckout($ids)
	{
        //购物车结算商品列表
        $postdata = array(
            'ids' => $ids,
            'access_token' => $_SESSION['weixin_user_info']['access_token']
		);
        $url = env('APP_API_URL')."/cart_checkout_goods_list";
		$res = curl_request($url,$postdata,'GET');
        $data['list'] = $res['data']['list'];
        
        //支付方式列表
        $postdata = array(
            'status' => 1,
            'access_token' => $_SESSION['weixin_user_info']['access_token']
		);
        $url = env('APP_API_URL')."/payment_list";
		$res = curl_request($url,$postdata,'GET');
        $data['payment_list'] = $res['data']['list'];
        
        //用户默认收货地址
        $postdata = array(
            'access_token' => $_SESSION['weixin_user_info']['access_token']
		);
        $url = env('APP_API_URL')."/user_default_address";
		$res = curl_request($url,$postdata,'GET');
        $data['user_default_address'] = $res['data'];
        
        //用户收货地址列表
        //收货地址列表
        $postdata = array(
            'limit'  => 100,
            'access_token' => $_SESSION['weixin_user_info']['access_token']
		);
        $url = env('APP_API_URL')."/user_address_list";
		$res = curl_request($url,$postdata,'GET');
        $data['address_list'] = $res['data']['list'];
        
        return view('weixin.cart.cartCheckout', $data);
    }
}