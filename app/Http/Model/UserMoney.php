<?php
namespace App\Http\Model;

class UserMoney extends BaseModel
{
	//用户余额明细
	
    protected $table = 'user_money';
	public $timestamps = false;
	
	/**
     * 不能被批量赋值的属性
     *
     * @var array
     */
    protected $guarded = array();
	
    //获取列表
	public static function getList(array $param)
    {
        extract($param); //参数：limit，offset
        
        $where['user_id'] = $user_id;
        $limit  = isset($limit) ? $limit : 10;
        $offset = isset($offset) ? $offset : 0;
        
        $model = new UserMoney;
        
        if(isset($type)){$where['type'] = $type;}
        
        $model = $model->where($where);
        
        $res['count'] = $model->count();
        $res['list'] = array();
        
		if($res['count']>0)
        {
            $res['list']  = $model->skip($offset)->take($limit)->orderBy('id','desc')->get();
        }
        else
        {
            return false;
        }
        
        return $res;
    }
    
    public static function getOne($id)
    {
        return self::where('id', $id)->first();
    }
    
    public static function add(array $data)
    {
        if ($id = self::insertGetId($data))
        {
            return $id;
        }

        return false;
    }
    
    public static function modify($where, array $data)
    {
        if (self::where($where)->update($data))
        {
            return true;
        }
        
        return false;
    }
    
    //删除一条记录
    public static function remove($id)
    {
        if (!self::whereIn('id', explode(',', $id))->delete())
        {
            return false;
        }
        
        return true;
    }
    
    //描述-文字
    public function getDesAttr($data)
    {
        $arr = [0 => '不限', 1 => '黑色', 2 => '白色', 3 => '银色', 4 => '橙色', 5 => '绿色', 6 => '红色', 7 => '蓝色', 8 => '紫色', 9 => '黄色', 10 => '香槟色', 11 => '咖啡色'];
        return $arr[$data['des']];
    }
}