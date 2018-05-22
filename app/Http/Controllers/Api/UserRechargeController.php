<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;
use App\Common\ReturnData;
use App\Common\Token;
use App\Http\Model\UserRecharge;

class UserRechargeController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    //用户充值列表
    public function userRechargeList(Request $request)
	{
        //参数
        $data['limit']  = $request->input('limit', 10);
        $data['offset'] = $request->input('offset', 0);
        $data['status'] = $request->input('status', -1);
        
        $data['user_id'] = Token::$uid;
        
        $res = UserRecharge::getList($data);
		if($res === false)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //获取一条用户充值
    public function userRechargeDetail(Request $request)
	{
        //参数
        $data['id']  = $request->input('id', '');
        if($data['id']=='')
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        $data['user_id'] = Token::$uid;
        
        $res = UserRecharge::getOne($data);
		if($res === false)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //添加充值记录
    public function userRechargeAdd(Request $request)
	{
        //参数
        $data['money'] = $request->input('money','');
        $data['status'] = UserRecharge::UN_PAY; //0未处理，1已完成
        $data['pay_type'] = $request->input('pay_type',''); //充值类型：1微信，2支付宝
        $data['user_id'] = Token::$uid;
        $data['created_at'] = time();
        
        if($data['money']=='' || $data['pay_type']=='')
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        
        $res = UserRecharge::add($data);
		if($res === false)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //修改充值记录
    public function userRechargeUpdate(Request $request)
	{
        //参数
        $id = $request->input('id','');
        $data['trade_no'] = $request->input('trade_no','');
        $data['pay_time'] = $request->input('pay_time','');
        $data['status'] = UserRecharge::COMPLETE_PAY;
        $data['updated_at'] = time();
        
        if($id=='' || $data['trade_no']=='' || $data['pay_time']=='')
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        
        $res = UserRecharge::modify(array('id'=>$id,'user_id'=>Token::$uid),$data);
		if($res === false)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS);
    }
}