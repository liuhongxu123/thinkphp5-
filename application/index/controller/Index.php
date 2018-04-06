<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Task;
class Index extends Controller
{
    public function index()
    {
        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = Task::where('types','1')->paginate(10);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch('list');
    }
}
