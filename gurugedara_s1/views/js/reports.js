/*=============================================
LOCAL STORAGE VARIABLE 
=============================================*/

if(localStorage.getItem("captureRange2") != null){

	$("#daterange-btn2 span").html(localStorage.getItem("captureRange2"));


}else{

	$("#daterange-btn2 span").html('<i class="fa fa-calendar"></i> Date Range')

}

/*=============================================
DATES RANGE
=============================================*/

$('#daterange-btn2').daterangepicker(
  {
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 days': [moment().subtract(29, 'days'), moment()],
      'this month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment(),
    endDate  : moment()
  },
  function (start, end) {
    $('#daterange-btn2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    var initialDate = start.format('YYYY-MM-DD');

    var finalDate = end.format('YYYY-MM-DD');

    var captureRange = $("#daterange-btn2 span").html();
   
   	localStorage.setItem("captureRange2", captureRange);
   	console.log("localStorage", localStorage);

   	window.location = "index.php?route=reports&initialDate="+initialDate+"&finalDate="+finalDate;

  }

)

/*=============================================
CANCEL DATES RANGE
=============================================*/

$(".daterangepicker.opensright .range_inputs .cancelBtn").on("click", function(){

	localStorage.removeItem("captureRange2");
	localStorage.clear();
	window.location = "reports";
})

/*=============================================
CAPTURE TODAY'S BUTTON
=============================================*/

$(".daterangepicker.opensright .ranges li").on("click", function(){

	var todayButton = $(this).attr("data-range-key");

	if(todayButton == "Today"){

		var d = new Date();
		
		// Format the date using String.prototype.padStart to ensure two digits
		var day = String(d.getDate()).padStart(2, '0');
		var month = String(d.getMonth() + 1).padStart(2, '0');
		var year = d.getFullYear();

		// Construct the date string
		var formattedDate = year + "-" + month + "-" + day;

		// Set initialDate and finalDate to the same value
		var initialDate = formattedDate;
		var finalDate = formattedDate;

		// Store the selected range in localStorage
		localStorage.setItem("captureRange2", "Today");

		// Redirect to the specified route with the selected date range
		window.location = "index.php?route=reports&initialDate=" + initialDate + "&finalDate=" + finalDate;

	}

});
