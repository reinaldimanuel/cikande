<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Pond;
use App\Models\SensorReading;
use App\Models\SensorSettings;
use App\Models\FeedingSchedule;
use App\Models\FeedingHistory;
use App\Models\Feeder;
use View;

class PondController extends Controller
{
    public function index(Request $request) {
        $query = Pond::where('status_pond', 'Active');

        if ($request->has('search')) {
            $query->where('name_pond', 'like', '%' . $request->search . '%');
        }

        $ponds = $query->orderBy('name_pond')->get();


        return view('app-pond', compact('ponds'));
    }

    public function show(Request $request, $id_pond){
        // Fetch the pond data
        $pond = Pond::where('id_pond', $id_pond)->firstOrFail();

        // Fetch the latest sensor reading for this pond
        $latestReadings  = SensorReading::where('id_pond', $id_pond)->orderBy('created_at', 'asc')->get();

        // Range date filter: default from 7 days ago to now
        $startDate = $request->input('start_date', Carbon::now()->subDays(7)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // Fetch sensor readings within the range (default: 7 days)
        $sensorReadings = SensorReading::where('id_pond', $id_pond)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);;

        // Fetch pond settings
        $settings = SensorSettings::where('id_pond', $id_pond)->first();

        //Fetch Feeding Schedule and Histories
        $schedules = FeedingSchedule::where('id_pond', $id_pond)->orderBy('feeding_time')->get();
        $histories = FeedingHistory::where('id_pond', $id_pond)->orderBy('feeding_time', 'desc')->get();
        $feeder = Feeder::where('id_pond', $id_pond)->first();

        return view('app-ponddetail', compact('pond', 'latestReadings', 'sensorReadings', 'settings','schedules','histories','feeder', 'startDate', 'endDate'));
    }

    public function store(Request $request) {
        // Validate input data
        $request->validate([
            'name_pond' => 'required',
            'birth_fish' => 'required|date',
            'total_fish' => 'required|integer',
        ]);
    
        // Create the pond with default status "Active"
        $pond = Pond::create($request->all() + ['status_pond' => 'Active']);
    
        // Insert default sensor settings for the new pond
        SensorSettings::create([
            'id_pond'          => $pond->id_pond,
            'min_ph'           => 6.5,   // Optimal min pH for most fish
            'max_ph'           => 8.5,   // Optimal max pH
            'min_temperature'  => 24.0,  // Optimal min temp (°C)
            'max_temperature'  => 30.0,  // Optimal max temp (°C)
            'min_tds'          => 300,   // Minimum TDS (ppm)
            'max_tds'          => 1200,  // Maximum TDS (ppm)
            'min_conductivity' => 500,   // Minimum Conductivity (µS/cm)
            'max_conductivity' => 1500,  // Maximum Conductivity (µS/cm)
            'min_salinity'     => 0.1,   // Minimum Salinity (ppt)
            'max_salinity'     => 35.0   // Maximum Salinity (ppt)
        ]);

        Feeder::create([
            'id_pond' => $pond->id_pond,
            'feeder_status' => 'Kosong'
        ]);        
    
        return redirect()->route('kolam.index')->with('success', 'Kolam berhasil ditambahkan!');
    }

    public function update(Request $request, $id_pond) {
        $pond = Pond::findOrFail($id_pond);
        $pond->update($request->except('id_pond'));

        return redirect()->route('kolam.index')->with('success', 'Berhasil, data kolam telah diperbarui!');
    }

    public function deactivate(Request $request, $id_pond) {

        $pond = Pond::findOrFail($id_pond);
    
        $pond->status_pond = 'Deactive';
        $pond->deact_reason = $request->input('deact_reason');
        $pond->save();

        return redirect()->route('kolam.index')->with('success', 'Kolam dihapus!');
    }

    public function updatesensor(Request $request, $id_pond)
    {
        // Find the record by id_pond
        $settings = SensorSettings::where('id_pond', $id_pond)->firstOrFail();

        // Validate the request data (optional but recommended)
        $validatedData = $request->validate([
            'min_ph' => 'nullable|numeric',
            'max_ph' => 'nullable|numeric',
            'min_temperature' => 'nullable|numeric',
            'max_temperature' => 'nullable|numeric',
            'min_tds' => 'nullable|integer',
            'max_tds' => 'nullable|integer',
            'min_conductivity' => 'nullable|integer',
            'max_conductivity' => 'nullable|integer',
            'min_salinity' => 'nullable|numeric',
            'max_salinity' => 'nullable|numeric',
        ]);

        // Update the record
        $settings->update($validatedData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Berhasil, nilai sensor telah diubah!');
    }

    public function updatetotalfood(Request $request, $id)
    {
        // Find the feeding schedule by ID
        $feeder = Feeder::where('id', $id)->firstOrFail();

        // Validate the request
        $request->validate([
            'total_food' => 'required|integer', // Ensures the time format is valid
        ]);

        // Update the feeding time
        $feeder->update([
            'total_food' => $request->input('total_food'),
        ]);

        return redirect()->back()->with('success', 'Berhasil, jumlah pakan telah diubah!');
    }
}