use Illuminate\Support\Facades\Schedule;

Schedule::command('products:sync-sheet')->hourly();
