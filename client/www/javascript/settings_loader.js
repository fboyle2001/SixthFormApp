function produceStyleElement() {
  var stylesheet = "light.css";

  if(Cookies.get("stylesheet") !== undefined) {
    stylesheet = Cookies.get("stylesheet");
  }

  var link = '<link href="./css/' + stylesheet + '" rel="stylesheet" type="text/css">';
  return link;
}

function produceViewportElement() {
  var scalable = "no";

  if(Cookies.get("zoom") !== undefined) {
    scalable = Cookies.get("zoom");
  }

  var tag = '<meta name="viewport" content="user-scalable=' + scalable + ', initial-scale=1, maximum-scale=2, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>';
  return tag;
}

$("head").append(produceStyleElement());
$("head").append(produceViewportElement());
