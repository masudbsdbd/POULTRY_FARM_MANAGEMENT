@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite([
'node_modules/selectize/dist/css/selectize.bootstrap3.css',
'node_modules/mohithg-switchery/dist/switchery.min.css',
'node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
'node_modules/select2/dist/css/select2.min.css',
'node_modules/multiselect/css/multi-select.css'
])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'User'])

    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($user) ? route('users.store', $user->id) : route('users.store') }}"
                                method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Name</label>
                                    <input type="text" id="simpleinput" class="form-control" placeholder="Name"
                                        name="name" required
                                        value="{{ old('name', isset($user) ? $user->name : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="example-email" class="form-label">Email</label>
                                    <input type="email" id="example-email" name="email" class="form-control"
                                        placeholder="Email"
                                        value="{{ old('name', isset($user) ? $user->email : '') }}">
                                </div>

                                @if(isset($user))
                                    <div class="mb-3">
                                        <label for="old_password" class="form-label"><strong>Old Password:</strong></label>
                                        <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password" autocomplete="off">
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <div class="form-group">
                                        <strong>New Password:</strong>
                                        <input type="password" name="password" placeholder="Password" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <strong>Confirm Password:</strong>
                                        <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control" autocomplete="off">
                                    </div>
                                </div>


                                @if(isset($user))
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Select Role</label>
                                        <select id="selectize-optgroup" name="roles[]" multiple="multiple" required>
                                            @foreach ($roles as $key => $label)
                                            <option value="{{ $key }}" {{ in_array($key, $userRole) ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @else
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Select Role</label>
                                        <select id="selectize-optgroup" name="roles[]" multiple="multiple" required>
                                            @foreach ($roles as $key => $label)
                                            <option value="{{ $key }}">
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($user) ? 'Update User' : 'Add New User' }}</button>
                                </div>
                            </form>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- end row -->
</div> <!-- container -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/pages/form-advanced.init.js'])
<script>
    $(document).ready(function() {
        $('#selectize-optgroup').selectize({
            plugins: ['remove_button'],
            placeholder: 'Select roles...',
            persist: false,
            create: false
        });
    });
</script>
@endsection