<?php
/**
 * banner图
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\BannerModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class BannerController extends Controller
{
    use HasResourceActions;


    /**
     * 商品列表
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Banner管理');
            $content->description('banner列表');

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

            $content->header('Banner管理');
            $content->description('banner详情');
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

            $content->header('Banner管理');
            $content->description('创建banner');

            $content->body($this->form());

        });
    }

    /**
     * Banner grid
     * @return Grid
     */
    protected function grid()
    {

        return Admin::grid(BannerModel::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('image', '图片')->image(env('IMAGE_URL') , 50, 50);
            $grid->title('标题');
            $grid->url('链接地址');
            $states = [
                'on' => ['value' => 1, 'text' => '启用', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '禁用', 'color' => 'default'],
            ];
            $grid->column('status', '状态')->switch($states);
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->scope('trashed', '回收站')->onlyTrashed();
                $filter->like('ad_name', 'banner名称');
            });
//            $grid->start_time('起始时间');
//            $grid->end_time('结束时间');

            $grid->created_at('创建时间')->date('Y-m-d H:i:s')->sortable();
        });
    }

    /**
     * banner form
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BannerModel());
        $form->text('title', 'banner名称')->rules("required");
        $form->image('image', '图片')->uniqueName()->move('/banner')->rules("required");
        $form->number('sort', '排序')->default(50);
        $form->text('url', '链接地址');
        $states = [
            'on' => ['value' => 1, 'text' => '启用', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '禁用', 'color' => 'default'],
        ];
        $form->switch('status', '状态')->states($states)->default(1);

//        $form->datetimeRange('start_time', 'end_time', '展示时间');

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
