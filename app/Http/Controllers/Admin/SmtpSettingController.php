<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Dotenv\Dotenv; // Required for updating .env file

class SmtpSettingController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('manage settings');
        $setting = SmtpSetting::query()->latest()->first();
        return view('admin.smtp.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage settings');

        $data = $request->validate([
            // Validation remains required
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',

            'encryption' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'nullable|string',
            'receiving_email' => 'nullable|email',
        ]);

        SmtpSetting::updateOrCreate([], $data);

        // --- FIX FOR .ENV UPDATE AND MAILER FALLBACK ---

        // 1. Force the MAILER to 'smtp' in the running environment
        config(['mail.default' => 'smtp']);
        config(['mail.mailers.smtp.host' => $data['host']]);

        // 2. Update .env file to ensure future processes (like queue workers starting up)
        //    do not fall back to 'log' if the DB is read first.
        $this->updateDotEnvFile('MAIL_MAILER', 'smtp');
        $this->updateDotEnvFile('MAIL_HOST', $data['host']);
        $this->updateDotEnvFile('MAIL_PORT', $data['port']);
        $this->updateDotEnvFile('MAIL_USERNAME', $data['username']);
        $this->updateDotEnvFile('MAIL_PASSWORD', $data['password']);
        $this->updateDotEnvFile('MAIL_ENCRYPTION', $data['encryption'] ?? 'null');
        $this->updateDotEnvFile('MAIL_FROM_ADDRESS', $data['from_address']);

        // --- END FIX ---

        return back()->with('success', 'SMTP settings saved. Note: Queue worker must be restarted.');
    }

    /**
     * Test SMTP connection by sending a test email.
     */
    public function testConnection(Request $request)
    {
        $this->authorize('manage settings');

        $request->validate([
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
            'encryption' => 'nullable|string',
            'from_address' => 'required|email',
        ]);

        try {
            // Backup current mail configuration
            $backup = config('mail');

            // Set dynamic configuration for the test
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp' => [
                    'transport' => 'smtp',
                    'host' => $request->host,
                    'port' => $request->port,
                    'encryption' => $request->encryption === 'none' ? null : $request->encryption,
                    'username' => $request->username,
                    'password' => $request->password,
                    'timeout' => null,
                ],
                'mail.from' => [
                    'address' => $request->from_address,
                    'name' => $request->from_name ?? config('app.name'),
                ],
            ]);

            // Re-register the mailer with new config
            \Illuminate\Support\Facades\Mail::purge('smtp');

            // Send test email
            \Illuminate\Support\Facades\Mail::to($request->from_address)->send(new \App\Mail\SendOtpMail("123456"));

            // Restore backup config (optional, but good practice)
            config(['mail' => $backup]);

            return response()->json([
                'status' => 'success',
                'message' => 'Connection successful! A test email has been sent to ' . $request->from_address
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Helper to safely update a key-value pair in the .env file.
     * Requires the 'vlucas/phpdotenv' package (usually included in Laravel).
     * This is generally not recommended but implemented as per user request.
     */
    protected function updateDotEnvFile(string $key, string $newValue)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $oldValue = env($key);
            $oldValue = ($oldValue === false) ? '' : $oldValue;

            // Handle quotes for values containing spaces
            $quotedNewValue = (strpos($newValue, ' ') !== false || empty($newValue) || $newValue === 'null')
                ? $newValue
                : $newValue;

            if (strpos(file_get_contents($path), $key) !== false) {
                // Key exists, replace it
                file_put_contents($path, str_replace(
                    "$key=" . $oldValue,
                    "$key=" . $quotedNewValue,
                    file_get_contents($path)
                ));
            } else {
                // Key does not exist, append it
                file_put_contents($path, PHP_EOL . "$key=" . $quotedNewValue . PHP_EOL, FILE_APPEND);
            }
        }
    }
}
