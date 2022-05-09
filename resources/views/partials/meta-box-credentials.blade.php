<div class="meta-box info-box">
    <span class="info-box-icon {{ isset($bg_class) ? $bg_class : 'bg-gradient-cyan' }} elevation-1"><i class="material-icons">{{ isset($icon) ? $icon : 'error_outline' }}</i></span>
    <div class="info-box-content">
        @foreach($labels as $label => $value)
            <div> {{ $label }} <span class="roboto-bold"> {{ $value }} </span></div>
            <!-- <div> {{ $label }} <span class="roboto-bold"> {{ $value }} </span></div> -->
        @endforeach
    </div>
</div>