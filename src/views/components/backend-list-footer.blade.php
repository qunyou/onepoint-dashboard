<footer class="card-footer">
    <div class="row">
        <div class="col d-none d-md-inline">
            @if (!isset($footer_dropdown_hide) || (isset($footer_dropdown_hide) && !$footer_dropdown_hide))
                <div class="btn-group">
                    <div class="dropdown">
                        <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('backend.選取項目')
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if ($trashed)
                                <button type="submit" name="force_delete" value="force_delete" class="dropdown-item">
                                    <i class="fa fa-trash"></i>@lang('backend.永久刪除')
                                </button>
                                <button type="submit" name="restore" value="restore" class="dropdown-item">
                                    <i class="fa fa-recycle"></i>@lang('backend.還原')
                                </button>
                            @else
                                <button type="submit" name="status_enable" value="status_enable" class="dropdown-item">
                                    <i class="fas fa-eye"></i>@lang('backend.啟用')
                                </button>
                                <button type="submit" name="status_disable" value="status_disable" class="dropdown-item">
                                    <i class="fas fa-eye-slash"></i>@lang('backend.停用')
                                </button>
                                @if (!isset($footer_delete_hide) || (isset($footer_delete_hide) && !$footer_delete_hide))
                                    <button type="submit" name="delete" value="delete" class="dropdown-item">
                                        <i class="fa fa-trash"></i>@lang('backend.刪除')
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-auto pt-2">
            {{ $slot }}
        </div>
        <div class="col d-none d-md-inline">
            @if (!isset($footer_sort_hide) || (isset($footer_sort_hide) && !$footer_sort_hide))
                @if (!$trashed)
                    <div class="btn-group float-right">
                        <button type="submit" name="set_sort" value="set_sort" class="btn btn-outline-deep-purple waves-effect">
                            <i class="fa fa-sort"></i>@lang('backend.修改排序')
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>
</footer>