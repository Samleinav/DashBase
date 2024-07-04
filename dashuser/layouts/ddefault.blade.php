@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
    <main>
        <section class="tp-page-area pb-80 pt-50">
            <div class="container">
                {!! Theme::content() !!}
            </div>
        </section>
    </main>
@endsection

