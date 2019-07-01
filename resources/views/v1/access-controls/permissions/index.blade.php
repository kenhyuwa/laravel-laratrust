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
                        <table id="roles" class="table table-hover dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Ability</th>
                                    <th>Description</th>
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
@endsection
@section('js')
<script>
    $('#roles').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: false, width: '3%' },
            { data: 'name', name: 'name', orderable: true, searchable: true, width: '20%' },
            { data: 'description', name: 'description', orderable: true, searchable: true },
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
        @if (auth()->user()->canStorePermissions())
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