
<a href="{{ route('admin-management.admin-list.show', $user->id) }}" class="btn btn-icon btn-active-light-primary w-30px h-30px" style="margin-right: -10px !important;
" target="_blank">
    <i class="ki-duotone ki-eye fs-3">
        <span class="path1"></span>
        <span class="path2"></span>
        <span class="path3"></span>
    </i>
</a>

@if( $user->id !== 1 )
<button class="btn btn-icon btn-active-light-primary w-30px h-30px" data-kt-action="delete_row" data-kt-admin-id="{{$user->id}}" data-kt-role-name="{{ $user->roles->first()->name ?? 'No Role' }}">
    <i class="ki-duotone ki-trash fs-3">
        <span class="path1"></span>
        <span class="path2"></span>
        <span class="path3"></span>
        <span class="path4"></span>
        <span class="path5"></span>
    </i>
</button>
@else 
<button class="btn btn-icon btn-active-light-primary w-30px h-30px" onclick="errorAccess('Cannot move this admin from role')">
    <i class="ki-duotone ki-trash fs-3">
        <span class="path1"></span>
        <span class="path2"></span>
        <span class="path3"></span>
        <span class="path4"></span>
        <span class="path5"></span>
    </i>
</button>
@endif