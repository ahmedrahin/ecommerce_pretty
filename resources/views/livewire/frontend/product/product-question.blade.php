<section class="ask-question q-n-r-section bg-white m-tb-15" id="ask-question">
    <div class="section-head">
        <div class="title-n-action">
            <h2>Questions ({{ $questions->count() }})</h2>
            <p class="section-blurb">Have question about this product? Get specific details
                about this product from expert.</p>
        </div>
        <div class="q-action">
            @if (auth()->check())
                <button class="btn st-outline" data-bs-toggle="modal" data-bs-target="#question-modal" tabindex="0">Ask
                    Question</button>
                <div class="customer-reviews-modal modal theme-modal fade" id="question-modal" tabindex="-1"
                    role="dialog" aria-modal="true" wire:ignore.self>
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 style="color: black;">Ask A Question</h3>
                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"><i
                                        class="fa fa-times" aria-hidden="true"></i></button>
                            </div>

                            <div class="modal-body pt-0">

                                <form wire:submit.prevent="submit">
                                    <div style="text-align: left;">
                                        <div class="col-12 mt-2">
                                            <div class="from-group">
                                                <label class="form-label">Question :</label>
                                                <textarea class="form-control @error('question') error-border @enderror" id="question" cols="30" rows="4"
                                                    placeholder="Write question here..." wire:model="question"></textarea>
                                                @error('question')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-button-group">
                                            <button class="btn btn-cancel cancel-modal-review" type="button"
                                                data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                            <button class="btn btn-submit" type="submit" style="width: 140px;">
                                                <span wire:loading.remove wire:target="submit">Submit</span>
                                                <span wire:loading wire:target="submit" class="formloader"></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            @else
                <button class="btn st-outline" onclick="message('warning', 'Login at first to ask a question')">Ask
                    Question</button>
            @endif
        </div>
    </div>
    <div id="question">
        @if ($questions->count() < 1)
            <div class="empty-content">
                <span class="icon material-icons">textsms</span>
                <div class="empty-text">There are no questions asked yet. Be the first one to ask a
                    question.</div>
            </div>
        @else
            @foreach ($questions as $question)
                <div class="question-wrap">
                    <p class="author"><span class="name" itemprop="author">{{ $question->user->name }}</span> on
                        <time>{{ \Carbon\Carbon::parse($question->created_at)->format('d M, Y') }}</time></p>
                    <h3 class="question"><span class="hint">Q:</span> <span
                            itemprop="name">{{ $question->question }}</span></h3>
                    <div class="answer-wrap">
                        <p class="answer"><span class="hint">A:</span> <span>{!! $question->answer !!}</span></p>

                    </div>
                </div>
            @endforeach

            @if ($questionCount > 3)
                <div class="text-right">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($questions->onFirstPage())
                            <li><span class="disabled">PREV</span></li>
                        @else
                            <li>
                                <a href="#" wire:click.prevent="previousPage" aria-label="Previous">PREV</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($questions->getUrlRange(1, $questions->lastPage()) as $page => $url)
                            @if ($page == $questions->currentPage())
                                <li class="active"><span>{{ $page }}</span></li>
                            @else
                                <li>
                                    <a href="#"
                                        wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($questions->hasMorePages())
                            <li>
                                <a href="#" wire:click.prevent="nextPage" aria-label="Next">NEXT</a>
                            </li>
                        @else
                            <li><span class="disabled">NEXT</span></li>
                        @endif
                    </ul>
                </div>
            @endif
        @endif
    </div>
</section>
