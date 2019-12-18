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
        return redirect('index');
        // return view('welcome');
    }
}
