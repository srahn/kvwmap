registerEventHandler = function() {
 // console.log('registerEventHandler');

  $('input[data-field]').map(function(i, field) {
    $(field).on(
      'change',
      writeSwitchedOn
    )
  });

  $('.th-inner.sortable').map(function(i, field) {
    $(field).on(
      'click',
      writeSortDirection
    )
  })
}

writeSwitchedOn = function(evt) {
  // console.log('writeSwitchedOn');
  var target = $(evt.target),
      params = {
        layer_id: getSelectedLayerId(),
        attributename: target.attr('data-field'),
        switched_on: (target.prop('checked') ? 1 : 0)
      },
      url = 'index.php?go=write_layer_attributes2rolle';

  $.ajax({
    url: url,
    data: params,
    success: function(response) {
      //console.log('writeAttributeSetting success: ', response);
      debug_r = response;
      if (response.success) {
        message([{ type: 'notice', msg: 'Einstellung gespeichert'}], 1000, 500, '13%');
      }
      else {
        message([{ type: 'error', msg: response.err_msg}], 1000, 500, '13%');
      }
    },
    error: function(err) {
      //console.log('writeAttributeSetting error: %', err.responseText);
      message([{ type: 'error', msg: 'Fehler bei der Abfrage unter der URL: ' + url + ' Parameter: ' + JSON.stringify(params)}], 1000, 500, '13%');
    }
  });
}

writeSortDirection = function(evt) {
  // console.log('writeSortDirection');
  var target = $(evt.target),
      sortDirection,
      params = {
        layer_id: getSelectedLayerId(),
        attributename: target.parent().attr('data-field'),
        sort_order: 1,
        sort_direction: (target.hasClass('asc') ? 'desc' : (target.hasClass('desc') ? 'asc' : 'desc')),
        sort_other: false
      },
      url = 'index.php?go=write_layer_attributes2rolle';

  // console.log('send attribute settings: %o to url: %s', params, url);

  $.ajax({
    url: url,
    data: params,
    success: function(response) {
      //console.log('writeSortDirection success: ', response);
      if (response.success) {
        message([{ type: 'notice', msg: 'Einstellung gespeichert'}], 1000, 500, '13%');
      }
      else {
        message([{ type: 'error', msg: response[1]}], 1000, 500, '13%');
      }
    },
    error: function(err) {
      //console.log('writeSortDirection error: %', err)
      message([{ type: 'error', msg: 'Fehler bei der Abfrage unter der URL: ' + url + ' Parameter: ' + JSON.stringify(params) + '<p>Rückgabe:<br>' + err.responseText}], 1000, 500, '13%');
    }
  });

}

getSelectedLayerId = function() {
  return $('table[data-query-params]').attr('data-query-params').split('&').filter(function(part) { return part.split('=')[0] == 'selected_layer_id'; })[0].split('=')[1];
}