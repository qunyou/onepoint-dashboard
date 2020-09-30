<?php

namespace Onepoint\Dashboard\Traits;

trait ShareMethod
{
    public function share()
    {
        // 判斷是否使用分站網址
        $this->backend_url_suffix = '';
        if (config('backend_url_suffix', false)) {
            $this->backend_url_suffix = config('backend_url_suffix') . '/';
        }
        config(['dashboard.uri' => $this->backend_url_suffix . config('dashboard.uri')]);
    }
}
