<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;
use App\Common\ReturnData;
use App\Common\Token;
use App\Common\Helper;
use App\Http\Model\Comment;

class CommentController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function commentList(Request $request)
	{
        //参数
        $data['limit'] = $request->input('limit', 10);
        $data['offset'] = $request->input('offset', 0);
        $data['user_id'] = Token::$uid;
        $data['comment_type'] = $request->input('comment_type', 0); //0商品评价，1文章评价
        if($request->input('comment_rank', '') != ''){$data['comment_rank'] = $request->input('comment_rank');}
        if($request->input('id_value', '') != ''){$data['id_value'] = $request->input('id_value');}
        if($request->input('parent_id', '') != ''){$data['parent_id'] = $request->input('parent_id');}
        
        $res = Comment::getList($data);
		if($res === false)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL,null,$res);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //添加一条评价
    public function commentAdd(Request $request)
	{
        //参数
        $data['comment_type'] = $request->input('comment_type',0);
        $data['id_value'] = $request->input('id_value',null);
        $data['content'] = $request->input('content',null);
        $data['comment_rank'] = $request->input('comment_rank',null);
        if($request->input('ip_address', null) !== null){$data['ip_address'] = $request->input('ip_address');}
        if($request->input('parent_id', null) !== null){$data['parent_id'] = $request->input('parent_id');}
        $data['add_time'] = time();
        $data['user_id'] = Token::$uid;
        $data['ip_address'] = Helper::getRemoteIp();
        
        if($data['comment_type']===null || $data['id_value']===null || $data['content']===null || $data['comment_rank']===null)
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        
        return Comment::add($data);
    }
    
    //评价批量添加
    public function commentBatchAdd(Request $request)
	{
        if($request->input('comment',null)===null){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        $comment = json_decode($request->input('comment'),true);
        foreach($comment as $k=>$v)
        {
            $comment[$k]['user_id'] = Token::$uid;
            $comment[$k]['ip_address'] = Helper::getRemoteIp();
            $comment[$k]['add_time'] = time();
        }
        
        return Comment::batchAdd($comment);
    }
    
    public function commentUpdate(Request $request)
	{
        //参数
        $id = $request->input('id',null);
        if($request->input('content', null) !== null){$data['content'] = $request->input('content');}
        if($request->input('comment_rank', null) !== null){$data['comment_rank'] = $request->input('comment_rank');}
        if($request->input('ip_address', null) !== null){$data['ip_address'] = $request->input('ip_address');}
        if($request->input('parent_id', null) !== null){$data['parent_id'] = $request->input('parent_id');}
        
        
        if($id===null)
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        
        if(isset($data))
        {
            $data['user_id'] = Token::$uid;
            Comment::modify(array('id'=>$id),$data);
        }
        
		return ReturnData::create(ReturnData::SUCCESS);
    }
    
    //删除评价
    public function commentDelete(Request $request)
	{
        //参数
        $data['comment_type'] = $request->input('comment_type',null);
        $data['id_value'] = $request->input('id_value',null);
        $data['user_id'] = Token::$uid;
        
        if($data['comment_type']===null || $data['id_value']===null)
		{
            return ReturnData::create(ReturnData::PARAMS_ERROR);
        }
        
        $res = Comment::remove($data);
		if($res === false)
		{
			return ReturnData::create(ReturnData::SYSTEM_FAIL,null,$res);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
}