$(document).ready(function() {
$('.dropdown.bootstrap-select.bs3').eq(2).width(60);//HR
$('.dropdown.bootstrap-select.bs3').eq(3).width(60);//MIN
$('.dropdown.bootstrap-select.bs3').eq(4).width(150);//TZ
});

$("#request_meeting").click(function(){
console.log(client_request_uri);	
$.post(client_request_uri, {
topic: $("#topic").val(),
agenda: $("#agenda").val(),
category: $("#category").val(),
project: $("#project").val(),
date: $('#date').val(),
hour: $("#hour").val(),
minutes: $('#minutes').val(),
timezone: $('#timezones').val(),
type: 'request',
}, function(result){
const obj = JSON.parse(result);	
if(obj.msg == 'success'){
$("#dateandtime").text(obj.dateandtime);
$("#allattendees").text(obj.allattendees);
$("#confirmation").text(obj.confirmation);
$('#meetingModal').modal('hide');
$('#meetingModalConfirm').modal('show');
}else{
$('#meetingModal').modal('hide');	
$('#meetingModalError').modal('show');
}
});
});

$('#category').on('change', function () {
  //ways to retrieve selected option and text outside handler
  console.log('Changed option value ' + this.value);
  console.log('Changed option text ' + $(this).find('option').filter(':selected').text());
  if(this.value == 'Existing Project'){
  $('#attach_project').show();
  }else{
  $('#attach_project').hide();	  
  }
});
$("#closeConfirm").click(function(){ 
var domain = location.protocol + '//' + location.host;
window.location.assign(domain + "/scheduled_meetings/clients/meeting_list")	;
});
