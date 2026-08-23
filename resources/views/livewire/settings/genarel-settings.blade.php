<div>
    <form class="form" wire:submit.prevent="update">
        <!--begin::Card body-->
        <div class="card-body border-top p-9 py-4 pt-8">
            <!--begin::Input group-->
            <div class="row mb-6">
                <div class="col-md-2 col-6">
                    <label class="col-lg-12 col-form-label fw-semibold fs-6">Dashboard Logo</label>

                   <div class="col-lg-12">
                        <div class="image-input image-input-outline {{ $logo || $current_image ? '' : 'image-input-empty' }}"
                            data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            @if ($logo)
                                @if($logo->getClientOriginalExtension() === 'svg')
                                    <img src="{{ $logo->temporaryUrl() }}" class="w-125px h-125px" alt="SVG Logo">
                                @else
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="background-image: url('{{ $logo->temporaryUrl() }}');"></div>
                                @endif
                            @elseif ($current_image)
                                @if (pathinfo($current_image, PATHINFO_EXTENSION) === 'svg')
                                    <img src="{{ asset($current_image) }}" class="w-125px h-125px" alt="SVG Logo">
                                @else
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="background-image: url('{{ asset($current_image) }}');"></div>
                                @endif
                            @else
                                <div class="image-input-wrapper w-125px h-125px"
                                    style="background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});">
                                </div>
                            @endif
                            <!--end::Preview existing avatar-->

                            <!--begin::Label-->
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change logo">
                                {!! getIcon('pencil', 'fs-7') !!}
                                <!--begin::Inputs-->
                                <input type="file" wire:model.defer="logo" name="logo" accept=".png, .jpg, .jpeg, .svg" />
                                <input type="hidden" name="avatar_remove" />
                                <!--end::Inputs-->
                            </label>
                            <!--end::Label-->

                            <!--begin::Cancel-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                {!! getIcon('cross', 'fs-2') !!}
                            </span>
                            <!--end::Cancel-->

                            <!--begin::Remove-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove logo"
                                wire:click="removeLogo">
                                {!! getIcon('cross', 'fs-2') !!}
                            </span>
                            <!--end::Remove-->
                        </div>
                    </div>

                </div>

                <div class="col-md-2 col-6">
                    <label class="col-lg-12 col-form-label fw-semibold fs-6">Fav Icon</label>

                    <div class="col-lg-12">
                        <div class="image-input image-input-outline {{ $fav_icon || $current_favIcon ? '' : 'image-input-empty' }}"
                            data-kt-image-input="true">
                            <!--begin::Preview existing avatar-->
                            @if ($fav_icon)
                                @if($fav_icon->getClientOriginalExtension() === 'svg')
                                    <img src="{{ $fav_icon->temporaryUrl() }}" class="w-125px h-125px" alt="SVG Icon">
                                @else
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="background-image: url('{{ $fav_icon->temporaryUrl() }}');"></div>
                                @endif
                            @elseif ($current_favIcon)
                                @if (pathinfo($current_favIcon, PATHINFO_EXTENSION) === 'svg')
                                    <img src="{{ asset($current_favIcon) }}" class="w-125px h-125px" alt="SVG Icon">
                                @else
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="background-image: url('{{ asset($current_favIcon) }}');"></div>
                                @endif
                            @else
                                <div class="image-input-wrapper w-125px h-125px"
                                    style="background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});">
                                </div>
                            @endif
                            <!--end::Preview existing avatar-->

                            <!--begin::Label-->
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change fav icon">
                                {!! getIcon('pencil', 'fs-7') !!}
                                <!--begin::Inputs-->
                                <input type="file" wire:model.defer="fav_icon" name="fav_icon" accept=".png, .jpg, .jpeg, .svg" />
                                <input type="hidden" name="avatar_remove" />
                                <!--end::Inputs-->
                            </label>
                            <!--end::Label-->

                            <!--begin::Cancel-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel fav icon">
                                {!! getIcon('cross', 'fs-2') !!}
                            </span>
                            <!--end::Cancel-->

                            <!--begin::Remove-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove fav icon"
                                wire:click="removeFavIcon">
                                {!! getIcon('cross', 'fs-2') !!}
                            </span>
                            <!--end::Remove-->
                        </div>
                    </div>

                </div>

                <div class="col-md-2 col-6">
                    <label class="col-lg-12 col-form-label fw-semibold fs-6">Website Logo</label>
                    <div class="col-lg-12">
                        <div class="image-input image-input-outline {{ $website_logo || $current_websiteLogo ? '' : 'image-input-empty' }}" data-kt-image-input="true">
                            @if ($website_logo)
                                <img src="{{ $website_logo->temporaryUrl() }}" class="w-125px h-125px" alt="Website Logo">
                            @elseif ($current_websiteLogo)
                                <img src="{{ asset($current_websiteLogo) }}" class="w-125px h-125px" alt="Website Logo">
                            @else
                                <div class="image-input-wrapper w-125px h-125px"
                                     style="background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});"></div>
                            @endif
                
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                   data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change website logo">
                                {!! getIcon('pencil', 'fs-7') !!}
                                <input type="file" wire:model.defer="website_logo" name="website_logo" accept=".png, .jpg, .jpeg, .svg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove website logo"
                                  wire:click="removeWebsiteLogo">
                                {!! getIcon('cross', 'fs-2') !!}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <label class="col-lg-12 col-form-label fw-semibold fs-6">Footer Logo</label>
                    <div class="col-lg-12">
                        <div class="image-input image-input-outline {{ $website_footer_logo || $current_footerLogo ? '' : 'image-input-empty' }}" data-kt-image-input="true">
                            @if ($website_footer_logo)
                                <img src="{{ $website_footer_logo->temporaryUrl() }}" class="w-125px h-125px" alt="Footer Logo">
                            @elseif ($current_footerLogo)
                                <img src="{{ asset($current_footerLogo) }}" class="w-125px h-125px" alt="Footer Logo">
                            @else
                                <div class="image-input-wrapper w-125px h-125px"
                                     style="background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});"></div>
                            @endif
                
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                   data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change footer logo">
                                {!! getIcon('pencil', 'fs-7') !!}
                                <input type="file" wire:model.defer="website_footer_logo" name="website_footer_logo" accept=".png, .jpg, .jpeg, .svg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove footer logo"
                                  wire:click="removeFooterLogo">
                                {!! getIcon('cross', 'fs-2') !!}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 col-6">
                    <label class="col-lg-12 col-form-label fw-semibold fs-6">Dark Logo</label>
                    <div class="col-lg-12">
                        <div class="image-input image-input-outline {{ $dark_logo || $current_darkLogo ? '' : 'image-input-empty' }}" data-kt-image-input="true">
                            @if ($dark_logo)
                                <img src="{{ $dark_logo->temporaryUrl() }}" class="w-125px h-125px" alt="Dark Logo">
                            @elseif ($current_darkLogo)
                                <img src="{{ asset($current_darkLogo) }}" class="w-125px h-125px" alt="Dark Logo">
                            @else
                                <div class="image-input-wrapper w-125px h-125px"
                                     style="background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});"></div>
                            @endif
                
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                   data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change dark logo">
                                {!! getIcon('pencil', 'fs-7') !!}
                                <input type="file" wire:model.defer="dark_logo" name="dark_logo" accept=".png, .jpg, .jpeg, .svg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove dark logo"
                                  wire:click="removeDarkLogo">
                                {!! getIcon('cross', 'fs-2') !!}
                            </span>
                        </div>
                    </div>
                </div>
                
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6 pt-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Site Title</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="title"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('title') error-border @enderror"
                                placeholder="Site Title" wire:model="title" />
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="email"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('email') error-border @enderror"
                                placeholder="Enter shop email" wire:model="email" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Number - 1</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="number1"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('number1') error-border @enderror"
                                placeholder="Enter Comapny Number-1" wire:model="number1" />
                            @error('number1')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Number - 2</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="number2"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('number2') error-border @enderror"
                                placeholder="Enter Comapny Number-2" wire:model="number2" />
                            @error('number2')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">State</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="state"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('state') error-border @enderror"
                                placeholder="State" wire:model="state" />
                            @error('state')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <input type="text" name="state"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('address') error-border @enderror"
                                placeholder="Address" wire:model="address" />
                            @error('state')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-0" style="border: none;">
            <button type="submit" class="btn btn-primary" data-kt-brand-modal-action="submit"
                style="width: 200px !important;">
                <span class="indicator-label" wire:loading.remove wire:target="update">Save Changes</span>
                <span class="indicator-progress" wire:loading wire:target="update">
                    Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
</div>
