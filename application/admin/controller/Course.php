<?php
namespace app\admin\controller;
use app\admin\model\Comment;
use app\admin\model\CourseCategory;
use app\admin\model\CourseIntroduce;
use app\admin\model\VideoCategory;
use app\admin\model\Course as Cr;

class Course extends Base
{

    public function _empty()
    {
        return $this->index();
    }

    /**
     * index 课程列表
     */
    public function index()
    {
        $key = $so['title'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $pid = $so['pid'] = input('pid');
        $audit = $so['audit'] = input('audit');
        $insur_switch = $so['insur_switch'] = input('insur_switch');
        $map = [];
        $map['closed'] = 0;
        if ($key && $key !== "") {
            $map['title'] = ['like',"%" . $key . "%"];
        }
        if ($start_date) {
            $map['create_time'] = [ '>',strtotime($start_date)];
        }
        if ($pid) {
            $map['pid'] = $so['pid'] = $pid;
        }
        if ($audit == '0') {
            $map['audit'] = 0;
        } elseif ($audit == '1') {
            $map['audit'] = 1;
        }
        if ($insur_switch == '0') {
            $map['insur_switch'] = 0;
        } elseif ($insur_switch == '1') {
            $map['insur_switch'] = 1;
        }
        if ($end_date) {
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $map['step'] = 4; // 课程添加完整
        $Nowpage = input('get.p') ? input('get.p') : 1;
        $cate = new CourseCategory();
        $catelist = $cate->where(array('closed'=>'0'))->field('id,cate_name,pid')->order('orderby desc')->select();
        $ret_cate = [];
        foreach ($catelist as $cateinfo) {
            if($cateinfo['pid'] == 0){
                $ret_cate[$cateinfo['id']] = $cateinfo;
            }
        }
        $course = new Cr();
        $limits = 10; // 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $course->where($map)->count(); // 计算总页面
        $allpage = ceil($count / $limits);
        $lists = $course->where($map)->order('orderby desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['edit_url'] = \think\Url::build('course/edit_course','cid='.$v['cid']);
            $lists[$k]['type_url'] = \think\Url::build('course/video_type','cid='.$v['cid']);
            $lists[$k]['intro_url'] = \think\Url::build('course/introduce','cid='.$v['cid']);
            $lists[$k]['comment_url'] = \think\Url::build('course/comment','cid='.$v['cid']);
            if(isset($ret_cate[$v['pid']])){
                $lists[$k]['cate_name'] = $ret_cate[$v['pid']]['cate_name'];
            }else{
                $lists[$k]['cate_name'] = "";
            }
            /*if($cateInfo = $cate->where('id',$v['pid'])->value('cate_name')){
                $lists[$k]['cate_name'] = $cateInfo;
            } else {
                $lists[$k]['cate_name'] = "";
            }*/
        }
        $this->assign('lists', $lists);
        $this->assign('Nowpage', $Nowpage); // 当前页
        $this->assign('allpage', $allpage); // 总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        $this->assign('catelist', $catelist);
        if (request()->isAjax()) {
            $msg['status'] = 200;
            $msg['data']['list'] = $lists;
            $msg['data']['title'] = "课程列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        // 面包屑导航 固定位置
        $this->assign('course_bread', 1);
        return $this->fetch();
    }

    // 课程分类 start
    /**
     * 分类列表
     */
    public function index_cate()
    {
        $key = $so['cate_name'] = input('key');
        $map = [];
        $map['closed'] = 0;
        $map['pid'] = 0; // 顶级分类
        if ($key && $key !== "") {
            $map['cate_name'] = ['like',"%" . $key . "%"];
        }
        $Nowpage = input('get.p') ? input('get.p') : 1;
        $cate = new CourseCategory();
        $limits = 10; // 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $cate->where($map)->count(); // 计算总页面
        $allpage = ceil($count / $limits);
        $lists = $cate->where($map) ->order('orderby desc')->limit($start, $limits)->select();
        foreach ($lists as $key => $temp) {
            $select_pid = $temp['id'];
            $child_list = $cate->where(array('closed' => '0','pid' => $select_pid))->field('id,cate_name,pid,orderby')->order('orderby desc')->select();
            $lists[$key]['child_list'] = $child_list;
        }
        // 查询子分类
        $this->assign('Nowpage', $Nowpage); // 当前页
        $this->assign('allpage', $allpage); // 总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        if (request()->isAjax()) {
            $msg['status'] = 200;
            $msg['data']['list'] = $lists;
            $msg['data']['title'] = "课程分类";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $topcate_list_first = array(0 => array('id' => 0,'cate_name' => '默认顶级'));
        $topcate_list_data = $cate->where(array('closed' => '0','pid' => '0'))->field('id,cate_name')->order('orderby desc')->select();
        $topcate_list = array_merge_recursive($topcate_list_first, $topcate_list_data);
        $this->assign('topcate_list', $topcate_list); // 课程顶级分类列表
        $this->assign('course_bread', 2);// 面包屑导航 固定位置
        return $this->fetch();
    }

    /**
     * 添加分类
     */
    public function add_cate()
    {
        $param = input('post.');
        $cate_name = $param['cate_name'];
        if (mb_strlen($cate_name) > 20) {
            return json([
                'status' => '-200',
                'url' => '/admin/course/index_cate',
                'data' => '',
                'msg' => '课程分类字数不超过20个字'
            ]);
        }
        $cate = new CourseCategory();
        $lists_orderby = $cate->where(['closed' => 0])->order('orderby desc')->value('orderby');
        if ($lists_orderby) {
            $next_order_by = $lists_orderby + 1;
        } else {
            $next_order_by = 1;
        }
        $param['orderby'] = $next_order_by;
        $flag = $cate->InsertData($param);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    /**
     * 编辑分类
     */
    public function edit_cate()
    {
        $cate = new CourseCategory();
        $param = input('post.');
        $cate_name = $param['cate_name'];
        if (mb_strlen($cate_name) > 20) {
            return json([
                'status' => '-200',
                'url' => '/admin/course/index_cate',
                'data' => '',
                'msg' => '课程分类字数不超过20个字'
            ]);
        }
        $where['id'] = $param['id'];
        $flag = $cate->UpdateData($param, $where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    /**
     *   删除分类
     */
    public function del_cate()
    {
        $id = input('param.id');
        $cate = new CourseCategory();
        $where['id'] = $id;
        $course = new Cr();
        $course_where['pid'] = $id;
        $course_where['closed'] = 0;
        $is_course_set = $course->where($course_where)->find();
        if ($is_course_set) {
            return json([
                'status' => - 200,
                'url' => 'reload',
                'data' => '',
                'msg' => '分类下存在课程不能删除'
            ]);
        }
        $flag = $cate->DeleteData($where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }

    /**
     * 批量删除分类
     */
    public function batch_cate()
    {
        $ids = input('param.checkbox');
        $cate = new CourseCategory();
        $course = new Cr();
        $where['id'] = ['in',$ids];
        $course_where['pid'] = ['in',$ids];
        $course_where['closed'] = 0;
        $is_course_set = $course->where($course_where)->find();
        if ($is_course_set) {
            return json([
                'status' => - 200,
                'url' => 'reload',
                'data' => '',
                'msg' => '分类下存在课程不能删除'
            ]);
        }
        if ($cate->DeleteData($where)) {
            return json([
                'status' => 200,
                'url' => 'reload',
                'data' => '',
                'msg' => '批量删除成功'
            ]);
        } else {
            return json([
                'status' => - 1,
                'url' => 'reload',
                'data' => '',
                'msg' => '批量删除失败'
            ]);
        }
    }
    // 课程分类排序
    public function moveCateUpDown()
    {
        $id = input('id');
        $updown = input('updown');
        $pid = input('pid');
        $map['closed'] = 0;
        $map['pid'] = $pid;
        $course = new CourseCategory();
        $data_this = $course->where('id', $id)->find();
        $orderby = $data_this['orderby'];
        if ($updown == "up") {
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        } else {
            $map['orderby'] = [ '<',$orderby];
            $order = 'orderby desc';
        }
        $data_next = $course->where($map)
        ->order($order)
        ->find();
        if ($data_next) {
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            $course->UpdateData(['orderby' => $next_order], ['id' => $id]);
            $course->UpdateData(['orderby' => $orderby], ['id' => $next_id]);
        }
        return json([
            'status' => 200,
            'url' => 'reload',
            'data' => '',
            'msg' => '排序成功'
        ]);
    }
    // 课程分类 end
    
    //课程 start
    /**
     * 添加课程
     */
    public function create()
    {
        $last_cid = 0;
        $get_param = input();
        if (isset($get_param['cid'])) // 说明是创建了的
        {
            $last_cid = $get_param['cid'];
        } else // 创建
        {
            $course = new Cr();// 获取未设置完善的课程
            $step_list = db('course')->where('step', '<>', '4')->field('cid,step,closed')->order('cid desc')->find();
            if ($step_list) {
                $last_cid = $step_list['cid']; // 上次未修改完的课程id
            }
        }
        if (isset($get_param['step'])) { // 步骤
            $step = isset($get_param['step']) ? (int) $get_param['step'] : 0;
        } else {
            $step = 1;
        }
        // 课程基本资料
        if ($step == 1) {
            $user = new User();
            $cate = new CourseCategory(); // 文章分类模型
            $cate_list = $cate->where(array('closed' => '0'))->field('id,pid,cate_name')->order('orderby desc')->select();
            $cates = $this->tree($cate_list);
            $cate_pid = array_unique(array_column($cates, 'pid'));
            foreach ($cates as &$v) {
                if (in_array($v['id'], $cate_pid)) {
                    $v['disabled'] = 'disabled';
                } else {
                    $v['disabled'] = '';
                }
            }
            $last_cate = $cate->order([
                'id' => 'desc'
            ])->find();
            $this->assign('last_cate', $last_cate);
            $this->assign('cate', $cates); // 课程分类列表
            
            $topcate_list_first = array(
                0 => array('id' => 0,'cate_name' => '默认顶级')
            );
            $topcate_list_data = $cate->where(array('closed' => '0','pid' => '0'))
            ->field('id,cate_name')
            ->order('orderby desc')
            ->select();
            $topcate_list = array_merge_recursive($topcate_list_first, $topcate_list_data);
            $this->assign('topcate_list', $topcate_list); // 课程顶级分类列表
        }
        if (isset($last_cid)) {
            // 基础资料
            if ($step == 1) {
                // 查询上次提交资料
                $course = db('course')->where(['cid' => $last_cid])->find();
                $this->assign('course', $course);
                $step_name = '添加课程';
            }
            // 章节 (视频上传)
            if ($step == 2) {
                $this->redirect('chapter/index', ['cid' => $last_cid]);
            }
            // pc介绍
            if ($step == 3) {
                $pc_url = '/admin/course/introduce?cid=' . $last_cid;
                $this->redirect($pc_url);
            } elseif ($step == 4) // 完成
            {
                $step_name = '完成';
                $url = host() . '/wechat/course/detail?&cid=' . $last_cid;
                $qrcode = 'http://tool.oschina.net/action/qrcode/generate?data=' . urlencode($url) . '&output=image%2Fjpeg&error=L&type=0&margin=8';
                $this->assign('url', $url);
                $this->assign('qrcode', $qrcode);
            }
        }
        $this->assign('step', $step); // 下一步步骤
        $this->assign('step_name', $step_name); // 下一步步骤名称
        $this->assign('cid', $last_cid);
        return $this->fetch();
    }
    /**
     * 添加课程  提交课程步骤
     */
    public function step_change()
    {
        if (request()->isPost()) {
            $course = new Cr();
            $param = input('post.');
            $step = $param['step'] <= 4 ? $param['step'] : 1;
            $cid = $param['cid'];
            if ($step == 1) // 提交基本信息
            {
                unset($param['file']);
                $param['uid'] = ''; // 无老师
                $pid = $param['pid'];
                $topid = db('course_category')->where(['closed' => 0,'id' => $pid])->value('pid');
                if ($topid) {
                    $param['child_id'] = $pid;
                    $param['pid'] = $topid;
                } else {
                    $param['child_id'] = 0;
                    $param['pid'] = $pid;
                }
                $title = $param['title'];
                if (mb_strlen($title) > 30) {
                    return json([
                        'status' => '-200',
                        'url' => '/admin/course/create',
                        'data' => '',
                        'msg' => '课程标题字数不超过30个字'
                    ]);
                }
                $img = host() . $param['banner'];
                $param['main_color'] = $this->get_main_color($img);
                if ($cid == 0) { // cid不存在且step为1 说明是新建课程
                    // 排序最前
                    $course_order = $course->field('orderby')->order('orderby desc')->find();
                    $orderby = $course_order['orderby'] + 1;
                    $param['orderby'] = $orderby;
                    $param['audit'] = 0;
                    $course->InsertData($param);
                    $cid = $course->getLastInsID();
                    $this->showcode($cid);// todo 生成小程序二维码
                } else {
                    unset($param['step']); // 不用修改步骤
                    $param['audit'] = 0;
                    $param['wxmincode'] = $this->showcode($cid, 0);// todo 生成小程序二维码
                    update('Course', ['cid' => $cid], $param);
                }
                $log_data['cid'] = $cid;
                $log_data['price']=$param['price'];
                $this->log($log_data);
                return json([
                    'status' => '200',
                    'url' => '/admin/chapter?cid=' . $cid,
                    'data' => '',
                    'msg' => '基本信息添加成功'
                ]);
            } elseif ($step == 2) // 提交视频
            {
                // 查询 是否添加了章节
                $chapter = db('video_category')->where('cid', '=', $cid)->order('orderby asc')->find();
                if ($chapter) {
                    $pc_url = '/admin/course/introduce?cid=' . $cid;
                    $this->redirect($pc_url);
                } else {
                    $nextstep = 2;
                    $nextstepname = '添加视频';
                    $msg = '必须添加视频章节才能下一步';
                    $url = host() . '/admin/chapter?cid=' . $cid;
                    $this->error($msg, $url);
                }
            } elseif ($step == 3) // 提交pc资料
            {
                $nextstep = 4;
                $nextstepname = '完成';
                $where['cid'] = $cid;
                $param['step'] = 4;
                $param['audit'] = 1;
                $flag = $course->UpdateData($param, $where);
                // 跳转下一步
                return json([
                    'status' => '200',
                    'url' => '/admin/course/create?step=4&cid=' . $cid,
                    'data' => '',
                    'msg' => '添加视频成功'
                ]);
            } elseif ($step == 4) {
                $where['cid'] = $cid;
                $param['step'] = 4;
                $param['audit'] = 1;
                $flag = $course->UpdateData($param, $where); // 更新步骤
                return json([
                    'status' => '200',
                    'url' => '/admin/course/index',
                    'data' => '',
                    'msg' => '添加完成'
                ]);
            }
        }
    }
    /**
     *  编辑课程
     */
    public function edit_course()
    {
        $course = new Cr();
        if (request()->isPost()) {
            $param = input();
            $title = $param['title'];
            if (mb_strlen($title) > 30) {
                return json([
                    'status' => '-200',
                    'url' => '/admin/course/edit_course',
                    'data' => '',
                    'msg' => '课程 标题字数不超过30个字'
                ]);
            }
            $where['cid'] = $param['cid'];
            $pid = $param['pid'];
            $topid = db('course_category')->where(['closed' => 0,'id' => $pid])->value('pid');
            if ($topid) {
                $param['child_id'] = $pid;
                $param['pid'] = $topid;
            } else {
                $param['child_id'] = 0;
                $param['pid'] = $pid;
            }
            $img = host() . $param['banner'];
            $param['main_color'] = $this->get_main_color($img);
            $flag = $course->UpdateData($param, $where);
            $log_data['cid']  = $param['cid'];
            $log_data['price']  = $param['price'];
            $this->showcode($param['cid']);// todo 生成小程序二维码
            $this->log($log_data);
            return json([
                'status' => $flag['status'],
                'url' => '/admin/course/index',
                'data' => '',
                'msg' => $flag['msg']
            ]);
        }
        
        $id = input('param.cid');
        $cate = new CourseCategory(); // 文章分类模型
        $cate_list = $cate->where(array('closed' => '0'))
            ->field('id,pid,cate_name')
            ->order('orderby desc')
            ->select();
        $cates = $this->tree($cate_list);
        $cate_pid = array_unique(array_column($cates, 'pid'));
        foreach ($cates as &$v) {
            if (in_array($v['id'], $cate_pid)) {
                $v['disabled'] = 'disabled';
            } else {
                $v['disabled'] = '';
            }
        }
        $this->assign('cate', $cates); // 课程分类列表
        $this->assign('course', $course->get($id));
        return $this->fetch();
    }

    /**
     * 删除课程
     */
    public function del_course()
    {
        $id = input('param.id');
        $course = new Cr();
        $where['cid'] = $id;
        $flag = $course->DeleteData($where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    /**
     * 批量删除
     */
    public function delall_course()
    {
        $ids = input('param.checkbox');
        $course = new Cr();
        $where['cid'] = ['in', $ids];
        $flag = $course->DeleteData($where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    /**
     * 课程排序
     */
    public function moveUpDown()
    {
        $id = input('id');
        $updown = input('updown');
        $map['closed'] = 0;
        $course = new Cr();
        $data_this = $course->where('cid', $id)->find();
        $orderby = $data_this['orderby'];
        if ($updown == "up") {
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        } else {
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
        }
        $data_next = $course->where($map)
        ->order($order)
        ->find();
        if ($data_next) {
            $next_id = $data_next['cid'];
            $next_order = $data_next['orderby'];
            $course->UpdateData(['orderby' => $next_order], ['cid' => $id]);
            $course->UpdateData(['orderby' => $orderby], ['cid' => $next_id]);
        }
        return json([
            'status' => 200,
            'url' => 'reload',
            'data' => '',
            'msg' => '排序成功'
        ]);
    }
    /**
     * [course_state 课程状态]
     */
    public function course_state()
    {
        $id = input('param.id');
        $course = new Cr();
        $status = $course->where(array('cid' => $id))->value('audit'); // 判断当前状态情况
        if ($status == 0) {
            $flag = $course->where(array('cid' => $id))->setField(['audit' => 1]);
            return json([
                'status' => $flag['status'],
                'data' => '',
                'msg' => '上架'
            ]);
        } else {
            $flag = $course->where(array('cid' => $id))->setField(['audit' => 0]);
            return json([
                'status' => $flag['status'],
                'data' => '',
                'msg' => '下架'
            ]);
        }
    }

    /**
     * [course_insur_state 课程insur支付开启状态]
     */
    public function course_insur_state()
    {
        $id = input('param.id');
        $course = new Cr();
        $status = $course->where(array('cid' => $id))->value('insur_switch'); // 判断当前状态情况
        if ($status == 0) {
            $flag = $course->where(array('cid' => $id))->setField(['insur_switch' => 1]);
            return json([
                'status' => $flag['status'],
                'data' => '',
                'msg' => '开启'
            ]);
        } else {
            $flag = $course->where(array('cid' => $id))->setField(['insur_switch' => 0]);
            return json([
                'status' => $flag['status'],
                'data' => '',
                'msg' => '关闭'
            ]);
        }
    }


    /**
     * pc介绍
     */
    public function introduce()
    {
        $cid = input('param.cid');
        $type = input('param.type', 'create');
        $key = $so['title'] = input('key');
        $map = [];
        $map['closed'] = 0;
        if ($key && $key !== "") {
            $map['title'] = ['like',"%" . $key . "%"];
        }
        if ($cid) {
            $map['cid'] = $cid;
        }
        $Nowpage = input('get.p') ? input('get.p') : 1;
        $intro = new CourseIntroduce();
        $limits = 10; // 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $intro->where($map)->count(); // 计算总页面
        $allpage = ceil($count / $limits);
        $lists = $intro->where($map)
            ->order('orderby desc')
            ->limit($start, $limits)
            ->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['edit_url'] = \think\Url::build('course/edit_intro', [
                'id' => $v['id']
            ]);
        }
        $this->assign('cid', $cid);
        $this->assign('type', $type);
        if (request()->isAjax()) {
            // $article->getlastsql();
            $msg['status'] = 200;
            $msg['data']['list'] = $lists;
            $msg['data']['title'] = "课程介绍管理";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }
    /**
     * 添加 pc介绍
     */
    public function add_intro()
    {
        $cid = input('param.cid');
        $type = input('param.type', 'create');
        $intro = new CourseIntroduce();
        if (request()->isPost()) {
            $param = input('post.');
            // 排序最前
            $course_order = $intro->where('cid', $cid)
                ->field('orderby')
                ->order('orderby desc')
                ->find();
            $orderby = $course_order['orderby'] + 1;
            $param['orderby'] = $orderby;
            $flag = $intro->InsertData($param);
            $pc_url = '/admin/course/introduce?cid=' . $cid . "&type=" . $type;
            return json([
                'status' => $flag['status'],
                'url' => $pc_url,
                'data' => '',
                'msg' => $flag['msg']
            ]);
        }
        $this->assign('cid', $cid);
        $this->assign('type', $type);
        return $this->fetch();
    }
    /**
     * 编辑 pc介绍
     */
    public function edit_intro()
    {
        $id = input('param.id');
        $cid = input('param.cid');
        $type = input('param.type', 'create');
        $parenttype = input('param.parenttype', 'create');
        $intro = new CourseIntroduce();
        if (request()->isPost()) {
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $intro->UpdateData($param, $where);
            $pc_url = '/admin/course/introduce?cid=' . $cid . "&type=" . $type;
            return json([
                'status' => $flag['status'],
                'url' => $pc_url,
                'data' => '',
                'msg' => $flag['msg']
            ]);
        }
        $introInfo = $intro->where('id', $id)->find();
        $this->assign('introInfo', $introInfo);
        $this->assign('parenttype', $parenttype);
        $this->assign('type', $type);
        $this->assign('cid', $cid);
        return $this->fetch();
    }
    /**
     * 删除 pc介绍
     */
    public function del_intro()
    {
        $id = input('param.id');
        $intro = new CourseIntroduce();
        $where['id'] = $id;
        $flag = $intro->DeleteData($where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    /**
     * 批量删除  pc介绍
     */
    public function del_Allintro()
    {
        $ids = input('param.checkbox');
        $intro = new CourseIntroduce();
        $where['id'] = [
            'in',
            $ids
        ];
        $flag = $intro->DeleteData($where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    // 课程介绍排序
    public function moveIntroduceUpDown()
    {
        $id = input('id');
        $cid = input('cid');
        $updown = input('updown');
        $course = new CourseIntroduce();
        $data_this = $course->where('id', $id)->find();
        $orderby = $data_this['orderby'];
        $map['cid'] = $cid;
        if ($updown == "up") {
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        } else {
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
        }
        $data_next = $course->where($map)->order($order)->limit(1)->find();
        if ($data_next) {
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            $list = [['id' => $id,'orderby' => $next_order],['id' => $next_id,'orderby' => $orderby]];
            $course->saveAll($list);
        }
        return json([
            'status' => 200,
            'url' => 'reload',
            'data' => '',
            'msg' => '排序成功'
        ]);
    }
    /////评论
    /**
     * 评论列表
     */
    public function comment()
    {
        $cid = input('param.cid');
        $map = [];
        $map['closed'] = 0;
        if ($cid) {
            $map['cid'] = $cid;
        }
        $Nowpage = input('get.p') ? input('get.p') : 1;
        $comment = new Comment();
        $user = new User();
        $limits = 10; // 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $comment->where($map)->count(); // 计算总页面
        $allpage = ceil($count / $limits);
        $lists = $comment->where($map)->order('id desc')->limit($start, $limits)->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['nickname'] = $user->where('uid', $v['uid'])->value('nickname');
        }
        $this->assign('cid', $cid);
        if (request()->isAjax()) {
            // $article->getlastsql();
            $msg['status'] = 200;
            $msg['data']['list'] = $lists;
            $msg['data']['title'] = "课程评价管理";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }
    /**
     * 评价状态
     */
    public function comment_state()
    {
        $id = input('param.id');
        $comment = new Comment();
        $status = $comment->where(array('id' => $id))->value('audit'); // 判断当前状态情况
        if ($status == 1) {
            $flag = $comment->where(array('id' => $id))->setField(['audit' => 0]);
            return json([
                'status' => $flag['status'],
                'data' => '',
                'msg' => '取消审核'
            ]);
        } else {
            $flag = $comment->where(array('id' => $id))->setField(['audit' => 1]);
            return json([
                'status' => $flag['status'],
                'data' => '',
                'msg' => '审核成功'
            ]);
        }
    }
    public function setAudit_comment()
    {
        $ids = input('param.checkbox');
        $comment = new Comment();
        $where['id'] = ['in',$ids];
        $param['audit'] = 1;
        $flag = $comment->SetParam($param, $where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    public function del_comment()
    {
        $id = input('param.id');
        $comment = new Comment();
        $where['id'] = $id;
        $flag = $comment->DeleteData($where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }
    public function delall_comment()
    {
        $ids = input('param.checkbox');
        $comment = new Comment();
        $where['id'] = ['in',$ids];
        $flag = $comment->DeleteData($where);
        return json([
            'status' => $flag['status'],
            'url' => 'reload',
            'data' => '',
            'msg' => $flag['msg']
        ]);
    }

   
    /**
     * 获取分类树
     */
    public function tree($cate, $lefthtml = '— — ', $pid = 0, $lvl = 0, $leftpin = 0)
    {
        $arr = $arr_pid = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $c['id'] = $v['id'];
                $c['pid'] = $v['pid'];
                $c['cate_name'] = $v['cate_name'];
                $c['lvl'] = $lvl + 1;
                $arr[] = $c;
                $arr = array_merge($arr, $this->tree($cate, $lefthtml, $v['id'], $lvl + 1, $leftpin + 15));
            }
        }
        
        return $arr;
    }

    /**
     * 课程生成小程序二维码
     */
    public function showcode($cid, $is_insert = 1)
    {
        $config = find('Config');
        $APPID = $config['wx_appid'];
        $APPSECRET = $config['wx_secret'];
        $data = get_php_file("min_access_token.php");
        $data = json_decode($data, true);
        if ($data['expire_time'] < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
            $res = httpGet($url);
            $res = json_decode($res, true);
            if (! isset($res['access_token'])) {
                $wxmincode = '';
                return $wxmincode;
            }
            $access_token = $res['access_token'];
            $data['expire_time'] = $expire_time = time() + 7000;
            $data['access_token'] = $access_token;
            set_php_file("min_access_token.php", json_encode($data));
        } else {
            $expire_time = $data['expire_time'];
            $access_token = $data['access_token'];
        }
        $getMinAccessToken = [
            'access_token' => $access_token,
            'token_time' => $expire_time
        ];
        $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token=' . $getMinAccessToken['access_token'];
        $param = [
            'path' => 'pages/course/detail?cid=' . $cid,
            'width' => 430
        ];
        $result = doCurlPostRequest($url, json_encode($param));
        if (isset($result['errcode'])) {
            // errorJson('errmsgc');
            $wxmincode = '';
        } else {
            $basedir = ROOT_PATH . 'public' . DS . '/image/';
            $course_path = $basedir . "course_wxcode_" . $cid . '.png';
            fopen($course_path, "w");
            file_put_contents($course_path, $result);
            $wxmincode = '/public/image/course_wxcode_' . $cid . '.png';
            if ($is_insert) {
                update('Course', [
                    'cid' => $cid
                ], [
                    'wxmincode' => $wxmincode
                ]);
            }
        }
        return $wxmincode;
    }

    /**
     * 获取图片主要色值
     */
    private function get_main_color($img)
    {
        $color = '';
        $imgrgb_arr = $this->img_color($img);
        if ($imgrgb_arr && count($imgrgb_arr) > 0) {
            $img_rgb = implode(',', $imgrgb_arr);
            $color = 'rgb(' . $img_rgb . ')';
        }
        return $color;
    }

    /*
     * 获取图片 rgb
     */
    private function img_color($imgurl)
    {
        $rgb = array();
        $rgb['r'] = '';
        $rgb['g'] = '';
        $rgb['b'] = '';
        if($imgurl){
            $imageInfo = getimagesize($imgurl);
            // 图片类型
            $imgType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            // 对应函数
            $imageFun = 'imagecreatefrom' . ($imgType == 'jpg' ? 'jpeg' : $imgType);
            $i = $imageFun($imgurl);
            // 循环色值
            $rColorNum = $gColorNum = $bColorNum = $total = 0;
            for ($x = 0; $x < imagesx($i); $x ++) {
                for ($y = 0; $y < imagesy($i); $y ++) {
                    $rgb = imagecolorat($i, $x, $y);
                    // 三通道
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    $rColorNum += $r;
                    $gColorNum += $g;
                    $bColorNum += $b;
                    $total ++;
                }
            }
            $rgb = array();
            $rgb['r'] = round($rColorNum / $total);
            $rgb['g'] = round($gColorNum / $total);
            $rgb['b'] = round($bColorNum / $total);
            return $rgb;
        }else{
            return $rgb;
        }

    }
    private function  log($list){
        $config_id = 5;
        if($list){
            $adminlog_detail = new AdminLog();
            $adminlog_detail->log($config_id, $list);
        }
    }
}