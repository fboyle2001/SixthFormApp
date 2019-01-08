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
    images: {
      login_logo: "light_mca.png"
    }
  },
  dark: {
    name: "dark",
    stylesheet: "dark.css",
    images: {
      login_logo: "dark_mca.png"
    }
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

// Load the user's settings
function loadSettings() {
  var saved = Cookies.get("settings");

  if(saved == undefined) {
    // Default settings
    // Must stringify otherwise will error on first try

    saved = JSON.stringify({
      theme: "light",
      scalable: false
    });

    // Should last for their entire duration at sixth form (4 years to account
    // for issues caused by leap days)
    Cookies.set("settings", saved, {expires: 1460});
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

  var tag = '<meta name="viewport" content="user-scalable=' + scalableValue + ', initial-scale=1, maximum-scale=2, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>';
  return tag;
}

function loadElements() {
  var theme = getUserTheme();
  var images = theme.images;

  for(var key in images) {
    // Element exists on this page
    if(document.getElementById(key) != null) {
      $("#" + key).attr("src", "./images/" + images[key]);
    }
  }
}

$("head").append(produceStyleElement());
$("head").append(produceViewportElement());

$(document).ready(function() {
  loadElements();
});
