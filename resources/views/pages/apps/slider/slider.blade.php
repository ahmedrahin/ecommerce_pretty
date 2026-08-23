<x-default-layout>

    @section('custom-css')
        <style>
            .nav-line-tabs .nav-item .nav-link {
                margin: 0 1.3rem !important;
                font-size: 17px !important;
            }
            .dropify-wrapper {
                border: 2px dashed var(--bs-primary) !important;
                background-color: var(--bs-primary-light) !important;
                border-radius: 10px !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            }

            .dropify-message span.file-icon {
                font-size: 40px !important;
                color: var(--bs-primary) !important;
            }

            .dropify-message p {
                font-weight: bold !important;
                color: #333 !important;
            }

            .dropify-clear {
                background-color: #f44336 !important; /* Red clear button */
                color: white !important;
                border-radius: 5px !important;
            }

            .dropify-clear:hover {
                background-color: #d32f2f !important;
            }

            .dropify-font-upload:before, .dropify-wrapper .dropify-message span.file-icon:before{
                font-size: 60px;
                font-weight: 700;
            }
            .file-icon p {
                font-size: 14px !important;
                color: #45444887 !important;
            }
            
            label{
                font-size: 16px;
                font-weight: 600;
                padding-block: 10px;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
    @endsection

    @section('title') Banner Images @endsection

    @section('breadcrumbs')
       <div class="w-100 d-flex justify-content-between">
            {{ Breadcrumbs::render('slider') }}
       </div>
    @endsection
  

    <form id="imageUploadForm" class="mt-6" enctype="multipart/form-data">
        @csrf
        <div class="row">
            @if( $images->count() == 0 )
                <div class="col-md-3">
                    <div class="image-box">
                        <label for="image">Add New Image</label>
                        <input type="file" class="form-control dropify" id="image" name="image" accept="image/*">
                        @error('image')
                            <div style="color: red" class="mt-2">{{$message}}</div>
                        @enderror
                    </div>
                </div>
            @else
                @foreach ($images as $index => $image)
                    <div class="col-md-3">
                        <div class="image-box mb-3">
                            <label for="image">Image - {{ $index+1 }}</label>
                            <input type="file" class="form-control dropify" accept="image/*" data-default-file="{{ isset($image) && $image->image ? asset($image->image) : '' }}" data-image-id="{{ $image->id }}">
                            <div id="uploadStatus" style="margin-top: 10px;"></div>
                        </div>
                    </div>
                @endforeach
                
                <div class="col-md-3">
                    <div class="image-box">
                        <label for="new-image">Add New Image</label>
                        <input type="file" class="form-control dropify" id="image" name="image" accept="image/*">
                        @error('image')
                            <div style="color: red" class="mt-2">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                
            @endif
        </div>
    </form>


    @push('scripts')
    <script src="{{ asset('dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var drEvent = $('.dropify').dropify({
                messages: {
                    replace: 'Drag and drop or click to replace',
                    remove: 'Remove',
                    error: 'Oops, something went wrong!'
                }
            });

            $('#image').on('change', function() {
                var file = this.files[0];
                if (file) {
                    uploadImage(file);
                }
            });

            function uploadImage(file) {
                var formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('image', file);

                $.ajax({
                    url: "{{ route('slider.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        toastr.success('Banner image uploaded successfully');
                        location.reload();
                    },
                    error: function() {
                      
                    }
                });
            }
        });
    </script>

   <script>
    $(document).ready(function() {
      
        $('.dropify').on('dropify.afterClear', function(event, element) {
            var imageId = $(this).attr('data-image-id'); 
            if (imageId) {
                removeImage(imageId, $(this)); 
            } else {
                console.error("Image ID not found");
            }
        });

        function removeImage(imageId, inputElement) {
                $.ajax({
                    url: '{{ route("slider.destroy", ":id") }}'.replace(':id', imageId),
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.info('The image has been deleted from banner');
                        if (response.success) {
                            inputElement.closest('.col-md-3').fadeOut(300, function() { 
                                $(this).remove(); 
                            });
                        }
                    },
                    error: function() {
                        alert("Error deleting image.");
                    }
                });
            }
        });

   </script>
   
@endpush

</x-default-layout>