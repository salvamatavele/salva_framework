@php
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header('location: ' . $_SERVER['HTTP_REFERER']);
}
@endphp
@extends('layouts.app')
@section('content')
    <div class="uk-flex uk-flex-center uk-flex-middle uk-background-muted uk-height-viewport" id="sign"
        data-uk-height-viewport>
        <div class="uk-position-bottom-center uk-position-small uk-visible@m uk-position-z-index">
            <span class="uk-text-small uk-text-muted">© 2022 All right reserved - <a
                    href="https://github.com/salvamatavele/salva_framework">GitHub</a> | Built with <a
                    href="http://getuikit.com" title="Visit UIkit 3 site" target="_blank" data-uk-tooltip><span
                        data-uk-icon="uikit"></span></a></span>
        </div>
        <div class="uk-width-medium uk-padding-small">
            <!-- login -->
            <form class="toggle-class" method="POST" @submit.prevent="onLogin">
                <fieldset class="uk-fieldset">
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                            <input class="uk-input uk-border-pill" required placeholder="E-mail" type="email"
                                v-model="email">
                        </div>
                    </div>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
                            <input id="password" class="uk-input uk-border-pill" required placeholder="Password"
                                type="password" v-model="password">
                        </div>
                    </div>
                    <div class="uk-margin-small">
                        <label><input class="uk-checkbox" v-model="remember" type="checkbox"> Keep me logged in</label>
                    </div>
                    <div class="uk-margin-bottom">
                        <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">LOG
                            IN<div v-if="loading" uk-spinner></div></button>
                    </div>
                </fieldset>
            </form>
            <!-- /login -->
            <!-- register -->
            <form class=" toggle-class" method="POST" @submit.prevent='onRegister' hidden>
                <fieldset class="uk-fieldset">
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                            <input class="uk-input uk-border-pill" required placeholder="Name" type="text" v-model="name">
                        </div>
                    </div>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: mail"></span>
                            <input class="uk-input uk-border-pill" required placeholder="E-mail" type="email"
                                v-model="email">
                        </div>
                    </div>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
                            <input id="password" class="uk-input uk-border-pill" required placeholder="Password"
                                type="password" v-model="password">
                        </div>
                    </div>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
                            <input id="password" class="uk-input uk-border-pill" required placeholder=" Confirm Password"
                                type="password" v-model="confirm_password">
                        </div>
                    </div>
                    <div class="uk-margin-small">
                        <label><input class="uk-checkbox" type="checkbox" v-model="terms"> Agree with terms and
                            conditions.</label>
                    </div>
                    <div class="uk-margin-bottom">
                        <button type="submit"
                            class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Register<div v-if="loading1" uk-spinner></div></button>
                    </div>
                </fieldset>
            </form>
            <!-- /register -->

            <!-- recover password -->
            <!-- This is the modal with the default close button -->
            <div id="modal-close-default" uk-modal>
                <div class="uk-modal-dialog uk-modal-body">
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                    <h2 class="uk-modal-title">Reset password</h2>
                    <form class="toggle" method="POST" @submit.prevent='onSubmitSend'>
                        <div class="uk-margin-small">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: mail"></span>
                                <input class="uk-input uk-border-pill" placeholder="E-mail" required type="text"
                                    v-model='email'>
                            </div>
                        </div iv>
                        <div class="uk-margin-bottom">
                            <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">SEND
                                <div v-if="loading2" uk-spinner></div></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /recover password -->

            <!-- action buttons -->
            <div>
                <div class="uk-text-center">
                    <a class="uk-link-reset uk-text-small "
                        data-uk-toggle="target: #modal-close-default; animation: uk-animation-fade">Forgot your
                        password?</a>
                    <a class="uk-link-reset uk-text-small toggle-class"
                        data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade" hidden><span
                            data-uk-icon="arrow-left"></span> Back to Login</a>
                </div>
                <a class="uk-link uk-text-small toggle-class"
                    data-uk-toggle="target: .toggle-class ;animation: uk-animation-fade">Dont have acount? Sign Up</a>
            </div>
            <!-- action buttons -->
        </div>

    </div>
@endsection

@push('js')
    <script>

        var app = new Vue({
            el: '#sign',
            data: {
                name: '',
                email: '',
                password: '',
                confirm_password: '',
                remember: '',
                terms: '',
                loading: false,
                loading1: false,
                loading2: false,
                user: [],
            },
            
            methods: {
                onLogin() {
                    if (this.email !== '' && this.password !== '') {
                        var formData = new FormData()
                        formData.append('email', this.email)
                        formData.append('password', this.password)
                        this.loading =! false
                        axios({
                                method: 'post',
                                url: "{{ $router->route('auth') }}",
                                data: formData
                            })
                            .then(response => {
                                this.loading =! true
                                if (response.data.status == 'success') {
                                    if (response.data.message) {
                                        Toast.fire({
                                            icon: 'success',
                                            title: response.data.message,
                                        })
                                        setTimeout("window.location = '{{ $_SERVER['HTTP_REFERER'] }}';",
                                            800);
                                    }
                                } else {
                                    if (response.data.errors) {
                                        var errors = response.data.errors
                                        let text = ''
                                        for (let i in errors) {
                                            text += errors[i] + '\n';

                                        }
                                        Toast.fire({
                                            icon: 'error',
                                            title: text,
                                        })
                                    }
                                    if (response.data.message) {
                                        Toast.fire({
                                            icon: 'error',
                                            title: response.data.message,
                                        })
                                    }

                                }
                            })
                            .catch(error => {
                                console.log(error)
                            })

                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'All inputs is required field',
                        })
                    }
                },
                onRegister() {
                    if (this.name !== '' && this.email !== '' && this.password !== '') {
                        var formData = new FormData()
                        formData.append('name', this.name)
                        formData.append('email', this.email)
                        formData.append('password', this.password)
                        formData.append('confirm_password', this.confirm_password)
                        formData.append('terms', this.terms)
                        this.loading1 =! false
                        axios({
                                method: 'post',
                                url: "{{ $router->route('register') }}",
                                data: formData
                            })
                            .then(response => {
                                this.loading1 =! true
                                if (response.data.status == 'success') {
                                    if (response.data.message) {
                                        Toast.fire({
                                            icon: 'success',
                                            title: response.data.message,
                                        })
                                        setTimeout("window.location = '{{ $router->route('login') }}';",
                                            1000);
                                    }
                                } else {
                                    if (response.data.errors) {
                                        var errors = response.data.errors
                                        let text = ''
                                        for (let i in errors) {
                                            text += errors[i] + '\n';

                                        }
                                        Toast.fire({
                                            icon: 'error',
                                            title: text,
                                        })
                                    }
                                    if (response.data.message) {
                                        Toast.fire({
                                            icon: 'error',
                                            title: response.data.message,
                                        })
                                    }

                                }
                            })
                            .catch(error => {
                                console.log(error)
                            })

                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'All inputs is required field',
                        })
                    }
                },
                onSubmitSend() {
                    if (this.email !== '') {
                        var formData = new FormData()
                        formData.append('email', this.email)
                        this.loading2 =! false
                        axios({
                                method: 'post',
                                url: "{{ $router->route('password') }}",
                                data: formData
                            })
                            .then(response => {
                                this.loading2 =! true
                                if (response.data.status == 'success') {
                                    if (response.data.message) {
                                        Toast.fire({
                                            icon: 'success',
                                            title: response.data.message,
                                        })
                                    }
                                } else {
                                    if (response.data.errors) {
                                        var errors = response.data.errors
                                        let text = ''
                                        for (let i in errors) {
                                            text += errors[i] + '\n';

                                        }
                                        Toast.fire({
                                            icon: 'error',
                                            title: text,
                                        })
                                    }
                                    if (response.data.message) {
                                        Toast.fire({
                                            icon: 'error',
                                            title: response.data.message,
                                        })
                                    }

                                }
                            })
                            .catch(error => {
                                console.log(error)
                            })
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'The email is required field',
                        })
                    }
                }
            },
        });
    </script>
@endpush
