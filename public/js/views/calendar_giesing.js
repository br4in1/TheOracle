$(function(){

  $.ajax({
    url: Routing.generate('giesing_predictions'),
  }).then(returned => {

    returned = JSON.parse(returned);
    var Events = [];

    for(var i=0;i<returned.labels.length;i++){
      Events.push({
        title: returned.numbers[i],
        start: returned.labels[i]
      });
    }

    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month'
      },
      defaultDate: '2019-05-25',
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: Events
    });
  });
});
