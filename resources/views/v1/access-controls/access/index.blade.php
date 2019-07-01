@extends(__v() . '.layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12 can-focus">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ str_title() }}</h3>
                    <div class="box-tools pull-right">
                        {{ box_collapse('collapse') }}
                        {{ box_remove('remove') }}
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-md-12"> 
                        <table id="access" class="table table-hover dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Menu</th>
                                    @foreach ($roles as $v)
                                        <th>{{ ucwords($v->name) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                {{ box_footer() }}
            </div>
        </div>
    </div>
</section>
{{-- <section class="content">
    <div class="row">
        <div class="col-md-12 can-focus">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ str_title() }}</h3>
                    <div class="box-tools pull-right">
                        {{ box_collapse('collapse') }}
                        {{ box_remove('remove') }}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-md-{{ 12 - count($roles) }} col-xs-{{ 12 - (count($roles) * 2) }}">
                                        <h2 class="text-center mt-5">{{ _('Menu') }}</h2>
                                    </div>
                                    @foreach ($roles as $v)
                                        <div class="col-md-1 col-xs-2">
                                            <h2 class="text-center mt-5">{{ ucwords($v->name) }}</h2>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-{{ 12 - count($roles) }} col-xs-{{ 12 - (count($roles) * 2) }}">
                                        <div class="nestable-with-handle">
                                            <ol class="dd-list">
                                                @foreach ($nav as $vv)
                                                    <li class="dd-item dd3-item">
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content">
                                                            <i class="{{ $vv->icon ?? 'fa fa-circle-o' }}"></i>
                                                            <span class="hidden-xs">{{ $vv->name }}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                    @foreach ($roles as $i => $element)
                                        <div class="col-md-1 col-xs-2">
                                            @foreach ($menu as $i => $v)
                                                <div class="row {{ $i < 1 ? 'mt-2' : '' }}">
                                                    <div class="col-md-12 col-xs-12" style="margin-top: 4px;">
                                                        <div class="dd">
                                                            <ol class="dd-list">
                                                                <li class="dd-item">
                                                                    <div class="dd-handle" style="margin: 1px 0px;height: 33px;border: none;">
                                                                        <div class="checkbox icheck mt-0 mb-0 text-center">
                                                                            <label>
                                                                                <input type="checkbox" name="checkbox" id="checkbox" class="checkbox checkbox_{{ $v->id }}" data-permissions="{{ $v->id }}" data-roles="{{ $element->id }}" {{ $element->hasMenu($v->id) ? 'checked' : '' }}>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ box_footer() }}
            </div>
        </div>
    </div>
</section> --}}
@endsection
@section('js')
<script>
    $('.nestable-with-handle').nestable().nestable('expandAll');
    const Table = $('#access').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: false, width: '3%' },
            { data: '{{ app()->getLocale() }}_name', name: '{{ app()->getLocale() }}_name', orderable: true, searchable: true, width: '20%' },
            @foreach ($roles as $i => $v)
                { data: 'action_{{ $i }}', name: 'action_{{ $i }}', orderable: false, searchable: false },
            @endforeach
        ],
        [
            {
                responsivePriority: 0,
                className: 'text-center', 'targets': [
                    @foreach ($roles as $i => $v)
                        {{ '-'.++$i.',' }}
                    @endforeach
                    0
                ],
            }
        ], 1, 'asc'
    ).on('draw.dt', () => {
        @if (auth()->user()->canStoreAccess())
            $('.checkbox').checkboxRequest();
        @else
            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
            });
        @endif
    });
</script>
@endsection