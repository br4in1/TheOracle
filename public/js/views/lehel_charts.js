function plot_dnog(Type = 1){
  $.ajax({
    url: Routing.generate('get_Lehel_DNOG', { type: Type }),
  }).then(returned => {

    $('#number_of_guests').remove(); // this is my <canvas> element
    $('#dnog_container').append('<canvas id="number_of_guests"><canvas>');
    returned = JSON.parse(returned);
    var data = {
      //labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S'],
      labels: returned.labels,
      datasets: [
        {
          label: 'Number Of Guests',
          backgroundColor: 'transparent',
          borderColor: $.brandSuccess,
          pointHoverBackgroundColor: '#fff',
          borderWidth: 2,
          data: returned.numbers
        }
      ]
    };

    var options = {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines: {
            drawOnChartArea: false,
          }
        }],
        yAxes: [{
          ticks: {
            beginAtZero: true,
            maxTicksLimit: 5,
            stepSize: Math.ceil(returned.numbers.reduce(function(a, b) {
              return Math.max(a, b);
            })/3),
            max: returned.numbers.reduce(function(a, b) {
              return Math.max(a, b);
            })*1.3
          }
        }]
      },
      elements: {
        point: {
          radius: 0,
          hitRadius: 10,
          hoverRadius: 4,
          hoverBorderWidth: 3,
        }
      },
    };
    var ctx = $('#number_of_guests');
    var mainChart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: options
    });
  })
}

function plot_dnog2(){
  $.ajax({
    url: Routing.generate('get_Lehel_DNOG_ByMonth'),
  }).then(returned => {

    $('#dnog_by_monthName').remove(); // this is my <canvas> element
    $('#dnog_container2').append('<canvas id="dnog_by_monthName"><canvas>');
    returned = JSON.parse(returned);
    var data = {
      //labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S'],
      labels: returned.labels,
      datasets: [
        {
          label: "Number Of Guests By Month Name",
          backgroundColor : 'rgba(220,220,220,0.5)',
          borderColor : 'rgba(220,220,220,0.8)',
          highlightFill: 'rgba(220,220,220,0.75)',
          highlightStroke: 'rgba(220,220,220,1)',
          data : returned.numbers
        }
      ]
    };
    var ctx = $('#dnog_by_monthName');
    var mainChart = new Chart(ctx, {
      type: 'bar',
      data: data,
      options: {
        responsive: true
      }
    });
  })
}

function plot_dnog3(){
  $.ajax({
    url: Routing.generate('get_Lehel_DNOG_ByDayOfWeek'),
  }).then(returned => {

    $('#dnog_by_DayOfWeek').remove(); // this is my <canvas> element
    $('#dnog_container3').append('<canvas id="dnog_by_DayOfWeek"><canvas>');
    returned = JSON.parse(returned);
    var data = {
      //labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S', 'M', 'T', 'W', 'T', 'F', 'S', 'S'],
      labels: returned.labels,
      datasets: [
        {
          label: "Number Of Guests By Day Of Week",
          backgroundColor : 'rgba(220,220,220,0.5)',
          borderColor : 'pink',
          highlightFill: 'pink',
          highlightStroke: 'pink',
          data : returned.numbers
        }
      ]
    };
    var ctx = $('#dnog_by_DayOfWeek');
    var mainChart = new Chart(ctx, {
      type: 'bar',
      data: data,
      options: {
        responsive: true
      }
    });
  })
}

$(document).ready(function () {
  $('#option1').click(function (e) {
    e.preventDefault();
    plot_dnog(1);
  });
  $('#option2').click(function (e) {
    plot_dnog(2);
  });
  $('#option3').click(function (e) {
    plot_dnog(3);
  });
  plot_dnog(1);
  plot_dnog2();
  plot_dnog3();
});

