<div>
    <div class="tab-pane fade active show" id="kt_ecommerce_settings_products" role="tabpanel">
        <form wire:submit.prevent="update" id="kt_ecommerce_settings_general_products" class="form">
            <div class="card-body border-top p-9 py-4 pt-8"></div>
            <!--begin::Heading-->
            <div class="row mb-7">
                <div class="col-md-9 offset-md-3">
                    <h4>Shop page settings</h4>
                </div>
            </div>
            <!--end::Heading-->
            <!--begin::Input group-->
            <div class="row fv-row mb-7">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Category Product Count</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Show the number of products inside the categories in the shop sidebar category menu.">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <div class="d-flex mt-3">
                        <div class="form-check form-check-custom form-check-solid me-5">
                            <input class="form-check-input" type="radio" wire:model="product_count_enabled" value="1"
                                id="category_product_count_yes" />
                            <label class="form-check-label" for="category_product_count_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" wire:model="product_count_enabled" value="0"
                                id="category_product_count_no" />
                            <label class="form-check-label" for="category_product_count_no">No</label>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row fv-row mb-16">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span class="">Items Per Page</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Determines how many items are shown per page.">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <input type="number" wire:model="item_per_page" class="form-control form-control-solid @error('item_per_page') error-border @enderror" placeholder="20" />
                    @error('item_per_page')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>


            <div class="row mb-7">
                <div class="col-md-9 offset-md-3">
                    <h4>Product list settings</h4>
                </div>
            </div>
            <!--end::Heading-->
            <!--begin::Input group-->
            <div class="row fv-row mb-7">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Wishlist Button</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Enable/disable wishlist button in product list item">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <div class="d-flex mt-3">
                        <div class="form-check form-check-custom form-check-solid me-5">
                            <input class="form-check-input" type="radio" wire:model="show_wishlist" value="1"
                                id="show_wishlist" />
                            <label class="form-check-label" for="show_wishlist">Yes</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" wire:model="show_wishlist" value="0"
                                id="show_wishlist_no" />
                            <label class="form-check-label" for="show_wishlist_no">No</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row fv-row mb-7">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Buy Now Button</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Enable/disable buy now button in product list item">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <div class="d-flex mt-3">
                        <div class="form-check form-check-custom form-check-solid me-5">
                            <input class="form-check-input" type="radio" wire:model="buy_now_button" value="1"
                                id="buy_now_button" />
                            <label class="form-check-label" for="buy_now_button">Yes</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" wire:model="buy_now_button" value="0"
                                id="buy_now_button_no" />
                            <label class="form-check-label" for="buy_now_button_no">No</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row fv-row mb-7">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Expire Date show</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Show product expire date counting time.">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <div class="d-flex mt-3">
                        <div class="form-check form-check-custom form-check-solid me-5">
                            <input class="form-check-input" type="radio" wire:model="show_expire_date" value="1"
                                id="show_expire_date" />
                            <label class="form-check-label" for="show_expire_date">Yes</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" wire:model="show_expire_date" value="0"
                                id="show_expire_date_no" />
                            <label class="form-check-label" for="show_expire_date_no">No</label>
                        </div>
                    </div>
                </div>
            </div>


            <!--begin::Heading-->
            <div class="row mb-7 mt-12">
                <div class="col-md-9 offset-md-3">
                    <h4>Reviews Settings</h4>
                </div>
            </div>

            <div class="row fv-row mb-7">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Allow Reviews</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="User can give product review?">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <div class="d-flex mt-3">
                        <!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid me-5">
                            <input class="form-check-input" type="radio" value="1"
                                 id="allow_review"
                                wire:model="allow_review" />
                            <label class="form-check-label"
                                for="allow_review">Yes</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="0"
                                 id="allow_review_no" wire:model="allow_review" />
                            <label class="form-check-label" for="allow_review_no">No</label>
                        </div>
                        <!--end::Radio-->
                    </div>
                </div>
            </div>

            <div class="row fv-row mb-7">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Show reviews in product</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="show product avg review in shop page product list item">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <div class="d-flex mt-3">
                        <!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid me-5">
                            <input class="form-check-input" type="radio" value="1"
                                name="show_review" id="show_review" wire:model="show_review" />
                            <label class="form-check-label"
                                for="show_review">Yes</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="0"
                                name="show_review" id="show_review_no"
                                wire:model="show_review" />
                            <label class="form-check-label"
                                for="show_review_no">No</label>
                        </div>
                        <!--end::Radio-->
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <div class="row fv-row mb-16">
                <div class="col-md-3 text-md-end">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span>Allow Guest Reviews</span>
                        <span class="ms-1" data-bs-toggle="tooltip"
                            title="Without login user can give review!!">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                </div>
                <div class="col-md-9">
                    <div class="d-flex mt-3">
                        <!--begin::Radio-->
                        <div class="form-check form-check-custom form-check-solid me-5">
                            <input class="form-check-input" type="radio" value="1"
                                wire:model="allow_guest_review" id="allow_guest_review" />
                            <label class="form-check-label"
                                for="allow_guest_review">Yes</label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="0"
                                wire:model="allow_guest_review" id="allow_guest_review_no"
                                 />
                            <label class="form-check-label"
                                for="allow_guest_review_no">No</label>
                        </div>
                        <!--end::Radio-->
                    </div>
                </div>
            </div>

            <!--begin::Action buttons-->
            <div class="row py-5">
                <div class="col-md-9 offset-md-3">
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
                </div>
            </div>
            <!--end::Action buttons-->
        </form>
    </div>
</div>
