        <p>
            {{trans_choice('messages.founded_records', $numAll>20 ? ($numAll%10==0 ? $numAll : $numAll%10)  : $numAll, ['count'=>number_format($numAll, 0, ',', ' ')])}}
        </p>
