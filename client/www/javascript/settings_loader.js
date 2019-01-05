function produceStyleElement() {
  var stylesheet = "dark.css";

  if(Cookies.get("stylesheet") !== undefined) {
    stylesheet = Cookies.get("stylesheet");
  }

  var link = '<link href="./css/' + stylesheet + '" rel="stylesheet" type="text/css">';
  return link;
}

$("head").append(produceStyleElement());
