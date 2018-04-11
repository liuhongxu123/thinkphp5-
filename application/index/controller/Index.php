<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\IndexModel;
class Index extends Controller
{
    public function index(){
        $list = IndexModel::where('appCode','feizhu')->paginate(10);
        // 获取分页显示
        $room = json_decode(json_encode($list,JSON_UNESCAPED_UNICODE),true);
        $row = array();
        foreach($room['data'] as $val){
            $data = json_decode($val['data'],1);
            $row = $data['flatOptions'];
        }
        $page = $list->render();
        // 模板变量赋值
        $this->assign('row', $row);
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch('Index/index');
    }

    public function edit(){
        $id = input();
        $list = IndexModel::where('id',$id['id'])->find();
        $room = json_decode(json_encode($list,JSON_UNESCAPED_UNICODE),true);
        $data = json_decode($room['data'],1);
        $fla = $data['flatOptions'];
        $ctrip_room = json_decode($room['ctrip_room'],1);
        $merge = array();
        foreach($fla as $key=>$val){
            if(!empty($ctrip_room)){
                foreach($ctrip_room as $keys=>$value){
                    if($key == $keys){
                        $val['ctrip_room'] = $value;
                        $merge[$key] = $val;
                    }
                }
            }else{
                $val['ctrip_room'] = '';
                $merge[$key] = $val;
            }
        }
        $this->assign('data', $merge);
        $this->assign('list', $list);
        return $this->fetch('Index/edit');
    }

    public function update(){
        $data = input();
        $fliggy = json_encode($data['fliggy_room'],JSON_FORCE_OBJECT);
        $ctrip = json_encode($data['ctrip_room'],JSON_FORCE_OBJECT);
        $task = IndexModel::where('id',$data['id'])->update(['ctrip'=>$data['ctrip'],'ctrip_url'=>$data['ctrip_url'],'fliggy_room'=>$fliggy,'ctrip_room'=>$ctrip]);
        if($task){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('更新成功！', 'Index/index','',1);
        } else {
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('更新失败！');
        }

    }

}