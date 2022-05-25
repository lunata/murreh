@extends('layouts.page')

@section('page_title')
{{ trans('geo.place_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p>
        @if (User::checkAccess('edit'))
            <a href="{{route('place.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>
        
        @include('geo.place._search_form',['url' => '/geo/place/']) 

        <p>{{ !$numAll ? trans('messages.not_founded_records') : trans_choice('messages.founded_records', $numAll%20, ['count'=>$numAll]) }}</p>
        
        @if (sizeof($places))
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>ID</th>
{{--                <th>{{ trans('geo.region') }}</th> --}}
                <th>{{ trans('geo.district') }}</th>
                <th>{{ trans('geo.name') }} {{ trans('messages.in_russian') }}</th>
                <th>{{ trans('geo.name') }} {{ trans('messages.in_karelian') }}</th>
                <th>{{ trans('geo.latitude') }}</th>
                <th>{{ trans('geo.longitude') }}</th>
{{--                <th>{{ trans('geo.population') }}</th> --}}
                <th>{{trans('dict.dialect')}}</th>
                <th>{{ trans('navigation.informants') }}</th>
                <th>{{ trans('navigation.anketas') }}</th>
                <th>{{ trans('sosd.words') }}</th>
                @if (User::checkAccess('edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($places as $place)
            <tr><td data-th="ID">{{$place->id}}</td>
{{--                <td data-th="{{ trans('geo.region') }}">{{$place->regionNames()}}</td> --}}
                <td data-th="{{ trans('geo.district') }}">
                    @if ($place->districts)
                        {{$place->districtNamesWithDates()}}
                    @endif
                </td>
                <td data-th="{{ trans('geo.name') }} {{ trans('messages.in_russian') }}">
                    {{ $place->name_ru }}
                    @if ($place->name_old_ru)
                        {{ $place->name_old_ru }})
                    @endif
                </td>
                <td data-th="{{ trans('geo.name') }} {{ trans('messages.in_karelian') }}">
                    {{ $place->name_krl }}
                    @if ($place->name_old_krl)
                        {{ $place->name_old_krl }})
                    @endif
                </td>
                <td data-th="{{ trans('geo.latitude') }}">
                    {{ $place->latitude ? sprintf("%.05f\n", $place->latitude) : '' }}
                </td>
                <td data-th="{{ trans('geo.longitude') }}">
                    {{ $place->longitude ? sprintf("%.05f\n", $place->longitude) : '' }}
                </td>
{{--                <td data-th="{{ trans('geo.population') }}" style="text-align: right">
                    {{ $place->population }}
                </td> --}}
                <td data-th="{{trans('dict.dialect')}}">
                    {{ $place->dialect ? $place->dialect->name : '' }}
                    {{ $place->lang ? '('.$place->lang->code.')' : ''}}
                </td>
                <td data-th="{{ trans('navigation.informants') }}">
                    @if($place->informants()->count())
                    <a href="/geo/informant?search_place={{$place->id}}">
                        {{ $place->informants()->count() }}
                    </a>
                    @else 
                        0
                    @endif
                </td>
                <td data-th="{{ trans('navigation.anketas') }}">
                    @if($place->anketas()->count())
                    <a href="/ques/anketas?search_place={{$place->id}}">
                        {{ $place->anketas()->count() }}
                    </a>
                    @else 
                        0
                    @endif
                </td>
                <td data-th="{{ trans('sosd.words') }}">
                    @if ($place->concepts()->count())
                    <a href="/sosd/concept_place/{{$place->id}}">{{$place->concepts()->count()}}</a>
                    @else
                    0
                    @endif
                </td>
                @if (User::checkAccess('geo.edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/geo/place/'.$place->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'place.destroy', 
                             'obj' => $place,
                             'args'=>['id' => $place->id]])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $places->appends($url_args)->links() }}
        @endif
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
    {!!Html::script('js/special_symbols.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop