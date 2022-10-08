<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function addSupplier(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required|string|unique:suppliers,name',
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        } else {
            Supplier::create([
                'name' => $request->name,
                'amount' => $request->amount,
            ]);
            return response()->json([
                'success' => "Supplier $request->name added successfully",
            ]);
        }
    }

    public function deleteSupplier($suppId)
    {
        $supplier = Supplier::find($suppId);
        if (!$supplier) {
            return response()->json([
                'error' => 'supplier not found',
            ]);
        } else {
            $supplier->delete();
            return response()->json([
                'success' => "$supplier->name deleted successfully", 
            ]);
        }
    }
}
