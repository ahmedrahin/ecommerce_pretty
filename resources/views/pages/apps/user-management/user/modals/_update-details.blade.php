
<div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="#" id="kt_modal_update_user_form" wire:submit.prevent="updateData">
                
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_update_user_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Update Admin Details</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        {!! getIcon('cross','fs-1') !!}
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body px-5 my-7">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_update_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_update_user_header" data-kt-scroll-wrappers="#kt_modal_update_user_scroll" data-kt-scroll-offset="300px">
                        <!--begin::User toggle-->
                        <div class="fw-bolder fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_user_info" role="button" aria-expanded="false"
                            aria-controls="kt_modal_update_user_user_info">Admin Information
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>
                        <!--end::User toggle-->
                        <!--begin::User form-->
                        <div id="kt_modal_update_user_user_info" class="collapse show">
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
                                <label class="fs-6 fw-semibold mb-2">Name</label>

                                <input type="text" class="form-control form-control-solid  @error('name') error-border  @enderror" placeholder="Enter admin name" name="name" wire:model="name" />
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">
                                    <span>Email</span>
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Email address must be active">
                                        <i class="ki-duotone ki-information fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </label>
                             
                                <input type="email" class="form-control form-control-solid  @error('email') error-border  @enderror" placeholder="Enter a valid email" name="email" wire:model="email" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Phone no.</label>

                                <input type="text" class="form-control form-control-solid  @error('phone') error-border  @enderror" placeholder="+880" name="phone" wire:model="phone" />
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                        </div>
                        <!--end::User form-->
                        <!--begin::Address toggle-->
                        <div class="fw-bolder fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_address" role="button" aria-expanded="false" aria-controls="kt_modal_update_user_address">
                            Address Details
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>
                        <!--end::Address toggle-->
                        <!--begin::Address form-->
                        <div id="kt_modal_update_user_address" class="collapse show">
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Address Line 1</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="Address Line 1" name="address1" wire:model="address_line1" />
                                <!--end::Input-->
                            </div>
                            
                            <div class="d-flex flex-column mb-7 fv-row">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">District</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="language" aria-label="Select a district" data-control="select2" data-placeholder="Select a district..." class="form-select form-select-solid"
                                    data-dropdown-parent="#kt_modal_update_details" wire:model="division_id">
                                    <option></option>
                                    @foreach( $districts as $district )
                                        <option value="{{$district->id}}">{{$district->name}} - {{$district->bn_name}}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            
                            <div class="row g-9 mb-7">
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">State / City</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="State / City" name="state" wire:model="address_line2" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2">Post Code</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="Post Code" name="zipCode" wire:model="zipCode" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            
                               
                        
                        </div>
                        <!--end::Address form-->
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <!--end::Button-->
                    <button type="updateData" class="btn btn-primary" data-kt-brand-modal-action="updateData" style="width: 200px !important;">
                        <span class="indicator-label" wire:loading.remove wire:target="updateData">Save Changes</span>
                        <span class="indicator-progress" wire:loading wire:target="updateData">
                            Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>