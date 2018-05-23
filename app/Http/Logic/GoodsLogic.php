<?php
namespace App\Http\Logic;
use App\Common\ReturnData;
use App\Http\Model\Goods;
use App\Http\Requests\GoodsRequest;
use Validator;
use App\Http\Model\Comment;

class GoodsLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getModel()
    {
        return model('Goods');
    }
    
    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new GoodsRequest();
        return Validator::make($data, $validate->getSceneRules($scene_name), $validate->getSceneRulesMessages());
    }
    
    //列表
    public function getList($where = array(), $order = '', $field = '*', $offset = '', $limit = '')
    {
        $res = $this->getModel()->getList($where, $order, $field, $offset, $limit);
        
        if($res['count'] > 0)
        {
            foreach($res['list'] as $k=>$v)
            {
                $res['list'][$k] = $this->getDataView($v);
                
                $res['list'][$k]->price = $this->getModel()->get_goods_final_price($v);
                $res['list'][$k]->is_promote_goods = $this->getModel()->bargain_price($v->promote_price,$v->promote_start_date,$v->promote_end_date); //is_promote_goods等于0，说明不是促销商品
            }
        }
        
        return $res;
    }
    
    //分页html
    public function getPaginate($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = $this->getModel()->getPaginate($where, $order, $field, $limit);
        
        return $res;
    }
    
    //全部列表
    public function getAll($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = $this->getModel()->getAll($where, $order, $field, $limit);
        
        if($res['count'] > 0)
        {
            foreach($res['list'] as $k=>$v)
            {
                $res['list'][$k] = $this->getDataView($v);
                
                $res['list'][$k]->price = $this->getModel()->get_goods_final_price($v); //商品最终价格
                $res['list'][$k]->is_promote_goods = $this->getModel()->bargain_price($v->promote_price,$v->promote_start_date,$v->promote_end_date); //is_promote_goods等于0，说明不是促销商品
            }
        }
        
        return $res;
    }
    
    //详情
    public function getOne($where = array(), $field = '*')
    {
        $res = $this->getModel()->getOne($where, $field);
        if(!$res){return false;}
        
        $res = $this->getDataView($res);
        $res->price = $this->getModel()->get_goods_final_price($res); //商品最终价格
        $res->is_promote_goods = $this->getModel()->bargain_price($res->promote_price,$res->promote_start_date,$res->promote_end_date); //is_promote_goods等于0，说明不是促销商品
		
        //商品评论数
        $where2['comment_type'] = Comment::GOODS_COMMENT_TYPE;
        $where2['status'] = Comment::SHOW_COMMENT;
        $where2['id_value'] = $res->id;
        $res->goods_comments_num = model('Comment')->getCount($where2);
        
        $this->getModel()->getDb()->where($where)->increment('click', 1); //点击量+1
        
        return $res;
    }
    
    //添加
    public function add($data = array(), $type=0)
    {
        if(empty($data)){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        
        $validator = $this->getValidate($data, 'add');
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
        
        $res = $this->getModel()->add($data,$type);
        if($res === false){return ReturnData::create(ReturnData::SYSTEM_FAIL);}
        
        return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //修改
    public function edit($data, $where = array())
    {
        if(empty($data)){return ReturnData::create(ReturnData::SUCCESS);}
        
        $validator = $this->getValidate($data, 'edit');
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
        
        $res = $this->getModel()->edit($data,$where);
        if($res === false){return ReturnData::create(ReturnData::SYSTEM_FAIL);}
        
        return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //删除
    public function del($where)
    {
        if(empty($where)){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        
        $validator = $this->getValidate($where,'del');
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
        
        $res = $this->getModel()->del($where);
        if($res === false){return ReturnData::create(ReturnData::SYSTEM_FAIL);}
        
        return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    /**
     * 数据获取器
     * @param array $data 要转化的数据
     * @return array
     */
    private function getDataView($data = array())
    {
        return getDataAttr($this->getModel(),$data);
    }
}