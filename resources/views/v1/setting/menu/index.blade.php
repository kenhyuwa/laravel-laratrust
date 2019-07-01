@extends(__v() . '.layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12 can-focus">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ str_title() }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" id="_btn_expand_collapse" class="btn btn-sm btn-flat btn-default" data-toggle="tooltip" title="Collapse All">{{ __('Collapse All') }}</button>
                        {{ box_collapse('collapse') }}
                        {{ box_remove('remove') }}
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dd {{ auth()->user()->canStoreMenu() ? 'dd-menu' : '' }} nestable-with-handle">
                                <ol class="dd-list">
                                    {{ nestable_render($nav->toArray(), '') }}
                                </ol>
                            </div>
                        </div>
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
    $('.dd').nestable().nestable('collapseAll');
    $('.dd').on('change', function (e) {
        e.stopPropagation();
        const $this = $(this);
        const serializedData = window.JSON.stringify($($this).nestable('serialize'));
        axios.post('/menu', {request: serializedData})
        .then((response) => {
            successResponse(response.data);
        })
        .catch((error) => {
            failedResponse(error);
        });
    });
    $(document).off('click', '#_btn_expand_collapse')
        .on('click', '#_btn_expand_collapse', function(e){
        const text = $(this).text() === 'Expand All';
        const title = $(this).attr('data-original-title') === 'Expand All';
        $(this).text(text ? 'Collapse All' : 'Expand All');
        $(this).attr('data-original-title', title ? 'Collapse All' : 'Expand All');
        $('.dd').nestable(text ? 'collapseAll' : 'expandAll');
        e.stopPropagation();
        $('#thumbs,#insideholder,#buttonsH').toggle();
    });
    @if(auth()->user()->canStoreMenu())
    function createForm(data){
        let e = '';
        e += '<div class="col-md-4">';
            e += '<form id="menuUpdate" class="form-horizontal" action="'+ window.App.APP_ROUTE+'/'+ data.id +'" method="POST">';
                e += '@csrf';
                e += '@method("PATCH")';
                e += '<div class="box-body">';
                    e += '<div class="form-group">';
                        e += '<label for="en_name" class="col-sm-4 control-label">EN Name</label>';
                        e += '<div class="col-sm-8">';
                            e += '<input type="text" class="form-control" id="en_name" placeholder="EN Name" name="en_name" autocomplete="off" value="'+ data.en_name +'">';
                        e += '</div>';
                    e += '</div>';
                    e += '<div class="form-group">';
                        e += '<label for="id_name" class="col-sm-4 control-label">ID Name</label>';
                        e += '<div class="col-sm-8">';
                            e += '<input type="text" class="form-control" id="id_name" placeholder="ID Name" name="id_name" autocomplete="off" value="'+ data.id_name +'">';
                        e += '</div>';
                    e += '</div>';
                    e += '<div class="form-group">';
                        e += '<label for="icon" class="col-sm-4 control-label">Icon</label>';
                        e += '<div class="col-sm-8">';
                            e += '<input type="text" class="form-control" id="icon" placeholder="Icon" name="icon" autocomplete="off" value="'+ data.icon +'">';
                        e += '</div>';
                    e += '</div>';
                    e += '<div class="form-group">';
                        e += '<div class="col-sm-offset-2 col-sm-10">';
                            e += '{{ loading_button("info", "save") }}';
                        e += '</div>';
                    e += '</div>';
               e += '</div>';
            e += '</form>';
        e += '</div>';
        return e;
    }
    $('.to-update').on('click', function(){
        const Form = $('#menuUpdate');
        const validatorFields = {
            en_name: {
                validators: {
                    notEmpty: {},
                }
            },
            id_name: {
                validators: {
                    notEmpty: {}
                }
            },
            icon: {
                validators: {
                    notEmpty: {}
                }
            },
        };
        const el = $(this).parents('.dd');
        const id = $(this).parents('li').data('id');
        const Axios = axios.get(`/menu/${id}/edit`);
        el.parents('.box-body').find('form').waitMeShow();
            Axios.then((response) => {
                el.parents('.box-body').find('.col-md-4').detach();
                el.parent().addClass('col-md-8').removeClass('col-md-12');
                el.parents('.box-body').children().append(createForm(response.data.data));
                $('#menuUpdate').callFormValidation(validatorFields)
                .on('success.form.fv', function(e) {
                    e.preventDefault();
                    $('#menuUpdate').waitMeShow();
                    $('.btn-loading').loading(true);
                    const $form = $(e.target),
                        fv    = $form.data('formValidation');
                    const Axios = axios.patch(`/menu/${id}`, $form.serialize());
                    Axios.then((response) => {
                        successResponse(response.data);
                        window.location.href = window.App.APP_ROUTE;
                        $('#menuUpdate').waitMeHide();
                    });
                    Axios.catch((error) => {
                        failedResponse(error);
                        $('#menuUpdate').waitMeHide();
                    });
                });
            });
            Axios.catch((error) => {
                failedResponse(error);
                el.parents('.box-body').find('.col-md-4').detach();
            });
    });
    @endif
</script>
@endsection