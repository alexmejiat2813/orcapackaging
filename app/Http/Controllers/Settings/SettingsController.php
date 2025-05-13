<?

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function profile()
    {
        return view('settings.profile');
    }

    public function password()
    {
        return view('settings.password');
    }

    public function notifications()
    {
        return view('settings.notifications');
    }
}
