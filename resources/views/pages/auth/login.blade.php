<x-auth-layout>

    @section('title')
        Login
    @endsection

    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
        data-kt-redirect-url="{{ route('dashboard') }}" action="{{ route('login') }}">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="fw-bolder mb-3" style="color: black;">
                Log in
            </h1>
            
        </div>
        <!--begin::Heading-->

        <!--begin::Input group--->
        <div class="fv-row mb-4">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="on"
                class="form-control bg-transparent" />
            <!--end::Email-->
        </div>

        <!--end::Input group--->
        <div class="fv-row mb-3">
            <!--begin::Password-->
            <input type="password" placeholder="Password" name="password" autocomplete="off"
                class="form-control bg-transparent" />
            <!--end::Password-->
        </div>
        <!--end::Input group--->

        <!--begin::Wrapper-->
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-4">
            <div></div>

            <!--begin::Link-->
            <a href="{{ route('admin.password.request') }}" class="link-primary">
                Forgot Password ?
            </a>
            <!--end::Link-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Submit button-->
        <div class="d-grid mb-0">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                @include('partials/general/_button-indicator', ['label' => 'Log in'])
            </button>
        </div>
        <!--end::Submit button-->

    </form>
   

    @push('scripts')
        <script src="{{asset('assets/js/custom/authentication/sign-in/general.js')}}"></script>

        @if (session('info'))
            <script>
                toastr.error("{{ session('info') }}");
            </script>
        @endif
    
    @endpush


</x-auth-layout>
