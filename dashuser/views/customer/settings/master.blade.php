@php
    Theme::set('pageTitle',  __('Settings'));
    $user = auth('customer')->user();
   
    $menu = DashHelper::getMenu('menuSettings', false);

    $setting_menu = [];
    foreach($menu as $key => $item) {
        if(isset($item['render'])) {
            $setting_menu["@".$key] = $item['render'];
            continue;
        }
        $setting_menu[$item['title']] = $item['url'];
    }
    $closeDiv = false;

@endphp

    <div class="row g-0">
    <div class="col-12 col-md-3 border-end">
            @foreach($setting_menu as $key => $value)
                @if(Str::startsWith($key, '@'))
                    @if ($closeDiv)
                        </div>
                        @php
                            $closeDiv = false;
                        @endphp
                    @endif
                    {!! $value !!}
                    <div class="list-group mb-3 list-group-transparent">
                        @php
                            $closeDiv = true;
                        @endphp
                @else
                    <a href="{{ $value }}" class="list-group-item list-group-item-action d-flex align-items-center @if( $value == url()->current()) active @endif">{{ __($key) }}</a>
                
                @endif
            @endforeach
            </div>
    </div>
    <div class="col-12 col-md-9 d-flex flex-column">
        <div class="card-body">
            @yield('content')
        </div>
    </div>
