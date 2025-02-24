<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Fortify::createUsersUsing(CreateNewUser::class);
    Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    Fortify::authenticateUsing(function (LoginRequest $request) {
      try {
        $request->authenticate();

        $user = User::where('email', $request->email)->first();

        // Set the current brand if not set
        if (!$user->current_brand_id) {
          $latestBrand = $user->brands()
            ->latest()
            ->first();

          if ($latestBrand) {
            $user->current_brand_id = $latestBrand->id;
            $user->save();
          } else {
            throw ValidationException::withMessages([
              'email' => 'No brands associated with this account.',
            ]);
          }
        }

        // Verify brand access
        if (!$user->brands()->where('brands.id', $user->current_brand_id)->exists()) {
          throw ValidationException::withMessages([
            'email' => 'Access to the selected brand is not authorized.',
          ]);
        }

        return $user;
      } catch (ValidationException $e) {
        throw $e;
      }

    });

    RateLimiter::for('login', function (Request $request) {
      $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

      return Limit::perMinute(5)->by($throttleKey);
    });

    RateLimiter::for('two-factor', function (Request $request) {
      return Limit::perMinute(5)->by($request->session()->get('login.id'));
    });
  }
}
