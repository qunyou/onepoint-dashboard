@inject('backendPresenter','Onepoint\Dashboard\Presenters\BackendPresenter')

@foreach (config('backend.navigation_item') as $element)
    @include('dashboard::backend-navi', $backendPresenter->setNavi($element))
@endforeach

{{ $slot }}