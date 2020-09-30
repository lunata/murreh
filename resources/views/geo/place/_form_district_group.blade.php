        <div class='flex-hor-group'>
        @include('widgets.form.formitem._select2',
                ['name' => 'districts['.$i.'][id]', 
                 'values' =>$district_values,
                 'value' => $district['id'] ?? '',
                 'is_multiple' => false,
                 'title' => trans('geo.district'),
                 'class'=>'select-district-'.$i.' form-control'])
          
        @include('widgets.form.formitem._text', 
                ['name' => 'districts['.$i.'][from]',
                 'title' => 'c',
                 'value' => $district['from'] ?? '',
                 'attributes' => ['size'=>4, 'placeholder'=>'гггг']])
        
        @include('widgets.form.formitem._text', 
                ['name' => 'districts['.$i.'][to]',
                 'title' => 'по',
                 'value' => $district['to'] ?? '',
                 'attributes' => ['size'=>4, 'placeholder'=>'гггг']])
        </div>
