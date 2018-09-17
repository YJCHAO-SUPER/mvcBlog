<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/9/17
 * Time: 20:46
 */
namespace controllers;

class BaseController
{
    function response($data){
        die(json_encode($data));
    }
}