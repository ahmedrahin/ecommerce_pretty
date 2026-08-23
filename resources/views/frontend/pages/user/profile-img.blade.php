<div class="account-author">
    <div class="author_avatar" style="cursor: pointer;" id="{{ empty($user->avatar) ? 'avatar_box' : '' }}">
        <div class="image">
            <img class="lazyload imgDash" id="userAvatar"
             src="{{ $user->avatar ? asset($user->avatar) : asset('uploads/user.png') }}">

            @if ($user->avatar)
                <div class="btn-change_img box-icon" id="removePhotoBtn">
                    <i class="icon icon-trash"></i>
                </div>
            @else
                <div class="btn-change_img box-icon" id="changeImgBtn">
                    <i class="icon icon-camera"></i>
                </div>
            @endif
        </div>

        <input type="file" id="profilePhotoInput" accept=".jpg,.jpeg,.png,.gif" hidden>
        <div class="loading">
            <div class="formloader"></div>
        </div>
    </div>
    <h4 class="author_name">{{$user->name}}</h4>
    <p class="author_email h6">{{$user->email}}</p>
</div>

@push('scripts')
    {{-- profile uploade --}}
    <script>
        $(document).on("click", "#avatar_box", function () {
            $("#profilePhotoInput").click();
        });

        $(document).on("click", "#profilePhotoInput", function (e) {
            e.stopPropagation();
        });


        $(document).on("change", "#profilePhotoInput", function () {

            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $(".imgDash").attr("src", e.target.result);
                };

                reader.readAsDataURL(file);
            }

            let formData = new FormData();
            formData.append("profilePhoto", this.files[0]);
            formData.append("_token", "{{ csrf_token() }}");



            $.ajax({
                type: "POST",
                url: "{{ route('user.avatar.upload') }}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('.loading').show();
                },
                success: function (res) {
                    $('.loading').hide();
                    message('success', 'Profile photo updated successfully');
                    $('#profile_img').html(res.html);
                }
            });
        });

        $(document).on("click", "#removePhotoBtn", function () {

            $.ajax({
                type: "POST",
                url: "{{ route('user.avatar.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function () {
                    $('.loading').show();
                },
                success: function (res) {
                    $('.loading').show();
                    message('success', 'Profile has been removed');
                    $('#profile_img').html(res.html);
                }
            });

        });
    </script>
@endpush