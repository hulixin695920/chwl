<?php


namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
{
    use HasResourceActions;


    /**
     * 商品列表
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('用户管理');
            $content->description('用户列表');

            $content->body($this->grid());
        });

    }

    /**
     * 编辑
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('用户管理');
            $content->description('用户详情');
            $content->body($this->form()->edit($id));
        });

    }

    /**
     * 创建
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('用户管理管理');
            $content->description('创建用户');

            $content->body($this->form());

        });
    }

    /**
     * 用户grid
     * @return Grid
     */
    protected function grid()
    {

        return Admin::grid(UserModel::class, function (Grid $grid) {
            $grid->model()->orderBy('created_at', 'desc');
            $grid->id('ID')->sortable();
            $grid->realname('真实姓名')->sortable();
            $grid->column('avatar', '头像')->image('', 50, 50);
            $grid->nickname('昵称');
            $grid->code('邀请码');
//            $grid->bail('百分比')->editable();
            $grid->mobile_phone('手机号')->editable();
            $grid->column('gender', '性别')->using([0 => '未知', 1 => '男', 2 => '女']);
//            $grid->column('user_type', '代理级别')->using([0 => '无', 1 => 'B级代理', 2 => 'A级代理']);
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
//                $filter->scope('trashed', '回收站')->onlyTrashed();
                $filter->equal('mobile_phone', '手机号');
                $filter->like('nickname', '昵称');
//                $filter->equal('is_member', '是否是会员')->radio([
//                    '' => '全部',
//                    0 => '否',
//                    1 => '是',
//                ]);
            });
            $grid->column('is_member', '标签')->display(function () {
                if ($this->expire_time == 0) {
                    return '普通用户';
                } else {
                    if ($this->expire_time > time()) {
                        return '会员';
                    } else {
                        return '普通用户';
                    }
                }
            })->label();
//            $grid->column('expire_time', '过期时间')->display(function () {
//                if ($this->expire_time == 0) {
//                    return '-';
//                } else {
//                    return date('Y-m-d H:i:s', $this->expire_time);
//                }
//            });

            $grid->disableCreateButton();
            $grid->country('国家');
            $grid->province('省份');
            $grid->city('城市');
            $grid->created_at('创建时间')->date('Y-m-d H:i:s')->sortable();
//            $grid->disableActions();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableView();
            });
        });
    }

    /**
     * 用户form
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserModel());
        $show = new Show(new UserModel());
        $form->text('mobile_phone', '手机号');
//        $form->text('bail', '百分比（%）');
        $form->text('code', '邀请码')->readonly();
        $form->radioCard('user_type', '用户类型')->options([0 => '普通用户', 1 => 'B级代理', 2 => 'A级代理'])->default(0);

        $form->display('nickname', '昵称');
        $form->display('country', '国家');
        $form->display('province', '省份');
        $form->display('city', '城市');

        $form->saving(function (Form $form) {
            if ($form->isEditing()) {
                if (\request('user_type') == 2) {
                    $form->parent_id = 0;
                }
                if (empty(\request('code'))) {
                    $form->code = $form->model()->id + 10000;
                }
            }

        });

        return $form;
    }

    protected function show()
    {

    }


}
