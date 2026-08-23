<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_user_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ $edit_mode ? 'Edit Admin' : 'Add Admin' }}</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross', 'fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_user_form" class="form" action="#" wire:submit.prevent="submit"
                    enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_user_header"
                        data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="height: 300px;">
                        
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="d-block fw-semibold fs-6 mb-3">Profile</label>
                            <!--end::Label-->
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline {{ $image || $current_image ? '' : 'image-input-empty' }}" data-kt-image-input="true">
                                <!--begin::Preview existing avatar-->
                                @if ($image)
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $image->temporaryUrl() }}');"></div>
                                @elseif ($current_image)
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset($current_image) }}');"></div>
                                @else
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});"></div>
                                @endif
                                <!--end::Preview existing avatar-->
                                
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change image">
                                    {!! getIcon('pencil', 'fs-7') !!}
                                    <!--begin::Inputs-->
                                    <input type="file" wire:model.defer="image" name="image" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="avatar_remove" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                    {!! getIcon('cross', 'fs-2') !!}
                                </span>
                                <!--end::Cancel-->

                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove image" wire:click="removeImage">
                                    {!! getIcon('cross', 'fs-2') !!}
                                </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="name" name="name"
                                class="form-control form-control-solid mb-3 mb-lg-0 @error('name') error-border  @enderror" placeholder="Full name" />
                            <!--end::Input-->
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Email</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="email" name="email" {{ $edit_mode ? 'readonly' : '' }}
                                class="form-control form-control-solid mb-3 mb-lg-0 @error('email') error-border  @enderror" placeholder="example@domain.com" />
                            <!--end::Input-->
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        @if( !$edit_mode )
                            <div class="fv-row mb-7">
                                <label class="{{ $edit_mode ? '' : 'required'}} fw-semibold fs-6 mb-2">Password</label>
                                <input type="password" wire:model.defer="password" name="password"
                                    class="form-control form-control-solid mb-3 mb-lg-0  @error('password') error-border  @enderror"
                                    placeholder="Enter your password" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        @if( $user_id !== 1 )
                            <div class="mb-7">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-5">Role</label>
                                <!--end::Label-->
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <!--begin::Roles-->
                                
                                    @foreach ($roles as $role)
                                        <div class="d-flex fv-row">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input me-3"
                                                    id="kt_modal_update_role_option_{{ $role->id }}"
                                                    wire:model.defer="role" name="role" type="radio"
                                                    value="{{ $role->name }}" />
                                                <label class="form-check-label"
                                                    for="kt_modal_update_role_option_{{ $role->id }}">
                                                    <div class="fw-bold text-gray-800">
                                                        {{ ucwords($role->name) }}
                                                    </div>
                                                    <div class="text-gray-600">
                                                        {{ $role->description }}
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @if (!$loop->last)
                                            <div class='separator separator-dashed my-5'></div>
                                        @endif
                                    @endforeach
                                <!--end::Roles-->
                            </div>
                        @else
                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                                <!--begin::Icon-->
                                {!! getIcon('information','fs-2tx text-warning me-4') !!}
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-semibold">
                                        <div class="fs-6 text-gray-700">
                                            <strong class="me-1">Warning!</strong> No any admin cannot be change this admin role. this was set by defualt and fixed. and also cannot be delete.
                                        </div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                        @endif
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    
                    <div class="text-center pt-12">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-kt-brand-modal-action="submit" style="width: 200px !important;">
                            @if( !$edit_mode )
                                <span class="indicator-label" wire:loading.remove wire:target="submit">Add Admin</span>
                            @else
                                <span class="indicator-label" wire:loading.remove wire:target="submit">Save Changes</span>
                            @endif
                            <span class="indicator-progress" wire:loading wire:target="submit">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>

@push('scripts')
<script>
    const modal = document.querySelector('#kt_modal_add_user');
    modal.addEventListener('show.bs.modal', (e) => {
        Livewire.emit('open_add_modal');
    });
</script>
@endpush
