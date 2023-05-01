<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Coin;

class FetchCoinData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:coin-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches data from the CoinGecko API and stores it in the table.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = Http::get('https://api.coingecko.com/api/v3/coins/list?include_platform=true');
        $coins = $response->json();

        foreach ($coins as $coin) {
            Coin::updateOrCreate(['id' => $coin['id']], [
                'symbol' => $coin['symbol'],
                'name' => $coin['name'],
                'platforms' => json_encode($coin['platforms']),
            ]);
        }

        $this->info('Coin data fetched successfully!');
    }
}
