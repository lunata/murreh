                    <a href="/ques/anketas/{{$anketa->id}}?search_question={{$question->id}}#question{{$question->id}}">{{$anketa->fond_number}}</a> - {{$anketa->place->toStringWithDistrict()}}
                    <a href="/ques/question/{{$question->id}}/edit_answer/{{$anketa->id}}"><i class="fa fa-pencil-alt fa-lg" title='редактировать ответ'></i></a>
