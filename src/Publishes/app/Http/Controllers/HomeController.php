<?php
namespace App\Http\Controllers;

/**
 * 首頁
 */
class HomeController extends Controller
{
    /**
     * 首頁
     */
    public function index()
    {
        // 流量統計
        // return $this->base_services->agent();
        // return redirect('index');
        return view('welcome');
    }
}
