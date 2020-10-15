<?php
/**
 * 反馈
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\FeedBackModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class FeedbackController extends Controller
{
    use HasResourceActions;


    /**
     * 商品列表
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('反馈管理');
            $content->description('反馈列表');

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

            $content->header('反馈管理');
            $content->description('反馈详情');
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

            $content->header('反馈管理');
            $content->description('创建反馈');

            $content->body($this->form());

        });
    }

    /**
     * 反馈 grid
     * @return Grid
     */
    protected function grid()
    {

        return Admin::grid(FeedBackModel::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
//            $grid->column('image', '图片')->image(env('IMAGE_URL') , 50, 50);
            $grid->column('msg_content')->display(function($text) {
                return substr($text, 0, 30);
            });
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
     * 反馈 form
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new FeedBackModel());
        $form->textarea('msg_content', '反馈内容')->readonly();

        // 两个时间显示
        $form->display('created_at', '创建时间');
        $form->display('updated_at', '修改时间');

        // 数据预处理
//        $form->saving(function (Form $form) {
//            $form->enabled = $form->enabled == 'on' ? 1 : 0;
//        });


        return $form;
    }

    protected function show()
    {

    }

}
