 $("#donutChartMonth").on("change", function() {
   var month = $(this).val();
    donutChartPie(month);
    MilestoneTypeBarChart(month)
    // Add more function calls here as needed
  });
function donutChartPie(month) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
  $.ajax({
    type: "post",
    url: "get_task_chart",
    data: {
      month:month,
    },
    success: function (data) {
      var jsonData = data;
      console.log(jsonData);
       var label1 = jsonData.label1;
      var label_id1 = jsonData.label_id1;
      var data1 = jsonData.data1;

  
      var $primary = "#5A8DEE",
        $success = "#39DA8A",
        $danger = "#FF5B5C",
        $warning = "#FDAC41",
        $info = "#00CFDD",
        $label_color = "#475F7B",
        $grid_line_color = "#B70404";

      var themeColors = [
        $primary,
        $success,
        $danger,
        $warning,
        $info,
        $label_color,
        $grid_line_color,
      ];

      var donutChartContent = document.getElementById("donutChartContentTaskStatus");
      donutChartContent.innerHTML = "&nbsp;";
      $("#donutChartContentTaskStatus").append(
        '<div id="donut-chart-TaskStatus" class="d-flex justify-content-center"></div>'
      );

      // var pieLabels = $("#donutChartContent").find(".apexcharts-pie-label");
      var donutChartOptions = {
        chart: {
          type: "donut",
          height: 250,
        },
        colors: themeColors,
        series: data1,
        labels: label1,
        dataLabels: {
          enabled: true,
          formatter: function (val, opts) {
            var seriesIndex = opts.seriesIndex;
            var sum = opts.w.globals.series[seriesIndex];
            return sum;
          },
        },
        datasets: [
          {
            data: data1,
            backgroundColor: themeColors,
          },
        ],
        legend: {
          itemMargin: {
            horizontal: 2,
          },
        },
        responsive: [
          {
            breakpoint: 576,
            options: {
              chart: {
                width: 250,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      };
      var donutChart = new ApexCharts(
        document.querySelector("#donut-chart-TaskStatus"),
        donutChartOptions
      );

      donutChart.render();
      $("#donut-chart-TaskStatus .apexcharts-pie-series").on("click", function () {
        var seriesIndex1 = $(this).attr("data:realIndex");
        var task_status_id = jsonData.label_id1[seriesIndex1];
        console.log(task_status_id);
        window.open("task_status_details-" + task_status_id ,"_self");
      });
    },
    error: function (data) {
      // Handle error
    },
  });
}

 function generateRandomHexColor() {
  // Create a random number between 0 and 0xffffff (white)
  const randomColor = Math.floor(Math.random() * 16777215);
  // Convert the number to a hexadecimal string and pad with zeros if necessary
  return "#" + randomColor.toString(16).padStart(6, '0');
}

var barChart;
var TypesArr_data;
function MilestoneTypeBarChart(month) {
   var barchartOptions =  {
               legend: { display: false },
                elements: {
      rectangle: {
        borderWidth: 2,
        borderSkipped: 'left'
      }
    },
    responsive: true,
    maintainAspectRatio: false,
    responsiveAnimationDuration: 500,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                hover: {
                onHover: function(e) {
                }
            },
               title: {
      display: true,
      text: 'Type wise'
    },
            };

  const randomRgbColor = () => {
      let r = Math.floor(Math.random() * 256); 
      let g = Math.floor(Math.random() * 256);
      let b = Math.floor(Math.random() * 256); 
      return 'rgb(' + r + ',' + g + ',' + b + ')';
    };
   
 var barchartData = {
            labels:  [],
            datasets: [{
                label: '',
                data: [],
                backgroundColor: [],
                // borderColor: [
                //     'rgba(255, 99, 132, 1)',
                //     'rgba(54, 162, 235, 1)',
                //     'rgba(255, 206, 86, 1)',
                // ],
                // transparent
                borderWidth: 1
            }]
        };

        if (barChart) {
          barChart.destroy();
        }
    var barChartconfig = {
            type: 'bar',
            data: barchartData,
            options: barchartOptions
        };
        var ctx = document.getElementById('bar_chart').getContext('2d');
         barChart = new Chart(ctx, barChartconfig);

          $.ajax({
            type: "get",
            url: "get_type_chart",
             data: {
              month:month,
            },
            success: function (res) {
               TypesArr_data = res.label;
               updateData(res);
            },
            error: function (error) {
              console.log(error);
            },
          });
         function updateData(res) { 
            barchartData.labels = res.label;
            barchartData.datasets[0].data = res.data;  
             let colorArr = [];
              for(var i=0 ;i<res.label.length;i++){
                  colorArr.push(randomRgbColor());
              }
              barchartData.datasets[0].backgroundColor =colorArr; 
            // generateRandomHexColor()
            barChart.update(); 
        }


}

      // Handle specific bar click
      $('#bar_chart').click(function(event) {
        var activePoints = barChart.getElementsAtEvent(event);
        if (activePoints.length > 0) {
          var clickedElement = activePoints[0];
          var clickedIndex = clickedElement._index;
          var typeName = TypesArr_data[clickedIndex];
          console.log("Clicked bar:", typeName);
          window.open("task_type_details-" + typeName ,"_self");
        }
      });

function donutChartClickDetail(task_status_id) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  if(task_status_id != ''){
    $.ajax({
      type: "post",
      url: "get_task_status_list",
      data: {
        task_status_id:task_status_id,
      },
      success: function (res) {
        $('.task_status_detail').empty().html(res);
      },
      error: function (error) {
        // Handle error
        console.log(error);
      },
    });
  }
}
function barChartClickDetail(task_type) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  if(task_type != ''){
    $.ajax({
      type: "post",
      url: "get_task_type_list",
      data: {
        task_type:task_type
      },
      success: function (res) {
        $('.task_type_detail').empty().html(res);
      },
      error: function (error) {
        // Handle error
        console.log(error);
      },
    });
  }
}