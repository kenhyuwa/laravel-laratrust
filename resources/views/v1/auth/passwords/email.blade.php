@extends(__v() . '.layouts.app')

@section('content')
<div id="app" class="login-box">
    <div class="login-logo">
        <a href="/">{{ __app_name() }}</a>
    </div>
    <div class="login-box-body">
        <div class="alert" role="alert" style="display: none;"></div>
        @if (session('status'))
            <div class="alert alert-info" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('password.email') }}" method="post">
            @csrf
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <span class="fa fa-envelope form-control-feedback"></span>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus autocomplete="off">
                @if ($errors->has('email'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat primary-btn">Send Password Reset Link</button>
                </div>
            </div>
        </form>
        <a href="{{ route('login') }}" class="text-center is-block mt-15">Sign in</a><br>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
        @endif
    </div>
</div>
@endsection

{{-- @section('js')
<script>
$(function () {
    let ResetPassword = $('form');
        ResetPassword.find('#email').focus();
        ResetPassword.find('#email').focus(function(){
            $(this).parent().pulsate({
                color: '#7F00FF',
                pause: 1000,
             });
        });
    let validators = {
        email: {
            validators: {
                notEmpty: {},
                regexp:{
                    regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                }
            }
        },
    };
    ResetPassword.callFormValidation(validators)
    .on('success.form.fv', async function(e){
        e.preventDefault();
        ResetPassword.parents('#app').waitMeShow();
        let $form = $(e.target),
            fv    = $form.data('formValidation');
            try{
                const { response } = await axios.post($form.attr('action'), $form.serialize());
                $('.alert').css('display', 'block');
                $('.alert').addClass('alert-info');
                $('.alert').text(response.message);
                ResetPassword.parents('#app').waitMeHide();
            }catch(e){console.log(e);
                $('.alert').css('display', 'block');
                $('.alert').addClass('alert-danger');
                $('.alert').text(e.message);
                ResetPassword.parents('#app').waitMeHide();
                fv.resetForm(true);
                throw new Error(e);
            }
    });
    const email = new Typed('#email', {
        strings: ['yourmail@'+window.App.APP_NAME.replace(/\s/g, '').toLowerCase()+'.com', ''],
        typeSpeed: 100,
        backSpeed: 30,
        backDelay: 30,
        startDelay: 0,
        attr: 'placeholder',
        bindInputFocusEvents: true,
        loop: true
    });
});
</script>
@endsection --}}