$(document).ready(function () {
  $("#staff_id").select2({
    dropdownAutoWidth: true,
    width: "100%",
    placeholder: "Select Staff",
  });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    type: "get",
    url: "get_lead_type",
    datatype: "text/html",
   

    success: function (data) {
      console.log(data);
      var res=JSON.parse(data);
      console.log(res);
      var $primary = '#5A8DEE',
    $success = '#39DA8A',
    $danger = '#FF5B5C',
    $warning = '#FDAC41',
    $info = '#00CFDD',
    $label_color = '#475F7B',
    grid_line_color = '#dae1e7',
    scatter_grid_color = '#f3f3f3',
    $scatter_point_light = '#E6EAEE',
    $scatter_point_dark = '#5A8DEE',
    $white = '#fff',
    $black = '#000';

  var themeColors = [$primary, $warning, $danger, $success, $info, $label_color];
      var pieChartctx = $("#simple-pie-chart");

// Chart Options
var piechartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  responsiveAnimationDuration: 500,
//   title: {
//     display: true,
//     text: 'Assigned Leads'
//   }
};

// Chart Data
var piechartData = {
  labels: res.label,
  datasets: [{
    label: "My First dataset",
    data: res.data,
    backgroundColor: themeColors,
  }]
};

var pieChartconfig = {
  type: 'pie',

  // Chart Options
  options: piechartOptions,

  data: piechartData
};

// Create the chart
var pieSimpleChart = new Chart(pieChartctx, pieChartconfig);
 
    },
    error: function (data) {
      // console.log(data);
    },
  });
  barchart();
  linechart();
});
  function barchart(value)
{

if (value == undefined) {
  value = $('.bar_select').html();
}
$("#analytics-bar-chart").empty();
var $primary = "#5A8DEE";
var $gray_light = "#828D99";


$.ajax({
  type: "post",
  url: "get_bar_chart",
  datatype: "json",
  data: {
    value: value,
  },

  success: function (data) {
    $('.bar_select').html(value);
    console.log(data);
    var res=JSON.parse(data);
    $('.total_inc').html('₹'+res.total_income.toLocaleString('en-IN'));
    $('.total_exp').html('₹'+res.total_expense.toLocaleString('en-IN'));
   // Bar Chart
// ---------
var analyticsBarChartOptions = {
  chart: {
    height: 260,
    type: "bar",
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: "60%",
      endingShape: "rounded",
    },
  },
  dataLabels: {
    enabled: false,
  },
  colors: [$primary, "#B6CDF8"],
  fill: {
    type: "gradient",
    gradient: {
      shade: "light",
      type: "vertical",
      inverseColors: true,
      opacityFrom: 1,
      opacityTo: 1,
      stops: [0, 70, 100],
    },
  },
  series: [
    {
      name: "Income",
      data: res.income,
    },
    {
      name: "Expenses",
      data: res.expense,
    },
  ],
  xaxis: {
    categories: res.month,
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    labels: {
      style: {
        colors: $gray_light,
      },
    },
  },
  yaxis: {
    min: 0,
    max: res.max,
    tickAmount: 5,
    labels: {
      style: {
        color: $gray_light,
      },
    },
  },
  legend: {
    show: false,
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return "₹" + val.toLocaleString('en-IN');
      },
    },
  },
};

var analyticsBarChart = new ApexCharts(
  document.querySelector("#analytics-bar-chart"),
  analyticsBarChartOptions
);

analyticsBarChart.render();
  },
  error: function (data) {
    console.log(data);
  },
});



}
function linechart(value)
{

if (value == undefined) {
  value = $('.line_select').html();
}

var $primary = '#5A8DEE',

grid_line_color = '#dae1e7';



$.ajax({
  type: "post",
  url: "get_line_chart",
  datatype: "json",
  data: {
    value: value,
  },

  success: function (data) {
    
    var res=JSON.parse(data);
console.log(res);
$('.line_select').html(value);
$('.quo_fin').html('₹'+res.total_finalize.toLocaleString('en-IN'));
    // Line Chart
    // ------------------------------------------
  
    //Get the context of the Chart canvas element we want to select
    var lineChartctx = $("#line-chart");
  
    // Chart Options
    var linechartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'top',
      },
      hover: {
        mode: 'label'
      },
      scales: {
        xAxes: [{
          display: true,
          gridLines: {
            color: grid_line_color,
          },
          scaleLabel: {
            display: true,
          }
        }],
        yAxes: [{
          display: true,
          gridLines: {
            color: grid_line_color,
          },
          scaleLabel: {
            display: true,
          }
        }]
      },
      title: {
        display: true,
        text: 'Finalize Quotation'
      }
    };
  
    // Chart Data
    var linechartData = {
      labels: res.month,
      datasets: [{
        label: "Finalize",
        data: res.finalize,
        borderColor: $primary,
        fill: true
      }]
    };
  
    var lineChartconfig = {
      type: 'line',
  
      // Chart Options
      options: linechartOptions,
      data: linechartData
    };
    // Create the chart
    var lineChart = new Chart(lineChartctx, lineChartconfig);
  },
  error: function (data) {
    console.log(data);
  },
});



}

