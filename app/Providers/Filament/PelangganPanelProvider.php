<?php

namespace App\Providers\Filament;

use App\Filament\Pelanggan\Pages\DashboardPelanggan;
use App\Filament\Pelanggan\Pages\PelangganLogin;
use App\Filament\Pelanggan\Pages\PelangganRegistration;
use App\Filament\Pelanggan\Widgets\PelangganStatOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PelangganPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pelanggan')
            ->path('')
            ->colors([
                'primary' => Color::Yellow,
            ])
            ->favicon(asset('img/logo.png'))
            ->login(PelangganLogin::class)
            ->registration(PelangganRegistration::class)
            ->authGuard('pelanggan')
            ->topNavigation()
            ->discoverResources(in: app_path('Filament/Pelanggan/Resources'), for: 'App\Filament\Pelanggan\Resources')
            ->discoverPages(in: app_path('Filament/Pelanggan/Pages'), for: 'App\Filament\Pelanggan\Pages')
            ->pages([
                DashboardPelanggan::class
            ])
            ->discoverWidgets(in: app_path('Filament/Pelanggan/Widgets'), for: 'App\Filament\Pelanggan\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                PelangganStatOverview::class
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->renderHook(PanelsRenderHook::BODY_END,
              fn(): string => Blade::render('
                <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config("midtrans.client_key") }}"></script>
                <script>
                    window.addEventListener("open-midtrans-snap", event => {
                        window.snap.pay(event.detail.snapToken, {
                            onSuccess: function(result) {
                                window.location.reload();
                            },
                            onPending: function(result) {
                                window.location.reload();
                            },
                            onError: function(result) {
                                console.log(result);
                            }
                        });
                    });
                </script>
            ')
            )
            ->spa();
    }
}
