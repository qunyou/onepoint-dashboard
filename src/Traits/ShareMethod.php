<?php

namespace Onepoint\Dashboard\Traits;

trait ShareMethod
{
    public function share()
    {
        // 設定語言
        // 如果有問題，執行清除動作
        // cache()->flush();
        // cache()->forget('backend_language');
        if (request('lang', false)) {
            cache()->forever('backend_language', request('lang'));
        }
        \App::setLocale(cache('backend_language', 'zh-tw'));
        
        // 判斷是否使用分站網址
        $this->backend_url_suffix = '';
        if (config('backend_url_suffix', false)) {
            $this->backend_url_suffix = config('backend_url_suffix') . '/';
        }
        config(['dashboard.uri' => $this->backend_url_suffix . config('dashboard.uri')]);
    }
}
