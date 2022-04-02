
@extends('layouts.app')
@section('content')
    <div class="uk-flex uk-flex-center uk-flex-middle uk-background-muted uk-height-viewport" id="reset"
        data-uk-height-viewport>
        <div class="uk-position-bottom-center uk-position-small uk-visible@m uk-position-z-index">
            <span class="uk-text-small uk-text-muted">© 2021 All right reserved - <a
                    href="https://github.com/zzseba78/Kick-Off">salva|framework</a> | Built with <a
                    href="http://getuikit.com" title="Visit UIkit 3 site" target="_blank" data-uk-tooltip><span
                        data-uk-icon="uikit"></span></a></span>
        </div>
        <div class="uk-width-medium uk-padding-small">
            <!-- login -->
            <form class="toggle-class" method="POST" @submit.prevent="onReset">
                <fieldset class="uk-fieldset">
                    <input type="hidden" value="PUT" v-model='method'>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
                            <input id="password" class="uk-input uk-border-pill" required placeholder="New Password"
                                type="password" v-model="password">
                        </div>
                    </div>
                    <div class="uk-margin-small">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
                            <input id="password" class="uk-input uk-border-pill" required placeholder="Confirm Password"
                                type="password" v-model="confirm_password">
                        </div>
                    </div>
                    <div class="uk-margin-bottom">
                        <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Reset Password<div v-if="loading" uk-spinner></div></button>
                    </div>
                </fieldset>
            </form>
            <!-- /login -->
            
        </div>

    </div>
@endsection

@push('js')
    <script>

        const app = new Vue({
            el: '#reset',
            data: {
                password: '',
                confirm_password: '',
                method: 'PUT',
                loading: false
            },

            methods: {
                onReset() {
                    if (this.confirm_password !== '' && this.password !== '') {
                        const formData = new FormData();
                        formData.append('confirm_password', this.confirm_password)
                        formData.append('password', this.password)
                        formData.append('_method', this.method)
                        this.loading = !false
                        axios({
                            method: 'post',
                            url: "{{ $router->route('password.store') }}",
                            data: formData
                        })
                            .then(response => {
                                this.loading = !true
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
                                        const errors = response.data.errors;
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
                }
            },
        });
    </script>
@endpush
