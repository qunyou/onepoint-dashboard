<?php

namespace Onepoint\Dashboard\Traits;

trait ShareMethod
{
    public function share()
    {
        // 設定語言
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
