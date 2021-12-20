<?php

namespace Onepoint\Dashboard\View\Components;

use Illuminate\View\Component;

class CheckedIteDropdownmMenu extends Component
{
    /**
     * 邊欄項目陣列
     *
     * @var array
     */
    // public $navigation_item;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    // public function __construct($navigationItem)
    // {
    //     $this->navigation_item = $navigationItem;
    // }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // Larvel 6 不能用這個功能，暫停修改
        return view('dashboard::bootstrap.components.checked-item-menu');
    }
}
