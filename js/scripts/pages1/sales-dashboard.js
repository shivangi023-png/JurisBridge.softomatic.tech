$(document).ready(function(){
    $.ajax({
    type: "get",
    url: "get_lead_type",
    datatype: "text/html",
    data:{
      staff_id:''
    },

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
    });