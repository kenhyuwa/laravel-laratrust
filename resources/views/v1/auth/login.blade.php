@extends(__v() . '.layouts.app')

@section('content')
<div id="app" class="login-box">
    <div class="login-logo">
        <a href="/" class="typed">&nbsp;</a>
    </div>
    <div class="login-box-body">
        <form id="login" action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <span class="fa fa-envelope form-control-feedback"></span>
                <input id="email" type="email" class="form-control" name="email" autofocus="true" autocomplete="off" value="{{ app()->environment('staging') ? 'demo@laravelpos.com' : '' }}{{ app()->environment('local') ? 'ken@gmail.com' : '' }}">
                @if ($errors->has('email'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <span class="fa fa-key form-control-feedback"></span>
                <input id="password" type="password" class="form-control" name="password" value="{{ app()->environment('staging') ? 'password' : '' }}{{ app()->environment('local') ? 'password' : '' }}">
                @if ($errors->has('password'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat primary-btn">Sign In</button>
                </div>
            </div>
        </form>
        {{-- <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="{{ action('Api\oAuthController@redirectToProvider', ['provider' => 'facebook']) }}" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
            Facebook</a>
            <a href="{{ action('Api\oAuthController@redirectToProvider', ['provider' => 'google']) }}" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
            Google+</a>
        </div> --}}
        <a href="{{ route('password.request') }}" class="text-center is-block mt-15">I forgot my password</a><br>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
$(function () {
    let Login = $('form');
        Login.find('#email').focus();
        Login.find('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        }).on('ifChanged', function(e){
            const field = $(this).attr('name');
            Login.formValidation('revalidateField', field);
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
        password: {
            validators: {
                notEmpty: {}
            }
        },
        remember: {
            validators: {}
        }
    };
    Login.callFormValidation(validators)
    .on('success.form.fv', function(e) {
        e.preventDefault();
        Login.parents('#app').waitMeShow();
        let $form = $(e.target),
            fv    = $form.data('formValidation');
        axios.post(
            $form.attr('action'), $form.serialize()+'&agree='+$('#agree').is(':checked')
        ).then((r) => {
            @if (app()->environment(['local', 'production']))
                location.reload();
            @elseif(app()->environment('staging'))
                window.location.href = 'https://demo.laravel-pos.com';
            @endif
        }).catch((e) => {
            Login.parents('#app').waitMeHide();
            fv.resetForm(true);
            if(e.response.status === 422){
                if(typeof e.response.data.errors.email !== 'undefined'){
                    danger(e.response.data.errors.email[0]);
                }
            }
        });
    });
    const typed = new Typed('.typed', {
        strings: ['', 'Welcome to', window.App.APP_NAME],
        typeSpeed: 100,
        backSpeed: 30,
        backDelay: 30,
        startDelay: 0,
        loop: false,
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
    const password = new Typed('#password', {
        strings: ['********', ''],
        typeSpeed: 100,
        backSpeed: 30,
        backDelay: 30,
        startDelay: 0,
        attr: 'placeholder',
        bindInputFocusEvents: true,
        loop: true
      });
    store.clearAll();
});
</script>
@endsection