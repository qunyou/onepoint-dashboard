@inject('backendPresenter','Onepoint\Dashboard\Presenters\BackendPresenter')

@foreach (config('backend.navigation_item') as $element)
    @include('dashboard::backend-navi', $backendPresenter->setNavi($element))
@endforeach

{{ $slot }}

@if (config('frontend.url', false))
    <li class="nav-item">
        <a class="nav-link" href="{{ config('frontend.url') }}">
            <i class="fas fa-home" aria-hidden="true"></i>
            <span>@lang('backend.檢視網站')</span>
        </a>
    </li>
@endif