<?php
use App\Models\Faucet;
use App\Models\PaymentProcessor;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 03-Mar-2015
 * Time: 18:56
 */

class FaucetPaymentProcessorsTableSeeder extends BaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentProcessors = PaymentProcessor::all();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($paymentProcessors as $paymentProcessor) {
            switch ($paymentProcessor->name) {
                case 'Direct':
                    $faucets = [
                        //19//
                        Faucet::where('name','Mellow Ads')->first(),
                        Faucet::where('name','Bit Fun')->first(),
                        Faucet::where('name','TrustBtcFaucet')->first(),
                        Faucet::where('name','BitEnergy')->first(),
                        Faucet::where('name','Field Bitcoins')->first(),
                        Faucet::where('name','CarBitcoin')->first(),
                        Faucet::where('name','Bonus Bitcoin')->first(),
                        Faucet::where('name','Claim BTC')->first(),
                        Faucet::where('name','Moon Faucet')->first(),
                        Faucet::where('name','BTC 4 You')->first(),
                        Faucet::where('name','777Bitco.in')->first(),
                        Faucet::where('name','TreeBitcoin')->first(),
                        Faucet::where('name','Bitcoin Faucet TK')->first(),
                        Faucet::where('name','Bitcoinker')->first(),
                        Faucet::where('name','BitCompound')->first(),
                        Faucet::where('name','Faucet Planet')->first(),
                        Faucet::where('name','CoinGainGuru Bitcoin Faucet')->first(),
                        Faucet::where('name','Earn Free Coins')->first(),
                        Faucet::where('name','Faucet Pig')->first(),
                    ];
                    for ($i = 0; $i < count($faucets); $i++) {
                        if (!empty($faucets[$i])) {
                            $faucets[$i]->paymentProcessors()->attach(
                                $paymentProcessor->where('name','=', 'Direct')->first()->id
                            );
                        }
                    }
                    break;
                case 'FaucetHub.io':
                    $faucets = [
                        //39//
                        Faucet::where('name','Penta Faucet')->first(),
                        Faucet::where('name','One Click Faucet')->first(),
                        Faucet::where('name','X-Faucet')->first(),
                        Faucet::where('name','Konstantinova')->first(),
                        Faucet::where('name','Bitcoin Faucetdog')->first(),
                        Faucet::where('name','Fautsy')->first(),
                        Faucet::where('name','IFaucet')->first(),
                        Faucet::where('name','Free4Faucet')->first(),
                        Faucet::where('name','FreeBits')->first(),
                        Faucet::where('name','Hot Coins')->first(),
                        Faucet::where('name','FaucetMega')->first(),
                        Faucet::where('name','BitUniverse')->first(),
                        Faucet::where('name','Marxian Roll Faucet')->first(),
                        Faucet::where('name','BTC Leets')->first(),
                        Faucet::where('name','MafiaCoins')->first(),
                        Faucet::where('name','DigiCoin')->first(),
                        Faucet::where('name','Free Bitcoin Faucet')->first(),
                        Faucet::where('name','WokeFaucet')->first(),
                        Faucet::where('name','Viral Alert Crypto Faucet')->first(),
                        Faucet::where('name','Lands of Chaos')->first(),
                        Faucet::where('name','CoinGainGuru Bitcoin Faucet')->first(),
                        Faucet::where('name','MY115 Faucet')->first(),
                        Faucet::where('name','Metal Faucet')->first(),
                        Faucet::where('name','BTCinBTC')->first(),
                        Faucet::where('name','BTC Safari')->first(),
                        Faucet::where('name','BitcoinsBest')->first(),
                        Faucet::where('name','Hue Faucet')->first(),
                        Faucet::where('name','BitLucky')->first(),
                        Faucet::where('name','AT Spot')->first(),
                        Faucet::where('name','Distan29 Faucet')->first(),
                        Faucet::where('name','Jacob\'s Faucet')->first(),
                        Faucet::where('name','CoinAd')->first(),
                        Faucet::where('name','GoBits')->first(),
                        Faucet::where('name','Captchas Rocks Bitcoin faucet')->first(),
                        Faucet::where('name','Bitaler')->first(),
                        Faucet::where('name','Bitcoin Faucet CA')->first(),
                        Faucet::where('name','Get Free Coin')->first(),
                        Faucet::where('name','RektCoins')->first(),
                        Faucet::where('name','Free Bitcoin Mine')->first(),
                    ];
                    for ($i = 0; $i < count($faucets); $i++) {
                        if (!empty($faucets[$i])) {
                            $faucets[$i]->paymentProcessors()->attach(
                                $paymentProcessor->where('name','=', 'FaucetHub.io')->first()->id
                            );
                        }
                    }
                    break;
                case 'FaucetSystem':
                    $faucets = [
                        //7//
                        Faucet::where('name','IFaucet')->first(),
                        Faucet::where('name','Lands of Chaos')->first(),
                        Faucet::where('name','BTC Safari')->first(),
                        Faucet::where('name','Hue Faucet')->first(),
                        Faucet::where('name','BitLucky')->first(),
                        Faucet::where('name','BTC Faucet Design')->first(),
                        Faucet::where('name','FreeBitcoin Today')->first(),
                    ];
                    for ($i = 0; $i < count($faucets); $i++) {
                        if (!empty($faucets[$i])) {
                            $faucets[$i]->paymentProcessors()->attach(
                                $paymentProcessor->where('name','=', 'FaucetSystem')->first()->id
                            );
                        }
                    }
                    break;
                case 'PurseFaucets':
                    $faucets = [
                        //6//
                        Faucet::where('name','IFaucet')->first(),
                        Faucet::where('name','BitcoBear')->first(),
                        Faucet::where('name','BitcoBear2')->first(),
                        Faucet::where('name','BitcoBear3')->first(),
                        Faucet::where('name','BitcoBear4')->first(),
                        Faucet::where('name','BitcoBear5')->first(),
                    ];
                    for ($i = 0; $i < count($faucets); $i++) {
                        if (!empty($faucets[$i])) {
                            $faucets[$i]->paymentProcessors()->attach(
                                $paymentProcessor->where('name','=', 'PurseFaucets')->first()->id
                            );
                        }
                    }
                    break;
                case 'Xapo':
                    $faucets = [
                        //9//
                        Faucet::where('name','Free4Faucet')->first(),
                        Faucet::where('name','Kiixa')->first(),
                        Faucet::where('name','Play Bitcoin')->first(),
                        Faucet::where('name','BitcoinBow')->first(),
                        Faucet::where('name','Bitcoinker')->first(),
                        Faucet::where('name','WaterBitcoin')->first(),
                        Faucet::where('name','We Love Bitcoin')->first(),
                        Faucet::where('name','BTC Source')->first(),
                        Faucet::where('name','CoinGainGuru Bitcoin Faucet')->first(),
                    ];
                    for ($i = 0; $i < count($faucets); $i++) {
                        if (!empty($faucets[$i])) {
                            $faucets[$i]->paymentProcessors()->attach(
                                $paymentProcessor->where('name','=', 'Xapo')->first()->id
                            );
                        }
                    }
                    break;
                case 'CoinPot':
                    $faucets = [
                        //3//
                        Faucet::where('name','Moon Bitcoin')->first(),
                        Faucet::where('name','Bit Fun')->first(),
                        Faucet::where('name','Bonus Bitcoin')->first(),
                    ];
                    for ($i = 0; $i < count($faucets); $i++) {
                        if (!empty($faucets[$i])) {
                            $faucets[$i]->paymentProcessors()->attach(
                                $paymentProcessor->where('name','=', 'CoinPot')->first()->id
                            );
                        }
                    }
                    break;
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
