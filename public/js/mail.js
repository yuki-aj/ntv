// function sendmail() {
//   var titles = document.getElementById('title').value;
//   var informations = document.getElementById('information').value;
//   var names = document.getElementById('name').value;
//   var mails = document.getElementById('mail').value;
//   var to_u_id = document.getElementById('to_u_id').value;
//   let thanksmessage = document.getElementById('thanksmessage');
//   thanksmessage.style.display ="none";
//   let errormessage = document.getElementById('errormessage');
//   errormessage.style.display ="none";

//   var title       = titles.replace(/[\t\s ]/g, '');
//   var information = informations.replace(/[\t\s ]/g, '');
//   var name        = names.replace(/[\t\s ]/g, '');
//   var mail        = mails.replace(/[\t\s ]/g, '');
//   //半角、全角空白、改行を空にする
//   if(title == '' || information == '' || name == '' || mail == ''){
//     alert("@lang('messages.invalid-entry')");
//     return false;
//   }
//   $.ajax({
//     type: 'POST',
//     dataType: 'html',
//     url: '/contact',
//     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//     dataType:'text',
//     data: {
//       'title':title,
//       'information':information,
//       'name':name,
//       'mail':mail,
//       'to_u_id':to_u_id,
//     },
//     success: function (data) {
//       let thanksmessage = document.getElementById('thanksmessage');
//       thanksmessage.style.display = "block";
//       let none = document.getElementById('none');
//       none.style.display= "none";
//     },
//     error: function () {
//       let errormessage = document.getElementById('errormessage');
//       errormessage.style.display = "block";
//     }
//   })
//   }
