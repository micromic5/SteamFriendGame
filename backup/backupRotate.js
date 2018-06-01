<?php include("steamapi.php");?>
<!DOCTYPE html>
<meta charset="utf-8">
<html lang="de">
<head>
</head>
<style>
/*.dimension { cursor: ns-resize; }*/
/*.category { cursor: ew-resize; }*/
.dimension tspan.name { font-size: 1.5em; fill: #333; font-weight: bold; }
.dimension tspan.sort { fill: #000; cursor: pointer; opacity: 0; }
.dimension tspan.sort:hover { fill: #333; }
.dimension:hover tspan.name { fill: #000; }
.dimension:hover tspan.sort { opacity: 1; }
.dimension line { stroke: #000; }
.dimension rect { stroke: none; fill-opacity: 0; }
.dimension > rect, .category-background { fill: #fff; }
.dimension > rect { display: none; }
.category:hover rect { fill-opacity: .3; }
.dimension:hover > rect { fill-opacity: .3; }
.ribbon path { stroke-opacity: 0; fill-opacity: .9; }
.ribbon path.active { fill-opacity: .9; }
.ribbon-mouse path { fill-opacity: 0; }

.category-0 { fill: #15415D; stroke: #15415D; }
.category-1 { fill: #106FAC; stroke: #106FAC; }
.category-2 { fill: #F9B233; stroke: #F9B233; }
.category-3 { fill: #d62728; stroke: #d62728; }
.category-4 { fill: #9467bd; stroke: #9467bd; }
.category-5 { fill: #8c564b; stroke: #8c564b; }
.category-6 { fill: #e377c2; stroke: #e377c2; }
.category-7 { fill: #7f7f7f; stroke: #7f7f7f; }
.category-8 { fill: #bcbd22; stroke: #bcbd22; }
.category-9 { fill: #17becf; stroke: #17becf; }

.tooltip {
  background-color: rgba(242, 242, 242, .6);
  position: absolute;
  padding: 5px;
}

body {
  font-family: sans-serif;
  font-size: 16px;
  position: relative;
}
h1, h2, .dimension text {
  text-align: center;
  font-family: "PT Sans", Helvetica;
  font-weight: 300;
}
h1 {
  font-size: 4em;
  margin: .5em 0 0 0;
}
h2 {
  font-size: 2em;
  margin: 1em 0 0.5em;
  border-bottom: solid #ccc 1px;
}
p.meta, p.footer {
  font-size: 13px;
  color: #333;
}
p.meta {
  text-align: center;
}

text.icicle { pointer-events: none; }

.options { font-size: 12px; text-align: center; padding: 5px 0; }
.curves { float: left; }
.source { float: right; }
pre, code { font-family: "Menlo", monospace; }

.html .value,
.javascript .string,
.javascript .regexp {
  color: #756bb1;
}

.html .tag,
.css .tag,
.javascript .keyword {
  color: #3182bd;
}

.comment {
  color: #636363;
}

.html .doctype,
.javascript .number {
  color: #31a354;
}

.html .attribute,
.css .attribute,
.javascript .class,
.javascript .special {
  color: #e6550d;
}
#visol{
    transform: rotate(180deg);
    margin:0;
    left:0;
}
#visol text{
    transform: rotate(180deg);
}
#visol .category-0 { fill: #F9B233; stroke: #F9B233; }
#visol .ribbon path{opacity: 1}
</style>

    <body>

        <?php
        $chart_data = "[";
        $chart_data_money = "[";
        foreach($users_array as $user)
        {
            if($user[0]!=0){
            /*
            ?>
            <h1><?=$user[5]["response"]["players"][0]["personaname"];?></h1>
            <img src='<?=$user[5]["response"]["players"][0]["avatarfull"];?>'>
            <ul>
                <li>SteamID64: <?=$user[5]["response"]["players"][0]["steamid"];?></li>
                <li>Display Name: <?=$user[5]["response"]["players"][0]["personaname"];?></li>
                <li>URL: <?=$user[5]["response"]["players"][0]["profileurl"];?></li>
                <li>Small Avatar: <?=$user[5]["response"]["players"][0]["avatar"];?></li>
                <li>Medium Avatar: <?=$user[5]["response"]["players"][0]["avatarmedium"];?></li>
                <li>Full Avatar: <?=$user[5]["response"]["players"][0]["avatarfull"];?></li>
                <li>Status: <?=personaState($user[5]['response']['players'][0]['personastate']);?></li>
                <?php
                if(array_key_exists("realname",$user[5]["response"]["players"][0]) != null)
                {
                ?>
                <li>Real Name:
                    <?= $user[5]["response"]["players"][0]["realname"];?>
                </li>
                <?php
                }
                ?>
                <li>Joined: <?=$user[6];?></li>
                <li>Played: <?=$user[1];?> free games</li>
                <li>Owns: <?=$user[2];?> games</li>
                <li>Owned Games value: <?=$user[3];?> Fr.</li>
                <li>Overall Time played: <?=$user[4];?></li>
                <li>Free Games Time played: <?=$user[7];?></li>
                <li>Owned Games Time played: <?=$user[8];?></li>
                <li>Simulation Game hours: <?=$user[11];?></li>
                <li>Action Game hours: <?=$user[12];?></li>
                <li>Strategy Game hours: <?=$user[13];?></li>
                <li>RPG Game hours: <?=$user[14];?></li>
                <li>Racing Game hours: <?=$user[15];?></li>
                <li>Adventure Game hours: <?=$user[16];?></li>
                <li>MMO Game hours: <?=$user[17];?></li>
                <li>Sports Game hours: <?=$user[18];?></li>
                <li>Other Game hours: <?=$user[19];?></li>
                <li>Free Simulation Game hours: <?=$user[20];?></li>
                <li>Free Action Game hours: <?=$user[21];?></li>
                <li>Free Strategy Game hours: <?=$user[22];?></li>
                <li>Free RPG Game hours: <?=$user[23];?></li>
                <li>Free Racing Game hours: <?=$user[24];?></li>
                <li>Free Adventure Game hours: <?=$user[25];?></li>
                <li>Free MMO Game hours: <?=$user[26];?></li>
                <li>Free Sports Game hours: <?=$user[27];?></li>
                <li>Free Other Game hours: <?=$user[28];?></li>
                <li>Games played over 100 hours: <?=$user[9];?></li>
                <li>Most Played Game: <?=$user[10][1]."   ".($user[10][0]/60)."   ".(($user[10][2]/100)==0?"Free":($user[10][2]/100)." Fr.")?></li>
                <img src='<?=  json_decode(file_get_contents("https://store.steampowered.com/api/appdetails?appids=".$user[10][1]."&filters=basic"),true)[$user[10][1]]["data"]["header_image"];?>'>
            </ul>
        <?php*/
            $gamer_name = $user[5]["response"]["players"][0]["personaname"];
            //prepare data payed simulation game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Simulation",$user[11],"Payed");
            //prepare data payed Action game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Action",$user[12],"Payed");
            //prepare data payed Strategy game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Strategy",$user[13],"Payed");
            //prepare data payed RPG game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"RPG",$user[14],"Payed");
            //prepare data payed Racing game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Racing",$user[15],"Payed");
            //prepare data payed Adventure game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Adventure",$user[16],"Payed");
            //prepare data payed MMO game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"MMO",$user[17],"Payed");
            //prepare data payed Sports game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Sports",$user[18],"Payed");
            //prepare data payed Other game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Other",$user[19],"Payed");
            //prepare data free simulation game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Simulation",$user[20],"Free");
            //prepare data free Action game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Action",$user[21],"Free");
            //prepare data free Strategy game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Strategy",$user[22],"Free");
            //prepare data free RPG game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"RPG",$user[23],"Free");
            //prepare data free Racing game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Racing",$user[24],"Free");
            //prepare data free Adventure game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Adventure",$user[25],"Free");
            //prepare data free MMO game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"MMO",$user[26],"Free");
            //prepare data free Sports game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Sports",$user[27],"Free");
            //prepare data free Other game hours
            $chart_data=generateChartData($chart_data,$gamer_name,"Other",$user[28],"Free");
            //prepare data Other game value
            $chart_data_money=generateChartDataMoney($gamer_name,"Other",$user[37]).$chart_data_money;
            //prepare data Sports game value
            $chart_data_money=generateChartDataMoney($gamer_name,"Sports",$user[36]).$chart_data_money;
            //prepare data MMO game value
            $chart_data_money=generateChartDataMoney($gamer_name,"MMO",$user[35]).$chart_data_money;
            //prepare data Adventure game value
            $chart_data_money=generateChartDataMoney($gamer_name,"Adventure",$user[34]).$chart_data_money;
            //prepare data Racing game value
            $chart_data_money=generateChartDataMoney($gamer_name,"Racing",$user[33]).$chart_data_money;
            //prepare data RPG game value
            $chart_data_money=generateChartDataMoney($gamer_name,"RPG",$user[32]).$chart_data_money;
            //prepare data Strategy game value
            $chart_data_money=generateChartDataMoney($gamer_name,"Strategy",$user[31]).$chart_data_money;
            //prepare data Action game value
            $chart_data_money=generateChartDataMoney($gamer_name,"Action",$user[30]).$chart_data_money;
            //prepare data simulation game value
            $chart_data_money=generateChartDataMoney($gamer_name,"Simulation",$user[29]).$chart_data_money;
            }
        }
        $chart_data_money=substr($chart_data_money, 0, -1);
        $chart_data_money ="[".$chart_data_money."]";
        $chart_data=substr($chart_data, 0, -1);
        $chart_data.="]";


        //adds the data in an array
        function generateChartData($chart_data,$player_name,$genre,$time,$payed){
            for($i = 0; $i < $time; $i++){
                $chart_data.='{"Player":"'.$player_name.'","Genre":"'.$genre.'","Play Type":"'.$payed.'"},';
            }
            return $chart_data;
        }

        function generateChartDataMoney($player_name,$genre,$value){
            $chart_data_money_new = "";
            for($j = 0; $j < $value; $j++){
                $chart_data_money_new.='{"Player":"'.$player_name.'","Genre":"'.$genre.'","Wert der gekauften Spiele":"Wert der gekauften Spiele"},';
            }
            return $chart_data_money_new;
        }
        ?>
    <div id="clickMe">clickMe</div>
    <div id="vis"></div>
    <br>
    <div id="visol" style="position:absolute"></div>
    <script src='d3.v2.js'></script>
    <script src="d3.parsets.js"></script>
    <!--script src="http://www.jasondavies.com/parallel-sets/d3.parsets.js"></script-->
    <!--script src="http://www.jasondavies.com/parallel-sets/highlight.min.js"></script-->
    <script>
        var chart = d3.parsets()
            .dimensions(["Play Type","Genre","Player"]);
            chart.width(1920);
            chart.height(500);

        var vis = d3.select("#vis").append("svg")
            .attr("width", chart.width()+920)
            .attr("height", chart.height());

       vis.datum(<?=$chart_data?>).call(chart);
        /* document.getElementById("clickMe").addEventListener("click", function myFunction() {
            chart.height(1080);
            vis.attr("height",chart.height());
        });*/

        var secondChart = d3.parsets()
            .dimensions(["Wert der gekauften Spiele","Genre","Player"]);
            secondChart.width(1920);
            secondChart.height(500);
        var visol = d3.select("#visol").append("svg")
            .attr("width", secondChart.width()+875)
            .attr("height", secondChart.height());

        visol.datum(<?=$chart_data_money?>).call(secondChart);
       /* setTimeout(function(){
            let dimensions =  document.querySelectorAll('g.dimension');
            for(let i = 0; i < dimensions.length; i++){
                let categorys = dimensions[i].getElementsByClassName("category");
                for (let j = 0; j < categorys.length; j++) {
                    console.log(j);
                    categorys[j].setAttribute("transform", "translate("+(categorys[j].transform.animVal[0].matrix.e+j*50)+")");
                    // console.log(categorys[i].transform.animVal[0].matrix.e);
                }
            }
        },1000);*/
    </script>
    </body>
</html>