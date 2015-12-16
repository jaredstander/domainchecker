$(document).ready(function(){

  $('#faq_details').hide();

  $('#faq').click(function(){
    $('#faq_details').slideToggle();
  });

  // Prepare the array to hold the list of domains.
  // Done outside of the functions so they are accessible to all of the functions.
  var domainList = [];
  var totalDomains = 0;
  // Header for the CSV output of the domain query responses.
  var domainResponses = ["Domains,Status Code,Status Description,Redirect URL"];
  // domainResponses.push(csvHeaders);

  // Callback for file reader progress.
  function updateProgress(evt){
    document.getElementById('status-message').innerHTML += 'Loading file...<br/>';
    document.location = '#bottom';
  }

  // Code to execute when file reading is complete.
  function loaded(evt){
    document.getElementById('status-message').innerHTML += 'File loaded.<br/>';
    document.location = '#bottom';
    // Use regex to extract the domains. /regex/g (global) important to get all matches.
    domainList = evt.target.result.match(/[A-Z0-9-]{1,63}\.[A-Z]{2,5}/g);
    totalDomains = domainList.length;
    document.getElementById('status-message').innerHTML += domainList.length + ' domains found.<br/>';
    document.location = '#bottom';
    checkDomains(0, domainList);
  }

  function errorHandler(evt){
    document.getElementById('status-message').innerHTML += 'ERROR: ' + evt.target.error.name + '<br/>';
  }

  function checkDomains(i, domains){
    document.getElementById('status-message').innerHTML += 'Requesting ' + domains[i] + ' (' + (i + 1) + ' of ' + totalDomains + ') ...<br/>';
    document.location = '#bottom';
    jqXHR = $.ajax({
      type: "POST",
      url: 'check_domain.php',
      dataType: 'json',
      data: {'domain': domains[i]},
    });
    $.when($.ajax("check_domain.php")).done(function(){
      document.getElementById('status-message').innerHTML += 'Received response from ' + domains[i] + ' (' + (i + 1) + ' of ' + totalDomains + ')' + ' ...<br/>';
      document.location = '#bottom';
      domainResponses.push(jqXHR.responseText);
      i++;
      if(i < totalDomains){
        checkDomains(i, domains);
      }else{
        document.getElementById('status-message').innerHTML += 'Finished requesting domains ...<br/>';
        document.location = '#bottom';
        exportCSV(domainResponses);
      }
    });
  }

  function exportCSV(responseArray){
    document.getElementById('status-message').innerHTML += 'Exporting *.CSV file ...<br/>';
    document.location = '#bottom';

    var csvContent = "data:text/csv;charset=utf-8,";
    responseArray.forEach(function(dataString, index){
      csvContent += index < responseArray.length ? dataString + '\n' : dataString;
    });
    var encodedURI = encodeURI(csvContent);
    var link = document.createElement('A');
    link.setAttribute('href', encodedURI);
    d = new Date();
    link.setAttribute('download', 'Checked_Domains_' + (d.getMonth() + 1) + '-' + d.getDate() + '-' + d.getFullYear() + '.csv');
    document.getElementById('status-message').innerHTML += 'Complete!';
    document.location = '#bottom';
    document.body.appendChild(link);
    link.click();
    $('#file-submit-form').show();
  }

  // What to do when the file-submit-form is submitted.
  $(document).on('submit', '#file-submit-form', function(event) {
    // Prevent default necessary to keep the browser from navigating to a different page.
    event.preventDefault();
    $('#file-submit-form').hide();
    $('#status-message').text('');
    file = $('#file-select').eq(0)[0].files[0];
    if(file){
      var reader = new FileReader();
      reader.readAsText(file);
      reader.onprogress = updateProgress;
      reader.onload = loaded;
      reader.onerror = errorHandler;
    }else{
      $('#status-message').text('ERROR: No file selected.');
      $('#file-submit-form').show();
    }

  });

});
