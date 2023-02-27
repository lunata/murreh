<div id="role{{$role_id}}" class="{{$class}}">
    <table class="table-bordered table-wide table-striped rwd-table wide-lg">
        <thead>
            <tr>
                <th>No</th>
                <th>E-mail</th>
                <th>{{ trans('auth.name') }}</th>
                <th>{{ trans('auth.roles') }}</th>
                @if (User::checkAccess('user.edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($users[$role_id] as $user)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="E-mail">{{$user->email}}</td>
                <td data-th="{{ trans('auth.name') }}">{{$user->first_name}} {{$user->last_name}}</td>
                <td data-th="{{ trans('auth.roles') }}">
                    {{$user->rolesNames()}}
                </td>
                @if (User::checkAccess('user.edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                             ['is_button'=>true, 
                              'without_text' => true,
                              'route' => '/user/'.$user->id.'/edit',
                             ])
                    @include('widgets.form.button._delete', 
                             ['is_button'=>true, 
                              'without_text' => true,
                              'route' => 'user.destroy',
                              'obj'=>$user,
                              'class' => '',
                              'obj_name' => 'user'])
                </td>
                @endif
            </tr> 
            @endforeach
        </tbody>
    </table>
</div>