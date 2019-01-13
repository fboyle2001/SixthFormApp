function loadPage() {
  var info = getDebugInfo();

  // Display the information
  $("#debug_info").text(JSON.stringify(info, null, 2));

  // Hide or show the information
  $("#debug_toggle").click(function (e) {
    e.preventDefault();

    if($("#debug_info").is(":hidden")) {
      $("#debug_info").slideDown();
    } else {
      $("#debug_info").slideUp();
    }
  })

  // Copy the debug information
  $("#debug_copy").click(function (e) {
    e.preventDefault();
    
    cordova.plugins.clipboard.copy(JSON.stringify(info), function(s) {
      sendAlert("Copied");
    }, function(s) {
      sendAlert("Unable to copy")
    });
  });
}

function getDebugInfo() {
  var data = {};

  data.settings = getUserSettings();

  var cache = {};

  $.each(Object.keys(localStorage), function(_, key) {
    cache[key] = JSON.parse(localStorage.getItem(key));
  });

  data.cache = cache;
  data.cookies = Cookies.get();
  data.device = {
    platform: window.device.platform,
    version: window.device.version,
    cordova: window.device.cordova,
    model: window.device.model,
    manufacturer: window.device.manufacturer
  };

  data.connection = {
    type: navigator.connection.type
  };

  data.screen = {
    innerWidth: window.innerWidth,
    innerHeight: window.innerHeight
  };

  return data;
}
