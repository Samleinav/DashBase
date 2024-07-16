@php
$menu = DashHelper::getMenu('menuFront', false);
@endphp
 
<nav id="sidebar" class="sidebar js-sidebar">
<div class="sidebar-content js-simplebar" style="overflow-y: auto;">
    <a class='sidebar-brand' href='/'>
        <span class="sidebar-brand-text align-middle">
        {{  env('APP_NAME') }}
        </span>
        <svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5"
            stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF" style="margin-left: -3px">
            <path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
            <path d="M20 12L12 16L4 12"></path>
            <path d="M20 16L12 20L4 16"></path>
        </svg>
    </a>

    
<ul class="sidebar-nav">
    <li class="sidebar-header">
        {{ __('Menu') }}
    </li> 
@foreach ($menu as $key => $row)
    @php

        if( isset($row['render'])){
            echo $row['render'];
            continue;
        }
        
        $pageActive = false;
        $isCollapsed = false;
        $childActive = false;

        if ($row['url'] == url()->current()) {
            $pageActive = true;
            $isCollapsed = false;
        }

        if ($row['has_child'] && !$pageActive ) {
            foreach ($row['child'] as $child) {
                if ($child['url'] == url()->current()) {
                    $pageActive = true;
                    $isCollapsed = true;
                    $childActive = true;
                }
            }
        }
    @endphp

    <li class="sidebar-item @if($pageActive) active @endif">
        @if ($row['has_child'])
            <a data-bs-target="#collapse{{ $key }}" data-bs-toggle="collapse" class="sidebar-link @if(! $isCollapsed) collapsed @endif">
                @if (!empty($row['icon_font']))
                    <i class="{{ trim($row['icon_font']) }}"></i>
                @endif
                <span>{{ __($row['title']) }}</span>
            </a>
            <ul id="collapse{{ $key }}" class="sidebar-dropdown list-unstyled collapse @if($isCollapsed) show @endif" data-bs-parent="#sidebar">
                @foreach ($row['child'] as $child)
                    <li class="sidebar-item @if($childActive && $child['url'] == url()->current()) active @endif">
                        <a class="sidebar-link" href="{{ url($child['url']) }}">{{ __($child['title']) }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <a class="sidebar-link " href="{{ url($row['url']) }}" @if ($row['target'] !== '_self') target="{{ $row['target'] }}" @endif>
                @if (!empty($row['icon_font']))
                    <i class="{{ trim($row['icon_font']) }}"></i>
                @endif
                <span>{{ __($row['title']) }}</span>
            </a>
        @endif
    </li>
@endforeach
</ul>

        <div class="sidebar-cta">
            <div class="sidebar-cta-content">
                <strong class="d-inline-block mb-2">Weekly Sales Report</strong>
                <div class="mb-3 text-sm">
                    Your weekly sales report is ready for download!
                </div>

                <div class="d-grid">
                    <a href="https://adminkit.io/" class="btn btn-outline-primary" target="_blank">Download</a>
                </div>
            </div>
        </div>
    </div>
</nav>