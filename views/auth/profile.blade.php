@extends('layouts.app')
@section('content')
    <div class="uk-container uk-flex-auto top-container uk-position-relative uk-margin-xlarge-top" id="profile">
        <h3 class="uk-heading-bullet">Profile</h3>
        <div class="uk-flex uk-flex-center@m uk-flex-right@l">
            <div class="uk-card  uk-inline">
                <div uk-lightbox>
                    <a v-if='image' class="" v-bind:href="image">
                        <img class="uk-border-circle" width="200" height="200" :src="image" alt="Photo">
                    </a>
                    <a v-else class="" v-bind:href="avatar">
                        <img class="uk-border-circle" width="200" height="200" :src="avatar" alt="Photo">
                    </a>
                </div>
                <div class="uk-position-bottom uk-overlay uk-overlay-default uk-flex uk-flex-center" style="height: 10px;">
                    <form method="POST" @submit.prevent='onUpload' enctype="multipart/form-data">
                        <div class="uk-margin">
                            <div v-if='loading' uk-spinner></div>
                            <div uk-form-custom>
                                <input type="file" id="photo" ref="photo">
                                <span class="uk-icon-button uk-text-danger" uk-icon="cloud-upload"></span>
                            </div>
                            <button title="upload" type="submit" class="uk-text-middle uk-button uk-button-link">
                                <span class="uk-icon-button uk-background-muted" uk-icon="upload"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="uk-flex uk-flex-wrap uk-flex-wrap-around uk-background-muted">
            <div class="uk-width-1-1 "><br></div>
            <div class="uk-width-auto  uk-card-body uk-card-xsmall">
                <h3>User information</h3>
                You can update you information here
            </div>
            <div class="uk-width-expand@m uk-width-auto@s uk-card uk-card-default uk-card-body uk-card-medium">
                <form method="POST" @submit.prevent='onUpdate'>
                    <fieldset class="uk-fieldset">
                        <div class="uk-margin">
                            <label class="uk-form-label">Name</label>
                            <input class="uk-input" type="text" placeholder="Name..." v-model='name'>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label">Email</label>
                            <input class="uk-input" type="email" placeholder="Email..." v-model='email'>
                        </div>
                        <div class="uk-position-bottom-right uk-margin-medium-right">
                            <button type="submit" class="uk-button uk-button-primary">send<div v-if='loading1' uk-spinner>
                                </div></button>
                        </div>
                    </fieldset>
                </form>
            </div><br>
            <div class="uk-width-1-1  uk-card-body "></div>
            <div class="uk-width-auto  uk-card-body uk-card-xsmall">
                <h3>Password</h3>
                You can update you password acount here.
            </div>
            <div class="uk-width-expand@m uk-width-auto@s uk-card uk-card-default uk-card-body uk-card-medium">
                <form method="POST" @submit.prevent='onChangePassword'>
                    <fieldset class="uk-fieldset">
                        <div class="uk-margin">
                            <label class="uk-form-label">Last password</label>
                            <input class="uk-input" type="password" placeholder="Last Password" v-model='password'>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label">New password</label>
                            <input class="uk-input" type="password" placeholder="New password" v-model='new_password'>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label">Confirm password</label>
                            <input class="uk-input" type="password" placeholder="Confirm password"
                                v-model='confirm_password'>
                        </div>
                        <div class="uk-position-bottom-right uk-margin-medium-right">
                            <button type="submit" class="uk-button uk-button-primary">send<div v-if='loading2' uk-spinner>
                                </div></button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="uk-width-2-6 uk-card uk-card-default uk-card-body uk-card-medium">
                <button type="submit" class="uk-button uk-button-danger" @click='onDelete'>Delete acount<div v-if='loading3'
                        uk-spinner></div>
                </button>
            </div>
        </div>

        <div class="uk-position-top-center uk-overlay "><span v-if='loading4' uk-spinner="ratio: 4.5"></span></div>
    </div>
@endsection
@push('js')
    <script>
        var app = new Vue({
            el: '#profile',
            data() {
                return {
                    name: '',
                    email: '',
                    password: '',
                    new_password: '',
                    confirm_password: '',
                    image: '',
                    avatar: '',
                    photo: '',
                    loading: false,
                    loading1: false,
                    loading2: false,
                    loading3: false,
                    loading4: false,
                }
            },
            mounted() {
                this.loading4 = true
                this.getUser()
            },
            methods: {
                onUpload() {
                    this.loading = true;
                    this.photo = this.$refs.photo.files[0];
                    var formData = new FormData();
                    formData.append('photo', this.photo);
                    formData.append('_method', 'PUT');
                    axios.post("{{ $router->route('profile.upload') }}", formData,
                    {
                        headers:{ 'Content-Type': 'multipart/form-data'}
                    })
                        .then(res => {
                            this.loading = false

                            if (res.data.status == 'success') {
                                console.log(res)
                                Toast.fire({
                                    icon: 'success',
                                    title: res.data.message,
                                })
                                this.getUser()
                            } else {
                                if (res.data.errors) {
                                    var errors = res.data.errors
                                    let text = ''
                                    for (let i in errors) {
                                        text += errors[i] + '\n';

                                    }
                                    Toast.fire({
                                        icon: 'error',
                                        title: text,
                                    })
                                }
                                if (res.data.message) {
                                    Toast.fire({
                                        icon: 'error',
                                        title: res.data.message,
                                    })
                                }

                            }
                        })
                        .catch(err => {
                            console.error(err);
                        })
                },
                onUpdate() {
                    if (this.name !== '' && this.email !== '') {
                        this.loading1 = true
                        var formData = new FormData()
                        formData.append('name', this.name)
                        formData.append('email', this.email)
                        formData.append('_method', 'PUT')
                        axios.post("{{ $router->route('profile.update') }}", formData)
                            .then(res => {
                                this.loading1 = false

                                if (res.data.status == 'success') {
                                    console.log(res)
                                    Toast.fire({
                                        icon: 'success',
                                        title: res.data.message,
                                    })
                                    this.getUser()
                                } else {
                                    if (res.data.errors) {
                                        var errors = res.data.errors
                                        let text = ''
                                        for (let i in errors) {
                                            text += errors[i] + '\n';

                                        }
                                        Toast.fire({
                                            icon: 'error',
                                            title: text,
                                        })
                                    }
                                    if (res.data.message) {
                                        Toast.fire({
                                            icon: 'error',
                                            title: res.data.message,
                                        })
                                    }

                                }
                            })
                            .catch(err => {
                                console.error(err);
                            })
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'All fields is required.',
                        })
                    }
                },
                onChangePassword() {
                    if (this.password !== '' && this.new_password !== '' && this.confirm_password !== '') {
                        this.loading2 = true
                        var formData = new FormData()
                        formData.append('password', this.password)
                        formData.append('new_password', this.new_password)
                        formData.append('confirm_password', this.confirm_password)
                        formData.append('_method', 'PUT')
                        axios.post("{{ $router->route('profile.change.password') }}", formData)
                            .then(res => {
                                this.loading2 = false

                                if (res.data.status == 'success') {
                                    console.log(res)
                                    Toast.fire({
                                        icon: 'success',
                                        title: res.data.message,
                                    })
                                    setTimeout("window.location = '{{ $router->route('logout') }}';",
                                        3000);
                                } else {
                                    if (res.data.errors) {
                                        var errors = res.data.errors
                                        let text = ''
                                        for (let i in errors) {
                                            text += errors[i] + '\n';

                                        }
                                        Toast.fire({
                                            icon: 'error',
                                            title: text,
                                        })
                                    }
                                    if (res.data.message) {
                                        Toast.fire({
                                            icon: 'error',
                                            title: res.data.message,
                                        })
                                    }

                                }
                            })
                            .catch(err => {
                                console.error(err);
                            })
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'All fields is required.',
                        })
                    }
                },
                onDelete() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this! This meaning you acount will be removed in this system.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.loading3 = true
                            var formData = new FormData()
                            formData.append('_method', 'DELETE')
                            axios.post("{{ $router->route('profile.destroy') }}", formData)
                                .then(res => {
                                    this.loading3 = false

                                    if (res.data.status == 'success') {
                                        console.log(res)
                                        Toast.fire({
                                            icon: 'success',
                                            title: res.data.message,
                                        })
                                        setTimeout(
                                            "window.location = '{{ $router->route('logout') }}';",
                                            3000);
                                    } else {
                                        if (res.data.message) {
                                            Toast.fire({
                                                icon: 'error',
                                                title: res.data.message,
                                            })
                                        }

                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                })
                        }
                    })
                },
                getUser() {
                    axios.get("{{ $router->route('profile.show') }}")
                        .then(res => {
                            this.loading4 = false
                            this.name = res.data.user.name
                            this.email = res.data.user.email
                            this.avatar = res.data.user.avatar
                            this.image = res.data.user.image
                        })
                        .catch(err => {
                            console.error(err);
                        })
                }
            },
        });
    </script>
@endpush
