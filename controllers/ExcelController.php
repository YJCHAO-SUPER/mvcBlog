<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/14
 * Time: 20:28
 */
namespace controllers;

use models\Blogs;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController
{
//         生成Excel并下载
    function makeExcel(){

//        数据库中取出数据
        $blog = new Blogs();
        $all = $blog->getAllContent();
//        获取当前标签页
        $spreadsheet = new Spreadsheet();
//        获取当前工作
        $sheet = $spreadsheet->getActiveSheet();
//        设置第一行内容
        $sheet->setCellValue('A1','标题');
        $sheet->setCellValue('B1','内容');
        $sheet->setCellValue('C1','发布时间');
        $sheet->setCellValue('D1','是否公开');
//        从第二行写入数据
        $i = 2;
        foreach($all as $v){
            $sheet->setCellValue('A'.$i,$v['title']);
            $sheet->setCellValue('B'.$i,$v['content']);
            $sheet->setCellValue('C'.$i,$v['created_at']);
            $sheet->setCellValue('D'.$i,$v['is_show']==1?'公开':'私有');
            $i++;
        }

        $date = date('Ymd');

//        生成Excel文件
        $write = new Xlsx($spreadsheet);
        $write->save(ROOT.'\\hello.xlsx');

//        调用header函数设置协议头，告诉浏览器可以开始下载文件

//        下载文件路径
        $file = ROOT.'\\excel\\'.$date.'.xlsx';
//        下载时文件名
        $fileName = '所有日志-'.$date.'.xlsx';

        // 告诉浏览器这是一个二进程文件流
        Header ( "Content-Type: application/octet-stream" );
        // 请求范围的度量单位
        Header ( "Accept-Ranges: bytes" );
        // 告诉浏览器文件尺寸
        Header ( "Accept-Length: " . filesize ( $file ) );
        // 开始下载，下载时的文件名
        Header ( "Content-Disposition: attachment; filename=" . $fileName );

        // 读取服务器上的一个文件并以文件流的形式输出给浏览器
        readfile($file);


    }


}