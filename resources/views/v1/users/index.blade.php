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
                        <table id="users" class="table table-hover dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>E-Mail</th>
                                    <th>Roles</th>
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
@if (auth()->user()->canCreateUsers() || auth()->user()->canUpdateUsers())
@component(__v() . '.components.modal', [
    'action' => route('users.store'),
    'method' => 'POST',
    'target' => 'user',
    'type' => '',
    'title' => 'create user',
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
        <label for="email" class="col-sm-3 control-label">E-Mail</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" id="email" placeholder="E-Mail" name="email" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label for="roles" class="col-sm-3 control-label">Roles</label>
        <div class="col-sm-9">
            <select class="form-control select2" id="roles" placeholder="Roles" name="roles">
                <option value=""></option>
                @foreach ($roles as $i => $v)
                    <option value="{{ $v->id }}">{{ ucwords($v->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endcomponent
@endif
@endsection
@section('js')
<script>
    const Table = $('#users').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: false, width: '3%' },
            { data: 'avatar', name: 'avatar', orderable: true, searchable: false },
            { data: 'name', name: 'name', orderable: true, searchable: true },
            { data: 'email', name: 'email', orderable: true, searchable: true },
            { data: 'roles', name: 'roles.name', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        [
            {
                className: 'text-center', 'targets': [ 0, -1, 1 ],
            }
        ],
    );
    @if (auth()->user()->canCreateUsers() || auth()->user()->canUpdateUsers())
    let id = '';
    const UserForm = $('#userModal');
    const validators = {
        name: {
            validators: {
                notEmpty: {},
                stringLength:{
                    min:3,
                }
            }
        },
        email: {
            validators: {
                notEmpty: {},
                regexp:{
                    regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                },
                stringLength:{
                    max:50,
                },
                remote: {
                    url: `${window.App.APP_ROUTE}/create`,
                    data: function(validator, $field, value) {
                        return {
                            id: id,
                            email: validator.getFieldElements('email').val(),
                        };
                    },
                    message: 'E-Mail sudah digunakan.',
                    type: 'GET'
                }
            }
        },
        roles: {
            validators: {
                notEmpty: {
                    message: 'Silakan pilih'
                },
            }
        }
    };
    UserForm.callFormValidation(validators)
    .on('success.form.fv', function(e) {
        e.preventDefault();
        UserForm.find('.modal-content').waitMeShow();
        const Text = UserForm.find('.modal-title').text();
        const $form = $(e.target),
            fv    = $form.data('formValidation');
        const Axios = axios.post($form.attr('action'), $form.serialize());
            Axios.then((response) => {
                UserForm.find('.modal-content').waitMeHide();
                UserForm.find('.modal').modal('hide');
                successResponse(response.data);
                Table.ajax.reload();
            });
            Axios.catch((error) => {
                failedResponse(error);
                UserForm.find('.modal-content').waitMeHide();
                UserForm.find('.modal').modal('hide');
            });
    });
    UserForm.find('.modal').on('hidden.bs.modal', function(){
        UserForm.find('.modal-title').html('CREATE USER');
        UserForm.find('#name').prop('disabled', false);
        UserForm.find('#email').prop('disabled', false);
        id = '';
    });
    @endif
    $(document).on('click', '.new-users', function(e){
        e.preventDefault();
        @if (auth()->user()->canCreateUsers())
        UserForm.find('.modal-title').html('CREATE USER');
        UserForm.attr('action', '/users');
        UserForm.find('[name="_method"]').val('POST');
        UserForm.find('.modal').modal('show');
        @endif
    });
    $(document).on('click', '._edit', function(e){
        e.preventDefault();
        @if (auth()->user()->canUpdateUsers())
        UserForm.find('.modal-title').html('EDIT USER');
        UserForm.attr('action', `/users/${$(this).data('id')}`);
        UserForm.find('.modal').modal('show');
        const Axios = axios.get(`/users/${$(this).data('id')}/edit`);
            Axios.then((response) => {
                id = $(this).data('id');
                UserForm.find('[name="_method"]').val('PUT');
                UserForm.find('#name').val(response.data.data.name).prop('disabled', true);
                UserForm.find('#email').val(response.data.data.email).prop('disabled', true);
                if(response.data.data.roles.length > 0) 
                    UserForm.find('#roles').callSelect2().val(response.data.data.roles[0].id).trigger("change");
            });
            Axios.catch((error) => {
                failedResponse(error);
                UserForm.find('.modal').modal('hide');
            });
        @endif
    });
    $(document).on('click', '._destroy', function(e){
        e.preventDefault();
        @if (auth()->user()->canDestroyUsers())
        const _this = $(this);
        _this.sweetAlert().then((aggre) => {
            if (aggre) {
                $('.content').find('.box').waitMeShow();
                const Axios = _this.destroy(`users/${_this.data('id')}`);
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