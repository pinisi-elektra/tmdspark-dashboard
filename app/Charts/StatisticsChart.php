<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Balping\JsonRaw\Raw;

class StatisticsChart extends Chart
{
    use ContainsRawJson;

    /**
     * Initializes the chart.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();

        $this->options([
            'maintainAspectRatio' => false,
            'responsive'=> true,
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'fontSize' => 14,
                    'boxWidth' => 12,
                    'usePointStyle' => true
                ]
            ],
            'hover' => [
                'mode' => 'index'
            ],
            'tooltips' => [
                'enabled' => false,
                'mode' => 'index',
                'intersect' => true,
                'xAlign' => 'right',
                'custom' => $this->rawObjects[] = new Raw('
                    function(tooltip) {
                        var tooltipEl = document.getElementById(\'chartjs-tooltip\');

                        if (!tooltipEl) {
                            tooltipEl = document.createElement("div");
                            tooltipEl.id = "chartjs-tooltip";
                            tooltipEl.innerHTML = "<table></table>";
                            tooltipEl.style._bodyAlign = "right";
                            this._chart.canvas.parentNode.appendChild(tooltipEl);
                        }

                        if (tooltip.opacity === 0) {
                            tooltipEl.style.opacity = 0;
                            return;
                        }

                        tooltipEl.classList.remove("above", "below", "no-transform");
                        if (tooltip.yAlign) {
                            tooltipEl.classList.add(tooltip.yAlign);
                        } else {
                            tooltipEl.classList.add("no-transform");
                        }

                        function getBody(bodyItem) {
                            return bodyItem.lines;
                        }

                        if (tooltip.body) {
                            var titleLines = tooltip.title || [];
                            var bodyLines = tooltip.body.map(getBody);

                            var innerHtml = "<thead>";

                            titleLines.forEach(function(title) {
                                innerHtml += "<tr><th>" + title + "</th></tr>";
                            });
                            innerHtml += "</thead><tbody>";

                            bodyLines.forEach(function(body, i) {
                                var colors = tooltip.labelColors[i];
                                var style = "background:" + colors.backgroundColor;
                                style += "; border-color:" + colors.borderColor;
                                style += "; border-width: 2px";
                                var span = "<span class=\'chartjs-tooltip-key\' style=\'" + style + "\'></span>";
                                innerHtml += "<tr><td>" + span + body + "</td></tr>";
                            });

                            innerHtml +="<tr><td class=\'text-center\'><small><b>(<span class=\'text-success\'><i class=\'fa fa-arrow-up\'></i></span>/<span class=\'text-danger\'><i class=\'fa fa-arrow-down\'></i></span>) Perkembangan Dari Waktu Sebelumnya</b></small></td></tr>";
                            innerHtml += "</tbody>";

                            var tableRoot = tooltipEl.querySelector("table");
                            tableRoot.innerHTML = innerHtml;
                        }

                        var position = this._chart.canvas.getBoundingClientRect();
                        tooltipEl.style.opacity = 1;
                        tooltipEl.style.display = "block";
                        tooltipEl.style.textAlign = "right";
                        tooltipEl.style.position = "absolute";
                        tooltipEl.style.top = "10px";
                        tooltipEl.style.right = "10px";
                        tooltipEl.style.fontSize = tooltip.bodyFontSize + 3 + "px";
                        tooltipEl.style.fontStyle = tooltip._bodyFontStyle;
                        tooltipEl.style.fontFamily = "Roboto";
                        tooltipEl.style.padding = tooltip.yPadding + "px " + tooltip.xPadding + "px";
                        tooltipEl.style.border = "3px solid #145984";
                        tooltipEl.style.background = "rgba(255, 255, 255, .8)";
                        tooltipEl.style.pointerEvents = "none";
                    }
                '),
                'callbacks' => [
                    'label' => $this->rawObjects[] = new Raw('
                        function(tooltipItem, data) {
                            if (tooltipItem.index > 0) {
                                var previousdata = tooltipItem.index - 1;
                                var val = ((data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] / data.datasets[tooltipItem.datasetIndex].data[previousdata] * 100) - 100).toFixed(1);
                                var sign = "<i class=\'fa fa-arrow-up\'></i>";
                                var color = "text-success";
                                if(val < 0) {
                                    sign = "<i class=\'fa fa-arrow-down\'></i>";
                                    color = "text-danger";
                                }
                                var growth = " <b><span class=\'"+color+"\'>(" + val + "%) ("+ sign +")</span></b>";
                            }

                            else {
                                var growth = "";
                            };

                            return data.datasets[tooltipItem.datasetIndex].label + " &nbsp;:&nbsp; Rp " + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + growth;
                        }
                    ')
                ]
            ],
            'scales' => [
                'yAxes'=> [[
                    'offset' => true,
                    'stacked' => true,
                    'ticks'=> [
                        'beginAtZero'=> true,
                        'callback' => $this->rawObjects[] = new Raw('
                            function(value, index, values){
                                if(parseInt(value) >= 1000){
                                    return "Rp " + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                } else {
                                    return "Rp " + value;
                                }
                            }
                        ')
                    ],
                ]],
                'xAxes'=> [[
                    'offset' => true,
                    'stacked' => true,
                    'ticks' => [
                        'beginAtZero' => true
                    ],
                ]],
            ],
        ]);
    }
}
