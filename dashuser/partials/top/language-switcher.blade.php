@if (is_plugin_active('language'))
    @php
        $supportedLocales = Language::getSupportedLocales();

        if (empty($options)) {
            $options = [
                'before' => '',
                'lang_flag' => true,
                'lang_name' => true,
                'class' => '',
                'after' => '',
            ];
        }

        $languageDisplay = setting('language_display', 'all');
        $showRelated = setting('language_show_default_item_if_current_version_not_existed', true);
    @endphp
    
    @if ($supportedLocales && count($supportedLocales) > 1)
        <!-- Nav Item - Language Switcher -->
        <li class="nav-item dropdown">
            <a class="nav-flag dropdown-toggle" href="#" id="languageDropdown" data-bs-toggle="dropdown">
                @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                    <img src="{{ DashHelper::flagUrl(Language::getCurrentLocaleFlag()) }}" alt="{{ Language::getCurrentLocaleName() }}" />
                @endif
            </a>
            <!-- Dropdown - Language Switcher -->
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                @foreach ($supportedLocales as $localeCode => $properties)
                    @if ($localeCode != Language::getCurrentLocale())
                        <a class="dropdown-item" href="{{ $showRelated ? Language::getLocalizedURL($localeCode) : url($localeCode) }}">
                            @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                                <img src="{{ DashHelper::flagUrl($properties['lang_flag']) }}" alt="{{ $properties['lang_name'] }}" width="20" class="align-middle me-1" />
                            @endif
                            @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                                <span class="align-middle">{{ $properties['lang_name'] }}</span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </li>
    @endif
@endif


