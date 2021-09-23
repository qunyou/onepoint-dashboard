<?php

namespace Onepoint\Dashboard\View\Components;

use Illuminate\View\Component;
use Onepoint\Dashboard\Presenters\BackendPresenter;

class SideItem extends Component
{
    /**
     * 邊欄項目陣列
     *
     * @var array
     */
    public $navigation_item;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($navigationItem)
    {
        $this->navigation_item = $navigationItem;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $res = BackendPresenter::setNavi($this->navigation_item);
        // dd($res);
        return view('dashboard::tailwind.components.side-item', BackendPresenter::setNavi($this->navigation_item));
    }
}
