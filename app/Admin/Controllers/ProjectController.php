<?php
/**
 * 内容
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\ProjectModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ProjectController extends Controller
{
    use HasResourceActions;


    /**
     * 商品列表
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('项目管理');
            $content->description('项目列表');

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

            $content->header('项目管理');
            $content->description('项目详情');
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

            $content->header('项目管理');
            $content->description('创建项目');

            $content->body($this->form());

        });
    }

    /**
     * 项目 grid
     * @return Grid
     */
    protected function grid()
    {

        return Admin::grid(ProjectModel::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('image', '封面')->image(env('IMAGE_URL') , 50, 50);
            $grid->title('标题');
            $grid->app_id('小程序ID');
            $grid->description('描述');
            $states = [
                'on' => ['value' => 1, 'text' => '启用', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '禁用', 'color' => 'default'],
            ];
            $grid->column('status', '状态')->switch($states);
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->scope('trashed', '回收站')->onlyTrashed();
                $filter->equal('app_id', '小程序ID');
            });
            $grid->url('链接地址');
//            $grid->start_time('起始时间');
//            $grid->end_time('结束时间');

            $grid->created_at('创建时间')->date('Y-m-d H:i:s')->sortable();
            $grid->actions(function ($actions) {
                if (\request('_scope_') == 'trashed') {
                    $actions->disableDelete();
                    $actions->disableEdit();
                }
            });
        });
    }

    /**
     * 项目 form
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProjectModel());
        $form->text('title', '标题')->rules("required");
        $form->text('app_id', '小程序ID');
        $form->text('description', '描述');
        $form->image('image', '封面')->uniqueName()->move('/project')->rules("required");
        $form->number('sort', '排序')->default(50);
        $form->text('url', '链接地址')->default('');
        $states = [
            'on' => ['value' => 1, 'text' => '启用', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '禁用', 'color' => 'default'],
        ];
        $form->switch('status', '状态')->states($states)->default(1);

        // 两个时间显示
        $form->display('created_at', '创建时间');
        $form->display('updated_at', '修改时间');


        return $form;
    }

    protected function show()
    {

    }

}
