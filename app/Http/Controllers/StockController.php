<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Supplier;


class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::with('supplier')->orderBy('created_at', 'desc')->paginate(10);
        $suppliers = Supplier::all();

        return view('admin.dashboard.stock.index', compact('stocks', 'suppliers'));
    }


    /**
     * Show the form for creating a new resource.
     */
// StockController.php

public function create()
{
    // Fetch all medicines from the stock table
    $medicines = Stock::all();

    // Pass medicines to the view
    return view('admin.dashboard.medicine.create', compact('medicines'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'medicine' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date',
            'unit_cost' => 'required|numeric',
            'purchase_date' => 'required|date',
        ]);

        // Store the stock
        Stock::create([
            'medicine' => $request->medicine,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'expiry_date' => $request->expiry_date,
            'unit_cost' => $request->unit_cost,
            'purchase_date' => $request->purchase_date,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stock = Stock::with(['supplier'])->findOrFail($id);
    
        return view('admin.dashboard.stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $suppliers = Supplier::all();
        return view('admin.dashboard.stock.edit', compact('stock', 'suppliers'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully');
    }
}
