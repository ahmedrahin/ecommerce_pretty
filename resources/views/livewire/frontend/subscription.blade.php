<div class="footer-newsletter bg-primary">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-xl-5 col-lg-6">
                <div class="icon-box icon-box-side text-white">
                    <div class="icon-box-icon d-inline-flex">
                        <i class="w-icon-envelop3"></i>
                    </div>
                    <div class="icon-box-content">
                        <h4 class="icon-box-title text-white text-uppercase font-weight-bold">
                            Subscribe To Our Newsletter
                        </h4>
                        <p class="text-white">Get all the latest information on Events, Sales and Offers.</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 col-lg-6 col-md-9 mt-4 mt-lg-0">
                <form wire:submit.prevent="submit" class="input-wrapper input-wrapper-inline input-wrapper-rounded">
                    <input type="email" class="form-control mr-2 bg-white @error('email') is-invalid @enderror"
                           name="email" id="email" placeholder="Your E-mail Address" wire:model.defer="email" />

                    <button class="btn btn-dark btn-rounded" type="submit">
                        Subscribe <i class="w-icon-long-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
