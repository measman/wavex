<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\from;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_currency_id',
        'to_currency_id',
        'from_amount',
        'to_amount',
        'status',
        'type',
        'exchangerate',
        'unit'
    ];

    protected $with = ['user', 'tocurrency','fromcurrency', 'fees'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tocurrency(){
        return $this->belongsTo(Currency::class,'to_currency_id');
    }
    public function fromcurrency(){
        return $this->belongsTo(Currency::class,'from_currency_id');
    }


    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function scopeSearch(Builder $query, $search = null)
    {
        return $query->when($search, function ($query) use ($search) {
            // Search by `currency` code in the `fromWallet` relation
            $query->whereHas('fromWallet', function ($query) use ($search) {
                $query->whereHas('currency', function ($query) use ($search) {
                    $query->where('code', 'like', '%' . $search . '%');
                });
            });
        });
    }

    public function getextrainfo() {
        $response = [];
        $response['id'] = $this->select('id')
                               ->orderBy('id', 'desc')
                               ->first()?->id;
        $response['date'] = Carbon::today()->toDateString();
        return $response;
    }
    
    public function fetchall(){
        $data = Transaction::all();
        foreach ($data as &$row) {
            // $row->from_currency=$row['fromcurrency']['code'];
            $row->from_currency=$row['fromcurrency']['code'] . ' ' . $row['from_amount'];
            $row->to_currency=$row['tocurrency']['code']. ' ' . $row['to_amount'];
            $row->name=$row['user']['name'];
            $row->action_buttons = $this->generateActionButtons($row);
            
        }
        return $data;
    }

    public function generateActionButtons($row)
    {
        return '
        <button type="button" title="Edit" data-id="' . $row->id . '" 
                class="transaction-edit bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
            <i class="icon fa fa-edit mr-1"></i>Edit
        </button>
        <button type="button" title="Delete" data-id="' . $row->id . '" 
                class="transaction-delete bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-2 rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 ml-2">
            <i class="icon fa fa-trash mr-1"></i>Delete
        </button>';
    }

    public function updatetransactioninfo($request){
        $transaction = Transaction::find($request->id);
        $transaction->status = $request->status;
        $transaction->save();
    }

    public function storebuytransaction(
        int $user_id,
        int $from_curency_id,
        int $to_curency_id,
        float $amount_from,
        float $amount_to,
        string $type,
        float $exchange_rate,
        int $unit
    ) {
        Transaction::create([
            'user_id' => $user_id,
            'from_currency_id' => $from_curency_id,
            'to_currency_id' => $to_curency_id,
            'from_amount' => $amount_from,
            'to_amount' => $amount_to,
            'status' => 'Completed',
            'type' => $type,
            'exchangerate' => $exchange_rate,
            'unit' => $unit

        ]);
    }

    public function storeselltransaction(
        int $user_id,
        int $from_curency_id,
        int $to_curency_id,
        float $amount_from,
        float $amount_to,
        string $type,
        float $exchange_rate,
        int $unit
    ) {
        Transaction::create([
                'user_id' => $user_id,
                'from_currency_id' => $from_curency_id,
                'to_currency_id' => $to_curency_id,
                'from_amount' => $amount_to,
                'to_amount' => $amount_from,
                'status' => 'Completed',
                'type' => $type,
                'exchangerate' => $exchange_rate,
                'unit' => $unit
            ]);
    }
    
}
