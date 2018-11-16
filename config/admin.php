<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 下午 17:46
 */

return [
	'title' => '后台管理',

	'page_limit' => [ 10, 20, 30 ],

	'image' => [
		'ext'  => 'gif, jpg, jpeg',
		'size' => 1024 * 1024
	],

	'local_upload_path' => env('root_path') . DIRECTORY_SEPARATOR . 'upload',
];