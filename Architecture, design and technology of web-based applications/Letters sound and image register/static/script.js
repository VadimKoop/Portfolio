$(function() {

  var $searchText = $('#search-text');
  var $searchButton = $('#search-button');
  var $suggestionList = $('#suggestion-list');
  var $newAudio = $('#new');

  var $word = $('#word');
  var $audio = $('#audio');
  var $uploadWord = $('#upload-word');
  var $uploadFile = $('#upload-file');
  var $uploadNote = $('#upload-note');
  var $uploadButton = $('#upload-button');

  var suggestions = [];

  function encodeBase64andThen(file, cb) {
    let reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function() {
      cb(reader.result);
    };
    reader.onerror = function(err) {
      console.log('File error: ', err);
    };
  }

  $searchText.on('keypress', function(e) {
    if(e.keyCode == 13) {
      if($('.suggestion:visible').length == 1) {
        $searchText.val($('.suggestion:visible').text());
        $searchButton.click();
      }
    }
  });

  function clearUploadForm() {
    $uploadWord.val('');
    $uploadFile.val('');
  }

  function handleSuggestSuccess(data, status, xhr) {
    console.log('List retrieved:', data);
    suggestions = data;
    $suggestionList.empty();
    data.sort();
    for(var i = 0; i < data.length; i++) {
      $suggestionList.append(
        $(`<p class='suggestion'>${data[i].toLowerCase()}</p>`)
          .click(function() {
            console.log('Suggestion clicked');
            $searchText.val($(this).text());
            $searchButton.click();
          })
      );
    }
    filterSuggestions();
  }

  function handleSuggestError(xhr, status, err) {
    console.log('Failed to retrieve word list', err);
    filterSuggestions();
  }

  function filterSuggestions() {
    let search = $searchText.val().toLowerCase();
    $('.suggestion').each(function(i, child) {
      let text = $(this).text().toLowerCase();
      if(text.indexOf(search) == 0) {
        $(child).show();
      } else {
        $(child).hide();
      }
    });
  }

  function loadSuggestions() {
      $.ajax({
        method: 'post',
        url: '/suggest',
        data: {},
        success: handleSuggestSuccess,
        error: handleSuggestError,
        accepts: {
          json: 'application/json'
        },
        dataType: 'json'
      });
  }

  window.setInterval(loadSuggestions, 3000);
  loadSuggestions();

  $searchText.on('input', filterSuggestions);
  $searchText.focusin(function() {
    filterSuggestions();
    $suggestionList.show();
    $searchText.select();
  });
  $searchText.focusout(function() {
    setTimeout(function() {
      $suggestionList.hide();
    }, 50);
  });

  $uploadButton.click(function() {
    function handleUploadSuccess(data, status, xhr) {
      console.log('File successfully uploaded!');
      $searchText.val($uploadWord.val());
      clearUploadForm();
      loadSuggestions();
      $searchButton.click();
    }

    function handleUploadError(xhr, status, err) {
      console.log('Failed to upload file: ', err);
    }

    function uploadFile(b64file) {
      $.ajax({
        method: 'post',
        url: '/upload',
        data: {
          text: $uploadWord.val(),
          file: b64file
        },
        success: handleUploadSuccess,
        error: handleUploadError,
        accepts: {
          json: 'application/json'
        },
        dataType: 'json'
      });
    }

    if($.inArray($uploadWord.val().toLowerCase(), suggestions) == -1
        || confirm('Existing audio will be replaced! Are you sure?')) {
      let file = document.getElementById('upload-file').files[0];
      encodeBase64andThen(file, uploadFile);
    }

  });

  function clearDetails() {
    $word.text('');
    $audio.empty();
  }

  function switchToNewMode() {
    clearDetails();
    clearUploadForm();
    $word.hide();
    $uploadWord.show();
    $('#text-replace').hide();
    $('#text-new').show();
    $('#new').hide();
  }

  function switchToReplaceMode() {
    $word.show();
    $uploadWord.hide();
    $('#text-replace').show();
    $('#text-new').hide();
    $('#new').show();
  }

  $searchButton.click(function() {
    function handleSearchSuccess(data, status, xhr) {
      console.log('File found: ', data);
      clearDetails();
      $word.text(data.text);
      var audio = new Audio(data.file);
      $uploadWord.val(data.text);
      $audio.append(
        $(audio).attr({
          'controls': true
        })
      );
      switchToReplaceMode();
    }

    function handleSearchError(xhr, status, err) {
      console.log('Failed to find file: ', err);
      clearDetails();
      switchToNewMode();
    }

    $.ajax({
      method: 'post',
      url: '/details',
      data: {
        text: $searchText.val()
      },
      success: handleSearchSuccess,
      error: handleSearchError,
      accepts: {
        json: 'application/json'
      },
      dataType: 'json'
    })
  });

  $newAudio.click(function() {
    switchToNewMode();
  });

});
