<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:50', $this->employeeIdRule()],
            'name'        => 'required|string|max:255',
            'password'    => 'required|string|size:6',
        ]);

        $user = User::where('employee_id', $validated['employee_id'])->first();

        if ($user) {
            if (mb_strtolower(trim($user->name)) !== mb_strtolower(trim($validated['name']))) {
                return back()->withInput()->withErrors([
                    'name' => 'Bu sicil numarası farklı bir isimle kayıtlı. Bilgilerinizi kontrol edin.',
                ]);
            }

            if (! Hash::check($validated['password'], $user->password)) {
                return back()->withInput()->withErrors([
                    'password' => 'Şifre hatalı.',
                ]);
            }
        } else {
            // Aynı isimle (başka bir kimlik no altında) zaten kayıtlı kullanıcı var mı?
            $nameTaken = User::whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim($validated['name']))])->exists();

            if ($nameTaken) {
                return back()->withInput()->withErrors([
                    'name' => 'Bu ad soyad ile zaten kayıtlı bir kullanıcı var. Farklı bir kimlik no ile daha önce kayıt olduysanız, o bilgilerle giriş yapın veya "Şifremi Unuttum" seçeneğini kullanın.',
                ]);
            }

            $user = User::create([
                'employee_id' => $validated['employee_id'],
                'name'        => $validated['name'],
                'email'       => $validated['employee_id'] . '@sirket.local',
                'password'    => Hash::make($validated['password']),
                'role'        => 'personel',
            ]);
        }

        session(['user_id' => $user->id]);

        return redirect()->route('rooms.index')->with('success', 'Hoş geldiniz, ' . $user->name . '.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('user_id');
        $request->session()->regenerate();

        return redirect()->route('login')->with('success', 'Çıkış yapıldı.');
    }

    /**
     * Şifremi Unuttum - Adım 1: Kimlik no + ad soyad doğrulama formu.
     */
    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Şifremi Unuttum - Adım 1: Kimlik + isim eşleşmesini kontrol eder,
     * eşleşirse sıfırlama izni session'a yazılır ve yeni şifre formuna yönlendirilir.
     */
    public function verifyForgotPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'name'        => 'required|string|max:255',
        ]);

        $user = User::where('employee_id', $validated['employee_id'])->first();

        if (! $user || mb_strtolower(trim($user->name)) !== mb_strtolower(trim($validated['name']))) {
            return back()->withInput()->withErrors([
                'employee_id' => 'Girilen kimlik no ve ad soyad eşleşen bir kayıt bulunamadı.',
            ]);
        }

        session(['password_reset_user_id' => $user->id]);

        return redirect()->route('password.reset-form');
    }

    /**
     * Şifremi Unuttum - Adım 2: Yeni şifre belirleme formu.
     * Sadece bir önceki adımda doğrulama yapan kullanıcı erişebilir.
     */
    public function showResetPasswordForm(): View|RedirectResponse
    {
        $userId = session('password_reset_user_id');

        if (! $userId || ! User::find($userId)) {
            return redirect()->route('password.forgot')->withErrors([
                'employee_id' => 'Önce kimlik doğrulaması yapmanız gerekiyor.',
            ]);
        }

        return view('auth.reset-password');
    }

    /**
     * Şifremi Unuttum - Adım 2: Yeni şifreyi kaydeder.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $userId = session('password_reset_user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            return redirect()->route('password.forgot')->withErrors([
                'employee_id' => 'Oturum süresi doldu, lütfen tekrar deneyin.',
            ]);
        }

        $validated = $request->validate([
            'password' => 'required|string|size:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        session()->forget('password_reset_user_id');

        return redirect()->route('login')->with('success', 'Şifreniz güncellendi. Yeni şifrenizle giriş yapabilirsiniz.');
    }

    /**
     * Şirket Kimlik No için özel doğrulama kuralı.
     */
    private function employeeIdRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return;
            }

            if (! preg_match('/^\d{6}$/', $value)) {
                $fail('Kimlik no geçerli bir e-posta adresi olmalı veya 6 haneli rakamlardan oluşmalı.');
                return;
            }

            $digits = array_map('intval', str_split($value));

            $isAscending = true;
            for ($i = 1; $i < count($digits); $i++) {
                if ($digits[$i] !== $digits[$i - 1] + 1) {
                    $isAscending = false;
                    break;
                }
            }

            $isDescending = true;
            for ($i = 1; $i < count($digits); $i++) {
                if ($digits[$i] !== $digits[$i - 1] - 1) {
                    $isDescending = false;
                    break;
                }
            }

            if ($isAscending || $isDescending) {
                $fail('Kimlik no sıralı rakamlardan oluşamaz (örn: 123456, 654321).');
                return;
            }

            if (count(array_unique($digits)) !== count($digits)) {
                $fail('Kimlik no tekrarlı rakamlar içeremez, her rakam farklı olmalı.');
                return;
            }
        };
    }
}