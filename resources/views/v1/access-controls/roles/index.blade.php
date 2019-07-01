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
                                    <th width="5">No</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
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
@if (auth()->user()->canCreateRoles() || auth()->user()->canUpdateRoles())
@component(__v() . '.components.modal', [
    'action' => route('roles.store'),
    'method' => 'POST',
    'target' => 'role',
    'type' => '',
    'title' => 'create role',
    'class' => '',
    'footer' => true
    ])
    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="name" placeholder="Name" name="name" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-3 control-label">Description <small>(Optional)</small></label>
        <div class="col-sm-9">
            <textarea class="form-control" id="description" placeholder="Description" name="description"></textarea>
        </div>
    </div>
@endcomponent
@endif
@endsection
@section('js')
<script>
    const Table = $('#roles').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: false },
            { data: 'name', name: 'name', orderable: true, searchable: true },
            { data: 'description', name: 'description', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        [
            {
                responsivePriority: 0,
                className: 'text-center', 'targets': [ -1, 0 ],
            }
        ], 1, 'asc'
    );
    @if (auth()->user()->canCreateRoles() || auth()->user()->canUpdateRoles())
    let id = '';
    const RoleForm = $('#roleModal');
    const validators = {
        name: {
            validators: {
                notEmpty: {},
                stringLength:{
                    min:3,
                },
                remote: {
                    url: `${window.App.APP_ROUTE}/create`,
                    data: function(validator, $field, value) {
                        return {
                            id: id,
                            name: validator.getFieldElements('name').val(),
                        };
                    },
                    message: 'Name sudah digunakan.',
                    type: 'GET'
                }
            }
        },
        description: {
            validators: {
                stringLength:{
                    max:225,
                }
            }
        }
    };
    RoleForm.callFormValidation(validators)
    .on('success.form.fv', function(e) {
        e.preventDefault();
        RoleForm.find('.modal-content').waitMeShow();
        const $form = $(e.target),
            fv    = $form.data('formValidation');
        const Axios = axios.post($form.attr('action'), $form.serialize());
            Axios.then((response) => {
                RoleForm.find('.modal').modal('hide');
                successResponse(response.data);
                Table.ajax.reload();
            });
            Axios.catch((error) => {
                failedResponse(error);
                RoleForm.find('.modal').modal('hide');
            });
    });
    RoleForm.find('.modal').on('hidden.bs.modal', function(){
        RoleForm.find('.modal-title').html('CREATE ROLE'); id = '';
    });
    @endif
    $(document).on('click', '.new-roles', function(e){
        e.preventDefault();
        @if (auth()->user()->canCreateRoles())
            RoleForm.find('.modal').modal('show');
            RoleForm.attr('action', '/roles');
            RoleForm.find('[name="_method"]').val('POST');
            RoleForm.find('#name').val('').focus();
            RoleForm.find('#description').val('');
        @endif
    });
    $(document).on('click', '._edit', function(e){
        e.preventDefault();
        @if(auth()->user()->canUpdateRoles())
        RoleForm.find('.modal-title').html('EDIT ROLE');
        RoleForm.attr('action', `/roles/${$(this).data('id')}`);
        RoleForm.find('.modal').modal('show');
        const Axios = axios.get(`/roles/${$(this).data('id')}/edit`);
            Axios.then((response) => {
                RoleForm.find('[name="_method"]').val('PUT');
                RoleForm.find('#name').val(response.data.data.name).focus();
                RoleForm.find('#description').val(response.data.data.description);
                id = $(this).data('id');
            });
            Axios.catch((error) => {
                failedResponse(error);
                RoleForm.find('.modal').modal('hide');
            });
        @endif
    });
    $(document).on('click', '._destroy', function(e){
        e.preventDefault();
        @if (auth()->user()->canDestroyRoles())
        const _this = $(this);
        _this.sweetAlert()
        .then((aggre) => {
            if (aggre) {
                $('.content').find('.box').waitMeShow();
                const Axios = _this.destroy(`/roles/${_this.data('id')}`);
                Axios.then((response) => {
                    successResponse(response.data);
                    Table.ajax.reload();
                    setTimeout(() => $('.content').find('.box').waitMeHide(), 1000);
                });
                Axios.catch((error) => {
                    $('.content').find('.box').waitMeHide();
                    failedResponse(error);
                });
            }else{
                swal(Label.sweetTextCancel, {
                    icon: 'error',
                });
            }
        });
        @endif
    });
</script>
@endsection