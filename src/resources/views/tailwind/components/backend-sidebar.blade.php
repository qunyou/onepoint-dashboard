@inject('backendPresenter','Onepoint\Dashboard\Presenters\BackendPresenter')

@foreach ($navigation_item as $element)
    @include('dashboard::backend-navi', $backendPresenter->setNavi($element))
@endforeach

{{ $slot }}