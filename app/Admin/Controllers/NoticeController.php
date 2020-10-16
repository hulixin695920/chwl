<?php
/**
 * 通知图
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\NoticeModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class NoticeController extends Controller
{
    use HasResourceActions;


    /**
     * 商品列表
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('通知管理');
            $content->description('通知列表');

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

            $content->header('通知管理');
            $content->description('通知详情');
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

            $content->header('通知管理');
            $content->description('创建通知');

            $content->body($this->form());

        });
    }

    /**
     * 通知 grid
     * @return Grid
     */
    protected function grid()
    {

        return Admin::grid(NoticeModel::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->title('内容');
            $states = [
                'on' => ['value' => 1, 'text' => '启用', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '禁用', 'color' => 'default'],
            ];
            $grid->column('status', '状态')->switch($states);
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->scope('trashed', '回收站')->onlyTrashed();
            });
//            $grid->start_time('起始时间');
//            $grid->end_time('结束时间');

            $grid->created_at('创建时间')->date('Y-m-d H:i:s')->sortable();
        });
    }

    /**
     * 通知 form
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new NoticeModel());
        $form->text('title', '通知名称')->rules("required");
        $form->number('sort', '排序')->default(50);
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
