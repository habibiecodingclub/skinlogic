<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\LoginUser;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\AdvancedStatsOverviewWidget;
use App\Filament\Widgets\MonthlyUsersChart;
use App\Filament\Widgets\PelangganLoyalTable;
use App\Filament\Widgets\PelangganStats;
use App\Filament\Widgets\PemasukanStats;
use App\Filament\Widgets\ProdukTerlarisTable;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\StokOverview;
use App\Models\StokMovement;
use Doctrine\DBAL\Schema\Schema;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use Filament\Facades\Filament;
use Filament\Forms\Components\Group;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Psy\Output\Theme;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(LoginUser::class)
            ->colors([
                'primary' => Color::Blue,
            ])
             ->brandName("SkinLogic")
            ->brandLogo(asset('images/brandSL.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                PelangganStats::class,
                PelangganLoyalTable::class,
                ProdukTerlarisTable::class,
                // StokOverview::class

                // PemasukanStats::class,
                // StatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')

            ->authMiddleware([
                Authenticate::class,
            ])
            ;
    }
}
