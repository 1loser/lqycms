<?php
namespace App\Http\Logic;
use App\Common\ReturnData;
use App\Http\Model\Article;
use App\Http\Requests\ArticleRequest;
use Validator;

class ArticleLogic extends BaseLogic
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getModel()
    {
        return new Article();
    }
    
    public function getValidate($data, $scene_name)
    {
        //数据验证
        $validate = new ArticleRequest();
        return Validator::make($data, $validate->getSceneRules($scene_name), $validate->getSceneRulesMessages());
    }
    
    //列表
    public function getList($where = array(), $order = '', $field = '*', $offset = '', $limit = '')
    {
        $res = Article::getList($where, $order, $field, $offset, $limit);
        
        if($res['list'])
        {
            foreach($res['list'] as $k=>$v)
            {
                $res['list'][$k] = $this->getDataView($v);
                $res['list'][$k]->typename = Article::getTypenameAttr(array('typeid' => $v->typeid));
            }
        }
        else
        {
            return false;
        }
        
        return $res;
    }
    
    //分页html
    public function getPaginate($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = Article::getPaginate($where, $order, $field, $limit);
        foreach($res as $k=>$v)
        {
			$res[$k]->typename = Article::getTypenameAttr(array('typeid'=>$v->typeid));
        }
        
        return $res;
    }
    
    //全部列表
    public function getAll($where = array(), $order = '', $field = '*', $limit = '')
    {
        $res = Article::getAll($where, $order, $field, $limit);
        
        /* if($res)
        {
            foreach($res as $k=>$v)
            {
                $res[$k] = $this->getDataView($v);
            }
        } */
        
        return $res;
    }
    
    //详情
    public function getOne($where = array(), $field = '*')
    {
        $res = Article::getOne($where, $field);
        if(!$res){return false;}
        
        $res = $this->getDataView($res);
        $res->typename = Article::getTypenameAttr(array('typeid'=>$res->typeid));
        
        Article::getDb()->where($where)->increment('click', 1);
        
        return $res;
    }
    
    //添加
    public function add($data = array(), $type=0)
    {
        if(empty($data)){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        
        $validator = $this->getValidate($data, 'add');
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
        
        $res = Article::add($data,$type);
        if($res === false){return ReturnData::create(ReturnData::SYSTEM_FAIL);}
        
        return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //修改
    public function edit($data, $where = array())
    {
        if(empty($data)){return ReturnData::create(ReturnData::SUCCESS);}
        
        $validator = $this->getValidate($data, 'edit');
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
        
        $res = Article::edit($data,$where);
        if($res === false){return ReturnData::create(ReturnData::SYSTEM_FAIL);}
        
        return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //删除
    public function del($where)
    {
        if(empty($where)){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        
        $validator = $this->getValidate($where,'del');
        if ($validator->fails()){return ReturnData::create(ReturnData::PARAMS_ERROR, null, $validator->errors()->first());}
        
        $res = Article::del($where);
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