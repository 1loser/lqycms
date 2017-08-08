<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;
use App\Common\ReturnData;
use App\Common\Token;
use App\Http\Model\User;

class UserController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    //用户信息
    public function userInfo(Request $request)
    {
        if ($user = User::getOne(Token::$uid))
		{
            return ReturnData::create(ReturnData::SUCCESS, $user);
        }
		else
		{
            return ReturnData::create(ReturnData::RECORD_NOT_EXIST);
        }
    }
    
    //修改用户信息
	public function updateUserInfo(Request $request)
	{
		$data = $data2 = '';
		if($request->input('sex', null)!=null){$data['sex'] = $request->input('sex');}
		if($request->input('head_img', null)!=null){$data['head_img'] = $request->input('head_img');}
		if($request->input('name', null)!=null){$data['name'] = $request->input('name');}
		if($request->input('nickname', null)!=null){$data['nickname'] = $request->input('nickname');}
		if($request->input('verify_mobile', null)!=null){$data['verify_mobile'] = $request->input('verify_mobile');}
		if($request->input('community_id', null)!=null){$data['community_id'] = $request->input('community_id');}
		if($request->input('address', null)!=null){$data['address'] = $request->input('address');}
		
        if ($data != '')
		{
			MallDataManager::userUpdate(['id'=>Token::$uid],$data);
        }
		
		return ReturnCode::create(ReturnCode::SUCCESS);
    }
    
    //用户列表
    public function userList(Request $request)
    {
        //参数
        $data['limit'] = $request->input('limit', 10);
        $data['offset'] = $request->input('offset', 0);
        
        $res = User::getList($data);
		if(!$res)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //签到
	public function signin(Request $request)
	{
		$res = User::signin();
        
        if($res !== true)
        {
            return ReturnData::create(ReturnData::PARAMS_ERROR,null,$res);
        }
        
		return ReturnData::create(ReturnData::SUCCESS);
    }
    
    //注册
    public function register(Request $request)
	{
        $mobile = $request->input('mobile', null);
        $password = $request->input('password', null);
		$community_id = $request->input('community_id', null);
		$address = $request->input('address', null);
		$type = $request->input('type', null);
		$verificationCode = $request->input('verificationCode', null);
        $verificationType = $request->input('verificationType', null); //7表示验证码登录
		
		$yezhu_mobile = $request->input('yezhu_mobile', null);
		
        Log::info("注册手机号==========mobile=======".$mobile);
        
        if ($mobile==null || $password==null || $verificationCode==null || $verificationType===null || $community_id===null)
		{
            return ReturnCode::create(ReturnCode::PARAMS_ERROR);
        }
		
        if (!Helper::isValidMobile($mobile))
		{
            return response(ReturnCode::create(ReturnCode::MOBILE_FORMAT_FAIL));
        }
		
		$verifyCode = VerifyCode::isVerify($mobile, $verificationCode, $verificationType);
		if(!$verifyCode)
		{
			return ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE);
		}
		
		if($yezhu_mobile!=null)
		{
			$yezhu = MallDataManager::userFirst(['mobile'=>$yezhu_mobile,'community_id'=>$community_id]);
			if (!$yezhu)
			{
				return response(ReturnCode::create(ReturnCode::PARAMS_ERROR,'业主不匹配'));
			}
		}
		
		//判断是否已经注册
		$user = MallDataManager::userFirst(['mobile'=>$mobile]);
		if ($user)
		{
			return response(ReturnCode::create(ReturnCode::MOBILE_EXIST));
		}
		
		try
		{
			DB::beginTransaction();
			//创建用户
			$userdata['mobile'] = $mobile;
			$userdata['password'] = $password;
			$userdata['verify_mobile'] = 1;
			$userdata['name'] = $mobile;
			$userdata['nickname'] = $mobile;
			$userdata['community_id'] = $community_id;
			$userdata['address'] = $address;
			$userdata['type'] = $type;
			$userid = DB::table('user')->insertGetId($userdata);
			
			//注册环信用户
			$Easemob = new Easemob();
			$Easemob->imRegister(['username'=>'cuobian'.$userid,'password'=>md5('cuobian'.$userid)]);
			
			//生成token
			if ($user = MallDataManager::userFirst(['mobile'=>$mobile,'password'=>$password]))
			{
				//获取token
				$expired_at = Carbon::now()->addDay()->toDateTimeString();
				$token = Token::generate(Token::TYPE_SHOP, $user->id);
			}
			
			DB::commit();
			$response         = ReturnCode::create(ReturnCode::SUCCESS);
			$response['data'] = [
				'id' => $user->id,
				'mobile' => $user->mobile,
				'expired_at' => $expired_at,
				'token' => $token,
			];
		}
		catch (Exception $e)
		{
			DB::rollBack();
			Log::info($e->getMessage());
			return response(ReturnCode::error($e->getCode(), $e->getMessage()));
		}
		
		return response($response);
    }
	
	//登录
    public function login(Request $request)
    {
        $mobile = $request->input('mobile');
        $password = $request->input('password');
		
        if (!$mobile || !$password)
		{
            return response(ReturnCode::create(ReturnCode::PARAMS_ERROR));
        }
        
        if ($user = MallDataManager::userFirst(['mobile'=>$mobile]))
		{
            //判断密码
            if ($password == $user->password)
			{
                //获取token
                $expired_at = Carbon::now()->addDay()->toDateTimeString();
                $token = Token::generate(Token::TYPE_SHOP, $user->id);
                
                $response = ReturnCode::success();
                $response['data']=[
                    'id' => $user->id, 'name' => $user->name, 'nickname' => $user->nickname, 'headimg' => (string)$user->head_img, 'token' => $token, 'expired_at' => $expired_at, 'mobile' => $user->mobile, 'hx_name' => 'cuobian'.$user->id, 'hx_pwd' => md5('cuobian'.$user->id)
                ];
				
                return response($response);
            }
			else
			{
                return response(ReturnCode::create(ReturnCode::PASSWORD_NOT_MATCH));
            }
        }
		else
		{
            return response(ReturnCode::create(ReturnCode::USER_NOT_EXIST));
        }
    }
    
    //验证码登录
	public function verificationCodeLogin(Request $request)
    {
        $mobile = $request->input('mobile');
		$code = $request->input('code', null);
        $type = $request->input('type', null); //7表示验证码登录
		
        if (!$mobile || !$code)
		{
            return response(ReturnCode::create(ReturnCode::PARAMS_ERROR));
        }
		
		//判断验证码
        if ($type != VerifyCode::TYPE_LOGIN)
		{
            return response(ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE));
        }
		
        $verifyCode = VerifyCode::isVerify($mobile, $code, $type);
        if (!$verifyCode)
		{
            return response(ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE));
        }
        
        if ($user = MallDataManager::userFirst(['mobile'=>$mobile]))
		{
			//获取token
			$expired_at = Carbon::now()->addDay()->toDateTimeString();
			$token = Token::generate(Token::TYPE_SHOP, $user->id);
			
			$response = ReturnCode::success();
			$response['data']=[
				'id' => $user->id, 'name' => $user->name, 'nickname' => $user->nickname, 'headimg' => (string)$user->head_img, 'token' => $token, 'expired_at' => $expired_at, 'mobile' => $user->mobile, 'hx_name' => 'cuobian'.$user->id, 'hx_pwd' => md5('cuobian'.$user->id)
			];
			
			return response($response);
        }
		else
		{
            return response(ReturnCode::create(ReturnCode::USER_NOT_EXIST));
        }
    }
    
    //修改密码
    public function changePassword(Request $request)
    {
        $mobile = $request->input('mobile', null);
        $password = $request->input('password', null); //新密码
		$oldPassword = $request->input('oldPassword', null); //旧密码
		
		if (!$mobile || !$password || !$oldPassword)
		{
            return ReturnCode::create(ReturnCode::PARAMS_ERROR);
        }
		
		if($password == $oldPassword)
		{
			return ReturnCode::create(ReturnCode::PARAMS_ERROR,'新旧密码相同');
		}
		
		if (!Helper::isValidMobile($mobile))
		{
			return ReturnCode::create(ReturnCode::MOBILE_FORMAT_FAIL);
		}
		
		$user = MallDataManager::userFirst(['mobile'=>$mobile,'password'=>$oldPassword,'id'=>Token::$uid]);
		
		if(!$user)
		{
			return ReturnCode::create(ReturnCode::PARAMS_ERROR,'手机或密码错误');
		}
		
		DB::table('user')->where(['mobile'=>$mobile,'password'=>$oldPassword,'id'=>Token::$uid])->update(['password'=>$password]);
		
		MallDataManager::tokenDelete(['uid'=>Token::$uid]);
		
		return ReturnCode::create(ReturnCode::SUCCESS);
    }
	
	//找回密码，不用输入旧密码
    public function findPassword(Request $request)
    {
        $mobile = $request->input('mobile', null);
        $password = $request->input('password', null);
		
        if ($mobile && $password)
		{
            if (!Helper::isValidMobile($mobile))
			{
                return response(ReturnCode::create(ReturnCode::MOBILE_FORMAT_FAIL));
            }
			
            //判断验证码是否有效
            $code = $request->input('code', '');
            $type = $request->input('type', null);
            if($type != VerifyCode::TYPE_CHANGE_PASSWORD)
                return response(ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE,'验证码类型错误'));
            $verifyCode = VerifyCode::isVerify($mobile, $code, $type);
			
            if($verifyCode)
            {
                try
				{
                    DB::beginTransaction();
                    $verifyCode->status = VerifyCode::STATUS_USE;
                    $verifyCode->save();
					
                    if ($user = MallDataManager::userFirst(['mobile'=>$mobile]))
					{
                        DB::table('user')->where(['mobile'=>$mobile])->update(['password'=>$password]);
                        
						MallDataManager::tokenDelete(['uid'=>$user->id]);
						
						$response = response(ReturnCode::create(ReturnCode::SUCCESS));
                    }
					else
					{
                        $response = response(ReturnCode::create(ReturnCode::PARAMS_ERROR));
                    }
					
					DB::commit();
					
                    return $response;
                }
				catch (Exception $e)
				{
                    DB::rollBack();
                    return response(ReturnCode::error($e->getCode(), $e->getMessage()));
                }
            }
            else
            {
                return response(ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE));
            }
        }
		else
		{
            return response(ReturnCode::create(ReturnCode::PARAMS_ERROR));
        }
    }
	
    //用户意见反馈
    public function feedback(Request $request)
    {
        $content = $request->input('content', null);
        if(!$content)
            return response(ReturnCode::create(ReturnCode::PARAMS_ERROR));
        if ($user = MallDataManager::userFirst(['id'=>Token::$uid]))
		{
			$id = MallDataManager::mallFeedbackinsertGetId(['content' => $content, 'user_id' => Token::$uid]);
			
            return response(ReturnCode::create(ReturnCode::SUCCESS,$id));
        }
		else
		{
            return response(ReturnCode::create(ReturnCode::AUTHORIZE_FAIL));
        }
    }
	
	//关于
    public function about(Request $request)
    {
        return response(ReturnCode::create(ReturnCode::SUCCESS,['url'=>'http://www.baidu.com']));
    }
	
	//修改手机号
    public function changeMobile(Request $request)
    {
        $mobile = $request->input('mobile', null); //新手机号码
        $verificationCode = $request->input('verificationCode', null); //新手机验证码
		$oldMobile = $request->input('oldMobile', null); //旧手机号码
		$oldVerificationCode = $request->input('oldVerificationCode', null); //旧手机验证码
		$type = $request->input('type', null); //验证码类型
		
		if (!$mobile || !$verificationCode || !$oldMobile || !$oldVerificationCode || !$type)
		{
            return ReturnCode::create(ReturnCode::PARAMS_ERROR);
        }
		
		if (!Helper::isValidMobile($mobile))
		{
			return ReturnCode::create(ReturnCode::MOBILE_FORMAT_FAIL);
		}
		
		if($mobile == $oldMobile)
		{
			return ReturnCode::create(ReturnCode::PARAMS_ERROR,'新旧手机号码相同');
		}
		
		if($type != VerifyCode::TYPE_CHANGE_MOBILE)
		{
			return ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE,'验证码类型错误');
        }
		
		$verifyCode = VerifyCode::isVerify($oldMobile, $oldVerificationCode, $type);
		if(!$verifyCode)
		{
			return ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE);
		}
		
		$verifyCode = null;
		$verifyCode = VerifyCode::isVerify($mobile, $verificationCode, $type);
		if(!$verifyCode)
		{
			return ReturnCode::create(ReturnCode::INVALID_VERIFY_CODE);
		}
		
		$user = MallDataManager::userFirst(['mobile'=>$oldMobile,'id'=>Token::$uid]);
		
		if(!$user)
		{
			return ReturnCode::create(ReturnCode::PARAMS_ERROR,'旧手机号码错误');
		}
		
		DB::table('user')->where(['mobile'=>$oldMobile,'id'=>Token::$uid])->update(['mobile'=>$mobile]);
		
		MallDataManager::tokenDelete(['uid'=>Token::$uid]);
		
		return ReturnCode::create(ReturnCode::SUCCESS);
    }
    
    //添加收货地址
    public function userAddressAdd(Request $request)
	{
        //参数
        $data['name'] = $request->input('name',null);
        $data['mobile'] = $request->input('mobile',null);
        $data['country'] = $request->input('country',null);
        $data['province'] = $request->input('province',null);
        $data['city'] = $request->input('city',null);
        $data['district'] = $request->input('district',null);
        $data['address'] = $request->input('address',null);
        if($request->input('telphone',null)!==null){$data['telphone'] = $request->input('telphone');}
        if($request->input('zipcode',null)!==null){$data['zipcode'] = $request->input('zipcode');}
        if($request->input('email',null)!==null){$data['email'] = $request->input('email');}
        if($request->input('best_time',null)!==null){$data['best_time'] = $request->input('best_time');}
        if($request->input('is_default',null)!==null){$data['is_default'] = $request->input('is_default');}
        
        if($data['name']===null || $data['mobile']===null || $data['address']===null || $data['country']===null || $data['province']===null || $data['city']===null || $data['district']===null)
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        
        $res = UserAddress::add($data);
		if(!$res)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //修改收货地址
    public function userAddressUpdate(Request $request)
	{
        //参数
        $data['id'] = $request->input('id',null);
        $data['name'] = $request->input('name',null);
        $data['mobile'] = $request->input('mobile',null);
        $data['country'] = $request->input('country',null);
        $data['province'] = $request->input('province',null);
        $data['city'] = $request->input('city',null);
        $data['district'] = $request->input('district',null);
        $data['address'] = $request->input('address',null);
        if($request->input('telphone',null)!==null){$data['telphone'] = $request->input('telphone');}
        if($request->input('zipcode',null)!==null){$data['zipcode'] = $request->input('zipcode');}
        if($request->input('email',null)!==null){$data['email'] = $request->input('email');}
        if($request->input('best_time',null)!==null){$data['best_time'] = $request->input('best_time');}
        if($request->input('is_default',null)!==null){$data['is_default'] = $request->input('is_default');}
        
        if($data['id']===null || $data['name']===null || $data['mobile']===null || $data['address']===null || $data['country']===null || $data['province']===null || $data['city']===null || $data['district']===null)
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        
        $res = UserAddress::modify($data);
		if(!$res)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //删除收货地址
    public function userAddressDelete(Request $request)
	{
        //参数
        $id = $request->input('id',null);
        
        $res = UserAddress::remove($id);
		if(!$res)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
}