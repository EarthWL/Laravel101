<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg card">
                        <div class="card-header">
                            User List
                        </div>
                        <div class="card-body">
                            <table id="userslist" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Name</td>
                                        <td>Eamil</td>
                                        <td>Last Login</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg card">
                        <div class="card-header">
                            <button class="btn btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-recycle"></i> Trash
                              </button>
                        </div>
                        <div class="collapse" id="collapseExample">
                            <div class="card-body">
                                <table id="usersTrash" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Name</td>
                                            <td>Eamil</td>
                                            <td>Deleted</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg card">
                        <div class="card-header">Add User</div>
                        <div class="card-body">
                            <form id="addUser" method="POST" action="{{route('addUser')}}">
                                @csrf
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
                                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                    <span class="text-danger error-text email_error"></span>
                                </div>
                    
                                <div class="mt-4">
                                    <x-jet-label for="password" value="{{ __('Password') }}" />
                                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                                    <span class="text-danger error-text password_error"></span>
                                </div>
                    
                                <div class="mt-4">
                                    <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                                </div>                    
                                <div class="flex items-center justify-end mt-4">
                                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    @include('template/user-edit-modal')
    @include('template/alert-template')

<script>

        const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            //Add new user
            $('#addUser').on('submit', function(e){
                    e.preventDefault();
                    var form = this;
                    $.ajax({
                        url:$(form).attr('action'),
                        method:$(form).attr('method'),
                        data:new FormData(form),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        beforeSend:function(){
                             $(form).find('span.error-text').text('');
                        },
                        success:function(data){
                             if(data.code == 0){
                                   $.each(data.error, function(prefix, val){
                                       $(form).find('span.'+prefix+'_error').text(val[0]);
                                   });
                             }else{
                                $(form)[0].reset();
                                $('#userslist').DataTable().ajax.reload(null, false);
                                Toast.fire({
                                    icon: 'success',
                                    title: data.msg
                                })
                             }
                        }
                    });
                });
            //Show all user table
            $('#userslist').DataTable({
                processing:true,
                responsive:true,
                info:true,
                ajax:"{{route('getUserList')}}",
                columns:[
                    {data:'DT_RowIndex', name:'DT_RowIndex'},
                    {data:'name', name:'name'},
                    {data:'email', name:'email'},
                    {data:'last_login', name:'last_login'},
                    {data:'actions', name:'actions'},
                ]
            });

            //Show user in trash
            $('#usersTrash').DataTable({
                processing:true,
                responsive:true,
                info:true,
                ajax:"{{route('getUserTrash')}}",
                columns:[
                    {data:'DT_RowIndex', name:'DT_RowIndex'},
                    {data:'name', name:'name'},
                    {data:'email', name:'email'},
                    {data:'deleted_at', name:'deleted_at'},
                    {data:'actions', name:'actions'},
                ]
            });

            //Get detail user modal
            $(document).on('click','#userEdit', function(){
                var user_id = $(this).data('id');
                $.post('{{route("getUserDetail") }}' ,{user_id:user_id}, function(data){
                    $('.editUser').find('input[name="uid"]').val(data.details.id);
                    $('.editUser').find('input[name="name"]').val(data.details.name);
                    $('.editUser').find('input[name="username"]').val(data.details.username);
                    $('.editUser').find('select[name="role"]').val(data.details.role);
                    $('.editUser').find('input[name="email"]').val(data.details.email);
                    $('.editUser').modal('show');
                },'json');
            });

            //Update User
            $('#editUser').on('submit', function(e){
                    e.preventDefault();
                    var form = this;
                    $.ajax({
                        url:$(form).attr('action'),
                        method:$(form).attr('method'),
                        data:new FormData(form),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        beforeSend: function(){
                             $(form).find('span.error-text').text('');
                        },
                        success: function(data){
                              if(data.code == 0){
                                  $.each(data.error, function(prefix, val){
                                      $(form).find('span.'+prefix+'_error').text(val[0]);
                                  });
                              }else{
                                  $('#userslist').DataTable().ajax.reload(null, false);
                                  $('.editUser').modal('hide');
                                  $('.editUser').find('form')[0].reset();
                                  Toast.fire({
                                        icon: 'success',
                                        title: data.msg
                                    })
                              }
                        }
                    });
                });

            //Delete User to trash
            $(document).on('click','#userDel', function(){
                    var user_id = $(this).data('id');
                    var url = '{{ route("delUser")}}';

                    swal.fire({
                        template: '#delUser'
                    }).then(function(result){
                          if(result.value){
                              $.post(url,{user_id:user_id}, function(data){
                                   if(data.code == 1){
                                       $('#userslist').DataTable().ajax.reload(null, false);
                                       $('#usersTrash').DataTable().ajax.reload(null, false);
                                       Toast.fire({
                                        icon: 'success',
                                        title: data.msg
                                        })
                                   }else{
                                       Toast.fire({
                                            icon: 'error',
                                            title: data.msg
                                        })
                                   }
                              },'json');
                          }
                    });

                });

            //Restore User
            $(document).on('click','#userRestore', function(){
                    var user_id = $(this).data('id');
                    var url = '{{ route("restoreUser")}}';

                    swal.fire({
                        template: '#restoreUser'
                    }).then(function(result){
                          if(result.value){
                              $.post(url,{user_id:user_id}, function(data){
                                   if(data.code == 1){
                                       $('#userslist').DataTable().ajax.reload(null, false);
                                       $('#usersTrash').DataTable().ajax.reload(null, false);
                                       Toast.fire({
                                        icon: 'success',
                                        title: data.msg
                                        })
                                   }else{
                                       Toast.fire({
                                            icon: 'error',
                                            title: data.msg
                                        })
                                   }
                              },'json');
                          }
                    });

                });

            //Destroy User
            $(document).on('click','#userDestroy', function(){
                    var user_id = $(this).data('id');
                    var url = '{{ route("destroyUser")}}';

                    swal.fire({
                        template: '#delUser'
                    }).then(function(result){
                          if(result.value){
                              $.post(url,{user_id:user_id}, function(data){
                                   if(data.code == 1){
                                       $('#userslist').DataTable().ajax.reload(null, false);
                                       $('#usersTrash').DataTable().ajax.reload(null, false);
                                       Toast.fire({
                                        icon: 'success',
                                        title: data.msg
                                        })
                                   }else{
                                       Toast.fire({
                                            icon: 'error',
                                            title: data.msg
                                        })
                                   }
                              },'json');
                          }
                    });

                });
        });

</script>

</x-app-layout>
