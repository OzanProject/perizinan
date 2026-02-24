@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
@php(    $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
@php(    $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

<a href="{{ $dashboard_url }}" @if($layoutHelper->isLayoutTopnavEnabled())
class="navbar-brand {{ config('adminlte.classes_brand') }}" @else
    class="brand-link {{ config('adminlte.classes_brand') }}" @endif>

    {{-- Small brand logo --}}
    @if(isset($globalDinas) && $globalDinas && $globalDinas->logo)
        <img src="{{ Storage::url($globalDinas->logo) }}" alt="{{ $globalDinas->app_name ?? 'Logo' }}"
            class="{{ config('adminlte.logo_img_class', 'brand-image img-circle elevation-3') }}" style="opacity:.8">
    @else
        <img src="{{ asset(config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png')) }}"
            alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
            class="{{ config('adminlte.logo_img_class', 'brand-image img-circle elevation-3') }}" style="opacity:.8">
    @endif

    {{-- Brand text --}}
    <span class="brand-text font-weight-light {{ config('adminlte.classes_brand_text') }}">
        @if(isset($globalDinas) && $globalDinas && $globalDinas->app_name)
            {{ Str::limit($globalDinas->app_name, 20) }}
        @else
            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
        @endif
    </span>

</a>