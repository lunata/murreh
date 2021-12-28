        @if (User::checkAccess('corpus.edit'))
            @include('widgets.modal',['name'=>'modalAddAnswer',
                                  'title'=>trans('ques.add-answer'),
                                  'submit_onClick' => 'saveAnswer()',
                                  'submit_title' => trans('messages.save'),
                                  'modal_view'=>'ques.answer._form_create'])
            @include('widgets.modal',['name'=>'modalCopyAnswers',
                                  'title'=>trans('ques.copy_answers'),
                                  'submit_title' => null,
                                  'to_anketa_id' => $anketa->id,
                                  'modal_view'=>'ques.anketa_question._for_copy_answers'])
        @endif         
