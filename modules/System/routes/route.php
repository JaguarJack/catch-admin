<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\AsyncTaskController;
use Modules\System\Http\Controllers\ConnectorLogController;
use Modules\System\Http\Controllers\CronTasksController;
use Modules\System\Http\Controllers\CronTasksLogController;
use Modules\System\Http\Controllers\DictionaryController;
use Modules\System\Http\Controllers\DictionaryValuesController;
use Modules\System\Http\Controllers\DomainConfigController;
use Modules\System\Http\Controllers\PersonalAccessTokensController;
use Modules\System\Http\Controllers\RouteController;
use Modules\System\Http\Controllers\SchemaController;
use Modules\System\Http\Controllers\SmsConfigController;
use Modules\System\Http\Controllers\SystemAttachmentCategoryController;
use Modules\System\Http\Controllers\SystemAttachmentsController;
use Modules\System\Http\Controllers\SystemSmsCodeController;
use Modules\System\Http\Controllers\SystemSmsTemplateController;
use Modules\System\Http\Controllers\UploadConfigController;
use Modules\System\Http\Controllers\WebhookController;
use Modules\System\Http\Controllers\WechatConfigController;
use Modules\System\Http\Controllers\SettingController;

Route::prefix('system')->group(function () {
    Route::apiResource('dictionary', DictionaryController::class);
    Route::put('dictionary/enable/{id}', [DictionaryController::class, 'enable']);
    Route::post('dictionary/enums/{id}', [DictionaryController::class, 'enums']);

    Route::apiResource('dic/values', DictionaryValuesController::class);
    Route::put('dic/values/enable/{id}', [DictionaryValuesController::class, 'enable']);
    // 上传管理
    Route::post('upload/config', [UploadConfigController::class, 'store']);
    Route::get('upload/config/{driver?}', [UploadConfigController::class, 'show']);
    // 附件管理
    Route::apiResource('attachments', SystemAttachmentsController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('attachment/category', SystemAttachmentCategoryController::class)->except(['show']);
    // 设置
    Route::post('setting', [SettingController::class, 'store'])->name('system.setting.store');
    Route::get('setting', [SettingController::class, 'show'])->name('system.setting.show');
    // next
});
