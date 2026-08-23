<x-default-layout>

    @section('custom-css')

    @endsection

    @section('title') Reply Question @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('question-details') }}
    @endsection

    <livewire:contact-message.qustionaction :id="$message->id" />

    <div class="d-flex flex-column flex-lg-row">

        <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10" style="margin-left: 0 !important;">
            <!--begin::Card-->
            <div class="card">
                <div class="card-header align-items-center py-5 gap-5">
                    <!--begin::Actions-->
                    <div class="d-flex">
                        <!--begin::Back-->
                        <a href="{{ route('question.weekly') }}"
                            class="btn btn-sm btn-icon btn-clear btn-active-light-primary me-3" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Back">
                            <i class="ki-duotone ki-arrow-left fs-1 m-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>
                        <!--end::Back-->


                        <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary me-2 delete-message"
                            data-id="{{ $message->id }}" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Delete">
                            <i class="ki-duotone ki-trash fs-2 m-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </a>

                        <!--end::Delete-->

                        <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary me-2"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Copy" onclick="copyMessage(event)">
                            <i class="ki-duotone ki-copy fs-2 m-0"></i>
                        </a>

                        <span id="messageText" class="d-none">{{ $message->question }}</span>

                        <!--end::Mark as read-->
                        <!--begin::Move-->
                        <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Move">
                            <i class="ki-duotone ki-entrance-left fs-2 m-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>
                        <!--end::Move-->
                        <a href="{{ route('product-management.show',$message->product_id) }}"
                            class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                            style="width: inherit;padding: 0 10px;margin-left:5px;" target="_blank">
                            View Product
                        </a>
                    </div>

                    <div>
                        <h5 class="text-danger mb-0">
                            Sent: {{ $message->created_at->diffForHumans() }}
                            @if( $message->replied_at )
                            <span class="d-block text-success mt-1">Reply: {{
                                \Carbon\Carbon::parse($message->replied_at)->diffForHumans() ?? '' }}</span>
                            @endif
                        </h5>
                    </div>

                </div>
                <div class="card-body">
                    <!--begin::Message accordion-->
                    <div data-kt-inbox-message="message_wrapper">
                        <!--begin::Message header-->
                        <div class="d-flex flex-wrap gap-2 flex-stack cursor-pointer" data-kt-inbox-message="header">
                            <!--begin::Author-->
                            <div class="d-flex align-items-center">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50 me-4">
                                    <img src="{{ asset(optional($message->user)->avatar ?? 'uploads/user.png') }}"
                                        width="50" height="50" class="rounded-circle">
                                </div>
                                <!--end::Avatar-->
                                <div class="pe-5">
                                    <!--begin::Author details-->
                                    <div class="d-flex align-items-center flex-wrap gap-1">
                                        <a href="{{ optional($message->user) ? route('user-management.users.show', $message->user_id) : '#' }}"
                                            target="_blank" class="fw-bold text-dark text-hover-primary">
                                            {{ optional($message->user)->name ?? 'Deleted User' }}
                                        </a>
                                        <i class="ki-duotone ki-abstract-8 fs-7 text-success mx-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <span class="text-muted fw-bold">
                                            {{ $message->created_at ? $message->created_at->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    <!--end::Author details-->
                                    <!--begin::Message details-->
                                    <div data-kt-inbox-message="details">
                                        <span class="text-muted fw-semibold">to {{ config('app.name') }}</span>
                                        <!--begin::Menu toggle-->
                                        <a href="#" class="me-1" data-kt-menu-trigger="click"
                                            data-kt-menu-placement="bottom-start">
                                            <i class="ki-duotone ki-down fs-5 m-0"></i>
                                        </a>
                                        <!--end::Menu toggle-->
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-300px p-4"
                                            data-kt-menu="true">
                                            <!--begin::Table-->
                                            <table class="table mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="w-75px text-muted">From</td>
                                                        <td>{{ optional($message->user)->name ?? 'Deleted User' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Date</td>
                                                        <td>{{ $message->created_at ? $message->created_at->format('d M,
                                                            Y (h:i a)') : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Subject</td>
                                                        <td>{{ $message->subject ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Reply-to</td>
                                                        <td>{{ optional($message->user)->email ?? 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Message details-->
                                </div>
                            </div>
                            <!--end::Author-->
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span class="fw-semibold text-muted text-end me-3">
                                    {{ $message->created_at ? $message->created_at->format('d M, Y (h:i a)') : '' }}
                                </span>
                            </div>
                            <!--end::Actions-->
                        </div>

                        <div class="collapse fade show" data-kt-inbox-message="message">
                            <div class="py-5 pb-0">
                                <p>{{ $message->question }}</p>
                            </div>
                        </div>
                    </div>
                    <!--end::Message accordion-->

                    @if ($message->is_replied)
                    <div class="separator my-6"></div>
                    <!--begin::Message accordion-->
                    <div data-kt-inbox-message="message_wrapper">
                        <!--begin::Message header-->
                        <div class="d-flex flex-wrap gap-2 flex-stack cursor-pointer" data-kt-inbox-message="header">
                            <!--begin::Author-->
                            <div class="d-flex align-items-center">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50 me-4">
                                    <img src="{{ asset(optional($message->repliedby)->avatar ?? 'uploads/user.png') }}"
                                        width="50" height="50" class="rounded-circle">
                                </div>
                                <!--end::Avatar-->
                                <div class="pe-5">
                                    <!--begin::Author details-->
                                    <div class="d-flex align-items-center flex-wrap gap-1">
                                        <a href="{{ optional($message->repliedby) ? route('admin-management.admin-list.show', $message->repliedby->id) : '#' }}"
                                            target="_blank" class="fw-bold text-dark text-hover-primary">
                                            {{ optional($message->repliedby)->name ?? 'Deleted Admin' }}
                                        </a>
                                        <i class="ki-duotone ki-abstract-8 fs-7 text-success mx-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <span class="text-muted fw-bold">
                                            {{ $message->replied_at ?
                                            \Carbon\Carbon::parse($message->replied_at)->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    <!--end::Author details-->
                                    <!--begin::Message details-->
                                    <div class="d-none" data-kt-inbox-message="details">
                                        <span class="text-muted fw-semibold">to {{ optional($message->user)->name ??
                                            'Deleted User' }}</span>
                                        <a href="#" class="me-1" data-kt-menu-trigger="click"
                                            data-kt-menu-placement="bottom-start">
                                            <i class="ki-duotone ki-down fs-5 m-0"></i>
                                        </a>
                                    </div>
                                    <div class="text-muted fw-semibold mw-450px mt-2" data-kt-inbox-message="preview">
                                        {!! $message->answer !!}
                                    </div>
                                </div>
                            </div>
                            <!--end::Author-->
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span class="fw-semibold text-muted text-end me-3">
                                    {{ $message->replied_at ? \Carbon\Carbon::parse($message->replied_at)->format('d M,
                                    Y (h:i a)') : '' }}
                                </span>
                            </div>
                            <!--end::Actions-->
                        </div>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="form-message mt-10">
                        @foreach ($errors->all() as $error)
                        <ul id="errors-msgs" type="none" class="p-0">
                            <li>
                                <div
                                    class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5">
                                    <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div class="d-flex flex-column pe-0 pe-sm-10">
                                        <h5 class="mb-1">{{ $error }}</h5>
                                        <span>Please fill up the field with valid data!</span>
                                    </div>
                                    <button type="button"
                                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                                        data-bs-dismiss="alert">
                                        <i class="ki-duotone ki-cross fs-1 text-danger">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                        @endforeach
                    </div>
                    @endif

                    <form id="kt_inbox_reply_form" class="rounded border mt-10"
                        action="{{ route('question.reply',$message->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-block">
                            <!--begin::To-->
                            <div class="d-flex align-items-center border-bottom px-8 min-h-50px">
                                <div class="text-dark fw-bold w-75px">To:</div>
                                <input type="text" class="form-control border-0" name="email"
                                    value="{{ optional($message->user)->email ?? 'N/A' }}" readonly />
                                <div class="ms-auto w-75px text-end">
                                    <span class="text-muted fs-bold cursor-pointer text-hover-primary me-2"
                                        data-kt-inbox-form="cc_button">Cc</span>
                                    <span class="text-muted fs-bold cursor-pointer text-hover-primary"
                                        data-kt-inbox-form="bcc_button">Bcc</span>
                                </div>
                            </div>
                            <!--end::To-->

                            <!-- CC / BCC inputs omitted (unchanged) -->

                            <div class="border-bottom">
                                <input class="form-control border-0 px-8 min-h-45px" name="subject"
                                    placeholder="Subject" value="" />
                            </div>

                            <div id="kt_inbox_form_editor" class="border-0 h-250px px-3"></div>
                            <textarea name="reply_message" id="hidden_message_input" class="d-none"></textarea>

                            <div class="dropzone dropzone-queue px-8 py-4" id="kt_inbox_reply_attachments"
                                data-kt-inbox-form="dropzone">
                                <div class="dropzone-items">
                                    <div class="dropzone-item" style="display:none">
                                        <div class="dropzone-file">
                                            <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                <span data-dz-name="">some_image_file_name.jpg</span>
                                                <strong>(<span data-dz-size="">340kb</span>)</strong>
                                            </div>
                                            <div class="dropzone-error" data-dz-errormessage=""></div>
                                        </div>
                                        <div class="dropzone-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"
                                                    data-dz-uploadprogress=""></div>
                                            </div>
                                        </div>
                                        <div class="dropzone-toolbar">
                                            <span class="dropzone-delete" data-dz-remove="">
                                                <i class="ki-duotone ki-cross fs-6">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top">
                            <div class="d-flex align-items-center me-3">
                                <div class="btn-group me-4">
                                    <button class="btn btn-primary fs-bold px-6" data-kt-inbox-form="send"
                                        type="submit">
                                        <span class="indicator-label">Send</span>
                                        <span class="indicator-progress">Please wait...</span>
                                    </button>
                                </div>
                                <span class="btn btn-icon btn-sm btn-clean btn-active-light-primary me-2"
                                    id="kt_inbox_reply_attachments_select" data-kt-inbox-form="dropzone_upload">
                                    <i class="ki-duotone ki-paper-clip fs-2 m-0"></i>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <!--end::Card-->
        </div>
        <!--end::Content-->
    </div>


    @push('scripts')

    @if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
    @endif

    <script>
        document.getElementById('kt_inbox_reply_form').addEventListener('submit', function (e) {
                const qlEditor = document.querySelector('#kt_inbox_form_editor .ql-editor');

                // Get HTML content
                let messageEditorContent = qlEditor ? qlEditor.innerHTML.trim() : '';

                // Remove empty <p><br></p> tags
                messageEditorContent = messageEditorContent.replace(/<p><br><\/p>/g, '').trim();

                // Optionally prevent empty submission
                if (messageEditorContent === '') {
                    e.preventDefault();
                    alert('Please enter a message before sending.');
                    return;
                }

                // Set to hidden input
                document.getElementById('hidden_message_input').value = messageEditorContent;
            });
    </script>

    <script>
        function copyMessage(event) {
                    event.preventDefault(); // Prevent default anchor behavior
                    const message = document.getElementById('messageText').textContent;

                    navigator.clipboard.writeText(message).then(function() {
                    }, function(err) {
                        console.error('Could not copy text: ', err);
                    });
                }
    </script>

    <script>
        var hostUrl = "assets/";
    </script>
    <script>
        $(document).on('click', '.delete-message', function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        Swal.fire({
            text: 'Are you sure you want to remove?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('question.delete', ['id' => '__id__']) }}".replace('__id__', id),
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        window.location.href = "{{ route('question.weekly') }}";
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong.',
                        });
                    }
                });
            }
        });
    });
    </script>

    <script src="{{ asset('assets/js/custom/apps/inbox/reply.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>

    @endpush

</x-default-layout>