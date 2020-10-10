                @foreach ($questions as $question_id => $question_text)
                    <p>
                        {{$question_id}}.
                        {{$question_text}} &ndash;
                        <b>{{$anketa->getAnswer($question_id)->answer_text ?? null}}</b>
                    </p>
                @endforeach
