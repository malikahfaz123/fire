<div class="modal fade" id="{{$id}}" tabindex="1" role="dialog" aria-labelledby="{{$id}}-title" aria-hidden="true">
    <div class="modal-dialog" role="document" {!! isset($max_width) ? "style='max-width: {$max_width}px'" : "" !!} >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title cambria-bold" id="{{$id}}-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="{{$id}}-content" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>