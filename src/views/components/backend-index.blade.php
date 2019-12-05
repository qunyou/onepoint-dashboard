<div class="card card-list">
    <div class="card-header">
        <div class="row justify-content-between">
            <div class="col-auto">
                <div class="card-title">{{ $page_title }}</div>
            </div>
            <div class="col-auto">
                <div class="float-right btn-group dropleft">
                    {!! $top_button !!}
                </div>
            </div>
        </div>
    </div>

    {{ $search_block ?? '' }}
    
    <form action="" method="post">
        @csrf
        @method('PUT')
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="check_all_width d-none d-md-table-cell">
                            <input type="checkbox" name="select_all" id="select_all" value="" data-toggle="tooltip" data-original-title="@lang('backend.全選')" />
                        </th>
                        {!! $th ?? '' !!}
                        @if (!$trashed)
                            <th scope="col"></th>
                            @if (!isset($hide_sort) || (isset($hide_sort) && $hide_sort == false))
                                <th scope="col" class="th_sort_width d-none d-md-table-cell">@lang('backend.排序')</th>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    {!! $td ?? '' !!}
                </tbody>
            </table>
        </div>
        <footer class="card-footer">
            <div class="row">
                <div class="col d-none d-md-inline">
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @lang('backend.選取項目')
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @if ($trashed)
                                    <button type="submit" name="force_delete" value="force_delete" class="dropdown-item">
                                        <i class="fa fa-fw fa-trash"></i><span class="d-none d-md-inline">@lang('backend.永久刪除')</span>
                                    </button>
                                    <button type="submit" name="restore" value="restore" class="dropdown-item">
                                        <i class="fa fa-fw fa-recycle"></i><span class="d-none d-md-inline">@lang('backend.還原')</span>
                                    </button>
                                @else
                                    @if (!isset($hide_status) || (isset($hide_status) && $hide_status == false))
                                        <button type="submit" name="status_enable" value="status_enable" class="dropdown-item">
                                            <i class="fas fa-eye fa-fw"></i><span class="d-none d-md-inline">@lang('backend.啟用')</span>
                                        </button>
                                        <button type="submit" name="status_disable" value="status_disable" class="dropdown-item">
                                            <i class="fas fa-eye-slash fa-fw"></i><span class="d-none d-md-inline">@lang('backend.停用')</span>
                                        </button>
                                    @endif
                                    <button type="submit" name="delete" value="delete" class="dropdown-item">
                                        <i class="fa fa-fw fa-trash"></i><span class="d-none d-md-inline">@lang('backend.刪除')</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                    {!! $paginate ?? '' !!}
                </div>
                <div class="col d-none d-md-inline">
                    @if (!$trashed)
                        <div class="btn-group float-right">
                            @if (!isset($hide_sort) || (isset($hide_sort) && $hide_sort == false))
                                <button type="submit" name="set_sort" value="set_sort" class="btn btn-outline-deep-purple waves-effect">
                                    <i class="fa fa-fw fa-sort"></i>@lang('backend.修改排序')
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </footer>
    </form>
</div>