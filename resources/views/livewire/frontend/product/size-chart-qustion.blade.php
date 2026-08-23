<div class="buy-box border-buttom">
    <ul>
      @if( config('website_settings.show_size_chart')  == true )
        <li> <span data-bs-toggle="modal" data-bs-target="#size-chart" title="Quick View" tabindex="0"><i class="iconsax me-2" data-icon="ruler"></i>Size Chart</span></li>
      @endif
      
      @if( config('website_settings.ask_qustion')  == true )
      <li> <span data-bs-toggle="modal" data-bs-target="#question-box" title="Quick View" tabindex="0"><i class="iconsax me-2" data-icon="question-message"></i>Ask a Question</span></li>
      @endif
    </ul>


    <div class="modal theme-modal fade question-answer-modal" id="question-box" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4>Ask a Question</h4>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
              <div class="row g-3">
                <div class="col-12"> 
                  <div class="reviews-product"> 
                    <div> <img src="../assets/images/modal/0.jpg" alt="">
                      <div> 
                        <h5>Denim Skirts Corset Blazer</h5>
                        <p>$20.00 
                          <del>$35.00           </del>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">    
                  <div class="from-group"> 
                    <label class="form-label">Your Question :</label>
                    <textarea class="form-control" id="comment" cols="30" rows="4" placeholder="Write your Question here..."></textarea>
                  </div>
                </div>
                <div class="modal-button-group">
                  <button class="btn btn-cancel" type="submit" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                  <button class="btn btn-submit" type="submit" data-bs-dismiss="modal" aria-label="Close">Submit</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal theme-modal fade" id="size-chart" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4>Size Chart</h4>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0"><a href="#"> <img class="img-fluid" src="../assets/images/size-chart/size-chart.jpg" alt=""></a></div>
          </div>
        </div>
      </div>
</div>
