<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CommonController;
use App\Http\Model\Order;
use DB;

class OrderController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }
	
    public function index()
    {
        $res = '';
		$where = function ($query) use ($res) {
			if(isset($_REQUEST["keyword"]))
			{
				$query->where('order_sn', 'like', '%'.$_REQUEST['keyword'].'%')->orWhere("name", "like", '%'.$_REQUEST['keyword'].'%');
			}
			
            //0或者不传表示全部，1待付款，2待发货,3待收货,4待评价(确认收货，交易成功),5退款/售后
			if(isset($_REQUEST["status"]))
			{
                if($_REQUEST["status"] == 1)
                {
                    $query->where(array('order_status'=>0,'pay_status'=>0));
                }
                elseif($_REQUEST["status"] == 2)
                {
                    $query->where(array('order_status'=>0,'shipping_status'=>0,'pay_status'=>1));
                }
                elseif($_REQUEST["status"] == 3)
                {
                    $query->where(array('order_status'=>0,'refund_status'=>0,'shipping_status'=>1,'pay_status'=>1));
                }
                elseif($_REQUEST["status"] == 4)
                {
                    $query->where(array('order_status'=>3,'refund_status'=>0,'shipping_status'=>2,'is_comment'=>0));
                }
                elseif($_REQUEST["status"] == 5)
                {
                    $query->where(array('order_status'=>3,'refund_status'=>1));
                }
			}
			
			$query->where('is_delete', 0); //未删除
        };
		
        $posts = parent::pageList('order', $where);
		foreach($posts as $key=>$value)
        {
            $order_status_arr = Order::getOrderStatusText(object_to_array($value, 1));
            $posts[$key]->order_status_text = $order_status_arr?$order_status_arr['text']:'';
            $posts[$key]->order_status_num = $order_status_arr?$order_status_arr['num']:'';
        }
		
        $data['posts'] = $posts;
        
        return view('admin.order.index', $data);
    }
    
    public function doadd()
    {
        $_POST['add_time'] = time();//更新时间
        $_POST['click'] = rand(200,500);//点击
        
		unset($_POST["_token"]);
        if(isset($_POST['editorValue'])){unset($_POST['editorValue']);}
		
        if(Order::insert($_POST))
        {
            success_jump('添加成功！', route('admin_order'));
        }
		else
		{
			error_jump('添加失败！请修改后重新添加');
		}
    }
    
    public function add()
    {
        return view('admin.order.add');
    }
    
    public function edit()
    {
        if(!empty($_GET["id"])){$id = $_GET["id"];}else{$id="";}
        if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $data['id'] = $id;
		$data['post'] = Order::where('id', $id)->first();
		
        return view('admin.order.edit', $data);
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];unset($_POST["id"]);}else {$id="";exit;}
        
		unset($_POST["_token"]);
        if(isset($_POST['editorValue'])){unset($_POST['editorValue']);}
		
        if(Order::where('id', $id)->update($_POST))
        {
            success_jump('修改成功！', route('admin_order'));
        }
		else
		{
			error_jump('修改失败！请修改后重新添加');
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{error_jump("删除失败！请重新提交");} //if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(Order::whereIn("id", explode(',', $id))->update(array('is_delete'=>1)))
        {
            success_jump('删除成功');
        }
		else
		{
			error_jump("删除失败！请重新提交");
		}
    }
}