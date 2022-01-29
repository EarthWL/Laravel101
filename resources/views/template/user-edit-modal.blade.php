<div class="modal fade editUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUser" method="POST" action="{{route('updateUser')}}">
                    @csrf
                    <input type="hidden" name="uid">
                    <div>
                        <x-jet-label for="name" value="{{ __('Name') }}" />
                        <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <span class="text-danger error-text name_error"></span>
                    </div>
    
                    <div class="mt-4">
                        <x-jet-label for="username" value="{{ __('Username') }}" />
                        <x-jet-input id="username" class="block mt-1 w-full" type="text" name="username" required />
                        <span class="text-danger error-text username_error"></span>
                    </div>
        
                    <div class="mt-4">
                        <x-jet-label for="role" value="{{ __('Role') }}" />
                        <select id="role" name="role" class="form-select" aria-label="role select" required>
                            <option selected disabled>Select Roles</option>
                            <option value="0">Admin</option>
                            <option value="1">Teacher</option>
                            <option value="2">Student</option>
                        </select>
                        <span class="text-danger error-text role_error"></span>
                    </div>
        
                    <div class="mt-4">
                        <x-jet-label for="email" value="{{ __('Email') }}" />
                        <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" required value="old('email')" />
                        <span class="text-danger error-text email_error"></span>
                    </div>
        
                    <div class="mt-4">
                        <x-jet-label for="password" value="{{ __('Password') }}" />
                        <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" placeholder="leave blank if don't change password" />
                        <span class="text-danger error-text password_error"></span>
                    </div>
        
                    <div class="mt-4">
                        <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" placeholder="leave blank if don't change password" />
                    </div>                    
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                    </div>
                </form>
                

            </div>
        </div>
    </div>
</div>