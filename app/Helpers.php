<?php


if (!function_exists('get_property')) {
    function get_property($obj, $property, $default = null)
    {
        if (!$obj) {
            return $default;
        }
        is_string($obj) and $obj = json_decode($obj, true);
        if (is_object($obj)) {
            return property_exists($obj, $property) || isset($obj->$property) ? $obj->$property : $default;
        }

        return isset($obj[$property]) ? $obj[$property] : $default;
    }
}

/**
 * 格式化返回
 */
if (!function_exists('commonResponse')) {
    /**
     * Return a new response from the application.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|\App\Libraries\CommonResponse
     */
    function commonResponse($content = '', $status = 200, array $headers = [])
    {
        $factory = new \App\Libraries\CommonResponse();
        if (func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($content, $status, $headers);
    }
}


if (!function_exists('curl_method')) {

    function curl_method($url, $data, $method, $setcooke = false, $cookie_file = false)
    {
        $ch = curl_init();     //1.初始化
        curl_setopt($ch, CURLOPT_URL, $url); //2.请求地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);//3.请求方式
        //4.参数如下
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0'); //指定请求方式（浏览器）
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if (strtolower($method) == "post") {//5.post方式的时候添加数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($setcooke == true) {
            //把生成的cookie保存在指定的文件中
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        } else {
            //直接从文件中读取cookie信息
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }
}

if (!function_exists('curl_post')) {

    function curl_post($url, $data)
    {

        //初使化init方法
        $ch = curl_init();

        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);

        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);

        //发送什么数据呢
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        //发送请求
        $output = curl_exec($ch);

        //关闭curl
        curl_close($ch);

        //返回数据
        return $output;
    }
}

if (!function_exists('get_img_ext')) {

    function get_img_ext($url)
    {
        $aext = explode('.', $url);
        $ext = end($aext);
        return $ext;
    }
}

if (!function_exists('grab_image')) {
    function grab_image($url, $save_dir = '', $filename = '', $type = true)
    {
        if (trim($url) == '') {
            return '';
        }
        if (trim($save_dir) == '') {
            $localdir = '/temp/';
            $save_dir = '/tmp' . $localdir;
        }
        if (trim($filename) == '') {//保存文件名
            $ext = strrchr($url, '.');
            $filename = md5(uniqid()) . rand(1000, 9999) . $ext;
        }
        //创建保存目录
        if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
            return array('file_name' => '', 'save_path' => '', 'error' => 5);
        }
        //获取远程文件所采用的方法
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小
        $fp2 = @fopen($save_dir . $filename, 'a');
        fwrite($fp2, $img);
        fclose($fp2);
        unset($img, $url);
        return $save_dir . $filename;
    }
}

if (!function_exists('image_resize')) {
    function image_resize($src_file, $dst_file, $new_width, $new_height)
    {
        $new_width = intval($new_width);

        $new_height = intval($new_height);

        if ($new_width < 1 || $new_height < 1) {
            echo "params width or height error !";
            exit();
        }

        if (!file_exists($src_file)) {
            echo $src_file . " is not exists !";
            exit();
        }

// 图像类型
        $type = exif_imagetype($src_file);
        $support_type = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);

        if (!in_array($type, $support_type, true)) {

            echo "this type of image does not support! only support jpg , gif or png";
            exit();
        }

        switch ($type) {
            case IMAGETYPE_JPEG :
                $src_img = imagecreatefromjpeg($src_file);
                break;
            case IMAGETYPE_PNG :
                $src_img = imagecreatefrompng($src_file);
                break;
            case IMAGETYPE_GIF :
                $src_img = imagecreatefromgif($src_file);
                break;
            default :
                echo "Load image error!";
                exit();
        }
        $w = imagesx($src_img);
        $h = imagesy($src_img);
        $ratio_w = 1.0 * $new_width / $w;
        $ratio_h = 1.0 * $new_height / $h;
        $ratio = 1.0;

        // 生成的图像的高宽比原来的都小,或都大 ,原则是 取大比例放大,取大比例缩小(缩小的比例就比较小了)
        if (($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) {
            if ($ratio_w < $ratio_h) {
                $ratio = $ratio_h;
                // 情况一,宽度的比例比高度方向的小,按照高度的比例标准来裁剪或放大
            } else {
                $ratio = $ratio_w;
            }

            // 定义一个中间的临时图像,该图像的宽高比 正好满足目标要求
            $inter_w = (int)($new_width / $ratio);
            $inter_h = (int)($new_height / $ratio);
            $inter_img = imagecreatetruecolor($inter_w, $inter_h);
            //var_dump($inter_img);
            imagecopy($inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h);

            // 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
            // 定义一个新的图像
            ini_set('memory_limit', '8M');    //更改PHP的内存限制
            $new_img = imagecreatetruecolor($new_width, $new_height);
            //var_dump($new_img);exit();
            imagecopyresampled($new_img, $inter_img, 0, 0, 0, 0, $new_width, $new_height, $inter_w, $inter_h);
            switch ($type) {
                case IMAGETYPE_JPEG :
                    // 存储图像
                    imagejpeg($new_img, $dst_file, 100);
                    break;
                case IMAGETYPE_PNG :
                    imagepng($new_img, $dst_file, 100);
                    break;
                case IMAGETYPE_GIF :
                    imagegif($new_img, $dst_file, 100);
                    break;
                default :
                    break;
            }
        }// end if 1
        // 2 目标图像 的一个边大于原图,一个边小于原图 ,先放大平普图像,然后裁剪

        // =if( ($ratio_w < 1 && $ratio_h > 1) || ($ratio_w >1 && $ratio_h <1) )
        else {
            $ratio = $ratio_h > $ratio_w ? $ratio_h : $ratio_w;
            //取比例大的那个值
            // 定义一个中间的大图像,该图像的高或宽和目标图像相等,然后对原图放大
            $inter_w = (int)($w * $ratio);
            $inter_h = (int)($h * $ratio);
            $inter_img = imagecreatetruecolor($inter_w, $inter_h);
            //将原图缩放比例后裁剪
            imagecopyresampled($inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h, $w, $h);
            // 定义一个新的图像
            $new_img = imagecreatetruecolor($new_width, $new_height);
            imagecopy($new_img, $inter_img, 0, 0, 0, 0, $new_width, $new_height);
            switch ($type) {
                case IMAGETYPE_JPEG :
                    imagejpeg($new_img, $dst_file, 100);
                    break;
                case IMAGETYPE_PNG :
                    imagepng($new_img, $dst_file, 100);
                    break;
                case IMAGETYPE_GIF :
                    imagegif($new_img, $dst_file, 100);
                    break;
                default :
                    break;
            }
        }// if3
    }
}
