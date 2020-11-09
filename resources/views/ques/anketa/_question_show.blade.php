                @foreach ($questions as $question_id => $question_info)
                    <p>
                        {{$question_info[0]}}.
                        {{$question_info[1]}} &ndash;
                        <b>{{$anketa->getAnswer($question_id)->answer_text ?? null}}</b>
                    </p>
                @endforeach
