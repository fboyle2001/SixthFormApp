// All of the themes
// Opted to store them out of the settings object as they can then be changed
// without needing to change user's settings. Instead they will simply be
// correct and consistent.
//
// In images, the key needs to correspond with the elements ID so that this
// loader will auto-load the correct image.
var __themes = {
  light: {
    name: "light",
    stylesheet: "light.css",
    dark: false,
    icons: true,
    images: {
      login_logo: "light_mca.png"
    },
    statusbar: {
      background: "#FFFFFF"
    }
  },
  dark: {
    name: "dark",
    stylesheet: "dark.css",
    dark: true,
    icons: true,
    images: {
      login_logo: "dark_mca.png"
    },
    statusbar: {
      background: "#303030"
    }
  },
  sidebar_light: {
    name: "sidebar_light",
    stylesheet: "sidebar_light.css",
    dark: false,
    icons: true,
    images: {
      login_logo: "light_mca.png"
    },
    statusbar: {
      background: "#FFFFFF"
    }
  },
  sidebar_dark: {
    name: "sidebar_dark",
    stylesheet: "sidebar_dark.css",
    dark: true,
    icons: true,
    images: {
      login_logo: "dark_mca.png"
    },
    statusbar: {
      background: "#303030"
    }
  }
};

var __defaultSettings = {
  theme: "light",
  scalable: false,
  remember: {
    enabled: false,
    username: ""
  }
}

// Store them as they will be used by other functions
// try to hide them though as global variables in this could cause major issues
// as this file is always called
var __settings = loadSettings();

function getUserSettings() {
  return __settings;
}

function getUserTheme() {
  return __themes[getUserSettings().theme];
}

function amendSettings(settings) {
  var amended = false;

  if(settings.theme === undefined) {
    amended = true;
    settings.theme = "light";
  }

  if(settings.scalable === undefined) {
    amended = true;
    settings.scalable = false;
  }

  if(settings.remember === undefined) {
    amended = true;
    settings.remember = {
      enabled: false,
      username: ""
    };
  }

  return [settings, amended];
}

// Load the user's settings
function loadSettings() {
  var saved = Cookies.get("settings");

  if(saved == undefined) {
    // Default settings
    // Must stringify otherwise will error on first try
    saved = JSON.stringify(__defaultSettings);

    // Should last for their entire duration at sixth form (4 years to account
    // for issues caused by leap days)
    Cookies.set("settings", saved, {expires: 1460});
  } else {
    result = amendSettings(JSON.parse(saved));

    if(result[1] == true) {
      saved = result[0];
      Cookies.set("settings", saved, {expires: 1460});
    }
  }

  return JSON.parse(saved);
}

function produceStyleElement() {
  var theme = getUserTheme();

  var link = '<link href="./css/' + theme.stylesheet + '" rel="stylesheet" type="text/css">';
  return link;
}

function produceViewportElement() {
  var scalableValue = getUserSettings().scalable ? "yes" : "no";

  var tag = '<meta name="viewport" content="user-scalable=' + scalableValue + ', initial-scale=1, maximum-scale=2, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi", viewport-fit=cover/>';
  return tag;
}

function loadElements() {
  var theme = getUserTheme();
  var images = theme.images;

  // Set status bar colour
  // Only do it once to stop flickering
  if(Cookies.get("status_bar_changed") !== true) {
    StatusBar.overlaysWebView(false);
    StatusBar.backgroundColorByHexString(theme.statusbar.background);
    StatusBar.show();

    if(theme.dark == true) {
      StatusBar.styleBlackOpaque();
    } else {
      StatusBar.styleDefault();
    }

    Cookies.set("status_bar_changed", true);
  }

  for(var key in images) {
    // Element exists on this page
    if(document.getElementById(key) != null) {
      $("#" + key).attr("src", "./images/" + images[key]);
    }
  }

  if(theme.icons == true) {
    // Load the images

    $("#nav_home").text("");

    if($("#nav_home").hasClass("current") && theme.dark == false) {
      $("#nav_home").append('<img width="32" height="32" src="./images/nav_home_clicked.png">');
    } else {
      $("#nav_home").append('<img width="32" height="32" src="./images/nav_home.png">');
    }

    $("#nav_documents").text("");

    if($("#nav_documents").hasClass("current") && theme.dark == false) {
      $("#nav_documents").append('<img width="32" height="32" src="./images/nav_documents_clicked.png">');
    } else {
      $("#nav_documents").append('<img width="32" height="32" src="./images/nav_documents.png">');
    }

    $("#nav_links").text("");

    if($("#nav_links").hasClass("current") && theme.dark == false) {
      $("#nav_links").append('<img width="32" height="32" src="./images/nav_links_clicked.png">');
    } else {
      $("#nav_links").append('<img width="32" height="32" src="./images/nav_links.png">');
    }

    $("#nav_settings").text("");

    if($("#nav_settings").hasClass("current") && theme.dark == false) {
      $("#nav_settings").append('<img width="32" height="32" src="./images/nav_settings_clicked.png">');
    } else {
      $("#nav_settings").append('<img width="32" height="32" src="./images/nav_settings.png">');
    }

    if($("#nav_calendar").hasClass("current") && theme.dark == false) {
      $("#nav_calendar").append('<img width="32" height="32" src="./images/nav_calendar_clicked.png">');
    } else {
      $("#nav_calendar").append('<img width="32" height="32" src="./images/nav_calendar.png">');
    }
  } else {
    $("#experimental_calendar").remove();
  }
}

$("head").append(produceStyleElement());
$("head").append(produceViewportElement());

document.addEventListener("deviceready", function() {
  loadElements();
}, false);
