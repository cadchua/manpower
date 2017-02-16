<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 3/13/2016
 * Time: 10:06 PM
 */

include("../config/conf.db.php");
include("../classes/class.dbquery.php");
include("../library/lib.general.php");

if(count($_GET)>0){
    $value=$_GET;
}else{
    $value=$_POST;
}

function getTaxDetails($payfreq,$taxcode,$payrate){
    $columnNo = 0;
//    $arr = array();

    global $conf;
    $db = new dbQuery();
    $db->connect($conf);

    $SQL = "SELECT pay_rate, tax_amount, over_tax_percent from bir where pay_rate <= $payrate and pay_frequency = '$payfreq' and tax_code = '$taxcode' ".
           "order by pay_rate desc limit 1";

    $db->sqlStatement=$SQL;

    try{
        $db->beginTransaction();
        $db->querySQL(null);
        $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (Exception $e){
        echo $e->getMessage();
    }

//    $response = array("data" => $data);
//    print json_encode($response);

    return $data;
}

function computeWithHoldingTax($payfreq,$taxcode,$payrate){

    //pay frequency
    //semi-monthly - 035004
    //weekly - 035002
    //daily - 035001

    //taxcode - Z, S/ME, S/ME1, S/ME2, S/ME3, S/ME4
    //payrate - net income

    $tax = 0;

//    echo "Pay Freq: ".$payfreq. " Tax code: ".$taxcode." Payrate: ".$payrate. " ";

    if($payfreq == "035001" || $payfreq == "035002" || $payfreq == "035004"){ //semi-monthly
        $taxamount = getTaxDetails($payfreq,$taxcode,$payrate);
        $tax = $taxamount[0]['tax_amount'] + (($payrate - $taxamount[0]['pay_rate']) * ($taxamount[0]['over_tax_percent'] /100));
    }

    /*if($payfreq == "semimonthly"){
        if($taxcode == "Z"){
            if($payrate >= 0 && $payrate <= 417){

                $tax = 0;

            }else if($payrate > 417 && $payrate <= 1250){

                $overhead = 20.83;
                $taxable = 417;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 1250 && $payrate <= 2917){

                $overhead = 104.17;
                $taxable = 1250;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 2917 && $payrate <= 5833){

                $overhead = 354.17;
                $taxable = 2917;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5833 && $payrate <= 10417){

                $overhead = 937.50;
                $taxable = 5833;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 10417 && $payrate <= 20833){

                $overhead = 2083.33;
                $taxable = 20833;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 20833){

                $overhead = 5208.33;
                $taxable = 20833;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME"){
            if($payrate >= 1 && $payrate <= 2083){

                $tax = 0;

            }else if($payrate > 2083 && $payrate <= 2500){

                $overhead = 0;
                $taxable = 2083;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 2500 && $payrate <= 3333){

                $overhead = 20.83;
                $taxable = 2500;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 3333 && $payrate <= 5000){

                $overhead = 104.17;
                $taxable = 3333;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5000 && $payrate <= 7917){

                $overhead = 354.17;
                $taxable = 5000;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 7917 && $payrate <= 12500){

                $overhead = 937.50;
                $taxable = 7917;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 12500 && $payrate <= 22917){

                $overhead = 2083.33;
                $taxable = 12500;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 22917){

                $overhead = 5208.33;
                $taxable = 22917;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME1"){
            if($payrate >= 1 && $payrate <= 3125){

                $tax = 0;

            }else if($payrate > 3125 && $payrate <= 3542){

                $overhead = 0;
                $taxable = 3125;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 3542 && $payrate <= 4375){

                $overhead = 20.83;
                $taxable = 3542;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 4375 && $payrate <= 6042){

                $overhead = 104.17;
                $taxable = 4375;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 6042 && $payrate <= 8958){

                $overhead = 354.17;
                $taxable = 6042;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 8958 && $payrate <= 13542){

                $overhead = 937.50;
                $taxable = 8958;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 13542 && $payrate <= 23958){

                $overhead = 2083.33;
                $taxable = 13542;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 23958){

                $overhead = 5208.33;
                $taxable = 23958;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME2"){
            if($payrate >= 1 && $payrate <= 4167){

                $tax = 0;

            }else if($payrate > 4167 && $payrate <= 4583){

                $overhead = 0;
                $taxable = 4167;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 4583 && $payrate <= 5417){

                $overhead = 20.83;
                $taxable = 4583;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5417 && $payrate <= 7083){

                $overhead = 104.17;
                $taxable = 5417;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 7083 && $payrate <= 10000){

                $overhead = 354.17;
                $taxable = 7083;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 10000 && $payrate <= 14583){

                $overhead = 937.50;
                $taxable = 10000;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 14583 && $payrate <= 25000){

                $overhead = 2083.33;
                $taxable = 14583;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 25000){

                $overhead = 5208.33;
                $taxable = 25000;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME3"){
            if($payrate >= 1 && $payrate <= 5208){

                $tax = 0;

            }else if($payrate > 5208 && $payrate <= 5625){

                $overhead = 0;
                $taxable = 5208;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5625 && $payrate <= 6458){

                $overhead = 20.83;
                $taxable = 5625;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 6458 && $payrate <= 8125){

                $overhead = 104.17;
                $taxable = 6458;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 8125 && $payrate <= 11042){

                $overhead = 354.17;
                $taxable = 8125;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 11042 && $payrate <= 15625){

                $overhead = 937.50;
                $taxable = 11042;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 15625 && $payrate <= 26042){

                $overhead = 2083.33;
                $taxable = 15625;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 26042){

                $overhead = 5208.33;
                $taxable = 26042;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME4"){
            if($payrate >= 1 && $payrate <= 6250){

                $tax = 0;

            }else if($payrate > 6250 && $payrate <= 6667){

                $overhead = 0;
                $taxable = 6250;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 6667 && $payrate <= 7500){

                $overhead = 20.83;
                $taxable = 6667;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 7500 && $payrate <= 9167){

                $overhead = 104.17;
                $taxable = 7500;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 9167 && $payrate <= 12083){

                $overhead = 354.17;
                $taxable = 9167;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 12083 && $payrate <= 16667){

                $overhead = 937.50;
                $taxable = 12083;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 16667 && $payrate <= 27083){

                $overhead = 2083.33;
                $taxable = 16667;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 27083){

                $overhead = 5208.33;
                $taxable = 27083;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }
    }else if($payfreq == "monthly"){
        if($taxcode == "Z"){
            if($payrate >= 0 && $payrate <= 833){

                $overhead = 41.67;
                $taxable = 833;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 833 && $payrate <= 2500){

                $overhead = 41.67;
                $taxable = 833;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 2500 && $payrate <= 5833){

                $overhead = 208.33;
                $taxable = 2500;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5833 && $payrate <= 11667){

                $overhead = 708.33;
                $taxable = 5833;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 11667 && $payrate <= 20833){

                $overhead = 1875;
                $taxable = 11667;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 20833 && $payrate <= 41667){

                $overhead = 4166.67;
                $taxable = 20833;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 41667){

                $overhead = 10416.67;
                $taxable = 41667;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME"){
            if($payrate >= 1 && $payrate <= 4167){

                $tax = 0;

            }else if($payrate > 4167 && $payrate <= 5000){

                $overhead = 0;
                $taxable = 4167;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5000 && $payrate <= 6667){

                $overhead = 41.67;
                $taxable = 5000;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 6667 && $payrate <= 10000){

                $overhead = 208.33;
                $taxable = 6667;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 10000 && $payrate <= 15833){

                $overhead = 708.33;
                $taxable = 10000;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 15833 && $payrate <= 25000){

                $overhead = 1875;
                $taxable = 15833;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 25000 && $payrate <= 45833){

                $overhead = 4166.67;
                $taxable = 25000;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 45833){

                $overhead = 10416.67;
                $taxable = 45833;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }

        }else if($taxcode == "SME1"){
            if($payrate >= 1 && $payrate <= 6250){

                $tax = 0;

            }else if($payrate > 6250 && $payrate <= 7083){

                $overhead = 0;
                $taxable = 6250;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 7083 && $payrate <= 8750){

                $overhead = 41.67;
                $taxable = 7083;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 8750 && $payrate <= 12083){

                $overhead = 208.33;
                $taxable = 8750;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 12083 && $payrate <= 17917){

                $overhead = 708.33;
                $taxable = 12083;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 17917 && $payrate <= 27083){

                $overhead = 1875;
                $taxable = 17917;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 27083 && $payrate <= 47917){

                $overhead = 4166.67;
                $taxable = 27083;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 47917){

                $overhead = 10416.67;
                $taxable = 47917;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME2"){
            if($payrate >= 1 && $payrate <= 8333){

                $tax = 0;

            }else if($payrate > 8333 && $payrate <= 9167){

                $overhead = 0;
                $taxable = 8333;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 9167 && $payrate <= 10833){

                $overhead = 41.67;
                $taxable = 9167;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 10833 && $payrate <= 14167){

                $overhead = 208.33;
                $taxable = 10833;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 14167 && $payrate <= 20000){

                $overhead = 708.33;
                $taxable = 14167;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 20000 && $payrate <= 29167){

                $overhead = 1875;
                $taxable = 20000;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 29167 && $payrate <= 50000){

                $overhead = 4166.67;
                $taxable = 29167;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 50000){

                $overhead = 10416.67;
                $taxable = 50000;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME3"){
            if($payrate >= 1 && $payrate <= 10417){

                $tax = 0;

            }else if($payrate > 10417 && $payrate <= 11250){

                $overhead = 0;
                $taxable = 10417;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 11250 && $payrate <= 12917){

                $overhead = 41.67;
                $taxable = 11250;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 12917 && $payrate <= 16250){

                $overhead = 208.33;
                $taxable = 12917;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 16250 && $payrate <= 22083){

                $overhead = 708.33;
                $taxable = 16250;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 22083 && $payrate <= 31250){

                $overhead = 1875;
                $taxable = 22083;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 31250 && $payrate <= 52083){

                $overhead = 4166.67;
                $taxable = 31250;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 52083){

                $overhead = 10416.67;
                $taxable = 52083;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME4"){
            if($payrate >= 1 && $payrate <= 12500){

                $tax = 0;

            }else if($payrate > 12500 && $payrate <= 13333){

                $overhead = 0;
                $taxable = 12500;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 13333 && $payrate <= 15000){

                $overhead = 41.67;
                $taxable = 13333;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 15000 && $payrate <= 18333){

                $overhead = 208.33;
                $taxable = 15000;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 18333 && $payrate <= 24167){

                $overhead = 708.33;
                $taxable = 18333;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 24167 && $payrate <= 33333){

                $overhead = 1875;
                $taxable = 24167;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 33333 && $payrate <= 54167){

                $overhead = 4166.67;
                $taxable = 33333;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 54167){

                $overhead = 10416.67;
                $taxable = 54167;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }
    }*/

    return $tax;
}

//echo "Withholding Tax =  ".computeWithHoldingTax("semimonthly","SME", 19484.35);
