<div class="modal fade in" id="{{ $name }}" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">{{ $title ?? null }}</h4>
            </div>
            <div class="modal-body">
                @if ($modal_view)
                    @include($modal_view) 
                @endif
            </div>
            <div class="modal-footer">
                @if ($submit_title) 
                <button type="submit" class="btn btn-success" onClick="{{ $submit_onClick }}">{{ $submit_title }}</button>
                @endif
                <button type="button" class="btn btn-default cancel" data-dismiss="modal">{{trans('messages.close')}}</button>
            </div>
        </div>
    </div>
</div>