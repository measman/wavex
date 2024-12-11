<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'cur_id',
        'amount',
        'type',
    ];


    public function fetchall()
    {
        $data = LocalTransaction::all();
        foreach ($data as &$row) {
            $row->currency_name = $this->getcurrencyname($row);
            $row->action_buttons = $this->generateActionButtons($row);
        }
        return $data;
    }
    public function generateActionButtons($row)
    {
        return '
        <button type="button" title="Edit" data-id="' . $row->id . '" 
                class="localtransaction-edit bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
            <i class="icon fa fa-edit mr-1"></i>Edit
        </button>
        <button type="button" title="Delete" data-id="' . $row->id . '" 
                class="localtransaction-delete bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-2 rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 ml-2">
            <i class="icon fa fa-trash mr-1"></i>Delete
        </button>';
    }
    public function getcurrencyname($row)
    {
        $currency = Currency::where('id', $row->cur_id)->first();
        return $currency->name;
    }
    public function getsingleinfo($id) {
        $data = LocalTransaction::where('id', $id)->first();
        if ($data) {
            $data->currency_name = $this->getcurrencyname($data);
        }
        return $data;
    }
    
    public function updatetransactioninfo(Request $request) {
        $id = $request->input('id');
        $localtransaction = LocalTransaction::where('id', $id)->first();
        $localtransaction->amount = $request->input('amount');
        $localtransaction->type = $request->input('type');
        $localtransaction->save();
    }
    

    public function createlocaltransaction(Request $request) {  
        $amount = $request->input('amount');
        $type = $request->input('type');
        $user_id = Auth::user()->id; 
        $cur_id = Settings::where('user_id', $user_id)->value('currency_id');
        LocalTransaction::create([
            'cur_id' => $cur_id,
            'amount' => $amount,
            'type' => $type,
        ]);
    }
    
}
