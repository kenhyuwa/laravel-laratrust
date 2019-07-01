@extends(__v() . '.layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-15 text-right">
                <button type="button" class="btn btn-sm btn-flat btn-primary new-users" {{ modal('user') }} {{ auth()->user()->canCreateUsers() ? '' : 'disabled' }}><span {{ tooltip('create') }}>{{ str_title('create') }}</span></button>
            </div>
        </div>
    </div>
    @if (count($users) < 1)
        {{ callout_info('nothing users are registered', $dimmis = true, $icon = false) }}
    @endif
    @foreach ($users->chunk(3) as $user)
        <div class="row">
            @foreach ($user as $v)
                <div class="col-md-4">
                    <div class="box box-widget widget-user">
                        <div class="widget-user-header bg-aqua-active">
                            <span>
                                <button data-id="{{ in_array($v->roles[0]['name'], [config('laravelia.default_role')]) ? '' : $v->id }}" type="button" class="btn btn-xs btn-flat btn-info _edit" {{ auth()->user()->canUpdateUsers() && !in_array($v->roles[0]['name'], [config('laravelia.default_role')]) ? '' : 'disabled' }}>
                                    <i class="fa fa-edit" {{ tooltip('Edit', 'bottom') }}></i>
                                </button>
                            </span>
                            <span>
                                <button data-id="{{ in_array($v->roles[0]['name'], [config('laravelia.default_role')]) ? '' : $v->id }}" type="button" class="btn btn-xs btn-flat btn-danger _destroy" {{ auth()->user()->canDestroyUsers() && !in_array($v->roles[0]['name'], [config('laravelia.default_role')]) ? '' : 'disabled' }}>
                                    <i class="fa fa-trash" {{ tooltip('Delete', 'bottom') }}></i>
                                </button>
                            </span>
                            <h3 class="widget-user-username">{{ ucwords($v->name) }}</h3>
                            <h5 class="widget-user-desc">{{ $v->roles->count() > 0 ? ucwords($v->roles[0]['name']) : '' }}</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="{{ $v->avatar }}" alt="{{ ucwords($v->name) }}">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-6 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">3,200</h5>
                                        <span class="description-text">SALES</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="description-block">
                                        <h5 class="description-header">35</h5>
                                        <span class="description-text">PRODUCTS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
    <div class="row">
        <div class="col-md-12 text-center">
            {!! $users->links() !!}
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
            <input type="text" class="form-control focused" id="name" placeholder="Name" name="name" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-sm-3 control-label">E-Mail</label>
        <div class="col-sm-9">
            <input type="email" class="form-control focused" id="email" placeholder="E-Mail" name="email" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label for="roles" class="col-sm-3 control-label">Roles</label>
        <div class="col-sm-9">
            <select class="form-control" id="roles" placeholder="Roles" name="roles">
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
                location.reload();
            });
            Axios.catch((error) => {
                failedResponse(error);
                UserForm.find('.modal-content').waitMeHide();
                UserForm.find('.modal').modal('hide');
            });
    });
    UserForm.find('.modal').on('hidden.bs.modal', function(){
        UserForm.find('.modal-title').html('CREATE USER');
        UserForm.find('#name').val('').prop('disabled', false);
        UserForm.find('#email').val('').prop('disabled', false);
        id = '';
    });
    @endif
    $(document).on('click', '.new-users', function(e){
        e.preventDefault();
        @if (auth()->user()->canCreateUsers())
        UserForm.find('.modal-title').html('CREATE USER');
        UserForm.attr('action', '/users');
        UserForm.find('[name="_method"]').val('POST');
        UserForm.find('#name').val('').focus();
        UserForm.find('.modal').modal('show');
        UserForm.find('#roles').callSelect2({
            ajax: true,
            url: `${window.App.APP_ROUTE}/roles`,
            modal: $('#userModal')
        });
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
                UserForm.find('#roles').callSelect2({
                    ajax: true,
                    url: `${window.App.APP_ROUTE}/roles`,
                    modal: $('#userModal')
                });
                if(response.data.data.roles.length > 0)
                    UserForm.find('#roles').setValueSelect2({
                        id: response.data.data.roles[0].id,
                        text: response.data.data.roles[0].name
                    });
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
                _this.closest('.box').waitMeShow();
                const Axios = _this.destroy(`users/${_this.data('id')}`);
                Axios.then((response) => {
                    successResponse(response.data);
                    location.reload();
                });
                Axios.catch((error) => {
                    _this.closest('.box').waitMeHide();
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