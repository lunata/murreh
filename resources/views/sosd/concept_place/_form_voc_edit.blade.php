        <div class="row" style="background-color: #eee; padding:5px 0 0;">
            <div class="col-sm-2">                 
                @include('widgets.form.formitem._text', 
                        ['name' => 'words['.$concept_id.']['.$count.'][code]', 
                         'special_symbol' => true,
                         'value' => $voc_code ?? null])
            </div>                 
            <div class="col-sm-10">                 
                @include('widgets.form.formitem._text', 
                        ['name' => 'words['.$concept_id.']['.$count.'][word]', 
                         'special_symbol' => true,
                         'value' => $voc_word ?? null])
            </div>                 
        </div>
